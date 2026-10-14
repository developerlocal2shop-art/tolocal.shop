@extends('layouts.client.contact')
@section('title','Cart | ToLocal.shop ')
@section('content')

	<div class="container margin_60_35">
		<div class="row justify-content-center thanks-msg">
			<div class="col-lg-6">
				<div class="margin_60_35">
					<div class="box_contacts">
						<i class="fa fa-suitcase" aria-hidden="true"></i>
						<h2>Congratulations! You’re now a part of the ToLocal.shop family.</h2>
						<h2>Thank you for your order!</h2>
						<p>Go to 
							<a href="{{ url('account/order') }}" style="font-weight: bold; color: #704aee; text-decoration: underline;">
							Orders list
						    </a> 
							to see your order status and information.</p>
					</div>
				</div>			
			</div>
		</div>
	</div>

@endsection