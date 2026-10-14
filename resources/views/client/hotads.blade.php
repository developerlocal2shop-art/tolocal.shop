@extends('layouts.client.blog')
@section('title','Hot Ads| Details ')
@section('content')

<?php 
    $defaultCurrency = Helper::defaultCurrencyData();
    if(session()->has('currency_code')){
      $currencySymbol = session()->get('currency_code')['currsymbol'];
      $currencycode = session()->get('currency_code')['currcode'];
    }else{
      $currencySymbol = $defaultCurrency[0]->symbol;
      $currencycode = $defaultCurrency[0]->code;
    }
    if (isset($data['adevertiseData'])) {
        $hotadsData =  $data['adevertiseData'] ;
    }
    $hotadsdetails = $data['hotads'];
?>

<!-- Main page -->
<main>
    <div class="common_banner">
        @if(!empty($hotadsdetails))
            <div class="hero_in shop_detail" >
            <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$hotadsdetails->image_name }}" alt="">
            </div>
        @else
            <div class="hero_in shop_detail" >
            <img src="{{ asset('assets/images/no-img.jpg') }}" alt="">
            </div>
        @endif
   </div>

   <nav class="hotads_nav sticky_horizontal_2">
        <div class="container">
            <ul class="clearfix" >
                <li ><a href="{{ route('pages') }}" >Home</a></li>
                <li style="color: #fff !important;">Hot Ads</li>
            </ul>
        </div>
    </nav>

    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-8">
                <section>
                    <div class="detail_title_1">
                        <h1>{{ $hotadsdetails->heading }}</h1>
                    </div>
                    @if (!empty($hotadsdetails->mid_text))
                    <h5>{{__("message.description_title")}}</h5>
                    <p class="text-justify">{{ $hotadsdetails->mid_text }}</p>
                    @endif
                    @if (!empty($hotadsdetails->bottom_text))
                    <h5>Content</h5>
                    <p class="text-justify">{{ $hotadsdetails->bottom_text }}</p>
                    @endif
                    @if(!empty($data['hotadsData'][0]->directory->seo_url))
                        <div class="hotads_comp">
                            <a target="_blank" class="btn btn-warning" href="{{ route('listing.details',$data['hotadsData'][0]->directory->seo_url) }}">View Company </a>
                        </div>

                     @endif
                </section>
                <!-- /section -->
            </div>

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->

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
        });

    </script>

    <!-- This map-category.php file has all the fucntions for showing the map, marker, map info and all the popup markups -->
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
                        // Removing the class after seconds
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
            , items: '4'
            , nav: true
            , dots: false
            , pagination: false
        , })
   
        createListingsMap({
            mapId: 'map'
            , jsonFile: 'https://demo.creativeitem.com/atlas/assets/frontend/single-listing-geojson/listing-id-6.json'
        });

    </script>

    
    <style>
        .dropcaps:first-child::first-letter {
            color: #fff;
            background-color: #004dda;
            float: left;
            font-size: 64px;
            font-size: 4rem;
            line-height: 1;
            margin: 10px 15px 0 0 !important;
            padding: 12px;
        }
        .hotads_nav ul li {
        display: inline-block;
        margin-right: 20px;
        font-weight: 500;
        font-size: 16px;
        font-size: 1rem;
        }
        .hotads_nav ul {
            margin-bottom: 0;
        }

        .hotads_nav {
            background: #2f4a7c;
            padding: 15px 0;
        }
        .hotads_nav ul li a:hover {
            color: #fff;
            opacity: 1;
        }
        .hotads_nav ul li a {
            color: rgba(255, 255, 255, 0.5);
        }
        .hotads_comp{
            border-bottom: 3px solid #d2d8dd;
            margin-bottom: 30px;
        }
    </style>
</main>


@endsection
