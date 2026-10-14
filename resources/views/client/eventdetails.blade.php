@extends('layouts.client.blog')
@if (isset($metadata) && !empty($metadata))
    @if (!empty($metadata->meta_title))
    @section('title', $metadata->meta_title.' - Company')
    @else
    @section('title', $data['event']->et_title)
    @endif
@else
    @section('title', $data['event']->et_title)
@endif
@section('content')
</a>

<style>

.singlepost figure img {
    max-width: 100%;
    height: 640px!important;
}

 @media screen and (max-width:767px){
    .singlepost figure img {
        max-width: 100%;
        height: 385px!important;
    }
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
    span.calendar_text {
        display: table-cell;
        font-size: 14px;
        font-weight: 500;
        color: midnightblue;
    }
    li.calendar_div a {
        padding: 10px 5px !important;
    }
    .calendar_div img {
        width: 45px !important;
        padding: 4px;
    }
    input.form-control {
        margin: 1px;
    }
    .form-control {
        margin: 1px;
    }
    .single-listing-map {
        height: 223px;
    }
}

.dropcaps p:first-child::first-letter {
    font-size: 33px !important;
    margin: 8px 3px 0 0 !important;
    padding: 2px;
}
#sploffer .owl-item {
    margin: 0px -27px 0px 0px;
}
div#sploffer.eventsinglepostgallery.owl-carousel .owl-item img {
    width: 115px;
    object-fit: contain!important;
    object-position: center;
    height: 160px!important;
    border: 1px solid #ccc;
}
button#leave_comment {
    border: none;
    margin: 5px 0px;
    padding: 5px 10px 0px 10px;
    cursor: pointer;
}
#comments {
    padding: 20px 0px 0px 30px;
    margin-bottom: 30px;
}
.comment_right {
    padding: 4px 10px 0 10px;
}
span.external_link a {
    background: #ccc;
    padding: 5px 10px;
    float: right;
    margin: -20px 0px;
    color: #121112;
    border-radius: 5%;
}
</style>

<div class="sub_header_in sticky_header">
    <?php
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);

    if($user_ip=='127.0.0.1' || $user_ip=='localhost' ){
        $countryCode = 'IN';
        $state = 'National Capital Territory of Delhi';
        $state_array = explode(" ",$state);
        $city = 'Delhi';
    }else{
        $countryCode = $geo["countryCode"]??'';
        $state = $geo["regionName"]??'';
        $state_array = explode(" ",$state);
        $city = $geo["city"]??'';
    }

    if(in_array("Capital", $state_array)){
        $state = $city;
    }else{
        $state = $state;
    }

	$event =  $data['event'];
	$eventData =  $data['eventData'];
    $eventsLatest =  $data['eventsLatest'];
	$catData =  $data['catData'];
	$reviewData = $data['reviewData'];

	?>
    <div class="container">
        <h1><?php echo $event->et_title;?></h1>
    </div>
</div>
@php
    $getCompanySlug = Helper::getDirectoryIdSlug($event->directory_id??0);
@endphp
<!-- /sub_header -->
<!-- Main page -->
<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<main>
    <div class="container margin_60_35">

        <div class="widget search_blog">

			<form action="{{ route('event.indexsearch') }}" method="get" class="" id="searchEvent_data" enctype="multipart/form-data">

				<div class="row form-group">
					<div class="col-md-2">
						<input type="text" name="fromdate" style="float: left;" autocomplete="off" value="{{ $eventsearchFromDate }}" id="fromdate"  class="form-control placeholder-lang-css" placeholder="From Date">
					</div>
					<div class="col-md-2">
						<input type="text" name="todate" autocomplete="off" value="{{ $eventsearchToDate }}" id="todate"  class="form-control placeholder-lang-css" placeholder="To Date">
					</div>
					<div class="col-md-3">
						<select class="wide2 cat form-control" name="cat" onChange="getSubCategory(this.value)">
							<option value="all">All Category</option>
							<?php foreach($allParentcategory as $parentcat){ ?>
							<option value="{{ $parentcat->id }}" {{ $parentcat->id == $searchCatId ? 'selected' : '' }}>{{ $parentcat->name }}</option>
							<?php } ?>
						</select>
					</div>
					<?php if($searchCatId>0){ ?>
						<div class="col-md-3">
						<?php
						$subcategory = Helper::getchildCategory($searchCatId);
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
						<div class="col-md-3">
							<select name="subcat" id="subcategory" class="form-control">
								<option disabled="disabled" selected="selected">Select Sub-Category</option>
							</select>
						</div>
					<?php } ?>
					<div class="col-md-2">
						<input type="text" name="searchtext" value="{{ $eventsearch }}" id="searching_key" class="form-control" placeholder="Search text..">
						<span><input type="submit" id="blog_searching_btn" name="search" value="&#xf002;" style="cursor: pointer;top: 5px !important;right: 17px !important;font-family: FontAwesome"></span>
					</div>

				</div>
			</form>
		</div>

        <div class="row">
            <div class="col-lg-9">
                <div class="singlepost eventsinglepost">
                    <figure>
                        <img src="{{ env('IMAGE_PATH').'files/events/'.$event->event_image }}" width="100%" alt="..." style="max-height: 650px;">
                    </figure>
                    <h1>{{ $event->et_title }} </h1>
                    <div class="postmeta">
                        <ul>
                            <li>
                                <a href="<?php echo url("/events/category", ['id' => @$event->category_slug]); ?>"><i class="fa fa-folder" aria-hidden="true"></i> <?php echo $event->category_name;?></a>
                            </li>
                            <li>
                                <span><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date('j F Y', strtotime($event->start_date));?> : <?php echo $event->start_time;?></span>
                            </li>
                            <li>
                                <span><i class="fa fa-calendar-alt" aria-hidden="true"></i> <?php echo date('j F Y', strtotime($event->end_date));?> : <?php echo $event->end_time;?></span>
                            </li>
                            <li>
                                <a href="javascript: void(0)" id="click"><i class="fa fa-comment-o" aria-hidden="true"></i> (<?php echo count($reviewData) ?>) {{__("message.comment_text")}}</a>
                            </li>
                            <li>
                                <?php //echo "<pre>"; print_r($event); ?>
                                <input type="hidden" name="type_item" id="type_items" value="event">
                                <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                                <input type="hidden" name="country_code" id="country_code" value="{{$countryCode}}">
                                <input type="hidden" name="state" id="state" value="{{$state}}">
                                <input type="hidden" name="city" id="city" value="{{$city}}">
                                <a href="javascript:void(0);" id="eventlikedtotal" title="Like Event" productid="{{ $event->id }}" onclick="addToLikeCounter(this, <?php echo $event->id ?>)">
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                    (
                                    <span>
                                        @if(count(Helper::CountLikeData('event',$event->id)) != 0)
                                            {{ count(Helper::CountLikeData('event',$event->id)) }}
                                        @else
                                            {{ 0 }}
                                        @endif
                                    </span>
                                    ) Like
                                </a>
                            </li>
                            @if($getCompanySlug)
                                <li>
                                    Go to Company<a href="{{ url('listings/details/'.$getCompanySlug->slug) }}"> <b>{{ $getCompanySlug->title }}</b></a>
                                </li>
                            @endif

                            @if(auth()->check())
                                @if(auth()->user()->directory_id == $event->directory_id)
                                    <li>
                                        <a href="{{ url('company/add-events') }}"> Add New <b>Event</b></a>
                                    </li>
                                @endif
                            @endif
                            <li>
                            <a href="{{ url('contact') }}" target="_blank">Contact Us</a>
                            </li>
                            @php
                               $userid = Auth()->user()->id??'0';
                               $date11=date_create($event->start_date);
                               $date1 = date_format($date11,"Ymd");
                               $date22=date_create($event->end_date);
                               $date2 = date_format($date22,"Ymd");

                                $title = urlencode($event->et_title);
                                $startDate = urlencode($date1);
                                $endDate = urlencode($date2);
                                $location = urlencode($event->location);
                                $details = urlencode($event->description);


                            @endphp
                            <hr>
                                <li class="calendar_div">

                                    <span class="calendar_text" style="text-decoration:underline;">ADD THIS EVENT TO CALENDAR </span> <i class="fas fa-hand-point-right" aria-hidden="true"></i>

                                    <a style="margin-right: 5px; border: 1px solid #2272ba; padding: 25px 2px 25px 2px;" class="add_to_social" href="https://outlook.live.com/calendar/0/deeplink/compose?subject={{$title}}&startdt={{$startDate}}&enddt={{$endDate}}&body={{$details}}&location={{$location}}"
                                        target="_blank" rel="nofollow">
                                        <img src="{{ url('assets/images/outlook_cal2.png') }}" class="img-fluid" alt="" width="80" height="50">
                                    </a>
                                    <a style="margin-right: 5px; border: 1px solid #ea4436; padding: 25px 2px 25px 2px;" class="add_to_social" href="https://calendar.google.com/calendar/r/eventedit?text={{ $event->et_title }}&details={{ $event->description }}&location={{ $event->location??'123%20Some%20Place%2C%20City' }}&dates={{ $date1 }}T100000/{{ $date2 }}T113000&ctz=America%2FNew_York"
                                        target="_blank" rel="nofollow">
                                        <img src="{{ url('assets/images/gcal2.png') }}" class="img-fluid" alt="" width="80" height="50">
                                    </a>
                                    <a style="margin-right: 5px; border: 1px solid #5f01d1; padding: 25px 2px 25px 2px;" class="add_to_social" href="https://calendar.yahoo.com/?v=60&view=d&type=20&title={{$title}}&st={{$startDate}}&et={{$endDate}}&in_loc={{$location}}&desc={{$details}}"
                                        target="_blank" rel="nofollow">
                                        <img src="{{ url('assets/images/yahoo.png') }}" class="img-fluid" alt="" width="83" height="50">
                                    </a>

                                    <a style="margin-right: 5px; border: 1px solid #5f01d1; padding: 25px 2px 25px 2px;" class="add_to_social" href="{{ $ics_link }}"
                                        target="_blank" rel="nofollow">
                                        <img src="{{ url('assets/images/ics.png') }}" class="img-fluid" alt="" width="83" height="50">
                                    </a>

                                </li>
                        </ul>
                        <input type="hidden" id="guser_id" value="{{Auth()->user()->id??'0'}}">
                        <div class="post-content" style="min-height: 100px;">
                            <div class="dropcaps blog_text pb-4">
                                <!-- <?php echo $event->description;?> -->
                                <?php echo  str_replace('&nbsp;', ' ', $event->description); ?>
                                <p>&nbsp;</p>
                            </div>
                            @if($event->externalLink)
                                <span class="external_link">
                                    <a href="{{ $event->externalLink }}" target="_blank">External Link</a>
                                </span>
                            @endif

                        </div>
                        <hr>
                        @php
                        $event_id = $event->id??'0';
                        $galleryimagename_thumb = $data['eventgallery']->eventgallery;
                        @endphp
                        @if (count($galleryimagename_thumb) > 0)
                        <div class="container-fluid">
                            <div id="sploffer" class="owl-carousel owl-theme eventsinglepostgallery">
                                <?php foreach($galleryimagename_thumb as $rowgallery){ ?>
                                <div class="item">
                                    <div class="strip grid">
                                        <figure>
                                            <a href="{{env('IMAGE_PATH').'files/events/'.$rowgallery->name_thumbnail }}" data-lightbox="gallery">
                                                <img src="{{env('IMAGE_PATH').'files/events/'.$rowgallery->name_thumbnail }}" class="img-fluid" alt="" width="400" height="266">
                                            </a>
                                        </figure>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- /post meta -->

                    <!-- /post -->
                </div>
                <!-- /single-post -->

                <?php
                $userID = Auth()->check() ? Auth()->user()->id : 0;
                //if($userID>0){
                ?>
                <hr>
                <button id="leave_comment"><h5>{{__("message.comment_form_title")}}</h5> </button>
                <div style="display: none;" class="comment_box">

                    <div class="alert alert-success d-none" id="msg_div">
                        <span id="res_message"></span>
                    </div>
                    <div class="form-group">
                        <label style="margin: 0px 0px 15px 0px; color: #3c3c3c;">Rating</label><br>
                        <select name="rating" id="combostar" class="form-control required" required="">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="content" id="parent_comment" rows="6" placeholder="{{__("message.placeholder_comment")}}"></textarea>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                    <!-- <input type="hidden" name="page_id" id="page_id" value="{{Request::segment(3)}}"> -->
                    <input type="hidden" name="page_id" id="page_id" value="{{$event->id}}">
                    <input type="hidden" name="page_name" id="page_name" value="{{Request::segment(1)}}">

                    <input type="hidden" name="directory_id" id="directory_id" value="{{$event->directory_id??0}}">

                    <div class="form-group">
                        <button type="submit" onclick="parent_comment_add()" class="btn_1 add_bottom_15">{{__("message.submit_button")}}</button>
                    </div>
                </div>
                <?php //} ?>
            </div>

            <aside class="col-lg-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>{{__("message.latest_post")}}</h4>
                    </div>
                    <ul class="comments-list">
                        <?php foreach($eventsLatest as $row){ ?>
                        <li>
                            <div class="alignleft">
                                <a href="{{ url('event/details/'.$row->slug??'') }}">
                                    <img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" alt="...">
                                </a>
                            </div>
                            <small>Hotel - <?php echo date('d M Y', strtotime($row->updated_at));?></small>
                            <h3>
                                <a href="{{ url('event/details/'.$row->slug??'') }}"><?php echo  substr(strip_tags($row->et_title),0,30);?>...</a>
                            </h3>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- /widget -->
                <div class="widget" style="display:none">
                    <div class="widget-title">
                        <h4>Categories</h4>
                    </div>
                    <ul class="cats">
                        <?php foreach($catData as $cat){ ?>
                        <li>
                            <a href="blog/105a3e.html?category=hotel" class=""> <?php echo $cat->name; ?> <span>(2)</span></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </aside>

            <div class="col-lg-12">
                <hr>
                <div id="comments">
                    <?php if(!empty($data['reviewData'])){ ?>
                    <h5>{{__("message.comment_text")}}</h5>
                    <ul>
                        @foreach($reviewData as $commentData)
                        <li>
                            <div class="avatar">
                                <a href="javascript: void(0)"><img src="{{ url('assets/client/images/user.png') }}" alt=""></a>
                            </div>
                            <div class="comment_right clearfix">
                                <div class="comment_info">
                                    By <span>
                                        @php
                                        $userName = Helper::getUserName($commentData->user_id);
                                        echo $userName[0]->name??'';
                                        @endphp
                                    </span>
                                    <span>|</span>
                                    <span><?php echo date('d/m/Y', strtotime($commentData->created_at));?></span>
                                    <hr>
                                    <p><?php echo $commentData->content ?></p>

                                    <p class="pb-2 pt-2" style="display: none;">
                                        <a href="javascript: void(0)" onclick="reply_comment('3')" class="float-right mb-0 mr-1"><i class="fa fa-mail-reply" aria-hidden="true"></i> Reply</a>
                                    </p>

                                    <ul class="replied-to" style="display: none;" id="comment_box_3">
                                        <li>
                                            <input type="hidden" id="under_comment_id_3" name="under_comment_id" value="3">
                                            <input type="hidden" id="blog_id_3" name="blog_id" value="2">
                                            <input type="hidden" id="blog_name_3" name="blog_name" value="HOW TO SAVE MONEY ON TRAVEL">

                                            <div class="form-group">
                                                <textarea class="form-control" name="comment" id="reply_comment_3" rows="4" placeholder="Comment"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" onclick="reply_comment_add('', '3')" id="" class="btn_1 add_bottom_15 float-right">
                                                    <i class="fa fa-mail-reply" aria-hidden="true"></i>
                                                    Reply </button>
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- </form> -->
                                    <p></p>
                                </div>
                        </li>
                        @endforeach
                    </ul>
                    <?php } ?>
                </div>

            </div>

        </div>
        <!-- /row -->
        @if($event->latitude)
        <script src="/api.js" async defer></script>
        <div id="contact_map" class="map-full map-layout single-listing-map" style="z-index: 50;">
            {{-- <iframe
            width="100%"
            height="450"
            style="border:0"
            loading="lazy"
            allowfullscreen
            src="https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}&z=16&output=embed">
            </iframe> --}}
            <iframe
                width="100%"
                height="450"
                style="border:0"
                loading="lazy"
                allowfullscreen
                src="https://www.google.com/maps?q={{ urlencode($event->location) }}&z=16&output=embed">
            </iframe>

        </div>
        @endif

    </div>
    <!-- /container -->

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
    <script>



    // Google tag event for event page view
    @if(app()->environment('production'))
        gtag('event', 'event_detail_view', {
            event_id: '{{ $event->id }}',
            entry_source: '{{ url()->previous() }}'
        });
    @endif

    $(document).ready(function() {

        $('#combostar').on('change', function() {
            $('#starcount').text($(this).val());
        });
        $('#combostar').combostars({
            starUrl: "{{ asset('assets/images/stars.png') }}",
        });

        $("#leave_comment").click(function(){
             $('.comment_box').toggle();
        });

        $("#fromdate").datepicker({
             dateFormat: 'yy-mm-dd'
        });
        $("#todate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('.ui-datepicker').addClass('notranslate');

    });

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

        function reply_comment(comment_id) {
            $('#comment_box_' + comment_id).slideToggle(500);
        };
        function reply_comment_add(user_login, comment_id) {
            var under_comment_id = $('#under_comment_id_' + comment_id).val();
            var blog_id = $('#blog_id_' + comment_id).val();
            var blog_name = $('#blog_name_' + comment_id).val();
            var comment = $('#reply_comment_' + comment_id).val();
            if (user_login == 1 && comment != '') {
                $.ajax({
                    type: 'post'
                    , url: 'https://demo.creativeitem.com/atlas/home/comment_add'
                    , data: {
                        under_comment_id: under_comment_id
                        , blog_id: blog_id
                        , blog_name: blog_name
                        , comment: comment
                    }
                    , success: function() {
                        location.reload();
                    }
                });
            } else {
                if (user_login != 1) {
                    toastr.error('Please login first');
                } else {
                    toastr.error('Write something in the comment box');
                }
            }
        }

        function parent_comment_add() {

            var user_login = $('#user_id').val();
            var page = $('#page_name').val();
            var content = $('#parent_comment').val();
            var page_id = $('#page_id').val();
            var directory_id = $('#directory_id').val();
            var rating = $('#combostar').val();
            var page_url = window.location.href;

            if (user_login > 0 && content != '') {
                $.ajax({
                    type: 'post'
                    , url: baseUrl + '/submit-eventcomment'
                    , data: {
                        "_token": "{{ csrf_token() }}"
                        , user_login: user_login
                        , page_id: page_id
                        , page_url: page_url
                        , content: content
                        , directory_id: directory_id
                        , rating: rating
                        , page: page
                    }
                    , success: function(response) {
                        //location.reload();
                        $('#res_message').show();
                        $('#res_message').html(response.msg);
                        $('#msg_div').removeClass('d-none');
                        $('#parent_comment').val('');
                        toastr.success('Thank you for your feedback. Your comment will be visible after approval.');
                        setTimeout(function () {
                            location.reload(true);
                        }, 3000);
                    }
                });
            } else {
                if (user_login == 0) {
                     alert('Please login first!');
                     window.location.href = "{{ url('login') }}";
                    //toastr.error('Please login first');
                } else {
                    toastr.error('Write something in the comment box');
                }
            }
        }

        function edit_blog_comment(comment_id) {
            $('#edit_comment_box_' + comment_id).slideToggle(500);
        }

        function update_blog_comment(comment_id) {
            var comment = $('#edit_reply_comment_' + comment_id).val();
            if (comment != '') {
                $.ajax({
                    type: 'post'
                    , url: 'https://demo.creativeitem.com/atlas/home/update_blog_comment/' + comment_id
                    , data: {
                        comment: comment
                    }
                    , success: function() {
                        location.reload();
                    }
                });
            } else {
                toastr.error('Write something in the comment box');
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
                            $('#eventlikedtotal span').text(response.totallike);
                            //$(elem).html('<i class="fas fa-heart"></i>');
                            toastr.success('You Liked!');
                        }
                        else {
                            // $(elem).html('<i class="far fa-heart"></i>');
                            // toastr.success('You Unlike!');
                            toastr.success('Already Liked!');
                        }
                    }
                });
            /* } else if (user_login == 0) {
                toastr.error('{{__("message.please_login_first_text")}}');

            } */
        }
    </script>

    <script>
        var base_url = "{{ url('/') }}";
        $(document).ready(function() {
            $("#loader").fadeOut("slow");
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
        // function blog_search() {
        //     var searching_value = $('#searching_key').val();
        //     if (searching_value != '') {
        //         location.replace("https://demo.creativeitem.com/atlas/home/blog?search=" + searching_value);
        //     }
        // }
        // var input = document.getElementById("searching_key");
        // input.addEventListener("keyup", function(event) {
        //     // Number 13 is the "Enter" key on the keyboard
        //     if (event.keyCode === 13) {
        //         // Cancel the default action, if needed
        //         event.preventDefault();
        //         // Trigger the button element with a click
        //         document.getElementById("blog_searching_btn").click();
        //     }
        // });
    </script>
    <script>
        $(document).ready(function() {
            $("#click").click(function() {
                $('html, body').animate({
                    scrollTop: $("#comments").offset().top
                }, 2000);
            });
        });
    </script>
</main>
@endsection
