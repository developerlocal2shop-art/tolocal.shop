@extends('layouts.client.blog')
@section('title','Competitions')
@section('content')

<style>
    .col-md-3.select_city_home {
        padding: 0px 40px;
    }
    .strip {
        margin-bottom: 0px !important;
    }
    .competition-div {
        --competition-ink: #18212b;
        --competition-muted: #6b7280;
        --competition-accent: #c2410c;
        --competition-accent-dark: #7c2d12;
        margin-bottom: 24px;
    }

    .competition-div .strip {
        height: 100%;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(24, 33, 43, .07);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .competition-div .strip:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(24, 33, 43, .12);
    }

    .competition-div .strip > .row {
        position: relative;
        margin: 0;
        padding: 42px 12px 0;
    }

    .competition-div .comp_heading {
        position: absolute;
        top: 14px;
        left: 24px;
        right: 24px;
        z-index: 2;
        margin: 0;
        overflow: hidden;
        color: var(--competition-ink);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.3;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .competition-div .competition_company {
        display: block;
        min-height: 18px;
        margin: 0 12px 8px;
        overflow: hidden;
        color: var(--competition-muted);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .04em;
        line-height: 18px;
        text-overflow: ellipsis;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .competition-div .competition_company::before {
        display: inline-block;
        width: 6px;
        height: 6px;
        margin: 0 7px 1px 0;
        border-radius: 50%;
        background: var(--competition-accent);
        content: '';
    }

    .competition-div .competition_discount {
        position: absolute;
        top: 10px;
        right: 5px;
        z-index: 3;
        min-width: 92px;
        padding: 8px 20px 7px;
        border-radius: 5px;
        background: var(--competition-accent);
        box-shadow: 0 5px 12px rgba(194, 65, 12, .24);
        color: #fff;
        text-align: center;
        transform: rotate(-2deg);
    }

    .competition-div .competition_discount::before {
        display: block;
        margin-bottom: 2px;
        color: #fed7aa;
        content: 'SPECIAL OFFER';
        font-size: 8px;
        font-weight: 700;
        letter-spacing: .08em;
        line-height: 1;
    }

    .competition-div .competition_discount span {
        display: block;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.15;
        white-space: nowrap;
    }

    .competition-div figure {
        margin-bottom: 0;
    }

    .competition-div figure img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
    }

    .competition-div .featured-body {
        padding: 14px 18px 16px;
    }

    .competition-div .featured-body h3 {
        margin-bottom: 7px;
        color: var(--competition-ink);
    }

    .competition-div .featured-body h3 a {
        color: inherit;
    }

    .competition-div .featured-body p {
        min-height: 38px;
        margin-bottom: 10px;
        color: var(--competition-muted);
    }

    .competition-div .calendaricon_alignment {
        color: var(--competition-muted);
    }

    .competition-div .calendaricon_alignment ul {
        width: 100%;
        margin: 0;
        padding: 12px 0 0;
        border-top: 1px solid #eef0f2;
        display: flex;
        gap: 12px;
    }

    .competition-div .competition-date {
        flex: 1;
        min-width: 0;
        color: var(--competition-muted);
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .competition-div .competition-date i {
        margin-right: 4px;
        color: var(--competition-accent);
    }

    .competition-div .competition-date-value {
        display: block;
        margin: 0px 5px;
        color: var(--competition-ink);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0;
        text-transform: none;
        white-space: nowrap;
    }

    @media (max-width: 575px) {
        .competition-div .strip > .row {
            padding-right: 8px;
            padding-left: 8px;
        }

        .competition-div .competition_company {
            margin-right: 8px;
            margin-left: 8px;
        }

        .competition-div .comp_heading {
            left: 18px;
            right: 18px;
        }

        .competition-div .competition_discount {
            left: 18px;
            top: 10px;
        }
    }

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

        #searchEvent_data .row > .col-md-3:has(#search_string),
        #searchEvent_data .row > .col-md-3:has(#directoryId) {
            flex: 0 0 50%;
            max-width: 50%;
        }

        #searchEvent_data #search_string,
        #searchEvent_data #directoryId {
            display: block;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        input#blog_searching_btn {
            margin: 16px 80px;
            float: right;
        }
        .search_blog .form-group input[type="submit"] {
            padding: 5px 70px !important;
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
        <a href="{{ url('/competitions') }}">
            <h1><i class="fa fa-hand-point-right"></i> Competitions @if(!empty($_GET['searchtext']))
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

//$params = ['cat' => [], 'subcat' => [], 'tags' => []];

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

$defaultCurrency = Helper::defaultCurrencyData();
    if(session()->has('currency_code')){
        $currencySymbol = session()->get('currency_code')['currsymbol'];
        $currencycode = session()->get('currency_code')['currcode'];
    }else{
        $currencySymbol = $defaultCurrency[0]->symbol;
        $currencycode = $defaultCurrency[0]->code;
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

            <div class="col-md-12">
                <form action="{{ route('competitions') }}" method="get" class="" id="searchEvent_data"
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
                        <div class="col-md-2">
                        </div>

                        <div class="col-md-3 select_city_home notranslate">
                            <select class="wide2 notranslate" name="city_name" id="city_name" class="form-control" onChange="getchangeTheSessionData(this)" style="width: 200px;">

                            </select>
                        </div>

                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="row justify-content-md-center">


        <div class="col-xl-9 col-md-9 order-xl-1 order-2" id="listings">

            @if ($data['shoppingcard']->count() > 0)

            <div class="row">

                <div class="col-md-6">
                    @if ($data['shoppingcard']->count() > 0)
                    <div class="d-flex notranslate">
                        <p class="text-right" style="margin: 10px 10px; font-size: 13px;">
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
                <div class="col-lg-4 col-md-4 competition-div">
                    <div class="strip grid featured-tag-border shopping_card_list_img">
                        <div class="row">
                            <h4 class="comp_heading">{{ $row->title }}</h4>
                            <span class="competition_company">
                                Company: {{ $row->company->title }}
                            </span>
                            <div class="col-md-12">
                                <div class="competition_discount">
                                    <span>
                                        @if($row->discount_type == 'Percentage')
                                            {{ $row->discount_amount??'' }}% off
                                        @else
                                            {{$currencySymbol}} {{ $row->discount_amount??'' }}
                                        @endif
                                    </span>
                                </div>
                                <figure>
                                    <?php
                                    $imagename = Helper::getDirectoryimage($row->company->id); ?>
                                    <?php if(!empty($imagename)){ ?>
                                    {{-- <a href="{{ url('listings/details/'.$row->company->slug??'') }}" target="_blank"> --}}

                                        @guest
                                            <a href="{{ url('competition/detail/'.$row->competition->id??'') }}">
                                        @else
                                            <a href="{{ url('account/advertisementquestionnaire?advertisementid=' . ($row->competition->id ?? '')) }}" target="_blank">
                                        @endguest

                                        <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php }else{ ?>
                                    <a href="{{ url('account/advertisementquestionnaire?advertisementid='.$row->competition->id??'') }}" target="_blank">
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php } ?>

                                </figure>
                            </div>

                            {{-- <div class="col-md-6">

                                <figure>

                                    <?php if(!empty($row->competition->image_name)){ ?>
                                    <a href="{{ url('competition/detail/'.$row->competition->id??'') }}" target="_blank">
                                        <img src="{{ env('IMAGE_PATH') . 'files/advertisement/' . $row->competition->image_name }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php }else{ ?>
                                    <a href="{{url('competition/detail/'.$row->competition->id??'') }}" target="_blank">
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}"
                                            class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php } ?>

                                </figure>
                            </div> --}}

                        </div>

                        <div class="wrapper featured-body">
                            <h3 class="ellipsis">
                                <a href="{{ url('competition/detail/'.$row->competition->id??'') }}">
                                {{ $row->competition->heading??'' }}
                                </a>
                            </h3>
                            <p class="ellipsis">
                                {{ substr(strip_tags($row->competition->description),0,48) }}
                            </p>
                            <small class="calendar_icon calendaricon_alignment">
                                <ul>
                                    <p class="competition-date">
                                        <span>
                                            <i class="fa fa-calendar" aria-hidden="true"></i> Starts
                                            <span class="competition-date-value">
                                                {{ !empty($row->competition->start_date) ? date('d M Y', strtotime($row->competition->start_date)) : '' }}
                                            </span><br>
                                        </span>
                                    </p>

                                    <p class="competition-date">
                                        <span>
                                            <i class="fa fa-calendar" aria-hidden="true"></i> Ends
                                            <span class="competition-date-value">
                                                {{ !empty($row->competition->end_date) ? date('d M Y', strtotime($row->competition->end_date)) : '' }}
                                            </span>
                                        </span>
                                    </p>
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

        if (valueurl == "competitions") {
            var urlPrefix = 'competitions?';
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
        if (valueurl == "competitions") {

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
                    setCookie('latitude', positionInfo1, 7);
                    setCookie('longitude', positionInfo2, 7);

                    console.log("positionInfo1 :", positionInfo1);
                    console.log("positionInfo2 :", positionInfo2);

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
                    $title = $row->company->title??'';
                    $seo_url = $row->id;
                    $distance = round($row->distance);
            ?>
            ['<a target="_blank" href="{{ url("competition/detail/$seo_url") }}"> <b style="text-transform: uppercase; color:#d93025;">{{ $title }}</b><br> <p style="font-style: italic;">Distance - {{ $distance }}km</p></a>', {{ $latitude }}, {{ $longitude }}, {{ $mi }}],

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

        //console.log("Locations array:", locations);

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

@endsection
