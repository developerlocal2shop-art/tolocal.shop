@extends('layouts.client.blog')
@section('title','Comparison | List')
@section('content')
</a>
<style type="text/css">
	.sub_header_in h1 {
		font-size: 40px;
		font-family: serif;
		text-align: center;
	}
	.strip ul {
		background: #fffad5;
	}
	.fa-remove:before, .fa-close:before, .fa-times:before {
		content: "\f00d";
		color: #d73b28;
	}
	.strip ul {
		background: #fffad5; 
	}
	.product-color span.color-label {
		color: #333;
		font-size: 14px;
		font-weight: 700;
		display: inline-block;
		margin-bottom: 10px;
		border-right: 1px solid #203864;
		min-width: 52px!important;
		padding: 0px 1px;
	}
	.product-color span.color {
		margin-left: -15px;
		line-height: 1.6;
		vertical-align: top;
		width: 80%;
		display: inline-block;
		margin-bottom: 10px;
	}
    .add-to-compare {
		text-align: right;
		margin: 2px 6px 0px 0px;
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
	}
	div#listings .strip .wrapper {
		padding: 1px 10px;
	}

	@media (max-width: 768px) {

		.product-color span.color {
			margin-left: -30px;
			line-height: 1.6;
			vertical-align: top;
			width: 85%;
			display: inline-block;
			margin-bottom: 10px;
		}
		.container-fluid h1 {
			font-size: 25px;
			padding: 10px 0px 0px 0px;
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
			padding-top: 10px!important;
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
			top: 5px;
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

	@media (max-width: 414px) {
		.shoplisting_div .strip figure {
			max-height: 240px;
		}

	}


</style>
<?php

	$defaultCurrency = Helper::defaultCurrencyData();
	//dd($productDirctoryData);
	// if(isset($productDirctoryData)){ 
	// 	$productData = $productDirctoryData; 
	// }
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

<div class="sub_header_in filter_sub_blue_header sticky_header">
	<div class="container-fluid" style="padding: 0px 25px;">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<h1 style="margin: 0px 0px;"> 
					{{__("Product Comparison")}}
				</h1>
			</div>
			
		</div>
	</div>
</div>
<!-- /Map -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35 bluefilter">
	<a href="{{ url('listings-product',$slug) }}">
		<h1 style="font-size: 16px; margin:0px 0px 10px 5px; color: #d73b28; text-decoration: underline;"> <i class="fa fa-hand-point-right"></i> 
		{{ $slug }} / {{__("products")}}
		</h1>
	</a>
	<div class="row justify-content-md-center">

		<div class="col-xl-12 col-md-12 order-xl-1 order-2" id="listings">
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

				<?php
				if(count($AddToCompare) > 0){
					foreach($AddToCompare as $productr){

					if(empty($productr->product)){
						continue; // Skip this item, product is missing
					}
					$productrow = $productr->product;

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

					if($dir_id == $productrow->directory_id){
					?>
				<div id="save_{{ $productr->id }}" class="col-lg-6 col-md-6 listing-div shoplisting_div" data-aos-duration="1000" data-aos="fade-up" data-aos-anchor-placement="top-bottom" 
					data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
					<div class="strip grid featured-tag-border allshops">
						<?php
						$getNewShopping = Helper::getNewShopping($productrow->id);
						$getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
						$getNewOffer = Helper::getNewOffer($productrow->id);
						$getProductOffers = Helper::getProductOffers($productrow->id);
						$getProductLangId = Helper::getProductLangId($productrow->id,$language_id);
						$attributesData = Helper::getProductAttributes($productrow->id);
						$reviews = Helper::getReviews($productrow->id);
						?>
						<div class="row">
							<div class="col-md-6">
								@if($getNewOffer == 1 || $getNewShopping == 1)
								<img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid new_offers" alt=""
									width="400" height="266">
								@endif
								@if($getProductOffers == 1 || $getNewShoppingOffer == 1)
								<img src="{{ asset('assets/client/images/sho_offer.png') }}"
									class="img-fluid new-shopping-offer" alt="" width="400" height="266">
								@endif
								<div class="close" style="cursor: pointer;" onclick="deleteComparison(this, {{ $productr->id }})">
									<i class="fa fa-close"></i>
								</div>
								<figure>

									<input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
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
											<a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a>
											<a href="" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
										</div>
									</a>
								</figure>
							</div>
							<div class="col-md-6">
								<div class="wrapper featured-body">
									
									<h3 class="ellipsis">
										<a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">
											{{
											($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name
											}}
										</a>
										<div class="rating">
											@php
											$val1 = count($reviews);
											$val2 = $reviews->sum('star')??'';
	
											if($val2 != 0){
											$res = ( $val2 / $val1 );
											$res = round($res, 1);
											}else{
											$res = '';
											}
											@endphp
											@for($i=1; $i<=$res; $i++) <i class="fa fa-star d-star"></i>@endfor
											@php
												while ($res<5) { echo '<i class="fa fa-star-o"></i>' ; $res++; } 
											@endphp 
										</div>
									</h3>

									<p class="ellipsis2">
										 {{ strip_tags($getProductLangId[0]->short_content) }} <hr>
									</p>

									<div class="product-color">
                                        @if(count($attributesData) > 0)
                                        @foreach ($attributesData as $key => $vdata)
                                        @php
                                            $explode_attdata = explode(',',$vdata->attribute_value);
                                        @endphp
                                            <span class="color-label">{{ $vdata->attribute->name??'' }}:</span>
                                            <span class="color">
                                            @foreach ($explode_attdata as $exval)
                                            <div class="attr-pro-details">
                                                <label class="pro_checkbox">
                                                    
                                                </label>
                                                <span>{{ $exval }}</span>
                                            </div>
                                            @endforeach
                                            </span><br>
                                        @endforeach

                                        @else
                                        <input type="checkbox" style="display:none;" id="variation" checked="checked" >
                                        @endif

                                    </div>

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
										<div class="pro-price"><span class="old-price notranslate">
											{{ $currencySymbol." ".$productRegularPrice }}</span></div>
										<?php } ?>
									</li>
									<?php }else{ ?>
									<li>
										<div class="pro-price"><span class="new-price notranslate">
											{{ $currencySymbol." ".$productRegularPrice }}</span></div>
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
					</div>
				</div>
				<?php 
				}
			    } } }else{ 
				?>
				<div class="container-fluid" >
					<p class="text-center">{{__("message.no_record_found_text")}}</h2>
				</div>
				<?php } ?>
			</div>
			{{-- <span class="notranslate">
				{{$productData->links('pagination::bootstrap-4')}}
			</span> --}}
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
						@if($getProductOffers == 1 || $getNewShoppingOffer == 1)
						<img src="{{ asset('assets/client/images/sho_offer.png') }}"
							class="img-fluid new-shopping-offer" alt="" width="400" height="266">
						@endif
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
										<span>View More</span> </button>
								</div>
								<div class="pro-icn">
									<a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a>
									<a href="" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
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

function deleteComparison(elem, id) {
		$('.AJloader').show();
		if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{ route('remove.item.comparison') }}',
                method: "get",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
					console.log(response);
					toastr.success(response.msg);
					$('.AJloader').hide();
					$("#save_"+id).hide();
					if(response.countsavelatter == 0){
                       window.location.reload();
					}

                }
            });
        }
		$('.AJloader').hide();
	};

	function addToCompare(elem, id) {
		var ele = $(this);
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
				}
				, success: function(response) {
					$('.AJloader').hide();
					if (response.msg == 'Product added to compare list') {
						//$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('Added to compare list');
						window.setTimeout(function() {
							window.location.href = "{{ url('product-comparison') }}";
						}, 3000);
					} else {
						//$(elem).html('<i class="far fa-heart"></i>');
						toastr.error('This item is already in compare list');
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