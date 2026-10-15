{{-- @extends('layouts.client.common_footer') --}}
@extends('layouts.client.prodetails')

@if (isset($metadata) && !empty($metadata))
    @if (!empty($metadata->meta_title))
    @section('title', $metadata->meta_title)
    @else
    @section('title', $data['productDetails']->name)
    @endif
@else
    @section('title', $data['productDetails']->name)
@endif
@section('content')
</a>
<?php
$currenturl = url()->current();
$user_ip = $_SERVER['REMOTE_ADDR'];
$geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);

if($user_ip=='127.0.0.1' || $user_ip=='localhost' || $user_ip=='::1' ){
    $countryCode = 'IN';
    $state_array = explode(" ",'National Capital Territory of Delhi');
    $city = 'Delhi';
}else{
    $countryCode = $geo["countryCode"];
    $state = $geo["regionName"];
    $state_array = explode(" ",$state);
    $city = $geo["city"];
}

if(in_array("Capital", $state_array)){
    $state = $city;
}else{
    $state = $state;
}

$defaultCurrency = Helper::defaultCurrencyData();
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}

$productStock = isset($data['productDetails']->stock)?$data['productDetails']->stock:"";
$productID = isset($data['productDetails']->id)?$data['productDetails']->id:"";
$directory_id = isset($data['productDetails']->id)?$data['productDetails']->directory_id:"";
$productName = isset($data['productDetails']->name)?$data['productDetails']->name:"";
$productCode = isset($data['productDetails']->sku)?$data['productDetails']->sku:"";
$productTags = isset($data['productDetails']->tags)?$data['productDetails']->tags:"";
$suppliername = isset($data['productDetails']->supplier_name)?$data['productDetails']->supplier_name:"";
$suppliernumber = isset($data['productDetails']->supplier_product_no)?$data['productDetails']->supplier_product_no:"";
$productslug = isset($data['productDetails']->seo_url)?$data['productDetails']->seo_url:"";

$productName = $productContent->name ?? $productName;
$productShortContent = $productContent->short_content ?? '';
$productLongContent  = $productContent->long_content ?? '';

$cart = session()->get('cart', []);
$wislistProductId=array();
	foreach($wishlists as $wishid){
		$wislistProductId[]=$wishid['product_id'];
	}
?>

<!-- Video Modal -->
<div class="modal fade listing_detail_gallery"  id="video_model" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Video</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview" id="listgallerypreview">
                        @if(!empty($productvideo))
                            <video height=""  controls preload="auto" data-toggle="modal" data-target="#video_model" style="opacity: unset !important;">
                                <source src="{{ env('IMAGE_PATH').'files/productvideo/'.$productvideo->video_link??'' }}" type="video/mp4">
                            </video>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade listing_detail_gallery"  id="review_form" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a review</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="review-form collapse show" id="add-review">

                            <form id="reviewfrm" action="{{url('addreview/'.$productID)}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="name" value="{{ Auth()->user()->name??'' }}">
                                <input type="hidden" name="mail" value="{{ Auth()->user()->email??'' }}">
                                <input type="hidden" name="product_id" value="{{ $productID }}">
                                <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                                <input type="hidden" name="review_directory_id" value="{{ $directory_id }}">
                                <label style="margin: 0px 0px 15px 0px; color: #3c3c3c;">Rating</label><br>
                                @csrf
                                <select name="rating" id="combostar" class="form-control required" required="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select><br>
                                <label style="margin: 10px 0px 5px 0px;">Photos</label><br>
                                <input type="file" name="r_images[]" id="r_images" multiple value="{{ $productID }}"><br>
                                <label style="margin: 10px 0px 5px 0px;">Add comments</label><br>
                                <textarea name="comment" style="height: 110px;" placeholder="Write your comments" class="form-control required" required=""></textarea>
                                <br>
                                <button type="submit" style="cursor:pointer" class="submitbtn" process-text="Requested">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade listing_detail_gallery" id="similar_product_model" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Using only modal-xl class -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Similar Products</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview row" id="similar_product_list">

                        @if(count($data['similar_product']) > 0)
                            <hr>
                                @foreach($data['similar_product'] as $key => $simval)

                                    @if($simval->image[0]->path??'')
                                    {{-- <li  @if($simval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif >
                                    <img src="{{ env('IMAGE_PATH').'images/product/'.$simval->image[0]->name_thumbnail.'.webp'??'' }} ">
                                    </li> --}}
                                    <div class="image-thumbnail existing-img col-md-3 similar_product_list" >
                                        <a href="<?php echo url("product/detail", ['id' => @$simval->id,'slug' => @$simval->seo_url]); ?>">
                                            <img data-id="${value.id}" src="{{ env('IMAGE_PATH').'images/product/'.$simval->image[0]->name_thumbnail.'.webp'??'' }}" @if($simval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif class="previewBlocks gtype_img" >
                                        </a>
                                    </div>
                                    @else
                                    <div class="image-thumbnail existing-img col-md-3" >
                                        <a href="<?php echo url("product/detail", ['id' => @$simval->id,'slug' => @$simval->seo_url]); ?>">
                                        <img  src="{{asset('assets/plugins/images/placeholder.png')}}" class="previewBlocks gtype_img" style="width: 95px;height: 85px;margin: 5px 5px;">
                                        </a>
                                    </div>
                                    @endif


                                @endforeach
                            @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Similar Product Modal -->
<div class="modal fade listing_detail_gallery"  id="similar_product_model2" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Similar Products</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview" id="listgallerypreview">

                        @if(count($data['similar_product']) > 0)
                            <hr>
                                <h5> Similar Product</h5>
                                @foreach($data['similar_product'] as $key => $simval)

                                <a href="<?php echo url("product/detail", ['id' => @$simval->id,'slug' => @$simval->seo_url]); ?>">
                                    @if($simval->image[0]->path??'')
                                    <li  @if($simval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif >
                                    <img src="{{ env('IMAGE_PATH').'images/product/'.$simval->image[0]->name_thumbnail.'.webp'??'' }} ">
                                    </li>
                                    @else
                                    <li @if($simval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif>
                                    <img  src="{{asset('assets/plugins/images/placeholder.png')}}"></li>
                                    @endif
                                </a>

                                @endforeach
                            @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="sub_header_in sticky_header">
    <div class="container">
        {{-- <h1>{{__('message.shop_details_title')}}</h1> --}}
    </div>
</div>
<!-- /Map -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35">
    <div class="row justify-content-md-center">
        <div class="col-xl-8 col-md-12 order-xl-1 order-2 col-xl-12" id="listings">
            <!-- product detail -->
            <section class="section-b-padding pro-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 pro-image">
                            <div class="row">
                                <div class="col-lg-6 col-xl-5 col-md-5 col-12 col-xs-12 larg-image">
                                    <?php $imagename = Helper::getProductImage($data['productDetails']->id); ?>
                                    <div class="exzoom" id="exzoom">
                                        <!-- Images -->
                                        <div class="exzoom_img_box">
                                            <ul class='exzoom_img_ul'>

                                                <div class="tab-content">
                                                    @if($imagename)
                                                    <?php $imageforfacebook = 'images/product/'.$imagename[0]->name_thumbnail.'.webp'; ?>
                                                    <li><img src="{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}"></li>
                                                    @else
                                                    <?php  $imageforfacebook = 'assets/plugins/images/placeholder.png'; ?>
                                                    <li><img src="{{asset('assets/plugins/images/placeholder.png')}}"></li>
                                                    @endif
                                                </div>
                                                <?php
                                                $imagetestarray = App\Http\Controllers\Client\ShopController::get_galleryImage($productID);
                                                if (!empty($imagetestarray)) {
                                                ?>
                                                    @foreach($imagetestarray as $val)
                                                    <li><img src="{{ env('IMAGE_PATH').'images/product/'.$val->name_thumbnail.'.webp' }}" /></li>
                                                    @endforeach
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="exzoom_nav"></div>
                                        <p class="exzoom_btn">
                                            <a href="javascript:void(0);" class="exzoom_prev_btn notranslate"> < </a>
                                            <a href="javascript:void(0);" class="exzoom_next_btn notranslate"> > </a>
                                        </p>
                                    </div>

                                    <div class="similar_product_cont">
                                        <ul class='similar_product'>
                                            @if(count($data['similar_product']) > 0)
                                            <hr>
                                                <h5> Similar Products</h5>
                                                @foreach($data['similar_product'] as $key => $similarval)
                                                    @if($key < 11)
                                                        <a href="<?php echo url("product/detail", ['id' => @$similarval->id,'slug' => @$similarval->seo_url]); ?>">
                                                            @if($similarval->image[0]->path??'')
                                                            <li  @if($similarval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif >
                                                            <img src="{{ env('IMAGE_PATH').'images/product/'.$similarval->image[0]->name_thumbnail.'.webp'??'' }} ">
                                                            </li>
                                                            @else
                                                            <li @if($similarval->id == $data['productDetails']->id) style="border: 2px solid #5fa800;" @endif>
                                                            <img  src="{{asset('assets/plugins/images/placeholder.png')}}"></li>
                                                            @endif
                                                        </a>
                                                    @endif
                                                @endforeach

                                                @if(count($data['similar_product']) > 10)
                                                    <a data-toggle="modal" data-target="#similar_product_model" style="border: 1px solid #eee; padding: 20px 28px; margin: 0px 1px;">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </a>
                                                @endif

                                            @endif

                                            @if(!empty($productvideo->video_link))
                                            <a data-toggle="modal" data-target="#video_model">
                                            <li>
                                            <img data-toggle="modal" data-target="#video_model" width="80px" src="{{asset('assets/images/VPlaceholder.jpg')}}">
                                             </li>
                                             </a>
                                            @endif

                                        </ul>
                                    </div>

                                </div>


                                <div class="col-lg-6 col-xl-7 col-md-7 col-12 col-xs-12 pro-info">
                                    <h4 class="notranslate">{{ $productName?? '' }} </h4>
                                    @if($isApprovedAffiliate ?? false)
                                        <div class="affiliate-product-link mt-3" id="affiliate-product-link">
                                            <button type="button" class="btn btn-style1" id="generate-affiliate-link"
                                                data-url="{{ route('affiliate.links.store') }}"
                                                data-product-id="{{ $productID }}"
                                                data-token="{{ csrf_token() }}">
                                                Generate &amp; Copy Affiliate Link
                                            </button>
                                            <div class="input-group mt-2" id="affiliate-link-result" hidden>
                                                <input type="text" class="form-control" id="affiliate-link-url" readonly aria-label="Affiliate link">
                                                <button type="button" class="btn btn-outline-secondary" id="copy-affiliate-link">Copy</button>
                                            </div>
                                            <span class="d-block mt-1" id="affiliate-link-status" role="status" aria-live="polite"></span>
                                        </div>
                                        <script>
                                            (function () {
                                                var generateButton = document.getElementById('generate-affiliate-link');
                                                if (!generateButton) return;

                                                var linkInput = document.getElementById('affiliate-link-url');
                                                var result = document.getElementById('affiliate-link-result');
                                                var status = document.getElementById('affiliate-link-status');
                                                var copyButton = document.getElementById('copy-affiliate-link');

                                                function copyLink() {
                                                    linkInput.focus();
                                                    linkInput.select();
                                                    linkInput.setSelectionRange(0, linkInput.value.length);
                                                    if (navigator.clipboard && window.isSecureContext) {
                                                        return navigator.clipboard.writeText(linkInput.value).catch(function () {
                                                            document.execCommand('copy');
                                                        });
                                                    }
                                                    document.execCommand('copy');
                                                    return Promise.resolve();
                                                }

                                                generateButton.addEventListener('click', function () {
                                                    generateButton.disabled = true;
                                                    status.textContent = 'Generating affiliate link...';

                                                    fetch(generateButton.dataset.url, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Accept': 'application/json',
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': generateButton.dataset.token
                                                        },
                                                        body: JSON.stringify({ product_id: generateButton.dataset.productId })
                                                    })
                                                        .then(function (response) {
                                                            return response.json().then(function (data) {
                                                                if (!response.ok) throw new Error(data.message || 'Unable to generate affiliate link.');
                                                                return data;
                                                            });
                                                        })
                                                        .then(function (data) {
                                                            linkInput.value = data.url;
                                                            result.hidden = false;
                                                            return copyLink();
                                                        })
                                                        .then(function () {
                                                            status.textContent = 'Affiliate link copied.';
                                                        })
                                                        .catch(function (error) {
                                                            status.textContent = error.message || 'Unable to copy affiliate link.';
                                                        })
                                                        .finally(function () {
                                                            generateButton.disabled = false;
                                                        });
                                                });

                                                copyButton.addEventListener('click', function () {
                                                    copyLink().then(function () {
                                                        status.textContent = 'Affiliate link copied.';
                                                    }).catch(function () {
                                                        status.textContent = 'Unable to copy affiliate link.';
                                                    });
                                                });
                                            })();
                                        </script>
                                    @endif
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

                                        @if($val2 != 0)
                                                <ul style="color: #5fa800;">
                                                    {{-- <li> {{ $res }} </li> --}}
                                                    <li> <small><u style="color: #e30514;">{{ $val1 }} Reviews</u> </small></li>
                                                </ul>
                                        @endif

                                        <div class="pro-availabale">
                                            <span class="available">{{__('message.availability_text')}}</span>
                                            <?php
                                                $inStoreOnly = $data['productDetails']->in_store_only ?? '';
                                                $stockval = $data['productDetails']->stock ?? 0;
                                                $outOfStockFlag = $data['productDetails']->outofstock ?? '';

                                                if ($inStoreOnly == 'in_store_only'){
                                                        $stkval = '<span style="color:#e30514;">In Store Only</span>';
                                                }else{
                                                    if ($stockval > 0 && $outOfStockFlag !== 'outofstock') {
                                                        $stkval = 'In stock';
                                                    } else {
                                                        $stkval = '<span style="color:#e30514;">Out of Stock</span>';
                                                    }
                                                }

                                            ?>
                                            <span class="pro-instock">{!! $stkval !!}</span>
                                        </div>
                                            <?php
                                                $productprice = Helper::getProductprice($data['productDetails']->id,$currencycode);
                                                $validityOfOffer = Helper::getValidityOfOffer($data['productDetails']->id);
                                                $validityOfOfferShoppingCard = Helper::getValidityOfShoppingCardOffer($data['productDetails']->id);

                                                $productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
                                                $productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0;
                                                $productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0;

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
                                                    $priceGoestoCart = $productSalesPrice;
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
                                                    $productSalesPrice = $productRegularPrice - $validityOfOfferShoppingCard[0]->discount_firstvalue;
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

                                    <?php if(count($validityOfOffer) > 0 || count($validityOfOfferShoppingCard) > 0){ ?>

                                    <div class="pro-price">
                                        <span class="new-price notranslate"><?php echo $currencySymbol; ?></span>&nbsp;
                                        <span class="new-price">{{ $priceGoestoCart }}</span>

                                        <span class="old-price"><del>{{ $productRegularPrice }}</del></span>

                                            <div class="Pro-lable discount-shodetails">
                                                <div class="offer-shopdetails">
                                                <?php if(count($validityOfOffer) > 0 ){ ?>

                                                    @if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount')
                                                     Offer
                                                    <span class="p-discount "> -{{ $validityOfOffer[0]->discount_firstvalue }}% </span>
                                                    @elseif ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount')
                                                    Offer
                                                    <span class="p-discount notranslate">
                                                    	-{{ $currencySymbol.' '.$validityOfOffer[0]->discount_firstvalue }}
                                                    </span>
                                                    @endif

                                                <?php } ?>
                                                </div>
                                                <div class="shoppingcard-shopdetails">
                                                <?php if(count($validityOfOfferShoppingCard) > 0){ ?>

                                                    @if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount')
                                                    Shopping card
                                                    <span class="p-discount"> -{{ $validityOfOfferShoppingCard[0]->discount_firstvalue }}% </span>
                                                    @elseif ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount')
                                                    Shopping card
                                                    <span class="p-discount notranslate">
                                                    	-{{ $currencySymbol.' '.$validityOfOfferShoppingCard[0]->discount_firstvalue }}
                                                    </span>
                                                    @endif

                                                <?php } ?>
                                                </div>
                                            </div>
                                    </div>
                                    <?php }else{ ?>
                                        <div class="pro-price">
                                            <span class="new-price notranslate"><?php echo $currencySymbol." "; ?></span>&nbsp;
                                            <span class="new-price">{{ $productRegularPrice }}</span>
                                        </div>
                                    <?php } ?>

                                    @if($stockval > 0 && !$data['productDetails']->outofstock == 'outofstock')
                                    <span class="pro-details" id="stock_details_section">

                                        @if ($inStoreOnly != 'in_store_only')
                                            <del>Hurry up!</del> only
                                            <span class="pro-number variation_stock">
                                                {{ isset($data['productDetails']->stock)?$data['productDetails']->stock:1}}
                                            </span>
                                            <b>products left</b> in stock!
                                        @endif
                                    </span>

                                    @else
                                        <a data-toggle="modal" id="contact_vendor_with_c" data-target="#myModal" style="text-decoration:underline; font-weight:500; cursor:pointer;">
                                            Contact Vendor for delivery information.
                                        </a>
                                    @endif
                                        <a data-toggle="modal" id="contact_vendor_without_c" data-target="#myModal" style="display:none; text-decoration:underline; font-weight:500; cursor:pointer;">
                                            Contact Vendor for delivery information.
                                        </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" role="dialog">
                                        <div class="modal-dialog vendor-request-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="vendor-request-heading">
                                                    <span class="vendor-request-icon" aria-hidden="true"><i class="fas fa-comments"></i></span>
                                                    <div>
                                                        <h5 class="vendor-request-title">Contact Vendor</h5>
                                                        <p class="vendor-request-subtitle">Contact Vendor for delivery information.</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <form id="bookingform" class="contact-us-form " action="{{ route('user.request.vendor') }}" method="post"  >
                                                    {{ csrf_field() }}



                                                    <div class="form-row">
                                                    <div class="form-group col-md-6 vendor-request-field" id="input-dates">
                                                        <input type="hidden" name="directory_id" value="{{ $data['productDetails']->directory_id }}">
                                                        <input type="hidden" name="user_id" value="{{ Auth()->user()->id??0 }}">
                                                        <input type="hidden" name="type" value="delivery_request">
                                                        <input type="hidden" name="hidden_product" value="@if($productContent) {{ $productContent->name??$productName }} @endif">

                                                        <label for="vendor-request-name">Your name <span class="vendor-request-required">*</span></label>
                                                        <input id="vendor-request-name" class="form-control required" type="text" name="name" required autocomplete="name" placeholder="{{__("message.placeholder_name_text")}}">
                                                    </div>
                                                    <div class="form-group col-md-6 vendor-request-field">
                                                        <label for="vendor-request-email">Email address <span class="vendor-request-required">*</span></label>
                                                        <input id="vendor-request-email" class="form-control required" type="email" name="email" required autocomplete="email" placeholder="Enter e-mail address!">
                                                    </div>
                                                    </div>
                                                    <div class="form-group vendor-request-field">
                                                        <label for="vendor-request-phone">Phone number <span class="vendor-request-required">*</span></label>
                                                        <input id="vendor-request-phone" class="form-control required" type="tel" name="phone" required autocomplete="tel-national" aria-describedby="vendor-request-phone-note" placeholder="Enter valid phone number">
                                                        <small id="vendor-request-phone-note" class="vendor-request-help">Enter your number without the country code.</small>
                                                    </div>

                                                    <div class="form-group vendor-request-field">
                                                        <label for="vendor-request-message">{{__("message.message_label_text")}} <span class="vendor-request-required">*</span></label>
                                                        <textarea id="vendor-request-message" rows="4" class="form-control required" required name="message" placeholder="How can the vendor help you?"></textarea>
                                                    </div>

                                                    <div class="respondata vendor-request-response" role="status" aria-live="polite"></div>
                                                    <button type="submit" class="btn_1 full-width purchase request_vendor vendor-request-submit">
                                                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                                        {{__("message.submit_button")}}
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                        </div>
                                    </div>


                                    <p style="color:#555;">ENA Number - <b class="ena_number" style="color:#5fa800"> {{ $data['productDetails']->ena??'' }} </b></p>

                                    <p style="color:#555;" class="notranslate">{!! $productShortContent??'' !!}</p>

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
                                                    <input type="checkbox" class="variationattr" id="variation{{ $key }}" name="{{ $vdata->attribute->name??'' }}" data-attrname="{{ $vdata->attribute->name??'' }}" data-attrid="{{ $vdata->attribute->id??'' }}" value="{{ $exval }}">
                                                    <span class="checkmark"></span>
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


                                    @if(auth()->guard('web')->check() != 0)
                                        <?php if($userAddressData){ ?>
                                        <hr><i class="fas fa-map-pin" aria-hidden="true"></i> &nbsp; Address: &nbsp;
                                        <?php } ?>
                                        <i style="font-weight: 400; color: green;" id="current_user_address2">
                                            <?php
                                             if($userAddressData){
						$ucountry = $userAddressData->country;
                                                $ustate = $userAddressData->state;
						$uCity = $userAddressData->city_id;
						$uCityname = $userAddressData->city;
						$uzipcode = $userAddressData->zipcode;

                                             }else{
                                                $ucountry = 0;
                                                $ustate = 0;
                                                $uCity = 0;
						$uCityname = '';
                                                $uzipcode = '';
                                             }
                                                $shippingcountryName = Helper::getCountryName($ucountry);
                                                $shippingstateName = Helper::getStateName($ustate);
                                                $shippingCityName = Helper::getCityName($uCity);
                                                if($shippingCityName){
                                                    $userCityName = $shippingCityName->name;
                                                }else{
                                                    $userCityName = '';
                                                }
                                            ?>
                                            <?php if($uCityname !="") echo $userCityName??''.","; ?> <?php if($ustate!="") echo $shippingstateName->name??'';?>
                                            <?php if($ucountry !="") echo $shippingcountryName->name??''; ?> - <?php echo $uzipcode;?>
                                        </i>
                                    @else
                                                {{ $userCityName = ''; }}
                                    @endif


                                    <div class="pro-qty">
                                        <span class="qty">{{__('message.quantity')}}:</span>
                                        <input type="hidden" id="stock" value="{{ isset($data['productDetails']->stock)?$data['productDetails']->stock:"" }}" name="stock">
                                        <input type="hidden" id="v_stock" value="{{ isset($data['productDetails']->stock)?$data['productDetails']->stock:"" }}" name="v_stock">
                                        <input type="hidden" id="attribute_value" value="" name="attribute_value">

                                        <div class="plus-minus">
                                            <span>
                                                <a href="javascript:void(0)" class="minus-btn text-black notranslate">-</a>
                                                <input type="text" id="quantity" name="quantity" onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]{5}" value="1" min="1" max="10">
                                                <a href="javascript:void(0)" class="plus-btn text-black notranslate">+</a>
                                            </span>
                                        </div>
                                        <span id=Msg></span>
                                        &nbsp;&nbsp;&nbsp;
                                        <span id="delivery_date_section" style="display: none;">
                                            <i class="fa fa-truck" aria-hidden="true"></i> &nbsp;Expected delivery by
                                        <i style="font-weight: 400; color: green;" id="delivery_date"> </i>
                                       </span>

                                        <input type="hidden" id="hidden_current_city">


                                    </div>

                                    <input type="hidden" id="d_ena_number" value="{{ $data['productDetails']->ena??'' }}" name="d_ena_number">
                                    <input type="hidden" id="ena_number" value="{{ $data['productDetails']->ena??'' }}" name="ena_number">
                                    <input type="hidden" id="supplier_product_no" value="{{ $suppliernumber}}" name="supplier_product_no">

                                    <input type="hidden" id="free_product_data" value="{{ $FreeProductsID }}" name="free_product_data">
                                    <input type="hidden" id="directory_id" value="{{ $directory_id }}" name="id">
                                    <input type="hidden" id="id" value="{{ $productID }}" name="id">
                                    <input type="hidden" id="name" value="{{ ($productContent->name!='')?$productContent->name:$productName }}" name="name">
                                    <input type="hidden" id="price" value="{{ $priceGoestoCart }}" name="price">
                                    <input type="hidden" id="regularprice" value="{{ $productRegularPrice }}" name="regularprice">
                                    <input type="hidden" id="image" value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}" name="image">
                                    <input type="hidden" id="productstock" value="{{ isset($data['productDetails']->stock)?$data['productDetails']->stock:"" }}" name="stock">
                                    <input type="hidden" id="product_slug" value="{{ $productslug }}" name="product_slug">

                                    <?php if(count($validityOfOffer) > 0 ){ ?>
                                        @if ($validityOfOffer[0]->discount_selectedvalue == 'percentagediscount')
                                        <input type="hidden" id="offer" name="offer" value="{{ $validityOfOffer[0]->discount_firstvalue }}% ">
                                        <input type="hidden" id="offer_dicount_type" name="offer_dicount_type" value="percentage">

                                        @elseif ($validityOfOffer[0]->discount_selectedvalue == 'pricediscount')

                                        <input type="hidden" id="offer" name="offer" value="{{ $validityOfOffer[0]->discount_firstvalue }} ">
                                        <input type="hidden" id="offer_dicount_type" name="offer_dicount_type" value="fixed">

                                        @endif
                                    <?php } ?>
                                    <?php if(count($validityOfOfferShoppingCard) > 0 ){ ?>
                                        @if ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'percentagediscount')
                                        <input type="hidden" id="shoppingcard" name="shoppingcard" value="{{ $validityOfOfferShoppingCard[0]->discount_firstvalue }}">
                                        <input type="hidden" id="shopping_dicount_type" name="shopping_dicount_type" value="percentage">

                                        @elseif ($validityOfOfferShoppingCard[0]->discount_selectedvalue == 'pricediscount')

                                        <input type="hidden" id="shoppingcard" name="shoppingcard" value="{{ $validityOfOfferShoppingCard[0]->discount_firstvalue }} ">
                                        <input type="hidden" id="shopping_dicount_type" name="shopping_dicount_type" value="fixed">

                                        @endif
                                    <?php } ?>

                                    <p id="sucess"></p>
                                    <div class="pro-btn">
                                        <input class="product-wishlist" type="hidden" value="{{ $productID }}" name="product_id" id="product_id">
                                        <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                                        <a href="javascript:void(0);" title="Add to Wishlist" class="btn btn-style1 notranslate" productid="{{ $productID }}" onclick="addToWishList(this, <?php echo $productID ?>)">
                                            <?php if(!in_array($productID, $wislistProductId)){ ?>
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            <?php }else{ ?>
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                            <?php } ?>
                                        </a>

                                        @if($stockval == 0 || $data['productDetails']->outofstock == 'outofstock' || $data['productDetails']->in_store_only == 'in_store_only')
                                            <a href="javascript:void(0)" id="a_outofstock" class="btn btn-style1 outofstock">
                                                <i class="fa fa-shopping-bag"></i>
                                                {{__('message.add_to_cart_btn')}}
                                            </a>
                                            <a href="javascript:void(0)" id="b_outofstock" class="btn btn-style1 outofstock">
                                                {{__("message.buy_now_btn")}}
                                            </a>
                                        @else
                                            <span class="a_addtocart_ajax">
                                                <span id="addtocartid">
                                                    <a href="javascript:void(0)" id="a_addtocart" class="btn btn-style1 addtocart">
                                                        <i class="fa fa-shopping-bag"></i>
                                                        {{__('message.add_to_cart_btn')}}
                                                    </a>
                                                </span>
                                                <a href="javascript:void(0)" id="b_addtocart" class="btn btn-style1 addtobuy">
                                                    {{__("message.buy_now_btn")}}
                                                </a>
                                            </span>
                                            <span class="a_outofstock_ajax" style="display: none;">
                                                <a href="javascript:void(0)" id="a_outofstock" class="btn btn-style1 outofstock">
                                                    <i class="fa fa-shopping-bag"></i>
                                                    {{__('message.add_to_cart_btn')}}
                                                </a>
                                                <a href="javascript:void(0)" id="b_outofstock" class="btn btn-style1 outofstock">
                                                    {{__("message.buy_now_btn")}}
                                                </a>
                                            </span>
                                        @endif
                                    </div>

                                    <?php
                                    $siteurl=url('share/product/'.$productID);
                                    $urlonfacebok = env('APP_URL');
                                    $imgurl = env('APP_URL')."/".$imageforfacebook;
                                    $discritionProduct = ($productContent->name!='')?$productContent->name:$productName;
                                    $fullurlforfacebook = $urlonfacebok."&picture=".$imgurl."&description=".$discritionProduct;
                                    ?>
                                                <input type="hidden" name="type_item" id="type_items" value="product">
                                                <input type="hidden" name="country_code" id="country_code" value="{{$countryCode}}">
                                                <input type="hidden" name="state" id="state" value="{{$state}}">
                                                <input type="hidden" name="city" id="city" value="{{$city}}">
                                                <a href="javascript:void(0);" id="productlikedtotal" title="Like Product" productid="{{ $productID }}" onclick="addToLikeCounter(this, <?php echo $productID ?>)">
                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                    (
                                                    <span>
                                                        @if(count(Helper::CountLikeData('product',$productID)) != 0)
                                                            {{ count(Helper::CountLikeData('product',$productID)) }}
                                                        @else
                                                            {{ 0 }}
                                                        @endif
                                                    </span>
                                                    ) Like
                                                </a>
                                                 |
                                                @if(!empty($data['productDetails']->directorySlug->slug))
                                                <a class="view_company" target="_blank" href="{{ route('listing.details',$data['productDetails']->directorySlug->slug) }}">View Company</a>
                                                |
                                                @endif

                                                <a class="fb-share" href="javascript:void(0);" onclick="fb_share('{{ $siteurl }}', '{{ $discritionProduct }}')"> <i class="fa fa-facebook" aria-hidden="true"></i> Share </a>
                                                |
                                                <a class="fb-share twit" href="http://twitter.com/share?text={{ $productName }}&url={{ $currenturl }}" target="_blank"> <i class="fab fa-twitter" aria-hidden="true"></i> Tweet </a>

                                                @if(!empty($data['productDetails']->video_link))
                                                    |
                                                    <a class="fb-share twit" target="_blank" href="{{ $data['productDetails']->video_link??'' }}">
                                                    <i class="fab fa-youtube"></i> Video for this product
                                                    </a>
                                                @endif
                                                @if(!empty($suppliername) && !empty($suppliernumber))
                                                <div class="pro-cod" style="border-top:none;">
                                                    <span class="p-code">
                                                        <span class="code-title">Supplier: </span>
                                                        <span>{{ $suppliername }}</span>  &nbsp; || &nbsp;
                                                        <span class="code-title">Product No.: </span>
                                                        <span>{{ $suppliernumber }}</span>
                                                    </span>
                                                </div>
                                                @endif


                                                <?php $directorydata = Helper::getDirectory($data['productDetails']->directory_id); ?>
                                                @if(!empty($data['productDetails']->user))
                                                    @if($data['productDetails']->user->role_id == 1)
                                                        <span class="pro-details">
                                                            <span class="pro-number">{{__("message.vendor_details_text")}}</span>
                                                            <!-- <p>{{__("message.vendor_name_text")}} - {{ $data['productDetails']->user->name }}</p> -->
                                                            @if(!empty($directorydata[0]->title))
                                                            <p style="color:#555; margin: 10px 0px;">
                                                                {{__("message.vendor_name_text")}} - {{ $directorydata[0]->title }}
                                                                | <a target="_blank" href="<?php echo url('listings-product', ['id' => $data['productDetails']->directorySlug->slug??'']); ?>" style="color: #555; font-weight: 600;">
                                                                    Visit the Shop
                                                                </a>
                                                            </p>
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
            </section>



            <section class="section-b-padding pro-page-content">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="pro-page-tab">
<hr>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item rdescription">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-1">{{__("message.description_title")}}</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-2">Reviews</a></li>
                                </ul>
                                <div class="tab-content">

                                    <div class="tab-pane fade show active" id="tab-1">
                                        @if(!empty($productLongContent))
                                            {!! $productLongContent !!}
                                        @endif
                                    </div>

                                    <div class="tab-pane fade show" id="tab-2">
                                        <h4 class="reviews-title">Customer reviews</h4>
                                        <div class="t-desk-2">
                                            <p class="review-desck">Based on <b>{{ count($reviews) }}</b> reviews</p>
                                            Ratings & Reviews <i class="fas fa-comments"></i> <a style="cursor: pointer; color: #004dda; font-size: 16px;" controls preload="auto" data-toggle="modal" data-target="#review_form">Write a review</a>
                                        </div>

                                        @foreach ($reviews as $rval)
                                        <div class="customer-reviews">
                                            <span class="rating">
                                                @for($i=1; $i<=$rval->star; $i++)
                                                    <i class="fa fa-star d-star"></i>
                                                    @endfor
                                                    @php
                                                        while ($rval->star<5) {
                                                        echo '<i class="fa fa-star-o"></i>' ;
                                                        $rval->star++;
                                                        }
                                                    @endphp
                                                    <br>
                                            </span>
                                            <span class="reviews-editor">{{ $rval->user->name??'' }} {{ $rval->user->last_name??'' }} <span class="review-name">on</span> {{ $rval->created_at->format('F d, Y') }} </span>
                                            <p class="r-description">{!! $rval->content??'' !!}</p>
                                                @if(count($rval->photos) > 0)
                                                    @foreach ($rval->photos as $photosval)
                                                        <a href="{{ env('IMAGE_PATH') }}files/reviews_photo/{{ $photosval->photos }}" data-lightbox="allGalleryImages">
                                                            <img data-id="{{ $photosval->id }}" src="{{ env('IMAGE_PATH') }}files/reviews_photo/{{ $photosval->photos }}" class="previewBlocks gtype_img" style="width: 90px; height: 102px; margin: 5px 5px;">
                                                        </a>
                                                    @endforeach
                                                @endif

                                        </div>
                                        @endforeach
                                    </div>
                                    {{-- <div class="tab-pane fade show" id="tab-3">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe height="630" src="./Vegist - Multipurpose eCommerce HTML Template_files/0etCKCAsImI.html" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-b-padding pro-page-content tags-heading-product">
                <?php if(count($data['tags']) > 0){ ?>
                <div class="container">
                    <h5>Product Tags</h5>
                    <div class="tags-product-content">
                        <div class="add_bottom_15">
                            {{-- @foreach($data['productDetails'] as $productTagsrow) --}}
                            {{-- <?php $tagData = explode(',',$productTagsrow->tags);?> --}}
                            <?php
                                $tagsdata="";
                                foreach( $data['tags'] as $tagsrow){
                                $tagsdata = Helper::getProductTags($tagsrow->id);
                            ?>
                            {{-- @foreach( $data['tags'] as $tagsrow) --}}
                            <span class="loc_open mr-2" style="background-color: #b5b9b7;color: #000!important;border: 1px solid #b5b9b7;">
                                {{-- <a href="{{ route('product.details.tag', $tagsdata[0]->id) }}" style="color: #000;">{{ $tagsdata[0]->tags }}</a> --}}
                                <a href="{{ url('shop?tags='.$tagsdata[0]->id) }}" style="color: #000;" target="_blank">
                                    {{ $tagsdata[0]->tags }}
                                </a>
                            </span>
                            <?php } ?>
                            {{-- @endforeach --}}
                            {{-- @endforeach --}}
                        </div>
                    </div>
                </div>
                <?php } ?>
            </section>

            <section class="section-b-padding pro-page-content tags-heading-product">
                <?php if(!empty($data['relatedProductData'] )){ ?>
                    <div class="container-fluid">
                        <div class="main_title_2">
                            <span><em></em></span>
                            <h2>{{__("message.related_product")}}</h2>
                        </div>
                        <div id="product" class="owl-carousel owl-theme listing_related_product">
                            <?php
                            foreach($data['relatedProductData']  as $productrow){
                            $productprice = Helper::getProductprice($productrow->id,$currencycode);
                            $validityOfOffer = Helper::getValidityOfOffer($productrow->id);
                            $productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
                            $productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0;
                            $productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0;

                            if(!empty($validityOfOffer)){
                                if($productRegularPrice>0 && $productSalesPrice>0){
                                $priceGoestoCart = $productSalesPrice;
                                }else{
                                $priceGoestoCart = $productRegularPrice;
                                }

                            }else{
                                $priceGoestoCart = $productRegularPrice;
                            }
                            ?>
                            <div class="item product_listing">
                                <div class="strip grid">
                                    <?php
                                    $getNewShopping = Helper::getNewShopping($productrow->id);
                                    $getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
                                    $getNewOffer = Helper::getNewOffer($productrow->id);
                                    $getProductOffers = Helper::getProductOffers($productrow->id);
                                    ?>
                                    @if($getNewOffer == 1 || $getNewShopping == 1)
                                        <img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid offericon newicon" alt="" width="400" height="266">
                                    @endif
                                    @if($getProductOffers == 1 || $getNewShoppingOffer == 1)
                                    <span style="
                                        background: rgb(231, 248, 236); color:rgb(88 168 65);
                                        padding: 1px 4px;
                                        border-radius: 2px;
                                        font-size: 13px;
                                        margin: 0px 0px;
                                        position: absolute;
                                        ">Hot Deal</span>
                                            {{-- <img src="{{ asset('assets/client/images/sho_offer.png') }}" class="img-fluid offericon new-shopping-offer" alt="" width="400" height="266"> --}}
                                    @endif
                                    <figure>
                                        <input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
                                        <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                                        <?php $imagename = Helper::getProductImage($productrow->id); ?>
                                        <!-- <a href="javascript::" class="featured-tag-grid">Featured</a> -->
                                            <a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="d-block h-100 img"
                                            <?php if(!empty($imagename)){ ?>
                                                style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
                                            <?php }else{ ?>
                                                style="background-image:url('{{  asset('assets/plugins/images/placeholder.png') }}')"
                                            <?php } ?>>

                                            <div class="read_more">

                                                {{-- <form action="{{ route('add.to.cart',$productrow->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" value="{{ $productrow->id }}" name="id">
                                                    <input type="hidden" value="{{ $productrow->product_lang_name }}" name="name">
                                                    <input type="hidden" value="{{ $priceGoestoCart }}" name="price">
                                                    <input type="hidden" value="{{ $productRegularPrice }}" name="regularprice">
                                                    <input type="hidden" value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}" name="image">
                                                    <input type="hidden" value="1" name="quantity">
                                                    {{-- <button class="btn "><i class="fa fa-shopping-bag"></i>{{__("message.add_to_cart_btn")}}</button>
                                                    @if ($productrow->stock > 0 && !$productrow->outofstock == 'outofstock')
                                                        <button class="btn "><i class="fa fa-shopping-bag"></i>{{__("message.add_to_cart_btn")}}</button>

                                                    @else
                                                        <span style=" color:red;">Out Of Stock</span>

                                                    @endif
                                                </form> --}}
                                                <button class="btn "><i class="fa fa-eye"></i> View More </button>
                                            </div>
                                            <!-- <div class="read_more"><span> Add To Cart</span></div> -->
                                            <div class="pro-icn">
                                                {{-- <a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a> --}}
                                                {{-- <a href="" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a> --}}

                                                <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="w-c-q-icn" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                                                <?php
                                                if(!in_array($productrow->id, $wislistProductId)){ ?>
                                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                    <?php }else{ ?>
                                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                                    <?php } ?>
                                                </a>

                                            </div>
                                        </a>
                                        <!-- <small>Hotel</small> -->
                                    </figure>
                                    <div class="wrapper featured-body">
                                        <h3 class="ellipsis"><a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">{{ $productrow->product_lang_name }}</a></h3>
                                        <!-- <small>Lille, France</small> -->
                                        <p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
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

                                        <li style="display:none;">
                                            <div class="score"><span> Awesome <em> 2 Reviews </em> </span><strong>5.0</strong></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </section>

            <section class="section-b-padding pro-page-content tags-heading-product">
                <?php if(!empty($data['maylikeProductData'] )){ ?>
                    <div class="container-fluid">
                        <div class="main_title_2">
                            <span><em></em></span>
                            <h2 class="title-text-main">{{__("You may also like this")}}</h2>
                        </div>

                        <div id="product" class="owl-theme listing_related_product row">
                            <?php
                            foreach($data['maylikeProductData']  as $productrow){
                            $productprice = Helper::getProductprice($productrow->id,$currencycode);
                            $validityOfOffer = Helper::getValidityOfOffer($productrow->id);
                            $productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
                            $productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0;
                            $productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0;

                            if(!empty($validityOfOffer)){
                                if($productRegularPrice>0 && $productSalesPrice>0){
                                $priceGoestoCart = $productSalesPrice;
                                }else{
                                $priceGoestoCart = $productRegularPrice;
                                }

                            }else{
                                $priceGoestoCart = $productRegularPrice;
                            }
                            ?>
                            <div class="item item2 col-md-3 product_listing">
                                <div class="strip strip2 grid">
                                    <?php
                                    $getNewShopping = Helper::getNewShopping($productrow->id);
                                    $getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
                                    $getNewOffer = Helper::getNewOffer($productrow->id);
                                    $getProductOffers = Helper::getProductOffers($productrow->id);
                                    ?>
                                    @if($getNewOffer == 1 || $getNewShopping == 1)
                                        <img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid offericon newicon" alt="" width="400" height="266">
                                    @endif
                                    @if($getProductOffers == 1 || $getNewShoppingOffer == 1)
                                            {{-- <img src="{{ asset('assets/client/images/sho_offer.png') }}" class="img-fluid offericon new-shopping-offer" alt="" width="400" height="266">
                                     --}}
                                    <span style="
                                    background: rgb(231, 248, 236); color:rgb(88 168 65);
                                    padding: 1px 4px;
                                    border-radius: 2px;
                                    font-size: 13px;
                                    margin: 0px 0px;
                                    position: absolute;
                                    z-index: 20000;
                                    ">Hot Deal</span>
                                    @endif
                                    <figure>
                                        <input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
                                        <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                                        <?php $imagename = Helper::getProductImage($productrow->id); ?>
                                        <!-- <a href="javascript::" class="featured-tag-grid">Featured</a> -->
                                            <a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="d-block h-100 img"
                                            <?php if(!empty($imagename)){ ?>
                                                style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
                                            <?php }else{ ?>
                                                style="background-image:url('{{  asset('assets/plugins/images/placeholder.png') }}')"
                                            <?php } ?>>

                                            <div class="read_more">


                                                <button class="btn "><i class="fa fa-eye"></i> View More </button>
                                            </div>
                                            <!-- <div class="read_more"><span> Add To Cart</span></div> -->
                                            <div class="pro-icn">
                                                {{-- <a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a> --}}
                                                <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="w-c-q-icn" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                                                    <?php
                                                    if(!in_array($productrow->id, $wislistProductId)){ ?>
                                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                    <?php }else{ ?>
                                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                                    <?php } ?>
                                                </a>
                                                {{-- <a target="_blank" href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="w-c-q-icn">
                                                    <i class="fa fa-shopping-bag"></i>
                                                </a> --}}
                                            </div>
                                        </a>
                                        <!-- <small>Hotel</small> -->
                                    </figure>
                                    <div class="wrapper featured-body">
                                        <h3 class="ellipsis"><a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">{{ $productrow->product_lang_name }}</a></h3>
                                        <!-- <small>Lille, France</small> -->
                                        <p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
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

                                        {{-- <li style="display:none;">
                                            <div class="score"><span> Awesome <em> 2 Reviews </em> </span><strong>5.0</strong></div>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </section>

        </div>
    </div>
</div>





<script>

     // Google tag event for product page view
    @if(app()->environment('production'))
        gtag('event', 'product_detail_view', {
            product_id: '{{ $productID }}',
            entry_source: '{{ url()->previous() }}'
        });
    @endif

    $('.company_details_logo_img').on('click', function() {
        $('.company_detail_menu').toggle();
    });
    $('.btn_mobile').on('click', function() {
        $('.company_detail_menu').show();
    });

    function getAddressFromLatLng(latitude, longitude) {
        //const apiKey = 'AIzaSyCDWk_AYrkrQ0IewYKg25O0XjC5o1VbbGk';
        const apiUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key={{ env('GOOGLE_MAP_API_KEY') }}`;
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'OK') {

                    const address = data.results[0].formatted_address;
                    $('#current_user_address').html(address);

                    getCityFromAddress(address)
                    .then(city => {
                        $('#hidden_current_city').val(city);
                        //console.log("City:", city);
                    });

                } else {console.error('Geocoding failed:', data.status);}
            })
            .catch(error => {
                console.error('Error fetching geocoding data:', error);
            });
    }


    const latitude = '{{ $latitude_session }}';
    const longitude = '{{ $longitude_session }}';
    // getAddressFromLatLng(latitude, longitude);
    function getCurrentLocation() {
        if (navigator.geolocation) {
            // Get the current user's position
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Extract latitude and longitude from the position object
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    //console.log("Latitude:", latitude);
                    //console.log("Longitude:", longitude);
                    getAddressFromLatLng(latitude, longitude);
                },
                function(error) {
                    getAddressFromLatLng(latitude, longitude);
                    console.error("Error getting current location:", error);
                }
            );
        } else {console.error("Geolocation is not supported by this browser.");}
    }
    // Example usage
    getCurrentLocation();

    function getCityFromAddress(address) {

        const apiUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key={{ env('GOOGLE_MAP_API_KEY') }}`;

        return fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'OK') {
                    // Extract the city component from the first result
                    const components = data.results[0].address_components;
                    for (let component of components) {
                        if (component.types.includes('locality')) {
                            return component.long_name;
                        }
                    }
                } else {
                    console.error('Geocoding failed:', data.status);
                    return null;
                }
            })
            .catch(error => {
                console.error('Error fetching geocoding data:', error);
                return null;
            });
    }







    $("input:checkbox").on('click', function() {
        var $box = $(this);
        if ($box.is(":checked")) {
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            $(group).prop("checked", false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
    });

    $(document).on('click', '.submitbtn', function(e) {

        $('.AJloader').show();
        var user_login = $('#user_id').val();
        if (user_login == 0) {
            toastr.error('Please login');
            window.setTimeout(function() {
                $('.AJloader').hide();
                window.location.href = "{{ url('login') }}";
            }, 2000);
            return false;
        }

        // var is_purchase_product = '{{ $is_purchase_product }}';
        // if(is_purchase_product == 0){
        //     window.setTimeout(function() { $('.AJloader').hide(); }, 2000);
        //     toastr.error("Sorry! You are not allowed to review this product since you haven't bought it.");
        //     return false;
        // }

        e.preventDefault();
        $(document).find('.required').removeClass('is-invalid');
        $(document).find('.invalid-feedback').remove();
        var obj = $(this);
        let requestTxt = $(this).attr('process-text');
        requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
        var oldhtml = $(this).html();

        var form = $(this).closest("form");
        var url = form.attr('action');
        var method = form.attr('method');
        var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <span class="">${requestTxt}...</span>`;

        var allformdata = new FormData(form[0]);

        $.ajax({
            type: method
            , url: url
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: allformdata
            , mimeType: "multipart/form-data"
            , contentType: false
            , cache: false
            , processData: false
            , dataType: "json"
            , beforeSend: function() {
                obj.attr("disabled", true);
                obj.html(loader);
            },
            success: function(response) {
                $('.AJloader').hide();
                console.log(response);
                if (response.status == false) {
                    $.each(response.errors, function(index, value) {
                        form.find('.required').each(function() {
                            var fieldName = $(this).attr('name');

                            if (fieldName == index && value != "") {
                                $(this).addClass("is-invalid");
                                $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                $(this).focus();
                            }
                        });
                    });
                } else {
                    if (typeof(response.message) != "undefined") {
                        console.log(response.message);
                        $("#reviewfrm")[0].reset();
                        toastr.success(response.message);
                    }
                }
                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
            , error: function(err) {}
        });
    });


    $(document).on('click', '.request_vendor', function(e) {
                e.preventDefault();

                $(document).find('.required').removeClass('is-invalid');
                $(document).find('.invalid-feedback').remove();

                var obj = $(this);
                let requestTxt = $(this).attr('process-text');
                requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
                var oldhtml = $(this).html();

                var form = $(this).closest("form");
                var url = form.attr('action');
                var method = form.attr('method');
                var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="">${requestTxt}...</span>`;

                form.find('.required').removeClass('is-invalid').removeAttr('aria-invalid');
                form.find('.invalid-feedback').remove();
                form.find('.respondata').empty().show();

                var allformdata = new FormData(form[0]);
                $.ajax({
                    type: method
                    , url: url
                    , headers: {
                        'X-CSRF-TOKEN': form.find('input[name="_token"]').val()
                    }
                    , data: allformdata
                    , mimeType: "multipart/form-data"
                    , contentType: false
                    , cache: false
                    , processData: false
                    , dataType: "json"
                    , beforeSend: function() {
                        obj.attr("disabled", true);
                        obj.html(loader);
                    },

                    success: function(response) {
                        console.log(response);

                        if (response.status == false) {
                            $.each(response.errors, function(index, value) {
                                form.find('.required').each(function() {
                                    var fieldName = $(this).attr('name');
                                    if (fieldName == index && value != "") {
                                        $(this).addClass("is-invalid");
                                        $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                        $(this).focus();
                                    }
                                });
                            });

                            if (typeof(response.message) != "undefined") {
                                $('.respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');

                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 3000);
                                setTimeout(function() {$('#myModal').modal('hide');}, 5000);
                            }

                        } else {
                            if (typeof(response.message) != "undefined") {
                                console.log(response.message);
                                $("#bookingform")[0].reset();
                                $('.respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');
                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 3000);
                                setTimeout(function() {$('#myModal').modal('hide');}, 5000);

                            }

                        }
                        obj.attr("disabled", false);
                        obj.html(oldhtml);
                    }
                    , error: function(xhr) {
                        obj.attr("disabled", false);
                        obj.html(oldhtml);

                        var response = xhr.responseJSON || {};
                        var responseMessage = form.find('.respondata').first();

                        if (xhr.status === 422) {
                            var errors = response.errors || {};
                            var firstInvalidField = null;
                            var hasErrors = false;

                            $.each(errors, function(fieldName, messages) {
                                var message = Array.isArray(messages) ? messages[0] : messages;
                                if (!message) {
                                    return;
                                }

                                hasErrors = true;
                                var field = form.find(':input').filter(function() {
                                    return this.name === fieldName;
                                }).first();

                                if (field.length) {
                                    field.addClass('is-invalid').attr('aria-invalid', 'true');
                                    $('<span>', {
                                        class: 'red invalid-feedback',
                                        role: 'alert'
                                    }).text(message).insertAfter(field);

                                    if (!firstInvalidField && field.is(':visible')) {
                                        firstInvalidField = field;
                                    }
                                }
                            });

                            responseMessage.html(
                                $('<p>', {
                                    css: {
                                        margin: '13px 0',
                                        color: 'red',
                                        fontWeight: 500
                                    }
                                }).text(hasErrors
                                    ? 'Please correct the highlighted fields and try again.'
                                    : 'Please check the information and try again.')
                            );

                            if (firstInvalidField) {
                                firstInvalidField.trigger('focus');
                            }
                        } else if (xhr.status === 419) {
                            responseMessage.html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">Your session expired. Refresh the page and try again.</p>');
                        } else {
                            responseMessage.html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">Unable to submit your request. Please try again.</p>');
                        }
                    }
                });

            });

    $(document).on('click', '#myModal .modal_header_close_btn', function() {
        $('#myModal').modal('hide');
    });

    $(document).ready(function() {

        $('#tab-2').removeClass('show');

        $("#loader").fadeOut("slow");

        setTimeout(
        function()
        {

            var hidden_current_city = $("#hidden_current_city").val();
            var user_city_name = '{{ $userCityName }}';

            if(user_city_name){
                var city_name = user_city_name;
            }else{
                var city_name = hidden_current_city;
            }
            if(city_name) {
               $.ajax({
                    url: baseUrl + '/get-city-by-name'
                    , type: "POST"
                    , data: {
                        city_name: city_name
                        , _token: '{{ csrf_token() }}'
                    }
                    , dataType: 'json'
                    , success: function(result) {

                         //current date
                         var currentDate = new Date();
                            currentDate.setDate(currentDate.getDate() + result.default_delivery_day);
                            var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday', 'Saterday'];
                            var monthsOfYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            var dayOfWeek = daysOfWeek[currentDate.getDay()];
                            var dayOfMonth = currentDate.getDate();
                            var monthOfYear = monthsOfYear[currentDate.getMonth()];
                            //var year = currentDate.getFullYear();
                            var formatted_default_delivery_day = dayOfMonth+ ' ' + monthOfYear + ', ' +dayOfWeek ; //+ ' ' + year;
                            if(dayOfWeek != undefined){
                                $('#delivery_date_section').show();
                                $('#delivery_date').html(formatted_default_delivery_day);
                            }

                    }
                });
        }

    }, 1000);

        $('#combostar').on('change', function() {
            $('#starcount').text($(this).val());
        });
        $('#combostar').combostars({
            starUrl: "{{ asset('assets/images/stars.png') }}",
        });

        $('.minus-btn').click(function() {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $('#Msg').html("");
            $input.val(count);
            $input.change();
            return false;
        });
        $('.plus-btn').click(function() {
            var $input = $(this).parent().find('input');
            var stock = (parseInt($('#stock').val()));

            var $quantitydata = $input.val(parseInt($input.val()) + 1);
            c_stock = stock < parseInt($quantitydata.val()) ? stock : parseInt($quantitydata.val());
            if (c_stock == stock) {
                $('#Msg').html("Required quantity is not in stock");
            } else {
                $('#Msg').html("");
            }
            $input.val(c_stock);
            $input.change();
            return false;
        });

        $(".outofstock").click(function() {
            @if ($inStoreOnly == 'in_store_only')
                toastr.error('Sorry! this product is availale in store only');
            @else
                toastr.error('Sorry! this product is out of stock');
            @endif
            setTimeout(function() {$('#myModal').modal('show');}, 1000);
            return false;
        });


        $(".variationattr").click(function() {

            @if ($outOfStockFlag == 'outofstock')
                toastr.error('Sorry! this product is out of stock');
                setTimeout(function() {$('#myModal').modal('show');}, 1000);
                return false;
            @endif

            @if ($inStoreOnly == 'in_store_only')
                toastr.error('Sorry! this product is availale in store only');
                setTimeout(function() {$('#myModal').modal('show');}, 1000);
                return false;
            @endif

            $('.AJloader').show();
            var arributes = $(".variationattr:checked").map(function () {
                return this.value
            }).get()

            var d_stock = $('#stock').val();
            var d_ena_number = $('#d_ena_number').val();

            if(arributes.length > 0){
                $('#attribute_value').val(arributes.join(','));
                var product_id = $('#id').val();
                $.ajax({
                    url: baseUrl + '/get-product-variations/' + product_id,
                    type: "POST",
                    data: {
                        'arributes':arributes.join(','),
                        _token: '{{csrf_token()}}'
                        },
                    success: function (data) {

                        console.log('datadd - ',data);

                        if(data){

                            if(data.v_stock == 0){
                                console.log('v 0');
                                $(".pro-instock").html('<span style="color:#e30514;">Out of Stock</span>');
                                $("#a_addtocart").removeClass("addtocart").addClass("outofstock");
                                $(".a_outofstock_ajax").show();
                                $(".a_addtocart_ajax").hide();
                                $("#contact_vendor_without_c").show();
                                $("#contact_vendor_with_c").hide();
                                $("#stock_details_section").hide();
                                $('.ena_number').html(data.v_ena);
                            }else{
                                console.log('v 1');
                                $(".pro-instock").html('In stock');
                                $("#contact_vendor_without_c").hide();
                                $("#contact_vendor_with_c").hide();
                                $("#stock_details_section").show();
                                $(".a_outofstock_ajax").hide();
                                $(".a_addtocart_ajax").show();
                                $("#a_addtocart").removeClass("outofstock").addClass("addtocart");
                                $('.ena_number').html(data.v_ena);
                            }

                            $('.variation_stock').html(data.v_stock);
                            $('#v_stock').val(data.v_stock);
                            //$('#ena_number').val(data.v_ena);

                        }else{

                            console.log('Data 0');

                            $(".pro-instock").html('<span style="color:#e30514;">Out of Stock</span>');
                            $("#a_addtocart").removeClass("addtocart").addClass("outofstock");
                            $(".a_outofstock_ajax").show();
                            $(".a_addtocart_ajax").hide();
                            $("#contact_vendor_with_c").hide();
                            $("#contact_vendor_without_c").show();
                            $("#stock_details_section").hide();
                            $('.ena_number').html('');

                        }
                        $('.AJloader').hide();
                    },
                    error: function (data) {
                        $('.AJloader').hide();
                    },
                });
            }else{

                console.log('arributes ',arributes);

                $('.AJloader').hide();
                $(".pro-instock").show();
                $("#stock_details_section").show();
                $(".a_outofstock_ajax").hide();
                $(".a_addtocart_ajax").show();
                $('.variation_stock').html(d_stock);
                $('#stock').val(d_stock);
                $('#v_stock').val('');
                $('.ena_number').html(d_ena_number);
                $('#ena_number').val(d_ena_number);

            }

        });

        $('.addtocart').on('click', function() {

            $('.AJloader').show();

           var productAttributeLength = $(".color-label").length;
            var checkboxValues = [];
            var checkboxAttrid = [];
            var checkboxAttrname = [];
            var checkboxClassName = [];
            var attributeValueInOption = [];

            for (var checkConfirm=0;checkConfirm<productAttributeLength;checkConfirm++){

                $('input[type=checkbox][id=variation'+checkConfirm+']:checked').each(function(index, elem) {
                    checkboxValues.push($(elem).val());
                    checkboxAttrid.push($(elem).data('attrid'));
                    checkboxAttrname.push($(elem).data('attrname'));
                    checkboxClassName.push($(elem).attr('id'));
                });
                if(jQuery.inArray("variation"+checkConfirm, checkboxClassName) != -1) {
                    attributeValueInOption.push(1); //is in array
                }
                else {
                    attributeValueInOption.push(0); //is NOT in array
                }
            }
            var optionAttributeValue = attributeValueInOption.every(checkAttributeAvailable);

            function checkAttributeAvailable(attributeoption) {
            return attributeoption > 0;
            }
            if(optionAttributeValue == false) {
                toastr.error('Please select product variation first.');
                $('.AJloader').hide();
                return false;
            }

            @php
               $product_stock = $data['productDetails']->stock??'0';
            @endphp

            var product_id = $('#id').val();
            var directory_id = $('#directory_id').val();
            var product_name = $('#name').val();
            var product_price = $('#price').val();
            var product_regularprice = $('#regularprice').val();
            var free_product_data = $('#free_product_data').val();
            var product_image = $('#image').val();
            var product_offer_dicount_type = $('#offer_dicount_type').val();
            var product_offer = $('#offer').val();
            var product_stock = $('#productstock').val();
            var product_shopping_dicount_type = $('#shopping_dicount_type').val();
            var product_shoppingcard = $('#shoppingcard').val();
            var v_stock = {{ $product_stock ?? 0 }}; //$('#v_stock').val();
            var attribute_value = $('#attribute_value').val();
            var product_quantity = $('#quantity').val();
            var product_number = $('#supplier_product_no').val();
            var product_seo_slug = $('#product_slug').val();

            if (product_quantity != "") {
                product_quantity = product_quantity;
            } else {
                product_quantity = 1;
            }
            $.ajax({
                type: "POST",
                url: baseUrl + '/add-to-cart/' + product_id
                , data: {
                    'id': product_id
                    , 'name': product_name
                    , 'directory_id': directory_id
                    , 'free_product_data': free_product_data
                    , 'price': product_price
                    , 'regularprice': product_regularprice
                    , 'image': product_image
                    , 'attr_value': checkboxValues
                    , 'attr_id': checkboxAttrid
                    , 'attr_name': checkboxAttrname
                    , 'offer_dicount_type': product_offer_dicount_type
                    , 'offer': product_offer
                    , 'productstock' : product_stock
                    , 'shopping_dicount_type': product_shopping_dicount_type
                    , 'shoppingcard': product_shoppingcard
                    , 'quantity': product_quantity
                    , 'supplier_product_no': product_number
                    , 'product_slug': product_seo_slug
                    , 'v_stock': v_stock
                    , 'attribute_value': attribute_value
                    , _token: '{{csrf_token()}}'
                }
                , success: function(result) {
                    $('.AJloader').hide();
                    window.location.href = baseUrl + "/cart";
                }
            });
        });

        $('.addtobuy').on('click', function() {

            $('.AJloader').show();

            var productAttributeLength = $(".color-label").length;
            var checkboxValues = [];
            var checkboxAttrid = [];
            var checkboxAttrname = [];
            var checkboxClassName = [];
            var attributeValueInOption = [];

            for (var checkConfirm=0;checkConfirm<productAttributeLength;checkConfirm++){

                $('input[type=checkbox][id=variation'+checkConfirm+']:checked').each(function(index, elem) {
                    checkboxValues.push($(elem).val());
                    checkboxAttrid.push($(elem).data('attrid'));
                    checkboxAttrname.push($(elem).data('attrname'));
                    checkboxClassName.push($(elem).attr('id'));
                });
                if(jQuery.inArray("variation"+checkConfirm, checkboxClassName) != -1) {
                    attributeValueInOption.push(1); //is in array
                }
                else {
                    attributeValueInOption.push(0); //is NOT in array
                }
            }
            var optionAttributeValue = attributeValueInOption.every(checkAttributeAvailable);

            function checkAttributeAvailable(attributeoption) {
            return attributeoption > 0;
            }
            if(optionAttributeValue == false) {
                toastr.error('Please select product variation first.');
                return false;
            }

            @php
               $product_stock = $data['productDetails']->stossck??'0';
            @endphp


            var product_id = $('#id').val();
            var directory_id = $('#directory_id').val();
            var product_name = $('#name').val();
            var product_price = $('#price').val();
            var product_regularprice = $('#regularprice').val();
            var free_product_data = $('#free_product_data').val();
            var product_image = $('#image').val();
            var product_offer_dicount_type = $('#offer_dicount_type').val();
            var product_offer = $('#offer').val();
            var product_stock = $('#productstock').val();
            var product_shopping_dicount_type = $('#shopping_dicount_type').val();
            var product_shoppingcard = $('#shoppingcard').val();
            var v_stock = {{ $product_stock ?? 0 }}; //$('#v_stock').val();
            var attribute_value = $('#attribute_value').val();
            var product_quantity = $('#quantity').val();
            var product_number = $('#supplier_product_no').val();
            var product_seo_slug = $('#product_slug').val();

            if (product_quantity != "") {
                product_quantity = product_quantity;
            } else {
                product_quantity = 1;
            }

            $.ajax({
                type: "POST",
                url: baseUrl + '/add-to-cart/' + product_id
                , data: {
                    'id': product_id
                    , 'name': product_name
                    , 'directory_id': directory_id
                    , 'free_product_data': free_product_data
                    , 'price': product_price
                    , 'regularprice': product_regularprice
                    , 'image': product_image
                    , 'attr_value': checkboxValues
                    , 'attr_id': checkboxAttrid
                    , 'attr_name': checkboxAttrname
                    , 'offer_dicount_type': product_offer_dicount_type
                    , 'offer': product_offer
                    , 'productstock' : product_stock
                    , 'shopping_dicount_type': product_shopping_dicount_type
                    , 'shoppingcard': product_shoppingcard
                    , 'quantity': product_quantity
                    , 'supplier_product_no': product_number
                    ,  'product_slug': product_seo_slug
                    , 'v_stock': v_stock
                    , 'attribute_value': attribute_value
                    , _token: '{{csrf_token()}}'
                }
                , success: function(result) {
                    $('.AJloader').hide();
                    window.location.href = baseUrl + "/checkout";
                }
            });
        });
    });

</script>

<!-- /container -->
<script type="text/javascript">
    $(document).ready(function() {


        $('.pro-pag-5-slider').owlCarousel({
            itemElement: 'LI'
            , stageElement: 'UL'
            , items: '4'
            , nav: true
            , dots: false
            , pagination: false
        , })

        $('#product').owlCarousel({
            loop: true
            , autoplay: true
            , dots: false
            , pagination: false
            , nav: true
            , lazyLoad: true
            , singleItem: false
            , slideSpeed: 300
            , paginationSpeed: 400
            , navigation : true
            , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

            , responsiveClass: true
            , responsive: {
                0: {
                    items: 2
                }
                , 600: {
                    items: 3
                }
                , 1024: {
                    items: 4
                }
                , 1366: {
                    items: 5
                }
            }

        });


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

    function addToLikeCounter(elem, id) {
        var user_login = $('#user_id').val();
        var type = $('#type_items').val();
        var countrycode = $('#country_code').val();
        var state = $('#state').val();
        var city = $('#city').val();
        /*if (user_login > 0) {*/
            $.ajax({
                type: 'POST'
                , url: baseUrl + '/add-to-like/' + id
                , data: {
                    "_token": "{{ csrf_token() }}"
                    , user_login: user_login
                    , id: id, type_item:type
                    ,countrycode: countrycode
                    ,state : state, city :city
                }
                , success: function(response) {
                    if (response.msg == 'liked') {
                        $('#productlikedtotal span').text(response.totallike);
                        //$(elem).html('<i class="fas fa-heart"></i>');
                        toastr.success('You Liked!');
                    }
                    else {
                        //$(elem).html('<i class="far fa-heart"></i>');
                        toastr.success('Already Liked!');
                        //toastr.success('You Unlike!');
                    }
                }
            });
        /* } else if (user_login == 0) {
            toastr.error('{{__("message.please_login_first_text")}}');

        } */
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

<style>
    .margin_60_35 {
        padding-top: 25px;
        padding-bottom: 20px;
    }
    header.sticky .main-menu ul li.nav-item a.nav-link.dropdown-toggle {
        color: #fff;
    }
    .btn-style1 {
        font-size: 13px !important;
        padding: 2px 10px !important;
        font-weight: 500 !important;
    }
    .sub_header_in {
        padding: 10px 0 !important;
    }
    .owl-theme .owl-controls .owl-buttons div {
        padding: 6px 10px !important;
    }
    .wishlist-icon {
        top: 3px;
        color: #abaab0 !important;
        background: none !important;
        padding: 4px 5px !important;
        font-size: 29px;
        right: 2px !important;
        top: 2px !important;
    }
    .wishlist-icon:hover {
        background-color: rgb(0, 0, 0);
        color: #abaab0 !important;
    }
    .fa-heart-o:before {
        content: "\f08a";
        color: #abaab0;
    }
    .modal {
        top: 75px;
    }
    .btn-style1 {
        background-color: rgb(255 157 7) !important;
        border: 2px solid rgb(255 157 7) !important;
    }
    .pro-page-content .pro-page-tab ul.nav.nav-tabs {
        margin-bottom: 25px;
    }
    span.locally_text {
        width: 140px !important;
        top: 6px;
        line-height: 20px !important;
    }
    .company_details_logo_img {
        cursor: pointer;
        width: 48px !important;
        margin: 40px 65px !important;
    }
    span.company_details_logo {
        margin: -16px -40px !important;
        position: absolute;
    }

    .sub_header_in.sticky_header {
        margin-top: 28px !important;
        padding-bottom: 10px !important;
    }


    .strip .wrapper p {
        margin-bottom: 5px;
    }
    div#product .strip .wrapper {
        padding: 0px 10px 10px 15px;
    }
    div#review_form .modal-content {
        background: #fff;
    }
    .modal-lg {
        max-width: 530px;
    }
.pro-page-content .pro-page-tab .tab-content .tab-pane .customer-reviews {
    padding-top: 5px;
}
.modal {
    top: 80px;
}
a.btn_1.full-width.purchase, .btn_1.full-width.purchase {
    background-color: #5fa800;
}

    .tab-content td ul {
        margin: 0;
    }

    .tab-content td {
        padding: 10px 20px 10px 0px;
    }

    .exzoom .exzoom_btn a:hover {
        background: #203864;
        color: #fff;
    }

    .exzoom .exzoom_nav .exzoom_nav_inner span.current {
        border: 1px solid #203864;
    }

    div#exzoom img {
        max-width: 445px;

    }

    .tags-heading-product h5 {
        font-weight: 600;
        color: #203864;
        margin-bottom: 14px;
        border-bottom: 1px solid #dfdfdf;
        padding-bottom: 10px;
    }

    .tags-product-content span {
        border: none !important;
        background: rgb(204 204 204 / 54%) !important;
        border-radius: 0;
        font-size: 14px;
        font-weight: 400;
    }

    #Msg {
        color: #e30514;
        margin-left: 10px;
    }

    .exzoom .exzoom_btn {
        position: relative;
        margin: -61px 0px 70px 0px;
    }
    .exzoom .exzoom_btn a {
        left: -1px;
        top: -1px;
    }

    .exzoom .exzoom_img_ul_outer .exzoom_img_ul li {
        text-align: center;
    }
    #product .owl-item {
        /* width: 20% !important; */
        /* margin: 0 15px; */
    }
    div#product .item {
        margin: 0 15px;
    }
    div#product .strip .wrapper {
        padding: 10px 10px 6px 20px;
    }
    .owl-prev {
        position: absolute;
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 50%;
        opacity: 1;
        margin-top: auto;
        margin-bottom: auto;
        box-shadow: -2px 1px 10px #bdbdbd;
        top: calc(50% - 40px);
        cursor: pointer;
        left: 0;
        padding: 0;
        line-height: 44px;
        margin: 0 0 0 10px!important;
    }
    #myModal .modal-content {
        background-color: #fff;
    }
    .owl-next{

        position: absolute;
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 50%;
        opacity: 1;
        margin-top: auto;
        margin-bottom: auto;
        box-shadow: -2px 1px 10px #bdbdbd;
        top: calc(50% - 40px);
        cursor: pointer;
        right: 0;
        padding: 0;
        line-height: 44px;
        margin: 0 10 0 0px!important;
    }

    .owl-prev i , .owl-next i {
        font-size: 25px;
    }

    .owl-theme .owl-controls .owl-buttons div {
        background: #0a071e;
    }

    .owl-controls.clickable {
        margin: 0;
    }
    div#product.owl-carousel .owl-item img {
        height: 100%;
        object-fit: unset;
    }
    div#product.owl-carousel .owl-item img.offericon {
        height: auto;
        object-fit: inherit;
    }
    .newicon{
        position: absolute;
        max-width: 60px;
        left: 10px;
        top: -6px;
        z-index: 1;
    }
    .pro-page .pro-image .pro-info span.pro-details {
        font-size: 14px;
    }

    .red {
        background: none;
    }
    .modal-header b {
        width: 100%;
        text-align: center !important;
    }
    .pro-page-content .pro-page-tab ul.nav.nav-tabs li.nav-item a.nav-link {
        border: 1px solid #5fa800;
    }
    .pro-page-content .pro-page-tab ul.nav.nav-tabs li.nav-item a.nav-link.active, .pro-page-content .pro-page-tab ul.nav.nav-tabs li.nav-item a.nav-link:hover {
        color: #fff;
        border: 1px solid #000;
        background: rgb(255 157 7);
    }
    .pro-page-content .pro-page-tab .tab-content .tab-pane .customer-reviews {
        border-top: 1px solid #5fa800;
    }
    .pro-page .pro-image .pro-info .pro-cod span.p-code span.code-title {
        margin-right: 1px;
    }
    .similar_product_list img {
        width: 95px !important;
        height: 85px !important;
        border: 1px solid #eee;
        margin: 5px;
    }
    .similar_product_cont ul.similar_product a img {
        border: 1px solid #eee;
    }


	@media (max-width: 768px) {

        .exzoom .exzoom_btn {
            margin: -69px 0px 65px !important;
        }
        span.locally_text {
            width: 100% !important;
            right: 177px;
            line-height: 35px;
        }
        .item.item2.col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
            margin: 0px !important;
            padding: 1px 3px 2px 3px;
        }
        .goog-te-gadget-simple {
            margin-top: -3px;
        }
        .pro-page-content .pro-page-tab ul.nav.nav-tabs li.nav-item a.nav-link {
            padding: 4px 15px;
        }
        .pro-page-content .pro-page-tab ul.nav.nav-tabs {
            margin-bottom: 15px;
        }
        li.nav-item.rdescription {
            padding: 9px 0px;
        }
        .section-b-padding {
            padding-bottom: 25px;
        }
        .image-thumbnail.existing-img.col-md-3.similar_product_list {
            width: 33%;
            margin: 0px;
        }
        .similar_product_list img {
            width: 85px !important;
            padding: 2px;
        }
        .sub_header_in.sticky_header {
            margin-top: 48px;
            padding-bottom: 2px;
        }

    }

    #similar_product_model .modal-content {
        background-color: #fff;
    }
    .section-b-padding {
        padding-bottom: 25px;
    }

    .main_title_2 h2 {
        margin: 0px 0 0;
    }
    div#product .item2 {
        margin: 0px auto;
    }
    div#listings .strip2 ul {
        bottom: 10px;
    }
    .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed span {
        color: #2e2a2a;
    }
    .fa-heart-o:before {
        color: #ffffff;
    }
    .modal-header {
        padding: 5px;
        border-bottom: none;
    }
    #myModal .vendor-request-dialog {
        max-width: 580px;
    }
    #myModal .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 14px;
        box-shadow: 0 18px 55px rgba(20, 35, 55, .22);
    }
    #myModal .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 24px 28px 20px;
        border-bottom: 1px solid #edf0f4;
    }
    #myModal .vendor-request-heading {
        display: flex;
        align-items: center;
        min-width: 0;
    }
    #myModal .vendor-request-icon {
        display: inline-flex;
        flex: 0 0 46px;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        margin-right: 14px;
        color: #fff;
        background: #5fa800;
        border-radius: 12px;
        font-size: 19px;
    }
    #myModal .vendor-request-title {
        margin: 0 0 4px;
        color: #202a36;
        font-size: 20px;
        font-weight: 700;
    }
    #myModal .vendor-request-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.45;
    }
    #myModal .modal_header_close_btn {
        padding: 0 0 0 12px;
        color: #667085;
        font-size: 27px;
        line-height: 1;
        opacity: .75;
    }
    #myModal .modal_header_close_btn:hover {
        color: #202a36;
        opacity: 1;
    }
    #myModal .modal-body {
        padding: 24px 28px 28px;
    }
    #bookingform .vendor-request-field {
        margin-bottom: 18px;
    }
    #bookingform .vendor-request-field label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 13px;
        font-weight: 600;
    }
    #bookingform .vendor-request-required {
        color: #dc3545;
    }
    #bookingform .vendor-request-field .form-control {
        min-height: 46px;
        padding: 11px 13px;
        color: #263238;
        background-color: #fff;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        box-shadow: none;
        font-size: 14px;
    }
    #bookingform .vendor-request-field textarea.form-control {
        min-height: 112px;
        resize: vertical;
    }
    #bookingform .vendor-request-field .form-control:focus {
        border-color: #78b83c;
        box-shadow: 0 0 0 3px rgba(95, 168, 0, .13);
        outline: 0;
    }
    #bookingform .vendor-request-field .form-control::placeholder {
        color: #98a2b3;
    }
    #bookingform .vendor-request-help {
        display: block;
        margin-top: 6px;
        color: #667085;
        font-size: 12px;
    }
    #bookingform .vendor-request-response:empty {
        display: none;
    }
    #bookingform .vendor-request-response {
        margin-bottom: 14px;
    }
    #bookingform .vendor-request-submit {
        min-height: 48px;
        margin-top: 2px;
        border-radius: 7px;
        font-size: 15px;
        font-weight: 600;
    }
    #bookingform .vendor-request-submit i {
        margin-right: 7px;
    }
    @media (max-width: 575.98px) {
        #myModal .vendor-request-dialog {
            margin: 12px;
        }
        #myModal .modal-header {
            padding: 20px 18px 17px;
        }
        #myModal .modal-body {
            padding: 20px 18px 22px;
        }
        #myModal .vendor-request-icon {
            flex-basis: 40px;
            width: 40px;
            height: 40px;
            margin-right: 11px;
        }
        #myModal .vendor-request-title {
            font-size: 18px;
        }
    }
</style>


</main>

@endsection
