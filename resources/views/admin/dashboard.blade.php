@extends('layouts.adminmaster')
@section('title','Dashboard')
@section('content')
@php 
$user = auth()->guard('webadmin')->user(); 
ini_set('memory_limit', '1024M');
@endphp

<style>
    .text-purple {
        color: #ffffff !important;
    }
    .white-box {
        padding: 15px;
        border-radius: 10px;
    }
    .list-inline .counter {
        font-size: 28px;
        font-weight: 100;
        color: #fff !important;
    }
    .white-box .box-title {
        font-weight: bold;
        font-size: 1.5rem;
        display: block;
        line-height: 1;
        margin: 1rem auto;
        color: #fff;
    }
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }
    .toggle.btn {
        min-height: 26px !important;
    }
    .btn-danger {
        color: #fff !important;
    }
    .pagination {
        float: right !important;
    }
    .manualreports .dashboard-filter {
        background: #f5f5f5;
        padding: 10px 0px;
        border-radius: 15px;
        border: 2px solid #ddd;
    }
    .manualreports textarea {
        border: 2px solid #004dda;
        border-radius: 0;
        background: #4473c5;
        color: #fff;
        text-align: center;
        padding: 10px !important;
        margin-bottom: 5px!important;
    }
    .manualeDateField {
        background: #ededed;
        padding: 10px 0;
        margin-bottom: 0;
    }
    .manualreports .box-title {
        background: #eeeeeb;
        padding: 10px 0px;
        border-radius: 50px;
        display: flex;
    }
    .manualreports textarea:focus {
        background: #2f4a7c;
        border: 2px solid #004dda;
        color:#fff;
    }
    .manualreports .input-group-append input.btn {
        border-radius: 0 50px 50px 0;
    }
    .manualreports .input-group-append input.btn {
        border-radius: 0 50px 50px 0;
        background: #7179da;
        border-color: #7179da;
    }
    .manualreports .box-title input[type="text"] {
        width: 80%;
        border: 2px solid #004dda;
        border-radius: 0;
        background: #4473c5;
        color: #fff;
        text-align: center;
        padding: 10px !important;
    }
    .manualreports .col-lg-12 {
        padding-left: 0;
        padding-right: 0;
    }
    .visitordashboard{
        padding: 20px 20px;
        border-radius: 4px;
        border: 2px solid #ddd;
        margin-top:30px;
    }
    .hr-visitor{
        margin:0px;
    }
    .module-title{
        font-weight: 700;
        line-height: 30px;
        font-size: 15px;
        color: #555;
    }
    .filtervisitor span.select2 {
        width: 30%;
    }
    .filtervisitor {
        padding:10px 10px 10px 10px;
    }
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if ($user->hasPermission('view') == true)
        <div class="row justify-content-center">

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ff0844), to(#ffb199)) !important;">
                    <h3 class="box-title">Total Orders</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-success">
                            
                            {{ Helper::numberformat(Helper::getTotalOrders()) }}
                        </span></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffa908), to(#ffb199)) !important;">
                    <h3 class="box-title">Total Bookings</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">
                            {{ Helper::numberformat(Helper::getTotalBookings()) }}
                        </span></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">

                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#107a4e), to(#47dd9f)) !important;">
                    <h3 class="box-title">Total Products</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter">
                            {{ Helper::numberformat(Helper::getTotalProducts()) }}</span>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="col-lg-12 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#664400), to(#ffffff)) !important">
                    <h3 class="box-title"> Total Website Traffic </h3>

                    <button class="btn" style="float: right; margin: -50px 0px;">
                        <span class="counter text-purple">
                            <i class="fa fa-eye"></i> {{ number_format($visitors) }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="dashboard-filter">
            <div class="row dashbord-option mb-4">
                <div class="col-md-2">
                    <h3 class="box-title">Filter </h3>
                </div>
                <div class="col-md-1">
                    <div class="datepickerrange" id="reportrange" style="background: #764646; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <div class="col-md-9">
                    <form action="" method="post">
                        @csrf
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            <div class="input-group mb-12">

                                <input type="hidden" name="type" value="filter">
                                <input type="text" name="fromdate" autocomplete="off" title="From Date" value="{{ session('filterregiondata')['fromdate']??'' }}" id="fromdate" class="form-control" placeholder="From date">
                                <input type="text" name="todate" autocomplete="off" title="To Date" value="{{ session('filterregiondata')['todate']??'' }}" id="todate" class="form-control" placeholder="To date">
                                <input type="type" name="countrywise" value="{{ session('filterregiondata')['countrywise']??'' }}" id="countrywise" class="form-control" placeholder="Enter country">
                                <input type="type" name="statewise" value="{{ session('filterregiondata')['statewise']??'' }}" id="statewise" class="form-control" placeholder="Enter State">
                                <input type="type" name="citywise" value="{{ session('filterregiondata')['citywise']??'' }}" id="citywise" class="form-control" placeholder="Enter City">
                                <div class="input-group-append">
                                    <input class="btn btn-primary" name="search" type="submit" value="Go!">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="row dasgboard-filter-data">
                <div class="col-lg-6 col-md-12">
                    <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#633ee7), to(#4795dd)) !important;">
                        <h3 class="box-title">Total Customers
                            <form action="{{ url('admin/all-customer') }}" method="get" enctype="multipart/form-data">
                                <input type="hidden" name="country" value="{{ session('filterregiondata')['countrywise']??'' }}" id="countrywiseres" class="form-control">
                                <input type="hidden" name="state" value="{{ session('filterregiondata')['statewise']??'' }}" id="statewiseres" class="form-control">
                                <input type="hidden" name="city" value="{{ session('filterregiondata')['citywise']??'' }}" id="citywiseres" class="form-control">
                                <button class="btn"><span class="counter text-success"> {{ Helper::numberformat($totalCustomer??'0') }}</span></button>
                            </form>
                        </h3>
                    </div>
                </div>
                

                <div class="col-lg-6 col-md-12">
                    <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#e9806d), to(#683a3a)) !important;">
                        <h3 class="box-title"> Total companies
                            <form action="{{ url('admin/all-companies') }}" method="get" enctype="multipart/form-data">
                                <input type="hidden" name="country" value="{{ session('filterregiondata')['countrywise']??'' }}" id="countrywiseres" class="form-control">
                                <input type="hidden" name="state" value="{{ session('filterregiondata')['statewise']??'' }}" id="statewiseres" class="form-control">
                                <input type="hidden" name="city" value="{{ session('filterregiondata')['citywise']??'' }}" id="citywiseres" class="form-control">
                                <button class="btn"><span class="counter text-purple">
                                     {{ Helper::numberformat($totalCompanies??'0') }}</span></button>
                            </form>
                        </h3>
                    </div>
                </div>

                

            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="title white-box visitordashboard">
                    <h3 class="box-title" style="color: #6a6666;">
                        <i class="fa fa-eye" style="color:#270a0a"></i> VISITORS
                    </h3>
                    <div class="row dashbord-option filtervisitor mb-4">
                        <div class="d-md-flex">
                            <div class="input-group mb-12">
                                <input type="hidden" name="type" value="filter_visitors">
                                @php
                                $countries = Helper::getCountryAllData();
                                @endphp
                                <select class="form-control select2" name="visitor_country" id="visitor-country">
                                    <option value="no_val">Select country</option>
                                    @foreach ($countries as $val)
                                    <option dataid="{{ $val->id }}" value="{{ $val->code }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                                <select name="visitor_state" id="visitor-state" class="form-control">
                                    <option value="no_val">Select State</option>
                                </select>
                                <input type="hidden" name="hiddenstate" id="hiddenstate">
                                <select name="visitor_city" id="visitor-city" class="form-control">
                                    <option value="no_val">Select City</option>
                                </select>
                                <input type="hidden" name="hiddenvisitorcity" id="hiddenvisitorcity">
                                <div class="input-group-append">
                                    <a class="btn btn-primary" href="javascript:void(0);" style="border-radius: 0 5px 5px 0;padding: 3px 2px;" title="Filter Data" id="filterVisitorData" onclick="filterVisitorData(this)">Go!</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title"> Company :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="directoryCountVisitor">
                            <span>
                                <i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('directory')) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="productCountVisitor">
                            <span>
                                <i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('product')) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product Offer :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="productOfferCountVisitor">
                            <span><i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('offer')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Shopping Card :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="shoppingCardCountVisitor">
                            <span>
                               <i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('shoppingcard')) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Events :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="eventCountVisitor">
                            <span><i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('event')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Blogs :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="blogCountVisitor">
                            <span><i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('blog')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                
                <div class="title white-box visitordashboard">
                    <h3 class="box-title" style="color: #6a6666;"><i class="fa fa-eye" style="color:#270a0a"></i> LIKES</h3>
                    <!-- <hr class="hr hr-visitor" /> -->
                        <div class="row dashbord-option filtervisitor mb-4">
                            
                                <div class="d-md-flex">
                                    <div class="input-group mb-12">
                                        <input type="hidden" name="type" value="filter_likes">
                                        @php
                                        $countries = Helper::getCountryAllData();
                                        @endphp
                                        <select class="form-control select2" name="like_country" id="like-country">
                                            <option value="no_val">Select country</option>
                                            @foreach ($countries as $val)
                                            <option dataid="{{ $val->id }}" value="{{ $val->code }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="like_state" id="like-state" class="form-control">
                                            <option value="no_val">Select State</option>
                                        </select>
                                        <input type="hidden" name="hiddenstate" id="hiddenstate">
                                        <select name="like_city" id="like-city" class="form-control">
                                            <option value="no_val">Select City</option>
                                        </select>
                                        <input type="hidden" name="hiddenlikecity" id="hiddenlikecity">
                                        <div class="input-group-append">
                                            <a class="btn btn-primary" href="javascript:void(0);" style="border-radius: 0 5px 5px 0;padding: 3px 2px;" title="Filter Data" id="filterLikedData" onclick="filterLikedData(this)">Go!</a>
                                        </div>
                                    </div>

                                </div>
                            
                        </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Company :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="directoryCount">
                            <span>
                                <i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('directory')) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="prductCount">
                            <span>
                                <i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('product')) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product Offer :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="product_offerCount">
                        <span>
                            <i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('product_offer')) }}
                        </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Shopping Card :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="shoppingcardCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('Shoppingcard')) }}
                        </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Events :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="eventCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('event')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Blogs :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="blogCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i>  {{ Helper::numberformat(Helper::getTotalLikesByType('blog')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endif


</div>

</div>

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->


<script type="text/javascript">

    $(document).ready(function() {

        $("#like-country").select2({
            closeOnSelect: true
        });
        $("#like-state").select2({
            closeOnSelect: true
        });
        $("#like-city").select2({
            closeOnSelect: true
        });

        $(document).on('change', "#like-country", function() {
            var text = $("#like-country option:selected").text();
            console.log(text);
            $('#hiddencountry').val(text);
            $.ajax({
                url: baseUrl + '/admin/get-states-by-country'
                , type: "POST"
                , data: {
                    country_id: $('option:selected', this).attr('dataid')
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#like-state').html('<option value="no_val">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#like-state").append('<option dataid="'+value.id+'" value="' + value.name + '" >' + value.name + '</option>');
                    });
                    $('#like-city').html('<option value="no_val">Select State First</option>');
                }
            });
        });

        $(document).on('change', "#like-state", function() {
            var text = $("#like-state option:selected").text();
            $('#hiddenstate').val(text);

            $.ajax({
                url: baseUrl + '/admin/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: $('option:selected', this).attr('dataid')
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#like-city').html('<option value="no_val">Select City</option>');
                    $.each(result.cities, function(key, value) {
                        $("#like-city").append('<option dataid="'+value.id+'" value="' + value.name + '" >' + value.name + '</option>');
                    });
                }
            });
        });

    });

    function filterLikedData(elem) {
        var likedcountry = $("#like-country option:selected").val();
        var likedstate = $("#like-state option:selected").val();
        var likedcity = $("#like-city option:selected").val();
              
        $("#filterLikedData").text('wait...');
        
        $.ajax({
            type: 'POST',
            url: baseUrl + '/admin/liked-data',
            data: {
                "_token": "{{ csrf_token() }}", 
                likeCountry: likedcountry, 
                likeState: likedstate, 
                likeCity:likedcity
            }, 
            success: function(response) {

                $("#filterLikedData").text('Go!');

                $('#directoryCount span').text(response.directorycount);
                $('#prductCount span').text(response.productcount);
                $('#shoppingcardCount span').text(response.Shoppingcardcount);
                $('#eventCount span').text(response.eventcount);
                $('#product_offerCount span').text(response.product_offercount);
                $('#blogCount span').text(response.blogcount);
            }
        });
    }

    $(document).ready(function() {

        $("#visitor-country").select2({
            closeOnSelect: true
        });
        $("#visitor-state").select2({
            closeOnSelect: true
        });
        $("#visitor-city").select2({
            closeOnSelect: true
        });

        $(document).on('change', "#visitor-country", function() {
            var text = $("#visitor-country option:selected").text();
            console.log(text);
            $('#hiddencountry').val(text);
            //alert($('option:selected', this).attr('dataid'));
            $.ajax({
                url: baseUrl + '/admin/get-states-by-country'
                , type: "POST"
                , data: {
                    country_id: $('option:selected', this).attr('dataid')
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#visitor-state').html('<option value="no_val">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#visitor-state").append('<option dataid="'+value.id+'" value="' + value.name + '" >' + value.name + '</option>');
                    });
                    $('#visitor-city').html('<option value="no_val">Select State First</option>');
                }
            });
        });

        $(document).on('change', "#visitor-state", function() {
            var text = $("#visitor-state option:selected").text();
            $('#hiddenstate').val(text);

            $.ajax({
                url: baseUrl + '/admin/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: $('option:selected', this).attr('dataid')
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#visitor-city').html('<option value="no_val">Select City</option>');
                    $.each(result.cities, function(key, value) {
                        $("#visitor-city").append('<option dataid="'+value.id+'" value="' + value.name + '" >' + value.name + '</option>');
                    });
                }
            });
        });

        $(function() {
            $( "#fromdate" ).datepicker({
                dateFormat: 'yy-mm-dd'
                //defaultDate:"YYYY-MM-DD"
            });
            $( "#todate" ).datepicker({
                //defaultDate:"YYYY-MM-DD"
                dateFormat: 'yy-mm-dd'
            });
        });

    });

    function filterVisitorData(elem) {
        var visitorcountry = $("#visitor-country option:selected").val();
        var visitorstate = $("#visitor-state option:selected").val();
        var visitorcity = $("#visitor-city option:selected").val();

        $("#filterVisitorData").text('wait...');
                
        $.ajax({
            type: 'POST',
            url: baseUrl + '/admin/visitor-data',
            data: {
                "_token": "{{ csrf_token() }}", 
                visitorCountry: visitorcountry, 
                visitorState: visitorstate, 
                visitorCity: visitorcity
            }, 
            success: function(response) {
                $("#filterVisitorData").text('Go!');
                $('#directoryCountVisitor span').text(response.directoryvisitorcount);
                $('#productCountVisitor span').text(response.productvisitorcount);
                $('#shoppingCardCountVisitor span').text(response.Shoppingcardvisitorcount);
                $('#eventCountVisitor span').text(response.eventvisitorcount);
                $('#productOfferCountVisitor span').text(response.product_offervisitorcount);
                $('#blogCountVisitor span').text(response.blogvisitorcount);
            }
        });
    }

    

		$(function() {

            $(".ranges ul li").click(function() {    
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder","Please select date range");
            });

			var start = moment().subtract(29, 'days');
			var end = moment();

			function cb(start, end) {
				//$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#fromdate').val(start.format('YYYY-MM-DD'));
                $('#todate').val(end.format('YYYY-MM-DD'));
                fromdate
			}

			$('#reportrange').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
				startDate: start,
				endDate: end,
				ranges: {
				   'Today': [moment(), moment()],
				   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				   'This Month': [moment().startOf('month'), moment().endOf('month')],
				   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			}, cb);

            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder","Please select date range");

			cb(start, end);

		});

</script>
@endsection
