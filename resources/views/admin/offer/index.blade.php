@extends('layouts.adminmaster')
@section('title','Offer| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php $currenturl = url()->current();
$currenturl = explode("/",$currenturl);
if(in_array("directory-offer", $currenturl)){
    $arrendvalue = end($currenturl);
    $directoryName = Helper::getDirectory($arrendvalue);
}else{
    $arrendvalue = "";
}

?>
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
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
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Offer<?php if(!empty($directoryName)){ echo " for Directory: ".$directoryName[0]->title; } ?></h4>
                <Span></Span>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.offer.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offer</a>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <style>
            .hotel-search { margin-right: 25px; width:200px;}
        </style>
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Total Offer</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-success">
                            {{ Helper::numberformat(($offerdataCount>0)?$offerdataCount:0) }}
                        </span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Ongoing Offer</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash2">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">{{ Helper::numberformat(($totalOngoingOfferCount>0)?$totalOngoingOfferCount:0) }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Expired Offer</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash3">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-info">
                            {{ Helper::numberformat(($totalExpiredOfferCount>0)?$totalExpiredOfferCount:0) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Upcoming Offer</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash4">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">
                            {{ Helper::numberformat(($totalUpcomingOffer>0)?$totalUpcomingOffer:0) }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.offer.indexsearch') }}" method="get" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Offer List</h3>
                        </div>

                            <div class="col-md-4">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ isset($textsearch)?$textsearch:'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
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
                                    <th class="border-top-0">Price/Percentage Discount</th>
                                    <th class="border-top-0">Offer Type</th>
                                    <th class="border-top-0">Visitors</th>
                                    <th class="border-top-0">Likes</th>
                                    <th class="border-top-0">Offer Active Date</th>
                                    <th class="border-top-0">Offer Expiry Date</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($offerdata); if ($datacount>0){?>
                                @foreach($offerdata as $key => $offerdatarow)
                                    <tr>
                                        <td>
                                            <!-- <?php echo ++$i; ?> -->
                                            {{ ($offerdata->currentpage()-1) * $offerdata->perpage() + $key + 1 }}
                                            <a class="btn btn-xs" target="_blank" href="<?php echo url("offer/detail", ['id' => @$offerdatarow->slug]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                                        </td>
                                        <td>{{ $offerdatarow->title }}</td>
                                        <td>
                                            @php
                                            // if($offerdatarow->offerimage->main_image == 'main'){
                                            //     $imagename_thumb = $offerdatarow->offerimage->name_thumbnail??'';
                                            // }else{
                                            //     $imagename_thumb = '';
                                            // }
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
                                                // if($offerdatarow->discount_selectedvalue=="advertisement"){ echo "Advertisement"; }
                                                if($offerdatarow->discount_selectedvalue=="pricereduction"){ echo "Price reduction"; }
                                                if($offerdatarow->discount_selectedvalue=="pricediscount"){ echo "Price Discount"; }
                                                if($offerdatarow->discount_selectedvalue=="percentagediscount"){ echo "Percentage discount"; }
                                                if($offerdatarow->discount_selectedvalue=="valuediscount"){ echo "Value discount"; }
                                                if($offerdatarow->discount_selectedvalue=="quantitativediscount"){ echo "Quantitative discount"; }
                                                if($offerdatarow->discount_selectedvalue=="freegift"){ echo "Free gift"; }
                                                if($offerdatarow->discount_selectedvalue=="new"){ echo "New"; }
                                            ?>
                                        </td>
                                        <td style="padding-left: 25px;">{{ $offerdatarow->discount_firstvalue }}</td>
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
                                        <td>{{ $offerdatarow->offer_active_date }}</td>
                                        <td>{{ $offerdatarow-> 	offer_expire_date }}</td>
                                        <td>
                                        @if ($user->hasPermission('edit') == true)
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/offer-edit", ['id' => @$offerdatarow->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/offer-delete", ['id' => @$offerdatarow->id]); ?>">DELETE</a>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            <?php }else{ ?>
                                    <tr>
                                        <td align="center" colspan="10">No Data Found</td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $offerdata->appends($query)->links() }}
                        @else
                        {!! $offerdata->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>


@endsection
