@extends('layouts.client.company')
@section('title','User Profile')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
	<div class="container asd">
		<div class="row">
			<h1 style="display:inline-block;">Member Account</h1>
		</div>
			@if($directoryBasicData->status=="Active")
			<button type="button" style="border-radius: 15px;margin-left: 10px;" class="btn btn-success btn-sm">Active</button>
			@endif	
		<x-client.company_account_menu/>
	</div>
	<!-- /container -->
</div>
<!-- /sub_header -->
<!-- Main page -->
<main>
	<div class="container margin_60_35">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-md-12">
					@if(Session::has('error'))
						<div class="alert alert-danger">
						{{ Session::get('error') }}
						@php
						Session::forget('error');
						@endphp
						</div>
					@endif
					</div>
				</div>
					<!--Main Area-->
					<form action="{{ route('account.submitProfile') }}" method="POST" class="" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group mb-4">
									<label class="col-md-12 p-0">First Name</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="name" value="{{ Auth::user()->name??'' }}" placeholder="Name" class="form-control border-0">
									</div>
									<span class="text-danger">@error('name'){{$message}} @enderror</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group mb-4">
									<label class="col-md-12 p-0">Last Name</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="last_name" value="{{ Auth::user()->last_name??'' }}" placeholder="Name" class="form-control border-0">
									</div>
									<span class="text-danger">@error('last_name'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4">
									<label class="col-md-12 p-0">Email</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="email" value="{{ Auth::user()->email??'' }}" placeholder="Email" class="form-control border-0">
									</div>
									<span class="text-danger">@error('email'){{$message}} @enderror</span>
								</div>
								<div class="form-group mb-4">
									<label class="col-md-12 p-0">{{__("message.phone_no_text")}}</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="phone" value="{{ Auth::user()->phone??'' }}" placeholder="{{__('message.placeholder_phone_no_text')}}" class="form-control border-0">
									</div>
									<span class="text-danger">@error('phone'){{$message}} @enderror</span>
								</div>
								<div class="form-group mb-4">
										<button type="submit" class="btn btn-info">Save</button>
								</div>
							</div>
						</div>                    
					</form>			
				</div>
				<!-- /row -->				
			</div>	
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</main>
@endsection