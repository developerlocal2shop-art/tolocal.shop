@extends('layouts.client.contact')
@section('title','Login | ToLocal.shop ')
@section('content')

<br/>

	<div class="container margin_60_35">
		<div class="row justify-content-center">
			
			<div class="col-xl-5 col-lg-6 pr-xl-5">
				<div class="main_title_3">
					
					<h2>Forgot Password</h2> 
                    @if (session('message'))
							<div class="alert alert-success in alert-dismissible" style="margin-top:10px;">
								<strong>Success!</strong> {{ session('message') }}
							</div>
					@endif
				</div>
				<form method="POST" class="my-login-validation" action="{{ route('user.password.email') }}">
					{!! csrf_field() !!}

					<div class="form-group">
						<label for="email">Please Enter Registered E-Mail Address</label>
						<input id="email" type="email" class="form-control" name="email" placeholder="Enter your e-mail">
						<!-- <span class="text-danger">@error('email'){{ $message }} @enderror</span> -->
						@if ($errors->has('email'))
							<span class="text-danger">{{ $errors->first('email') }}</span>
						@endif
						@if(session('error'))
							<span class="text-danger">{{session('error')}}</span>
						@endif
					</div>

					<div class="form-group m-0">
						<button type="submit" class="btn btn-primary btn-block">
							Send Password Link
						</button>
					</div>
				</form>
			</div>
			
		</div>
	</div>
		
@endsection
