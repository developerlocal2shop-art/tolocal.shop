@extends('layouts.adminmaster')
@section('title','User | Add User')
@section('content')

        <style>
            input#otp {
                width: 75px;
            }
            .error {
                color: #ff0000 !important;
            }
            #password-strength-status {
                padding: 5px 10px;
                color: #FFFFFF;
                border-radius: 4px;
                margin-top: 5px;
            }

            .medium-password {
                background-color: #b7d60a;
                border: #BBB418 1px solid;
            }

            .weak-password {
                background-color: #ce1d14;
                border: #AA4502 1px solid;
            }

            .strong-password {
                background-color: #12CC1A;
                border: #0FA015 1px solid;
            }
        </style>
           <div class="page-wrapper">

            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">New User Info</h4>
                    </div>
                </div>
            </div>
           <div class="container-fluid">
               <div class="row cus-title">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.user.submitUser') }}" method="POST" class="" id="registerForm">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="form-group mb-4 col-md-6">
                                        <label class="col-md-12 p-0">First Name<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter First Name" class="form-control p-0 border-0" autocomplete=off>

                                        </div>
                                        <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4 col-md-6">
                                        <label class="col-md-12 p-0">Last Name<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter Last Name" class="form-control p-0 border-0" autocomplete=off>

                                        </div>
                                        <span class="text-danger">@error('last_name'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12">Set Phone Number<span style="color:red;">*</span></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="is_default_valid" value="default" id="pay" checked="">
                                                <label class="custom-control-label" for="1">Set As Default Number </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="is_default_valid" value="valid" style="margin-left: 20px;" id="nonpay" >
                                                <label class="custom-control-label" for="1">Valid and Active Number </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-6 mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Email<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off id="example-email">
                                        </div>
                                        <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                    </div>

                                    {{-- <div class="form-group mb-4 col-md-4">
                                        <label class="col-md-12 p-0">Verify Otp<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
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
                                    </div> --}}

                                </div>

                                <div class="row">

                                    <div class="form-group mb-4 col-md-6">
                                        <label class="col-md-12 p-0">Phone Number<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="phone_number" id="phone" value="{{ old('phone_number') }}" placeholder="Enter Phone Number" class="form-control p-0 border-0" autocomplete=off>

                                        </div>
                                        <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <label class="col-md-12 p-0">Password<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="password" name="password" id="password" value="" class="form-control p-0 border-0">

                                        </div>
                                        <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                        <div class="col-md-6" id="password-strength-status"></div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country<span style="color:red;">*</span></label>
                                            <select class="form-control" id="country" name="country" id="country">
                                                <option value=""> Select Country </option>
                                                @foreach($countries as $countryrow)
                                                <option @if($countryrow->id==old('country_id')) selected @endif value="{{ $countryrow->id }}"> {{ $countryrow->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">@error('country'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>State<span style="color:red;">*</span></label>
                                            <select class="form-control" id="state" name="state" >
                                                <option value="">Select State</option>
                                            </select>
                                            <span class="text-danger">@error('state'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="city_name" style="display: none">
                                            <label>City<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="cityname" name="city-name" value="">
                                        </div>
                                        <div class="form-group" id="city_dropdown">
                                            <label>City<span style="color:red;">*</span></label>
                                            <select class="form-control" name="city" id="city">
                                                <option value="">Select City</option>
                                            </select>
                                            <span class="text-danger">@error('city'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role<span style="color:red;">*</span></label>
                                            <select class="form-control" id="role" name="role" >
                                                <option value="0" > User </option>
                                                <option value="1" > Vendor </option>
                                            </select>
                                            <span class="text-danger">@error('role'){{$message}} @enderror</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="commission_field" style="display: none;">
                                        <div class="form-group">
                                            <label>Commission in Percentage<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="commission"  name="commission" value="{{ old('commission') }}">
                                            <span class="text-danger">@error('commission'){{$message}} @enderror</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4 col-md-12">
                                        <label class="col-md-12 p-0">Address</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="address" class="form-control p-0 border-0" style="border: 1px solid #ccc !important;"></textarea>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">

                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Phone No</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" placeholder="123 456 7890" class="form-control p-0 border-0">
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Message</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                        </div>
                                    </div> -->

                                </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="form-check-input" name="import_optional" id="" >
                                                    <label class="custom-control-label" for="1" style="display: inline-flex;">
                                                        <div id="tooltip">
                                                            <span id="tooltipText">Please check this for import all optional product's fields.</span>
                                                            <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span class="text-danger">@error('popular'){{$message}} @enderror</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="form-check-input" name="show_import_file" id="" value="1" >
                                                    <label class="custom-control-label" for="1" style="display: inline-flex;">
                                                        <div id="tooltip">
                                                            <span id="tooltipText">Please check this to show vendor's product import file.</span>
                                                            <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span class="text-danger">@error('show_import_file'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <div class="col-sm-12">
                                            <button type="submit" id="submitFunc" class="btn btn-info">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <!-- Column -->
                </div>

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

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
                    name: {
                        required: true,
                        capitalized: true
                    }
                    , last_name: {
                        required: true , //lettersonly: true
                        capitalized: true
                    }
                    , email: {
                        required: true, email: true,
                        noCapitalLetters: true
                    } ,
                    register_password: {
                        required: true
                        , minlength: 6
                        , maxlength: 30
                        //, pwcheck: true
                    }
                    , register_confirm_password: {
                        required: true,
                        //equalTo: "#password"
                    }
                    , phone_number: {
                        required: true
                        , number: true
                        , minlength: 10
                        , maxlength: 10
                    },
                    country: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    city: {
                        required: true
                    }

                }
                , messages: {
                    name: {
                        required: "Please enter your first name",
                        capitalized: "The first letter must be capitalized."
                    },
                    last_name: {
                        required: "Please enter your last name",
                        capitalized: "The first letter must be capitalized."
                    },
                    email: {
                        required: "The email should be in the format: abc@domain.tld",
                        noCapitalLetters: "The email must not contain capital letters."
                    },
                    register_password: {
                        required: "Please enter password"
                    },
                    register_confirm_password: {
                        required: "Please enter confirm password"
                        //, equalTo: "Your password and confirm password not same"
                    }
                    , phone_number: {
                        required: "Enter phone no."
                        , number: "Enter number only"
                        , minlength: "Please enter valid number"
                        , maxlength: "Please enter valid number"
                    }
                    ,country: {
                        required: "Please enter country"
                    }
                    ,state: {
                        required: "Please enter state"
                    }
                    ,city: {
                        required: "Please enter city"
                    }

                }
                ,errorPlacement: function(error, element) {
                    if (element.attr("name") === "otp") {
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

        $(document).ready(function () {

            $('#role').on('change', function() {
                var role_id = this.value;
                if(role_id == 1){
                    $('#commission').prop('required', true);
                    document.getElementById('commission_field').style.display = "block";
                }else{
                    $('#commission').prop('required', false);
                    document.getElementById('commission_field').style.display = "none";
                }
            });

            $("#getotp").click(function(event) {
                event.preventDefault();
                //var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
               //$("input[name='phone_number[full]'").val(full_number);
              var register_email = $('#example-email').val();
                //alert(register_phone);
                $('#getotp').prop('disabled', true);
                $('#getotp').html('Wait......');
                //var phone = $("#phone_number").val();
                axios.post('/sendemailotp', {
                        email: register_email,
                    })
                    .then(function(response) {
                        console.log('response - ',response);
                        if (response.data.status == 'error') {  //toastr.error(response.data.msg);
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
                                $('#example-email').prop('readonly', true);
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

            $("#password").on('keyup', function(){
                var number = /([0-9])/;
                var alphabets = /([a-zA-Z])/;
                var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
                if ($('#password').val().length < 6) {
                    $('#password-strength-status').removeClass();
                    $('#password-strength-status').addClass('weak-password');
                    $('#password-strength-status').html("Weak Password");
                } else {
                    if ($('#password').val().length > 6) {
                        $('#password-strength-status').removeClass();
                        $('#password-strength-status').addClass('strong-password');
                        $('#password-strength-status').html("Strong Password");
                    } else {
                        $('#password-strength-status').removeClass();
                        $('#password-strength-status').addClass('medium-password');
                        $('#password-strength-status').html("Medium Password");
                    }
                }
            });

            $(document).on('click', "#submitFunc", function() {
                var otp_val = $("#otp").val();

                if (otp_val == '') {
                    toastr.error('First OTP verification is required');
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

        $('#country').on('change', function() {

            var country_id = this.value;

            $("#state").html('');

            $.ajax({
                url: baseUrl + '/get-countrybyid'
                , type: "POST"
                , data: {
                    country_id: country_id
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result2) {
                    console.log(result2);
                }
            });

            $.ajax({
                url: baseUrl + '/get-states-by-country'
                , type: "POST"
                , data: {
                    country_id: country_id
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#state').html('<option value="">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#state").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

        });

        $('#state').on('change', function() {
            var state_id = this.value;
            $("#city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
                    //console.log(result.cities.length);
                    if(result.cities.length== 0){
                       // $('#city_name').style.display = "block";
                        document.getElementById('city_name').style.display = "block";
                        document.getElementById('city_dropdown').style.display = "none";

                    }else{
                        document.getElementById('city_name').style.display = "none";
                        document.getElementById('city_dropdown').style.display = "block";

                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }

                }
            });
        });
    </script>
@endsection
