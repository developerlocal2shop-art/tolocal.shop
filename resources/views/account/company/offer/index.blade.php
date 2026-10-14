@extends('layouts.client.vendor')
@section('title','Company offer')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px !important;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    #infotooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #infotooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #infotooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -7px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #infotooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #infotooltip:hover #infotooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Offer</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->
<?php $currenturl = url()->current();
$currenturl = explode("/",$currenturl);
if(in_array("directory-offer", $currenturl)){
    $arrendvalue = end($currenturl);
    $directoryName = Helper::getDirectory($arrendvalue);
}else{
    $arrendvalue = "";
}
?>
<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="page-wrapper">

                <div class="container-fluid">
                    @if(session()->get('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div><br />
                    @endif
                    <style>
                        .hotel-search {
                            margin-right: 25px;
                            width: 200px;
                        }
                    </style>
                    <div class="row top-info-offer">
                        <div class="col-lg-3 col-md-6">
                            <div class="white-box front-offer-info analytics-info">
                                <h3 class="box-title">Total Offer</h3>
                                <ul class="list-inline two-part d-flex align-items-center mb-0">
                                    <li class="ms-auto"><span class="counter text-success">
                            
                                        {{ Helper::numberformat(($offerdataCount>0)?$offerdataCount:0) }}
                                    </span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="white-box front-offer-info analytics-info">
                                <h3 class="box-title">Ongoing Offer</h3>
                                <ul class="list-inline two-part d-flex align-items-center mb-0">
                                    <li class="ms-auto"><span class="counter text-purple">
                                        {{ Helper::numberformat(($totalOngoingOfferCount>0)?$totalOngoingOfferCount:0) }}
                                    </span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="white-box front-offer-info analytics-info">
                                <h3 class="box-title">Expired Offer</h3>
                                <ul class="list-inline two-part d-flex align-items-center mb-0">
                                    <li class="ms-auto"><span class="counter text-info">
                                        {{ Helper::numberformat(($totalExpiredOfferCount>0)?$totalExpiredOfferCount:0) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="white-box front-offer-info analytics-info">
                                <h3 class="box-title">Upcoming Offer</h3>
                                <ul class="list-inline two-part d-flex align-items-center mb-0">
                                    <li class="ms-auto"><span class="counter text-purple">{{ ($totalUpcomingOffer>0)?$totalUpcomingOffer:0 }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p><strong>Note : <i class="fas fa-eye" aria-hidden="true"></i> Please delete those offers which are not in use or expire.</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">
                                <form action="{{ route('company.offer.indexsearch') }}" method="get" class="">

                                    <div class="row">
                                        <div class="col-md-3 offer-helpicon">
                                            <h3 class="box-title" style="display: flex;">Offer List
                                                @if(!empty($instruction->message))
                                                <div id="helpicon">
                                                    <span id="helpiconText">{{$instruction->message}}</span>
                                                    <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                                                </div>
                                                @endif
                                            </h3>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-md-flex">

                                                <div class="input-group mb-3">
                                                    <input type="text" name="searchtext" id="search" value="{{ isset($textsearch)?$textsearch:'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                                        <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">



                                        <?php
                                            $getFeatureUsesValue = Helper::getFeatureUsesValue('offers');
                                            if($getFeatureUsesValue <= count($offerdata)){
                                        ?>
                                        <a onclick="alert('Please upgrade member account. you can not add offers greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offer</a>
                                        <?php }else{ ?>
                                            @if ($user->hasPermission('add') == true)
                                                <a href="{{ route('company.offer.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white companyAdd">Add Offer</a>
                                            @endif
                                        <?php } ?>

                                        </div>


                                    </div>
                                </form>
                                <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">S.No</th>
                                                <th class="border-top-0">Title</th>
                                                <th class="border-top-0">Image</th>
                                                <th class="border-top-0">Discount Type</th>
                                                <th class="border-top-0">Visitors</th>
                                                <th class="border-top-0">Likes</th>
                                                <th class="border-top-0">Price/Percentage Discount</th>
                                                <th class="border-top-0">Offer Type</th>
                                                <th class="border-top-0">Offer Active date</th>
                                                <th class="border-top-0">Offer Expiry date</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; $datacount = count($offerdata); if ($datacount>0){?>
                                            @foreach($offerdata as $offerdatarow)
                                            <?php
                                            $today_date = strftime('%F');
                                            if($offerdatarow->offer_expire_date <= $today_date){ ?>
                                                <tr bgcolor="#c0c1c4">
                                            <?php }else{ ?>
                                                <tr>
                                            <?php } ?>
                                            
                                                <td>
                                                    <?php echo ++$i; ?>
                                                    <a class="btn btn-xs" target="_blank" href="<?php echo url("offer/detail", ['id' => @$offerdatarow->slug]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                                                </td>
                                                <td>{{ $offerdatarow->title }}</td>
                                                <td>
                                                    @php
                                                    $imagename_thumb = Helper::getOfferimage($offerdatarow->id);
                                                    @endphp
                                                    @if($imagename_thumb)
                                                        <div class="thumbnail">
                                                            <img src="{{ env('IMAGE_PATH').'images/offer/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" id="preview-main-img" alt="directory-img" width="50px" height="50px" />
                                                        </div>
                                                    @else
                                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                                    @endif
                                                </td>
                                                <td>
                                                    <?php
                                                        if($offerdatarow->discount_selectedvalue=="advertisement"){ echo "Advertisement"; }
                                                        if($offerdatarow->discount_selectedvalue=="pricereduction"){ echo "Price reduction"; }
                                                        if($offerdatarow->discount_selectedvalue=="pricediscount"){ echo "Price Discount"; }
                                                        if($offerdatarow->discount_selectedvalue=="percentagediscount"){ echo "Percentage discount"; }
                                                        if($offerdatarow->discount_selectedvalue=="valuediscount"){ echo "Value discount"; }
                                                        if($offerdatarow->discount_selectedvalue=="quantitativediscount"){ echo "Quantitative discount"; }
                                                        if($offerdatarow->discount_selectedvalue=="freegift"){ echo "Free gift"; }
                                                        if($offerdatarow->discount_selectedvalue=="new"){ echo "New"; }
                                                    ?>
                                                </td>
                                                <td>
                                                @if(count(Helper::CountVisitorsId('offer',$offerdatarow->id)) != 0)
                                                    {{ count(Helper::CountVisitorsId('offer',$offerdatarow->id)) }}
                                                @else
                                                {{ 0 }}
                                                @endif
                                                </td>
                                                <td>
                                                @if(count(Helper::CountLikeData('product_offer',$offerdatarow->id)) != 0)
                                                    {{ count(Helper::CountLikeData('product_offer',$offerdatarow->id)) }}
                                                @else
                                                {{ 0 }}
                                                @endif
                                                </td>
                                                <td>{{ $offerdatarow->discount_firstvalue }}</td>
                                                <td>
                                                    <?php
                                                        if($offerdatarow->offer_type_selectedvalue!=""){
                                                            if($offerdatarow->offer_type_selectedvalue=="product"){ echo "Product"; }
                                                            if($offerdatarow->offer_type_selectedvalue=="directory"){ echo "Directory"; }
                                                        }else{
                                                            echo "None";
                                                        }
                                                    ?>
                                                </td>
                                                <td>{{ $offerdatarow->offer_active_date }}</td>
                                                <td>{{ $offerdatarow->offer_expire_date }}</td>
                                                <td>
                                                @if ($user->hasPermission('edit') == true)
                                                    <a class="btn btn-primary btn-sm" href="{{ route("company.offer.edit", $offerdatarow->id) }}">EDIT</a>
                                                @endif
                                                @if ($user->hasPermission('delete') == true)
                                                    <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/product/offer-delete", ['id' => @$offerdatarow->id]); ?>">DELETE</a>
                                                @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            <?php }else{ ?>
                                            <tr>
                                                <td align="center" colspan="4">No Data Found</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center cust-page">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</main>
<script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>

<style>
    @media (max-width: 767px) {

        .d-none{
            display: unset !important;
        }

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
    #helpicon {
        top:8px;
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #helpiconText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #helpicon:hover:after {
        top: 14px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }

    #helpiconText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #helpicon:hover #helpiconText {
        visibility: visible;
        opacity: 1;
        top: 62%;
        z-index: 99;
    }
</style>
@endsection
