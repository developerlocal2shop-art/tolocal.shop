@extends('layouts.client.listingdetails')
@section('title','Shop | Company')
@section('content')
@php
	$data['mainDirectoryDetails'] = $mainDirectoryDetails;
@endphp
<?php
	if(isset($data)){
        $productData = $data;
    }
	$defaultCurrency = Helper::defaultCurrencyData();
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
</a>


<div class="sub_header_in filter_sub_blue_header sticky_header" id="shop_sub_header_bg" style="background: #f8f8f8;">
	<div class="container-fluid" id="shop_sub_header">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-10 shop_sub_header_company">
                <!-- @php
                    $getSlug = Helper::getDirectorySlug(request()->id);
                @endphp -->
                <!-- @php
                    $getSlug = Helper::getDirectorySeoSlug(request()->id);
                @endphp -->
                <a href="{{ route('listing.details', $directoryRelatedData->slug) }}">
					<h1 class="notranslate"> <?php echo $directoryRelatedData->title ?></h1>
                </a>
			</div>

			<div class="col-lg-9 col-md-9 col-12">
				<!-- <a href="#0" class="search_mob btn_search_mobile"></a>  -->
				<form action="{{ route('directory.product',request()->id) }}" method="GET">
					<div class="row no-gutters custom-search-input-2 inner">
						<div class="col-lg-6 col-md-5">
							<div class="form-group">
								<input class="form-control" name="search_string" id="search_string" type="text" value="{{ isset($search_string)?$search_string:''; }}" placeholder="{{__("message.header_search_text")}}(Products, ENA)">
								<i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording"></i>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 mobile_field">
							<select class="wide cat" name="cat">
								<option value="all">{{__("message.header_search_catogory_text")}}</option>
								<?php foreach($allParentcategory as $parentcat){ ?>
								<option value="{{ $parentcat->id }}" {{ $parentcat->id == $cat ? 'selected' : '' }}>{{ $parentcat->name }}</option>
								<?php } ?>
							</select>
						</div>
                        <div class="col-lg-2 col-md-3 mobile_field">
							<select class="wide" name="filters">
                                <option value="">Select Filters</option>
								<option value="shoppingcard" {{ 'shoppingcard'== $filters ? 'selected':''}}>Shopping card</option>
                                <option value="offer" {{ 'offer'== $filters ? 'selected':''}}>Offer</option>
                                <option value="latestproduct" {{ 'latestproduct'== $filters ? 'selected':''}}>Latest Product</option>
                                <option value="mostpopular" {{ 'mostpopular'== $filters ? 'selected':''}}>Most Popular</option>
							</select>
						</div>
						<div class="col-lg-1 col-md-1">
							<input type="submit" class="btn_search_mobile_submit" value="{{__("message.header_search_text_search_btn")}}" >
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
<div class="margin_60_35 bluefilter" style="margin: -10px 0px;">

	<div id="company_imp_tags_div">
			@if(count($imp_ptags) > 0)
				@foreach ($imp_ptags as $key => $tagval)
					<span class="loc_open mr-2 notranslate" style="background-color: {{ request('tags') == $key ? '#da3429' : '#b5b9b7' }}; color: #000 !important; border: 1px solid #b5b9b7; padding: 3px 6px; border-radius: 4px; display: inline-flex; align-items: center;">
						<a href="{{ url('listings-product/'.$directoryRelatedData->slug.'?tags='.$key??'') }}" class="company_imp_tags2"  style="color: #000; text-decoration: none; margin-right: 5px;">
							{{ $tagval }}
						</a>
					</span>
				@endforeach
			@endif
		</div>

    <div class="row container-fluid">

        {{-- <x-ClientfilterPro :productData="$pDirctoryData" /> --}}
        <x-ClientfilterShop :productData="$pDirctoryData" />

        <div class="col-xl-9 col-md-9 order-xl-1 order-2" id="listings">
            <div class="row">

			@if(!empty($categoryUrls) || !empty($subcategoryUrls) || !empty($producttagsUrls))
				<div class="col-md-12" id="listing_hierarchical">
					<p>
						<a style="color: #da3429;">
							<?php echo $directoryRelatedData->title ?>
						</a>
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


            <div class="col-lg-11 col-md-11">
                @if ($productData->count() > 0)
                <div class="d-flex notranslate">
                    <p class="text-right">
                        Showing {{ $productData->firstItem() }} - {{ $productData->lastItem() }} of {{ $productData->total() }} products
                    </p>
                </div>
                @endif
            <!-- <p class="text-right" style="cursor: pointer;font-weight: 600;" id="filter_btn">Show Filters</p> -->
            </div>

			<div class="col-lg-1 col-md-1" style="margin:-20px -5px">
				<span title="Products Comparison" class="products_comparison">{{-- <i class="fa fa-exchange" style="color: #ffc107;"></i>--}}
					<a href="{{ route('companyproductComparison', [$directoryRelatedData->slug, $directoryRelatedData->id]) }}" target="_blank">
					<img src="{{ asset('assets/images/compare.png') }}" class="img-fluid" alt="" style="margin:20px 14px; width:36px;">
					</a>
				</span>
			</div>


                <?php
				if(count($productData)!=0){

					foreach($productData as $productrow){
						$productprice = Helper::getProductprice($productrow->id,$currencycode);
						$validityOfOffer = Helper::getValidityOfOffer($productrow->id);

						$productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
						$productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
						$productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;

                        if(!empty($_GET['search_string'])){
                            Helper::search_visitors('product',$productrow->id);
                        }

						if(!empty($validityOfOffer)){
							if($productRegularPrice>0 && $productSalesPrice>0){
								$priceGoestoCart = $productSalesPrice;
							}else{
								$priceGoestoCart = $productRegularPrice;
							}
						}else{
							$priceGoestoCart = $productRegularPrice;
						}
						if($productRegularPrice > 0){
					?>
                        <div class="col-lg-3 col-md-3 listing-div shoplisting_div product_listing" data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
                            <div class="strip grid featured-tag-border allshops">
                                <?php
                                $getNewShopping = Helper::getNewShopping($productrow->id);
                                $getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);

                                $getNewOffer = Helper::getNewOffer($productrow->id);
                                $getProductOffers = Helper::getProductOffers($productrow->id);
                                ?>
                                @if($getNewOffer == 1 || $getNewShopping == 1)
                                <img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid new_offers" alt="" width="400" height="266">
                                @endif
                                @if($getProductOffers == 1 || $getNewShoppingOffer == 1)
                                <img src="{{ asset('assets/client/images/sho_offer.png') }}" class="img-fluid new-shopping-offer" alt="" width="400" height="266">
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
                                    <input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
                                    <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                                    <?php $imagename = Helper::getProductImage($productrow->id); ?>
                                    <a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')" <?php } ?>>
                                        <div class="read_more">
                                            <button class="btn "><i class="fa fa-eye"></i> View More </button>
										</div>
                                        <div class="pro-icn">
                                            {{-- <a href="wishlist.html" class="w-c-q-icn"><i class="fa fa-heart"></i></a> --}}
                                            <a target="_blank" href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="w-c-q-icn">
                                                <i class="fa fa-shopping-bag"></i>
                                            </a>
                                        </div>
                                    </a>
                                </figure>
                                <div class="wrapper featured-body">
                                    <h3 class="ellipsis"><a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">{{ ($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name }}</a></h3>

                                    <p class="ellipsis">
                                        @if($productrow->short_content)
                                        {{ \Illuminate\Support\Str::limit(strip_tags($productrow->short_content), 13, '...') }}
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
                                        <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productSalesPrice }}</span></div>
                                        <?php } ?>
                                        <?php if($productRegularPrice>0 && $productSalesPrice==0){?>
                                        <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                                        <?php }else if($productRegularPrice>0){ ?>
                                        <div class="pro-price"><span class="old-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                                        <?php } ?>
                                    </li>
                                    <?php }else{ ?>
                                    <li>
                                        <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                                    </li>
                                    <?php } ?>

                                    <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                                        <?php
                                            if(!in_array($productrow->id, $wislistProductId)){ ?>
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                        <?php }else{ ?>
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                        <?php } ?>
                                    </a>
                                    <li style="display:none;">
                                        <div class="score"><span> Awesome <em> 2 Reviews </em> </span><strong>5.0</strong></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } } }else{ ?>
                        <div class="text-center">
                            <h2>{{__("message.no_record_found_text")}}</h2>
                        </div>
                <!-- A Single Listing Ends-->
                <?php } ?>
            </div>
            <!-- custom pagination -->
            {{$productData->links('pagination::bootstrap-4')}}
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

					$productcurrency     = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
					$productSalesPrice   = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
					$productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;
					//dd($validityOfOffer);
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
							<input type="hidden" name="user_id" id="user_id"
								value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
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
									<button class="btn vieweye_alignment"><i class=" notranslate fa fa-eye"></i>
										<span>View More</span>
									</button>
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
									{{ ($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name }}
								</a>
							</h3>

							<p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
						</div>
						<ul class="featured-footer mb-0">
							<?php if(count($validityOfOffer) > 0){  ?>
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

        <!-- custom pagination end-->
    </div>
</div>
</div>
<!-- /container  -->


<script type="text/javascript">

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
            //voiceOutput.textContent = "Listening...";
        });

        // When speech is detected
        recognition.onresult = (event) => {
            const query = event.results[0][0].transcript;
            voiceOutput.textContent = `You said: "${query}"`;
			$('#search_string').val(query);
			$('.btn_search_mobile_submit').click();
			//alert(query);
            //fetchSearchResults(query);
        };


    $(document).ready(function() {

        $('.company_details_logo_img').on('click', function() {
			$('.company_detail_menu').toggle();
		});
		$('.btn_mobile').on('click', function() {
			$('.company_detail_menu').show();
		});

        $("#filter_btn").click(function () {
            $("#shop_sub_header").slideToggle();

            if($("#shop_sub_header").hasClass("sticky_header")) {
                $("#shop_sub_header_bg").css({"background-color":"#f8f8f8"});
                $(this).text("Show Filters");
            } else {
                $("#shop_sub_header_bg").css({"background-color":"#004dda"});
                $(this).text("Hide Filters");
            }

            $("#shop_sub_header").toggleClass("sticky_header");
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

    function addToWishList(elem, id) {
        var user_login = $('#user_id').val();

        if (user_login > 0) {
            $.ajax({
                type: 'POST'
                , url: baseUrl + '/add-to-wishlist/' + id
                , data: {
                    "_token": "{{ csrf_token() }}"
                    , user_login: user_login
                    , id: id
                }
                , success: function(response) {
                    if (response.msg == 'Added to wishlist') {
                        $(elem).html('<i class="fas fa-heart"></i>');
                        toastr.success('{{__("message.added_to_wishlist_text")}}');
                    } else if (response.msg == 'This item is already in wishlist') {
                        $(elem).html('<i class="far fa-heart"></i>');
                        toastr.success('{{__("message.this_item_is_already_in_wishlist_text")}}');
                    } else {
                        $(elem).html('<i class="far fa-heart"></i>');
                        toastr.success('{{__("message.removed_from_the_wishlist_text")}}');
                    }
                }
            });
        } else if (user_login == 0) {
            toastr.error('{{__("message.please_login_first_text")}}');
        }
    }

    function showToggle(elem, selector) {
        $('.' + selector).slideToggle();
        console.log($(elem).text());
        if ($(elem).text() === "Show more") {
            $(elem).text('Show less');
        } else {
            $(elem).text('Show more');
        }
    }
</script>

</main>
@endsection
