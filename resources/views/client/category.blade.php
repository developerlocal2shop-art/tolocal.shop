@extends('layouts.client.blog')
@section('title','Category')
@section('content')

<div class="sub_header_in sticky_header">
	<div class="container">
		<h1>{{__("message.menu_text_listing")}}{{ " - ".$categoryName }}</h1>
	</div>
</div>
<?php
$wislistdirectoryId=array();
foreach($wishlists as $wishid){
	$wislistdirectoryId[]=$wishid->directory_id??'';
}
$search_string = request()->search_string;
if((request()->cat)>0){
	$cat = request()->cat;
}else{
	$cat = "";
}
?>

<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35 bluefilter">
	<div class="row justify-content-md-center">
		<!-- /aside -->
		<x-Clientfilter :directoryData="$pDirectorySubCatData" />
		<!-- /aside -->
		<div class="col-xl-5 col-md-12 order-xl-1 order-2" id="listings">
			<div class="row">
				<?php

				if(count($directoryDetails)!=0){
				foreach($directoryDetails as $row){
					// $details_text = Helper::getSEO_URL($row->id);
					// $details_text = isset($details_text->seo_url)?$details_text->seo_url:'';
					$details_text = Helper::getDirectorySeoSlug($row->id);
					$details_text = isset($details_text->slug)?$details_text->slug:'';
					?>
				<div class="col-lg-6 col-md-6 listing-div " data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id = "9eb131c8e0f5e5e3796606e6246b76e0">

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
							<!-- <a href="<?php echo url('listings/details', ['id' => $details_text]); ?>"  class="d-block h-100 img" style="background-image:url('{{asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg')}}')">
								<div class="read_more"><span>{{__("message.watch_details_text")}}</span></div>
								</a> -->
							<a href="<?php echo url('listings/details', ['id' => $details_text]); ?>"  class="d-block h-100 img"

							<?php if(!empty($imagename)){ ?>
								style="background-image:url('{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}')"
							<?php }else{ ?>
								style="background-image:url('{{ asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg') }}')"
							<?php } ?>
							>
							<div class="read_more"><span>{{__("message.watch_details_text")}} </span></div>
							</a>
						</figure>
						<div class="wrapper featured-body">
							<h3 class="ellipsis">
								<a href="<?php echo url('listings/details', ['id' => $details_text]); ?>">{{ $row->dtr_title}}</a>
							</h3>
							<small>
							@php
								$directoryAddress = Helper::getDirectoryLocation($row->id);
							@endphp
							{{ substr(strip_tags(isset($directoryAddress[0]->address)?$directoryAddress[0]->address:""),0,30) }}
							{{ isset($directoryAddress[0]->address)?'..':"" }}
							</small>
							<p class="ellipsis">{{ substr(strip_tags($row->dtr_description),0,50) }}{{ isset($row->dtr_description)?'..':"" }}</p>
							<?php
								$latitude = isset($directoryAddress[0]->latitude)?$directoryAddress[0]->latitude:"";
								$longitude = isset($directoryAddress[0]->longitude)?$directoryAddress[0]->longitude:"";
                                $address = isset($directoryAddress[0]->address)?$directoryAddress[0]->address:"";
                                $title = $row->dtr_title;
								if($latitude>0 && $longitude>0){
							?>
							<a class="address" id="listingmap_<?php echo $row->id; ?>" dirid="<?php echo $row->id; ?>" href="javascript:" lati="<?php echo $latitude; ?>" addr="<?php echo $address; ?>" titledir="<?php echo $title; ?>" longi="<?php echo $longitude; ?>" target="">Show on map</a>
							<?php } ?>
						</div>
						<ul class="featured-footer mb-0">
							<li><span class="loc_closed">{{__("message.closed_text")}} at {{ $row->close_time }}</span></li>
							<li>
								<div class="score">
									<span>Awesome<em>2 Reviews</em></span>
									<strong>5.0</strong></div>
							</li>
						</ul>
					</div>
				</div>
				<?php  } }else{ ?>
					<h2>{{__("message.no_record_found_text")}}</h2>
				<?php } ?>
			</div>
			<!-- custom pagination -->
			<div class="d-flex justify-content-center">
				{{$directoryDetails->links('pagination::bootstrap-4')}}
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
		if (deviceWidth >= 1300 && deviceWidth <= 1550) {
			$('#sidebar').removeClass('col-xl-2');
			$('#sidebar').addClass('col-xl-3');
			$('#listings').removeClass('col-xl-5');
			$('#listings').addClass('col-xl-6');
		}
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
</script>
<script>
    var locations = [
		<?php
		if(count($directoryDetails)!=0){
		$mi = 1;
		$latitude = [];
		$longitude = [];
		foreach($directoryDetails as $row){
			$directoryAddress = Helper::getDirectoryLocation($row->id);
				$latitude = isset($directoryAddress[0]->latitude)?$directoryAddress[0]->latitude:"";
				$longitude = isset($directoryAddress[0]->longitude)?$directoryAddress[0]->longitude:"";
				$address = isset($directoryAddress[0]->address)?$directoryAddress[0]->address:"";
				$title = $row->dtr_title;
				$seo_url = $row->slug;
		?>
        // ['{{ $title }}', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],
		['<a target="_blank" href="{{ url("listings/details/$seo_url") }}">{{ $title }}</a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],
		<?php
		$mi++;
		 }}

		 ?>
    ];

    function initMap() {
		<?php if(count($directoryDetails)!=0){ ?>

		var selectedPositionlatlong = new google.maps.LatLng({{ $getLatLog[0] }},{{ $getLatLog[1] }});// madhulika for focus on the right map based on the selected location
        var myLatLng = {lat: {{ $latitude }}, lng: {{ $longitude }}};
		<?php }else{ ?>
	    var myLatLng = {lat: '37.0902', lng: '95.7129'};
		<?php } ?>

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 3,
            center: selectedPositionlatlong
            });
        var count=0;

        for (count = 0; count < locations.length; count++) {

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[count][1], locations[count][2]),
                map: map
                });
            marker.info = new google.maps.InfoWindow({
                content: locations [count][0]
                });
            google.maps.event.addListener(marker, 'click', function() {
                var marker_map = this.getMap();
                this.info.open(marker_map, this);
                 });
        }
    }
</script>

<script data-consent-category="functional" data-consent-src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&amp;callback=initMap"></script>

<script>
	window.mobileCheck = function() {
		let check = false;
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
		return check;
	};
	$(document).ready(function() {
	   $('.address').on('click', function() {

		   var latival 			= $(this).attr('lati');
		   var longival 		= $(this).attr('longi');
           var addrval 			= $(this).attr('addr');
           var titledirval 		= $(this).attr('titledir');
		   var mapDirectoryId 	= $(this).attr('id');
		   var dirId 			= $(this).attr('dirid');

		   var mapdivCount = $('.mobilemapview').length;
		   //console.log("mapdivCount: "+mapdivCount) ;
			if(mobileCheck()){
				if(mapdivCount==0){
					$(`<div class="stiky-map mb-5 mb-xl-0 mobilemapview">
						<div id="map`+dirId+`" class="map-full map-layout"></div>
						<div id="marker-tooltip"></div>
					</div>`).insertAfter("#"+mapDirectoryId);
				}else{
					$(".mobilemapview").remove();
					$(`<div class="stiky-map mb-5 mb-xl-0 mobilemapview">
						<div id="map`+dirId+`" class="map-full map-layout"></div>
						<div id="marker-tooltip"></div>
					</div>`).insertAfter("#"+mapDirectoryId);
				}
				var mapidshow = "map"+dirId;
			}else{
				var mapidshow = "map";
			}

			console.log("Lati: "+latival) ;
			console.log("Longi: "+longival) ;
            console.log("Addr: "+addrval) ;
            console.log("Title: "+titledirval) ;
			$("#mapiframe1").remove();
			$("#mapiframe2").remove();

            var map = new google.maps.Map(document.getElementById(mapidshow), {
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
</script>
<style>
    .listing-div .strip .wrapper {
        padding: 10px 10px 70px 20px;
    }
</style>
</main>
@endsection
