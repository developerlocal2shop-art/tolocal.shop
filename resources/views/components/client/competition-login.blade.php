
<div class="row justify-content-center"><br/>
<div class="col-xl-6 col-lg-6 pr-xl-6 login_con">
    <div class="main_title_3">
        <span></span>
        <h2>Competition {{__("message.login_title")}}</h2>
        <p></p>
    </div>
    <form method="post" action="{{ route('competitionLoginPost') }}" id="loginForm" autocomplete="off">
        {!! csrf_field() !!}
        @php
            $ischeckoutpage = (in_array(Route::currentRouteName(), ['checkout']))?1:0;
        @endphp
        <input type="hidden" class="form-control" name="fromCheckoutPage" value="{{ $ischeckoutpage }}" >
        {{ \Session::forget('success') }}
        @if(\Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ \Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        @if(session()->has('emailexistmessage'))
            <div class="alert alert-danger">
                {{ session()->get('emailexistmessage') }}
            </div>
        @endif
        <div class="form-group">
            <label for="name">{{__("message.email_id_text")}}</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{__("message.email_text")}}" required="required" autocomplete=off>
            @if ($errors->has('email'))
            <span class="help-block font-red-mint">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group">
            <label for="name">{{__("message.password_text")}}:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="{{__("message.password_text")}}" required="required" autocomplete=off>
            @if ($errors->has('password'))
            <span class="help-block font-red-mint">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group">
            <label for="password">
                <a style="color: #fff;" href="{{route('userpassword.request')}}" class="float-right">
                    {{__("message.forgot_password")}}
                </a>
            </label>
            <label for="password" style="float: right;">
                <a style="color: #fff; float: right;border: 1px solid #eee; padding: 2px 5px;" href="{{route('accessByotp')}}" class="float-right">
                    {{__("Sign in using OTP")}}
                </a>
            </label>
            @if(!in_array(Route::currentRouteName(), ['checkout']))
            <label for="register" style="display:none">
                <a href="{{route('register.page')}}" class="float-right">
                    {{__("message.register_title")}}
                </a>
            </label>
            @endif
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="g-recaptcha" data-sitekey="6Le9vt0ZAAAAAB5IBqpIQ11zCFLa678vYybvi82l"></div>
            </div>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" onclick="submitloginFunc();" type="submit" value="{{__("message.login_title")}}" style="background-color: #dc3545 !important;">
            <a  id="signup_p" style="margin: 10px !important;cursor: pointer;border: 1px solid #eee;padding: 3px 10px; text-decoration: underline;">Sign Up</a>
        </div>
    </form>
    <div class="row">
        {{-- <div class="col-md-12"> <p style="text-align:center"> ------- OR ------- <p></div> --}}
    </div>
    {{-- <div class="row">
            <div class="form-group col-md-6">
                <a href="{{ route('login.facebook') }}" style="width: 100%;" class="btn btn-primary float-right">
                <!-- <img src="{{ asset('assets/images/socialbuttonimage/fulllogofacebook1.jpg') }}"> -->
                <i class="fa fa-facebook"></i>&nbsp; Facebook</a>
            </div>
            <div class="form-group col-md-6">
                <a href="{{ route('login.google') }}" style="width: 100%;" class="btn btn-danger float-right">
                <i class="fa fa-google"></i>&nbsp; Google</a>
            </div>
    </div> --}}
</div>
</div>
<script type="text/javascript">
function submitloginFunc() {
    document.getElementById('loginForm').submit();
}
</script>
<style>
    .btn_1:hover {
        background-color: #0069d9 !important;
        border-color: #0062cc !important;
        color: #fff !important;
    }
    .btn-primary {
        background-color: #dc3545 !important;
        border-color: rgb(233, 224, 224) !important;
    }
</style>

