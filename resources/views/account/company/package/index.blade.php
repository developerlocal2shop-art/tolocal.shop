@extends('layouts.client.vendor')
@section('title','Package| Listing')
@section('content')
@php $user = auth()->user(); @endphp

<style>
    .snip1214 .plan-price {
        font-size: 20px;
    }
    .introduction p {
        line-height: 10px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 10px;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header blue-bar-package">
    <div class="container">
        <h1>Create Package</h1>
        <x-client.company_account_menu />
    </div>
</div>

<main>
    <div class="container margin_60_35">

        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">Packages</h3>
                    <div class="white-box">

                        <div class="row">

                            <div class="snip1214">
                                <?php 
                                $i = 0; 
                                if($mysubscription){
                                    $mysubscriptionslug = $mysubscription->slug??'';
                                }else{
                                    $mysubscriptionslug = '';
                                }
                                ?>
                                @foreach($plans as $row)                             
                                @php
                                $subscription_createdDate = \Carbon\Carbon::create($mysubscription->created_at??"")->addMonths($row->invoice_period);
                                $subscription_Date = $subscription_createdDate->format('Y-m-d H:i:s');
                                $currentDate = date("Y-m-d H:i:s");
                                if($paymentTrasaction){
                                    $paymenttransaction_type = $paymentTrasaction->type;
                                }else{
                                    $paymenttransaction_type = "";
                                }
                                @endphp

                                <div class="plan {{ ($row->slug == $mysubscriptionslug) || (empty($mysubscriptionslug) && $row->slug == 'free') ? 'featured' : '' }}">
                                    <h3 class="plan-title">
                                        {{ trim($row->name,'"') }}
                                    </h3>
                                    <div class="plan-cost">
                                        @if($row->invoice_period > 0)
                                            <span class="plan-price notranslate">
                                            @if($paymenttransaction_type == 'auto_payment' && strtolower($paymentTrasaction->package_name) == $row->slug )
                                                {{ $row->currency }}{{ $row->signup_fee }}
                                            @else 
                                                @if(($currentDate > $subscription_Date) && ($row->slug == $mysubscriptionslug) && ($row->signup_fee !== '0.00'))
                                                    @php
                                                        $pprice = $row->signup_fee * $row->invoice_period;
                                                    @endphp
                                                    {{ $row->currency }} {{ number_format($row->signup_fee, 2, ',', ' ') }}
                                                @else
                                                    @php
                                                        $pprice = $row->price * $row->invoice_period;
                                                    @endphp
                                                    {{ $row->currency }} {{ number_format($row->price, 2, ',', ' ') }}
                                                @endif
                                            @endif
                                        </span>

                                        <span class="plan-type">/ @if($row->invoice_interval == 'months')month @else {{ $row->invoice_interval }} @endif</span>
                                        
                                        @if($paymenttransaction_type == 'auto_payment' && strtolower($paymentTrasaction->package_name) == $row->slug ) 
                                        @else
                                        <b>Minimum {{ $row->invoice_period }} months ({{ number_format($pprice, 2, ',', ' ') }})</b>
                                        @endif

                                        @endif
                                        
                                        @if($paymenttransaction_type == 'auto_payment' && strtolower($paymentTrasaction->package_name) == $row->slug )
                                            <div class="introduction">
                                                <p> Auto payment mode has activated for {{ $row->invoice_period }} months. </p>
                                            </div>
                                        @else

                                            @if($row->signup_fee !== '0.00' && $currentDate < $subscription_Date )
                                            <div class="introduction">
                                                <p>Introduction offer for {{ $row->invoice_period }} months, </p>
                                                <p>THEREAFTER {{ $row->currency }} {{ $row->signup_fee }}/m, {{ $row->signup_fee*$row->invoice_period }} {{ $row->currency }}/{{ $row->invoice_period }} months</p>
                                            </div>
                                            @elseif( ($row->signup_fee !== '0.00') && ($row->slug == $mysubscriptionslug ))
                                                <div class="introduction">
                                                    <p> Plan has been expired. Please renew it. </p>
                                                </div>
                                            @elseif( ($row->signup_fee !== '0.00' ) && ($row->slug != $mysubscriptionslug ))
                                                <div class="introduction">
                                                <p>Introduction offer for {{ $row->invoice_period }} months, </p>
                                                <p>THEREAFTER {{ $row->currency }}{{ $row->signup_fee }}/m, {{ $row->signup_fee*$row->invoice_period }} {{ $row->currency }}/{{ $row->invoice_period }} months</p>
                                            </div>
                                            @endif

                                        @endif
                                       
                                        @if($row->invoice_period > 0)
                                            + Tax 25%
                                        @endif
                                       

                                    </div>

                                    <div class="plan-features">

                                        {!! json_decode($row->description) !!}
                                    </div>

                                    @if($i != 0)
                                           
                                        <div class="plan-select">
                                            <a href="{{ route('company.package.details',$row->id) }}">Select Plan</a>
                                        </div>
                                            
                                    @endif


                                    @php
                                    $i++;
                                    @endphp
                                </div>
                                @endforeach

                            </div>


                            <!-- <p class="text-muted">Add class <code>.table</code></p> -->

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    $(document).ready(function() {

    });

</script>
<style type="text/css">
    .snip1214 {
        font-family: 'Raleway', Arial, sans-serif;
        color: #000000;
        text-align: center;
        font-size: 16px;
        width: 100%;
        max-width: 1000px;
        margin: 40px 10px;
    }

    .snip1214 .plan {
        margin: 0;
        width: 25%;
        position: relative;
        float: left;
        background-color: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .snip1214 * {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .snip1214 header {
        position: relative;
    }

    .snip1214 .plan-title {
        position: relative;
        top: 0;
        font-weight: 800;
        padding: 5px 15px;
        margin: 0 auto;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        margin: 0;
        display: inline-block;
        background-color: #222f3d;
        color: #ffffff;
        text-transform: uppercase;
    }

    .snip1214 .plan-cost {
        padding: 0px 10px 20px;
    }

    .snip1214 .plan-price {
        font-family: 'Montserrat', Arial, sans-serif;
        font-weight: 800;
        font-size: 20px;
        color: #34495e;
    }

    .snip1214 .plan-type {
        opacity: 0.6;
    }

    .snip1214 .plan-features {
        padding: 0;
        margin: 0;
        text-align: center;
        list-style: outside none none;
        font-size: 0.8em;
    }

    .snip1214 .plan-features li {
        border-top: 1px solid #d2d7e2;
        padding: 10px 5%;
    }

    .snip1214 .plan-features li:nth-child(even) {
        background: rgba(0, 0, 0, 0.08);
    }

    .snip1214 .plan-features i {
        margin-right: 8px;
        opacity: 0.4;
    }

    .snip1214 .plan-select {
        border-top: 1px solid #d2d7e2;
        padding: 10px 10px 0;
    }

    .snip1214 .plan-select a {
        background-color: #222f3d;
        color: #ffffff;
        text-decoration: none;
        padding: 0.5em 1em;
        -webkit-transform: translateY(50%);
        transform: translateY(50%);
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
    }

    .snip1214 .plan-select a:hover {
        background-color: #46627f;
    }

    .snip1214 .featured {
        margin-top: -10px;
        background-color: #34495e;
        color: #ffffff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
        z-index: 1;
    }

    .snip1214 .featured .plan-title,
    .snip1214 .featured .plan-price {
        color: #ffffff;
    }

    .snip1214 .featured .plan-cost {
        padding: 10px 10px 20px;
    }

    .snip1214 .featured .plan-features li {
        border-top: 1px solid rgba(255, 255, 255, 0.4);
    }

    .snip1214 .featured .plan-select {
        padding: 20px 10px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.4);
    }

    @media only screen and (max-width: 767px) {
        .snip1214 .plan {
            width: 50%;
        }

        .snip1214 .plan-title,
        .snip1214 .plan-select a {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }

        .snip1214 .plan-cost,
        .snip1214 .featured .plan-cost {
            padding: 20px 10px 20px;
        }

        .snip1214 .plan-select,
        .snip1214 .featured .plan-select {
            padding: 10px 10px 10px;
        }

        .snip1214 .featured {
            margin-top: 0;
        }
    }

    @media only screen and (max-width: 440px) {
        .snip1214 .plan {
            width: 100%;
        }
    }

    @media (max-width: 767px) {
        .pull-right {
            margin-top: -30px !important;
        }

        .d-none {
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
@endsection