<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Forgot password</title>
	<link rel="stylesheet" href="{{  asset('assets/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/my-login.css') }}">
</head>
<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center align-items-center h-100">
				<div class="card-wrapper">

					<div class="cardx fat">
						<div class="card-body">
							<h4 class="card-title">Forgot Password</h4>
							@if (session('message'))
									<div class="alert alert-success in alert-dismissible" style="margin-top:10px;">
										<strong>Success!</strong> {{ session('message') }}
									</div>
                            @endif
							<form method="POST" id="regFrm" class="my-login-validation" action="{{ route('password.email') }}">
								{!! csrf_field() !!}

								<div class="form-group">
									<label for="email">Please Enter Registered Mail Address From Admin Panel</label>
									<input id="email" type="email" class="form-control" name="email" placeholder="Enter your email">
                                    <!-- <span class="text-danger">@error('email'){{ $message }} @enderror</span> -->
									@if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
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
					<!-- <div class="footer">
						Copyright &copy; 2021 &mdash; Your Company
					</div> -->
				</div>
			</div>
		</div>
	</section>

	
</body>
</html>
