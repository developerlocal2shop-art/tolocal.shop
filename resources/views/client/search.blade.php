@extends('layouts.client.blog')
@section('title','Search')
@section('content')
<?php

$wislistdirectoryId=array();
foreach($wishlists as $wishid){
	$wislistdirectoryId[]=$wishid->directory_id;
}

$search_string = request()->search_string;
if((request()->cat)>0){
	$cat = request()->cat;
}else{
	$cat = "";
}

if((request()->tags)>0){
	$tags = request()->tags;
}else{
	$tags = "";
}

if((request()->subcat)>0){
	$subcat = request()->subcat;
}else{
	$subcat = "";
}

if((request()->filters)){
    $filters = request()->filters;
}else{
    $filters = "";
}

$currenturl = url()->current();
$currenturl = explode("/",$currenturl);
$arrendvalue = end($currenturl);
?>
<style>
	a#tags-toggle-btn {
		float: right;
		margin: -32px 10px;
	}
	.nice-select.wide2 {
		display: none;
	}
	.select2-container {
		display: table-cell;
	}
	.select2-container--default .select2-selection--single {
		height: 40px;
	}
	.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 39px;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 38px;
	}
	.timedrop .nice-select .list {
		height: 16vh !important;
	}
	.select_city_home .select2-container--default .select2-selection--single {
		background-color: #e0e0e0 !important;
	}
	#accordion.tree-stuc .card-header a {
		padding: 5px 25px;
	}
	.acc_shop_filter #accordion.tree-stuc .card-header {
		padding: 3px 15px;
	}
	span#LparentCategory {
		padding: 5px 25px;
	}
	#accordion.tree-stuc .area {
		border-left: 0px solid #203864;
	}
	.acc_shop_filter #accordion.tree-stuc .ponto h5 span label {
    margin-bottom: 5px;
}

img.direction {
    float: right; margin: -20px 0px; width: auto;
}
</style>

<div class="sub_header_in sticky_header">
    <div class="container-fluid">
        <h1>{{__("message.search_result_text")}} @if($_GET['city_name']??'') {{ $_GET['city_name']??'' }} @endif</h1>
    </div>
</div>
<!-- /Map -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35">
    <div class="row justify-content-md-center">

		<x-Clientfilter :directoryData="$pDirectorySubCatData" />
        <div class="col-xl-5 col-md-12 order-xl-1 order-2" id="listings">

            <div class="row">
                <?php
			    if(count($data['directoryDetails'])!=0){
				foreach($data['directoryDetails'] as $row){
                    $details_text = Helper::getDirectorySeoSlug($row->id);
					$details_text = isset($details_text->slug)?$details_text->slug:'';

				?>
                <!-- A Single Listing Starts-->
                <div class="col-lg-6 col-md-6 listing-div " data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
                    <div class="strip grid featured-tag-border">
                        <figure>

                            <input class="product-wishlist" type="hidden" value="{{ $row->id }}" name="product_id" id="product_id">
                            <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                            <a href="javascript:void(0);" productid="{{ $row->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $row->id ?>)">
                                <?php
									if(!in_array($row->id, $wislistdirectoryId)){ ?>
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                <?php }else{ ?>
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                <?php } ?>
                            </a>
                            <?php $imagename = Helper::getDirectoryimage($row->id); ?>
                            <span class="featured-tag-grid">
                            @if($row->feature_type == 'superfeatured')
									Super featured
								@else
									Featured
								@endif
                            </span>
                            <a href="<?php echo url('listings/details', ['id' => $row->slug??'']); ?>" class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg') }}')" <?php } ?>>

                                <div class="read_more"><span>{{__("message.watch_details_text")}}</span></div>
                            </a>
                            {{-- <small>{{__("message.hotel_text")}}</small> --}}
                        </figure>
                        <div class="wrapper featured-body">
                            <h3 class="ellipsis">
                            <div class="notranslate"><a href="<?php echo url('listings/details', ['id' => $row->slug]); ?>">{{ $row->title}}</a></div>
                            </h3>
                            <small class="notranslate">
                                @php
                                $directoryAddress = Helper::getDirectoryLocation($row->id);

                                //echo isset($directoryAddress[0]->address)?$directoryAddress[0]->address:"";
                                @endphp
                                {{ substr(strip_tags(isset($directoryAddress[0]->address)?$directoryAddress[0]->address:""),0,30) }}
							   {{ isset($directoryAddress[0]->address)?'..':"" }}
                            </small>
                            <p class="ellipsis">{{ $row->description }}</p>
                            <?php
								$latitude 	= isset($directoryAddress[0]->latitude)?$directoryAddress[0]->latitude:"";
								$longitude 	= isset($directoryAddress[0]->longitude)?$directoryAddress[0]->longitude:"";
                                $address 	= isset($directoryAddress[0]->address)?$directoryAddress[0]->address:"";

                                if($latitude>0 && $longitude>0){
							?>
                            <a target="_blank" href="https://www.google.com/maps/dir//<?php echo $latitude; ?>,<?php echo $longitude; ?>/@<?php echo $latitude; ?>,<?php echo $longitude; ?>,16z">
								<img class="direction" src="{{ url('/') }}/assets/client/images/direction.jpg" width="10%" alt="">
							</a>
                            <a class="address" href="javascript:" lati="{{ $directoryAddress[0]->latitude?? '' }}" longi="{{ $directoryAddress[0]->longitude?? '' }}" target="">{{__("message.show_on_map_text")}}</a>
                            <?php } ?>
                        </div>

                        <ul class="featured-footer mb-0">
                            <li><span class="loc_closed">{{__("message.closed_text")}} at {{ $row->close_time }}</span></li>
                            <li>
                                <div class="score">
                                    <span>{{__("message.awesome_text")}}<em>{{__("message.reviews_text")}}</em></span>
                                    <strong>5.0</strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php  } }else{ ?>
                <h2>{{__("message.no_record_found_text")}}</h2>
                <?php } ?>
            </div>
        </div>
        <!-- /col -->
        <div class="col-xl-3 order-xl-2 order-1">
            <div class="stiky-map mb-5 mb-xl-0">
                <div id="map" class="map-full map-layout"></div>
				<div id="marker-tooltip"></div>
            </div>
        </div>
    </div>
</div>
<!-- /container -->
<script type="text/javascript">
    $(document).ready(function() {

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

    $("#loader").fadeOut("slow");

    function addToWishList(elem, id) {
        var user_login = $('#user_id').val();
        $('.AJloader').show();
        if (user_login > 0) {
            $.ajax({
                type: 'POST'
                , url: baseUrl + '/add-to-wishlist-directory/' + id
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
                    $('.AJloader').hide();
                }
            });
        } else if (user_login == 0) {
            toastr.error('{{__("message.please_login_first_text")}}');
            window.setTimeout(function() {
                $('.AJloader').hide();
                window.location.href = "{{ url('login') }}";
            }, 3000);

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







    $(document).ready(function() {

		 $('#sploffer').owlCarousel({
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

    $("#loader").fadeOut("slow");
	function addToWishList(elem,id) {
		var user_login   = $('#user_id').val();
		$('.AJloader').show();
		if (user_login >0) {
			$.ajax({
				type : 'POST',
				url: baseUrl+'/add-to-wishlist-directory/'+id,
				data: {"_token": "{{ csrf_token() }}",user_login : user_login, id:id },
				success : function(response) {
					if (response.msg == 'Added to wishlist') {
						$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('{{__("message.added_to_wishlist_text")}}');
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

	function showToggle(elem, selector) {
		$('.'+selector).slideToggle();
		console.log($(elem).text());
		if($(elem).text() === "Show more")
    {
        $(elem).text('Show less');
    }
    else
    {
        $(elem).text('Show more');
    }
	}

	function initialize() {
		var locations = [

			<?php
            //dd($data['directoryDetails']);
			if(count($data['directoryDetails'])!=0){
			$mi = 1;
			$latitude = [];
			$longitude = [];
			foreach($data['directoryDetails'] as $row){
				$directoryAddress = Helper::getDirectoryLocation($row->id);

					$latitude = isset($directoryAddress[0]->latitude)?$directoryAddress[0]->latitude:"";
					$longitude = isset($directoryAddress[0]->longitude)?$directoryAddress[0]->longitude:"";
					$address = isset($directoryAddress[0]->address)?$directoryAddress[0]->address:"";
					$title = $row->title;
					$seo_url = $row->slug;
					$distance = round($row->distance);
			?>
			['<a target="_blank" href="{{ url("listings/details/$seo_url") }}"> <b style="text-transform: uppercase; color:#ffc108;">{{ $title }}</b><br> <p style="font-style: italic;">Distance - {{ $distance }}km</p></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],

			<?php
			$mi++;
			$newLatitude = $getLatLog[0];
			$newLongitude = $getLatLog[1];
			?>
			['<a href="javascript:void();"> <b style="text-transform: uppercase; color:#ffc108;">My Location</b> </a>', {{ $newLatitude }}, {{ $newLongitude }}, {{ $mi }}],

			<?php
			$mi++;
			}}else{
            ?>
            <?php
                $mi = 1;
                $latitude = session('latitude_city');
                $longitude = session('logitude_city');
                ?>
               ['<a href="javascript:void();"><b style="text-transform: uppercase; color:#ffc108;">My Location</b></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],
			<?php
			$mi++;
            }
			?>
		];

		window.map = new google.maps.Map(document.getElementById('map'), {
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var infowindow = new google.maps.InfoWindow();
		var bounds = new google.maps.LatLngBounds();

		//for (i = 0; i < locations.length; i++) {
			$.each(locations, function(i, value) {

				var wordToFind = "My Location";
				var regex = new RegExp('\\b' + wordToFind + '\\b', 'i');

				marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					map: map
				});

				if (regex.test(value[0])) {
					// Set the background color you want
					var backgroundColor = "#FF0000"; // Replace with your desired color code
					// Create a new marker icon with the specified background color
					var newIcon = createMarkerIcon(backgroundColor);
					// Set the new icon for the marker
					marker.setIcon(newIcon);
					// Function to create a marker icon with a given background color
					function createMarkerIcon(backgroundColor) {
						return {
							path: google.maps.SymbolPath.CIRCLE,
							fillColor: backgroundColor,
							fillOpacity: 1,
							strokeWeight: 0,
							scale: 10 // Adjust the scale as needed
						};
					}
		     	}

			bounds.extend(marker.position);
			google.maps.event.addListener(marker, 'click', (function (marker, i) {
				return function () {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		});

		map.fitBounds(bounds);
		var listener = google.maps.event.addListener(map, "idle", function () {
			map.setZoom(4);
			google.maps.event.removeListener(listener);
		});
	}

	function loadScript() {
		if (!window.CookieConsent || !window.CookieConsent.has('functional')) {
			return;
		}
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&v=3.exp&sensor=false&callback=initialize';
		document.body.appendChild(script);
	}
	document.addEventListener('cookie-consent:category-enabled', function (event) {
		if (event.detail.category === 'functional') {
			loadScript();
		}
	});
	if (window.CookieConsent && window.CookieConsent.has('functional')) {
		loadScript();
	}


	$(document).ready(function() {

	   $('.address').on('click', function() {

		   var latival = $(this).attr('lati');
		   var longival = $(this).attr('longi');
           var addrval = $(this).attr('addr');
           var titledirval = $(this).attr('titledir');

			console.log("Lati: "+latival) ;
			console.log("Longi: "+longival) ;
            console.log("Addr: "+addrval) ;
            console.log("Title: "+titledirval) ;
			$("#mapiframe1").remove();
			$("#mapiframe2").remove();

            var map = new google.maps.Map(document.getElementById("map"), {
			zoom: 16,
			center: new google.maps.LatLng(latival, longival),
			mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			var marker = new google.maps.Marker({
			position: new google.maps.LatLng(latival, longival),
			map: map,
			label: {
				text: titledirval,
				color: '#32a067',
				className: 'marker-label',
			}
			});

			marker.tooltipContent = addrval;
			google.maps.event.addListener(marker, 'mouseover', function () {
				var point = fromLatLngToPoint(marker.getPosition(), map);
				$('#marker-tooltip').html(marker.tooltipContent).css({
					'center': point.x,
					'top': point.y
				}).show();
			});

			google.maps.event.addListener(marker, 'mouseout', function () {
				$('#marker-tooltip').hide();
			});

			function fromLatLngToPoint(latLng, map) {
				var topRight = map.getProjection().fromLatLngToPoint(map.getBounds().getNorthEast());
				var bottomLeft = map.getProjection().fromLatLngToPoint(map.getBounds().getSouthWest());
				var scale = Math.pow(2, map.getZoom());
				var worldPoint = map.getProjection().fromLatLngToPoint(latLng);
				return new google.maps.Point((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
			}
	    });

   });



    $(document).ready(function() {

        //$("#map").append('<iframe id="mapiframe1" src="https://maps.google.com/maps?q=52.36757340,4.90413890&hl=es;z=14&amp;output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>');
        //$('.address').on('click', function() {
           // var latival = $(this).attr('lati');
            //var longival = $(this).attr('longi');
            //console.log("Lati: " + latival);
            //console.log("Longi: " + longival);
            //$("#mapiframe1").remove();
            //$("#mapiframe2").remove();
            //$("#map").append('<iframe id="mapiframe2" src="https://maps.google.com/maps?q=' + latival + ',' + longival + '&hl=es;z=14&amp;output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>');
        //});

    });

</script>

</main>
@endsection
