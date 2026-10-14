@extends('layouts.client.blog')
@section('title','Categories')
@section('content')
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>{{__("message.all_category_text")}}</h1>
    </div>
</div>
<div class="container margin_60_35">
    <div class="row justify-content-md-center">
        <div class="col-lg-12">
            @if ($data->count() > 0)
            <div class="d-flex notranslate">
                <p class="text-right">
                    Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} Categories 
                </p>
            </div>
            @endif
        </div>
        <div class="col-md-12 order-xl-1 order-2 category_list" id="listings">
            <div class="row">
                @php
                    $latitude = $_GET['latitude']??'';
                    $longitude = $_GET['longitude']??'';
                @endphp

                @foreach($data as $directoryrow)
                <!-- @php
				$categoryName = strtolower($directoryrow->name);
				$categoryName = str_replace(' ', '-', $categoryName);
				@endphp -->
                @php
                $categoryName = Helper::getSeoUrl($directoryrow->name);
                $directoryCount = Helper::getCountRelatedDirectory($directoryrow->id, $latitude, $longitude);
                @endphp

                <!-- A Single Listing Starts-->
                
                <div class="col-lg-4 col-md-4 listing-div">
                    <div class="strip grid featured-tag-border">
                        <figure> 
                            <?php $imagename = Helper::getCategoryimage($directoryrow->upload_id); ?></a>
                            <span class="featured-tag-grid">{{ ($directoryrow->feature=="yes")?'Featured':'Non Featured';  }}</span>
                            
                            @if($directoryCount > 0)
                            <a href="<?php echo url('/listings?cat='.$directoryrow->id); ?>" class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'files/category/'.$imagename[0]->name_thumbnail }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')" <?php } ?>>
                                <div class="read_more"><span>{{__("message.watch_details_text")}}</span></div>
                            </a>
                            @else
                            <a class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'files/category/'.$imagename[0]->name_thumbnail }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')" <?php } ?>>
                                <div class="read_more"><span>No Company</span></div>
                            </a>
                            @endif

                             @php
                            $categorytype = Helper::getCategoryType($directoryrow->category_type_id);
                            @endphp
                            @if($directoryCount > 0)
                                <small>
                                    {{ $directoryCount??0 }} {{__("message.menu_text_listing")}}
                                </small>
                            @endif
                        </figure>
                        <div class="wrapper featured-body">
                            <h3 class="ellipsis">
                                @if($directoryCount > 0)
                                <a href="<?php echo url('/listings?cat='.$directoryrow->id); ?>">{{ $directoryrow->title }}</a>
                                @else
                                <a >{{ $directoryrow->title }}</a>
                                @endif
                            </h3>
                            <p class="ellipsis">{{ substr(strip_tags($directoryrow->cat_trans_description),0,48) }}</p>
                        </div>
                    </div>
                </div>
               
                <!-- A Single Listing Ends-->
                @endforeach
            </div>
            <!-- custom pagination -->
            <div class="d-flex justify-content-center">
                {{$data->links('pagination::bootstrap-4')}}
            </div>
            <!-- custom pagination end-->
        </div>

    </div>
    
</div>
<!-- /container -->
<style>

    /* Container spacing */
.category_list .row {
    margin-top: 10px;
}

/* Card */
.listing-div {
    margin-bottom: 25px;
}

.strip {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    position: relative;
}

.strip:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* Image section */
.strip figure {
    position: relative;
    height: 200px;
    margin: 0;
    overflow: hidden;
}

.strip figure .img {
    display: block;
    height: 100%;
    width: 100%;
    background-size: cover;
    background-position: center;
    transition: transform 0.4s ease;
}

.strip:hover figure .img {
    transform: scale(1.08);
}

/* Overlay Read More */
.read_more {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: 0.3s ease;
}

.read_more span {
    background: rgba(0,0,0,0.7);
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
}

.strip:hover .read_more {
    opacity: 1;
}

/* Featured Tag */
.featured-tag-grid {
    border-radius: 20px;
    font-weight: 500;
}

/* Listing Count */
.strip figure small {
    position: absolute;
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Content */
.wrapper {
    padding: 15px;
}

.wrapper h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
}

.wrapper h3 a {
    text-decoration: none;
    color: #333;
}

.wrapper h3 a:hover {
    color: #ff5a5f;
}

/* Description */
.wrapper p {
    font-size: 13px;
    color: #777;
    margin-bottom: 0;
    line-height: 1.5;
}

/* Text ellipsis */
.ellipsis {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}


/* Responsive */
@media (max-width: 768px) {
    .strip figure {
        height: 160px;
    }
}
.cust-page {
    position: relative;
}
@media (min-width: 1700px) and (max-width: 2600px) {
    .container{
    max-width: 1600px;
    }
}

   
</style>
</main>
@endsection
