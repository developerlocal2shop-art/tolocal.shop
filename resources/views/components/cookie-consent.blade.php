@php($consentIsSwedish = app()->getLocale() === 'sv')

<style>
    [hidden] { display: none !important; }
    .cookie-consent {
        position: fixed;
        z-index: 10050;
        right: 16px;
        bottom: 16px;
        left: 16px;
        max-width: 980px;
        margin: 0 auto;
        color: #1d2733;
        font: 15px/1.5 Arial, sans-serif;
    }
    .cookie-consent__banner,
    .cookie-consent__preferences {
        border: 1px solid #9aa6b2;
        border-radius: 6px;
        background: #fff;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .24);
        padding: 18px 20px;
        color:#2a2424;
    }
    .cookie-consent__preferences span {
        color: #2a2424 !important;
    }
    .cookie-consent__banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    .cookie-consent__copy { flex: 1 1 300px; }
    .cookie-consent__copy h2,
    .cookie-consent__preferences h2 {
        margin: 0 0 5px;
        font-size: 19px;
        line-height: 1.3;
    }
    .cookie-consent__copy p { margin: 0; }
    .cookie-consent__copy a { color: #174b73; text-decoration: underline; }
    .cookie-consent__actions {
        display: flex;
        flex: 0 1 510px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .cookie-consent button {
        min-height: 42px;
        border: 2px solid #174b73;
        border-radius: 4px;
        background: #fff;
        color: #174b73;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
        padding: 8px 12px;
    }
    .cookie-consent__actions button { flex: 1 1 145px; }
    .cookie-consent button:hover,
    .cookie-consent button:focus-visible { outline: 3px solid #f5a623; outline-offset: 2px; }
    .cookie-consent button[data-consent-action="accept-all"] {
        background: #174b73;
        color: #fff;
    }
    .cookie-consent__preferences {
        position: fixed;
        top: 50%;
        left: 50%;
        box-sizing: border-box;
        width: min(520px, calc(100vw - 32px));
        max-height: min(560px, calc(100vh - 32px));
        overflow-x: hidden;
        overflow-y: auto;
        transform: translate(-50%, -50%);
    }
    .cookie-consent__backdrop {
        position: fixed;
        z-index: -1;
        inset: -5vh -100vw;
        background: rgb(143 143 143 / 62%);
    }
    .cookie-consent__preferences > p { margin: 0 0 14px; }
    .cookie-consent__option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-top: 1px solid #d7dde2;
        padding: 8px 0;
    }
    .cookie-consent__option input { width: 20px; height: 20px; flex: 0 0 auto; margin: 2px 0 0; }
    .cookie-consent__option strong { display: block; }
    .cookie-consent__option span { color: #495663; }
    .cookie-consent__preference-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
    @media (max-width: 720px) {
        .cookie-consent { right: 8px; bottom: 8px; left: 8px; }
        .cookie-consent__banner { align-items: stretch; flex-direction: column; gap: 14px; padding: 15px; }
        .cookie-consent__actions { flex: 1 1 auto; }
        .cookie-consent__actions button { flex-basis: 100%; }
    }
</style>

<section id="cookie-consent" class="cookie-consent" hidden>
    <div class="cookie-consent__banner" role="region" aria-labelledby="cookie-consent-title">
        <div class="cookie-consent__copy">
            <h2 id="cookie-consent-title">{{ $consentIsSwedish ? 'Vi använder cookies' : 'We use cookies' }}</h2>
            <p>
                {{ $consentIsSwedish
                    ? 'Nödvändiga cookies hjälper webbplatsen att fungera. Med ditt tillstånd använder vi även valfria tjänster för analys, funktioner och marknadsföring.'
                    : 'Necessary cookies help this website work. With your permission, we also use optional services for analytics, functionality, and marketing.' }}
                <a href="{{ url('cookie-policy') }}">{{ $consentIsSwedish ? 'Cookiepolicy' : 'Cookie policy' }}</a>
            </p>
        </div>
        <div class="cookie-consent__actions">
            <button type="button" data-consent-action="accept-all">{{ $consentIsSwedish ? 'Acceptera alla' : 'Accept All' }}</button>
            <button type="button" data-consent-action="reject">{{ $consentIsSwedish ? 'Avvisa valfria' : 'Reject Non-Essential' }}</button>
            <button type="button" data-consent-action="manage">{{ $consentIsSwedish ? 'Hantera inställningar' : 'Manage Preferences' }}</button>
        </div>
    </div>

    <div class="cookie-consent__preferences" role="dialog" aria-modal="true" aria-labelledby="cookie-preferences-title" hidden>
        <div class="cookie-consent__backdrop" data-consent-action="close"></div>
        <h2 id="cookie-preferences-title">{{ $consentIsSwedish ? 'Cookieinställningar' : 'Cookie Preferences' }}</h2>
        <p>{{ $consentIsSwedish ? 'Välj vilka valfria tjänster du vill tillåta. Du kan ändra dig när som helst via Cookieinställningar.' : 'Choose which optional services to allow. You can change your choices at any time from Cookie Settings.' }}</p>
        <label class="cookie-consent__option">
            <input type="checkbox" checked disabled>
            <span><strong>{{ $consentIsSwedish ? 'Nödvändiga' : 'Necessary' }}</strong>{{ $consentIsSwedish ? ' Krävs för säkerhet, sessioner och grundläggande webbplatsfunktioner.' : ' Required for security, sessions, and core website functions.' }}</span>
        </label>
        <label class="cookie-consent__option">
            <input type="checkbox" data-consent-category="analytics">
            <span><strong>{{ $consentIsSwedish ? 'Analys' : 'Analytics' }}</strong>{{ $consentIsSwedish ? ' Hjälper oss förstå hur webbplatsen används.' : ' Helps us understand how the website is used.' }}</span>
        </label>
        <label class="cookie-consent__option">
            <input type="checkbox" data-consent-category="functional">
            <span><strong>{{ $consentIsSwedish ? 'Funktionella tjänster' : 'Functional services' }}</strong>{{ $consentIsSwedish ? ' Till exempel kartor, platsfunktioner och Google Översätt.' : ' Such as maps, location features, and Google Translate.' }}</span>
        </label>
        <label class="cookie-consent__option">
            <input type="checkbox" data-consent-category="marketing">
            <span><strong>{{ $consentIsSwedish ? 'Marknadsföring och externt innehåll' : 'Marketing and external media' }}</strong>{{ $consentIsSwedish ? ' Tillåter marknadsföringstjänster och inbäddat externt innehåll.' : ' Allows marketing services and embedded third-party media.' }}</span>
        </label>
        <div class="cookie-consent__preference-actions">
            <button type="button" data-consent-action="close">{{ $consentIsSwedish ? 'Avbryt' : 'Cancel' }}</button>
            <button type="button" data-consent-action="save">{{ $consentIsSwedish ? 'Spara inställningar' : 'Save Preferences' }}</button>
        </div>
    </div>
</section>
