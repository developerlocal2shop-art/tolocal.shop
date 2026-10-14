@extends('layouts.client.common_footer')
@if (isset($metadata) && !empty($metadata))
@if (!empty($metadata->meta_title))
@section('title', $metadata->meta_title)
@else
@section('title', $data['offerData']->title)
@endif
@else
@section('title', $data['offerData']->title)
@endif
@section('content')
<?php
$user_ip = $_SERVER['REMOTE_ADDR'];
// $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);
if($user_ip=='127.0.0.1' || $user_ip=='localhost' || $user_ip=='::1' ){
    $countryCode = 'IN';
    $state = 'National Capital Territory of Delhi';
    $state_array = explode(" ",$state);
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

$wislistProductId=array();
foreach($wishlists as $wishid){
    $wislistProductId[]=$wishid['product_id'];
}
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
  $currencySymbol = session()->get('currency_code')['currsymbol'];
  $currencycode = session()->get('currency_code')['currcode'];
}else{
  $currencySymbol = $defaultCurrency[0]->symbol;
  $currencycode = $defaultCurrency[0]->code;
}
$bannerImage = isset($data['bannerImage'][0]->name_thumbnail)?$data['bannerImage'][0]->name_thumbnail:"";
?>

<!-- Video Modal -->
<div class="modal fade listing_detail_gallery" id="video_model" tabindex="-1" role="dialog" style="top:50px;"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Offer Video</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview" id="listgallerypreview"></a>
                        @if(!empty($data['offerData']->video))
                        <video height="" controls preload="auto" data-toggle="modal" data-target="#video_model"
                            style="opacity: unset !important;">
                            <source src="{{ env('IMAGE_PATH').'files/offer/'.$data['offerData']->video??'' }}"
                                type="video/mp4">
                        </video>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main page -->
<main>
    <!-- Gallery Image Modal -->
    <div class="modal fade listing_detail_gallery" id="allgalleryimages_model" tabindex="-1" role="dialog"
        style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">All Gallery Images</h5>
                    <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 listgallerypreview" id="listgallerypreview">
                            <div class="row">
                                <?php
                            if(!empty($data['galleryImage'])){ ?>
                                @foreach($data['galleryImage'] as $rowgallery)
                                <div class="thumbnail existing-img col-md-4 col-6">
                                    @if($rowgallery->name_thumbnail)
                                    <a href="{{env('IMAGE_PATH').'images/offer/'.$rowgallery->name_thumbnail.'.webp' }}"
                                        data-lightbox="gallery">
                                        <img src="{{env('IMAGE_PATH').'images/offer/'.$rowgallery->name_thumbnail.'.webp' }}"
                                            class="img-fluid" alt="">
                                    </a>
                                    @else
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img"
                                        alt="directory-img" class="previewBlocks" />
                                    @endif
                                </div>
                                @endforeach
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="common_banner">
    @if($bannerImage != null)
        <div class="hero_in shop_detail" >
        <img src="{{ env('IMAGE_PATH').'files/offer/'.$bannerImage }}" alt="">
        </div>
    @else
        <div class="hero_in shop_detail">
        <img src="{{ asset('assets/images/no-img.jpg') }}" alt="">
        </div>
    @endif
   </div> -->
    <div id="offerbannerimg" class="owl-carousel owl-theme listing_gllary_img common_banner">
        <div class="item">
            @if($bannerImage != null)
            <div class="hero_in shop_detail">
                <img src="{{ env('IMAGE_PATH').'images/offer/'.$bannerImage.'.webp' }}" alt="">
            </div>
            @else
            <div class="hero_in shop_detail">
                <img src="{{ asset('assets/images/no-img.jpg') }}" alt="">
            </div>
            @endif
        </div>
    </div>




    <!--/hero_in-->
    <nav class="secondary_nav sticky_horizontal_2">
        <div class="container">
            <ul class="clearfix">
                <li><a href="#description" class="active">{{__("message.description_title")}}</a></li>
                <li><a href="#products">{{__("message.product_text")}}</a></li>
                <li><a href="#products">{{__("message.product_text")}}</a></li>
            </ul>
        </div>
    </nav>

    <?php if(!empty($data['galleryImage'])){ ?>
    <div class="container-fluid  margin_80_55" style="padding-top: 0px;">
        <div class="main_title_2">
            <h2>Offer Gallery</h2>
        </div>
        <div id="offergalimg" class="owl-carousel owl-theme listing_gllary_img">
            <?php foreach($data['galleryImage'] as $rowgallery){ ?>
            <div class="item">
                <div class="strip grid">
                    <figure>
                        <a href="{{env('IMAGE_PATH').'images/offer/'.$rowgallery->name_thumbnail.'.webp' }}"
                            data-lightbox="gallery">
                            <img src="{{env('IMAGE_PATH').'images/offer/'.$rowgallery->name_thumbnail.'.webp' }}"
                                class="img-fluid" alt="" width="400" height="266">
                        </a>
                    </figure>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="listing_see_all">
        @if(count($data['galleryImage']) > 5)
        <div class="see_all" data-toggle="modal" data-target="#allgalleryimages_model">See All</div>
        @endif
    </div>
    <?php } ?>


    <div class="container">
        <section id="description">
            <div class="row">
                <div class="col-lg-12">
                    <div class="detail_title_1 offer-detail-title">
                        <h1 style="float: left; margin: 0px 15px 0px 0px;">{{ $data['offerData']->title }}</h1>
                        <input type="hidden" name="type_item" id="type_items" value="product_offer">
                        <input type="hidden" name="country_code" id="country_code" value="{{$countryCode}}">
                        <input type="hidden" name="state" id="state" value="{{$state}}">
                        <input type="hidden" name="city" id="city" value="{{$city}}">
                        <a href="javascript:void(0);" id="productofferlikedtotal" title="Like Product"
                            productid="{{ $data['offerData']->id }}"
                            onclick="addToLikeCounter(this, <?php echo $data['offerData']->id ?>)">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            (
                            <span>
                                @if(count(Helper::CountLikeData('product_offer',$data['offerData']->id)) != 0)
                                {{ count(Helper::CountLikeData('product_offer',$data['offerData']->id)) }}
                                @else
                                {{ 0 }}
                                @endif
                            </span>
                            ) Like
                        </a>
                        @if($data['offerData']->discount_selectedvalue == 'pricediscount')
                        <i class="p-discount"> -{{ $data['offerData']->discount_firstvalue }} </i>
                        @elseif($data['offerData']->discount_selectedvalue == 'percentagediscount')
                        <i class="p-discount"> -{{ $data['offerData']->discount_firstvalue }}% </i>
                        @endif

                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-8" style="border-bottom: 1px solid #ccc;">
                    <h5>{{__("message.description_title")}}</h5>
                    <p class="text-justify">{{ $data['offerData']->description }}</p>
                </div>
                <div class="col-lg-4">
                    <h5>{{__("message.expire_date_text")}}</h5>
                    <p>{{ $data['offerData']->offer_expire_date }}</p>
                    @if(!empty($data['offerData']->directorySlug->slug))
                    <a target="_blank" class="btn btn-warning"
                        href="{{ route('listing.details',$data['offerData']->directorySlug->slug) }}">View Company </a>
                    @endif
                    @if(!empty($data['offerData']->video))
                    <a class="btn btn-warning" data-toggle="modal" data-target="#video_model">Offer video </a>
                    @endif
                </div>
            </div>
        </section>
        <!-- /row -->
    </div>
    <!-- /container -->

    <?php if(!empty($data['relatedProductData'])){ ?>
    <div class="container-fluid" id="products">
        <div class="main_title_2">
            <h2>{{__("message.product_text")}}</h2>
        </div>
        <div id="sploffer" class="owl-carousel owl-theme offer_detail_product">
            <?php
            foreach($data['relatedProductData'] as $productrow){
              $productprice = Helper::getProductprice($productrow->id,$currencycode);
              $validityOfOffer = Helper::getValidityOfOffer($productrow->id);
              $productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
              $productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
              $productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;
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
            <div class="item">
                <div class="strip grid">
                    <figure style="display: block !important;">
                        <input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id"
                            id="product_id">
                        <input type="hidden" name="user_id" id="user_id"
                            value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                        <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="wishlist-icon"
                            onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                            <?php
									if(!in_array($productrow->id, $wislistProductId)){ ?>
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <?php }else{ ?>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <?php } ?>
                        </a>
                        <?php $imagename = Helper::getProductImage($productrow->id); ?>
                        <span class="featured-tag-grid">Featured</span>
                        <a href="<?php echo url("product/detail", ['id'=> @$productrow->id,'slug' =>
                            @$productrow->seo_url]); ?>" class="d-block h-100 img"
                            <?php if(!empty($imagename)){ ?> style="background-image:url('{{
                            env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
                            <?php }else{ ?> style="background-image:url('{{
                            asset('assets/plugins/images/placeholder.png') }}')"
                            <?php } ?>>
                            <div class="read_more">
                                {{-- <form action="{{ route('add.to.cart',$productrow->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $productrow->id }}" name="id">
                                    <input type="hidden" value="{{ $productrow->name }}" name="name">
                                    <input type="hidden" value="{{ $priceGoestoCart }}" name="price">
                                    <input type="hidden" value="{{ $productRegularPrice }}" name="regularprice">
                                    <input type="hidden"
                                        value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}"
                                        name="image">
                                    <input type="hidden" value="1" name="quantity">
                                    @if ($productrow->stock > 0 && !$productrow->outofstock == 'outofstock')
                                    <button class="btn "><i
                                            class="fa fa-shopping-bag"></i>{{__("message.add_to_cart_btn")}}</button>

                                    @else
                                    <span style=" color:red;">Out Of Stock</span>
                                    @endif
                                </form> --}}
                                <button class="btn "><i class="fa fa-eye"></i> View More </button>
                            </div>
                            <div class="pro-icn">
                                <a href="wishlist.html" class="w-c-q-icn"><i class="fa fa-heart"></i></a>
                                <a href="cart.html" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
                            </div>
                        </a>
                        <!-- <small>{{__("message.hotel_text")}}</small> -->
                        <?php foreach($data['offerrelatedproductData'] as $row){ ?>
                        <small>{{ $row->title }}
                            @if($row->discount_selectedvalue == 'pricediscount')
                            <i class="p-discount"> -{{ $row->discount_firstvalue }} </i>
                            @elseif($row->discount_selectedvalue == 'percentagediscount')
                            <i class="p-discount"> -{{ $row->discount_firstvalue }}% </i>
                            @elseif($row->discount_selectedvalue == 'new')
                            <i class="p-discount"> New </i>
                            @endif
                        </small>
                        <?php } ?>
                    </figure>
                    <div class="wrapper featured-body">
                        <h3 class="ellipsis"><a href="<?php echo url("product/detail", ['id'=> @$productrow->id,'slug'
                                => @$productrow->seo_url]); ?>">{{ $productrow->product_lang_name }}</a></h3>
                        <p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
                    </div>
                    <ul class="featured-footer mb-0">
                        <?php if(!empty($validityOfOffer)){  ?>
                        <li>
                            <?php if($productSalesPrice>0){ ?>
                            <div class="pro-price"><span class="new-price notranslate">
                                    {{ $defaultCurrency[0]->symbol." ".$productSalesPrice }}
                                </span></div>
                            <?php } ?>
                            <?php if($productRegularPrice>0 && $productSalesPrice==0){?>
                            <div class="pro-price"><span class="new-price notranslate">
                                    {{ $defaultCurrency[0]->symbol." ".$productRegularPrice }}
                                </span></div>
                            <?php }else if($productRegularPrice>0){ ?>
                            <div class="pro-price"><span class="old-price notranslate">
                                    {{ $defaultCurrency[0]->symbol." ".$productRegularPrice }}
                                </span></div>
                            <?php } ?>
                        </li>
                        <?php }else{ ?>
                        <li>
                            <div class="pro-price"><span class="new-price notranslate">
                                    {{-- {{ $defaultCurrency[0]->symbol." ".$productRegularPrice." ".$productcurrency }}
                                    --}}
                                    {{ $defaultCurrency[0]->symbol." ".$productRegularPrice }}
                                </span>
                            </div>
                        </li>
                        <?php } ?>
                        <li>
                            <div class="score"><span> {{__("message.awesome_text")}} <em> {{__("message.reviews_text")}}
                                    </em> </span><strong>5.0</strong></div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <script>
        $(document).ready(function() {
            $('#sploffer,#trademark,#offerdata').owlCarousel({
                loop: true
                , items: 4
                , autoplay: true
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 2
                    }
                    , 1366: {
                        items: 5
                    }
                }
            });

            $('#offerbannerimg').owlCarousel({
                loop: true
                , autoplay: true
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: true
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
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
            $('#offergalimg').owlCarousel({
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
                        items: 1
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
        });
        function addToLikeCounter(elem, id) {
        var user_login = $('#user_id').val();
        var type = $('#type_items').val();
        var countrycode = $('#country_code').val();
        var state = $('#state').val();
        var city = $('#city').val();

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
                        $('#productofferlikedtotal span').text(response.totallike);
                        toastr.success('You Liked!');
                    }
                    else {
                        //$(elem).html('<i class="far fa-heart"></i>');
                        toastr.success('Already Liked!');
                        //toastr.success('You Unlike!');
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        'use strict';
        function createListingsMap(options) {
            var defaults = {
                markerPath: 'assets/client/images/marker.png'
                , markerPathHighlight: 'assets/client/images/marker-hover.png'
                , markerShadow: 'assets/client/images/location-pin.svg'
                , imgBasePath: 'assets/client/images/listing_thumbnails/'
                , mapPopupType: 'venue'
                , useTextIcon: false
            }

            var markersGroup = []

            var defaultIcon = L.icon({
                iconUrl: settings.markerPath,
                iconSize: [25, 37.5], // default size was 25, 37.5
                popupAnchor: [0, -18]
                , tooltipAnchor: [0, 19],
            });

            var highlightIcon = L.icon({
                iconUrl: settings.markerPathHighlight
                , iconSize: [25, 37.5]
                , popupAnchor: [0, -18]
                , tooltipAnchor: [0, 19]
            });


            function onEachFeature(feature, layer) {
                layer.on({
                    mouseover: highlightMarker
                    , mouseout: resetMarker
                    , click: scrollToListing
                });

                if (feature.properties && feature.properties.about) {

                    layer.bindPopup(getPopupContent(feature.properties), {
                        minwidth: 200
                        , maxWidth: 600
                        , className: 'map-custom-popup'
                    });

                    if (settings.useTextIcon) {
                        layer.bindTooltip('<div id="customTooltip-' + feature.properties.id + '">$' + feature.properties.price + '</div>', {
                            direction: 'top'
                            , permanent: true
                            , opacity: 1
                            , interactive: true
                            , className: 'map-custom-tooltip'
                        });
                    }
                }
                markersGroup.push(layer);
            }

            function pointToLayer(feature, latlng) {
                if (settings.useTextIcon) {
                    var markerOpacity = 0
                } else {
                    var markerOpacity = 1
                }

                return L.marker(latlng, {
                    icon: defaultIcon
                    , id: feature.properties.id
                    , opacity: markerOpacity
                });
            }

            function highlightMarker(e) {
                highlight(e.target);
            };

            function resetMarker(e) {
                reset(e.target);
            };

            // this function triggers after clicking on a marker
            function scrollToListing(e) {
                var listing_code = e.target.defaultOptions.id;
                var toFocusId = '#listing-banner-image-for-' + listing_code; // this is the id of the link where hovering gives an animation

                if ($("#" + listing_code).length > 0) { // this if condition checks if "#"+listing_code this id exists in the view. We've done this to avoid a console error in single listing page
                    // to focust the listing
                    $('html, body').animate({
                        scrollTop: $('#' + listing_code).offset().top - 110
                    }, 'slow', function() {
                        $(toFocusId).addClass('focus-a-listing');
                        setTimeout(function() {
                            $(toFocusId).removeClass('focus-a-listing');
                        }, 300);
                    });
                }
            }

            function highlight(marker) {
                marker.setIcon(highlightIcon);
                if (settings.useTextIcon) {
                    findTooltip(marker).addClass('active');
                }
            }
            function reset(marker) {
                marker.setIcon(defaultIcon);
                if (settings.useTextIcon) {
                    findTooltip(marker).removeClass('active');
                }
            }
            function findTooltip(marker) {
                var tooltip = marker.getTooltip()
                var id = $(tooltip._content).filter("div").attr("id")
                return $('#' + id).parents('.leaflet-tooltip')
            }
            function getPopupContent(properties) {
                if (properties.name) {
                    var title = '<h6><a href="' + properties.link + '">' + properties.name + '</a></h6>'
                } else {
                    title = ''
                }
                if (properties.about) {
                    var about = '<p class="">' + properties.about + '</p>'
                } else {
                    about = ''
                }
                if (properties.image) {
                    var imageClass = 'image';
                    if (settings.mapPopupType == 'venue') {
                        imageClass += ' d-none d-md-block'
                    }
                    var image = '<div class="' + imageClass + '"style="height: 150px; background-image: url(\'' + settings.imgBasePath + properties.image + '\'); background-size: cover; background-position: center;"></div>';
                } else {
                    image = '<div class="image"></div>'
                }
                if (properties.address) {
                    var address = '<p class="text-muted"><i class="fas fa-map-marker-alt fa-fw text-dark mr-2"></i>' + properties.address + '</p>'
                } else {
                    address = ''
                }
                if (properties.email) {
                    var email = '<p class="text-muted"><i class="fas fa-envelope-open fa-fw text-dark mr-2"></i><a href="mailto:' + properties.email + '" class="text-muted">' + properties.email + '</a></p>'
                } else {
                    email = ''
                }
                if (properties.phone) {
                    var phone = '<p class="text-muted"><i class="fa fa-phone fa-fw text-dark mr-2"></i>' + properties.phone + '</p>'
                } else {
                    phone = ''
                }
                if (properties.stars) {
                    var stars = '<div class="text-xs">'
                    for (var step = 1; step <= 5; step++) {
                        if (step <= properties.stars) {
                            stars += "<i class='fas fa-star text-warning'></i>"
                        } else {
                            stars += "<i class='fas fa-star' style = 'color: #BDBDBD'></i>"
                        }
                    }
                    stars += "</div>"
                } else {
                    stars = ''
                }

                if (properties.url) {
                    var url = '<a href="' + properties.url + '">' + properties.url + '</a><br>'
                } else {
                    url = ''
                }

                var popupContent = '';
                if (settings.mapPopupType == 'venue') {
                    popupContent =
                        '<div class="popup-venue card">' +
                        '<div style = "overflow: hidden; height: 150px; background-image: url(\'' + settings.imgBasePath + properties.image + '\'); background-size: cover; background-position: center;" class="map-direction-image">' + '</div>' +
                        '<div class="card-body">' +
                        '<div class="">' + title + ' </div>' +
                        '<div>' + address + '</div>' +
                        '<div>' + phone + '</div>' +
                        '</div>' +
                        '</div>';


                } else if (settings.mapPopupType == 'rental') {
                    popupContent =
                        '<div class="popup-rental">' +
                        image +
                        '<div class="text">' +
                        title +
                        stars +
                        '</div>' +
                        '</div>';
                }


                return popupContent;
            }
            /*
            ====================================================
              Highlight marker when users hovers above
              corresponding .card in the listing
            ====================================================
            */
            L.Map.include({
                getMarkerById: function(id) {
                    var marker = null;
                    this.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            if (layer.options.id === id) {
                                marker = layer;
                            }
                        }
                    });
                    return marker;
                }
            });

            var previousHighLightedMarkerId;
            $('[button-direction-id!=""][button-direction-id]').on('click', function() {
                var markerId = $(this).attr('button-direction-id');
                var marker = map.getMarkerById(markerId);
                if (marker) {
                    marker.openPopup(); // this line of code opens up the pop on map
                    highlight(marker);
                    if (previousHighLightedMarkerId != markerId) {
                        var marker = map.getMarkerById(previousHighLightedMarkerId);
                        if (marker) {
                            reset(marker);
                        }
                    }
                    previousHighLightedMarkerId = markerId;
                }
            });
        }
    </script>
    <script>
        function addToWishList(elem,id) {
		var user_login   = $('#user_id').val();

		if (user_login >0) {
			$.ajax({
				type : 'POST',
				url: baseUrl+'/add-to-wishlist/'+id,
				data: {"_token": "{{ csrf_token() }}",user_login : user_login, id:id },
				success : function(response) {
					if (response.msg == 'Added to wishlist') {
						$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('Added to wishlist');
					}else if(response.msg == 'This item is already in wishlist'){
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('This item is already in wishlist');
					}else{
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('Removed from the wishlist');
					}
				}
			});
		}else if(user_login ==0){
			toastr.error('Please login first');
		}
	}
    </script>
    <script>
        function showToggle(elem, selector) {
        $('.' + selector).slideToggle();
        console.log($(elem).text());
        if ($(elem).text() === "Show more") {
            $(elem).text('Show less');
        } else {
            $(elem).text('Show more');
        }
        }
        $('.pro-pag-5-slider').owlCarousel({
            itemElement: 'LI'
            , stageElement: 'UL'
            , items: '3'
            , nav: true
            , dots: false
            , pagination: false
        , })
    </script>
    <script>
        createListingsMap({
            mapId: 'map'
            , jsonFile: 'https://demo.creativeitem.com/atlas/assets/frontend/single-listing-geojson/listing-id-6.json'
        });
    </script>

    <style>
        .modal-content {
            background-color: #fff;
        }
        div#allgalleryimages_model.listing_detail_gallery .thumbnail img {
            max-height: 220px;
        }
        .strip .wrapper p {
            margin-bottom: 0px;
        }
        #sploffer .strip ul {
            margin: 0;
            padding: 5px 17px;
            height: 35px;
        }
        .p-discount {
            background-color: #e30514;
            font-size: 13px;
            color: #fff;
            padding: 0px 5px 0px 8px;
            transform: rotate(-35deg);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 20% 50%);
            margin: 6px;
        }

        .secondary_nav ul li:last-child {
            display: none;
        }

        #sploffer .item {
            margin: 0 5px;
        }

        .score {
            display: none;
        }

        .old-price {
            text-decoration: line-through;
            color: dimgray;
            font-size: 12px;
            font-weight: 160;
        }

        div#sploffer .strip .wrapper {
            padding: 10px 10px 70px 20px;
        }

        .offer_item.owl-item {
            width: 160px !important;
        }

        div#allgalleryimages_model .modal-header {
            background: #1f3863;
        }

        div#allgalleryimages_model .modal-header h5.modal-title {
            color: #fff;
        }

        div#allgalleryimages_model .modal-header button.close {
            color: #fff;
        }

        div#allgalleryimages_model.listing_detail_gallery .thumbnail {
            position: relative;
            display: inline-flex;
            width: 100%;
            height: 100%;
            margin: 0 0 20px 0;
            max-width: 100%;
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
            margin: 0 0 0 10px !important;
        }

        .owl-next {
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
            margin: 0 10 0 0px !important;
        }

        .owl-prev i,
        .owl-next i {
            font-size: 25px;
        }

        .owl-theme .owl-controls .owl-buttons div {
            background: #0a071e;
        }

        .owl-controls.clickable {
            margin: 0;
        }

        /* div#offergalimg.owl-carousel .owl-item img {
        height: 100%;
        object-fit: unset;
    } */
        #offergalimg .item {
            margin: 0 5px;
        }

        .offer-detail-title {
            margin-top: 30px;
        }
        @media (min-width: 1700px) and (max-width: 2600px) {
            .container{
                max-width: 1600px;
            }
        }

    </style>
</main>

@endsection
