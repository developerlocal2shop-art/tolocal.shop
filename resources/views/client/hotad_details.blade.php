@extends('layouts.client.blog')
{{-- @section('title','Blog detail') --}}
@if (isset($data) && !empty($data))
    @if (!empty($data->heading))
    @section('title', $data->heading??'')
    @else
    @section('title', $data->heading??'')
    @endif
@else
    @section('title', $data->heading??'')
@endif
<style>
    video {
        opacity: 1 !important;
        max-height: 400 !important;
    }
    .hotadMoreinfo {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        font-size: 14px;
        font-weight: 600;
        color: #fff !important;
        background: linear-gradient(135deg, #171818, #f1c6c6);
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.25);
    }
    .hotadMoreinfo:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.35);
        color: #fff !important;
    }
    .hotadMoreinfo i {
        font-size: 12px;
    }
    .que_discount {
        color: #2e771e !important;
        font-size: 18px;
        text-align: left;
    }
    .add_discount {
        margin-top: 20px;
        padding: 20px;
        background: #f5f8ff;
        border-left: 5px solid #e8411a;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .que_discount {
        display: inline-block;
        font-size: 18px;
        font-weight: 600;
        color: #0b7d1e;  /* green text */
        text-decoration: none;
        background: #e6ffe8;
        padding: 12px 20px;
        border-radius: 8px;
        border: 1px solid #56c15b;
        transition: 0.3s ease-in-out;
    }
    .que_discount:hover {
        background: #56c15b;
        color: #fff;
        border-color: #3fa646;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    /* Singlepost Div Styling - Title & Description */
    .singlepost_div {
        position: relative;
    }
    .singlepost_div .col-lg-8 h1 {
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 25px 0;
        line-height: 1.3;
        letter-spacing: -0.5px;
        position: relative;
        padding-bottom: 20px;
        animation: slideInDown 0.6s ease-out;
    }
    .singlepost_div .col-lg-8 h1::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #0056b3);
        border-radius: 2px;
    }
    .singlepost_div .col-lg-8 h1:hover {
        color: #007bff;
        transition: color 0.3s ease;
    }
    .post-content {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .post-content p {
        font-size: 16px;
        line-height: 1.8;
        color: #444;
        margin-bottom: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: justify;
    }

    .post-content p:first-letter {
        font-size: 32px;
        font-weight: 700;
        color: #007bff;
        line-height: 0.8;
        float: left;
        padding-right: 8px;
    }

    .post-content p:first-of-type::first-line {
        font-weight: 600;
        color: #333;
    }

    .post-content a {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .post-content a:hover {
        color: #0056b3;
        border-bottom-color: #007bff;
    }

    .post-content strong {
        color: #1a1a1a;
        font-weight: 700;
    }

    .post-content em {
        color: #555;
        font-style: italic;
    }

    .singlepost_div .postmeta {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.08), rgba(0, 123, 255, 0.04));
        padding: 15px 20px;
        border-radius: 10px;
        border-left: 4px solid #007bff;
        margin-bottom: 25px;
    }

    .singlepost_div .postmeta ul li {
        margin-right: 25px;
        font-size: 14px;
        color: #666;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .singlepost_div .postmeta i {
        color: #007bff;
        font-size: 14px;
    }

    .singlepost_div .postmeta span {
        font-weight: 500;
    }

    /* Animations */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .singlepost_div .col-lg-8 > * {
        animation: fadeIn 0.6s ease-out;
    }

    .singlepost_div .postmeta {
        animation-delay: 0.1s;
    }

    .singlepost_div .post-content {
        animation-delay: 0.2s;
    }

    /* Media Sections Styling */
    .media-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin: 40px 0;
    }

    .video_section, .mp3_section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .video_section:hover, .mp3_section:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 0, 0, 0.08);
    }

    .video_section::before, .mp3_section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #0056b3);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .video_section:hover::before {
        opacity: 1;
    }

    .mp3_section::before {
        background: linear-gradient(90deg, #28a745, #1e7e34);
    }

    .mp3_section:hover::before {
        opacity: 1;
    }

    .media-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }

    .media-header i {
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        color: #fff;
    }

    .video_section .media-header i {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .mp3_section .media-header i {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }

    .media-content {
        position: relative;
    }

    video, audio {
        width: 100%;
        border-radius: 10px;
        background: #000;
        display: block;
    }

    video::-webkit-media-controls-panel,
    audio::-webkit-media-controls-panel {
        background-color: rgba(0, 0, 0, 0.8);
    }

    video::-webkit-media-controls-time-remaining-display,
    audio::-webkit-media-controls-time-remaining-display {
        color: #fff;
    }

    /* Custom media player styling */
    video::cue,
    audio::cue {
        background: rgba(0, 0, 0, 0.8);
    }

    .media-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 250px;
        background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
        border-radius: 10px;
        border: 2px dashed #ccc;
        color: #999;
        font-size: 14px;
        flex-direction: column;
        gap: 10px;
    }

    .media-placeholder i {
        font-size: 32px;
        color: #ddd;
        opacity: 0.6;
    }

    .video_section .media-placeholder {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05), rgba(0, 123, 255, 0.02));
        border-color: rgba(0, 123, 255, 0.2);
    }

    .mp3_section .media-placeholder {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(40, 167, 69, 0.02));
        border-color: rgba(40, 167, 69, 0.2);
    }

    .media-info {
        margin-top: 12px;
        font-size: 12px;
        color: #777;
        text-align: center;
    }

    .media-info i {
        margin-right: 5px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .media-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .video_section, .mp3_section {
            padding: 20px;
        }

        .media-header {
            font-size: 15px;
        }

        .media-header i {
            width: 32px;
            height: 32px;
            font-size: 18px;
        }
    }

    /* Animation for load */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .video_section, .mp3_section {
        animation: slideUp 0.6s ease-out;
    }

    .video_section {
        animation-delay: 0.1s;
    }

    .mp3_section {
        animation-delay: 0.2s;
    }

</style>

@section('content')
</a>
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

	$hotad =  $data;

    $getCompanySlug = [];
    if($hotad->company_id){
        $getCompanySlug = Helper::getDirectoryIdSlug($hotad->company_id);
    }
	?>
    <div class="container">
        <h1><?php echo $hotad->title;?></h1>
    </div>
</div>
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                <div class="singlepost_div row">

                    <div class="col-lg-9">
                        <h1 class="notranslate">{{ $hotad->title }}</h1>


                        <div class="postmeta">
                            <ul>
                                <li>
                                    <span>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <?php echo date('d/m/Y', strtotime($hotad->start_date));?>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <?php echo date('d/m/Y', strtotime($hotad->end_date));?>
                                    </span>
                                </li>
                                <li>
                                    <i class="fa fa-user-o" aria-hidden="true"></i>
                                    @if($hotad->company_id == 1 || $hotad->company_id == '')
                                        Admin
                                    @else

                                    @endif
                                </li>
                                <li>
                                    <i class="fa fa-globe" aria-hidden="true"></i>
                                    <a href="{{ $hotad->link??'' }}" target="_blank" class="hotadMoreinfo"> More information</a>
                                </li>
                            </ul>
                        </div>

                        <!-- /post meta -->
                        <div class="post-content notranslate" >
                            <p>{!! $hotad->description !!}</p>
                        </div>
                        <!-- /post -->
                    </div>
                    <div class="col-lg-3">

                            <figure>
                                <?php if(!empty($hotad->image_name)){ ?>
                                    <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$hotad->image_name }}" class="img-fluid" alt="" width="100%" style="max-height: 650px;">
                                <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="100%" style="max-height: 650px;">
                                <?php } ?>
                            </figure>

                    </div>
                    <div class="col-lg-12">


                        <div class="media-container">
                            @if(!empty($hotad->short_video))
                                <div class="video_section">
                                    <div class="media-header">
                                        <i class="fa fa-video-camera"></i>
                                        <span>Video</span>
                                    </div>
                                    <div class="media-content">
                                        @if(!empty($hotad->short_video))
                                            @php
                                            $videoUrl = env('IMAGE_PATH') . 'files/advertisement/videos/' . $hotad->short_video;
                                            $extension = strtolower(pathinfo($hotad->short_video, PATHINFO_EXTENSION));
                                            $mimeTypes = [ 'mp4'  => 'video/mp4', 'webm' => 'video/webm', 'mov'  => 'video/quicktime'];
                                            $mimeType = $mimeTypes[$extension] ?? 'video/mp4';
                                            @endphp
                                            <video controls playsinline>
                                                <source src="{{ $videoUrl }}" type="{{ $mimeType }}">
                                                Your browser does not support this video.
                                            </video>
                                        @else
                                            <div class="media-placeholder">
                                                <i class="fa fa-video-camera"></i>
                                                <span>No video uploaded</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!empty($hotad->short_video))
                                        <div class="media-info">
                                            <i class="fa fa-info-circle"></i>
                                            HD Video - Click to play
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(!empty($hotad->mp3_file))
                                <div class="mp3_section">
                                    <div class="media-header">
                                        <i class="fa fa-music"></i>
                                        <span>Audio</span>
                                    </div>
                                    <div class="media-content">
                                        @if(!empty($hotad->mp3_file))
                                            <audio controls>
                                                <source src="{{ env('IMAGE_PATH') . 'files/advertisement/audio/' . $hotad->mp3_file }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @else
                                            <div class="media-placeholder">
                                                <i class="fa fa-music"></i>
                                                <span>No audio file uploaded</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!empty($hotad->mp3_file))
                                        <div class="media-info">
                                            <i class="fa fa-info-circle"></i>
                                            MP3 Audio - Premium quality
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                <!-- /single-post -->
                </div>
            <!-- /aside -->
            </div>
        <!-- /row -->
    </div>
    <!-- /container -->
    <script>
        $(document).ready(function() {
            $("#click").click(function() {
                $('html, body').animate({
                    scrollTop: $("#comments").offset().top
                }, 2000);
            });

            $("#que_discount").click(function() {
                alert('Kindly log in to proceed further.');
                window.location.href = "{{ route('login') }}";
            });

        });

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


    </script>
</main>

@endsection
