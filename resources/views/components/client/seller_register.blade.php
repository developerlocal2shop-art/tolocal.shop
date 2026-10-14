<div class="col-xl-5 col-lg-6 pr-xl-5">
    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="text-align: center;">Seller Registration successful</h5>
                </div>
                <div class="modal-body respondata">
                </div>
            </div>
        </div>
    </div>

    <div class="main_title_3">
        <span></span>
        <h2>Seller Registration</h2>
        <p></p>
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
    </div>

    <form method="POST" id="regFrm" action="{{ route('registerseller.create') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">First Name:</label>
                    <input type="text" class="form-control required" required="" placeholder="Enter first name" name="first_name" id="first_name">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Last Name:</label>
                    <input type="text" class="form-control required" required="" placeholder="Enter last name" name="last_name" id="last_name">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control required" required="" placeholder="Enter e-mail address" id="register_email" name="register_email">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Phone No:</label>
                    <input type="text" class="form-control required" id="phone" name="phone" required="" placeholder="Enter phone number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control required" id="password" name="password" placeholder="Enter password">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Confirm Password:</label>
                    <input type="password" class="form-control required" id="cpassword" name="cpassword" placeholder="Confirm password">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Register</button>
                </div>
            </div>
        </div>

    </form>
</div>

<div class="col-xl-5 col-lg-6 pr-xl-5">
    <div class="main_title_3">
        <span></span>
        <h2>Log In</h2>
        <p></p>
    </div>
    <form method="post" action="http://127.0.0.1:8000/login" id="loginForm" autocomplete="off">
        <input type="hidden" name="_token" value="SbBy3QHwajqZDAFas48l3rfNUnG1iwO0eML0R9dT">
        <input type="hidden" class="form-control" name="fromCheckoutPage" value="0">

        <div class="form-group">
            <label for="name">Email Id:</label>
            <input type="email" class="form-control" name="email" value="" placeholder="Email" required="required" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="name">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
        </div>
        <div class="form-group">
            <label for="password">
                <a href="http://127.0.0.1:8000/reset" class="float-right">
                    Forgot Password?
                </a>
            </label>
            <label for="register" style="display:none">
                <a href="http://127.0.0.1:8000/register" class="float-right">
                    Register
                </a>
            </label>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="g-recaptcha" data-sitekey="6Le9vt0ZAAAAAB5IBqpIQ11zCFLa678vYybvi82l"></div>
            </div>
        </div>
        <p class="add_top_30">
            <input class="btn_1" onclick="submitloginFunc();" type="submit" value="Log In" style="background-color: #007bff !important;">
        </p>
    </form>
</div>


<script type="text/javascript">
    $(document).on('change', '#state', function() {
        var id = $(this).val();
        $.ajax({
            url: "getcitybyid/" + id
            , type: "get",

            success: function(responce) {
                var obj = jQuery.parseJSON(responce);
                $('#city').html(obj);
            }
        });
    });

    $(document).on('change', '#city', function() {
        var id = $(this).val();
        console.log(id);
        //var token = "{{ csrf_token() }}";
        $.ajax({
            url: "getbrancebycityid/" + id
            , type: "get"
            , success: function(responce) {
                var obj = jQuery.parseJSON(responce);
                $('#branch').html(obj);

            }
        });
    });

    $(document).on('click', '.submitbtn', function(e) {
        e.preventDefault();

        $(document).find('.required').removeClass('is-invalid');
        $(document).find('.invalid-feedback').remove();

        var obj = $(this);
        let requestTxt = $(this).attr('process-text');
        requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
        var oldhtml = $(this).html();

        var form = $(this).closest("form");
        var url = form.attr('action');
        var method = form.attr('method');
        var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <span class="">${requestTxt}...</span>`;

        var allformdata = new FormData(form[0]);

        $.ajax({
            type: method
            , url: url
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: allformdata
            , mimeType: "multipart/form-data"
            , contentType: false
            , cache: false
            , processData: false
            , dataType: "json"
            , beforeSend: function() {
                obj.attr("disabled", true);
                obj.html(loader);
            },

            success: function(response) {

                console.log(response);

                if (response.status == false) {

                    $.each(response.errors, function(index, value) {
                        form.find('.required').each(function() {
                            var fieldName = $(this).attr('name');

                            if (fieldName == index && value != "") {
                                $(this).addClass("is-invalid");
                                $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                $(this).focus();
                            }
                        });
                    });

                } else {
                    if (typeof(response.message) != "undefined") {
                        console.log(response.message);

                        window.setTimeout(function() {
                            window.location.href = "{{ url('login') }}";
                        }, 8000);

                        window.setTimeout(function() {
                            $("#regFrm")[0].reset();
                            jQuery.noConflict();
                            jQuery('#exampleModalLong').modal('show');



                        }, 1000);
                        $('.respondata').html('<p>' + response.message + '</p>');

                    }

                }
                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
            , error: function(err) {}
        });

    });

</script>
<style>
    .modal {
        top: 58px !important;
    }

    .modal-backdrop.show {
        opacity: 0 !important;
    }

    label.error {
        color: #f33155 !important;
    }

    .hideShowPassword-toggle-show {
        top: 45% !important;
    }

    .red {
        background: none;
    }

</style>
