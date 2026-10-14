
@extends('layouts.client.contact')
@section('title','Reset Password | ToLocal.shop ')
@section('content')

<br/>
<div class="container margin_60_35">
		<div class="row justify-content-center">
			
			<div class="col-xl-5 col-lg-6 pr-xl-5">
				<div class="main_title_3">
					<span></span>
					<h2>Reset Password</h2>
                    <p></p>
					<!-- <p style="font-size: 15px;">Please complete the form below and send it to us.</p> -->
				</div>
				<form method="POST" class="my-login-validation" action="{{ route('user.password.update') }}">
				{!! csrf_field() !!}
					<input type="hidden" name="token" value="{{ $token }}">
					<div class="form-group">
						<label for="email">Email</label>
						<input id="email" type="email" class="form-control" name="email" placeholder="Email address" value="{{ $email ?? old('email') }}">
						<span class="text-danger">@error('email'){{$message}} @enderror</span>
					</div>
					<div class="form-group">
						<label for="password">New Password</label>
						<input id="password" type="password" class="form-control" name="password" placeholder="Enter new password">
						<span class="text-danger">@error('password'){{$message}}@enderror</span>
					</div>
					<div class="form-group">
						<label for="password-confirm">Confirm Password</label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Enter confirm password">
						<span class="text-danger">@error('password_confirmation'){{$message}} @enderror</span>
					</div>

					<div class="form-group m-0">
						<button type="submit" class="btn btn-primary btn-block">
							Reset Password
						</button>
					</div>
				</form>
			</div>
			
		</div>
	</div>
	
@endsection
