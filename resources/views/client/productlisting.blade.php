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
@if ($data['mainDirectoryDetails']->company_color)
<style>
.btn-custom { color: #fff; border-radius: 5px; font-weight: bold; background-color: {{ $data['mainDirectoryDetails']->company_color??'rgb(249 95 9)' }}; }
.header { background: {{ $data['mainDirectoryDetails']->company_color??'rgb(249 95 9)' }};}
</style>
@else
<style>
    .btn-custom {  background-color: #007bff; color: #fff; border-radius: 5px; font-weight: bold;}
</style>
@endif

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

<style>
    .assured {
		text-align: right;
		margin: -26px 0px 0px 0px;
	}
    header.header.sticky, header.header_in {
        background-color: {{ $data['mainDirectoryDetails']->company_color }};
    }
	.sub_header_in.sticky_header {
		padding-bottom: 0px;
	}
    .main-menu ul, .main-menu ul li, .main-menu ul ul {
        margin: 9px 0px 0px 0px;
        padding: 0px 0px 3px 0px;
    }

    span.locally_text {
        margin: -85px -105px 0px 0px;
        float: right;
        width: 158px;
        color: #fff;
        position: absolute;
        right: 283px;
        font-size: 13px;
    }

	.company_details_logo_img {
		cursor: pointer;
		width: 50px !important;
		float: left;
		margin: 23px 15px !important;
	}
    .visit_the_shop {
        color: #333 !important;
        font-size: 13px;
        margin: 13px;
    }
    i.fas.fa-chevron-down {
        margin: 0px 6px;
    }
    .add_bottom_15.likebtn_alignment {
        margin: 0px 35px;
    }

    .listing-social-btn .social-button {
        height: 30px;
        line-height: 26px!important;
        width: 25%!important;
        border: 1px solid #ccc !important;
        font-size: 12px;
        font-weight: 600;
    }
    .more_type {
        width: 19%;
        float: right;
        margin: -16px 5px;
        text-align: right;
        cursor: pointer;
        border-color: #3b5998;
        background: #fff;
        border: 1px solid #666;
        border-radius: 50px;
        font-size: 14px;
        padding: 8px 20px;
        transition: .3s;
        display: inline-block;
        line-height: 17px;
        font-weight: 500;
    }
    .row.no-gutters.custom-search-input-2.inner {
        margin: 7px 0px 6px 0px;
    }
    span.company_details_logo {
        margin: 0px 23px;
        position: absolute;
    }
    button#down-to-schedule_c {
        width: 80px;
        margin: -40px 40px -25px 140px;
    }
    button#down-to-schedule {
        width: 130px;
        float: left;
        margin: 1px 5px;
    }
    a.company_imp_tags {
        cursor: pointer;
    }
    a.company_imp_tags2 {
        cursor: pointer;
    }
    i.fa.fa-times.tag_delete {
        color: #993939;
        margin: 0px 5px;
    }
    div#allgalleryimages_model.listing_detail_gallery .thumbnail img {
        max-height: 100%;
        border: 3px solid #1f3863;
        padding: 0px;
    }
    img.logo_normal_center {
        width: 295px;
        margin: -15px 0px 0px 0px;
    }
    div#logo {
        margin: -10px 0px 0px 20px;
    }
    #logo img {
        max-width: 245px;
        min-width: 200px;
    }


    .main-menu ul, .main-menu ul li, .main-menu ul ul {
        margin: 5px 0px 0px 0px;
    }
    div#listing_banner {
    margin-top: 85px;
   }

    .main-menu {
        text-align: center;
        float:none !important;
    }
    header {
		padding: 20px 20px 5px 0px !important;
	}
    .company_d_title {
        margin: -10px 202px -6px 100px;
        font-size: 38px;
        background: #fff;
        width: auto;
        font-family: fantasy;
        border-radius: 25px;
        padding: 1px 15px;
    }

   .company_d_title_mobile{
    display:none;
   }

.secondary_nav ul li:nth-child(2) {
    display: unset;
}

.secondary_nav {
    padding: 8px 0;
}
    .visit_the_shop{
        color: #333 !important;
        font-size: 13px;
    }
    div#lightbox {
        right: 0%;
    }
    .gallerytype_pdf {
        margin: 0px 5px 30px 15px;
        border: 4px solid #1f3863;
        padding: 1px;
    }
button#listsubmit-dirtype {
    margin-top: 24px;
}
/* .schedulenewtable2 {
    height: 210px;
    overflow: auto;
    margin-top: 25px;
    border: 1px solid #ddd;
    scrollbar-width: thin;
    scrollbar-color: #203864 #bbb;
} */

#functions_reload a {
    color: #333 !important;
    margin: -20px -22px;
    padding: 5px;
}
span#functions_reload i {
    margin: 0px 5px !important;
}
    .lb-dataContainer {
        max-width: 26% !important;
        padding: 2px;
        text-align: center;
    }
    #listing_banner .owl-item img {
        min-height: 417px;
    }
    img.direction {
        float: right;
        margin: -40px 10px;
        width: 60px;
        height: auto;
        border-radius: 5px;
    }
    .room_type.first img {
        min-height: 147px;
        max-height: 137px;
        object-fit: cover;
        margin: 15px 20px;
    }
    .room_type.first .col-md-4 {
        text-align: center;
    }
    ul.hotel_facilities {
        margin: 5px 15px 5px -24px; padding: 8px;
    }
    .toggle-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
    }

    .dropdown-icon {
        margin-left: -36px;
        transition: transform 0.3s ease;
        padding: 0px 10px;
    }

    .toggle-btn.collapsed .dropdown-icon i {
        transform: rotate(0deg);
    }

    .toggle-btn:not(.collapsed) .dropdown-icon i {
        transform: rotate(180deg);
    }

    .show-more-height {
        overflow: hidden;
        display: block;
        max-height: 87px; /* Collapsed height */
        transition: max-height 0.3s ease;
    }

    .show-more-height.expanded {
        max-height: 500px; /* Expanded height (adjust based on your content) */
    }
    .description_toggle {
        color: #3e3838;
        border: none;
        padding: 1px 5px;
        cursor: pointer;
        margin: -5px 0px 20px 0px;
        display: block;
        text-align: center;
        outline: none;
    }
    .description_toggle:focus {
        outline:none;
    }

    @media screen and (max-width:767px){

        div#home_qr_code {
            display: none;
        }
        .mobile_field{
            display: none;
        }
        .compare_checkbox {
            margin: 4px 5px !important;
        }
		.company_details_logo_img {
			display: none;
		}
		header {
			padding: 13px !important;
		}
        .headercontact {
            position: fixed;
            right: 0;
            top: 50px;
            width: 100%;
            padding: 19px 0px;
            z-index: 99999999;
            border-bottom: 1px solid transparent;
            float: left;
        }
        img.logo_normal_center {
			width: 201px;
			margin: 0px 0px -30px 90px;
		}
        .listing_see_all {
            display: inline-block !important;
        }
        div#logo {
            margin: 0px 30px;
        }
        #logo img {
            height: 28px;
            margin: 4px 0 0;
            min-width: 200px;
        }
        span#functions_reload {
            margin: -25px 5px auto;
            text-align: center;
            border: none;
            position: absolute;
        }
        .direction_a {
            border: none;
            margin: 10px 10px;
            padding: 0;
        }
        div#listing_banner {
         margin-top: 49px;
        }
        .owl-carousel {
           margin: 25px 0px 0px 0px;
        }


    .main-menu ul li select {
       max-width: 100%;
       width: 70%;
    }
    .mm-listview>li>a, .mm-listview>li>span {
    text-align: left;
    }
        .company_d_title {
           margin: 15px;
           font-size: 20px;
           background: #fff;
           padding: 0px 0px;
        }
        li.company_details_logo img {
            margin: -20px 0px 0px 0px !important;
            position: relative !important;
        }
        .company_d_title_mobile {
    display: block;
    margin: 1px 130px -26px -230px;
    font-size: 17px;
    background: #fff;
    width: auto;
    font-family: fantasy;
    border-radius: 25px;
    padding: 3px 10px;
        }
        .single-listing-map {
            height: 212px;
            margin: 85px 0px 0px 0px;
        }
        .room_type {
            padding: 0 !important;
        }
        .dropdown-icon {
            margin-left: 0px;
            position: absolute;
            right: 7px;
            top: 21px;
        }
        .room_type {  border: none !important; padding: 3px 0px; }
        ul.hotel_facilities {
            margin: 5px 0px 0px 5px;
            padding: 10px;
        }
        #functions_reload a {
            font-size: 13px !important;
        }
        .google_translate_mobile {
            float: right;
            margin: 0px 35px;
        }
        .owl-prev {
            margin: 30px 10px !important;
        }
        .owl-next {
            margin: 30px 10px !important;
        }
        iframe{
            height: 193px !important;
        }
        #logo {
            width: 60%;
        }

        .goog-te-gadget-simple span {
            display: inherit !important;
        }
        .goog-te-gadget-simple {
            width: 96px;
        }
        #listing_banner .owl-item img {
            min-height: auto;
        }

        div#trademark.owl-carousel .owl-item img {
            display: block;
            width: 180px;
            height: 120px !important;
        }
        .mobile_direction_a{
            display: block !important;
        }
    }
    .notactivate:before {
        content: '';
        width:  100%;
        height: 100%;
        position: absolute;
        background-color: rgba(0,0,0,0.6) !important;
        z-index: 999;

    }
    .notactivate {
        position: relative;

    }
    .overlay-content {
        width: 80%;
        height: 80%;
        display: flex;
        justify-content: center;
        align-items: center;
        border: #fff solid 4px;
        color: #fff;
        font-size: 30px;
        z-index: 999;
        position: absolute;
        top: 0;
        left: 0;
        margin: 10%;
    }

    #customer_club h4 {
        width: 100%;
    }
    #customer_club p {
        text-align: center;
    }
    #customer_club p {
        text-align: center;
        width: 100%;
    }

    .customer-club-form-container {
        max-width: 600px;
        margin: 10px auto;
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .btn-custom:hover {
        background-color: #0056b3;
    }
    .formlabel_state label {
        display: block;
    }
    span.cc_respondata {
        font-size: 18px;
    }
    .add_bottom_15 span a:hover {
        color: hsl(224.83deg 46.29% 30.68%) !important;
        font-size: 11px;
    }

    @media only screen and (min-width: 768px) and (max-width: 992px) {
        .locally {
            margin: -62px 85px !important;
        }
        span.locally_text {
            width: 280px !important;
            right: 150px !important;
            font-size: 15px !important;
            top: 5px;
            margin: 0px !important;
        }

        .headercontact {
            position: fixed;
            right: 0;
            top: 50px;
            width: 100%;
            padding: 19px 0px;
            z-index: 99999999;
            border-bottom: 1px solid transparent;
            float: left;
        }
        button#down-to-contact {
            margin: -20px 0px;
            position: relative;
            width: auto;
            z-index: 20000;
            padding: 0px 5px;
        }
        span.company_details_logo {
            margin: -23px 24px;
        }
        .mm-listview>li>a, .mm-listview>li>span {
            text-align: left;
        }
        .main-menu ul, .main-menu ul li, .main-menu ul ul {
            margin: 5px 0px 0px 0px;
            text-align: left;
        }
    }


    @media (min-width: 992px) and (max-width: 1199px) {
        span.company_details_logo {
            margin: -23px 24px;
        }
        span.locally_text {
            width: 160px !important;
            right: 154px !important;
            font-size: 15px !important;
            top: 5px;
            margin: 0px !important;
        }
    }

    @media screen and (min-width: 1500px) and (max-width: 2500px) {
        span.locally_text {
            margin: -69px 5px 0px 0px !important;
            float: right !important;
            width: 156px !important;
            color: #222121 !important;
            position: absolute !important;
            right: 215px !important;
            font-size: 14px !important;
        }
        .company_details_logo_img {
            width: 78px !important;
            margin: 10px 15px !important;
        }
    }

    /* @media (orientation: landscape) { } */

</style>

<style>

	div#company_imp_tags_div {
    margin: -20px 10px 16px 20px;
}
.shop_sub_header_company h1 {
    padding: 17px 10px;
    font-family: serif;
    font-size: 20px;
}
.products_comparison img {
    width: 60px;
}
.add-to-compare {
		margin: 0px 0px 0px -2px;
		text-align: right;
        cursor: pointer;
		width: 212px;
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
.acc_shop_filter #accordion.tree-stuc .ponto h5 span label {
    margin-bottom: 5px;
}
.acc_shop_filter #accordion.tree-stuc .card-header {
    padding: 1px 15px;
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

	.strip figure .read_more span {
		background-color: rgba(255, 255, 255, .08);
	}
	.strip figure .read_more span {
		padding: 0px 0px 0px 0px;
	}
	.compare_checkbox {
    margin: 6px 6px;
    position: absolute;
}
	.new_offers {
		right: 25px;
	}

	.strip .wrapper p {
		margin-bottom: 3px;
	}
	div#listings .strip .wrapper {
		padding: 1px 10px;
	}


	@media (max-width: 768px) {
		.shoplisting_div {
			flex: 0 0 50%;
			max-width: 50%;
			margin: 0px !important;
			padding: 2px 2px 0px 2px;
		}
        .assured {
			display: none;
		}
		.bluefilter {
			margin: 0!important;
			padding-top: 30px!important;
		}
		.custom-search-input-2.inner .nice-select {
			height: 35px;
			line-height: 30px;
		}
		.shop_sub_header_company h1 {
			padding: 0px 10px;
			font-size: 20px;
			text-align: left;
		}
		.sub_header_in.sticky_header {
			margin-top: 40px;
		}
		.shoplisting_div .strip figure {
				height: 200px;
		}
		.row.container-fluid {
			margin: 2px;
		}

		.pagination li {
			padding: 0px;
		}
		.pagination a, .pagination span {
			font-size: 14px;
			height: 30px;
			line-height: 33px;
		}
		#logo img {
			margin: 3px -24px 1px 22px !important;
		}

    }


</style>

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
