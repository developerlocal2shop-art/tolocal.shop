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

    <script src="/api.js" async defer></script>
    <?php /* ?><div id="contact_map" class="map-full map-layout single-listing-map" style="z-index: 50;">
        <?php foreach($map as $row){ ?>
        <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d79488.92981732363!2d-0.3820522095939976!3d51.49433426815668!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x487673833cc94347%3A0x72d7cfdca507d7e8!2sAuthentic%20Thai%20Food%2C%20Colham%20Ave%2C%20West%20Drayton%20UB7%208EU%2C%20United%20Kingdom!3m2!1d51.512161299999995!2d-0.4695065!4m5!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2slondon!3m2!1d51.5073509!2d-0.1277583!5e0!3m2!1sen!2sin!4v1632736212100!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <input type="hidden" name="location" id="location" value="{{$row->location}}">
        <?php }?>
    </div><?php */ ?>

    <div class="container margin_60_35">
        <div class="row justify-content-center">

            <div class="col-lg-6 pr-xl-5">
                <div class="main_title_3">
                    <span></span>
                    <h2>{{__("message.contact_us_title")}}</h2>
                    <p style="font-size: 15px;">{{__("message.contact_form_subtitle")}}</p>
                </div>
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
				@if(session()->get('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div><br />
                @endif
                <form id="send_contact_message" action="{{ route('contact.submitContact') }}" method="POST" name="contactform">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="name_contact">{{__("message.first_name_text")}}<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" id="name_contact" name="name_contact">
                            @error('name_contact')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        </div>
                        <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="lastname_contact">{{__("message.last_name_text")}}<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" id="lastname_contact" name="lastname_contact">
                            @error('lastname_contact')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                         </div>
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="email_contact">{{__("message.email_text")}}<span style="color:red;">*</span></label>
                                <input class="form-control" type="email" id="email_contact" name="email_contact">
                                @error('email_contact')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="phone_contact">{{__("message.mobile_text")}}<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" id="phone_contact" name="phone_contact">
                                @error('phone_contact')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                    <div class="form-group">
                        <label for="message_contact">{{__("message.message_label_text")}}<span style="color:red;">*</span></label>
                        <textarea class="form-control" id="message_contact" name="message_contact" style="height:120px;"></textarea>
                        @error('message_contact')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group contact-recaptcha-section">
                        <div class="contact-recaptcha-widget">
                            @if(!empty($recaptchaSiteKey))
                                <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                            @else
                                <span class="text-danger">reCAPTCHA is currently unavailable.</span>
                            @endif
                        </div>
                        @error('g-recaptcha-response')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class=" row">
                        <div class="form-group" style="margin-left: 17px;">
                            <button type="submit" class="btn_1" id="submit_contact">{{__("message.submit_button")}}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-5 col-lg-6 pl-xl-5">
                @foreach($contact as $contactdata)
                <div class="box_contacts">
                    <i class="fa fa-suitcase" aria-hidden="true"></i>
                    <h2>{{__("message.question_text")}}</h2>
                    <a href="tel:{{$contactdata->phone}}">{{$contactdata->phone}}</a> - <a href="mailto:{{$contactdata->system_email}}">{{$contactdata->system_email}}</a>
                </div>
                <div class="box_contacts">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <h2>{{__("message.address_text")}}</h2>
                    <div>{!!$contactdata->address!!}</div>
                </div>
                @endforeach
            </div>

            <div class="col-md-12">
                <iframe
                    src="https://www.google.com/maps?q=Drottninggatan%2020,%20432%2041%20Varberg,%20Sweden&output=embed"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
    @if(!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>

        $(document).ready(function() {
            setTimeout(function(){
                //window.location.reload();
            },4000);
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
                        required: true,
                        //lettersonly: true
                    }
                    , lastname_contact: {
                        required: true,
                        // lettersonly: true
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
                        required: true,
                        noKeywordsValidation: ["sex","Sex","SEX", "sexy","SEXY", "porn","Porn","PORN"]
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
                        required: "Please enter message",
                        noKeywordsValidation: "Message must not contain any of the specified keywords"
                    , }
                , }
                , submitHandler: function() {
                    if ($("#send_contact_message").valid() === true) {
                        document.getElementById('send_contact_message').submit();
                    }
                }
            });


            $.validator.addMethod("noKeywordsValidation", function(value, element, param) {
                // Check if none of the specified keywords are present in the message
                for (var i = 0; i < param.length; i++) {
                if (value.includes(param[i])) {
                    return false; // Validation fails if any keyword is found
                }
                }
                return true; // Validation passes if none of the keywords are found
            }, "Invalid keyword");


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

        #send_contact_message {
            width: 100%;
        }

        #send_contact_message .form-group {
            margin-bottom: 1rem;
        }

        #send_contact_message label {
            display: block;
            margin-bottom: 0.45rem;
        }

        #send_contact_message .form-control {
            min-height: 46px;
            width: 100%;
        }

        #send_contact_message #message_contact {
            min-height: 120px;
            resize: vertical;
        }

        #send_contact_message .contact-recaptcha-widget {
            width: 304px;
            max-width: 100%;
        }

        #send_contact_message .contact-recaptcha-widget .g-recaptcha {
            max-width: 100%;
        }

        #send_contact_message .contact-recaptcha-section .text-danger {
            width: 100%;
            margin-top: 0.5rem;
            text-align: center;
        }

        #send_contact_message #submit_contact {
            min-height: 46px;
        }

        @media (max-width: 767px) {
            #send_contact_message .row {
                margin-left: -10px;
                margin-right: -10px;
            }

            #send_contact_message .row > [class*="col-"] {
                padding-left: 10px;
                padding-right: 10px;
            }

            #send_contact_message > .row:last-child {
                margin-left: 0;
                margin-right: 0;
            }

            #send_contact_message > .row:last-child .form-group {
                margin-left: 0 !important;
                width: 100%;
            }

            #send_contact_message #submit_contact {
                display: block;
                width: 100%;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #send_contact_message > .row:last-child .form-group {
                margin-left: 0 !important;
            }
        }
</style>
@endsection
