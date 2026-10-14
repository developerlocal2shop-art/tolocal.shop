@extends('layouts.client.contact')
@section('title','Member Login/Register | ToLocal.shop ')
@section('content')
<style>
    div#page {
        background-image: url("{{ url('assets/images/loginbg.jpg') }}");
        background-repeat: no-repeat;
        background-size: cover;
        color: #fff;
    }
    .main_title_3 h2,
    .main_title_3 h3 {
        color: #fff;
    }
    label {
        margin-right: 15px;
    }
    a#veifyotp {
        color: #000;
    }
    a#getotp {
        color: #000;
    }
    input#register_email {
        margin: 4px 0px;
    }
    .login_con {
        margin: 79px auto;
        max-width: 465px;
        background-color: transparent;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        backdrop-filter: blur(55px);
        justify-content: center;
        align-items: center;
        padding: 1rem 1rem;
    }

    .signup_con {
        margin: 79px auto;
        max-width: 465px;
        background-color: transparent;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        backdrop-filter: blur(55px);
        justify-content: center;
        align-items: center;
        padding: 1rem 1rem;
    }

    span.help-block.font-red-mint strong {
        color: #fd96a0;
    }
    footer ul.contacts li {
        color: #5c5c5c !important;
    }

    #page {
        background-color: #616161;
        width: 100%;
        min-height: 100vh;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        padding: 15px;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        position: relative;
        z-index: 1;
    }
    .login-form::before {
        content: "";
        display: block;
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: rgb(45 43 43 / 90%);
    }
    footer.plus_border.vendor_footer {
        display: none !important;
    }
    @media (max-width: 768px) {
        input#otp {
            width: 40%;
        }
    }
</style>
<br />
<div class="container margin_60_35 login-form">

    <div class="login_p">
        <x-client.login />
    </div>
    <div class="register_p" style="display:none;">
        <x-client.member_register />
    </div>
</div>

@endsection
