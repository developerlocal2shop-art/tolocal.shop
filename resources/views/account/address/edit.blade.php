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
					<div class="col-lg-12">
					<div class="sub_header_in sticky_header filter_sub_blue_header">	
						<?php 
						$addressData =  $data['addressData']; 
						?>
						<div class="container">
							<h1>Address Edit</h1>
						</div>
						<!-- /container -->
					</div>
				</div>

				<div class="col-lg-3 bg-light justify-content-center align-items-center vh-100">

					@include('account.profile_pic')
					
				</div>
				<div class="col-lg-8">

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

					@if(Session::has('success'))
						<div class="alert alert-success">
						{{ Session::get('success') }}
						@php
							Session::forget('success');
						@endphp
						</div>
					@endif
					</div>
				</div>


				<!--Main Area-->                  
				<div class="row">
					<div class="col-md-12">
						<form action="{{ route('address.updateAddress') }}" method="POST" class="profile_data" enctype="multipart/form-data">
                   			@csrf
							<input type="hidden" name="userid" value="{{ $addressData['user_id'] }}" />
							<div class="row">
								<div class="col-md-6">
									<div class="form-group mb-4">
										<label class="col-md-12 p-0">Billing and Shiping address are same</label> 
										<input type="checkbox" name="sameBillingAddress" <?php if($addressData['sameBillingAddress']=='on'){ echo "checked";}?>>								
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group mb-4">
										<label class="col-md-12">Set Address as Default</label>
										<div class="custom-control custom-checkbox default-address">
											<input type="radio" class="pay" name="default_add" value="yes" {{ $addressData['default_address']=='yes' ? 'checked' : '' }}>
											<label class="custom-control-label" for="1">Yes </label>
										</div>
										<div class="custom-control custom-checkbox default-address">
											<input type="radio" class="pay" name="default_add" value="no" style="margin-left: 20px;" {{ $addressData['default_address']=='no' ? 'checked' : '' }}>
											<label class="custom-control-label" for="1">No </label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group mb-4">
								<label class="col-md-12 p-0">Type</label>
								<div class="col-md-12 border-bottom p-0">
									<select name="type" id="type" class="form-control border-0" >
										<option value="shipping"<?php if($addressData['type']=='shipping'){ echo "selected";}?>>Shipping</option>
										<option value="billing" <?php if($addressData['type']=='billing'){ echo "selected";}?>>Billing</option>
									</select>
								</div>								
							</div>

							<div class="row">
								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">First Name</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="first_name" value="<?php echo $addressData['first_name'];?>" placeholder="First name" class="form-control border-0">
									</div>
									<span class="text-danger">@error('first_name'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Last Name</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="last_name" value="<?php echo $addressData['last_name'];?>" placeholder="Last name" class="form-control border-0">
									</div>
									<span class="text-danger">@error('last_name'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Phone</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="phone" value="<?php echo $addressData['phone'];?>" placeholder="Phone" class="form-control border-0">
									</div>
									<span class="text-danger">@error('phone'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Email</label>
									<div class="col-md-12 border-bottom p-0">
										<input type="text" name="email" value="<?php echo $addressData['email'];?>" placeholder="Email" class="form-control border-0">
									</div>
									<span class="text-danger">@error('email'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Address 1</label>
									<div class="col-md-12 border-bottom p-0">
										<textarea rows="5" name="address1" placeholder="Address" class="form-control border-0"><?php echo $addressData['address1'];?></textarea>
									</div>
									<span class="text-danger">@error('address1'){{$message}} @enderror</span>
								</div>

								<div class="form-group mb-4 col-md-6">
									<label class="col-md-12 p-0">Address 2</label>
									<div class="col-md-12 border-bottom p-0">
										<textarea rows="5" name="address2" placeholder="Address" class="form-control border-0"><?php echo $addressData['address2'];?></textarea>
									</div>
									<span class="text-danger">@error('address2'){{$message}} @enderror</span>
								</div>

							</div>

							

							

						<?php $shippingcountryName = Helper::getCountryName($addressData['country']);
						//echo "COUNTRY NAME: ".$shippingcountryName;echo "COUNTRY NAME: ".$addressData['country'];die;
						$shippingstateName = Helper::getStateName($addressData['state']);
						$shippingCityName = Helper::getCityName($addressData['city']); ?>

						<div class="row">
							<div class="form-group mb-4 col-md-4">
								<label class="col-md-12 p-0">Country</label>
								<div class="col-md-12 p-0">
								@php
								$countries = Helper::getCountryAllData();
								@endphp
								<select class="form-control select2" name="country" id="country">
									<option value="">Select Country</option>
									@foreach ($countries as $val)
									<option value="{{ $val->id }}" {{ $val->id == $addressData['country'] ? 'selected' : '' }}>{{ $val->name }}</option>
									@endforeach
								</select>
								<input type="hidden" name="hiddencountry" id="hiddencountry">
								<?php /* ?><input type="text" name="country" value="<?php echo $addressData['country'];?>" placeholder="Country" class="form-control border-0"><?php */ ?>
								</div>
								<span class="text-danger">@error('country'){{$message}} @enderror</span>
							</div>

							<div class="form-group mb-4 col-md-4">
								<label class="col-md-12 p-0">State</label>
								<div class="col-md-12 p-0">
										@php
										$states = Helper::getAllState($addressData['country']);
										@endphp
										<select name="state" id="state" class="form-control">
										<option value="">Select State</option>
											@foreach ($states as $val)
												<option value="{{ $val['id'] }}" {{ $val['id'] == $addressData['state'] ? 'selected' : '' }}>{{ $val['name'] }}</option>
											@endforeach
										</select>
									<?php /* ?><input type="text" name="state" value="<?php echo $addressData['state'];?>" placeholder="State" class="form-control border-0"><?php */ ?>
								</div>
								<span class="text-danger">@error('state'){{$message}} @enderror</span>
							</div>

							<div class="form-group mb-4 col-md-4">
								<label class="col-md-12 p-0">City</label>
								<div class="col-md-12 p-0">
										@php
										$cities = Helper::getAllCityByState($addressData['state']);
										@endphp
										<select name="city" id="city" class="form-control">
											<option value="">Select City</option>
											@foreach ($cities as $val)
												<option value="{{ $val['id'] }}" {{ $val['id'] == $addressData['city_id'] ? 'selected' : '' }}>{{ $val['name'] }}</option>
											@endforeach
										</select>
									<?php /* ?><input type="text" name="city" value="<?php echo $addressData['city'];?>" placeholder="City" class="form-control border-0"><?php */ ?>
								</div>
								<span class="text-danger">@error('city'){{$message}} @enderror</span>
							</div>
						</div>	

							<div class="form-group mb-4">
								<label class="col-md-12 p-0">Zipcode</label>
								<div class="col-md-12 border-bottom p-0">
									<input type="text" name="zipcode" value="<?php echo $addressData['zipcode'];?>" placeholder="Zipcode" class="form-control border-0">
								</div>
								<span class="text-danger">@error('zipcode'){{$message}} @enderror</span>
							</div>
							
							<div class="form-group mb-4">
								<div class="col-sm-12">
									<input type="hidden" name="id" id="id" value="<?php echo $addressData['id'];?>">
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
					$('#city').html('<option value="">Select City</option>');
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