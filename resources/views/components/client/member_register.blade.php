<div class="row justify-content-center"><br/>
<div class="col-xl-6 col-lg-6 pr-xl-6 signup_con">
    <div class="main_title_3">
        <span></span>
        <h2>{{__("message.register_title")}} </h2>
        <p></p>
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
    </div>
    <form method="POST" action="{{ route('member.register.create') }}" id="registerForm">
        {{ csrf_field() }}
        @php
        $ischeckoutpage = (in_array(Route::currentRouteName(), ['checkout']))?1:0;
        @endphp
        <input type="hidden" class="form-control" name="fromCheckoutPage" value="{{ $ischeckoutpage }}">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{__("message.first_name_text")}} <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="name" value="{{ old('register_name') }}" placeholder="{{__("message.placeholder_first_name_text")}}" name="register_name">
                    <span class="text-danger">@error('register_name'){{$message}} @enderror</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{__("message.last_name_text")}} <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="last-name" value="{{ old('register_last_name') }}" placeholder="{{__("message.placeholder_last_name_text")}}" name="register_last_name">
                    <span class="text-danger">@error('register_last_name'){{$message}} @enderror</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label style="display: flex;" for="phone">{{__("message.phone_no_text")}} <span style="color:red;">*</span>
                        <div id="tooltip">
                            <span id="tooltipText">Provide a valid number for further communication over SMS and call. Only enter number don't enter country code with phone number.</span>
                            <span><i class="fas fa-info-circle" ></i></span>
                        </div>
                    </label>
                    <input type="text" class="form-control" id="phone" value="{{ old('register_phone') }}" name="register_phone" placeholder="{{__("message.placeholder_phone_no_text")}}">
                    <span id="phone-unique-loader" class="field-loader"></span>
                    <span id="phone-unique-error" class="text-danger"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">{{__("message.email_text")}} <span style="color:red;">*</span></label>
                    <input type="email" class="form-control" id="register_email" value="{{ old('register_email') }}" placeholder="{{__("message.placeholder_email_text")}}" name="register_email">
                    <span id="email-unique-loader" class="field-loader"></span>
                    <span id="email-unique-error" class="text-danger"></span>
                    <span class="text-danger">@error('register_email'){{$message}} @enderror</span>
                </div>
            </div>
              <div class="col-md-12">
                <label>Verify Otp <span style="color:red;">*</span></label>
                <input type="text"  name="otp" id="otp" placeholder="Otp" >
                <input type="hidden" id="verified_status" value="no" >


                <a id="getotp" class="verifyOtp alert alert-info" style="padding: 3px 7px 3px 7px !important; margin: 25px 0px 2px 0px !important; cursor: pointer;">
                Get OTP
                </a>
                <a id="veifyotp" class="verifyOtp alert alert-info" style="padding: 3px 7px 3px 7px !important; margin: 25px 0px 2px 10px !important; display:none; cursor: pointer;">
                Verify OTP
                </a>
                <span class="text-danger">@error('otp'){{$message}} @enderror</span>
              </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="password">{{__("message.password_text")}} <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="password" name="register_password" placeholder="{{__("message.placeholder_password_text")}}" autocomplete="off">
                    <span class="text-danger">@error('register_password'){{$message}} @enderror</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="password">{{__("message.confirm_password_text")}} <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="cpassword" name="register_confirm_password" placeholder="{{__("message.placeholder_confirm_password_text")}}" autocomplete="off">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{-- <span>Detecting your current location...</span>
                    <span id="location-status" class="text-muted"></span> --}}
                    <input type="hidden" name="location_address" id="location-address">
                    <input type="hidden" name="location_country" id="location-country">
                    <input type="hidden" name="location_state" id="location-state">
                    <input type="hidden" name="location_city" id="location-city">
                </div>
            </div>

            <div class="col-md-12">

                <div class="form-group">
                    <button style="cursor:pointer" onclick="submitFunc();" id="submitFunc" class="btn btn-primary">{{__("message.submit_button")}}</button>
                    <a id="signin_p" style="margin: 10px !important;cursor: pointer;border: 1px solid #eee;padding: 3px 10px; text-decoration: underline;">Sign In</a>
                </div>
            </div>
        </div>
    </form>
</div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script type="text/javascript">
    var baseUrl = '{{ url(' / ') }}';

    var competitionValidationTimer = null;
    var competitionValidationBlocked = false;
    var competitionErrorClearTimers = {
        register_email: null,
        phone: null,
    };

    function getCurrentLocation() {
        var $status = $('#location-status');
        if (!navigator.geolocation) {
            $status.text('Location is not supported by this browser.');
            return;
        }
        $status.text('Getting your current location...');
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            var apiUrl = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latitude + '&lon=' + longitude;

            fetch(apiUrl, { headers: { 'Accept-Language': 'en' } })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    var address = data.address || {};
                    $('#location-address').val(data.display_name || '');
                    $('#location-country').val(address.country || '');
                    $('#location-state').val(address.state || address.region || '');
                    $('#location-city').val(address.city || address.town || address.village || '');
                    $status.text(data.display_name ? 'Location added.' : 'Address not found.');
                })
                .catch(function() { $status.text('Unable to find your address.'); });
        }, function() {
            $status.text('Location permission was not granted.');
        });
    }

    function checkCompetitionUnique(field, value) {
        var $fieldInput = field === 'register_email' ? $('#register_email') : $('#phone');
        var $errorField = field === 'register_email' ? $('#email-unique-error') : $('#phone-unique-error');
        var $loaderField = field === 'register_email' ? $('#email-unique-loader') : $('#phone-unique-loader');
        $errorField.text('');
        $fieldInput.removeClass('error');
        $loaderField.addClass('visible');

        if (!value) {
            competitionValidationBlocked = false;
            $loaderField.removeClass('visible');
            return;
        }

        if (competitionErrorClearTimers[field]) {
            clearTimeout(competitionErrorClearTimers[field]);
            competitionErrorClearTimers[field] = null;
        }

        axios.post('{{ url('/competition/check-unique') }}', {
                field: field,
                value: value,
                _token: '{{ csrf_token() }}'
            })
            .then(function(response) {
                if (response.data.exists === true) {
                    competitionValidationBlocked = true;
                    $errorField.text(field === 'register_email' ? 'Email is already registered.' : 'Phone is already registered.');
                    $fieldInput.addClass('error');

                    competitionErrorClearTimers[field] = setTimeout(function() {
                        if ($fieldInput.val().trim() === value) {
                            $fieldInput.val('');
                            $errorField.text('');
                            $fieldInput.removeClass('error');
                            competitionValidationBlocked = false;
                        }
                    }, 5000);
                } else {
                    competitionValidationBlocked = false;
                    $errorField.text('');
                    $fieldInput.removeClass('error');
                }
            })
            .catch(function(error) {
                competitionValidationBlocked = false;
            })
            .finally(function() {
                $loaderField.removeClass('visible');
            });
    }

    function debounceCompetitionCheck(field, value) {
        clearTimeout(competitionValidationTimer);
        competitionValidationTimer = setTimeout(function() {
            checkCompetitionUnique(field, value);
        }, 500);
    }

    $(document).ready(function() {

        getCurrentLocation();

        $(document).on('keyup', '#register_email', function() {
            debounceCompetitionCheck('register_email', $(this).val().trim());
        });

        $(document).on('keyup', '#phone', function() {
            debounceCompetitionCheck('phone', $(this).val().trim());
        });

        $('#signup_p').on('click', function (){
            $('.login_p').hide();
            $('.register_p').show();
        });

        $('#signin_p').on('click', function (){
            $('.login_p').show();
            $('.register_p').hide();
        });


            $("#getotp").click(function(event) {
                event.preventDefault();
                //var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
               //$("input[name='phone_number[full]'").val(full_number);
              var register_phone = $('#phone').val();
              //alert(register_phone);
                $('#getotp').prop('disabled', true);
                $('#getotp').html('Wait......');
                //var phone = $("#phone_number").val();
                axios.post('/sendotp', {
                        phone: register_phone,
                    })
                    .then(function(response) {
                        console.log(response);
                        if (response.data.status == 'error') {
                            //toastr.error(response.data.msg);
                            var myObj = response.data.error;
                            $.each(myObj, function(key, val) {
                                //swal("error!", val[0], "error");br
                                toastr.error('Error', val[0]);
                            });
                            $('#getotp').prop('disabled', false);
                            $('#getotp').html('Get OTP ');
                        } else {
                            toastr.success(response.data.msg);
                            $("#veifyotp").show();
                            $("#getotp").hide();
                            $('#getotp').prop('disabled', false);
                            $('#veifyotp').html('Verify OTP');
                        }
                    })
                    .catch(function(error) {});
                return false;
            });

            $("#veifyotp").click(function(event) {
                event.preventDefault();
                $('#veifyotp').prop('disabled', true);
                $('#veifyotp').html('Wait......');

                var otp = $("#otp").val();
                axios.post('/verify ', {
                        otp: otp,
                    })
                    .then(function(response) {
                        // $("#user"+user).html(response.data.bal);
                        if (response.data.status == '201') {
                            var myObj = response.data.error;
                            $.each(myObj, function(key, val) {
                                // swal("error!", val[0], "error");br
                                toastr.error('Error', val[0]);
                                //$('#' + key).css('border', '1px solid #f27863');
                            });
                            $('#veifyotp').prop('disabled', false);
                            $('#veifyotp').html('Get OTP');

                        } else {

                            $('#veifyotp').prop('disabled', false);
                            $('#veifyotp').html('Get OTP');
                            if (response.data.isSuccess == 'true') {
                                $('#phone').prop('readonly', true);
                                $('#otp').prop('readonly', true);
                                $('#otpfield').hide();
                                window.verify = 1;
                                toastr.success('OTP verified');
                                $('#veifyotp').css('border', '1px solid green');
                                $('#veifyotp').css('color', '#128807');
                                $('#veifyotp').html('Verified');
                                $('#verified_status').val('yes');

                                $('#veifyotp').prop('disabled', true);
                            } else {
                                toastr.error('Invalid Otp');
                            }
                        }
                    })
                    .catch(function(error) {});
                return false;
            });


        $("#register-country").select2({
            closeOnSelect: true
        });
        $("#register-state").select2({
            closeOnSelect: true
        });

        $(document).on('change', "#register-state", function() {
            var text = $("#register-state option:selected").text();
            $('#hiddenstate').val(text);
        });



        $(document).on('change', "#register-country", function() {
            var text = $("#register-country option:selected").text();
            console.log(text);
            $('#hiddencountry').val(text);

            $.ajax({
                url: baseUrl + '/get-states-by-country'
                , type: "POST"
                , data: {
                    country_id: $(this).val()
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#register-state').html('<option value="">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#register-state").append('<option value="' + value.id + '" >' + value.name + '</option>');
                    });
                }
            });
        });

        $(document).on('click', "#submitFunc", function() {
            var otp_val = $("#otp").val();

            if (otp_val == '') {
                toastr.error('Input fields and OTP verification is required');
                return false;
            }
            var verified_status = $('#verified_status').val();
            if(verified_status == 'no'){

            axios.post('/verify ', {
                    otp: otp_val,
                })
                .then(function(response) {

                    if (response.data.status == '201') {
                        var myObj = response.data.error;
                        $.each(myObj, function(key, val) {
                            toastr.error('Error', val[0]);
                        });
                        $('#veifyotp').prop('disabled', false);
                        $('#veifyotp').html('Get OTP');

                    } else {

                        $('#veifyotp').prop('disabled', false);
                        $('#veifyotp').html('Get OTP');
                        if (response.data.isSuccess == 'true') {
                            $('#phone').prop('readonly', true);
                            $('#otp').prop('readonly', true);

                            $('#otpfield').hide();
                            window.verify = 1;
                            toastr.success('OTP verified');
                            $('#veifyotp').css('border', '1px solid green');
                            $('#veifyotp').css('color', '#128807');
                            $('#veifyotp').html('Verified');
                            $('#verified_status').val('yes');
                            $('#veifyotp').prop('disabled', true);

                        } else {
                            toastr.error('Invalid Otp');

                        }
                    }
                });

                return false;

            }else{
                return true;
            }

        });


    });

    jQuery.validator.addMethod("email", function(value, element) {
        return this.optional(element) || /^[A-Z0-9._%+-]+@([A-Z]+\.)+[A-Z]{2,4}$/i.test(value);
    }, "{{__('message.register_a_company_text')}}");

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
    }, "Please enter only letters");

    // Strong Password Validation
    jQuery.validator.addMethod("pwcheck", function(value) {
        return /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*])[a-zA-Z0-9!@#$%&*]+$/.test(value);
    }, "Password is not strong enough. It contain at least one numeric digit, one uppercase , one lowercase letter and one special character.");

    jQuery.validator.addMethod("capitalized", function(value, element) {
        return this.optional(element) || /^[A-Z]/.test(value);
    }, "The first letter must be capitalized.");

    jQuery.validator.addMethod("noCapitalLetters", function(value, element) {
        return this.optional(element) || /^[^A-Z]*$/.test(value);
    }, "The email must not contain capital letters.");

    jQuery(function($) {
        $("#registerForm").validate({
            rules: {
                register_name: {
                    required: true,
                    capitalized: true
                }
                , register_last_name: {
                    required: true , //lettersonly: true
                    capitalized: true
                }
                , register_email: {
                    required: true, email: true,
                    noCapitalLetters: true
                } , otp: {
                    required: true
                }
                , register_password: {
                    required: true
                    , minlength: 6
                    , maxlength: 30
                    //, pwcheck: true
                }
                , register_confirm_password: {
                    required: true,
                    //equalTo: "#password"
                }
                , register_phone: {
                    required: true
                    , number: true
                    , minlength: 10
                    , maxlength: 10
                }

            }
            , messages: {
                register_name: {
                    required: "Please enter your first name",
                    capitalized: "The first letter must be capitalized."
                },
                register_last_name: {
                    required: "Please enter your last name",
                    capitalized: "The first letter must be capitalized."
                },
                register_email: {
                    required: "The email should be in the format: abc@domain.tld",
                    noCapitalLetters: "The email must not contain capital letters."
                },
                otp: {
                    required: "Please enter your otp"
                },
                register_password: {
                    required: "Please enter password"
                },
                 register_confirm_password: {
                    required: "Please enter confirm password"
                    //, equalTo: "Your password and confirm password not same"
                }
                , register_phone: {
                    required: "Please enter phone no."
                    , number: "Please enter number only"
                    , minlength: "Please enter valid number"
                    , maxlength: "Please enter valid number"
                }
             }
            ,errorPlacement: function(error, element) {
                if (element.attr("name") === "register_city") {
                    error.insertBefore(element);
                } else {
                    error.insertAfter(element);
                }
            }
            , submitHandler: function() {
                if ($("#registerForm").valid() === true) {
                    document.getElementById('registerForm').submit();
                }
            }
        });
    });

</script>
<style>

    input#otp {
        width: 30%;
    }
    .main_title_3 p {
        font-size: 14px;
        font-weight: 300;
        line-height: 1.4;
        margin-bottom: 5px;
    }
    label.error {
        color: #f33155 !important;
    }
    #email-unique-error,
    #phone-unique-error {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #d72b27;
        min-height: 18px;
    }
    .field-loader {
        position: absolute;
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-top: 6px;
        margin-left: 8px;
        vertical-align: middle;
        border: 2px solid rgba(0,0,0,0.12);
        border-top-color: #007bff;
        border-radius: 50%;
        opacity: 0;
        transform: scale(0.8);
        transition: opacity 150ms ease, transform 150ms ease;
    }
    .field-loader.visible {
        opacity: 1;
        transform: scale(1);
        animation: loader-spin 0.8s linear infinite;
    }
    @keyframes loader-spin {
        from { transform: rotate(0deg) scale(1); }
        to { transform: rotate(360deg) scale(1); }
    }
    #register_email.error,
    #phone.error {
        border-color: #d72b27;
        box-shadow: inset 0 1px 1px rgba(215, 43, 39, 0.2);
    }

    label.error {
        color: #f33155 !important;
    }

    .hideShowPassword-toggle-show {
        top: 45% !important;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px;
        border-right: 1px solid #aaa !important;
    }
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px !important;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    #infotooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #infotooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #infotooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -7px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #infotooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #infotooltip:hover #infotooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }

</style>
