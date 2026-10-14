@extends('layouts.client.blog')
@section('title','Posts | our blog ')
@section('content')
</a>
<div class="loader" id="loader"></div>
<div class="sub_header_in sticky_header">
    <?php
	$blogData =  $data['blogData'];
    $blogDataLatest =  $data['blogDataLatest'];
	$catData =  $data['catData'];
	?>
    <div class="container">
        <h1>{{__("Our Posts")}}</h1>
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-9">
                <div class="widget search_blog row">
                    <div class="col-lg-6">
                        <label>{{__("message.title_search")}}, {{__("message.description_title")}}</label>
                        <form action="{{ route('blog.indexsearch') }}" method="get" class="" id="searchBlog_data">

                            <div class="form-group">
                                <input type="text" name="searchtext" value="{{ $blogsearch }}" id="searching_key" class="form-control" placeholder="{{__("message.placeholder_search")}}">
                                <span><input type="submit" id="blog_searching_btn" name="search" value="{{__("message.header_search_text_search_btn")}}" style="cursor: pointer;"></span>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">

                    </div>
                </div>
                <div class="row allblogdata">

                    <?php if(count($blogData)!=0){ ?>
                    <?php foreach($blogData as $row){ ?>
                    <div class="col-md-6" data-aos-duration="1000" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
                        <article class="blog">
                            <div class="date-comment">
                                <span class="date-time">
                                    <span class="date">{{ date('j F Y', strtotime($row->created_at)) }}</span>
                                </span>
                            </div>
                            <figure>
                                <a href="{{ url('blog/details/'.$row->slug??'') }}">
                                    <!--<img src="{{ env('IMAGE_PATH').'files/blog/1692881497gal-image-1-jpg.webp' }}" alt="...">
                                    <img src="{{ storage_path('app/public/image/'.$row->blog_image) }}" alt="...">
                                    <img src="{{ Storage::disk('images')->path('1692945705gal-image-7-jpg.webp') }}" alt="...">
                                    <img src="{{ asset('/images/blog/'.$row->blog_image.'.webp') }}" alt="..."> -->
                                    <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" alt="...">

                                    <div class="preview"><span>{{__("message.read_more")}}</span></div>
                                </a>
                            </figure>
                            <div class="post_info">
                                <small><?php echo $row->category_name;?> - Updated: <?php echo date('d M Y', strtotime($row->updated_at));?></small>
                                <h2><a href="{{ url('blog/details/'.$row->slug??'') }}" class="notranslate"><?php echo strip_tags($row->bt_title); ?></a></h2>
                                <p><span class="notranslate"><?php echo  substr(strip_tags($row->description),0,200);?> </span>
                                @if(strlen($row->description) > 200 && $row->description)
                                    <a class="" href="{{ url('blog/details/'.$row->slug??'') }}">{{__("message.read_more")}}</a>
                                @endif

                                </p>
                                <ul>
                                    <li>
                                        <div class="thumb">
                                            <img src="{{ asset('assets/client/images/user.png') }}" alt="">
                                        </div>
                                        @if($row->created_by == 1)
                                            Admin
                                        @else
                                            {{ $row->name??'' }} {{ $row->last_name??'' }}
                                        @endif

                                    </li>
                                    <li>
                                    @if(Helper::getReviewCount($row->created_by, $row->id) > 0)
                                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                                        {{  Helper::getReviewCount($row->created_by, $row->id) }}

                                    @endif
                                    </li>

                                </ul>
                            </div>
                        </article>
                    </div>
                    <?php } ?>
                    <?php }else{?>
                        <h2>{{__("message.no_record_found_text")}}</h2>
                    <?php } ?>
                </div>
                <!-- /row -->
                <!-- custom pagination -->
                <div class="d-flex notranslate">
                    {{$blogData->links('pagination::bootstrap-4')}}
                </div>
			<!-- custom pagination end-->
            </div>
            <aside class="col-lg-3">

                <!-- /widget -->
                <div class="widget">
                    <div class="widget-title">
                        <h4>{{__("message.latest_post")}}</h4>
                    </div>
                    <ul class="comments-list">
                        <?php foreach($blogDataLatest as $key => $row){ ?>
                        @if($key < 4)
                        <li data-aos-duration="1000" data-aos="zoom-in">
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
                        <h4>{{__("message.footer_text_categories")}}</h4>
                    </div>
                    <ul class="cats">
                        <?php foreach($catData as $cat){ ?>
                        <li><a href="#" class=""> <?php echo $cat->name; ?> <span>(2)</span></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- /widget -->
                <!-- /widget -->
            </aside>
            <!-- /aside -->
        </div>
        <!-- /row -->
    </div>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#loader").fadeOut("slow");

        });

        jQuery(function($) {
            $("#searchBlog_data").validate({
                rules: {
                    searchtext: {
                        required: true
                    },
                }
                , messages: {
                    searchtext: {
                        required: "Please fill or select data first!"
                    , }
                , }
                , submitHandler: function() {
                    if ($("#searchBlog_data").valid() === true) {
                        document.getElementById('searchBlog_data').submit();
                    }
                }
            });
        });

    </script>
	<style type="text/css">
		#searching_key-error {
			color: red;
		}
        span.date-time {
            top: 3px !important;
            left: 18px !important;
        }
	</style>
</main>



@endsection
