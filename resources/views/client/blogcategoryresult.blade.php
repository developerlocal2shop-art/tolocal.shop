@extends('layouts.client.blog')
@section('title','Post | Category ')
@section('content')

<div class="sub_header_in sticky_header">
    <?php 
	$blogData =  $data['blogData'];
	$catData =  $data['catData'];
	?>
    <div class="container">
    <h1>{{__("message.post_title")}}</h1>
    </div>
</div>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-9">
                <div class="row">
                    <?php if(!empty($blogData)){ ?>
                    <?php foreach($blogData as $row){ ?>
                    <div class="col-md-6">
                        <article class="blog">
                            <figure>
                                <a href="{{ url('blog/details/'.$row->slug??'') }}">
                                    <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" alt="...">
                                    <div class="preview"><span>{{__("message.read_more")}}</span></div>
                                </a>
                            </figure>
                            <div class="post_info">
                                <small><?php echo $row->category_name;?> - <?php echo date('d M Y', strtotime($row->updated_at));?></small>
                                <h2><a href="{{ url('blog/details/'.$row->slug) }}"><?php echo strip_tags($row->title); ?></a></h2>
                                <p><?php echo  substr(strip_tags($row->content),0,200);?> 
                                <!-- <a class="" href="{{ url('blog/details/'.$row->slug) }}">Read more</a> -->
                                @if(strlen($row->content) > 200 && $row->content)
                                    <a class="" href="{{ url('blog/details/'.$row->slug??'') }}">{{__("message.read_more")}}</a>
                                @endif
                                </p>
                                <ul>
                                    <!-- <li>
                                        <div class="thumb">
                                            <img src="assets/client/images/user.png" alt="">
                                        </div>John Doe
                                    </li> -->
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
                                    <!-- <li><i class="fa fa-comment-o" aria-hidden="true"></i>6</li> -->
                                </ul>
                            </div>
                        </article>
                    </div>
                    <?php }?>
                    <?php }?>
                </div>
                <!-- /row -->
            </div>
            <aside class="col-lg-3">
                <!-- <div class="widget search_blog">
                    <label>Title, Description, Category</label>
                    <div class="form-group">
                        <input type="text" name="search" value="" id="searching_key" class="form-control" placeholder="Search..">
                        <span><input type="submit" id="blog_searching_btn" onclick="blog_search()" value="Search" style="cursor: pointer;"></span>
                    </div>
                </div> -->
                @if($row->category_slug??'')
                <div class="widget search_blog">
                    <label>{{__("message.title_search")}}, {{__("message.description_title")}}</label>
                    <form action="{{ route('blogcat.indexsearch', $row->category_slug??'') }}" method="get" class="" id="searchBlog_data">
                    
                        <div class="form-group">
                            <input type="text" name="searchtext" value="{{ $blogsearch }}" id="searching_key" class="form-control" placeholder="{{__("message.placeholder_search")}}">
                            <span><input type="submit" id="blog_searching_btn" name="search" value="{{__("message.header_search_text_search_btn")}}" style="cursor: pointer;"></span>
                        </div>
                    </form>
                </div>
                @endif
                <!-- /widget -->
                <div class="widget">
                    @if(!empty($blogData))
                    <div class="widget-title">
                        <h4>Latest post</h4>
                    </div>
                    <ul class="comments-list">
                        <?php foreach($blogData as $row){ ?>
                        <li>
                            <div class="alignleft">
                                <a href="{{ url('blog/details/'.$row->slug) }}">
                                    <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" alt="...">
                                </a>
                            </div>
                            <small><?php echo $row->category_name;?> - <?php echo date('d M Y', strtotime($row->updated_at));?></small>
                            <h3>
                                <a href="{{ url('blog/details/'.$row->slug) }}"><?php echo  substr(strip_tags($row->title),0,30);?>...</a>
                            </h3>
                        </li>
                        <?php } ?>
                    </ul>
                    @endif
                </div>
                <!-- /widget -->
                <div class="widget" style="display:none">
                    <div class="widget-title">
                        <h4>Categories</h4>
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
    <!-- /container -->
    <!-- <script>
        function blog_search() {
            var searching_value = $('#searching_key').val();
            if (searching_value != '') {
                location.replace("blog?search=" + searching_value);
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

    </script> -->
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
</main>

@endsection
