<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Admin login</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
.login-form {
    width: 340px;
    margin: 50px auto;
  	font-size: 15px;
}
.login-form form {
    margin-bottom: 15px;
    background: #f7f7f7;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    padding: 30px;
}
.login-form h2 {
    margin: 0 0 15px;
}
.form-control, .btn {
    min-height: 38px;
    border-radius: 2px;
}
.btn {        
    font-size: 15px;
    font-weight: bold;
}
body {
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
    background-color: rgb(75 75 75 / 90%);
}
.form-group button {
    font-family: Poppins-Medium;
    font-size: 16px;
    color: #555555;
    line-height: 1.2;
    justify-content: center;
    align-items: center;
    padding: 0px;
    min-width: 80px;
    height: 35px;
    border-radius: 25px;
    background: -webkit-linear-gradient(bottom, #7579ff, #b224ef);
    position: relative;
    z-index: 1;
    -webkit-transition: all 0.4s;
    width: 150px;
    margin: 0 auto;
}
a {
    color: #414344;
}
.login-form form {
    background: #f7f7f7;
    box-shadow: 10px 5px 15px rgba(0, 0, 0, 0.3);
    padding: 30px;
    border-radius: 10%;
}
.btn-primary {
    color: #fff;
    background-color: #2f4a7c;
    border-color: #007bff;
}
</style>
</head>
<body style="background-image: url('{{ url('assets/images/loginbg.jpg') }}');">
<div class="login-form" >
    <form action="{{ route('adminLoginPost') }}" method="post">
    	{!! csrf_field() !!}
        <h2 class="text-center">Login as a admin</h2>
        @if(\Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ \Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        {{ \Session::forget('success') }}
        @if(\Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ \Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif       
        <div class="form-group">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required="required" autocomplete=off>
            @if ($errors->has('email'))
            <span class="help-block font-red-mint">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
            @if ($errors->has('password'))
            <span class="help-block font-red-mint">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>

        <div class="form-group">
            <label for="password">
                <a href="{{route('password.request')}}" class="float-right">
                    Forgot Password?
                </a>
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block background: #2f4a7c;">LOG IN</button>
        </div>      
    </form>
</div>
</body>
</html>
