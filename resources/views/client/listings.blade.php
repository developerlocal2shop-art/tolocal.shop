@extends('layouts.client.blog')
@section('title','Companies')
@section('content')

<?php
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
	if((request()->timeSelected)){
		$timeSelected = request()->timeSelected;
	}else{
		$timeSelected = "";
	}
	$currenturl = url()->current();
	$currenturl = explode("/",$currenturl);
	$arrendvalue = end($currenturl);

	$user_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
	if (filter_var($user_ip, FILTER_VALIDATE_IP)) {
		$geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);
		$timezone = ($user_ip == '127.0.0.1' || $user_ip == '::1') ? 'Asia/Kolkata' : ($geo['timezone'] ?? 'Asia/Kolkata');
		date_default_timezone_set($timezone);
		$info = getdate();
		$date = $info['mday'];
		$month = $info['mon'];
		$year = $info['year'];
		$hour = $info['hours'];
		$min = $info['minutes'];
		$sec = $info['seconds'];
		$openCloseTags = 0;
	}
?>
<style type="text/css">

	/* Card Container */
	.company_list_data .strip {
		background: #fff;
		border-radius: 14px;
		overflow: hidden;
		box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
		transition: all 0.3s ease;
		position: relative;
	}

	.company_list_data .strip:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
	}

	/* Image Section */
	.company_list_data figure {
		position: relative;
		height: 220px;
		margin: 0;
		overflow: hidden;
	}

	/* Wishlist Icon */
	.company_list_data .wishlist-icon {
		position: absolute;
		top: 12px;
		right: 12px;
		background: #fff;
		border-radius: 50%;
		width: 36px;
		height: 36px;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 2px 10px rgba(0,0,0,0.15);
		z-index: 2;
	}

	.company_list_data .wishlist-icon i {
		color: #ff4d4d;
		font-size: 16px;
	}

	/* Featured Tag */
	.company_list_data .featured-tag-grid {
		background: linear-gradient(45deg, #ff7e00, #ffb347);
		color: #fff;
		font-size: 12px;
		border-radius: 6px;
		font-weight: 500;
	}

	/* Content */
	.company_list_data .wrapper {
		padding: 15px;
	}

	.company_list_data h3 {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
	}

	.company_list_data h3 a {
		color: #222;
		text-decoration: none;
	}

	.company_list_data h3 a:hover {
		color: #ff7e00;
	}

	/* Address */
	.company_list_data small {
		display: block;
		font-size: 13px;
		color: #777;
		margin-bottom: 6px;
	}

	/* Description */
	.company_list_data p {
		font-size: 14px;
		color: #555;
		margin-bottom: 5px !important;
	}

	.company_list_data .address {
		font-size: 13px;
		color: #007bff;
		cursor: pointer;
	}

	.company_list_data .address:hover {
		text-decoration: underline;
	}

	/* Footer */
	.company_list_data .featured-footer {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 12px 15px;
		border-top: 1px solid #eee;
	}

	/* Open / Close Status */
	.company_list_data .loc_closed {
		font-size: 12px;
		padding: 5px 10px;
		border-radius: 20px;
		background: #f5f5f5;
		color: #999;
	}

	.company_list_data .loc_closed[style*="#3aaf47"] {
		background: #e9f7ef;
		color: #3aaf47 !important;
		border: none !important;
	}

	.company_list_data .score strong {
		background: #3aaf47;
		color: #fff;
		padding: 5px 8px;
		border-radius: 6px;
		font-size: 13px;
	}

	.company_list_data .score span {
		font-size: 12px;
		color: #666;
	}

	.company_list_data .score em {
		font-style: normal;
		display: block;
		font-size: 11px;
		color: #aaa;
	}

    @media (min-width: 1700px) and (max-width: 2600px) {
        div#product .strip figure a, div#sploffer a {
            display: initial !important;
        }
    }

	@media (max-width: 980px) {
        #tree_structure2 {
			margin: -25px 0px !important;
			width: 100% !important;
		}
    }
	.listing-div .strip .img, div#product.listing_related_product .strip .img {
		background-color: #f6f6f6;
	}
	.strip .wrapper h3 {
		min-height: 25px;
	}
	.strip .wrapper h3 a {
		line-height: 20px;
		color: #403f3f;
		font-size: 20px;
	}
	#accordion.tree-stuc .mb-0 > a {
		display: ruby !important;
		position: unset !important;
	}
	.accordion .collapse .card-body .card-header span.mb-0 a {
		padding: 6px 0px 5px 0!important;
	}
	.childCategory_div{ padding-left: 5px; }
	a.collapsed.childCategory {
		line-height: 13px;
	}
	.acc_shop_filter #accordion.tree-stuc .equipamento span label {float: none !important; padding: 3px; }
	.childCategory { margin: 0px 0px 0px 10px; }
	.childCategory_else { margin: 0px 0px 0px 10px;}

	.parentCategory span { margin: 0px -12px; }
	.select_city_home .select2-container--default .select2-selection--single {
		border-left: 0px solid #aaa !important;
	}
	.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered {
		background: #fff !important; border: 1px solid #d2d8dd;}
	a#tags-toggle-btn { float: right; margin: -32px 10px; }
	.nice-select.wide2 { display: none; }
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
	.acc_shop_filter #accordion.tree-stuc .card-header {
		padding: 3px 25px;
	}
	span#LparentCategory {
		padding: 5px 25px;
	}
	#accordion.tree-stuc .area {
		border-left: 0px solid #203864;
	}
	img.direction {
		float: right; margin: -20px -5px; width: 50px;
	}
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

	a#tags-toggle-btn {
		color: #2f4a7c;
	}
	.sub_header_in h1 {line-height: 35px; }

	#listing_hierarchical p a {
		color: #da3429;
	}
	div#listing_hierarchical {
		margin-top: -20px;
	}

	@media (max-width: 768px) {

		.accordion .collapse .card-body .card-header span.mb-0 {
			display: math;
		}
		.childCategory_div span label {
			margin: -7px -5px;
		}
		.strip {
			margin-bottom: 12px;
		}
		#submit_div {
			width: 30%;
		}
		#searchquerylisting_div {
			width: 70%;
		}
		div#listing_hierarchical {
			margin: 0px 10px;
			font-size: 13px;
		}
		aside#sidebar {
			padding: 0px 20px;
		}
		.stiky-map .map-full {
			height: 40vh;
		}
		.all_listing_cat{
           display: none;
		}
		.listing_filters{
           display: none;
		}
		.listing_time{
           display: none;
		}

		.custom-search-input-2.inner input {
			height: 30px;
			font-size: 12px;
		}
		.custom-search-input-2.inner i {
			height: 29px;
			line-height: 29px;
			font-size: 10px;
		}
		.custom-search-input-2.inner input[type='submit'] {
			height: 32px;
			line-height: 2px;
			font-size: 12px;
			margin: -1px 2px;
		}
		.bluefilter { padding-top: 5px!important; }

		#filters_col {
			padding: 5px 5px 5px 10px;
			margin-bottom: 5px;
		}
		.listing_div {
			margin: -30px 0px;
		}
		.d-flex.notranslate {
			height: 30px;
			line-height: 16px !important;
			margin-bottom: 5px !important;
		}
		.d-flex.notranslate p{
			font-size: 12px !important;
		}
		.sub_header_in.sticky_header {
			margin-top: 40px;
			padding-bottom: 0px;
		}
		select#distance {
			height: 30px;
			font-size: 12px;
			padding-right: 2px;
		}
		#distance_div {
		  width: 50%;
		}
		.select_city_home.col-md-4.notranslate {
			width: 50%;
			float: right !important;
		}
		.select_city_home .select2-container--default .select2-selection--single {
			background-color: #f8f8f8 !important;
		}
		.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 28px;
			font-size: 11px;
		}
			.listing_div .strip figure {
				max-height: 140px;
				height: 140px;
			}
			.strip .wrapper p {
				margin-bottom: 0px;
				font-size: 12px;
			}
			.featured-tag-grid {
				right: 12px;
				padding-left: 5px;
				padding-right: 5px;
				font-size: 10px;
				font-weight: 500;
				bottom: 5px;
			}
			.container_check {
				display: block;
				line-height: 15px;
			}
			.strip .wrapper small {
				font-weight: 400;
				margin-bottom: 2px;
			}
			#listing_row > .listing-div {
				-ms-flex: 0 0 50%;
				flex: 0 0 50%;
				max-width: 50%;
				margin: 0px !important;
				padding: 0px 6px 0px 6px;
			}
			.wishlist-icon {
				right: 5px;
				top: 5px;
				padding: 5px 5px;
			}
			.strip .wrapper h3 a {
				line-height: 25px;
				font-size: 14px;
			}
			.strip .wrapper h3 {
				margin: 0px 0px 0px 0px;
				min-height: 31px;
			}
			img.direction {
				right: 10px;
				width: 46px;
				position: absolute;
				z-index: 2000;
				bottom: 20px;
			}
			.pagination a, .pagination span {
				min-width: 30px;
				font-size: 14px;
				height: 32px;
				line-height: 34px;
			}

			.pagination li {
				padding: 0px;
			}
			a.address {
				font-size: 12px;
			}
	}
</style>

<form id="latlongform" action="{{route('pages')}}" enctype="multipart/form-data" method="get">
    @csrf
    <input type="hidden" id="lati" name="lati" >
    <input type="hidden" id="long" name="long" >
</form>

<div class="sub_header_in filter_sub_blue_header sticky_header">
	<div class="container-fluid" style="padding: 0px 25px;">
		<div class="row">
			<div class="col-lg-2 col-md-2 ">
				<a class="sk_fade" href="{{ url('/listings') }}"><h1><i class="fa fa-hand-point-right"></i> {{__("message.menu_text_listing")}}</h1></a>
			</div>
			<div class="col-lg-10 col-md-10" >
				<input type="hidden" id="urlvalue" value="{{ $arrendvalue }}" />
				@if(in_array("trademark", $currenturl))
				<form action="{{ route('listingbytrademark', $arrendvalue) }}" method="GET">
				@elseif(in_array("tag", $currenturl))
				<form action="{{ route('listingbytag', $arrendvalue) }}" method="GET">
				@else
				<form action="{{ route('listing') }}" method="GET">
				@endif
					<div class="row no-gutters custom-search-input-2 inner notranslate">
						<div class="col-lg-4 col-md-3" id="searchquerylisting_div">
							<div class="form-group">
								<input class="form-control" id="searchquerylisting" autocomplete="off" name="search_string" type="text" value="{{ isset($search_string)?$search_string:''; }}"  placeholder="{{__('message.header_search_text')}}">
								<i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording"></i>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 all_listing_cat">
							@if($tags)
								<input type="hidden" name="tags" value="{{ $tags }}">
								@endif
								@if($subcat)
								<input type="hidden" name="subcat" value="{{ $subcat }}">
							@endif
							<select class="wide cat" name="cat">
								<option value="all">{{__("message.header_search_catogory_text")}}</option>
								<?php foreach($allParentcategory as $parentcat){ ?>
								<option value="{{ $parentcat->id }}" {{ $parentcat->id == $cat ? 'selected' : '' }}>{{ $parentcat->name }}</option>
								<?php } ?>
							</select>
						</div>
                        <div class="col-lg-2 col-md-3 listing_filters">
							<select class="wide" name="filters">5
                                <option value="">Select Filters</option>
								<option value="relevant-listings" {{ 'relevant-listings'== $filters ? 'selected':''}}>Most Relevant</option>
                                <option value="latestcompany" {{ 'latestcompany'== $filters ? 'selected':''}}>Latest Listings</option>
                                <option value="mostpopular" {{ 'mostpopular'== $filters ? 'selected':''}}>Most Popular</option>

							</select>
						</div>
						<div class="col-lg-2 col-md-2 timedrop listing_time">
							<select class="wide" name="timeSelected">
                                <option value="">Select Time</option>
								<option value="open_companies" {{ 'open_companies'== $timeSelected ? 'selected':''}}>Open</option>
                                <option value="closed_companies" {{ 'closed_companies'== $timeSelected ? 'selected':''}}>Close</option>
                            </select>
						</div>
						<div class="col-lg-1 col-md-1" id="submit_div">
							<input type="submit" value="{{__('message.header_search_text_search_btn')}}" class="desktop-search btn_search_mobile_submit">
							<input type="submit" value="{{__('message.header_search_text_search_btn')}}" class="search_mob btn_search_mobile btn_search_mobile_submit" placeholder="Search">
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
	<div class="row">
		<!-- /aside -->
		@if(!empty($categoryUrls) || !empty($subcategoryUrls) || !empty($tagsUrls))

		<div class="col-md-12" id="listing_hierarchical">
			<p><a class="sk_fade" href="{{ url('/listings') }}">{{__("message.menu_text_listing")}}</a>
				>
				@php
				if(!empty($_GET['search_string'])){
					echo $_GET['search_string'].' > ';
				}
				@endphp
				@foreach ($categoryUrls as $val)
				@php
				$getCatname = Helper::getCatnameIdLangId($val,$language_id);
				@endphp
				{{ $getCatname }} >
				@endforeach

				@foreach ($subcategoryUrls as $val)
				@php
				$getSubCatname = Helper::getCatnameIdLangId($val,$language_id);
				@endphp
				{{ $getSubCatname }}
				>
				@endforeach
				@foreach ($tagsUrls as $val)
				@php
				$getTagname = Helper::getTagnameIdLangId($val,$language_id);
				@endphp
				{{ $getTagname }}
				@if (!$loop->last)
				>
				@endif
				@endforeach
			</p>
		</div>

		@endif

		<x-Clientfilter :directoryData="$directoryDetails" />
		<!-- /aside -->
		<div class="col-xl-6 col-md-12 order-xl-1 order-2 listing_div" id="listings">
			<div class="row" data-aos="zoom-in"  data-aos-duration="1000">

				<div class="select_city_home col-md-4 notranslate">
					<select name="city_name" id="city_name" class="form-control wide2 notranslate" onChange="getchangeTheSessionData(this)" style="width: 200px;"> </select>
				</div>

				<div class="col-md-3" id="distance_div">
					<select class="form-control" name="distance" id="distance" style="margin-bottom: 5px;" onChange="updateSessionDistance(this)">
						@php
							$selected = session('distance', $distancesetting->firstWhere('default_distance', 'yes')->to_distance ?? '');
						@endphp
						@foreach ($distancesetting as $disval)
							<option value="{{ $disval->to_distance ?? '' }}" @if($selected == $disval->to_distance) selected @endif>
								0 - {{ $disval->to_distance ?? '' }}
							</option>
						@endforeach
					</select>
				</div>


				<div class="col-lg-5">
					@if ($directoryDetails->count() > 0)
					<div class="d-flex notranslate" style="border: 1px solid #d2d8dd; padding: 0px 10px; line-height: 30px; margin: 1px 0px; background: #fff;">
						<p class="text-right" style="margin: 5px -2px;font-size: 13px;">
							Showing {{ $directoryDetails->firstItem() }} - {{ $directoryDetails->lastItem() }} of {{ $directoryDetails->total() }} companies
						</p>
					</div>
					@endif
				</div>

		    </div>


			<div class="row" id="listing_row" style="margin-top: 5px;">
			<?php
				if(count($directoryDetails) > 0){
				foreach($directoryDetails as $row){

					$imagename = Helper::getDirectoryimage($row->id);
					$getDirectoryDescription = Helper::getDirectoryDescriptionIdLocationid($row->id,$language_id);
					$directoryAddress = Helper::getDirectoryLocationID($row->id);
					$current_day = date('l', strtotime(date("y-m-d")));
					$directorySchedule = Helper::getDirectoryScheduleIDday($row->id,$current_day);

					$comOpentime = explode(":",$directorySchedule->open_time??0);
					$compClosetime = explode(":",$directorySchedule->close_time??0);
					$close_time_val = $directorySchedule->close_time??0;
					$open_time_val = $directorySchedule->open_time??0;

					if(!empty($_GET['search_string'])){
						Helper::search_visitors('company',$row->id);
					}

				?>
				@if(app()->environment('production'))
				@if(request()->filled('search_string'))
					<script>
						window.dataLayer = window.dataLayer || [];
						gtag('event', 'search_vendor', {
							search_keyword: @json(request('search_string')),
							vendor_id: @json($row->id)
						});
					</script>
				@endif
				@endif
				<div class="col-lg-6 col-md-6 listing-div company_list_data" data-aos="fade-up" data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id = "dirList<?php echo $row->id; ?>">

					<div class="strip grid featured-tag-border">
						<figure>
							<input class="product-wishlist" type="hidden" value="{{ $row->id }}" name="product_id" id="product_id">
							<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
							<a href="javascript:void(0);" productid="{{ $row->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $row->id ?>)">
								<?php
								if(!in_array($row->id, $wislistdirectoryId)){ ?> <i class="fa fa-heart-o" aria-hidden="true"></i>
								<?php }else{ ?> <i class="fa fa-heart" aria-hidden="true"></i>
								<?php } ?>
							</a>
							<span class="featured-tag-grid">
								@if($row->feature_type == 'superfeatured') Super featured @else Featured @endif
							</span>
					        <a href="<?php echo url('listings/details', ['id' => $row->slug??'']); ?>"  class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}')"
							   <?php }else{ ?> style="background-image:url('{{ asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg') }}')" <?php } ?>
							>
							<div class="read_more"><span>{{__("message.watch_details_text")}} </span></div>
							</a>
						</figure>
						<div class="wrapper featured-body">
							<h3 class="ellipsis ">
								<div class="notranslate">
									<a href="<?php echo url('listings/details', ['id' => $row->slug]); ?>" class="notranslate">{{ $row->title }}</a>
								</div>
                            </h3>
							<small class="notranslate">
								{{ substr(strip_tags(isset($directoryAddress->address)?$directoryAddress->address:""),0,30) }}
								{{ isset($directoryAddress->address)?'..':"" }}
							</small>

							<p class="ellipsis notranslate">
								@if($getDirectoryDescription->description)
								{{ substr(strip_tags($getDirectoryDescription->description??''),0,50) }}
								@else
							   .
								@endif

							</p>
							<?php
								$latitude  = $directoryAddress->latitude ?? '';
								$longitude = $directoryAddress->longitude ?? '';
								$address   = $directoryAddress->address ?? '';
								$title     = $row->title;
								if($latitude>0 && $longitude>0){
							?>
							<a target="_blank" href="https://www.google.com/maps/dir//<?php echo $latitude; ?>,<?php echo $longitude; ?>/@<?php echo $latitude; ?>,<?php echo $longitude; ?>,16z">
								<img class="direction" src="{{ url('/') }}/assets/client/images/direction2.png" width="10%" alt="">
							</a>
							<a class="address" id="listingmap_<?php echo $row->id; ?>" dirid="<?php echo $row->id; ?>" href="javascript:" lati="<?php echo $latitude; ?>" addr="<?php echo $address; ?>" titledir="<?php echo $title; ?>" longi="<?php echo $longitude; ?>" target="">Show on map</a>
						    <?php } ?>
						</div>

						<ul class="featured-footer mb-0">

							<li>
								<?php if($compClosetime[0] >= $hour??'' && $comOpentime[0] <= $hour??''){
									if($compClosetime[0] > $hour??'' ){ ?>
										<span class="loc_closed" style="color: #3aaf47; border: 1px solid #3aaf47;">
											{{__("message.open_text")}} {{ $open_time_val }} - {{__("message.closed_text")}} {{ $close_time_val }}
										</span>
									<?php }else{
										if($compClosetime[1]>=$min){ ?>
											<span class="loc_closed" style="color: #3aaf47; border: 1px solid #3aaf47;">
												{{__("message.open_text")}} {{ $open_time_val }} - {{__("message.closed_text")}} {{ $close_time_val }}
											</span>
										<?php }else{ ?>
											<span class="loc_closed">
												{{__("message.open_text")}} {{ $open_time_val }} - {{__("message.closed_text")}} {{ $close_time_val }}
											</span>
								<?php } } }else{ ?>
									<span class="loc_closed">
										{{__("message.open_text")}} {{ $open_time_val }} - {{__("message.closed_text")}} {{ $close_time_val }}
									</span>
								<?php } ?>
							</li>

							<li>
								<div class="score">

									{{-- <span>{{__("message.awesome_text")}}<em>{{__("message.reviews_text")}}</em></span><strong>5.0</strong> --}}

								</div>
							</li>
						</ul>
					</div>
				</div>
				<?php } }else{ ?>
					<h2 style="font-size: 19px; margin: 5px 15px;">{{__("message.no_record_found_text")}}</h2>
				<?php } ?>
			</div>
			<div class="d-flex justify-content-center notranslate"> {{$directoryDetails->links('pagination::bootstrap-4')}}</div>
		</div>

		<div class="col-xl-3 order-xl-2 order-1" >
			<div class="stiky-map mb-5 mb-xl-0">
				<div id="map" class="map-full map-layout"></div>
				<div id="marker-tooltip"></div>
			</div>
		</div>
	</div>

	<div id="most-viewed-listings-container" class="notranslate"></div>

</div>
<!-- /container -->


<script type="text/javascript">

const startRecordingButton = document.getElementById('start-recording');
	const voiceOutput = document.getElementById('searchquerylisting');
	let recognition;
	if ('SpeechRecognition' in window) {
        recognition = new SpeechRecognition();
    } else if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
    } else {
        console.log("Speech Recognition API not supported in this browser.");
    }

	    @php
		if (session()->has('lang_code')) {
			if (session()->get('lang_code') == 'en') {
			@endphp
			if (recognition) {
			recognition.lang = "en-US";
			}
			@php
			}else if (session()->get('lang_code') == 'sv') {
			@endphp
			if (recognition) {
			recognition.lang = "sv-SE";
			}
			@php
			}
    	}else{
		@endphp
			if (recognition) {
			recognition.lang = 'en-US';
			}
		@php
		}
		@endphp

		 // Start recording when button is clicked
		 startRecordingButton.addEventListener('click', () => {
			if (recognition) {
            recognition.start();
			}
			$('#searchquerylisting').val('Listening...');
        });

        // When speech is detected
		if (recognition) {
        recognition.onresult = (event) => {
            const query = event.results[0][0].transcript;
            voiceOutput.textContent = `You said: "${query}"`;
			$('#searchquerylisting').val(query);
			$('.btn_search_mobile_submit').click();
        };
	    }

	$(document).ready(function() {

		$.ajax({
            url: "{{ route('load.most.viewed.listings') }}",
            type: 'GET',
            success: function(response) {
                $('#most-viewed-listings-container').html(response);
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
            }
        });

	var getcities = "{{ route('get.cities') }}";
        var token = "{{ csrf_token() }}";

			$('#city_name').select2({
				placeholder: "Search city",
				allowClear: true,
				ajax: {
					type: "POST",
					url: getcities,
					dataType: 'json',
					delay:250,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
						'Accept': 'application/json'
					},
					data:
					function (params) {
							return { search: params.term };
					},
					processResults: function (response) {
						return {
							results: response
						};
					},
					cache: true
				}
			});

			var deviceWidth = $(window).width();
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

	function showToggleAm(elem, selector) {
		$('.'+selector).slideToggle();
		console.log($(elem).text());
		if($(elem).text() === "Show less")
		{
			$(elem).text('Show more');
		}
		else
		{
			$(elem).text('Show less');

		}
	}

	function showToggle_tag(elem, selector) {
		$('.'+selector).slideToggle();
		if($(elem).html() == '<i id="tag_icon" style="font-size: 16px;" class="fas fa-caret-up" aria-hidden="true"></i>')
		{
			$('#tags-toggle-btn').html('<i id="tag_icon" style="font-size: 16px;" class="fas fa-caret-down" aria-hidden="true"></i>');
		}
		else
		{
			$('#tags-toggle-btn').html('<i id="tag_icon" style="font-size: 16px;" class="fas fa-caret-up" aria-hidden="true"></i>');
		}
	}


	function isMobileDevice() {
		return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
	}


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

        } else {

            console.log("Sorry, your browser does not support HTML5 geolocation.");
        }


		var locations = [
			<?php

			if(count($directoryDetails)!=0){

			$mi = 1;
			$latitude = [];
			$longitude = [];
			foreach($directoryDetails as $key => $row){
				$directoryAddress = Helper::getDirectoryLocationID($row->id);
					$latitude = isset($directoryAddress->latitude)?$directoryAddress->latitude:"";
					$longitude = isset($directoryAddress->longitude)?$directoryAddress->longitude:"";
					$address = isset($directoryAddress->address)?$directoryAddress->address:"";
					$title = $row->title;
					$seo_url = $row->slug;
					$distance = round($row->distance);
			?>
			['<a target="_blank" href="{{ url("listings/details/$seo_url") }}"> <b style="text-transform: uppercase; color:#d93025;">{{ $title }}</b><br> <p style="font-style: italic;">Distance - {{ $distance }}km</p></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],

			<?php
			$mi++;
			$newLatitude = $latitude_session;
			$newLongitude = $longitude_session;
			if(isset($key) && $key == 0){
			?>

			['<a href="javascript:void();"> <b style="text-transform: uppercase; color:#d93025;">My Location</b> </a>', {{ $newLatitude }}, {{ $newLongitude }}, {{ $mi }}],

			<?php
			}
			$mi++;
			}}else{
			?>
			<?php
				$mi = 1;
				$latitude = $latitude_session;
				$longitude = $longitude_session;
				if(isset($key) && $key == 0){
				?>
				['<a href="javascript:void();"><b style="text-transform: uppercase; color:#ffc108;">My Location</b></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }} ],
			<?php
				}
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
			if (isMobileDevice()) {
				google.maps.event.addListener(marker, 'click', (function (marker, i) {
					return function () {
						infowindow.setContent(locations[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			} else {
				google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
					return function () {
						infowindow.setContent(locations[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}

		});

		@if ($latitude_session)
			var selectedPositionlatlong = new google.maps.LatLng({{ $latitude_session }},{{ $longitude_session }});// madhulika for focus on the right map based on the selected location
		@else
			var selectedPositionlatlong = '';
		@endif

		map.fitBounds(bounds);
		var listener = google.maps.event.addListener(map, "idle", function () {
			map.setZoom(14);
			map.setCenter(selectedPositionlatlong);
			google.maps.event.removeListener(listener);
		});
	}
	window.onload = showPosition;



	$(document).ready(function() {

		var session_city = "{{ session()->get('filter_city') }}";
		if (session_city !== 'Not found' && session_city !== null && session_city !== '') {
			$(".select_city_home #select2-city_name-container").text(session_city);
		}

		$('.address').on('click', function() {

		var latival = $(this).attr('lati');
		var longival = $(this).attr('longi');
		var addrval = $(this).attr('addr');
		var titledirval = $(this).attr('titledir');
		var mapDirectoryId = $(this).attr('id');
		var dirId = $(this).attr('dirid');

		var mapdivCount = $('.mobilemapview').length;
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

	window.mobileCheck = function() {
		let check = false;
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
		return check;
	};
</script>

</main>
@endsection
