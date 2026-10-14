@extends('layouts.client.blog')
@section('title','Hot Offer')
@section('content')

<style>
@media screen and (max-width:767px){
    .hotoffer_detail {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 2px 2px 0px 2px;
        margin: 0 auto;
    }
    .strip .wrapper h3 a {
        line-height: 20px;
        font-size: 15px;
    }
    .calendaricon_alignment li span {
        font-size: 10px;
    }
    .sub_header_in.sticky_header {
        margin-top: 55px;
    }
}
</style>

<div class="sub_header_in sticky_header">
    <div class="container">
        <a href="{{ url('/hotoffer') }}"><h1><i class="fa fa-hand-point-right"></i> Hot offers @if(!empty($_GET['searchtext'])) 
        <span style="font-size: 12px;">/</span> {{ $_GET['searchtext'] }}@endif </h1></a>
    </div>
</div>
<div class="container margin_60_35">

    <div class="widget search_blog">	
        
            <div class="row form-group justify-content-md-center">
                <div class="col-md-9">
					@if ($data['hotoffer']->count() > 0)
					<div class="d-flex notranslate" >
						<p class="text-right" style="margin: 5px -2px;font-size: 13px;">
							Showing {{ $data['hotoffer']->firstItem() }} - {{ $data['hotoffer']->lastItem() }} of {{ $data['hotoffer']->total() }} 
						</p>
					</div>
					@endif
				</div>
                <div class="col-md-3">
                    <form action="{{ route('hotoffer') }}" method="get" class="" id="searchEvent_data" enctype="multipart/form-data">
                    <input type="text" name="searchtext" style="display: unset; width: 83%;" required value="@if(!empty($_GET['searchtext'])){{ $_GET['searchtext'] }}@endif" id="search_string" class="form-control" placeholder="Search text..">
                    <span class="offersubmit"><input type="submit" id="blog_searching_btn" name="search" value="&#xf002;" style="cursor: pointer;top: 1px !important;right: 17px !important;font-family: FontAwesome"></span>
                    </form>
                </div>  
            </div>
        
    </div>

    <div class="row justify-content-md-center">
        <div class="col-xl-12 col-md-12 order-xl-1 order-2" id="listings">
            @if ($data['hotoffer']->count() > 0)
            <div class="row">
                @foreach($data['hotoffer'] as $row)
                <!-- A Single Listing Starts-->
                <div class="col-lg-4 col-md-4 listing-div hotoffer_detail">
                    <div class="strip grid featured-tag-border offerlist_img shopping_card_list_img">
                        <figure>
                            <?php $imagename = Helper::getOfferimage($row->id); ?>
                            <?php if(!empty($imagename)){ ?>
                            <a href="<?php echo url('offer/detail', ['id' => $row->slug??'']); ?>">
                                <img src="{{ env('IMAGE_PATH').'images/offer/'.$imagename[0]->name_thumbnail.'.webp' }}" class="img-fluid" alt="" width="400" height="266">
                                <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                            </a>
                            <?php }else{ ?>
                            <a href="<?php echo url('offer/detail', ['id' => $row->slug??'']); ?>">
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266">
                                <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                            </a>
                            
                            <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" alt="" > -->
                            <?php } ?>
                        </figure>
                        <div class="wrapper featured-body">
                            <h3 class="ellipsis"><a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>">{{ $row->title }}</a></h3>

                            <p class="ellipsis">{{ substr(strip_tags($row->description),0,48) }}</p>
                            <small class="calendar_icon calendaricon_alignment">
                                <ul>
                                    <li>
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $row->offer_active_date??'' }}</span>
                                    </li>
                                    <li>
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $row->offer_expire_date??'' }}</span>
                                    </li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
                <!-- A Single Listing Ends-->
                @endforeach
            </div>
            <!-- custom pagination -->
            <div class="d-flex justify-content-center">
                {{$data['hotoffer']->links('pagination::bootstrap-4')}}
            </div>
            @else
            <h5 class="ellipsis">No results found</h5>
            @endif
            <!-- custom pagination end-->
        </div>
    </div>
</div>
<!-- /container -->

<style>
    .cust-page {
        position: relative;
    }
    .cust-page {
        position: relative;
    }
    .search_blog .form-group input[type="submit"] {
        position: relative;
        right: 5px;
        height: 42px;
        padding: 0px 10px;
    }
    .search_blog .form-group span {
        position: relative;
        right: -20px;
        top: -1px;
    }
    .strip ul {
        width: 85%;
        padding: 0px;
        border-top: 0px;
    }

    i.fa.fa-calendar {
        font-size: 1.25rem;
    }

</style>

</main>
@endsection
