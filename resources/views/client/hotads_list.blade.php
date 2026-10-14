@extends('layouts.client.blog')
@section('title','Hot Offer')
@section('content')

<div class="sub_header_in sticky_header">
    <div class="container">
        <a href="{{ url('/hotadslist') }}"><h1><i class="fa fa-hand-point-right"></i> Hot Ads @if(!empty($_GET['searchtext']))
        <span style="font-size: 12px;">/</span> {{ $_GET['searchtext'] }}@endif </h1></a>
    </div>
</div>
<div class="container margin_60_35">

    <div class="widget search_blog">

            <div class="row form-group justify-content-md-center">
                <div class="col-md-9">
					@if ($data['HotAdsData']->count() > 0)
					<div class="d-flex notranslate" >
						<p class="text-right" style="margin: 5px -2px;font-size: 13px;">
							Showing {{ $data['HotAdsData']->firstItem() }} - {{ $data['HotAdsData']->lastItem() }} of {{ $data['HotAdsData']->total() }}
						</p>
					</div>
					@endif
				</div>
                <div class="col-md-3">
                    {{-- <form action="{{ route('hotadslist') }}" method="get" class="" id="searchEvent_data" enctype="multipart/form-data">
                    <input type="text" name="searchtext" style="display: unset; width: 83%;" required value="@if(!empty($_GET['searchtext'])){{ $_GET['searchtext'] }}@endif" id="search_string" class="form-control" placeholder="Search text..">
                    <span class="offersubmit"><input type="submit" id="blog_searching_btn" name="search" value="&#xf002;" style="cursor: pointer;top: 1px !important;right: 17px !important;font-family: FontAwesome"></span>
                    </form> --}}
                </div>
            </div>

    </div>

    <div class="row justify-content-md-center">

        <div class="col-xl-12 col-md-12 order-xl-1 order-2" id="listings">
            @if ($data['HotAdsData']->count() > 0)
            <div class="row">
                @foreach($data['HotAdsData'] as $row)
                <!-- A Single Listing Starts-->
                <div class="col-lg-3 col-md-3 listing-div hotoffer_detail">
                    <div class="strip grid featured-tag-border offerlist_img shopping_card_list_img">
                        <figure>

                            <?php if(!empty($row->image_name)){ ?>
                                <a href="{{ url('hotads/detail/'.$row->id) }}">
                                    <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" class="img-fluid" alt="" width="400" height="266"></a>
                                <?php }else{ ?>
                                <a href="{{ url('hotads/detail/'.$row->id) }}">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266"></a>
                                <?php } ?>
                        </figure>
                        <div class="wrapper featured-body">
                            <small class="notranslate">
                                <a href="{{ route('hotads.detail',$row->id) }}" style="color: #000; font-weight: 600;">
                                {{ $row->title }}
                                </a>
                                <p>Valid until {{ \Carbon\Carbon::parse($row->end_date)->format('M j, Y') }}</p>
                            </small>

                            <small class="adsmidtext">
                                <a href="{{ $row->link }}" target="_blank">
                                    More information
                                </a>
                            </small>

                        </div>
                        <small class="notranslate">
                        {!! Str::words($row->description ?? '', 10, '...') !!}
                        </small>

                    </div>
                </div>
                <!-- A Single Listing Ends-->
                @endforeach
            </div>
            <!-- custom pagination -->
            <div class="d-flex justify-content-center">
                {{$data['HotAdsData']->links('pagination::bootstrap-4')}}
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
.shopping_card_list_img figure img {
    height: 275px;
    object-fit: cover;
}
    /* Card */
.shopping_card_list_img {
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.35s ease;
    position: relative;
}

.shopping_card_list_img:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 35px rgba(0, 0, 0, 0.15);
}

/* Image */
.shopping_card_list_img figure {
    margin: 0;
    position: relative;
}

.shopping_card_list_img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.shopping_card_list_img:hover img {
    transform: scale(1.06);
}

/* Discount Badge */
.adsprice {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
}

.p-discount {
    background: linear-gradient(135deg, #ff3b3b, #ff6b6b);
    color: #fff;
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 20px;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Body */
.featured-body {
    padding: 14px 16px;
    background: #f8f7ef; /* soft beige like your UI */
}

/* Title */
.featured-body a {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    text-decoration: none;
}

.featured-body a:hover {
    color: #ff4d4d;
}

/* Date Box */
.featured-body p {
    background: #e5e5e5;
    color: #555;
    font-size: 13px;
    padding: 8px;
    border-radius: 6px;
    margin: 8px 0;
    text-align: center;
}

/* Mid Text */
.adsmidtext a {
    font-size: 14px;
    color: #0d6efd;
    font-weight: 600;
    text-decoration: none;
}

.adsmidtext a:hover {
    text-decoration: underline;
}

/* Bottom Text */
.shopping_card_list_img p {
    background: #efefef;
    padding: 12px;
    font-size: 12px;
    color: #666;
    text-align: center;
    margin: 0;
    font-weight: 600;
    letter-spacing: 0.6px;
    border-top: 1px solid #e0e0e0;
}

/* Spacing */
.listing-div {
    margin-bottom: 25px;
}
small.adsmidtext {
    text-align: center;
}
/* Mobile */
@media (max-width: 768px) {
    .shopping_card_list_img img {
        height: 170px;
    }
}



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
    .strip .wrapper small {
        margin-bottom: -10px;
    }

</style>

</main>
@endsection
