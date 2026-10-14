(function (window, document) {
    'use strict';

    var storageKey = 'cookie_consent';
    var consentVersion = '1.0';
    var categories = ['analytics', 'functional', 'marketing'];
    var consent = readConsent();
    var pendingLocationRequests = [];
    var originalGeolocation = window.navigator && window.navigator.geolocation;

    function emptyConsent() {
        return {
            necessary: true,
            analytics: false,
            functional: false,
            marketing: false,
            timestamp: null,
            version: consentVersion
        };
    }

    function readJson(value) {
        try {
            return JSON.parse(value);
        } catch (error) {
            return null;
        }
    }

    function readCookie() {
        var item = document.cookie.split('; ').find(function (cookie) {
            return cookie.indexOf(storageKey + '=') === 0;
        });
        return item ? decodeURIComponent(item.substring(storageKey.length + 1)) : null;
    }

    function readConsent() {
        var saved = null;
        try {
            saved = readJson(window.localStorage.getItem(storageKey));
        } catch (error) {
            saved = null;
        }
        if (!saved) {
            saved = readJson(readCookie());
        }
        if (!saved || saved.version !== consentVersion) {
            return emptyConsent();
        }
        return {
            necessary: true,
            analytics: saved.analytics === true,
            functional: saved.functional === true,
            marketing: saved.marketing === true,
            timestamp: saved.timestamp || null,
            version: consentVersion
        };
    }

    function has(category) {
        return category === 'necessary' || consent[category] === true;
    }

    function persistConsent(nextConsent) {
        consent = {
            necessary: true,
            analytics: nextConsent.analytics === true,
            functional: nextConsent.functional === true,
            marketing: nextConsent.marketing === true,
            timestamp: new Date().toISOString(),
            version: consentVersion
        };
        var value = JSON.stringify(consent);
        try {
            window.localStorage.setItem(storageKey, value);
        } catch (error) {
            // The first-party cookie remains a fallback when browser storage is unavailable.
        }
        document.cookie = storageKey + '=' + encodeURIComponent(value) + '; Max-Age=31536000; Path=/; SameSite=Lax' + (window.location.protocol === 'https:' ? '; Secure' : '');
    }

    function loadScript(url, category, attributes) {
        if (!has(category) || !url || document.querySelector('script[data-consent-loaded-url="' + cssEscape(url) + '"]')) {
            return null;
        }
        var script = document.createElement('script');
        script.src = url;
        script.async = true;
        script.dataset.consentLoadedUrl = url;
        if (attributes) {
            Object.keys(attributes).forEach(function (name) {
                script.setAttribute(name, attributes[name]);
            });
        }
        document.head.appendChild(script);
        return script;
    }

    function cssEscape(value) {
        return window.CSS && window.CSS.escape ? window.CSS.escape(value) : value.replace(/["\\]/g, '\\$&');
    }

    function activateScript(element) {
        if (element.dataset.consentLoaded === 'true') {
            return;
        }
        var script = document.createElement('script');
        Array.prototype.forEach.call(element.attributes, function (attribute) {
            if (attribute.name === 'type' || attribute.name === 'data-consent-category' || attribute.name === 'data-consent-src' || attribute.name === 'data-consent-loaded') {
                return;
            }
            script.setAttribute(attribute.name, attribute.value);
        });
        script.type = 'text/javascript';
        if (element.dataset.consentSrc) {
            script.src = element.dataset.consentSrc;
        } else {
            script.textContent = element.textContent;
        }
        element.dataset.consentLoaded = 'true';
        document.head.appendChild(script);
    }

    function activateEmbeds(category) {
        document.querySelectorAll('iframe[data-consent-category="' + category + '"][data-consent-src]').forEach(function (frame) {
            if (!frame.getAttribute('src')) {
                frame.setAttribute('src', frame.dataset.consentSrc);
                frame.dataset.consentLoaded = 'true';
            }
        });
    }

    function activateCategory(category) {
        document.querySelectorAll('script[data-consent-category="' + category + '"]').forEach(activateScript);
        activateEmbeds(category);
        document.dispatchEvent(new CustomEvent('cookie-consent:category-enabled', { detail: { category: category } }));
        if (category === 'analytics') {
            activateAnalytics();
        }
        if (category === 'functional') {
            flushLocationRequests();
        }
    }

    function activateAnalytics() {
        var meta = document.querySelector('meta[name="google-analytics-id"]');
        var measurementId = meta && meta.content;
        if (!measurementId || window.googleAnalyticsStarted) {
            return;
        }
        window.googleAnalyticsStarted = true;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', measurementId, { anonymize_ip: true });
        loadScript('https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId), 'analytics');
    }

    function removeCookie(name) {
        document.cookie = name + '=; Max-Age=0; Path=/; SameSite=Lax';
        var hostname = window.location.hostname.split('.');
        if (hostname.length > 1) {
            document.cookie = name + '=; Max-Age=0; Path=/; Domain=.' + hostname.slice(-2).join('.') + '; SameSite=Lax';
        }
    }

    function clearOptionalState(previous, next) {
        if (previous.analytics && !next.analytics) {
            ['_ga', '_gid', '_gat', '_gcl_au'].forEach(removeCookie);
            if (typeof window.gtag === 'function') {
                window.gtag('consent', 'update', { analytics_storage: 'denied', ad_storage: 'denied' });
            }
        }
        if (previous.functional && !next.functional) {
            ['googtrans', 'googtrans2', 'latitude', 'longitude'].forEach(removeCookie);
        }
    }

    function openPreferences() {
        var root = document.getElementById('cookie-consent');
        if (!root) {
            return;
        }
        root.hidden = false;
        root.querySelector('.cookie-consent__banner').hidden = true;
        root.querySelector('.cookie-consent__preferences').hidden = false;
        categories.forEach(function (category) {
            var input = root.querySelector('[data-consent-category="' + category + '"]');
            if (input) {
                input.checked = has(category);
            }
        });
        root.querySelector('[data-consent-action="save"]').focus();
    }

    function closePreferences() {
        var root = document.getElementById('cookie-consent');
        if (root) {
            root.querySelector('.cookie-consent__preferences').hidden = true;
            root.hidden = true;
        }
    }

    function updateUI() {
        var root = document.getElementById('cookie-consent');
        if (!root) {
            return;
        }
        if (!consent.timestamp) {
            root.hidden = false;
            root.querySelector('.cookie-consent__banner').hidden = false;
            root.querySelector('.cookie-consent__preferences').hidden = true;
        } else {
            root.hidden = true;
        }
    }

    function reloadAfterConsent() {
        setTimeout(function () {
            window.location.reload();
        }, 2000);
    }

    function savePreferences(nextConsent) {
        var previous = consent;
        persistConsent(nextConsent);
        clearOptionalState(previous, consent);
        closePreferences();
        categories.forEach(function (category) {
            if (has(category)) {
                activateCategory(category);
            }
        });
        document.dispatchEvent(new CustomEvent('cookie-consent:changed', { detail: { consent: getConsent() } }));
    }

    function getConsent() {
        return Object.assign({}, consent);
    }

    function flushLocationRequests() {
        if (!originalGeolocation) {
            return;
        }
        pendingLocationRequests.splice(0).forEach(function (request) {
            originalGeolocation.getCurrentPosition(request.success, request.error, request.options);
        });
    }

    function installLocationGate() {
        if (!originalGeolocation || !window.navigator) {
            return;
        }
        var gated = {
            getCurrentPosition: function (success, error, options) {
                if (has('functional')) {
                    return originalGeolocation.getCurrentPosition(success, error, options);
                }
                pendingLocationRequests.push({ success: success, error: error, options: options });
            },
            watchPosition: function (success, error, options) {
                if (has('functional')) {
                    return originalGeolocation.watchPosition(success, error, options);
                }
                pendingLocationRequests.push({ success: success, error: error, options: options, watch: true });
                return -1;
            },
            clearWatch: function (watchId) {
                if (originalGeolocation.clearWatch && watchId >= 0) {
                    originalGeolocation.clearWatch(watchId);
                }
            }
        };
        try {
            Object.defineProperty(window.navigator, 'geolocation', { configurable: true, value: gated });
        } catch (error) {
            window.navigator.geolocation = gated;
        }
    }

    function initialize() {
        var root = document.getElementById('cookie-consent');
        if (!root) {
            return;
        }
        root.addEventListener('click', function (event) {
            var button = event.target.closest('[data-consent-action]');
            if (!button) {
                return;
            }
            var action = button.dataset.consentAction;
            if (action === 'accept-all') {
                savePreferences({ analytics: true, functional: true, marketing: true });
                reloadAfterConsent();
            } else if (action === 'reject') {
                savePreferences({ analytics: false, functional: false, marketing: false });
                reloadAfterConsent();
            } else if (action === 'manage') {
                openPreferences();
            } else if (action === 'close') {
                if (consent.timestamp) {
                    closePreferences();
                } else {
                    root.querySelector('.cookie-consent__preferences').hidden = true;
                    root.querySelector('.cookie-consent__banner').hidden = false;
                }
            } else if (action === 'save') {
                var nextConsent = {};
                categories.forEach(function (category) {
                    var input = root.querySelector('[data-consent-category="' + category + '"]');
                    nextConsent[category] = !!(input && input.checked);
                });
                savePreferences(nextConsent);
                reloadAfterConsent();
            }
        });
        document.querySelectorAll('[data-cookie-settings]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                root.hidden = false;
                openPreferences();
            });
        });
        updateUI();
        categories.forEach(function (category) {
            if (has(category)) {
                activateCategory(category);
            }
        });
    }

    window.gtag = function () {};
    installLocationGate();
    window.CookieConsent = {
        has: has,
        getConsent: getConsent,
        loadScript: loadScript,
        openPreferences: openPreferences
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
}(window, document));
