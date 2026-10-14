@extends('layouts.client.vendor')
@section('title','User Address')
@section('content')

<style>
	.default-address {
	    position: relative;
	    float: left;
	}
	.default-address input.pay {
	    position: relative;
	    left: 0;
	    width: 14px;
	    height: auto;
	    margin: 0;
	    float: none;
	}
	.custom-control.custom-checkbox.default-address label.custom-control-label {
	    padding: 0px !important;
	}
	.default-address input.pay:first-child {
	       margin-left: 15px;
	}
</style>

<!-- /sub_header -->

<!-- Main page -->
<main>
	<div class="container-fluid">
		<div class="row">
			<x-client.account_menu/>

			<div class="col-lg-9">
				<div class="row">
					<div class="col-md-12">
					<div class="sub_header_in sticky_header filter_sub_blue_header">	
						<div class="container">
							<h1>Add Address </h1>
						</div>
						<!-- /container -->
					</div>
					</div>

					<div class="col-md-12">
					@if(Session::has('error'))
						<div class="alert alert-danger">
						{{ Session::get('error') }}
						@php
						Session::forget('error');
						@endphp
						</div>
					@endif

					@if(Session::has('success'))
						<div class="alert alert-success">
						{{ Session::get('success') }}
						@php
							Session::forget('success');
						@endphp
						</div>
					@endif
					</div>
				

				<div class="col-lg-3 bg-light justify-content-center align-items-center vh-100">
					@include('account.profile_pic')
				</div>
				<div class="col-lg-8">
					<!--Main Area-->                  
					<div class="row">
						<div class="col-md-12">
							<form action="{{ route('address.storeAddress') }}" method="POST" class="profile_data" enctype="multipart/form-data">
								@csrf

								<div class="row">
									<div class="col-md-6">
										<div class="form-group mb-4">
											<label class="col-md-12 p-0">Billing and Shiping address are same</label> <input type="checkbox" name="sameBillingAddress">								
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group mb-4">
											<label class="col-md-12">Set Address as Default</label>
											<div class="custom-control custom-checkbox default-address">
												<input type="radio" class="pay" name="default_add" value="yes" id="pay">
												<label class="custom-control-label" for="1">Yes </label>
											</div>
											<div class="custom-control custom-checkbox default-address">
												<input type="radio" class="pay" name="default_add" value="no" style="margin-left: 20px;" id="nonpay" checked="">
												<label class="custom-control-label" for="1">No </label>
											</div>
										</div>
									</div>
									
								</div>

								<div class="form-group mb-4">
									<label class="col-md-12 p-0">Type</label>
									<div class="col-md-12 border-bottom p-0">
										<select name="type" id="type" class="form-control p-0 border-0" >
											<option value="shipping">Shipping</option>
											<option value="billing">Billing</option>
										</select>
									</div>								
								</div>
							<div class="row">	
								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">First Name<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="first_name" value="" placeholder="First name" class="form-control p-0 border-0">
									</div>
									<span class="text-danger">@error('first_name'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Last Name<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="last_name" value="" placeholder="Last name" class="form-control p-0 border-0">
									</div>
									<span class="text-danger">@error('last_name'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Phone<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="phone" value="" placeholder="Phone" class="form-control p-0 border-0">
									</div>
									<span class="text-danger">@error('phone'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Email<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="email" value="" placeholder="Email" class="form-control p-0 border-0">
									</div>
									<span class="text-danger">@error('email'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Address 1<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<textarea rows="5" name="address1" placeholder="Address" class="form-control p-0 border-0"></textarea>
									</div>
									<span class="text-danger">@error('address1'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Address 2</label>
									<div class="col-md-12 border-bottom p-0">
										<textarea rows="5" name="address2" placeholder="Address" class="form-control p-0 border-0"></textarea>
									</div>
									<span class="text-danger">@error('address2'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-4">
									<label class="col-md-12 p-0">Country<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 p-0">
									@php
									$countries = Helper::getCountryAllData();
									@endphp
									<select class="form-control select2" name="country" id="country">
										<option value="">Select Country</option>
										@foreach ($countries as $val)
										<option value="{{ $val->id }}">{{ $val->name }}</option>
										@endforeach
									</select>
									<input type="hidden" name="hiddencountry" id="hiddencountry">
										<?php /* ?><input type="text" name="country" value="" placeholder="Country" class="form-control p-0 border-0"><?php */ ?>
									</div>
									<span class="text-danger">@error('country'){{$message}} @enderror</span>
								</div>
								<div class="form-group mb-4 col-md-4">
									<label class="col-md-12 p-0">State<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">

									<select name="state" id="state" class="form-control">
										<option value="">Select State</option>
									</select>
										<input type="hidden" name="hiddenstate" id="hiddenstate">
										<?php /* ?><input type="text" name="state" value="" placeholder="State" class="form-control p-0 border-0"><?php */ ?>
									</div>
									<span class="text-danger">@error('state'){{$message}} @enderror</span>
								</div>
								<div class="form-group mb-4 col-md-4">
									<label class="col-md-12 p-0">City<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 p-0">
									<select class="form-control" name="city" id="city">
										<option value="">Select City</option>
									</select>
										<?php /* ?><input type="text" class="form-control input-group-sm mb-3" id="city-bkp" value="{{ old('city') }}" placeholder="Enter City" name="city">
										<input type="text" name="city" value="" placeholder="City" class="form-control p-0 border-0"><?php */ ?>
									</div>
									<span class="text-danger">@error('city'){{$message}} @enderror</span>
								</div>
							</div>

								<div class="form-group mb-4">
									<label class="col-md-12 p-0">Zipcode<span class="notranslate" style="color:red;">*</span></label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="zipcode" value="" placeholder="Zipcode" class="form-control p-0 border-0">
									</div>
									<span class="text-danger">@error('zipcode'){{$message}} @enderror</span>
								</div>
							
								<div class="form-group mb-4">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-info">Save</button>
									</div>
								</div>           
							</form>			
						</div>
					</div>              
			    </div>	
			</div>	
				<!-- /row -->				
			</div>		
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</main>
<script type="text/javascript">
    var baseUrl = '{{ url(' / ') }}';
    $(document).ready(function() {

        $("#country").select2({
            closeOnSelect: true
        });
        $("#state").select2({
            closeOnSelect: true
        });
		$("#city").select2({
            closeOnSelect: true
        });

        $(document).on('change', "#state", function() {
            var text = $("#state option:selected").text();
            $('#hiddenstate').val(text);
        });

        $(document).on('change', "#country", function() {
            var text = $("#country option:selected").text();
            console.log(text);
            $('#hiddencountry').val(text);

            $.ajax({
                url: baseUrl + '/get-states-by-country'
                , type: "POST"
                , data: {
                    country_id: $(this).val()
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#state').html('<option value="">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#state").append('<option value="' + value.id + '" >' + value.name + '</option>');
                    });
                }
            });
        });

		$('#state').on('change', function() {
            var state_id = this.value;
            $("#city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
					$('#city').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    
                }
            });
    	});
    });

	

    
        
</script>
@endsection