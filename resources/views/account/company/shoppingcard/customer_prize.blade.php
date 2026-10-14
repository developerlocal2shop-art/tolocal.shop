@extends('layouts.client.vendor')
@section('title','Shopping Card Customer Prize')
@section('content')
@php $user = auth()->user(); @endphp
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

    .back_link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #1f3656;
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        padding: 5px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
        margin: 10px;
    }

    .back_link:hover {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0a58ca;
        transform: translateX(-2px);
    }

    .back_link i {
        font-size: 16px;
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
<div class="sub_header_in sticky_header ">

    <div class="container">
        <h1>Company's Shopping Card Customer Prize</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->
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
<!-- Main page -->
<main>

    <div class="container">

        <div class="col-md-12">
            <a href="{{ route('company.shoppingcard') }}" class="back_link">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="white-box">

                @if(session()->get('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div><br />
                @endif
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

            <div class="table-responsive user_que_ans" id="competition-prizes">
                @if ($offerCustomerWinner->count() > 0)
                    @foreach ($offerCustomerWinner as $prize)


                        @php
                            $getShoppingCard = Helper::getShoppingCard($prize->offer_id ?? null);
                            $getUserById = Helper::getUserById($prize->customer_id ?? null);
                        @endphp

                        <div class="card">
                            <h4 class="notranslate">🎁 Shopping Card Prize for ( {{ $getShoppingCard->title ?? '' }} )</h4>

                            <p>
                                <b>Customer:</b>
                                {{ $getUserById->name ?? '' }} {{ $getUserById->last_name ?? '' }} ({{ $getUserById->email ?? '' }})
                            </p>
                            <p>
                                <b>Customer Feedback:</b>
                                {{ $prize->description ?? '' }}
                            </p>
                            <p>
                                <b>Discount: </b>
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
                    <div class="alert alert-info mb-0">No shopping card prizes found for customer.</div>
                @endif
            </div>
            <div class="d-flex justify-content-center cust-page">
                {{-- @if(isset($query))
                {{ $offerCustomerWinner->appends($query)->links() }}
                @else
                {!! $offerCustomerWinner->links() !!}
                @endif --}}
            </div>
        </div>
    </div>


    <script type="text/javascript">

        var suppressToggleEvent = false;
        function changeShoppingStatus(element, statusdata, id){

            if (suppressToggleEvent) return;

            var status = (statusdata!='0')?'0':'1';
            // record original checked state (before change event)
            var oldChecked = (statusdata != '0');
            var confirmation = status === '0'
                ? 'Are you sure you want to mark this prize as Used? This action cannot be undone.'
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
                url: baseUrl+'/company/changeShoppingWinnerStatus',
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

        $(document).ready(function() {

        });
    </script>
    </div>
    <!-- /row -->
    <!-- /row -->
    </div>
    <!-- /container -->
</main>

@endsection
