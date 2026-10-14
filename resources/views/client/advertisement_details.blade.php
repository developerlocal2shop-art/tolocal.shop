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
    .singlepost figure img {
        width: 100%;
    }
    .que_discount {
        color: #2e771e !important;
        font-size: 18px;
        text-align: left;
    }
    .singlepost figure img {
        width: 100%;
        margin: 35px 0px;
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

	$advertisement =  $data;

    $getCompanySlug = [];
    if($advertisement->directory_id){
        $getCompanySlug = Helper::getDirectoryIdSlug($advertisement->directory_id);
    }
	?>
    <div class="container">
        <h1><?php echo $advertisement->heading;?></h1>
    </div>
</div>
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                <div class="singlepost row">

                    <div class="col-lg-8">
                    <h1>{{ $advertisement->heading }}</h1>
                    <p>Discount : {{ $advertisement->price }} %</p>
                    <div class="postmeta">
                        <ul>

                            <li>
                                <span>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <?php echo date('d/m/Y', strtotime($advertisement->start_date));?>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <?php echo date('d/m/Y', strtotime($advertisement->end_date));?>
                                </span>
                            </li>

                            <li>
                                <i class="fa fa-user-o" aria-hidden="true"></i>
                                @if($advertisement->directory_id == 1 || $advertisement->directory_id == '')
                                    Admin
                                @else
                                    {{ $advertisement->directory_id??'' }} {{ $advertisement->directory_id??'' }}
                                @endif
                            </li>
                            <li>
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <a href="{{ $advertisement->link??'' }}" target="_blank"> Website</a>

                            </li>

                        </ul>
                    </div>
                    <!-- /post meta -->
                    <div class="post-content" style="min-height: 200px;">
                        <p>{{ $advertisement->description }}</p>
                        <div class="dropcaps blog_text pb-4">
                            <?php echo $advertisement->bottom_text;?>
                            <p>&nbsp;</p>
                        </div>

                    @php
                        $webUser = auth('web')->user();
                    @endphp
                    @if($webUser)
                        @if($advertisement && (!$webUser || $webUser->directory_id == 0))
                            <div class="add_discount">
                                <a href="{{ url('account/advertisementquestionnaire?advertisementid=' . $advertisement->id) }}"
                                class="que_discount">
                                    Answer the questionnaire and receive {{ $advertisement->price }}% discount on your purchase.
                                </a>
                            </div>
                            @else
                            <div class="add_discount">
                                <a href="{{ url('company/advertisement-edit/' . $advertisement->id) }}"
                                class="que_discount">
                                View participant responses for this competition and discount campaign.
                                </a>
                            </div>
                        @endif
                    @endif

                    </div>
                    <!-- /post -->
                    </div>
                    <div class="col-lg-4">
                        <b>{{ $advertisement->mid_text }}</b>
                        <figure>
                            <?php if(!empty($advertisement->image_name)){ ?>
                                <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$advertisement->image_name }}" class="img-fluid" alt="" width="100%" style="max-height: 650px;">
                            <?php }else{ ?>
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="100%" style="max-height: 650px;">
                            <?php } ?>
                        </figure>
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
