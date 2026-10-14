@extends('layouts.client.vendor')
@section('title','User Address')
@section('content')
<?php
	$addressData =  $data['addressData'];
?>

<!-- /sub_header -->
<style>
    .add-list{
        word-break: break-all;
    }
</style>
<!-- Main page -->
<main>
	<div class="container-fluid">
		<div class="row">
			<x-client.account_menu/>

			<div class="col-lg-9">

				<div class="row">
				<div class="col-lg-12">
					<div class="sub_header_in sticky_header filter_sub_blue_header">
						<div class="container">
							<h1>Address List</h1>
						</div>
						<!-- /container -->
					</div>
				</div>

				<div class="col-lg-3 bg-light justify-content-center align-items-center vh-100">

					@include('account.profile_pic')
					
				</div>
				<div class="col-lg-9">
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
						<a class="btn btn-success address_add_btn" href="{{ url('account/add-address') }}">Add Address</a><hr>
					</div>
				</div>

				<?php $i = 0; $count = count($addressData); if ($count>0){?>
					<div class="row">
						<?php foreach($addressData as $address){
							//echo "<pre>"; print_r($address);
							$shippingcountryName = Helper::getCountryName($address['country']);
							$shippingstateName = Helper::getStateName($address['state']);
							$shippingCityName = Helper::getCityName($address['city_id']);
							//echo "<pre>"; print_r($shippingCityName);die;
						?>
						<div class="col-md-6">
							
							<div class="add-list">
							<?php if($address['default_address']=="yes"){ ?>
								<b style="color: #11d511;">
									<p><?php echo $address['first_name']." ".$address['last_name']; ?><br>
										<?php echo $address['email'];?><br>
										<?php echo $address['phone'];?><br>
										<?php echo $address['address1'];?><br>
										<?php echo $address['address2'];?><br>
										<?php if($address['city']!="")echo $shippingCityName->name??''.",";?> <?php if($address['state']!="")echo $shippingstateName->name??'';?><br>
										<?php if($address['country']!="")echo $shippingcountryName->name??'';?> - <?php echo $address['zipcode'];?>
									</p>
								</b>
							<?php }else{ ?>
								<p><?php echo $address['first_name']." ".$address['last_name']; ?><br>
									<?php echo $address['email'];?><br>
									<?php echo $address['phone'];?><br>
									<?php echo $address['address1'];?><br>
									<?php echo $address['address2'];?><br>
									<?php if($address['city']!="")echo $shippingCityName->name??''.",";?> <?php if($address['state']!="")echo $shippingstateName->name??'';?><br>
									<?php if($address['country']!="")echo $shippingcountryName->name??'';?> - <?php echo $address['zipcode'];?>
								</p>
							<?php } ?>
							
							<p><a class="btn btn-primary btn-sm" href="<?php echo url("/account/edit-address/".$address['id']); ?>">Edit</a>
							<a class="btn btn-danger btn-sm show_confirm" onclick="return confirm('Are you sure?')" href="<?php echo url("/account/delete-address/".$address['id']); ?>">Delete</a></p>
						</div>
						</div>
						<?php }?>
					</div>
				<?php }else{ ?>

					<div class="row">
						<div class="col-md-12">
							<p>There is no address</p>
						</div>
					</div>
				<?php } ?>

			    </div>
			</div>
				<!-- /row -->
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</main>

@endsection
