@extends('layouts.client.contact')
@section('title','Forget Password Email | ToLocal.shop ')
@section('content')

<br/>
<?php echo $token;?>
<div class="container margin_60_35">
    <div class="row justify-content-center">
        
        <div class="col-xl-5 col-lg-6 pr-xl-5">
            <div class="main_title_3">
                <span></span>
                <h2>Forgot Password E-mail</h2>
                <p></p>
            </div>
            Please click HERE to reset your password: 
            <a href="<?php echo url("/reset-password", ['token' => @$token]); ?>">Reset Password</a>
        </div>
        
    </div>
</div>

@endsection