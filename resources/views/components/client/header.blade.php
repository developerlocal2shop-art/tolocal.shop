<style>
 header.header.sticky, header.header_in {
    background-color: #fff;
    border-bottom: 1px solid #ededed;
}
</style>
<div id="page">

    <header class="header menu_fixed">
        <div class="google_translate_mobile">
            <b id="google_translate_element_mobile"></b>
        </div>
        <div id="logo" class="logo_wrapper">
            <a href="{{ url('/') }}" title="ToLocal.shop Directory Listing CMS" class="logo_head_a">
                <span class="company_details_logo_head">
                    <img src="{{ asset('assets/images/adobe_icon.png') }}" height="auto" alt="" class="company_details_logo_img">
                </span>
                <img src="{{ env('IMAGE_PATH').'files/logo/'.$data['headerData']->logo_name_thumbnail }}" width="165" height="35" alt="" class="logo_normal">
                <img src="{{ asset('assets/client/images/dark_logo.webp') }}" width="165" height="35" alt="" class="logo_sticky">

            </a>
            <p id="city_name" class="logo_city_name"> {{ session()->get('filter_city') }}</p>
        </div>
        <x-Clientmenu/>
    </header>


    <main>
        <div class="row-hero">
            <?php if($data['headerData']->bannertype=="video"){ ?>
                <video autoplay muted loop id="myVideo">
                    <source src="{{ env('IMAGE_PATH').'files/banner/'.$data['headerData']->banner_name_thumbnail }}" type="video/mp4">
                </video>
            <?php } ?>
            </a>
            <section class="hero_single version_2" style="background:url({{ env('IMAGE_PATH').'files/banner/'.$data['headerData']->banner_name_thumbnail }}) center center no-repeat; background-size: cover;">
                <div class="wrapper banner_mdiv">

                    <div class="container" style="background-color: transparent; border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 20px; backdrop-filter: blur(15px); ">

                        <h3> <?php echo isset($data['headerData']->banner_title)?$data['headerData']->banner_title:""; ?> </h3>
                        <p> <?php echo isset($data['headerData']->banner_sub_title)?$data['headerData']->banner_sub_title:""; ?> </p>
                        <select class="wide" name="type" id="type_listing_pro">
                            <option class="notranslate" value="listing" selected>Companies</option>
                            <option value="product">Products </option>
                        </select>
                        <form action="{{ route('listing') }}" method="get" class="type_listing">
                            <div class="row no-gutters custom-search-input-2">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4 notranslate">
                                    <input class="typeahead form-control searchquery" type="text" id="searchquerylisting" name="search_string" placeholder='{{__("message.header_search_text")}}' autocomplete="off">
                                    {{-- <i class="fa fa-search" aria-hidden="true"></i> --}}
                                    <i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording"></i>
                                </div>
                                <div class="col-lg-2">
                                    <?php $categoryAll = Helper::getAllCategory(); ?>
                                    <select class="wide" name="cat" id="cat_list">
                                        <option value="all">{{__("message.header_search_catogory_text")}}</option>
                                        <?php foreach($categoryAll as $row){ ?>
                                        <option value="<?php echo $row->id; ?>">
                                            <?php echo $row->name; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 select_city_home notranslate">
                                    <select class="form-control wide2 notranslate" name="city_name" id="city_name" onChange="getchangeTheSessionDataHome(this)" style="width: 200px;">
                                    </select>
                                </div>
                                <div class="col-lg-2 notranslate">
                                    <input id="dir_btn" type="submit" value='{{__("message.header_search_text_search_btn")}}'>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('shop') }}" method="GET" class="type_product" style="display:none;">
                            <div class="row no-gutters custom-search-input-2">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input class="form-control searchquery search_string_product" data_value="product" name="search_string" id="search_string" type="text"
                                            value="{{ isset($search_string)?$search_string:''; }}" placeholder="{{__('message.header_search_text')}}">
                                        {{-- <i class="fa fa-search" aria-hidden="true"></i> --}}
                                        <i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording-product"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <?php $getProductCategories = Helper::getProductCategories(); ?>
                                    <select class="wide cat" name="cat" id="cat">
                                        <option value="all">{{__("message.header_search_catogory_text")}}</option>
                                        <?php foreach($getProductCategories as $parentcat){ ?>
                                        <option value="{{ $parentcat->id }}">{{ $parentcat->name }}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select class="wide" name="filters" id="filters">
                                        <option value="">Select filters</option> <option value="shoppingcard">Shopping card</option> <option value="offer">Offer</option> <option value="latestproduct">Latest Product</option> <option value="mostpopular">Most Popular</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 notranslate">
                                    <input type="submit" id="product_btn" value="{{__('message.header_search_text_search_btn')}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <!-- /hero_single -->

        <form id="latlongform" action="{{url('/')}}" enctype="multipart/form-data" method="get">
            @csrf
            <input type="hidden" id="lati" name="lati" >
            <input type="hidden" id="long" name="long" >
        </form>
