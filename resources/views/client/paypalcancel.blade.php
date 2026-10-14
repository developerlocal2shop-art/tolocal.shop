@extends('layouts.client.contact')
@section('title','Cart | ToLocal.shop ')
@section('content')

	<div class="container margin_60_35">
		<div class="row justify-content-center thanks-msg">
			<div class="col-lg-12">
				<div class="margin_60_35">
					<div class="card shadow text-center">
					<div class="card-body">
						<h2 class="text-danger mb-3">Payment Cancelled</h2>
						<p class="mb-4">
							Your PayPal payment was cancelled.  
							No amount has been deducted from your account.
						</p>
						<a href="{{ route('checkout') }}" class="btn btn-primary">
							Try Again
						</a>
						<a href="{{ url('/') }}" class="btn btn-outline-secondary ms-2">
							Go to Home
						</a>
					</div>
				</div>
				</div>			
			</div>
		</div>
	</div>

@endsection