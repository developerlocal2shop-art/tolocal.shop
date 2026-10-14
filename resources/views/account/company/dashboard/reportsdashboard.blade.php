@extends('layouts.client.vendor')
@section('title','Reports Dashboard')
@section('content')
@php $user = auth()->user(); @endphp

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Reports Dashboard</h1>
        <x-client.company_account_menu />
    </div>
</div>
@php $directory_id = auth()->user()->directory_id; @endphp
<main>
    <div class="container margin_60_35">
        <div class="page-wrapper">
            <div class="container-fluid">
                @if ($user->hasPermission('view') == true)
                    <div class="row">
                        <div class="col-md-2">
                            <h3 class="box-title">Filter {{ old('directory_id') }}</h3>
                        </div>
                        <div class="col-lg-1 col-md-2">
                            <div id="reportrange" style="cursor: pointer; padding: 9px 10px; border: 1px solid #ccc; width: 100%; background: #696969; color: #fff;">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <form action="" method="post">
                                    @csrf
                                <div class="d-md-flex">
                                    <div class="input-group mb-12">
                                        <input type="hidden" name="type" value="filter">
                                        <input type="text" name="fromdate" autocomplete="off" id="fromdate" value="{{ session('filterdata')['fromdate']??'' }}" class="form-control" placeholder="From date">
                                        <input type="text" name="todate" autocomplete="off" id="todate" value="{{ session('filterdata')['todate']??'' }}" class="form-control" placeholder="To date">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <br>
                    <div class="row justify-content-center vendor_panel">
                        <div class="col-xl-12 col-sm-12">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #edf1f4 0%, #dfe5ea 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($allorders))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$allorders->sum('total_amount')+$allorders->sum('total_tax')}}.00</span>
                                        @else 0 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                    <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($allorders)){{ count($allorders) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                    Total Orders
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #eaf1eb 0%, #d3e0d8 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusPending))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusPending->sum('total_amount')+$orderStatusPending->sum('total_tax')}}.00</span>
                                        @else 0 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[0]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusPending)){{ count($orderStatusPending) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                    Total Pending Orders
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #f4f1ee 0%, #e1d7cf 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusProcessing))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusProcessing->sum('total_amount')+$orderStatusProcessing->sum('total_tax')}}.00</span>
                                        @else 0.00 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[1]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusProcessing)){{ count($orderStatusProcessing) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                    Total Processing Orders
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #eef3f7 0%, #d9e3ec 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusCancelled))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusCancelled->sum('total_amount')+$orderStatusCancelled->sum('total_tax')}}.00</span>
                                        @else 0.00 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[2]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusCancelled)){{ count($orderStatusCancelled) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                Total Cancelled Orders


                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #edf8fa 0%, #d6eaf0 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusDelivered))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusDelivered->sum('total_amount')+$orderStatusDelivered->sum('total_tax')}}.00</span>
                                        @else 0.00 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[3]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusDelivered)){{ count($orderStatusDelivered) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                    Total Delivered Orders
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-sm-6">
                            <div class="card card-body" style="background-image: linear-gradient(135deg, #f3f5f8 0%, #e0e7f0 100%) !important;">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusRefunded))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusRefunded->sum('total_amount')+$orderStatusRefunded->sum('total_tax')}}.00</span>
                                        @else 0 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[5]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusRefunded)){{ count($orderStatusRefunded) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                    Total Refunded Orders
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-4 col-sm-6">
                        <?php /* ?>
                            <div class="card card-body">
                                <div class="media mb-3 px-0 pt-0">
                                    <div class="media-body">
                                        <h5 class="dsh-semi-bold">
                                        @if(!empty($orderStatusRTO))
                                        <span class="notranslate">{{ $defaultCurrency->symbol??'' }} {{$orderStatusRTO->sum('total_amount')+$orderStatusRTO->sum('total_tax')}}.00</span>
                                        @else 0.00 @endif
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <span class="btn btn-icon btn-lg btn-primary-light">
                                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%">
                                        <span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="float-right dsh-semi-bold">
                                    <form action="{{ url('company/ordersfilter') }}" method="get" enctype="multipart/form-data">
                                        <input type="hidden" value="{{ $directory_id }}" name="directory_id">
                                        <input type="hidden" value="{{ $orderstatus[4]->id??'' }}" name="status">
                                        <input type="hidden" value="{{ session('filterdata')['fromdate']??'' }}" name="startdate">
                                        <input type="hidden" value="{{ session('filterdata')['todate']??'' }}" name="enddate">
                                        <button class="btn btn-light btn-sm" style="background: #fff !important;">
                                        @if(!empty($orderStatusRTO)){{ count($orderStatusRTO) }} @else 0 @endif
                                        </button>
                                    </form>
                                    </h6>
                                Total RTO Orders
                                </div>
                            </div>
                            <?php */ ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="title white-box visitordashboard">
                                <h3 class="vendorbox-title">TOTAL VISITORS</h3>
                                <!-- <hr class="hr hr-visitor" /> -->
                                <div class="row dashbord-option vendorfiltervisitor mb-4">
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
                                                <a class="btn btn-primary" href="javascript:void(0);" style="border-radius: 0 5px 5px 0;padding: 0.2105rem 0.55rem;" title="Filter Data" onclick="filterVisitorData(this)">Go!</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Directory :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="directoryCountVendorVisitor">
                                        <span><?php
                                        echo Helper::numberformat(Helper::getVendorVistitors($directory_id));
                                        ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Product :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="productCountVendorVisitor">
                                        <span><?php
                                        $getTotalVendorProductVistitors = Helper::getTotalVendorProductVistitors($directory_id);
                                        echo Helper::numberformat($getTotalVendorProductVistitors);
                                        ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Product Offer :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="productOfferCountVendorVisitor">
                                        <span>
                                        <?php
                                        $getTotalVendorOfferVistitors = Helper::getTotalVendorOfferVistitors($directory_id);
                                        echo Helper::numberformat($getTotalVendorOfferVistitors);
                                        ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Shopping Card :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="shoppingCardCountVendorVisitor">
                                        <span>
                                            <?php
                                                $getTotalVendorShoppingcardVistitors = Helper::getTotalVendorShoppingcardVistitors($directory_id);
                                                echo Helper::numberformat($getTotalVendorShoppingcardVistitors);
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Events :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="eventCountVendorVisitor">
                                        <span>
                                        <?php
                                            $getTotalVendorEventsVistitors = Helper::getTotalVendorEventsVistitors($directory_id);
                                            echo Helper::numberformat($getTotalVendorEventsVistitors);
                                        ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Blogs :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="blogCountVendorVisitor">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorBlogVistitors($directory_id)); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="title white-box visitordashboard">
                                <h3 class="vendorbox-title">TOTAL LIKES</h3>
                                <!-- <hr class="hr hr-visitor" /> -->
                                <div class="row dashbord-option vendorfiltervisitor mb-4">
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
                                                <a class="btn btn-primary" href="javascript:void(0);" style="border-radius: 0 5px 5px 0;padding: 0.2105rem 0.55rem;" title="Filter Data" onclick="filterLikedData(this)">Go!</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Company :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="directorylikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getVendorLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Product :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="prductlikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorProductLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Product Offer :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="product_offerlikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorOfferLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Shopping Card :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="shoppingcardlikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorShoppingcardLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Events :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="eventlikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorEventsLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8 col-8">
                                    <h3 class="module-title">Blogs :</h3>
                                    </div>
                                    <div class="col-md-4 col-4" id="bloglikeCount">
                                        <span><?php echo Helper::numberformat(Helper::getTotalVendorBlogLikes($directory_id)); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

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
        //alert($('option:selected', this).attr('dataid'));
            $.ajax({
                url: baseUrl + '/get-states-by-country'
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
                url: baseUrl + '/get-cities-by-state'
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
            //alert("Country : "+likedcountry);alert("State : "+likedstate);alert("likedcity : "+likedcity);
        $.ajax({
            type: 'POST',
            url: baseUrl + '/company/liked-data',
            data: {
                "_token": "{{ csrf_token() }}",
                likeCountry: likedcountry,
                likeState: likedstate,
                likeCity:likedcity
            },
            success: function(response) {
                //console.log(response);
                $('#directorylikeCount span').text(response.directorylikecount);
                $('#prductlikeCount span').text(response.productlikecount);
                $('#shoppingcardlikeCount span').text(response.Shoppingcardlikecount);
                $('#eventlikeCount span').text(response.eventlikecount);
                $('#product_offerlikeCount span').text(response.product_offerlikecount);
                $('#bloglikeCount span').text(response.bloglikecount);
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
                url: baseUrl + '/get-states-by-country'
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
                url: baseUrl + '/get-cities-by-state'
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

    });

    function filterVisitorData(elem) {
        var visitorcountry = $("#visitor-country option:selected").val();
        var visitorstate = $("#visitor-state option:selected").val();
        var visitorcity = $("#visitor-city option:selected").val();
            //alert("Country : "+likedcountry);alert("State : "+likedstate);alert("likedcity : "+likedcity);
        $.ajax({
            type: 'POST',
            url: baseUrl + '/company/visitor-data',
            data: {
                "_token": "{{ csrf_token() }}",
                visitorCountry: visitorcountry,
                visitorState: visitorstate,
                visitorCity:visitorcity
            },
            success: function(response) {
                //console.log(response);
                $('#directoryCountVendorVisitor span').text(response.directoryvendorvisitorcount);
                $('#productCountVendorVisitor span').text(response.productvendorvisitorcount);
                $('#shoppingCardCountVendorVisitor span').text(response.Shoppingcardvendorvisitorcount);
                $('#eventCountVendorVisitor span').text(response.eventvendorvisitorcount);
                $('#productOfferCountVendorVisitor span').text(response.product_offervendorvisitorcount);
                $('#blogCountVendorVisitor span').text(response.blogvendorvisitorcount);
            }
        });
    }


        $(document).ready(function() {

                $( "#fromdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#todate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });

            $('.ui-datepicker').addClass('notranslate');
        })

		$(function() {
            $(".ranges ul li").click(function() {
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder","Please select date range");
            });
			var start = moment().subtract(29, 'days');
			var end = moment();
			function cb(start, end) {
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

<style>
    .vendor_panel .card.card-body {
        border-radius: 10px;
        padding: 5px 20px;
    }
    h5.dsh-semi-bold {
        font-weight: bold;
        font-size: 1.5rem;
        display: block;
        line-height: 1;
        margin: 1rem auto;
        color: #605b5b;
    }
    .card-body{
        color: #605b5b
    }
    .bg-primary {
        background-color: #e0a800 !important;
    }
    .ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span {
        background: #cba416 !important;
    }
    .visitordashboard{
        padding: 20px 20px;
        border-radius: 4px;
        border: 2px solid #ddd;
        margin-top:30px;
        background:#fff;
    }
    .hr-visitor{
        margin:0px;
    }
    .module-title{
        font-weight: 600;
        line-height: 30px;
        font-size: 15px;
        color: #555;
    }
    .vendorbox-title{
        font-family: inherit;
        font-weight: 500;
        line-height: 1.2;
        font-size: 1.25rem;
    }
    .vendorfiltervisitor {
        background: #ededed;
        margin-left: 0;
        margin-right: 0;
        padding: 10px 5px 10px 5px;
        border-radius: 25px;
    }
    .vendorfiltervisitor a.btn.btn-primary {
        padding: 0rem 0.4rem!important;
    }
    .vendorfiltervisitor .d-md-flex {
        width: 100%;
        padding: 10px;
    }
    .daterangepicker {
        margin-top: -50px;
    }
</style>

@endsection
