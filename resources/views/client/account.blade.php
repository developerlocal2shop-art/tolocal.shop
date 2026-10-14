@extends('layouts.client.contact')
@section('title','Contact | ToLocal.shop ')
@section('content')
</a>

<div class="loader" id="loader"></div>
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>{{__("message.menu_text_contact")}}</h1>
    </div>
</div>
<main>

    <div class='alert alert-success text-center' role='alert'>
    Successfully connected with your Account!
	</div>
	<div class='row'>
		<div class='col d-flex align-items-center justify-content-center'>
			<p class='mt-2'><strong>Account ID:</strong> {{ $account['id'] }}</p>
		</div>
	</div>

    <script>
        $(document).ready(function() {
            $("#loader").fadeOut("slow");
        });


        'use strict';
        var locationdata = $('#location').val();
        var map = L.map('contact_map').setView(locationdata, 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: '<a href="https://demo.creativeitem.com/atlas/" target="_blank">ATLAS Directory Listing CMS</a>'
            , id: 'mapbox/streets-v11'
            , accessToken: '{{ env("GOOGLE_MAP_API_KEY") }}'
            , gestureHandling: true
        }).addTo(map);
        L.marker(locationdata).addTo(map)
            .openPopup();
        map.scrollWheelZoom.disable();
        $("#contact_map").bind('mousewheel DOMMouseScroll', function(event) {
            event.stopPropagation();
            if (event.ctrlKey == true) {
                event.preventDefault();
                map.scrollWheelZoom.enable();
                $('#contact_map').removeClass('map-scroll');
                setTimeout(function() {
                    map.scrollWheelZoom.disable();
                }, 1000);
            } else {
                map.scrollWheelZoom.disable();
                $('#contact_map').addClass('map-scroll');
            }
        });
        $(window).bind('mousewheel DOMMouseScroll', function(event) {
            $('#contact_map').removeClass('map-scroll');
        })
    </script>
    <script type="text/javascript">
        jQuery.validator.addMethod("email", function(value, element) {
            return this.optional(element) || /^[A-Z0-9._%+-]+@([A-Z]+\.)+[A-Z]{2,4}$/i.test(value);
        }, "Please enter valid email");
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
        }, "Please enter only letters");
        jQuery(function($) {
            $("#send_contact_message").validate({
                rules: {
                    name_contact: {
                        required: true
                        , lettersonly: true
                    }
                    , lastname_contact: {
                        required: true
                        , lettersonly: true
                    }
                    , email_contact: {
                        required: true
                        , email: true
                    }
                    , phone_contact: {
                        required: true
                        , number: true
                        , minlength: 10
                        , maxlength: 10
                    }
                    , message_contact: {
                        required: true
                    },

                }
                , messages: {
                    name_contact: {
                        required: "Please enter your first name"
                    , }
                    , lastname_contact: {
                        required: "Please enter your last name"
                    , }
                    , email_contact: {
                        required: "Please Enter e-mail address id"
                        , email: "The email should be in the format: test@domain.tld"
                    , }
                    , phone_contact: {
                        required: "Please enter phone no."
                        , number: "Please enter number only"
                        , minlength: "Please enter valid number"
                        , maxlength: "Please enter valid number"
                    , }
                    , message_contact: {
                        required: "Please enter message"
                    , }
                , }
                , submitHandler: function() {
                    if ($("#send_contact_message").valid() === true) {
                        document.getElementById('send_contact_message').submit();
                    }
                }
            });
        });
    </script>
</main>
<style type="text/css">
        .map-scroll:before {
            content: 'Use ctrl + Scroll to zoom the map.';
            position: absolute;
            top: 40%;
            left: 40%;
            z-index: 999;
            font-size: 25px;
        }
        .map-scroll:after {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            content: '';
            background: #00000061;
            z-index: 999;
        }
        form .error {
            color: #ff0000;
        }
    </style>
@endsection
