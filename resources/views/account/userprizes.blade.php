@extends('layouts.client.vendor')
@section('title','User prizes')
@section('content')
<style>
    .card h4 {
        font-size: 17px;
        font-weight: 600;
        border-bottom: 1px solid #ccc;
        padding: 5px;
    }
    .card {
        padding: 0px 20px;
        margin: 30px 10px;
        border: 1px solid #ccc;
    }
    .toggle-on.btn {
        line-height: 0px;
    }
    .toggle-handle {
        height: 140%;
    }
    .filter_sub_blue_header.newClass {
        margin: -15px -29px !important;
    }
    .user_que_ans {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
        margin-right: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        border-radius: 0;
        padding: 0px 10px;
    }
    .prize_status_for_store {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        padding: 5px 30px;
        background: #f4fcf4;
        border: 1px solid #c5e7c4;
        border-radius: 30px;
        color: #264d2f;
    }
    .prize_status_for_store .store-action-text {
        flex: 1 1 220px;
        margin-right: 12px;
        font-size: 14px;
        line-height: 1.6;
    }
    .prize_status_for_store .store-action-toggle {
        flex: 0 0 auto;
        min-width: 140px;
    }
    .prize-tabs {
        margin: 0 0 20px;
        border-bottom: 1px solid #ddd;
    }
    .prize-tabs .nav-link {
        color: #444;
        font-weight: 600;
        border: 1px solid transparent;
        border-radius: 0;
    }
    .prize-tabs .nav-link.active {
        color: #0d6efd;
        border-color: #ddd #ddd #fff;
        background: #fff;
    }

    @media (max-width: 768px) {
        .user_que_ans {
                padding: 0px;
        }
    }

    @media (max-width: 576px) {
        .prize_status_for_store {
            flex-direction: column;
            align-items: stretch;
        }
        .prize_status_for_store .store-action-text {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
</style>

@php
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
	$currencySymbol = session()->get('currency_code')['currsymbol'];
	$currencycode = session()->get('currency_code')['currcode'];
}else{
	$currencySymbol = $defaultCurrency[0]->symbol;
	$currencycode = $defaultCurrency[0]->code;
}
@endphp
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />
            <div class="col-lg-9">
                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header ">
                        <div class="container">
                            <h1>Prizes</h1>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        @if(Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                            @php
                            Session::forget('error');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-11">

                        <div class="user_que_ans">
                            <ul class="nav nav-tabs prize-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#competition-prizes" role="tab">Competition Prizes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#shoppingcard-prizes" role="tab">Shopping Card Prizes</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="competition-prizes" role="tabpanel">

                                    @if ($myCompetitionPrizes->count() > 0)
                                        @foreach ($myCompetitionPrizes as $prize)
                                            @php
                                                $getCompany = Helper::getDirectoryIdSlug($prize->vendor_id ?? null);
                                                $getVendorCompetitionById = Helper::getVendorCompetitionById($prize->vendor_id ?? null, $prize->advertisement_id ?? null);
                                            @endphp
                                            <div class="card">
                                                <h4 class="notranslate">🎁 {{ $prize->advertisement->heading ?? '' }}</h4>
                                                <p>
                                                    <b>Company:</b>
                                                    <span class="notranslate" style="font-size: 16px;font-weight: 600;">
                                                        {{ $getCompany->title ?? '' }}
                                                    </span>
                                                </p>
                                                @if(!empty($prize->prize_description))
                                                    <p>
                                                        <b>Prize Description:</b>
                                                        {{ $prize->prize_description ?? '' }}
                                                    </p>
                                                @endif

                                                <p>
                                                    <b>Prize Amount:</b>

                                                    {{-- @if ($prize->status == 1 && empty($prize->prize_amount)) --}}

                                                        @if (!empty($getVendorCompetitionById->discount_amount))
                                                            @if($getVendorCompetitionById->discount_type == 'Percentage')
                                                                {{ $getVendorCompetitionById->discount_amount ?? '' }} % off
                                                            @else
                                                            {{ $currencySymbol }}{{ $getVendorCompetitionById->discount_amount ?? '' }}
                                                            @endif
                                                        @else
                                                            @if($prize->advertisement->discount_type == 'Percentage')
                                                                {{ $prize->advertisement->price ?? '' }} % off
                                                            @else
                                                            {{ $currencySymbol }}{{ $prize->advertisement->price ?? '' }}.00
                                                            @endif
                                                        @endif

                                                    {{-- @else

                                                        @if($prize->advertisement->discount_type == 'Percentage')
                                                            {{ $prize->prize_amount ?? '' }}% Off
                                                        @else
                                                        {{ $currencySymbol }}
                                                            {{ $prize->prize_amount ?? '' }}.00
                                                        @endif

                                                    @endif --}}

                                                </p>
                                                <p>
                                                    <b>Created:</b>
                                                    {{ !empty($prize->created_at) ? \Carbon\Carbon::parse($prize->created_at)->format('d M Y') : '' }}
                                                </p>
                                                <p>
                                                    <b>Valid Until:</b>
                                                    {{ !empty($prize->advertisement->end_date) ? \Carbon\Carbon::parse($prize->advertisement->end_date)->format('d M Y') : '' }}
                                                </p>
                                                <p>
                                                    <b>Status:</b>
                                                    @if($prize->type == 'user')
                                                        Discount on physical store
                                                    @elseif($prize->type == 'vendor')
                                                        Discount on Online Shop
                                                    @else
                                                        Discount on Online Shop
                                                    @endif
                                                </p>

                                                @php
                                                    $expiryDate = strtotime($prize->advertisement->end_date);
                                                    $today      = strtotime(date('Y-m-d'));
                                                @endphp

                                                @if (!empty($prize->voucher_code))
                                                    <p>
                                                        <b>Prize Code:</b>
                                                        <strong>{{ $prize->voucher_code }}</strong>
                                                    </p>

                                                    @if ($expiryDate < $today)
                                                        <b style="background:#e93d3d;color:#fff;padding:2px;border:1px solid #867d7d;font-weight:600;font-size:17px;width:105px;text-align:center; border-radius: 23px; margin-bottom: 10px;">
                                                             {{__("message.expired")}}
                                                        </b>
                                                    @elseif ($prize->status == 0)
                                                        <b style="background:#ff6536;color:#fff;padding:2px;border:1px solid #ec7171;font-weight:600;font-size:17px;width:105px;text-align:center; border-radius: 23px; margin-bottom: 10px;">
                                                             {{__("message.used")}}
                                                        </b>
                                                    @elseif ($getVendorCompetitionById)
                                                        <div class="prize_status_for_store_div">
                                                            @if($prize->answer->auto_reply_status == 0)
                                                            <div class="store-action-text prize_status_for_store">
                                                                 <div>
                                                                    <strong>Don't miss out!</strong> Apply the code before it expires. Redeem
                                                                    <b>
                                                                        discount
                                                                        @if($prize->advertisement->discount_type == 'Percentage')
                                                                            {{ $prize->advertisement->price ?? '' }}% off
                                                                        @else
                                                                            {{ $currencySymbol }}{{ $prize->advertisement->price ?? '' }}.00
                                                                        @endif
                                                                        on online shop
                                                                    </b> at cart
                                                                </div>
                                                                <div>
                                                                    <a target="_blank" href="{{ url('listings-product', ['id' => $getCompany->slug]) }}" style="background:#79d87b;color:#413d3d;padding:4px;border:1px solid #000;font-weight:600; display: inline-block; margin-top: 6px;">
                                                                         Online Shop
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="store-action-toggle prize_status_for_store">
                                                                <div style="margin-bottom: 8px;">
                                                                    <b>Apply</b> the code on <b>physical store discount
                                                                    {{-- @if($prize->advertisement->discount_type == 'Percentage')
                                                                        {{ $getCompany->competition_discount ?? '' }}% off
                                                                    @else
                                                                         {{ $currencySymbol }}{{ $getCompany->competition_discount ?? '' }}
                                                                    @endif --}}

                                                                    @if (!empty($getVendorCompetitionById->discount_amount))
                                                                        @if($getVendorCompetitionById->discount_type == 'Percentage')
                                                                            {{ $getVendorCompetitionById->discount_amount ?? '' }} % off
                                                                        @else
                                                                            {{ $currencySymbol }}{{ $getVendorCompetitionById->discount_amount ?? '' }}
                                                                        @endif
                                                                    @else
                                                                        @if($prize->advertisement->discount_type == 'Percentage')
                                                                            {{ $prize->advertisement->price ?? '' }} % off
                                                                        @else
                                                                        {{ $currencySymbol }}{{ $prize->advertisement->price ?? '' }}.00
                                                                        @endif
                                                                    @endif

                                                                </b> before it expires.
                                                                </div>
                                                                <input data-id="{{$prize->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="{{__("message.use")}}" data-off="Used" {{ ($prize->status=='1') ? 'checked' : '' }} onChange="changeStatus(this, '<?php echo $prize->status ?>','<?php echo $prize->id ?>');">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    @endif

                                                    <p>
                                                        <b>Status:</b>
                                                        @if($prize->type == 'user')
                                                            Discount for competition on <b>physical store</b> only
                                                        @elseif($prize->type == 'vendor')
                                                            Discount on Online Shop
                                                        @else
                                                            Discount on Online Shop
                                                        @endif
                                                    </p>

                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info mb-0">No competition prizes found.</div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="shoppingcard-prizes" role="tabpanel">

                                    @if ($myShoppingPrizes->count() > 0)
                                        @foreach ($myShoppingPrizes as $prize)

                                                @php
                                                    $getCompany = Helper::getDirectoryIdSlug($prize->vendor_id ?? null);
                                                    $getShoppingCard = Helper::getShoppingCard($prize->offer_id ?? null);
                                                @endphp
                                            <div class="card">
                                                <h4 class="notranslate">🎁 Shopping Card Prize for ( {{ $getShoppingCard->title ?? '' }} )</h4>
                                                <p>
                                                    <b>Company:</b>
                                                    <span class="notranslate" style="font-size: 16px;font-weight: 600;">
                                                        {{ $getCompany->title ?? '' }}
                                                    </span>
                                                </p>
                                                @if(!empty($prize->description))
                                                <p>
                                                    <b>Prize Description:</b>
                                                    {{ $prize->description ?? '' }}
                                                </p>
                                                @endif
                                                <p>
                                                    <b>Prize Amount: </b>
                                                    @if ($prize->discount_type == 'percentage')
                                                        {{ $prize->prize_amount }}% Off
                                                    @else
                                                        {{ $currencySymbol }}{{ $prize->prize_amount??'0.00' }} Off
                                                    @endif
                                                </p>
                                                <p>
                                                    <b>Created:</b>
                                                    {{ !empty($prize->created_at) ? \Carbon\Carbon::parse($prize->created_at)->format('d M Y') : '' }}
                                                </p>

                                                <p>
                                                    <b>Valid Until:</b>
                                                    {{ !empty($getShoppingCard->expire_date) ? \Carbon\Carbon::parse($getShoppingCard->expire_date)->format('d M Y') : '' }}
                                                </p>

                                                @if (!empty($prize->voucher_code))
                                                    <p>
                                                        <b>Prize Code:</b>
                                                        <strong>{{ $prize->voucher_code }}</strong>
                                                    </p>
                                                @endif


                                                <p>
                                                    <b>Status:</b>
                                                    @if($prize->type == 'shoppingcard')
                                                        Discount for shopping card offer on <b>physical store</b> only
                                                    @else
                                                        Discount on Online Shop
                                                    @endif
                                                </p>

                                                <p>
                                                    @if($prize->redeem_date != null)
                                                    <b>Redeemed successfuly at :</b>
                                                        {{ $prize->redeem_date }}
                                                    @endif
                                                </p>

                                                <div class="store-action-toggle prize_status_for_store">
                                                    <div style="margin-bottom: 8px;"><b>Apply</b> the code on
                                                    <b>
                                                        physical store discount
                                                        @if ($prize->discount_type == 'percentage')
                                                            {{ $prize->prize_amount }}% Off
                                                        @else
                                                            {{ $currencySymbol }}{{ $prize->prize_amount??'0.00' }} Off
                                                        @endif

                                                    </b> before it expires.</div>
                                                    @if($prize->status == 1)
                                                        <input data-id="{{$prize->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="{{__("message.use")}}" data-off="Used" {{ ($prize->status=='1') ? 'checked' : '' }} onChange="changeShoppingStatus(this, '<?php echo $prize->status ?>','<?php echo $prize->id ?>');">
                                                    @else
                                                        <b style="background:#ff6536;color:#fff;padding:2px;border:1px solid #ec7171;font-weight:600;font-size:17px;width:105px;text-align:center; border-radius: 23px; margin-bottom: 10px;">
                                                            Used
                                                        </b>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info mb-0">No shopping card prizes found.</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<script type="text/javascript">

    var suppressToggleEvent = false;

    $(function () {
        var hash = window.location.hash;
        if (hash) {
            $('.prize-tabs a[href="' + hash + '"]').tab('show');
        }

        $('.prize-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href');
            if (target) {
                history.replaceState(null, '', window.location.pathname + target);
            }
        });
    });

    function changeShoppingStatus(element, statusdata, id){
        if (suppressToggleEvent) return;

        var status = (statusdata!='0')?'0':'1';
        // record original checked state (before change event)
        var oldChecked = (statusdata != '0');
        var confirmation = status === '0'
            ? 'Please note that the Cashier must click on OK for your Discount/benefit to Apply'
            : 'Are you sure you want to mark this prize as To Use?';

        if (!confirm(confirmation)) {
            suppressToggleEvent = true;
            if (typeof $(element).bootstrapToggle === 'function') {
                try { $(element).bootstrapToggle(oldChecked ? 'on' : 'off'); } catch (e) { element.checked = oldChecked; }
            } else {
                element.checked = oldChecked;
            }
            setTimeout(function(){ suppressToggleEvent = false; }, 50);
            location.reload();
            return false;
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/account/changeShoppingWinnerStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
                setTimeout(function(){
                    location.reload();
                }, 1000);
            },
            error: function(){
                suppressToggleEvent = true;
                if (typeof $(element).bootstrapToggle === 'function') {
                    try { $(element).bootstrapToggle(oldChecked ? 'on' : 'off'); } catch (e) { element.checked = oldChecked; }
                } else {
                    element.checked = oldChecked;
                }
                setTimeout(function(){ suppressToggleEvent = false; }, 50);
            }
        });
    }

    function changeStatus(element, statusdata, id){
        if (suppressToggleEvent) return;

        var status = (statusdata!='0')?'0':'1';
        // record original checked state (before change event)
        var oldChecked = (statusdata != '0');
        var confirmation = status === '0'
            ? 'Please note that the Cashier must click on OK for your Discount/benefit to Apply'
            : 'Are you sure you want to mark this prize as To Use?';

        if (!confirm(confirmation)) {

            suppressToggleEvent = true;
            if (typeof $(element).bootstrapToggle === 'function') {
                try { $(element).bootstrapToggle(oldChecked ? 'on' : 'off'); } catch (e) { element.checked = oldChecked; }
            } else {
                element.checked = oldChecked;
            }
            setTimeout(function(){ suppressToggleEvent = false; }, 50);
            location.reload();
            return false;
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/account/changeWinnerStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
                setTimeout(function(){
                    location.reload();
                }, 1000);
            },
            error: function(){
                suppressToggleEvent = true;
                if (typeof $(element).bootstrapToggle === 'function') {
                    try { $(element).bootstrapToggle(oldChecked ? 'on' : 'off'); } catch (e) { element.checked = oldChecked; }
                } else {
                    element.checked = oldChecked;
                }
                setTimeout(function(){ suppressToggleEvent = false; }, 50);
            }
        });
    }

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
