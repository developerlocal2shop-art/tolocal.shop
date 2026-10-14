@extends('layouts.client.blog')
@section('title','Listings map')
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
?>

<!-- /Map -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<div class="container-fluid margin_60_35">
	<div class="row justify-content-md-center">
		<!-- /col -->
		<div class="col-xl-12 order-xl-2 order-1">
			<div class="stiky-map mb-5 mb-xl-0"><br><br>
				<div id="map" class="map-full map-layout"></div>
				<div id="marker-tooltip"></div>
			</div>
		</div>
	</div>
</div>
<!-- /container -->

<script data-consent-category="functional" data-consent-src="https://maps.googleapis.com/maps/api/js?libraries=geometry&amp;sensor=false"></script>
<script data-consent-category="functional" data-consent-src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&amp;callback=initialize"></script>

<script type="text/javascript">
    $("#loader").fadeOut("slow");
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
			if(count($directoryDetails)!=0){
			$mi = 1;
			$latitude = [];
			$longitude = [];
			foreach($directoryDetails as $row){
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
			$newLatitude = $latitude_session;
			$newLongitude = $longitude_session;
			?>
			['<a href="javascript:void();"> <b style="text-transform: uppercase; color:#ffc108;">My Location</b> </a>', {{ $newLatitude }}, {{ $newLongitude }}, {{ $mi }}],

			<?php
			$mi++;
			}}else{
			?>
			<?php
				$mi = 1;
				$latitude = $latitude_session;
				$longitude = $longitude_session;
				?>
				['<a href="javascript:void();"><b style="text-transform: uppercase; color:#ffc108;">My Location</b></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}
				],
			<?php
			$mi++;
			}
			?>
		];

		window.map = new google.maps.Map(document.getElementById('map'), {
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var infowindow = new google.maps.InfoWindow();
		var bounds     = new google.maps.LatLngBounds();

		//for (i = 0; i < locations.length; i++) {
			$.each(locations, function(i, value) {

				var wordToFind = "My Location";
				var regex = new RegExp('\\b' + wordToFind + '\\b', 'i');

				marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					map: map
				});

				if (regex.test(value[0])) {
					var backgroundColor = "#FF0000";
					var newIcon = createMarkerIcon(backgroundColor);
					// Set the new icon for the marker
					marker.setIcon(newIcon);
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
			google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
				return function () {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		});

		var selectedPositionlatlong = new google.maps.LatLng({{ $latitude_session }},{{ $longitude_session }});// madhulika for focus on the right map based on the selected location
		map.fitBounds(bounds);
		var listener = google.maps.event.addListener(map, "idle", function () {
			map.setZoom(10);
			map.setCenter(selectedPositionlatlong);
			google.maps.event.removeListener(listener);
		});
	}

	function addMarker(location) {
	  var marker = new google.maps.Marker({
		title: 'User added marker',

		icon: {
		  path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
		  scale: 5
		},
		position: location,
		map: map
	  });

	}

	function find_closest_marker(event) {
	  var distances = [];
	  var closest = -1;
	  for (i = 0; i < markers.length; i++) {
		var d = google.maps.geometry.spherical.computeDistanceBetween(markers[i].position, event.latLng);
		distances[i] = d;
		if (closest == -1 || d < distances[closest]) {
		  closest = i;
		}
	  }
	  alert('Closest marker is: ' + markers[closest].getTitle());
	}
	initialize();
 </script>



<style type="text/css">

        .marker-label {
			background-color: white;
			font-family: "Lucida Grande", "Arial", sans-serif;
			font-size: 1.6em !important;
			font-weight: bold;
			text-align: center;
			width: 12em;
			border: 1px solid black;
			white-space: normal;
			margin: 0px 0px 50px 0px !important;
			padding: 5px;
		}


		#marker-tooltip {
			display: none;
			position: absolute;
			width: 260px;
			font-weight: 600;
			border: 1px solid black;
			background-color: #9cc0f9;
			white-space: normal;
			margin: 5px 0px 0px 25px !important;
			padding: 5px;
			color: #555;
			font-size: 0.8125rem;
		}
    </style>

</main>
@endsection
