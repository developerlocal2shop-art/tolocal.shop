@extends('layouts.client.blog')
@section('title','Events | Event listing ')
@section('content')
</a>
	<style>
		.singlepost figure img {
			max-width: 100%;
			height: 387px!important;
		}
		.ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span {
			background: #ccc !important;
		}
		.post_info .category_infopost {
			font-weight: 762 !important;
			color: #004dda !important;
		}
		article.blog .post_info small {
			font-weight: 500;
			font-size: 12px;
		}
		article.blog figure {
			border-bottom: 1px solid #ccc;
		}
	</style>
	<div class="sub_header_in sticky_header">
		<?php
		$eventData =  $data['eventData'];
		$eventsLatest =  $data['eventsLatest'];
		$catData =  $data['catData'];
		?>
		<div class="container">
			<h1>{{__("message.event_title")}}</h1>
		</div>
	</div>
    <main>
	<div class="container margin_60_35">
		
		<div class="widget search_blog">
			<!-- <label>Search {{__("message.title_search")}}, {{__("message.description_title")}}</label> -->
			<form action="{{ route('event.indexsearch') }}" method="get" class="" id="searchEvent_data" enctype="multipart/form-data">

				<div class="row form-group">
					<div class="col-md-2">
						<input type="text" name="fromdate" style="float: left;" autocomplete="off" value="{{ $eventsearchFromDate }}" id="fromdate"  class="form-control placeholder-lang-css" placeholder="From Date">
					</div>
					<div class="col-md-2">
						<input type="text" name="todate" autocomplete="off" value="{{ $eventsearchToDate }}" id="todate"  class="form-control placeholder-lang-css" placeholder="To Date">
					</div>  	
					<div class="col-md-2">
						<select class="wide2 cat form-control" name="cat" onChange="getSubCategory(this.value)">
							<option value="all">All Category</option>
							<?php foreach($allParentcategory as $parentcat){ ?>
							<option value="{{ $parentcat->id }}" {{ $parentcat->id == $searchCatId ? 'selected' : '' }}>{{ $parentcat->name }}</option>
							<?php } ?>
						</select>
					</div>
					<?php if($searchCatId>0){ ?>
						<div class="col-md-2">
						<?php 
						$subcategory = Helper::getchildCategory($searchCatId); 
						//echo "<pre>"; print_r($subcategory); echo "</pre>";
						?>
							<select name="subcat" id="subcategory" class="form-control">
								<option disabled="disabled" selected="selected">Select Sub-Category</option>
								<option value="">None</option>
								<?php if($searchCatId!="all"){ foreach($subcategory as $subrow){ ?>
								<option value="{{ $subrow->id }}" {{ $subrow->id == $searchsubCatId ? 'selected' : '' }}>{{ $subrow->name }}</option>
								<?php }} ?>
							</select>
						
						</div>
					<?php }else{ ?>
						<div class="col-md-2">
							<select name="subcat" id="subcategory" class="form-control">
								<option disabled="disabled" selected="selected">Child Category</option>
							</select>
						</div>
					<?php } ?>
					<div class="col-md-2">
						<input type="text" name="searchtext" value="{{ $eventsearch }}" id="searching_key" class="form-control" placeholder="Search text..">
						<span><input type="submit" id="blog_searching_btn" name="search" value="&#xf002;" style="cursor: pointer;top: 5px !important;right: 17px !important;font-family: FontAwesome"></span>
					</div>

					<div class="col-lg-2 col-md-2 distance_div">
						<select class="form-control"  name="distance" id="distance" onChange="updateSessionDistance(this)" >
							{{-- <option value="0">Select distance in KM</option> --}}
							@foreach ($distancesetting as $disval)
								@if(session('distance'))
									@php
										$selected = session('distance');
									@endphp
								@else
									@php
										if($disval->default_distance == 'yes'){
											$selected = $disval->to_distance;
										}else{
											$selected = '';
										}
									@endphp
								@endif
								<option value="{{ $disval->to_distance??'' }}" @if($selected == $disval->to_distance) selected @endif>0 - {{ $disval->to_distance??'' }}</option>
							@endforeach
						</select>
					</div>
					
				</div>
			</form>
		</div>
		
			
		<div class="row">
			<div class="col-lg-9">
				<div class="col-lg-5">
					@if ($eventData->count() > 0)
					<div class="d-flex notranslate">
						<p class="text-right">
							Showing {{ $eventData->firstItem() }} - {{ $eventData->lastItem() }} events
						</p>
					</div>
					@endif
				</div>
				<div class="row">
					<?php if(count($eventData) > 0){ ?>
					<?php foreach($eventData as $row){ ?>
					<div class="col-md-6" data-aos-duration="1000" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
						<article class="blog eventlist">
							<figure>
								<a href="{{ url('event/details/'.$row->slug) }}">
									<img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" alt="...">
									<div class="preview"><span>{{__("message.read_more")}}</span></div>
								</a>
							</figure>
							<div class="post_info">
								<small><a class="category_infopost" href="<?php echo url("/events/category", ['id' => @$row->category_slug]); ?>">
									<?php echo $row->category_name;?>
								</a><br>
								<?php echo date('d M Y', strtotime($row->start_date));?> : <?php echo $row->start_time;?>
								- <?php echo date('d M Y', strtotime($row->end_date));?> : <?php echo $row->end_time;?>
								
								@php
									$today = date('Y-m-d H:i:s');
								@endphp

								@if($row->end_date >= $today)
									<b style="color:darkgreen">Active</b>
								@else
									<b style="color:rgb(236, 17, 17)">Expired</b>
								@endif 
								
							    </small>
								<h2><a href="{{ url('event/details/'.$row->slug) }}"><?php echo strip_tags($row->et_title); ?></a></h2>
								<p><?php echo  str_replace('&nbsp;', ' ',substr(strip_tags($row->description),0,200));?>
								@if(strlen($row->description) > 200 && $row->description)
								<a class="" href="{{ url('event/details/'.$row->slug) }}">{{__("message.read_more")}}</a>
								@endif
								</p>
								<ul  style="display:none">
									<li>
										<div class="thumb">
											<img src="assets/client/images/user.png" alt="">
										</div>{{__("message.user_name")}}</li>
									<li><i class="fa fa-comment-o" aria-hidden="true"></i>6</li>
								</ul>
							</div>
						</article>
					</div>
					<?php }?>
					<?php }else{?>
						<h2>{{__("message.no_record_found_text")}}</h2>
					<?php } ?>
				</div>
				<!-- /row -->
				<!-- /pagination -->
				<!-- custom pagination -->
				<div class="d-flex notranslate">
					{{$eventData->links('pagination::bootstrap-4')}}
				</div>
				<!-- custom pagination end-->
				<!-- <nav class="text-center"></nav> -->
				<!-- /pagination -->
			</div>
			<aside class="col-lg-3">
				
				<!-- /widget -->
				<div class="widget">
					<div class="widget-title">
						<h4>{{__("message.latest_post")}}</h4>
					</div>
					<ul class="comments-list">
						<?php foreach($eventsLatest as $row){ ?>
						<li data-aos-duration="1000" data-aos="zoom-in">
							<div class="alignleft">
								<a href="{{ url('event/details/'.$row->slug) }}">
									<img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" alt="...">
								</a>
							</div>
							<small>Hotel<?php //echo $row->category_name;?> - <?php echo date('d M Y', strtotime($row->updated_at));?></small>
							<h3>
								<a href="{{ url('event/details/'.$row->slug) }}"><?php echo  substr(strip_tags($row->et_title),0,30);?>...</a>
							</h3>
						</li>
						<?php } ?>
					</ul>
				</div>
				<!-- /widget -->
				<div class="widget"  style="display:none">
					<div class="widget-title">
						<h4>{{__("message.footer_text_categories")}}</h4>
					</div>
					<ul class="cats">
						<?php foreach($catData as $cat){ ?>
							<li><a href="#" class="">	<?php echo $cat->name; ?> <span>(2)</span></a></li>
						<?php } ?>
					</ul>
				</div>
			</aside>
			<!-- /aside -->
		</div>
		<!-- /row -->
	</div>
<!-- /container -->

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
<!-- <script>
	jQuery(function($) {
      $("#searchEvent_data").validate({
		rules: {
            searchtext : {required: true},
		},
	  	messages : {
			searchtext: {
				required: "Please fill or select data first!",
			},
		},
		submitHandler:function(){
          if($("#searchEvent_data").valid() === true){
			document.getElementById('searchEvent_data').submit();
          }
      	}
      });
	});
</script> -->
<script>
        $(document).ready(function() {

              	$("#fromdate").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $("#todate").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $('.ui-datepicker').addClass('notranslate');

        })
		function getSubCategory(cat_id) {
			$("#subcategory").html('');
			$.ajax({
				url: baseUrl + '/get-subcategories-by-categoryid',
				type: "POST",
				data: { category_id: cat_id, _token: '{{csrf_token()}}' },
				dataType: 'json',
				success: function(result) {
					$("#subcategory").append('<option value="">None</option>');
					if(cat_id!="all"){
						$.each(result.subcat, function(key, value) {
							$("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
						});
					}

				}
			});
		}
    </script>
</main>
<style type="text/css">

    @media (max-width: 768px) {
		select#subcategory {
			display: none;
		}
		input#searching_key {
			margin: 1px 2px 20px 0px;
		}
		input#todate {
			width: 49%;
			float: right;
			margin: -43px -1px;
		}
		input#fromdate {
			width: 50%;
			float: left;
		}
		select#distance {
			width: 30%;
			float: right;
		}
	}

	#searching_key-error{
		color:red;
	}
</style>
@endsection
