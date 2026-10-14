@extends('layouts.client.contact')
@section('title','Login | ToLocal.shop ')
@section('content')

<style>
	.left_login h3 { color: #fff; }
	footer ul.contacts li { color: #555555; }
	input#otp { margin: 5px 0px !important; }
	.left_login { width: 40%; background: #2f4a7c; padding: 150px 100px; color: #fff; }
	.right_login { padding: 170px; width: 60%; float: right; margin: -525px 0px; }
	.form-control { border-bottom: 1px solid #d2d8dd !important; border-top: 0; border-left: 0; border-right: 0; }
	img.logo_normal { margin: 25px 0px; }

	.login_con {
		background-color: transparent;
		border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 20px; backdrop-filter: blur(55px); justify-content: center; 
		align-items: center; padding: 1rem 1rem;
	}
	div.right_login {
		background-image: url("{{ url('assets/images/loginbg.jpg') }}");
		background-repeat: no-repeat; background-size: cover;
		color: #fff; height: 525px;
	}
	a.B_NWih { color: #cce; }
	a.fggg { color: #cce;}
	footer ul.contacts li { color: #565656; }

	@media (max-width: 768px) {

		.left_login {
			width: 100%;
			padding: 40px 0px 1px 15px;
		}
		div.right_login {
			height: auto;
		}
		img.logo_normal {
			display: none;
		}

		.right_login {
			padding: 15px;
			width: 100%;
			float: none;
			margin: 0;
		}

		.left_login h3 {
			font-size: 24px;
		}

	}
</style>

<br />

<div class="login_divotp">

	<div class="left_login">
		<h3> Login</h3>
		<p> Get access to your Orders, <br>Wishlist and Recommendations</p>

		<img src="https://tolocal.shop/public/files/logo/1707207153lightLogoimage.webp" width="165" height="35" alt=""
			class="logo_normal">
	</div>

	<div class="right_login">
		<div class="main_title_3">

			@if (session('message'))
			<div class="alert alert-success in alert-dismissible" style="margin-top:10px;">
				<strong>Success!</strong> {{ session('message') }}
			</div>
			@endif
		</div>
		<div class="my-login-validation login_con">
			
			<div class="form-group">
				<label for="email">Please Enter Mobile number</label>
				<input id="phone_email" type="text" class="form-control" name="phone_email" placeholder="Enter Mobile number">
				<input type="text" class="form-control" name="otp" id="otp" placeholder="Otp" style="display:none;">
			</div>

			<div class="form-group m-0">
				<p>
					By continuing, you agree to Tolocal.shop's <a href="{{ url('terms-and-condition') }}" class="B_NWih"
						target="_blank">Terms of Use</a> and
					<a href="{{ url('privacy-policy') }}" class="B_NWih" target="_blank">Privacy Policy</a>.
				</p>
				<button id="getotp" class="btn btn-primary btn-block"> Request OTP </button>
				<button id="veifyotp" class="btn btn-primary btn-block verifyOtp" style="display:none;"> Verify OTP </button>
				
				<br>
				<br>
				<p> New to Tolocal.shop? <a href="{{ url('login') }}" class="fggg" target="_blank">Create an account</a>
				</p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

var baseUrl = '{{ url(' / ') }}';
    $(document).ready(function() {

		$("#getotp").click(function(event) {

			event.preventDefault();
			var register_phone = $('#phone_email').val();
			$('#getotp').prop('disabled', true);
			$('#getotp').html('Wait......');
			axios.post('/login-with-otp', {
					phone: register_phone,
				})
				.then(function(response) {
					console.log(response);
					if (response.data.status == 'error') {
						var myObj = response.data.error;
						$.each(myObj, function(key, val) {
							toastr.error('Error', val[0]);
						});
						$('#getotp').prop('disabled', false);
						$('#getotp').html('Request OTP');
					} else {
						toastr.success(response.data.msg);
						$("#veifyotp").show();
						$("#otp").show();
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
			var register_phone = $('#phone_email').val();
			axios.post('/verify-loginotp', {
					otp: otp,
					phone: register_phone,
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
						$('#veifyotp').html('Verify OTP');

					} else {

						$('#veifyotp').prop('disabled', false);
						$('#veifyotp').html('Verify OTP');
						if (response.data.isSuccess == 'true') {
							$('#phone_email').prop('readonly', true);
							$('#otp').prop('readonly', true);
							window.verify = 1;
							toastr.success('Login successful.');
							$('#veifyotp').css('border', '1px solid green');
							$('#veifyotp').css('color', '#128807');
							$('#veifyotp').html('Verified');
							$('#veifyotp').prop('disabled', true);
							setTimeout(function() 
							{
								window.location.href = '/';
							}, 3000);
						} else {
							toastr.error('Invalid OTP or OTP expired or Credentials not matched.');
						}
					}
				})
				.catch(function(error) {});
			return false;
		});

    });

</script>

@endsection