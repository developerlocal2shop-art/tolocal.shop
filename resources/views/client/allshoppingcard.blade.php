@extends('layouts.client.blog')
@section('title','Shopping Card')
@section('content')

<style>
    .stiky-map {
        top: 10px;
    }
    .pagination {
        margin: -5px 0px 12px 5px;
    }
    .sub_header_in h1 {
        margin: -3px 10px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-weight: 500;
        color: #6f787f!important;
    }
    .select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        background: #fff !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        display: block;
        padding-left: 8px;
        padding-right: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        border: 1px solid #ccc;
        margin: 5px -25px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 6px;
    }
    .acc_shop_filter #accordion.tree-stuc .area span {
        float: left;
        margin-bottom: 0;
        z-index: 20000;
    }

    #filters_col label {
        color: #333;
        font-weight: 400;
    }

    .container_check input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .parentCategory span {
        margin: 0px -25px;
    }

    .acc_shop_filter #accordion.tree-stuc .card-header {
        padding: 3px 25px;
    }

    #accordion.tree-stuc .area {
        border-left: none !important;
    }

    #accordion.tree-stuc .mb-0 > a {
        display: ruby !important;
        position: unset !important;
    }
    .theiaStickySidebar {
        position: sticky!important;
        width: auto!important;
        transform: none!important;
        max-height: 600px;
        overflow: auto;
        scrollbar-width: thin;
    }

    @media (max-width: 767px) {

        input#blog_searching_btn {
            margin: 15px 0px 30px 30px;
        }
        .theiaStickySidebar {
            max-height: 400px;
        }

        .pagination li {
            margin: 15px 0px 5px 1px;
            font-size: 10px !important;
        }

        .box_list ul li:last-child, .strip ul li:last-child {
            float: none;
            padding: 10px 0px;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            margin: -10px 0px;
        }
        .listing-div {
            flex: 0 0 50%;
            max-width: 48%;
            margin: 0px auto !important;
            padding: 5px;
        }
        .shopping_card_list_img figure {
            max-height: 300px;
            height: 221px;
        }
        .calendaricon_alignment li span {
            font-size: 10px;
        }
        .form-control {
            margin: 12px 0px -8px -1px;
        }
        .sub_header_in.sticky_header {
            margin-top: 34px;
            padding-bottom: 10px;
        }

    }

</style>

<div class="sub_header_in sticky_header">
    <div class="container-fluid">
        <a href="{{ url('/shoppingcard') }}">
            <h1><i class="fa fa-hand-point-right"></i> Shopping cards @if(!empty($_GET['searchtext']))
                <span style="font-size: 12px;">/</span> {{ $_GET['searchtext'] }}@endif
            </h1>
        </a>
    </div>
</div>

@php
$currentfullurlfilter = url()->full();
$currenturlafterfirstval = explode("?",$currentfullurlfilter);
$currenturlafterfirstvalvariable = explode("=",$currenturlafterfirstval[1]??'');

$filterfirstvalue = $currenturlafterfirstvalvariable[0];
$countvalueofparam = count($currenturlafterfirstval);
$currenturl = url()->current();
$currenturl = explode("/",$currenturl);
$arrendvalue = end($currenturl);

$params = ['cat' => [], 'subcat' => [], 'tags' => []];
$categoryUrls = []; $subcategoryUrls = []; $listingtagsUrls = []; $amenityUrls = [];$trademarkUrls = [];
if (isset($_SERVER['QUERY_STRING'])) {
$query = explode('&', $_SERVER['QUERY_STRING']);
} else {
$query = [];
}
if (!empty($query[0])) {
foreach ($query as $param) {
list($name, $value) = explode('=', $param);
$params[urldecode($name)][] = urldecode($value);
}
}
if (!empty($params['cat'])) {
$categoryUrls = $params['cat'];
}
if (!empty($params['subcat'])) {
$subcategoryUrls = $params['subcat'];
}
if (!empty($params['tags'])) {
$listingtagsUrls = $params['tags'];
}
@endphp

<form id="latlongform" action="{{route('pages')}}" enctype="multipart/form-data" method="get">
    @csrf
    <input type="hidden" id="lati" name="lati">
    <input type="hidden" id="long" name="long">
</form>

<div class="container-fluid margin_60_35">
    <div class="widget search_blog">
        <div class="row form-group justify-content-md-center">

            <div class="col-lg-3 col-md-5">

            </div>

            <div class="col-md-9">
                <form action="{{ route('shoppingcard') }}" method="get" class="" id="searchEvent_data"
                    enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-3">
                            <input type="text" name="search_string" style="display: unset; "
                                value="@if(!empty($_GET['search_string'])){{ $_GET['search_string'] }}@endif"
                                id="search_string" class="form-control" placeholder="Search text..">

                        </div>

                        <div class="col-md-3 @error('directoryId') errorClass @enderror">
                            @php $allDirectory = Helper::getAllDirectory(); @endphp
                            <select class="form-control" name="directoryId" id="directoryId">
                                <option value="0">Select company</option>
                                @foreach($allDirectory as $directoryrow)
                                <option class="notranslate" value="{{ $directoryrow->id }}" {{ request()->get('directoryId') ==
                                    $directoryrow->id ? 'selected' : '' }}>
                                    {{ $directoryrow->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1">
                            <input type="submit" id="blog_searching_btn" name="search"
                                    value="&#xf002;"
                                    style="cursor: pointer;top: 0px !important;right: 30px !important;font-family: FontAwesome">
                                </span>
                        </div>
                        <div class="col-md-1">
                        </div>

                        <div class="col-md-4 select_city_home notranslate">
                            <select class="wide2 notranslate" name="city_name" id="city_name" class="form-control" onChange="getchangeTheSessionData(this)" style="width: 200px;">

                            </select>
                        </div>



                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="row justify-content-md-center">

        <aside class="col-xl-3 order-0" id="sidebar">
            <div id="filters_col">
                <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters"
                    id="filters_col_bt">
                    {{__("message.filters_title")}}
                </a>
                <!-- Filter form starts-->
                <form class="filter-form" action="#" method="get" enctype="multipart/form-data">
                    <div class="collapse show" id="collapseFilters">

                        <input type="hidden" id="urlvalue" value="{{ $arrendvalue }}" />
                        <input type="hidden" id="searchtext_search" value="{{ request()->search??'' }}" />
                        <input type="hidden" id="cityname_search" value="{{ request()->city_name??'' }}" />

                        <input type="hidden" id="filter_cat" value="{{ request()->cat??'' }}" />
                        <input type="hidden" id="filter_subcat" value="{{ request()->subcat??'' }}" />
                        <input type="hidden" id="filter_tags" value="{{ request()->tags??'' }}" />
                        <input type="hidden" id="filter_firstvalue" value="{{ $filterfirstvalue??'' }}" />
                        <input type="hidden" id="count_valueofparam" value="{{ $countvalueofparam??'' }}" />

                        <?php

                            $categorySelectedId = Helper::procategorySelectedId($arrendvalue);
                        ?>
                        <input type="hidden" id="urlvalue-cat-directory" value="category" />
                        <input type="hidden" id="cat-directoryendvalue" value="{{$arrendvalue}}" />
                        <div class="filter_type checkbox_alignment">
                            <hr>

                            @php
                            $dsubcat_vald = [];
                            foreach ($data['shoppingcard'] as $val) {
                                foreach (explode(',', $val->search_child_category) as $id) {
                                    $dsubcat_vald[] = trim($id);
                                }
                            }
                            $dsubcat_vald = array_unique($dsubcat_vald);
                            @endphp

                            <h6>{{__("message.filters_category_subtitle")}} </h6>
                            <ul class="panel-group" id="accordion">
                                <?php
                                //dd($data['parent_category']);
                                foreach($data['parent_category'] as $index =>$parentcat){
                                    if (is_object($categorySelectedId)) {
                                        $categorySelectedId_val = $categorySelectedId->id;
                                    }else{
                                        $categorySelectedId_val = 0;
                                    }
                                       // if($parentcat->id == $categorySelectedId_val){
                                ?>
                                <li class="" data-toggle="collapse" data-parent="#accordion"
                                    href="#collapse-{{ $index }}" aria-expanded="true">
                                    <div class="parentcat_filter">
                                        <span class="container_check parentCategory">
                                            <label class="">
                                                <input type="checkbox" name="category[]" class="categories sk_fade_only"
                                                    value="{{ $parentcat->id }}" onclick="filter(this)" {{
                                                    in_array($parentcat->id, $categoryUrls) ? ' checked' : '' }} >
                                                <span class="checkmark"></span>
                                            </label>
                                        </span>
                                        <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i>
                                        {{ ($parentcat->name!='')?$parentcat->name:$parentcat->name }} <small></small>
                                    </div>
                                    <?php
                                    foreach($data['subcategories'] as $subcaticon){
                                    if($subcaticon->parent_id == $parentcat->id){ ?>
                                    <i class="fa fa-caret-down down-icon" aria-hidden="true"></i>
                                    <?php
                                    } }
                                    ?>
                                </li>
                                <?php
                                foreach($data['subcategories'] as $subcat){

                                if($subcat->parent_id == $parentcat->id){
                                //if(in_array($subcat->id, $dsubcat_vald)){
                                ?>
                                    @if(in_array($parentcat->id, $categoryUrls))
                                    @php
                                    $collapse = '';
                                    @endphp
                                    @elseif(in_array($subcat->id, $subcategoryUrls))
                                    @php
                                    $collapse = '';
                                    @endphp
                                    @else
                                    @php
                                    $collapse = 'collapse';
                                    @endphp
                                    @endif
                                <li class="ml-3 panel-collapse {{ $collapse }}" id="collapse-{{ $index }}">
                                    <label class="container_check">
                                        {{ ($subcat->name!='')?$subcat->name:$subcat->name }}
                                        <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="subcategories sk_fade_only"
                                            value="{{ $subcat->id }}" onclick="filter(this)" {{ in_array($subcat->id,
                                        $subcategoryUrls) ? ' checked' : ''}}>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>

                                <?php
                                } } }
                                //}
                                ?>
                            </ul>

                        </div>
                        <?php //} ?>


                    </div>
                    <!--/collapse -->
                </form>
                <!-- filter form ends -->
            </div>
            <!--/filters col-->
        </aside>

        <div class="col-xl-6 col-md-6 order-xl-1 order-2" id="listings">

            @if ($data['shoppingcard']->count() > 0)

            <div class="row">

                <div class="col-md-6">
                    @if ($data['shoppingcard']->count() > 0)
                    <div class="d-flex notranslate">
                        <p class="text-right" style="margin: 5px -2px;font-size: 13px;">
                            Showing {{ $data['shoppingcard']->firstItem() }} - {{ $data['shoppingcard']->lastItem() }} of {{
                            $data['shoppingcard']->total() }}
                        </p>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-center">
                        {{$data['shoppingcard']->links('pagination::bootstrap-4')}}
                    </div>
                </div>

                @foreach($data['shoppingcard'] as $row)

                <!-- A Single Listing Starts-->
                <div class="col-lg-6 col-md-6 listing-div">
                    <div class="strip grid featured-tag-border shopping_card_list_img">
                        <div class="row">
                            <div class="col-md-12">
                                <figure>
                                    <?php
                                        //  $imagename = $row->image->name_thumbnail?? '';
                                        $imagename = Helper::getShoppingcardimage($row->id); ?>

                                    <?php if(!empty($imagename)){ ?>
                                    <a href="{{ url('shopping-card/detail/'.$row->slug??'') }}">
                                        <img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagename[0]->name_thumbnail.'.webp' }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php }else{ ?>
                                    <a href="{{url('shopping-card/detail/'.$row->slug??'') }}">
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php } ?>

                                </figure>
                            </div>
                            {{-- <div class="col-md-6">
                                <iframe width="100%" height="300" style="border:0" loading="lazy" allowfullscreen
                                    src="https://www.google.com/maps?q={{ $row->latitude }},{{ $row->longitude }}&z=16&output=embed">
                                </iframe>
                            </div> --}}
                        </div>

                        <div class="wrapper featured-body">
                            <h3 class="ellipsis notranslate">
                                <a href="{{ url('shopping-card/detail/'.$row->slug??'') }}">{{
                                    $row->title }}
                                </a>
                            </h3>
                            <p class="ellipsis">{{ substr(strip_tags($row->description),0,48) }}</p>
                            <small class="calendar_icon calendaricon_alignment">
                                <ul>
                                    <li>
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $row->active_date??''
                                            }}</span>
                                    </li>
                                    <li>
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $row->expire_date??''
                                            }}</span>
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
                {{$data['shoppingcard']->links('pagination::bootstrap-4')}}
            </div>
            @else
            <h5 class="ellipsis">No results found</h5>
            @endif
            <!-- custom pagination end-->
        </div>

        <div class="col-xl-3 order-xl-2 order-1">
            <div class="stiky-map mb-5 mb-xl-0">
                <div id="map" class="map-full map-layout"></div>
                <div id="marker-tooltip"></div>
            </div>
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
    .select_city_home .select2-container--default .select2-selection--single {
        border: none;
        border-left: none !important;
        text-align: left;
    }
    .search_blog .form-group span {
        position: relative;
        right: 0;
        top: -1px;
    }

    .form-group span {
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



<script type="text/javascript">

    $(document).ready(function() {

        var getcities = "{{ route('get.cities') }}";
        var token = "{{ csrf_token() }}";
        $('#city_name').select2({
            placeholder: "Search city",
            ajax: {
                type: "POST",
                url: getcities,
                dataType: 'json',
				delay:250,
                data:
                    function (params) {
                        return { search: params.term, _token:token };
                },
                processResults: function (response) {
                    console.log(response);
                    return {
                        results: response
                    };
                },cache: true
            }
        });

    });


    function filter(elem) {

        var valueurl = $('#urlvalue').val();
        var citynameSearch = $('#cityname_search').val();
        var valueurlCatPro = $('#urlvalue-cat-product').val();
        var valueurlListPro = $('#urlvalue-list-product').val();
        var valueurlCatDirectory = $('#urlvalue-cat-directory').val();
        var CatDirectoryEndValue = $('#cat-directoryendvalue').val();
        var filter_cat = $('#filter_cat').val();
        var filter_subcat = $('#filter_subcat').val();
        var filter_tags = $('#filter_tags').val();
        var filter_firstvalue = $('#filter_firstvalue').val();
        var count_value_param = $('#count_valueofparam').val();
        var fullurl = "";

        if (valueurl == "shoppingcard") {
            var urlPrefix = 'shoppingcard?';
        }
        if (valueurl == "search_string") {
            var urlPrefix = 'search_string?';
        }
        if (valueurlCatDirectory == "category") {
            var urlPrefix = CatDirectoryEndValue + '?';
        }
        var urlSuffix = "";
        var urlSuffix1 = "";
        var slectedCategories = "";
        var slectedsubCategories = "";
        var selectedOpeningStatus = "all";
        var slectedDirectoryTags = "";

        $('.categories:checked').each(function() {
            (slectedCategories === "") ? slectedCategories = $(this).attr('value'): slectedCategories = slectedCategories + "&cat=" + $(this).attr('value');
        });
        $('.subcategories:checked').each(function() {
            (slectedsubCategories === "") ? slectedsubCategories = $(this).attr('value'): slectedsubCategories = slectedsubCategories + "&subcat=" + $(this).attr('value');
        });
        $('.protags:checked').each(function() {
            (slectedDirectoryTags === "") ? slectedDirectoryTags = $(this).attr('value'): slectedDirectoryTags = slectedDirectoryTags + "&tags=" + $(this).attr('value');
        });

        selectedPriceMinRange = 0;
        selectedPriceRange = $('.price-range').val();
        if (valueurl == "shoppingcard") {

            urlSuffix = "search_string="+ $('#searchtext_search').val() +"&cat=" + slectedCategories;
            if(slectedCategories != ""){
                if (slectedCategories!="" && filter_firstvalue!="cat" && count_value_param > 1) {
                    urlSuffix1 += "&cat=" + slectedCategories;
                }
            }

            if(slectedsubCategories != ""){
                // if (slectedsubCategories!="" && filter_firstvalue!="subcat" && count_value_param > 1) {
                //     urlSuffix1 += "&subcat=" + slectedsubCategories;
                // }
                if (slectedsubCategories != "" && filter_firstvalue!="subcat" && count_value_param > 1) {
                    urlSuffix1 += "&subcat=" + slectedsubCategories;
                }else{
                    urlSuffix = "subcat=" + slectedsubCategories;
                }
            }

            // if(slectedDirectoryTags != ""){
            //     if (slectedDirectoryTags!="" && filter_firstvalue!="tags" && count_value_param > 1) {
            //         urlSuffix1 += "&tags=" + slectedDirectoryTags;
            //     }
            // }
            fullurl = urlPrefix + urlSuffix + urlSuffix1;
            fullurl1 = fullurl.split("?");
            fullurl = fullurl1[1].charAt(0);
            if(fullurl=="&"){
                var fullcorrecturl = urlPrefix + fullurl1[1].substring(1);
            }else{
                var fullcorrecturl = urlPrefix + urlSuffix + urlSuffix1;
            }
            window.location.replace(fullcorrecturl);

        } else {
            if(slectedCategories != ""){
                if (slectedCategories!="" && filter_firstvalue!="cat" && count_value_param > 1) {
                    urlSuffix1 += "&cat=" + slectedCategories;
                }else{
                    urlSuffix = "cat=" + slectedCategories;
                }
            }
            if(slectedsubCategories!=""){
                if (slectedsubCategories != "" && filter_firstvalue!="subcat" && count_value_param > 1) {
                    urlSuffix1 += "&subcat=" + slectedsubCategories;
                }else{
                    urlSuffix = "subcat=" + slectedsubCategories;
                }
            }
            if(slectedDirectoryTags!=""){

                if (slectedDirectoryTags != "" && filter_firstvalue!="tags" && count_value_param > 1) {
                    urlSuffix1 += "&tags=" + slectedDirectoryTags;
                }else{
                    urlSuffix = "tags=" + slectedDirectoryTags;
                }
            }

            fullurl = urlPrefix + urlSuffix + urlSuffix1;
            fullurl1 = fullurl.split("?");
            fullurl = fullurl1[1].charAt(0);
            if(fullurl=="&"){
                var fullcorrecturl = urlPrefix + fullurl1[1].substring(1);
            }else{
                var fullcorrecturl = urlPrefix + urlSuffix + urlSuffix1;
            }
            window.location.replace(fullcorrecturl);
        }
    }
</script>

<style>
    .collapse:not(.show) {
        display: none !important;
    }

    .down-icon {
        font-size: 16px;
        float: right;
        margin-right: -8px;
        margin-top: -33px;
    }
</style>

<script>
    function isMobileDevice() {
		return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
	}

    function showPosition() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(
            function (position) {
                var positionInfo1 = position.coords.latitude;
                var positionInfo2 = position.coords.longitude;
                document.getElementById("lati").value = positionInfo1;
                document.getElementById("long").value = positionInfo2;
                //setCookie('latitude', positionInfo1, 7);
                //setCookie('longitude', positionInfo2, 7);

                var geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(positionInfo1, positionInfo2);

                geocoder.geocode({ location: latlng }, function (results, status) {
                    var session_city = "{{ session()->get('filter_city') }}";

                    if (!session_city || session_city === 'Not found') {
                        if (status === google.maps.GeocoderStatus.OK && results) {
                            // Get city from results
                            let city;
                            for (let i = 0; i <= 2; i++) {
                                if (results[i]?.plus_code) {
                                    const cityDetails = results[i].plus_code.compound_code.split(",");
                                    city = cityDetails[0]?.split(" ")[1];
                                    break;
                                }
                            }
                            if (city) {
                                $(".select_city_home #select2-city_name-container").text(city);
                            }
                        }
                    } else {
                        $(".select_city_home #select2-city_name-container").text(session_city);
                    }
                });

                setTimeout(function () {
                    callAjax(positionInfo1, positionInfo2);
                }, 2000);

                function callAjax(lati, long) {
                    var form = $("form#latlongform");
                    $.ajax({
                        url: form.attr("action"),
                        type: "get",
                        data: { lati, long },
                        success: function (response) {
                            //console.log("Ajax response:", response);
                        },
                    });
                }
            },
            function (error) {
                console.error("Geolocation error:", error);
                //alert("Unable to retrieve location.");
            },
            { enableHighAccuracy: true } // Enable high accuracy
        );

    } else {

        console.log("Sorry, your browser does not support HTML5 geolocation.");
    }


    var locations = [
        <?php
        if(count($data['shoppingcard'])!=0){
        $mi = 1;
        $latitude = [];
        $longitude = [];
        foreach($data['shoppingcard'] as $row){
            $directoryAddress = Helper::getDirectoryLocationID($row->id);
                $latitude = $row->latitude??0;
                $longitude = $row->longitude??0;
                $address = $row->title??'';
                $title = $row->title;
                $seo_url = $row->slug;
                $distance = round($row->distance);
        ?>
        ['<a target="_blank" href="{{ url("shopping-card/detail/$seo_url") }}"> <b style="text-transform: uppercase; color:#d93025;">{{ $title }}</b><br> <p style="font-style: italic;">Distance - {{ $distance }}km</p></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],

        <?php
        $mi++;
        $newLatitude = $latitude_session;
        $newLongitude = $longitude_session;
        ?>
        ['<a href="javascript:void();"> <b style="text-transform: uppercase; color:#d93025;">My Location</b> </a>', {{ $newLatitude }}, {{ $newLongitude }}, {{ $mi }}],

        <?php
        $mi++;
        }}else{
        ?>
        <?php
            $mi = 1;
            $latitude = $latitude_session;
            $longitude = $longitude_session;
            ?>
            ['<a href="javascript:void();"><b style="text-transform: uppercase; color:#ffc108;">My Location</b></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }} ],
        <?php
        $mi++;
        }
        ?>
    ];

    window.map = new google.maps.Map(document.getElementById('map'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
    var bounds     = new google.maps.LatLngBounds();

    //for (i = 0; i < locations.length; i++) {
    $.each(locations, function(i, value) {
            var wordToFind = "My Location";
            var regex = new RegExp('\\b' + wordToFind + '\\b', 'i');
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });
            if (regex.test(value[0])) {
                var backgroundColor = "#FF0000";
                var newIcon = createMarkerIcon(backgroundColor);
                // Set the new icon for the marker
                marker.setIcon(newIcon);
                function createMarkerIcon(backgroundColor) {
                    return {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: backgroundColor,
                        fillOpacity: 1,
                        strokeWeight: 0,
                        scale: 10 // Adjust the scale as needed
                    };
                }
            }

        bounds.extend(marker.position);
        if (isMobileDevice()) {
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        } else {
            google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
                return function () {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }

    });

    var selectedPositionlatlong = new google.maps.LatLng({{ $latitude_session }},{{ $longitude_session }});// madhulika for focus on the right map based on the selected location
    map.fitBounds(bounds);
    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(14);
        map.setCenter(selectedPositionlatlong);
        google.maps.event.removeListener(listener);
    });
    }
    window.onload = showPosition;
</script>

@endsection
