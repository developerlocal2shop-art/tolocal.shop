@extends('layouts.adminmaster')
@section('title','Shopping Card| Listing')
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
                <h4 class="page-title">Shopping Card<?php if(!empty($directoryName)){ echo " for Directory: ".$directoryName[0]->title; } ?></h4>
                <Span></Span>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.shoppingcard.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Shopping Card</a>
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
                    <h3 class="box-title">Total Shopping Card</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-success">{{ ($shoppingcarddataCount>0)?$shoppingcarddataCount:0 }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Ongoing Shopping Card</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash2">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">{{ ($totalOngoingShoppingCardCount>0)?$totalOngoingShoppingCardCount:0 }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Expired Shopping Card</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash3">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-info">{{ ($totalExpiredShoppingCardCount>0)?$totalExpiredShoppingCardCount:0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Upcoming Shopping Card</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash4">
                                <canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">{{ ($totalUpcomingshoppingcard>0)?$totalUpcomingshoppingcard:0 }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.shoppingcard.indexsearch') }}" method="get" class="">

                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title">Shopping Card List</h3>
                        </div>

                        <div class="col-md-3">
                            <select name="directory_id" class="form-control mb-3" aria-label="Select Directory">
                                <option value="">All Directories</option>
                                @foreach($allDirectory as $directoryrow)
                                    <option value="{{ $directoryrow->id }}" {{ (string) request('directory_id', $arrendvalue) === (string) $directoryrow->id ? 'selected' : '' }}>
                                        {{ $directoryrow->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <div class="input-group mb-3">
                                    <input type="text"  name="searchtext" id="search" value="{{ isset($textsearch)?$textsearch:'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
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
                                    <th class="border-top-0"> Discount</th>

                                    <th class="border-top-0">Visitors</th>
                                    <th class="border-top-0">Likes</th>
                                    <th class="border-top-0">Shopping Card Active date</th>
                                    <th class="border-top-0">Shopping Card Expiry date</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($shoppingcarddata); if ($datacount>0){?>
                                @foreach($shoppingcarddata as $key => $shoppingcarddatarow)
                                    <tr>
                                        <td>
                                            <!-- <?php echo ++$i; ?> -->
                                            {{ ($shoppingcarddata->currentpage()-1) * $shoppingcarddata->perpage() + $key + 1 }}
                                            <a class="btn btn-xs" target="_blank" href="<?php echo url("shopping-card/detail", ['id' => @$shoppingcarddatarow->slug]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                                        </td>
                                        <td>{{ $shoppingcarddatarow->title }}</td>
                                        <td>
                                            @php
                                            // $imagename_thumb = $offerdatarow->offerimage->path??'';
                                            $imagename_thumb = Helper::getShoppingcardimage($shoppingcarddatarow->id);
                                            @endphp
                                            @if($imagename_thumb)
                                                <div class="thumbnail">
                                                    <img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" id="preview-main-img" alt="directory-img" width="50px" height="50px" />
                                                </div>
                                            @else
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                            @endif
                                        </td>

                                        <td>
                                            <?php
                                                // if($shoppingcarddatarow->discount_selectedvalue=="advertisement"){ echo "Advertisement"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="pricereduction"){ echo "Price reduction"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="pricediscount"){ echo "Price Discount"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="percentagediscount"){ echo "Percentage discount"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="valuediscount"){ echo "Value discount"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="quantitativediscount"){ echo "Quantitative discount"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="freegift"){ echo "Free gift"; }
                                                if($shoppingcarddatarow->discount_selectedvalue=="new"){ echo "New"; }
                                            ?>
                                        </td>
                                        <td style="padding-left: 25px;">
                                            @if($shoppingcarddatarow->discount_selectedvalue == 'percentagediscount')
                                                {{ $shoppingcarddatarow->discount_firstvalue }}% off
                                            @else
                                                kr{{ $shoppingcarddatarow->discount_firstvalue }}
                                            @endif
                                        </td>
                                        <td>
                                        @if(count(Helper::CountVisitorsId('shoppingcard',$shoppingcarddatarow->id)) != 0)
                                            {{ count(Helper::CountVisitorsId('shoppingcard',$shoppingcarddatarow->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                        </td>
                                        <td>
                                        @if(count(Helper::CountLikeData('Shoppingcard',$shoppingcarddatarow->id)) != 0)
                                            {{ count(Helper::CountLikeData('Shoppingcard',$shoppingcarddatarow->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                        </td>
                                        <td>{{ $shoppingcarddatarow->active_date }}</td>
                                        <td>{{ $shoppingcarddatarow->expire_date }}</td>
                                        <td>
                                        @if ($user->hasPermission('edit') == true)
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/shoppingcard-edit", ['id' => @$shoppingcarddatarow->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/shoppingcard-delete", ['id' => @$shoppingcarddatarow->id]); ?>">DELETE</a>
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
                        @if(isset($query))
                        {{ $shoppingcarddata->appends($query)->links() }}
                        @else
                        {!! $shoppingcarddata->links() !!}
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
