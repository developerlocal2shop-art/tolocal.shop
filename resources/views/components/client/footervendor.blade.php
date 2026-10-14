<?php
$currenturl = url()->current();
$currenturl = explode("/",$currenturl);

if(in_array("rydholms-dammodehus", $currenturl)){
    $is_rydholms = 1;
}else{
    $is_rydholms = 0;
}

?>


</div>

<!-- Back to top button -->
<div id="toTop"></div>

<x-cookie-consent />
{{-- <a href="#cookie-consent" data-cookie-settings class="cookie-settings-trigger">{{ app()->getLocale() === 'sv' ? 'Cookieinställningar' : 'Cookie Settings' }}</a> --}}

<script src="{{ asset('assets/client/js/3_5_1_jquery.min.js') }}" ></script>
<script src="{{ asset('assets/bootstrap/dist/js/bootstrap.bundle.min.js') }}"  @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/js/waves.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/bootstrap-toggle.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/jquery.ui.touch-punch.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/select2.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/popper.min.js') }}" ></script>
<script src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/tab-script.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"  @if(Request::is('/')) defer @endif></script>

<!-- COMMON SCRIPTS -->
<script src="{{ asset('assets/client/js/common_scripts.js') }}"  @if(Request::is('/')) defer @endif></script>

<script src="{{ asset('assets/client/js/functions.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/validate.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/bootstrap-tagsinput.min.js') }}" @if(Request::is('/')) defer @endif></script>
<!-- INPUT QUANTITY  -->
<script src="{{ asset('assets/client/js/input_qty.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/custom.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/toastr.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/markerclusterer.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/lightbox.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/4_8_select2.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/bootstrap.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/owl.carousel.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/jquery.maskedinput.js') }}" @if(Request::is('/')) defer @endif></script>
<?php if(!in_array("listings", $currenturl)){ ?>
    <script src="{{ asset('assets/client/js/jquery-ui.min.js') }}" @if(Request::is('/')) defer @endif></script>
    <script src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}" @if(Request::is('/')) defer @endif></script>
    <script src="{{ asset('assets/client/js/leaflet.js') }}" @if(Request::is('/')) defer @endif></script>
<?php } ?>
<script src="{{ asset('assets/js/jquery.datetimepicker.full.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/js/jquery.exzoom.js') }}" @if(Request::is('/')) defer @endif></script>
<script type="text/javascript" src="{{ asset('assets/client/js/jquery.cookie.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/client/js/bootstrap3-typeahead.min.js') }}" ></script>
<script data-consent-category="functional" data-consent-src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&amp;language=en&amp;callback=initializeGoogleMaps&amp;loading=async&amp;libraries=places"></script>


<script src="{{ asset('assets/client/js/owl.carousel.min.js') }}" @if(Request::is('/')) defer @endif></script>
<script src="{{ asset('assets/js/aos.js') }}" @if(Request::is('/')) defer @endif></script>

<script type="text/javascript" @if(Request::is('/')) defer @endif>

function showPosition() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var positionInfo1 = position.coords.latitude;
                var positionInfo2 = position.coords.longitude;
                document.getElementById("lati").value = positionInfo1;
                document.getElementById("long").value = positionInfo2;
                setCookie('latitude', positionInfo1, 7);
                setCookie('longitude', positionInfo2, 7);

                var geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(positionInfo1, positionInfo2);

                geocoder.geocode({ location: latlng }, function (results, status) {
                    var session_city = "{{ session()->get('filter_city') }}";

                    if (!session_city || session_city === 'Not found') {
                        if (status === google.maps.GeocoderStatus.OK && results) {
                            // Get city from results
                            let city;
                            for (let i = 0; i <= 2; i++) {
                                if (results[i]?.plus_code) {
                                    const cityDetails = results[i].plus_code.compound_code.split(",");
                                    city = cityDetails[0]?.split(" ")[1];
                                    break;
                                }
                            }
                            if (city) {
                                $(".select_city_home #select2-city_name-container").text(city);
                                $(".logo_city_name").text(city);
                            }
                        }
                    } else {
                        $(".select_city_home #select2-city_name-container").text(session_city);
                        $(".logo_city_name").text(session_city);
                    }
                });

                setTimeout(function () {
                    callAjax(positionInfo1, positionInfo2);
                }, 2000);

                function callAjax(lati, long) {
                    var form = $("form#latlongform");
                    $.ajax({
                        url: form.attr("action"),
                        type: "get",
                        data: { lati, long },
                        success: function (response) {
                        },
                    });
                }
            },
            function (error) {
                console.error("Geolocation error:", error);
                //alert("Unable to retrieve location.");
            },
            { enableHighAccuracy: true } // Enable high accuracy
        );
    } else {
        alert("Sorry, your browser does not support HTML5 geolocation.");
    }
}
$(document).ready(function() {

    $(document).on('click', '#mm-navbarDropdown', function (e) {
        e.preventDefault();
        $(this).next('.dropdown-menu').toggleClass('show');
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#mm-navbarDropdown, .dropdown-menu').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    $(document).on('click', '.dropdown-toggle', function () {
        $(this).toggleClass('active');
    });

    $('#profile_pic').on('change', function() {
        var file = this.files[0];

        if (!file) {
            alert("No file selected!");
            return;
        }

        var formData = new FormData();
        formData.append('profile_pic', file);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        console.log("CSRF Token:", $('meta[name="csrf-token"]').attr('content')); // Debugging
        console.log("FormData contains:", formData.get("profile_pic")); // Debugging

        $.ajax({
            url: "{{ route('profile.upload') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === "success") {
                    toastr.success(response.message);
                    setTimeout(
                    function()
                    {
                        window.location.reload();
                    }, 2000);

                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.message; // Laravel validation errors
                    var errorMsg = "";
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + "\n"; // Get first error message for each field
                    });
                    toastr.error(errorMsg);
                } else {
                    toastr.error("Something went wrong!");
                }
            }
        });
    });

    });





        function loadFacebookSdk() {
            if (document.getElementById('facebook-jssdk')) {
                return;
            }
            var script = document.createElement('script');
            script.id = 'facebook-jssdk';
            script.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10&appId=1094630054423850';
            document.head.appendChild(script);
        }
        document.addEventListener('cookie-consent:category-enabled', function (event) {
            if (event.detail.category === 'marketing') {
                loadFacebookSdk();
            }
        });
        if (window.CookieConsent && window.CookieConsent.has('marketing')) {
            loadFacebookSdk();
        }
        function fb_share(dynamic_link, dynamic_title) {
            var app_id = '1094630054423850';
            var pageURL = "https://www.facebook.com/dialog/feed?app_id=" + app_id + "&link=" + dynamic_link;
            var w = 600;
            var h = 400;
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 2);
            window.open(pageURL, dynamic_title, 'toolbar=no, location=no, directories=no, status=no, menubar=yes, scrollbar=no, resizeable=no, cpoyhistory=no, width=' + 800 + ', height=' + 650 + ', top=' + top + ', left=' + left)
            return false;
        }



        function googleTranslateElementInit() {
            const pageLanguage = '{{ app()->getLocale() }}';
            const hasGoogleTranslateCookie = document.cookie.split(';').some(cookie => cookie.trim().indexOf('googtrans=') === 0);
            if (pageLanguage === 'sv' && !hasGoogleTranslateCookie) {
                document.cookie = 'googtrans=/sv/sv; Path=/; SameSite=Lax';
            }

            const translateOptions = {
                pageLanguage: pageLanguage,
                includedLanguages: 'fr,es,sv,da,no,fi,de,en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                multilanguagePage: true,
                autoDisplay: false,
            };

            const isDesktop = window.innerWidth > 768;
            const targetElement = isDesktop
                ? (document.getElementById('google_translate_element') || document.getElementById('google_translate_element_home'))
                : (document.getElementById('google_translate_element_mobile') || document.getElementById('google_translate_element_home'));

            if (targetElement) {
                new google.translate.TranslateElement(translateOptions, targetElement.id);
            }

            window.addEventListener('resize', () => {
                const isDesktop = window.innerWidth > 768;
                const desktopElement = document.getElementById('google_translate_element');
                const mobileElement = document.getElementById('google_translate_element_mobile');
                if (desktopElement) {
                    desktopElement.style.display = isDesktop ? 'block' : 'none';
                }
                if (mobileElement) {
                    mobileElement.style.display = isDesktop ? 'none' : 'block';
                }
            });
        }

        function initializeGoogleMaps() {
            if (typeof showPosition === 'function') {
                showPosition();
            }
            if (typeof initAutocomplete === 'function') {
                initAutocomplete();
            }
        }

        </script>
<script data-consent-category="functional">CookieConsent.loadScript('https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', 'functional');</script>



<script type="text/javascript" @if(Request::is('/')) defer @endif>


$(document).ready(function() {

    @auth
        @if(Auth::user()->directory_id > 0)
            @if(empty(auth()->user()->paypal_email))
                $('#myModal33').modal('show').hide().fadeIn(400);
                toastr.error('You need to update your PayPal email or Merchant ID for <span class="notranslate">PayPal payouts</span>.');
            @endif
        @endif
    @endauth

    $('#paypalBtn').on('click', function (e) {
        e.preventDefault();
        const btn = $(this);
        const paypalEmail = $('#paypal_email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        $('.text-danger').text('');
        if (!paypalEmail) {
            $('.error-paypal_email').text('PayPal email is required.');
            return;
        }
        if (!emailRegex.test(paypalEmail)) {
            $('.error-paypal_email').text('Please enter a valid PayPal email address.');
            return;
        }
        btn.prop('disabled', true).text('Saving...');
        $.ajax({
            url: "{{ route('vendor.paypal.save') }}",
            type: "POST",
            data: {
                paypal_email: paypalEmail,
                paypal_merchant_id: $('#paypal_merchant_id').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function (res) {
            if (res.status === 'success') {
                $('#myModal33').modal('hide');
                alert(res.message); // replace with toast if needed
            }
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    $('.error-' + key).text(value[0]);
                });
            } else {
                alert('Something went wrong. Please try again.');
            }
        })
        .always(function () {
            btn.prop('disabled', false).text('Save PayPal Details');
        });
    });




        $("#loader").fadeOut("slow");


        $('.sk_fade').on('click', function() {
            $('.AJloader').show();
            setTimeout(
            function() {
                $('.loader').show();
            }, 1000);
        });
        $('.sk_fade_only').on('click', function() {
            $('.AJloader').show();
        });

        $('#staticBackdrop').modal('show');


        setTimeout(function() {
            $('#tree_structure').on("click", function() {
                $('#tree_structure_v').toggle();
                $('.tree-stuc .collapse').removeClass('show');
            });
        }, 2000);

        if (window.matchMedia("(max-width: 1200px)").matches) {
            $("#mm-google_translate_element").attr("id", "google_translate_element");
        }

        $.ajax({
            url: "{{ route('load.tree.structure.listings') }}",
            type: 'GET',
            success: function(response) {
                $('#tree_structure_listings').html(response);

                $('#tree_structure2').on("click", function() {
                    $('#tree_structure_v2').toggle();
                    $('.tree-stuc .collapse').removeClass('show');
                });

                setTimeout(
                function()
                {
                    $('#tagsname').select2({
                        placeholder: "Search tags"
                    });
                }, 2000);
            }
        });

        $.ajax({
            url: "{{ route('load.tree.structure.shop') }}",
            type: 'GET',
            success: function(response) {
                $('#tree_structure_shop').html(response);

            }
        });

        $('#map').addClass('notranslate');

        $('[data-toggle="tooltip"]').tooltip();
        if('{{ $expired_plan }}' == 1){
            toastr.error("The auto-payment mode for your plan has been deactivated because there has been a change in the plan's price. Please purchase the updated plan to reactivate the auto-payment mode.")
        }
        setTimeout(function(){
            $("div.alert-success").remove();
        }, 5000 ); // 5 secs

        $('#signup_p').on('click', function (){
            $('.login_p').hide();
            $('.register_p').show();
        });

        $('#signin_p').on('click', function (){
            $('.login_p').show();
            $('.register_p').hide();
        });

        AOS.init();

        setTimeout(function() {
            var arr = $.cookie("googtrans").split('/');
            $.ajax({
                url: '{{url("change-language")}}/' + arr[2]
                , success: function(response) { }
            });
        }, 1000);

        setTimeout( function(){
            $('.VIpgJd-ZVi9od-xl07Ob-lTBxed').contents().unwrap();
        }, 1000);

        $('#google_translate_element2').on("click", function () {

                $("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div") //, .goog-te-menu2 *
                .css({
                    'color': '#544F4B',
                    'background-color': '#e3e3ff',
                    'font-family': '"Open Sans",Helvetica,Arial,sans-serif'
                });

                $("iframe").contents().find(".goog-te-menu2-item div").hover(function () {
                    $(this).css('background-color', '#17548d').find('span.text').css('color', '#e3e3ff');
                }, function () {
                    $(this).css('background-color', '#e3e3ff').find('span.text').css('color', '#544F4B');
                });

                // Change Google's default blue border
                $("iframe").contents().find('.goog-te-menu2').css('border', '1px solid #17548d');
                $("iframe").contents().find('.goog-te-menu2').css('background-color', '#e3e3ff');
                // Change the iframe's box shadow
                $(".goog-te-menu-frame").css({
                    '-moz-box-shadow': '0 3px 8px 2px #666666',
                    '-webkit-box-shadow': '0 3px 8px 2px #666',
                    'box-shadow': '0 3px 8px 2px #666'
                });
        });

    });
    // End document ready

    function charCount(textvalue, id, maxlenght) {
        var len = 0;
        len = textvalue.length
        if (len > maxlenght) {
            return false;
        } else if (len > 0) {
            if ($(".errorshortcontent").length >= 1) {
                $('.errorshortcontent').remove();
            }
            $("#" + id).after("<span class='text-danger errorshortcontent'>" + (maxlenght - len) + "/" + (maxlenght) + " characters left! </span>");
        } else {
            if ($(".errorshortcontent").length >= 1) {
                $('.errorshortcontent').remove();
            }
            $("#" + id).after("<span class='text-danger errorshortcontent'>" + (maxlenght - len) + "/" + (maxlenght) + " characters left! </span>");
        }
    }

    window.addEventListener("pageshow", function(event) {
        var historyTraversal = event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2);
        if (historyTraversal) {
            window.location.reload();
        }
    });
    function loginAlert() {
        toastr.error('You will need to login first')
    }
    function getCookie(cookieName) {
        return $.cookie(cookieName);
    }
    function showAjaxModal(url, header) {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/client/images/bg-pattern-light.svg" /></div>');
        jQuery('#modal_ajax .modal-title').html('...');
        // LOADING THE AJAX MODAL
        jQuery('#modal_ajax').modal('show', {
            backdrop: 'true'
        });
        $.ajax({
            url: url
            , success: function(response) {
                jQuery('#modal_ajax .modal-body').html(response);
                jQuery('#modal_ajax .modal-title').html(header);
            }
        });
    }

    /* for auto-search in listing name and tags */
    var route = "{{ url('autocomplete-search') }}";
    var token = "{{ csrf_token() }}";

        var path = "{{ route('autocomplete') }}";
        $('#search').typeahead({
            source: function(query, process) {
                return $.get(path, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
            , minLength: 4
        });

        $('#searchquerylisting').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query, _token:token, type:"listings"
                }, function (data) {
                    return process(data);
                });
            }
        });

        $('#search_string').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query, _token:token, type:"product"
                }, function (data) {
                    return process(data);
                });
            }
        });

        function getchangeTheSessionData(selcity) {
            $('#AJloader').show();
            var selobj = selcity.value;
            $.ajax({
                url: baseUrl + '/setCityinSession',
                type: "POST"
                , data: {
                    city_name: selobj , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#AJloader').hide(); location.reload();
                }
            });
        }
        function getchangeTheSessionDataHome(selcity) {
            $('#AJloader').show();
            var selobj = selcity.value;
            $.ajax({
                url: baseUrl + '/setCityinSession',
                type: "POST"
                , data: {
                    city_name: selobj , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#AJloader').hide(); //location.reload();
                }
            });
        }

        function updateSessionDistance(distance) {
            $('#AJloader').show();
            var selobj = distance.value;
            if(selobj == 0){
                $('#AJloader').hide();
                toastr.error('Please select distance.')
                return false;
            }
            $.ajax({
                url: baseUrl + '/updateDistanceSessionData',
                type: "POST"
                , data: {
                    distance: selobj
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#AJloader').hide();
                    location.reload();
                }
            });
        }



        $('.pop_home_btn').on('click', function() {
            setTimeout(function() {

                $.getJSON('https://api.db-ip.com/v2/free/self', function(data) {
                    //console.log(JSON.stringify(data.countryCode, null, 2));
                    let countryCode = JSON.stringify(data.countryCode, null, 2);

                    console.log('countryCode2 - ', countryCode);

                    var googtrans_hold = $.cookie("googtrans2");
                    var googtrans = $.cookie("googtrans");
                    if(countryCode == '"IN"'){ //India
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/en", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"ES"'){ //Spain
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/es", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"SE"'){ //Sweden
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/sv", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"NO"'){ //Norway
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/no", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"DE"'){ //Germany
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/de", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"FR"'){ //France
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/fr", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"FI"'){ //Finland
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/fi", { expires: 90, path: '/' });
                        }
                    }else if(countryCode == '"DK"'){ //Denmark
                        if (googtrans_hold !== undefined) {
                            $.cookie("googtrans", googtrans_hold, { expires: 90, path: '/' });
                        } else if (googtrans === undefined) {
                            $.cookie("googtrans", "/en/da", { expires: 90, path: '/' });
                        }
                    }else{
                        $.cookie("googtrans", "/en/sv", { expires: 90, path: '/' });
                    }
                });

                <?php if($is_rydholms == 0){ ?>
                   location.reload();
                <?php }else{ ?>
                    window.open('https://tolocal.shop/listings/details/rydholms-dammodehus', '_self');
                <?php } ?>
                //changeLanguage(cookieValue);
                $('#staticBackdrop').modal('hide');
            }, 500);

        });

        $('#google_translate_element, #google_translate_element_mobile, #google_translate_element_home').on("click", function () {
            $('#map').addClass('notranslate');
            setTimeout(function () {

                <?php if($is_rydholms == 0){ ?>
                location.reload();
                <?php } ?>
                // Get the "googtrans" cookie value
                var googTransCookie = $.cookie("googtrans");
                if (googTransCookie) {
                    // Extract the selected language from the cookie
                    var selectedLanguage = googTransCookie.split('/')[2]; // e.g., "sv", "es", etc.
                    // Map languages to custom paths or logic
                    var languageMap = {
                        "sv": "/sv/sv",
                        "es": "/en/es",
                        "fr": "/en/fr",
                        "da": "/en/da",
                        "no": "/en/no",
                        "fi": "/en/fi",
                        "de": "/en/de"
                    };
                    // Get the mapped language or default to English
                    var cookieLanguage = languageMap[selectedLanguage] || "/en/en";
                    // Set a new cookie with the mapped value
                    $.cookie("googtrans2", cookieLanguage, { expires: 90, path: '/' });
                    // Perform additional actions based on the selected language
                    changeLanguage(selectedLanguage);
                } else {
                    //alert("Language not selected or cookie not set.");
                }
            }, 3000); // Delay for the translate widget to update the cookie
        });

        $('#mm-google_translate_element').on("click", function () {
            $('#map').addClass('notranslate');
            setTimeout(function() {
                location.reload();
                var cookieValue = $.cookie("googtrans").split('/')[2];
                var languageMap = {
                    "sv": "/sv/sv",
                    "es": "/en/es",
                    "fr": "/en/fr",
                    "da": "/en/da",
                    "no": "/en/no",
                    "fi": "/en/fi",
                    "de": "/en/de"
                };
                var cookieLanguage = languageMap[cookieValue] || "/en/sv";
                $.cookie("googtrans2", cookieLanguage, { expires: 90, path: '/' });
                changeLanguage(cookieValue);
            }, 3000);
        });

    // var googtrans_holddd = $.cookie("googtrans2");
    // var googtransss = $.cookie("googtrans");
    // alert(googtrans_holddd + " - " + googtransss);

    function more_category() {
        $.ajax({
            url: "footer_more_category/"
            , success: function(response) {
                $('#loader').show();
                setInterval(function() {
                    $('#loader').hide();
                    $('#footer_category').html(response);
                }, 1000);
            }
        });
    }

    <?php
        $firstSlug = request()->segment(1);
        $secondSlug = request()->segment(2);
        $product_detail = $firstSlug.'_'.$secondSlug;
        if($product_detail == 'product_detail'){
    ?>
    $(function() {
        $("#exzoom").exzoom({
            // thumbnail nav options
            "navWidth": 60
            , "navHeight": 60
            , "navItemNum": 4
            , "navItemMargin": 7
            , "navBorder": 1
            , "autoPlay": true
            , "autoPlayTimeout": 90000
        , });
    });
    <?php } ?>

    function changeLanguage(lang) {
        window.location = '{{url("change-language")}}/' + lang;
    }
    function changeCurrency(currencyCode) {
        window.location = '{{url("change-currency")}}/' + currencyCode;
    }
    function confirm_modal(delete_url) {
        jQuery('#alert-modal').modal('show', {
            backdrop: 'static'
        });
        document.getElementById('update_link').setAttribute('href', delete_url);
    }
    function cookieAccept() {
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem("accept_cookie_ToLocal.shop", true);
            localStorage.setItem("accept_cookie_time_ToLocal.shop", "09/24/2021");
            $('#cookieConsentContainer').fadeOut(1200);
        }
    }
    var baseUrl = "{{URL::to('/')}}";
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    $(window).scroll(function(){
        if ($(this).scrollTop() > 50) {
        $('.filter_sub_blue_header ').addClass('newClass');
        $('.is_sticky').addClass('header_s');
        } else {
        $('.filter_sub_blue_header ').removeClass('newClass');
        $('.is_sticky').removeClass('header_s');
        }
    });

</script>
