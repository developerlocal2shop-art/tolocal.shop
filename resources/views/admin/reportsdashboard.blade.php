@extends('layouts.adminmaster')
@section('title','Dashboard')
@section('content')
<style>
h3.module-title {
    font-size: 18px;
    font-weight: 600;
}
.list-inline .counter {
    font-size: 24px;
    font-weight: 400;
    color: #525207 !important;
}
</style>

@php $directory_id = session('filterdata')['directory_id']??'' @endphp
@php $user = auth()->guard('webadmin')->user(); @endphp

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
        
         <form action="" method="post">
         @csrf
            <div class="row">
            <div class="col-md-2">
                <h3 class="box-title">Filter </h3>
            </div>
            <div class="col-md-2">
                <input type="hidden" name="type" value="filter">
                <select name="directory_id" id="directory_id" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                    <option value="">Select Company</option>
                    @foreach ($directory as $dval)
                    <option value="{{ $dval->id }}" @if($dval->id==$directory_id) selected @endif>{{ $dval->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <div id="reportrange" style="background: #764646; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
            <div class="col-md-7">
               
                    <div class="d-md-flex">
                        <ol class="breadcrumb ms-auto">
                            <li><a href="#" class="fw-normal"></a></li>
                        </ol>
                        <div class="input-group mb-12">

                            <input type="text" autocomplete="off" name="fromdate" value="{{ session('filterdata')['fromdate']??'' }}" id="fromdate" class="form-control" placeholder="From date">
                            <input type="text" autocomplete="off" name="todate" value="{{ session('filterdata')['todate']??'' }}" id="todate" class="form-control" placeholder="To date">
                            <div class="input-group-append">
                                <input class="btn btn-primary" name="search" type="submit" style="border-radius: 0 5px 5px 0;padding: 6px 4px;" value="Go!">
                            </div>


                        </div>

                    </div>
                
            </div>
            </div>
            </form>
        
        <br>

        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#aeaeae)) !important;">
                    <h3 class="box-title">Total Orders

                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-success">
                                    @if(!empty($allorders)){{ count($allorders) }} @else 0 @endif
                                </span>
                            </button>
                        </form>

                    </h3>

                    <ul class="list-inline two-part mb-0">

                        <span class="counter text-success">
                            @if(!empty($allorders))
                            {{ $defaultCurrency->symbol??'' }} {{$allorders->sum('total_amount')+$allorders->sum('total_tax')}}.00
                            @else 0 @endif
                        </span>

                    </ul>

                </div>
            </div>
        </div>

        
       

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#98bcb1)) !important;">
                    <h3 class="box-title">Total Processing Orders

                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[0]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-success">
                                    @if(!empty($orderStatusProcessing)){{ count($orderStatusProcessing) }} @else 0 @endif
                                </span>
                            </button>
                        </form>


                    </h3>

                    <ul class="list-inline two-part mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusProcessing))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusProcessing->sum('total_amount')+$orderStatusProcessing->sum('total_tax')}}.00
                            @else 0 @endif
                        </span>

                    </ul>



                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#a2bc98)) !important;">
                    <h3 class="box-title">
                        Total Packed Orders
                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[1]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-success">
                                    @if(!empty($orderStatusPacked)){{ count($orderStatusPacked) }} @else 0 @endif
                                </span>
                            </button>
                        </form>

                    </h3>
                    <ul class="list-inline two-part mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusPacked))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusPacked->sum('total_amount')+$orderStatusPacked->sum('total_tax')}}.00
                            @else 0.00 @endif
                        </span>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#bcb398)) !important;">
                    <h3 class="box-title">
                        Total Shipped Orders
                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[1]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-success">
                                    @if(!empty($orderStatusShipped)){{ count($orderStatusShipped) }} @else 0 @endif
                                </span>
                            </button>
                        </form>

                    </h3>
                    <ul class="list-inline two-part mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusShipped))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusShipped->sum('total_amount')+$orderStatusShipped->sum('total_tax')}}.00
                            @else 0.00 @endif
                        </span>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#fca8a8)) !important;">
                    <h3 class="box-title">
                        Total Cancelled Orders

                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[2]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-purple">
                                    @if(!empty($orderStatusCancelled)){{ count($orderStatusCancelled) }} @else 0 @endif
                                </span>
                            </button>
                        </form>
                    </h3>
                    <ul class="list-inline two-part mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusCancelled))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusCancelled->sum('total_amount')+$orderStatusCancelled->sum('total_tax')}}.00
                            @else 0.00 @endif
                        </span>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#efd178)) !important;">
                    <h3 class="box-title">Total Delivered Orders

                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[3]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-success">
                                    @if(!empty($orderStatusDelivered)){{ count($orderStatusDelivered) }} @else 0 @endif
                                </span>
                            </button>
                        </form>
                    </h3>
                    <ul class="list-inline mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusDelivered))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusDelivered->sum('total_amount')+$orderStatusDelivered->sum('total_tax')}}.00
                            @else 0.00 @endif
                        </span>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info" style="background-image: -webkit-gradient(linear, left bottom, left top, from(#ffffff), to(#5ccd6c)) !important;">
                    <h3 class="box-title">Total Refunded Orders

                        <form action="{{ url('admin/ordersfilter') }}" method="get" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                            <input type="hidden" value="{{ $orderstatus[5]->id??'' }}" name="status">
                            <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                            <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                            <button class="btn">
                                <span class="counter text-purple">
                                    @if(!empty($orderStatusRefunded)){{ count($orderStatusRefunded) }} @else 0 @endif
                                </span>
                            </button>
                        </form>
                    </h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">

                        <span class="counter text-success">
                            @if(!empty($orderStatusRefunded))
                            {{ $defaultCurrency->symbol??'' }} {{$orderStatusRefunded->sum('total_amount')+$orderStatusRefunded->sum('total_tax')}}.00
                            @else 0 @endif
                        </span>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 col-md-12">
            
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="title white-box visitordashboard">
                    <h3 class="box-title"><i class="fa fa-eye" style="color: #000"></i> TOTAL VISITORS</h3>
                    <hr class="hr hr-visitor" /> 

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
                                    <a class="btn btn-primary" href="javascript:void(0);" id="filterVisitorData" style="border-radius: 0 5px 5px 0;padding: 3px 2px;" title="Filter Data" onclick="filterVisitorData(this)">
                                        Go!
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    @if(Helper::getTotalVistitors() > 0)
                        <div class="row mt-2">
                            <div class="col-md-8 col-8">
                            <h3 class="module-title">Website :</h3>
                            </div>
                            <div class="col-md-4 col-4" id="directoryCountVisitor">
                                <span>
                                   <i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('website')) }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Company :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="directoryCountVisitor">
                            <span>
                                @if(session('filterdata'))
                                   <i class="fa fa-eye" style="color:#270a0a"></i> {{ $data['directoryvisitorData']; }}
                                @else
                                    <i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('directory')) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="productCountVisitor">
                            <span><i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('product')) }}</span>
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
                            <span><i class="fa fa-eye" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalVistitorsByType('shoppingcard')) }}</span>
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
                    <h3 class="box-title"><i class="fa fa-eye" style="color: #000;"></i> TOTAL LIKES</h3>
                     <hr class="hr hr-visitor" />

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
                                            <a class="btn btn-primary" href="javascript:void(0);" style="border-radius: 0 5px 5px 0;padding: 3px 2px;" id="filterLikedData" title="Filter Data" onclick="filterLikedData(this)">Go!</a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Company :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="directoryCount">
                            <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('directory')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="prductCount">
                            <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('product')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Product Offer :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="product_offerCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('product_offer')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Shopping Card :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="shoppingcardCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('shoppingcard')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Events :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="eventCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('event')) }}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8 col-8">
                        <h3 class="module-title">Blogs :</h3>
                        </div>
                        <div class="col-md-4 col-4" id="blogCount">
                        <span><i class="fa fa-thumbs-up" style="color:#270a0a"></i> {{ Helper::numberformat(Helper::getTotalLikesByType('blog')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif

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

             $("#filterVisitorData").text('wait...');

            var visitorcountry = $("#visitor-country option:selected").val();
            var visitorstate = $("#visitor-state option:selected").val();
            var visitorcity = $("#visitor-city option:selected").val();
                    //alert("Country : "+likedcountry);alert("State : "+likedstate);alert("likedcity : "+likedcity);
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
                    //console.log(response);
                    $('#directoryCountVisitor span').text(response.directoryvisitorcount);
                    $('#productCountVisitor span').text(response.productvisitorcount);
                    $('#shoppingCardCountVisitor span').text(response.Shoppingcardvisitorcount);
                    $('#eventCountVisitor span').text(response.eventvisitorcount);
                    $('#productOfferCountVisitor span').text(response.product_offervisitorcount);
                    $('#blogCountVisitor span').text(response.blogvisitorcount);
                }
            });
        }

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

			//cb(start, end);

		});

</script>

@endsection
