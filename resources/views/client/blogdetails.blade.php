@extends('layouts.client.common_footer')
{{-- @section('title','Blog detail') --}}
@if (isset($metadata) && !empty($metadata))
    @if (!empty($metadata->meta_title))
    @section('title', $metadata->meta_title)
    @else
    @section('title', $data['blog']->title)
    @endif
@else
    @section('title', $data['blog']->title)
@endif

@section('content')
</a>


<div class="sub_header_in sticky_header">
    <?php
    $user_ip = $_SERVER['REMOTE_ADDR'];
    // $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ua22ser_ip"));
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

	$blog =  $data['blog'];
	$blogData =  $data['blogData'];
	$catData =  $data['catData'];
	$reviewData = $data['reviewData'];
    //echo "<pre>"; print_r($blog);die;
	?>
    <div class="container">
        <h1><?php echo $blog->bt_title;?></h1>
    </div>
</div>
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-9">
                <div class="singlepost">
                    <figure>
                        <!-- <img src="{{ env('IMAGE_PATH').'files/blog/'.$blog->blog_image }}" width="100%" alt="..." style="max-height: 650px;"> -->
                        <img src="{{ env('IMAGE_PATH').'images/blog/'.$blog->blog_image.'.webp' }}" width="100%" alt="..." style="max-height: 650px;">
                    </figure>
                    <h1>{{ $blog->bt_title }}</h1>
                    <div class="postmeta">
                        <ul>
                            <li>
                                <a href="<?php echo url("/blog/category", ['id' => @$blog->category_slug]); ?>"><i class="fa fa-folder" aria-hidden="true"></i> <?php echo $blog->category_name;?></a>
                            </li>
                            <li>
                                <span><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date('d/m/Y', strtotime($blog->created_at));?></span>
                            </li>
                            <li>
                                <input type="hidden" name="directory_id" id="directory_id" value="{{ $data['blog']->directory_id }}">
                                <input type="hidden" name="type_item" id="type_items" value="blog">
                                <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                                <input type="hidden" name="country_code" id="country_code" value="{{$countryCode}}">
                                <input type="hidden" name="state" id="state" value="{{$state}}">
                                <input type="hidden" name="city" id="city" value="{{$city}}">
                                <a href="javascript:void(0);" id="bloglikedtotal" title="Like Blog" productid="{{ $blog->id }}" onclick="addToLikeCounter(this, <?php echo $blog->id ?>)">
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                    (
                                    <span>
                                        @if(count(Helper::CountLikeData('blog',$blog->id)) != 0)
                                            {{ count(Helper::CountLikeData('blog',$blog->id)) }}
                                        @else
                                            {{ 0 }}
                                        @endif
                                    </span>
                                    ) Like
                                </a>
                            </li>
                            @if(count($reviewData) > 0)
                                <li>
                                    <a href="javascript: void(0)" id="click">
                                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                                    (<?php echo count($reviewData) ?>) {{__("message.comment_text")}}</a>
                                </li>
                            @endif
                            <li>
                                <i class="fa fa-user-o" aria-hidden="true"></i>
                                @if($blog->created_by == 1)
                                    Admin
                                @else
                                    {{ $blog->name??'' }} {{ $blog->last_name??'' }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <!-- /post meta -->
                    <div class="post-content" style="min-height: 200px;">
                        <div class="dropcaps blog_text pb-4">
                            <?php echo $blog->description;?>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <!-- /post -->
                </div>
                <!-- /single-post -->
                <div id="comments">
                    <?php if(!empty($data['reviewData'])){ ?>
                    <h5>{{__("message.comment_text")}}</h5>
                    <ul>
                        @foreach($reviewData as $commentData)
                        <li>
                            <div class="avatar">
                                <a href="javascript: void(0)"><img src="{{ env('IMAGE_PATH').'assets/client/images/user.png' }}" alt=""></a>
                            </div>
                            <div class="comment_right clearfix">
                                <div class="comment_info">
                                    @php
                                    $userName = Helper::getUserName($commentData->user_id);
                                    @endphp
                                    By <span>
                                        {{ $userName[0]->name??'Admin' }}
                                    </span>
                                    <span>|

                                    </span>
                                    <span><?php echo date('d/m/Y', strtotime($commentData->created_at));?></span>
                                    <p><?php echo $commentData->content ?></p>

                                    <p class="pb-2 pt-2" style="display: none;">
                                        <!--Reply button-->
                                        <a href="javascript: void(0)" onclick="reply_comment('3')" class="float-right mb-0 mr-1"><i class="fa fa-mail-reply" aria-hidden="true"></i> Reply</a>
                                    </p>
                                    <!--reply form-->
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
                <?php
                $userID = isset(Auth()->user()->id)?Auth()->user()->id:0;
	            if($userID>0){ ?>
                <hr>
                <div>
                    <h5>{{__("message.comment_form_title")}}</h5>
                    <div class="alert alert-success d-none" id="msg_div">
                        <span id="res_message"></span>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="content" id="parent_comment" rows="6" placeholder="{{__("message.placeholder_comment")}}"></textarea>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                    <!-- <input type="hidden" name="page_id" id="page_id" value="{{Request::segment(3)}}"> -->
                    <input type="hidden" name="page_id" id="page_id" value="{{$blog->id}}">
                    <input type="hidden" name="page_name" id="page_name" value="{{Request::segment(1)}}">

                    <div class="form-group">
                        <button type="submit" onclick="parent_comment_add()" class="btn_1 add_bottom_15" id="compBtn">
                        {{__("message.submit_button")}}
                        </button>
                    </div>
                </div>
                <?php } ?>
            </div>

            <aside class="col-lg-3">
                <div class="widget">
                    <div class="widget-title">
                        <h4>{{__("message.latest_post")}}</h4>
                    </div>
                    <ul class="comments-list">
                        <?php foreach($blogData as $key => $row){ ?>
                        @if($key < 4)
                        <li>
                            <div class="alignleft">
                                <a href="{{ url('blog/details/'.$row->slug??'') }}">
                                    <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" alt="...">
                                </a>
                            </div>
                            <small><?php echo $row->category_name;?> - <?php echo date('d M Y', strtotime($row->updated_at));?></small>
                            <h3>
                                <a href="{{ url('blog/details/'.$row->slug??'') }}"><?php echo  substr(strip_tags($row->bt_title),0,30);?>...</a>
                            </h3>
                        </li>
                        @endif
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
                            <a href="#" class=""> <?php echo $cat->name; ?> <span>(2)</span></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </aside>
            <!-- /aside -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
    <script>

        // Google tag blog for blog page view
        @if(app()->environment('production'))
            gtag('event', 'blog_detail_view', {
                blog_id: '{{ $blog->id }}',
                entry_source: '{{ url()->previous() }}'
            });
        @endif

        function reply_comment(comment_id) {
            $('#comment_box_' + comment_id).slideToggle(500);
        };
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
                        $('#bloglikedtotal span').text(response.totallike);
                        toastr.success('You Liked!');
                    }
                    else {
                        // $(elem).html('<i class="far fa-heart"></i>');
                        // toastr.success('You Unlike!');
                        toastr.success('Already Liked!');
                    }
                }
            });
        }
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

            var page_url = window.location.href;
                $('#compBtn').prop('disabled', true);
			    $('#compBtn').html('Please Wait');
            if (user_login > 0 && content != '') {

                $.ajax({
                    type: 'post',
                    url: baseUrl + '/submit-comment'
                    , data: {
                        "_token": "{{ csrf_token() }}"
                        , user_login: user_login
                        , page_id: page_id
                        , page_url: page_url
                        , content: content
                        , directory_id: directory_id
                        , page: page
                    }
                    , success: function(response) {
                        $('#compBtn').prop('disabled', false);
					    $('#compBtn').html('Submit');

                        $('#res_message').show();
                        $('#res_message').html(response.msg);
                        $('#msg_div').removeClass('d-none');
                        $('#parent_comment').val('');
                        toastr.success('Thanks for your feedback.');
                        setTimeout(function () {
                            location.reload(true);
                        }, 3000);

                    }
                });
            } else {
                $('#compBtn').prop('disabled', false);
					    $('#compBtn').html('Submit');
                if (user_login == 0) {
                    $(".toast-error").remove();
                    toastr.error('Please login first');
                } else {
                    $(".toast-error").remove();
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

        function blog_search() {
            var searching_value = $('#searching_key').val();
            if (searching_value != '') {
                location.replace("https://demo.creativeitem.com/atlas/home/blog?search=" + searching_value);
            } else {
                location.replace("blog.html");
            }
        }

        // Get the input field
        var input = document.getElementById("searching_key");

        // Execute a function when the user releases a key on the keyboard
        input.addEventListener("keyup", function(event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("blog_searching_btn").click();
            }
        });

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
