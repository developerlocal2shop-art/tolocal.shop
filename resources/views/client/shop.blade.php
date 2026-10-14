@extends('layouts.client.blog')
@section('title','Shop | Listing')
@section('content')
</a>
<style type="text/css">

	.strip figure .read_more span {
		background-color: rgba(255, 255, 255, .08);
		padding: 0px 0px 0px 0px;
	}

	.new-shopping-offer {
		max-width: 78px;
	}
	.assured {
		text-align: right;
		margin: -30px 0px 0px 0px;
	}
    .add-to-compare {
		margin: 0px 3px 0px 0px;
		text-align: right;
		cursor: pointer;
		width: 212px;
	}
	.sub_header_in.sticky_header {
		padding-bottom: 20px;
	}

	.new_offers {
		right: 25px;
	}
	.custom-search-input-2.inner input[type=submit] {
		right: 5px;
	}
	.compare_checkbox {
		margin: 5px 6px;
		position: absolute;
	}

	.shoplisting_div {
		margin: 0 auto;
	}
	.featured-tag-border{
		border: 1px solid #f7f5ed;
	}
	.recent_viewed {
		margin-bottom: 20px;
	}
    .strip .wrapper h3 a {
		line-height: 40px;
		font-size: 15px;
		color: #2a2a3c;
	}
	.strip .wrapper h3 {
		min-height: 40px;
	}
	.strip .wrapper p {
		margin-bottom: 5px;
	}
	.filter_type { margin-top: 25px; }
    .custom-search-input-2 { background-color: unset; }
	.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 40px; }
	a { color: #004dda; }
	a#tags-toggle-btn { margin: 5px 2px; }
	.nice-select.wide2 { display: none; }
	.select2-container { display: table-cell; }
	.select2-container--default .select2-selection--single { height: 40px; }
	.select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; }
	.acc_shop_filter #accordion.tree-stuc .area { border-left: none; border-bottom: none; }
	.acc_shop_filter #accordion.tree-stuc .card-header { padding: 5px 15px; }
	.acc_shop_filter #accordion.tree-stuc .ponto h5 span label {margin-bottom: 5px;}
	.distance_div {	margin-bottom: 10px; }
	.select2-container .select2-selection--single .select2-selection__rendered {
		border: 1px solid #ccc;
		margin: 0px -1px;
	}
	.bluefilter { padding-top: 20px!important; }
	.pagination {
		padding-left: 0;
	}
	.wishlist-icon {
		top: 6px;
		color: #abaab0 !important;
		background: none !important;
		padding: 3px 5px;
		font-size: 25px;
	}
	.wishlist-icon:hover {
		background-color: rgb(0, 0, 0);
		color: #abaab0 !important;
	}
	.fa-heart-o:before {
		content: "\f08a";
		color: #abaab0;
	}

	div#listings .strip .wrapper {
		padding: 1px 10px;
	}

	@media (max-width: 768px) {

		.assured {
			display: none;
		}
		.compare_checkbox {
			margin: 3px 10px;
		}
		.add-to-compare {
			margin: 1px 20px 0px 0px;
			width: auto;
		}
		.products_comparison img{
			margin: 0px auto !important;
			width: 33px !important;
			text-align: center;
		}
		.product-color span.color {
			margin-left: -30px !important;
			line-height: 1.6;
			width: 85% !important;
			margin-bottom: 20px !important;
		}
		.add-to-compare img {
			margin: 0px 5px;
		}
		.recent_viewed {
			padding-bottom: 35px !important;
		}
		.shoplisting_div {
			-ms-flex: 0 0 50%;
			flex: 0 0 50%;
			max-width: 50%;
			margin: 0px !important;
			padding: 2px 2px 0px 2px;
		}
		.header_search_offer{
			display:none;
		}

		.pagination a, .pagination span {
			font-size: 13px;
			height: 20px;
			line-height: 22px;
			min-width: 20px;
		}
		.header_search_catogory{
			display: none;
		}
		.select_city_home {
			width: 50%;
			float: left;
		}
		.distance_div {
			width: 50%;
		}
		.btn_search_mobile_div{
			text-align: center;
		}
		.sub_header_in.sticky_header {
			margin-top: 40px;
		}
		.custom-search-input-2.inner input[type='submit'] {
			width: 50%;
			border-radius: 25px !important;
			height: 32px;
		}
		.bluefilter {
			padding-top: 50px!important;
		}
		.strip .wrapper p {
			margin-bottom: 2px;
		}
		div#listings .strip ul {
			padding: 5px 5px 5px 10px;
			bottom: 0;
		}
		.wishlist-icon {
			right: 10px;
			top: 0px;
			padding: 5px 5px;
		}
		.strip {
			margin-bottom: 10px;
		}
		.strip .wrapper h3 {
			font-size: 13px;
			margin: 0 0 0px;
			min-height: 40px;
		}
		#filters_col {
			padding: 5px 10px 5px 10px;
			margin-bottom: 10px;
		}
		.select2-container .select2-selection--single {
			height: 31px !important;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 30px;
		}
		.custom-search-input-2.inner i {
			height: 30px;
			line-height: 30px;
			font-size: 14px;
		}
		.custom-search-input-2.inner .nice-select {
			height: 30px;
			line-height: 27px;
			padding: 2px;
			font-size: 13px;
		}
		.custom-search-input-2.inner input {
			height: 31px;
			font-size: 13px;
		}
		.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 29px;
			font-size: 12px;
		}
		select#distance {
			height: 31px;
			line-height: 4px;
			font-size: 12px;
			padding: 5px;
		}

	}

	@media (max-width: 432px) {
		.shoplisting_div .strip figure {
			max-height: 240px;
		}
		.product-color span.color {
			margin-left: -30px !important;
			line-height: 1.6;
			width: 85% !important;
			margin-bottom: 20px !important;
		}

	}

	.shop-empty-state {
		display: flex;
		min-height: 300px;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		margin: 18px 0 28px;
		padding: 48px 24px;
		text-align: center;
		background: linear-gradient(145deg, #fff 0%, #f7f9fc 100%);
		border: 1px solid #e7ebf1;
		border-radius: 14px;
		box-shadow: 0 8px 26px rgba(34, 54, 82, .06);
	}
	.shop-empty-state__icon {
		display: flex;
		width: 68px;
		height: 68px;
		align-items: center;
		justify-content: center;
		margin-bottom: 18px;
		color: #304d7c;
		background: #eaf0f8;
		border-radius: 50%;
		font-size: 27px;
	}
	.shop-empty-state h2 {
		margin: 0 0 9px;
		color: #26364b;
		font-size: 25px;
		font-weight: 700;
		line-height: 1.3;
	}
	.shop-empty-state p {
		max-width: 420px;
		margin: 0;
		color: #697789;
		font-size: 15px;
		line-height: 1.6;
	}
	@media (max-width: 575.98px) {
		.shop-empty-state {
			min-height: 250px;
			margin: 12px 0 22px;
			padding: 36px 18px;
		}
		.shop-empty-state h2 {
			font-size: 21px;
		}
	}

</style>
<?php


	$defaultCurrency = Helper::defaultCurrencyData();
	if(isset($data)){ $productData = $data; }
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}

	$wislistProductId=array();
	foreach($wishlists as $wishid){
		$wislistProductId[]=$wishid['product_id'];
	}

	$AddToCompareProductId=array();
	foreach($AddToCompare as $addtc){
		$AddToCompareProductId[]=$addtc['product_id'];
	}

	$search_string = request()->search_string;
	if((request()->cat)>0){
		$cat = request()->cat;
	}else{
		$cat = "";
	}
	if((request()->filters)){
		$filters = request()->filters;
	}else{
		$filters = "";
	}
?>
<form id="latlongform" action="{{route('pages')}}" enctype="multipart/form-data" method="get">
    @csrf
    <input type="hidden" id="lati" name="lati" >
    <input type="hidden" id="long" name="long" >
</form>
<div class="sub_header_in filter_sub_blue_header sticky_header">
	<div class="container-fluid" style="padding: 0px 25px;">
		<div class="row">
			<div class="col-lg-2 col-md-2">
				<a href="{{ url('/shop') }}"><h1 style="font-size: 16px; margin: 9px 0px;"> <i class="fa fa-hand-point-right"></i> {{__("Shop")}}</h1></a>
			</div>
			<div class="col-lg-10 col-md-10">
				<!-- <a href="#0" class="search_mob btn_search_mobile"></a>  -->
				<form action="{{ route('shop') }}" method="GET">
					<div class="row no-gutters custom-search-input-2 inner">
						<div class="col-lg-5 col-md-5">
							<div class="form-group">
								<input class="form-control" id="search_string" autocomplete="off" name="search_string"
									type="text" value="{{ isset($search_string)?$search_string:''; }}"
									placeholder="{{__('message.header_search_text')}}(Products, ENA)">
								<i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording"></i>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 header_search_catogory">
							<select class="wide cat" name="cat">
								<option value="all">{{__("message.header_search_catogory_text")}}</option>
								<?php foreach($allParentcategory as $parentcat){ ?>
								<option value="{{ $parentcat->id }}" {{ $parentcat->id == $cat ? 'selected' : '' }}>{{
									$parentcat->name }}</option>
								<?php } ?>
							</select>
						</div>
						<div class="col-lg-2 col-md-2 header_search_offer">
							<select class="wide" name="filters">
								<option value="">Select Filters</option>
								<option value="shoppingcard" {{ 'shoppingcard'==$filters ? 'selected' :''}}>Shopping
									card</option>
								<option value="offer" {{ 'offer'==$filters ? 'selected' :''}}>Offer</option>
								<option value="latestproduct" {{ 'latestproduct'==$filters ? 'selected' :''}}>Latest
									Product</option>
								<option value="mostpopular" {{ 'mostpopular'==$filters ? 'selected' :''}}>Most Popular
								</option>
							</select>
						</div>
						<div class="col-lg-1 col-md-1 btn_search_mobile_div">
							<input type="submit" value="{{__('message.header_search_text_search_btn')}}"
								class="desktop-search btn_search_mobile_submit">
							<input type="submit" value="{{__('message.header_search_text_search_btn')}}"
								class="search_mob btn_search_mobile btn_search_mobile_submit" placeholder="Search">
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>
<!-- /Map -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35 bluefilter">
	<div class="row justify-content-md-center">

		<x-ClientfilterShop :productData="$pProductSubCatData" />
		<div class="col-xl-9 col-md-12 order-xl-1 order-2" id="listings">
			<div class="row">

				@if(!empty($categoryUrls) || !empty($subcategoryUrls) || !empty($producttagsUrls))

			<div class="col-md-12" id="listing_hierarchical">
				<p><a class="sk_fade" style="color: #da3429;" href="{{ url('/listings') }}">{{__("message.menu_text_listing")}}</a>
					>
					@php
					if(!empty($_GET['search_string'])){
						echo $_GET['search_string'].' > ';
					}
					@endphp
					@foreach ($categoryUrls as $val)
					@php
					$getCatname = Helper::getProductCatnameIdLangId($val,$language_id);
					@endphp
					{{ $getCatname }} >
					@endforeach

					@foreach ($subcategoryUrls as $val)
					@php
					$getSubCatname = Helper::getProductCatnameIdLangId($val,$language_id);
					@endphp
					{{ $getSubCatname }}
					>
					@endforeach
					@foreach ($producttagsUrls as $val)
					@php
					$getTagname = Helper::getProductTagnameIdLangId($val,$language_id);
					@endphp

					{{ $getTagname }}
					@if (!$loop->last)
					>
					@endif
					@endforeach
				</p>
			</div>
		@endif

				<div class="col-lg-5">
					@if ($productData->count() > 0)
					<div class="d-flex notranslate">
						<p class="text-right">
							Showing {{ $productData->firstItem() }} - {{ $productData->lastItem() }} of {{ $productData->total() }} products
						</p>
					</div>
					@endif
				<!-- <p class="text-right" style="cursor: pointer;font-weight: 600;" id="filter_btn">Show Filters</p> -->
				</div>
				<div class="col-lg-3 col-md-3 select_city_home notranslate">
					<select class="wide2 notranslate" name="city_name" id="city_name" class="form-control" onChange="getchangeTheSessionData(this)" style="width: 200px;">
						{{-- @if(session()->get('filter_city'))
						<option value="{{ session()->get('filter_city') }}">
							{{ session()->get('filter_city') }}
						</option>
						@endif --}}
					</select>
				</div>
				<div class="col-lg-3 col-md-3 distance_div">
					<select class="form-control"  name="distance" id="distance" onChange="updateSessionDistance(this)" >
						{{-- <option value="0">Select distance in KM</option> --}}
						@foreach ($distancesetting as $disval)
							@if(session('distance'))
								@php
									$selected = session('distance');
								@endphp
							@else
								@php
									if($disval->default_distance == 'yes'){
										$selected = $disval->to_distance;
									}else{
										$selected = '';
									}
								@endphp
							@endif
							<option value="{{ $disval->to_distance??'' }}" @if($selected == $disval->to_distance) selected @endif>0 - {{ $disval->to_distance??'' }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-lg-1 col-md-1">
					<span title="Products Comparison" class="products_comparison"> {{-- <i class="fa fa-exchange" style="color: #ffc107;"></i>   --}}
						<a href="{{ url('product-comparison') }}" target="_blank">
							<img src="{{ asset('assets/images/compare.png') }}" class="img-fluid" style="margin: 8px 14px;width: 36px;">
						</a>
					</span>
				</div>

				<?php
				if(count($productData) > 0){
					foreach($productData as $productrow){

					$productprice = Helper::getProductprice($productrow->id,$currencycode);
					$validityOfOffer = Helper::getValidityOfOffer($productrow->id);
					$validityOfOfferShoppingCard = Helper::getValidityOfShoppingCardOffer($productrow->id);

					$productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
					$productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
					$productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;

					if(!empty($_GET['search_string'])){
						Helper::search_visitors('product',$productrow->id);
					}

                    if($productRegularPrice > 0){

					if(count($validityOfOffer) > 0 && count($validityOfOfferShoppingCard) > 0){

						if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOffer[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice22 = $new_width;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOffer[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount'){
								$productSalesPrice22 = $validityOfOfferShoppingCard[0]->discount_firstvalue;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount'){
								$productSalesPrice22 = $validityOfOffer[0]->discount_firstvalue;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice.'.00';
						}else{
							$priceGoestoCart = $productRegularPrice;
						}

					}elseif(!empty($validityOfOffer)){

						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice;
						}else{
							$priceGoestoCart = $productRegularPrice - $validityOfOffer[0]->discount_firstvalue;
						}
					}elseif(!empty($validityOfOfferShoppingCard)){
						if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
							$productSalesPrice = $productRegularPrice - $new_width;
						}else{
							$productSalesPrice = $productRegularPrice - $validityOfOfferShoppingCard[0]->discount_firstvalue.'.00';
						}
						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice;
						}else{
							$priceGoestoCart = $productRegularPrice;
						}
					}else{
						$priceGoestoCart = $productRegularPrice;
					}
					?>
				<div class="col-lg-3 col-md-3 listing-div shoplisting_div product_listing" data-aos-duration="1000" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
					data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
					<div class="strip grid featured-tag-border allshops">
						<?php
						$getNewShopping = Helper::getNewShopping($productrow->id);
						$getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
						$getNewOffer = Helper::getNewOffer($productrow->id);
						$getProductOffers = Helper::getProductOffers($productrow->id);
						?>
						@if($getNewOffer == 1 || $getNewShopping == 1)
						<img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid new_offers" alt=""
							width="400" height="266">
						@endif
						@if($getProductOffers == 1 || $getNewShoppingOffer == 1)
						{{-- <img src="{{ asset('assets/client/images/sho_offer.png') }}"
							class="img-fluid new-shopping-offer" alt="" width="400" height="266"> --}}
						<span style="
						background: rgb(231, 248, 236); color:rgb(88 168 65);
						padding: 1px 4px;
						border-radius: 2px;
						font-size: 13px;
						float: left;
						margin: -1px 0px;
						">Hot Deal</span>
						@endif

						<div class="add-to-compare" title="Add To Compare">
							{{-- <?php //if(!in_array($productrow->id, $AddToCompareProductId)){ ?>
								<input type="checkbox"  class="compare_checkbox" productid="{{ $productrow->id }}" onclick="addToCompare(this, {{ $productrow->id }})" id="Tick" name="compare[]">
							<?php //}else{ ?>
								<input type="checkbox" checked class="compare_checkbox" productid="{{ $productrow->id }}" onclick="addToCompare(this, {{ $productrow->id }})" id="Tick" name="compare[]">
							<?php //} ?> --}}
							<span>
								<i onclick="addToCompare(this, {{ $productrow->id }})" class="fa fa-exchange" style="color: rgb(234 67 53);"></i>
								{{-- <img src="{{ asset('assets/images/compare.png') }}" class="img-fluid" alt="" width="25" height="20"> --}}
							</span>
						</div>

						<figure>
							<input class="product-wishlist" type="hidden" value="{{ $productrow->id }}"
								name="product_id" id="product_id">
							<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
							<?php $imagename = Helper::getProductImage($productrow->id); ?>

							<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>"
								class="d-block h-100 img" <?php if(!empty($imagename)){ ?>
								style="background-image:url('{{
								env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
								<?php }else{ ?>
								style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')"
								<?php } ?>
								>
								<div class="read_more">
									@php
									$validityOfOffer = Helper::getValidityOfOffer($productrow->id);
									$validityOfOfferShoppingCard =
									Helper::getValidityOfShoppingCardOffer($productrow->id);
									@endphp
									<button class="btn vieweye_alignment"><i class=" notranslate fa fa-eye"></i>
										<span>View More</span>
									</button>

								</div>

							</a>


						</figure>
						<div class="wrapper featured-body">
							<h3 class="ellipsis">
								<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">
									{{
									($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name
									}}</a>
							</h3>
							<p class="ellipsis">
								@if($productrow->short_content)
								{{ \Illuminate\Support\Str::limit(strip_tags($productrow->short_content), 15, '...') }}
								@else
									.
								@endif
								<div class="assured">
									<img height="18" src="{{ url('assets/images/adobe_icon.png') }}">
									<span style="font-family: italic;color: #6a62c0;">Assured</span>
								</div>
							</p>

						</div>
						<ul class="featured-footer mb-0">
							<?php if(!empty($validityOfOffer)){  ?>
							<li>
								<?php if($productSalesPrice>0){ ?>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productSalesPrice }}</span></div>
								<?php } ?>
								<?php if($productRegularPrice>0 && $productSalesPrice==0){?>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
								<?php }else if($productRegularPrice>0){ ?>
								<div class="pro-price"><span class="old-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
								<?php } ?>
							</li>
							<?php }else{ ?>
							<li>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
							</li>
							<?php } ?>
							<a href="javascript:void(0);" productid="{{ $productrow->id }}"
								class="wishlist-icon notranslate"
								onclick="addToWishList(this, <?php echo $productrow->id ?>)">
								<?php if(!in_array($productrow->id, $wislistProductId)){ ?>
								<i class="fa fa-heart-o" aria-hidden="true"></i>
								<?php }else{ ?>
								<i class="fa fa-heart" aria-hidden="true"></i>
								<?php } ?>
							</a>
						</ul>
					</div>
				</div>
				<?php } } }else{ ?>
				<div class="shop-empty-state" role="status">
					<div class="shop-empty-state__icon" aria-hidden="true">
						<i class="fas fa-search"></i>
					</div>
					<h2>{{__("message.no_record_found_text")}}</h2>
					<p>Try adjusting your search or filters to find what you’re looking for.</p>
				</div>
				<?php } ?>

			</div>
			<span class="notranslate">
				{{$productData->links('pagination::bootstrap-4')}}
			</span>
		</div>


		<div class="col-xl-12 col-md-12 order-xl-1 order-2" >
			<?php
			if(count($recentview_products) > 0){
			?>
			<div class="main_title_2">
				<br>
				<h2>{{__("Recently Viewed Products")}}</h2>
				<span><em></em></span>
			</div>
			<div class="row">

				<?php

					foreach($recentview_products as $productrow){

					$productprice = Helper::getProductprice($productrow->id,$currencycode);
					$validityOfOffer = Helper::getValidityOfOffer($productrow->id);
					$validityOfOfferShoppingCard = Helper::getValidityOfShoppingCardOffer($productrow->id);

					$productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
					$productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
					$productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;

                    if($productRegularPrice > 0){

					if(count($validityOfOffer) > 0 && count($validityOfOfferShoppingCard) > 0){

						if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOffer[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice22 = $new_width;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOffer[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount'){
								$productSalesPrice22 = $validityOfOfferShoppingCard[0]->discount_firstvalue;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount' && $validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							if ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount'){
								$productSalesPrice22 = $validityOfOffer[0]->discount_firstvalue;
							}
							if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
								$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
								$productSalesPrice11 =  $new_width;
							}
							$productSalesPrice = $productRegularPrice - $productSalesPrice11 - $productSalesPrice22;
						}

						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice.'.00';
						}else{
							$priceGoestoCart = $productRegularPrice;
						}

					}elseif(!empty($validityOfOffer)){

						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice;
						}else{
							$priceGoestoCart = $productRegularPrice - $validityOfOffer[0]->discount_firstvalue;
						}
					}elseif(!empty($validityOfOfferShoppingCard)){
						if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount'){
							$new_width = ($validityOfOfferShoppingCard[0]->discount_firstvalue / 100) * $productRegularPrice;
							$productSalesPrice = $productRegularPrice - $new_width;
						}else{
							$productSalesPrice = $productRegularPrice - $validityOfOfferShoppingCard[0]->discount_firstvalue.'.00';
						}
						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice;
						}else{
							$priceGoestoCart = $productRegularPrice;
						}
					}else{
						$priceGoestoCart = $productRegularPrice;
					}
					?>
				<div class="col-lg-2 col-md-2 listing-div shoplisting_div recent_viewed" data-aos-duration="1000" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
					data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
					<div class="strip grid featured-tag-border allshops">
						<?php
						$getNewShopping = Helper::getNewShopping($productrow->id);
						$getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
						$getNewOffer = Helper::getNewOffer($productrow->id);
						$getProductOffers = Helper::getProductOffers($productrow->id);
						?>
						@if($getNewOffer == 1 || $getNewShopping == 1)
						<img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid new_offers" alt=""
							width="400" height="266">
						@endif

						<figure>
							@if($getProductOffers == 1 || $getNewShoppingOffer == 1)
							{{-- <img src="{{ asset('assets/client/images/sho_offer.png') }}"
								class="img-fluid new-shopping-offer" alt="" width="400" height="266">  --}}
								<div style="
								background: rgb(231, 248, 236); color:rgb(88 168 65);
								padding: 1px 4px;
								border-radius: 2px;
								font-size: 13px;
								float: left;
								margin: 0px;
								">Hot Deal
								</div>

							@endif
							<input class="product-wishlist" type="hidden" value="{{ $productrow->id }}"
								name="product_id" id="product_id">
							<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
							<?php $imagename = Helper::getProductImage($productrow->id); ?>
							<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>"
								class="d-block h-100 img" <?php if(!empty($imagename)){ ?>
								style="background-image:url('{{
								env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
								<?php }else{ ?>
								style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')"
								<?php } ?>
								>
								<div class="read_more">
									@php
									$validityOfOffer = Helper::getValidityOfOffer($productrow->id);
									$validityOfOfferShoppingCard =
									Helper::getValidityOfShoppingCardOffer($productrow->id);
									@endphp
									<button class="btn vieweye_alignment"><i class=" notranslate fa fa-eye"></i>
										<span>View More</span> </button>
								</div>
								<div class="pro-icn">
									{{-- <a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a> --}}
									<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
								</div>
							</a>
						</figure>
						<div class="wrapper featured-body">
							<h3 class="ellipsis">
								<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">
									{{
									($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name
									}}</a>
							</h3>

							<p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
						</div>
						<ul class="featured-footer mb-0">
							<?php if(!empty($validityOfOffer)){  ?>
							<li>
								<?php if($productSalesPrice>0){ ?>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productSalesPrice }}</span></div>
								<?php } ?>
								<?php if($productRegularPrice>0 && $productSalesPrice==0){?>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
								<?php }else if($productRegularPrice>0){ ?>
								<div class="pro-price"><span class="old-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
								<?php } ?>
							</li>
							<?php }else{ ?>
							<li>
								<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol."
										".$productRegularPrice }}</span></div>
							</li>
							<?php } ?>
							<a href="javascript:void(0);" productid="{{ $productrow->id }}"
								class="wishlist-icon notranslate"
								onclick="addToWishList(this, <?php echo $productrow->id ?>)">
								<?php if(!in_array($productrow->id, $wislistProductId)){ ?>
								<i class="fa fa-heart-o" aria-hidden="true"></i>
								<?php }else{ ?>
								<i class="fa fa-heart" aria-hidden="true"></i>
								<?php } ?>
							</a>
						</ul>
					</div>
				</div>
				<?php } }  ?>
			</div>
			<?php } ?>
		</div>


	</div>
</div>
</div>

<script>

	function addToCompare(elem, id) {
		var ele = $(this);

		if (elem.checked) {
			var status = 'checked';
		} else {
			var status = 'unchecked';
		}
		//console.log(ele.parents("tr").attr("data-id"));

		$('.AJloader').show();
		var user_login = $('#user_id').val();
		if (user_login > 0) {
			$.ajax({
				type: 'POST'
				, url: baseUrl + '/addto-compare/' + id
				, data: {
					"_token": "{{ csrf_token() }}"
					, user_login: user_login
					, id: id
					, status: status
				}
				, success: function(response) {
					$('.AJloader').hide();
					if (response.msg == 'Product added to compare list') {
						//$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('Product added to compare list.');
						// window.setTimeout(function() {
						// 	window.location.href = "{{ url('product-comparison') }}";
						// }, 3000);
					} else {
						//$(elem).html('<i class="far fa-heart"></i>');
						toastr.error('This item removed from compare list.');
					}
				}
			});
		} else if
		(user_login == 0) {
			$('.AJloader').hide();
			toastr.error('{{__("message.please_login_first_text")}}');
			setTimeout( function(){
				window.location.href = "{{ url('login') }}";
			}, 1000);

		}
	}

    const startRecordingButton = document.getElementById('start-recording');
	const voiceOutput = document.getElementById('search_string');
	// Check browser support for Web Speech API
	const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

	    @php
		if (session()->has('lang_code')) {
			if (session()->get('lang_code') == 'en') {
			@endphp
			recognition.lang = "en-US";
			@php
			}else if (session()->get('lang_code') == 'sv') {
			@endphp
			recognition.lang = "sv-SE";
			@php
			}
    	}else{
		@endphp
			recognition.lang = 'en-US';
		@php
		}
		@endphp

		 // Start recording when button is clicked
		 startRecordingButton.addEventListener('click', () => {
            recognition.start();
			$('#search_string').val('Listening...');
        });

        // When speech is detected
        recognition.onresult = (event) => {
            const query = event.results[0][0].transcript;
            voiceOutput.textContent = `You said: "${query}"`;
			$('#search_string').val(query);
			$('.btn_search_mobile_submit').click();
        };



    function showPosition() {

        if (navigator.geolocation) {

			navigator.geolocation.getCurrentPosition(
                function (position) {
                    var positionInfo1 = position.coords.latitude;
                    var positionInfo2 = position.coords.longitude;
                    document.getElementById("lati").value = positionInfo1;
                    document.getElementById("long").value = positionInfo2;
                    setCookie('latitude', positionInfo1, 7);
                    setCookie('longitude', positionInfo2, 7);

                    var geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(positionInfo1, positionInfo2);

                    geocoder.geocode({ location: latlng }, function (results, status) {
                        var session_city = "{{ session()->get('filter_city') }}";

                        if (!session_city || session_city === 'Not found') {
                            if (status === google.maps.GeocoderStatus.OK && results) {
                                // Get city from results
                                let city;
                                for (let i = 0; i <= 2; i++) {
                                    if (results[i]?.plus_code) {
                                        const cityDetails = results[i].plus_code.compound_code.split(",");
                                        city = cityDetails[0]?.split(" ")[1];
                                        break;
                                    }
                                }
                                if (city) {
                                    $(".select_city_home #select2-city_name-container").text(city);
                                }
                            }
                        } else {
                            $(".select_city_home #select2-city_name-container").text(session_city);
                        }
                    });

                    setTimeout(function () {
                        callAjax(positionInfo1, positionInfo2);
                    }, 2000);

                    function callAjax(lati, long) {
                        var form = $("form#latlongform");
                        $.ajax({
                            url: form.attr("action"),
                            type: "get",
                            data: { lati, long },
                            success: function (response) {
                                //console.log("Ajax response:", response);
                            },
                        });
                    }
                },
                function (error) {
                    console.error("Geolocation error:", error);
                    //alert("Unable to retrieve location.");
                },
                { enableHighAccuracy: true } // Enable high accuracy
            );

			// navigator.geolocation.getCurrentPosition(function(position) {

            //     var positionInfo1 = position.coords.latitude;
            //     var positionInfo2 = position.coords.longitude;

			// 	document.getElementById("lati").value = positionInfo1;
            //     document.getElementById("long").value = positionInfo2;
            //     setCookie('latitude', positionInfo1, 7);
            //     setCookie('longitude', positionInfo2, 7);
			// 	//var positionInfo1 = '57.1062789';
            //     //var positionInfo2 = '12.2514522';

			// 		var geocoder = new google.maps.Geocoder();
			// 		var latlng = new google.maps.LatLng(positionInfo1, positionInfo2);
			// 		geocoder.geocode({'location': latlng}, function(results, status) {

			// 			var session_city = "{{ session()->get('filter_city') }}";
			// 			if((session_city == 'Not found') || (session_city == null) || (session_city == '')){
			// 				if (status == google.maps.GeocoderStatus.OK && results) {

			// 					//const address = 'Kyrkogatan 15,43241 Varberg, Sweden';
			// 					const address = results[0].formatted_address;

			// 					//get city
			// 					if(results[0]['plus_code']){
			// 						var	city1 = results[0]['plus_code'].compound_code;
			// 						var city2 = city1.split(",");
			// 						var city3 = city2[0].split(" ");
			// 						var city4 = city3[1];
			// 						console.log('city1 ',city4);
			// 						console.log('city3 ',city4);
			// 						console.log('city4 ',city3[1]);
			// 						$(".select_city_home #select2-city_name-container").text(city4);
			// 						console.log("first");
			// 					}else if(results[1]['plus_code']){
			// 						var	city1 = results[1]['plus_code'].compound_code;
			// 						var city2 = city1.split(",");
			// 						var city3 = city2[0].split(" ");
			// 						var city4 = city3[1];
			// 						console.log(city3[1]);
			// 						$(".select_city_home #select2-city_name-container").text(city4);
			// 						console.log("second");
			// 					}else{
			// 						var	city1 = results[2]['plus_code'].compound_code;
			// 						var city2 = city1.split(",");
			// 						var city3 = city2[0].split(" ");
			// 						var city4 = city3[1];
			// 						console.log(city3[1]);
			// 						$(".select_city_home #select2-city_name-container").text(city4);
			// 						console.log("third");
			// 					}

			// 				}
			// 			}else{
			// 				console.log('session_city - ',session_city);
			// 				$(".select_city_home #select2-city_name-container").text(session_city);
			// 			}
			//     });

			// 	setTimeout(function(){ callAjax(); }, 2000);
			// 		function callAjax(){
			// 			var form = $("form#latlongform");
			// 			var lati = positionInfo1;
			// 			var long = positionInfo2;
			// 				$.ajax({
			// 					url: form.attr("action"),
			// 					type: "get",
			// 					data: {
			// 						lati: lati, long: long
			// 					},
			// 					success: function(responce) {

			// 					}
			// 			});
			// 		}

            // });

        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }
    window.onload = showPosition;
</script>

<!-- /container -->
<script type="text/javascript">
	$(document).ready(function() {

		var getcities = "{{ route('get.cities') }}";
        var token = "{{ csrf_token() }}";
        $('#city_name').select2({
            placeholder: "Search city",
            ajax: {
                type: "POST",
                url: getcities,
                dataType: 'json',
				delay:250,
                data:
                    function (params) {
                        return { search: params.term, _token:token };
                },
                processResults: function (response) {
                    console.log(response);
                    return {
                        results: response
                    };
                },cache: true
            }
        });

	$("#loader").fadeOut("slow");
		var deviceWidth = $(window).width();
		// windows laptop
		if (deviceWidth >= 1300 && deviceWidth <= 1550) {
			$('#sidebar').removeClass('col-xl-2');
			$('#sidebar').addClass('col-xl-3');
			$('#listings').removeClass('col-xl-5');
			$('#listings').addClass('col-xl-6');
		}
		//Macbook pro Ratina display
		if (deviceWidth >= 2550 && deviceWidth <= 2900) {
			$('#sidebar').removeClass('col-xl-2');
			$('#sidebar').addClass('col-xl-3');
			$('#listings').removeClass('col-xl-5');
			$('#listings').addClass('col-xl-6');
		}
	});

	function addToWishList(elem,id) {
		var user_login   = $('#user_id').val();
		$('.AJloader').show();

		if (user_login >0) {
			$.ajax({
				type : 'POST',
				url: baseUrl+'/add-to-wishlist/'+id,
				data: {"_token": "{{ csrf_token() }}",user_login : user_login, id:id },
				success : function(response) {
					if (response.msg == 'Added to wishlist') {
						$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('{{__("message.added_to_wishlist_text")}}');
					}else if(response.msg == 'This item is already in wishlist'){
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('{{__("message.this_item_is_already_in_wishlist_text")}}');
					}else{
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('{{__("message.removed_from_the_wishlist_text")}}');
					}
					$('.AJloader').hide();
				}
			});
		}else if(user_login ==0){

			toastr.error('{{__("message.please_login_first_text")}}');
			 window.setTimeout(function() {
				 $('.AJloader').hide();
				window.location.href = "{{ url('login') }}";
			}, 3000);

		}

	}

	function showToggle_tag(elem, selector) {
		$('.'+selector).slideToggle();
		if($(elem).html() == '<i id="tag_icon" style="font-size: 20px;" class="fas fa-caret-up" aria-hidden="true"></i>'){
			$('#tags-toggle-btn').html('<i id="tag_icon" style="font-size: 20px;" class="fas fa-caret-down" aria-hidden="true"></i>');
		}else{
			$('#tags-toggle-btn').html('<i id="tag_icon" style="font-size: 20px;" class="fas fa-caret-up" aria-hidden="true"></i>');
		}
	}

	function showToggle(elem, selector) {
		$('.'+selector).slideToggle();
		if($(elem).text() === "Show more"){
			$(elem).text('Show less');
		}else{
			$(elem).text('Show more');
		}
	}
</script>


</main>

@endsection
