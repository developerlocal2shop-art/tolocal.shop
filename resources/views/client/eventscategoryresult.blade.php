@extends('layouts.client.blog')
@section('title','Post | Events ')
@section('content')

<style>
	.post_info .category_infopost {
		font-weight: 762 !important;
		color: #004dda !important;
	}
</style>
<div class="sub_header_in sticky_header">
    <?php 
	$eventData =  $data['eventData'];
    $eventsLatest =  $data['eventsLatest'];
	$catData =  $data['catData'];
	?>
    <div class="container">
    <h1>Events</h1>
    </div>
</div>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-9">
                <div class="row">
                    <?php if(count($eventData)!=0){ ?>
                    <?php foreach($eventData as $row){ ?>
                    <div class="col-md-6">
                        <article class="blog eventlist">
                            <figure>
                                <a href="{{ url('event/details/'.$row->slug??'') }}">
                                    <img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" alt="...">
                                    <div class="preview"><span>{{__("message.read_more")}}</span></div>
                                </a>
                            </figure>
                            <div class="post_info">
                                <small><span class="category_infopost"><?php echo $row->category_name;?></span> - <?php echo date('d M Y', strtotime($row->start_date));?></small>
                                <h2><a href="{{ url('event/details/'.$row->slug) }}"><?php echo strip_tags($row->title); ?></a></h2>
                                <p><?php echo  substr(strip_tags($row->content),0,200);?> 
                                <!-- <a class="" href="{{ url('blog/details/'.$row->slug) }}">Read more</a> -->
                                @if(strlen($row->content) > 200 && $row->content)
                                    <a class="" href="{{ url('event/details/'.$row->slug??'') }}">{{__("message.read_more")}}</a>
                                @endif
                                </p>
                               
                            </div>
                        </article>
                    </div>
                    <?php }?>
                    <?php }else{?>
                        <h2>{{__("message.no_record_found_text")}}</h2>
				    <?php } ?>
                </div>
                <!-- /row -->
                <!-- custom pagination -->
                    <div class="d-flex notranslate">
                        {{$eventData->links()}}
                    </div>
			    <!-- custom pagination end-->

            </div>
            <aside class="col-lg-3">
               
                @if($row->category_slug??'')
                <div class="widget search_blog">
                    <label>{{__("message.title_search")}}, {{__("message.description_title")}}</label>
                    <form action="{{ route('eventcat.indexsearch', $row->category_slug??'') }}" method="get" class="" id="searchBlog_data">
                    
                        <div class="form-group">
                            <input type="text" name="fromdate" style="float: left; width: 49%;" autocomplete="off" value="{{ $eventsearchFromDate }}" id="fromdate"  class="form-control placeholder-lang-css" placeholder="From Date">
                    		<input type="text" name="todate" style="width: 50%;" autocomplete="off" value="{{ $eventsearchToDate }}" id="todate"  class="form-control placeholder-lang-css" placeholder="To Date">
                            
                            <input type="text" name="searchtext" value="{{ $eventsearch }}" id="searching_key" class="form-control" placeholder="{{__("message.placeholder_search")}}">
                            <span><input type="submit" id="blog_searching_btn" name="search" value="{{__("message.header_search_text_search_btn")}}" style="cursor: pointer;top: 48px !important;"></span>
                        </div>
                    </form>
                </div>
                @endif
                <!-- /widget -->
                <div class="widget">
                    @if(!empty($eventsLatest))
                    <div class="widget-title">
                        <h4>Latest post</h4>
                    </div>
                    <ul class="comments-list">
                        <?php foreach($eventsLatest as $row){ ?>
                        <li>
                            <div class="alignleft">
                                <a href="{{ url('event/details/'.$row->slug) }}">
                                    <img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" alt="...">
                                </a>
                            </div>
                            <small><?php echo $row->category_name;?> - <?php echo date('d M Y', strtotime($row->start_date));?></small>
                            <h3>
                                <a href="{{ url('event/details/'.$row->slug) }}"><?php echo  substr(strip_tags($row->title),0,30);?>...</a>
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
    
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
    
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
    </script>
</main>

@endsection
