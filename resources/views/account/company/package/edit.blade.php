@extends('layouts.client.vendor')
@section('title','Package')
@section('content')
<style>

    .active_package {
        display: block;
        background: #f1f3f5;
        border: 1px solid #e0e0e0;
        color: #4a5568;
        padding: 14px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 15px;
    }

    /* Optional: add subtle left highlight like UI */
    .active_package::before {
        content: "";
        display: inline-block;
        width: 4px;
        height: 100%;
        background: #28a745; /* green accent */
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 8px 0 0 8px;
    }

    /* Fix positioning for before element */
    .active_package {
        position: relative;
        padding-left: 20px;
    }
    /* General Layout */
    .package-details-mobile {
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .page-title {
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Labels & Text */
    label {
        font-weight: 500;
        color: #555;
    }

    .border-bottom {
        border-bottom: 1px solid #eee !important;
        padding: 7px 15px;
    }

    /* Pricing Section */
    .notranslate {
        font-weight: 500;
        font-size: 16px;
    }

    /* Total Highlight */
    .col-md-6 label.p-0:last-child {
        font-size: 18px;
        font-weight: 700;
        color: #000;
    }

    /* Introduction Box */
    .introduction {
        background: #f9f9f9;
        border-left: 4px solid #007bff;
        padding: 12px 15px;
        margin-top: 15px;
        border-radius: 6px;
    }

    .introduction p {
        margin: 0;
        font-size: 14px;
        color: #444;
    }

    /* Right Side Box */
    .form-check {
        margin-top: 10px;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-label {
        font-weight: 500;
        color: #333;
    }

    /* Info Alert */
    .alert-info {
        background-color: #d9edf7;
        border: none;
        color: #31708f;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
    }

    /* PayPal Button */
    .btn-warning {
        background-color: #ff9900;
        border: none;
        color: #000;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e68a00;
        color: #000;
    }

    /* Plan Description Box */
    .plan_description {
        margin-top: 15px;
        padding: 15px;
        background: #f5f7fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .plan_description label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .plan_description ul {
        padding-left: 20px;
    }

    .plan_description li {
        margin-bottom: 2px;
        color: #333;
    }

    /* Table Styling */
    .packagedatatable table {
        border: 1px solid #eee;
    }

    .packagedatatable th {
        background: #f7f7f7;
        font-weight: 600;
    }

    .packagedatatable td,
    .packagedatatable th {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .packagedatatable i.fa-check {
        color: green;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .border-bottom {
            padding: 6px 20px;
        }

        .btn-lg {
            font-size: 16px;
            padding: 10px;
        }

        .plan_description {
            margin-top: 20px;
        }
    }

    .plan_description {
        background: #f9fafb;
        padding: 15px 18px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        margin-top: 1px;
    }

    .plan_description .title {
        font-weight: 600;
        font-size: 16px;
        color: #111827;
        margin-bottom: 10px;
        display: block;
    }

    /* List styling */
    .plan_description .content-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* If plain text (no <li>) */
    .plan_description .content-list br {
        display: none;
    }

    .plan_description .content-list {
        line-height: 1.6;
        color: #374151;
    }

    /* If using <ul><li> */
    .plan_description ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .plan_description ul li {
        position: relative;
        padding-left: 25px;
        font-size: 14px;
        color: #374151;
    }

    /* Custom check icon */
    .plan_description ul li::before {
        content: "✔";
        position: absolute;
        left: 0;
        top: 0;
        color: #22c55e;
        font-size: 14px;
        font-weight: bold;
    }

    /* Hover effect */
    .plan_description ul li:hover {
        color: #111827;
        transform: translateX(3px);
        transition: all 0.2s ease;
    }

    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
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
        width:200px;
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
        margin-left: -5px;
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
        <h1>Upgrade Package</h1>
        <x-client.company_account_menu />
    </div>
</div>

<?php
if (isset($data['plansDetails'])) {
    $planData = $data['plansDetails'];
}
?>
<main>
    <div class="container margin_60_35">
        <div class="container-fluid package-details-mobile">

            @if(session()->has('stripe_error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('stripe_error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session()->get('msg'))
            <div class="alert alert-success">
                {{ session()->get('msg') }}
            </div><br />
            @endif

            <?php
            $mysubscriptionslug = $data['planSubscription'] ? $data['planSubscription']->slug : '';
            $mysubscription = $data['planSubscription'];

            if($mysubscription){
                $mysubscription_type = $mysubscription->type;
            }else{
                $mysubscription_type = "";
            }

            $sdate = date_create($data['planSubscription'] ? $data['planSubscription']->created_at : '');
            $planSubscriptiondate = date_format($sdate,"Y-m-d");
            $currentDate = date('Y-m-d');
            $date1=date_create($planSubscriptiondate);
            $date2=date_create($currentDate);
            $diff=date_diff($date1,$date2);

            if($mysubscription && $mysubscription->signup_fee){
                $mysubs_signupfee = $mysubscription->signup_fee;
            }else{
                $mysubs_signupfee = "";
            }

            if($data['planSubscription'] && $data['planSubscription']->plan && $data['planSubscription']->plan->signup_fee){
                $plansub_signupfee = $data['planSubscription']->plan->signup_fee;
            }else{
                $plansub_signupfee = 0;
            }

            if(($mysubs_signupfee !== '0.00') && ($mysubscription_type == 'signup')){
                $plan_sub = $plansub_signupfee;
            }else{
                $plan_sub = $data['planSubscription'] ? $data['planSubscription']->plan->price : 0;
            }


            $subscription_createdDate = \Carbon\Carbon::create($mysubscription->created_at??'')->addMonths($planData->invoice_period);
            $subscription_Date = $subscription_createdDate->format('Y-m-d H:i:s');

            //dd($subscription_Date);

            $current_Date = date("Y-m-d H:i:s");

            if( $plan_sub > $planData->price ){
                $isPriceLess = 1;
            }else{
                $isPriceLess = 0;
            }

            if($diff->format("%R%a") < '+10'){
                $isDiscout = 1;
            }else{
                $isDiscout = 0;
            }

            if( $data['planSubscription'] && $data['planSubscription']->plan){
                $planSubscription_invoice_period = $data['planSubscription']->plan->invoice_period;
            }else{
                $planSubscription_invoice_period = 0;
            }
            ?>

            <div class="card">
                <div class="card-body">

                    <h4 class="page-title">Packages</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content cust-tab-cont">
                                <div class="form-group mb-4 row">

                                    <div class="col-lg-6 col-md-12" >

                                        <div class="row">
                                            <div class="col-md-6 border-bottom">
                                                <label class="">Package title </label><br>
                                                {{ json_decode($planData->name) }}
                                                <input type="hidden" name="plantranlation" value="{{ $planData->id }}">
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                <label class="col-md-12 p-0">Price </label>

                                                @if($planData->price  == $plan_sub || $planData->signup_fee  == $plan_sub)

                                                    <span class="notranslate">
                                                        @if(($planData->signup_fee !== '0.00') && ($mysubscription_type == 'signup'))
                                                            {{ $planData->currency }} {{ number_format($planData->signup_fee, 2, '.', ',') }}
                                                            @php
                                                                $package_price = $planData->signup_fee * $planData->invoice_period;
                                                            @endphp
                                                        @else
                                                            {{ $planData->currency??'' }} {{ number_format($planData->price, 2, '.', ',') }}

                                                            @php
                                                                $package_price = $planData->price * $planData->invoice_period;
                                                            @endphp
                                                        @endif
                                                    </span>
                                                @else

                                                        <span class="notranslate">
                                                            {{ $planData->currency??'' }} {{ number_format($planData->price, 2, '.', ',') }}
                                                        </span>
                                                        @php
                                                            $package_price = $planData->price * $planData->invoice_period;
                                                        @endphp

                                                @endif
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                <label class="p-0">Interval </label><br>
                                                Minimum {{ $planData->invoice_period }} months
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                <label class="">Period </label><br>
                                                @if( ($planData->slug == $mysubscriptionslug) && ($planData->signup_fee !== '0.00') && ($mysubscription_type == 'signup'))
                                                    {{ $planData->signup_fee }} X {{ old('invoice_period', $planData->invoice_period) }}
                                                @else

                                                    @if(($current_Date > $subscription_Date) && ($planData->signup_fee !== '0.00') && ($mysubscription_type == 'signup'))
                                                        {{ $planData->signup_fee }} X {{ old('invoice_period', $planData->invoice_period) }}
                                                    @else
                                                        {{ $planData->price }} X {{ old('invoice_period', $planData->invoice_period) }}
                                                    @endif

                                                @endif
                                            </div>

                                            <hr>

                                            <div class="col-md-6 border-bottom">
                                                <label class="p-0">Subtotal </label><br>
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                {{ $planData->currency??'' }} {{ number_format($package_price, 2, '.', ',') }}
                                                @php
                                                   $subtotal = $package_price;
                                                @endphp
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                <label class="p-0">Tax (25%) </label><br>
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                {{ $planData->currency??'' }} {{ number_format($package_price * 0.25, 2, '.', ',') }}
                                                @php
                                                  $package_price_tax = $package_price * 0.25;
                                                @endphp
                                            </div>

                                            <div class="col-md-6 border-bottom">
                                                <label class="p-0">Total </label><br>
                                            </div>

                                            @php
                                                $total2 = (float)$subtotal + (float)$package_price_tax;
                                            @endphp
                                            <div class="col-md-6 border-bottom">
                                                <label class="p-0">{{ $planData->currency ?? '' }} {{ number_format($total2, 2) }}</label>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="p-0"> Expiry Date - </label>
                                                @php
                                                if( ($planData->signup_fee !== '0.00') && ($planData->slug == $mysubscriptionslug )){
                                                    $datettt = strtotime($subscription_Date);
                                                }else{
                                                    $datettt = strtotime('+3 months', time());
                                                }
                                                    $effectiveDate = date(
                                                        'd, F Y',
                                                        strtotime("+".$planData->invoice_period, $datettt)
                                                    );
                                                    echo $effectiveDate;
                                                @endphp
                                                <br>
                                            </div>

                                        </div>

                                        @if($planData->signup_fee !== '0.00' && $current_Date < $subscription_Date )
                                            <div class="introduction">
                                            <p>Introduction offer for {{ $planData->invoice_period }} months, </p>
                                            <p>THEREAFTER {{ $planData->currency }} {{ $planData->signup_fee }}/m, {{ $planData->currency }} {{ $planData->signup_fee*$planData->invoice_period }} /{{ $planData->invoice_period }} months + 25% Tax</p>
                                            </div>
                                        @elseif( ($planData->signup_fee !== '0.00') && ($planData->slug == $mysubscriptionslug ) && ($current_Date > $subscription_Date))
                                            <div class="introduction">
                                            <p> Plan has been expired. Please renew it</p>
                                            </div>
                                        @elseif( ($planData->signup_fee !== '0.00' ) && ($planData->slug != $mysubscriptionslug ))
                                            <div class="introduction">
                                            <p>Introduction offer for {{ $planData->invoice_period }} months, </p>
                                            <p>THEREAFTER {{ $planData->currency }} {{ $planData->signup_fee }}/m, {{ $planData->currency }} {{ $planData->signup_fee*$planData->invoice_period }} / {{ $planData->invoice_period }} months + 25% Tax</p>
                                            </div>
                                        @endif

                                    </div>



                                    <div class="col-lg-6 col-md-12">
                                        @php

                                            $price = (float) preg_replace('/[^0-9.\-]/', '', $planData->price ?? 0);
                                            $period = (int) preg_replace('/[^0-9]/', '', $planData->invoice_period ?? 0);

                                            $second_price = (float) preg_replace('/[^0-9.\-]/', '', $planData->signup_fee ?? 0);

                                            $subtotal_paypal = $price * $period;
                                            $tax = $subtotal_paypal * 0.25; // 25% tax
                                            $total_paypal = $subtotal_paypal + $tax;

                                            $second_subtotal_paypal = $second_price * $period;
                                            $second_tax = $second_subtotal_paypal * 0.25; // 25% tax
                                            $second_total_paypal = $second_subtotal_paypal + $second_tax;
                                        @endphp

                                        <div class="plan_description">
                                            <label class="p-0">Content</label>
                                            {!! json_decode($planData->description) !!}
                                        </div>
                                        @if( ($planData->signup_fee !== '0.00') && ($planData->slug == $mysubscriptionslug ))
                                            <br>
                                        <b class="active_package">You are currently subscribed to this package.</b>
                                        @else

                                        <hr>

                                        <form method="POST" action="{{ route('package.place') }}">
                                            @csrf

                                            <input type="hidden" name="package_id" value="{{ $planData->id }}">
                                            <input type="hidden" name="package_name" value="{{ $planData->name }}">
                                            <input type="hidden" name="invoice_period" value="{{ $planData->invoice_period }}">
                                            <input type="hidden" name="invoice_interval" value="{{ $planData->invoice_interval }}">
                                            <input type="hidden" name="total_amount2" value="{{ $total_paypal }}">
                                            <input type="hidden" name="second_total_amount" value="{{ $second_total_paypal }}">
                                            <input type="hidden" name="tax" value="{{ $tax }}">

                                            <input type="hidden" name="currency" value="{{ $planData->currency }}">

                                            {{-- AUTO PAYMENT --}}
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    name="autopayment" id="autopayment" value="1" required>
                                                <label class="form-check-label" for="autopayment" style="margin: 3px 25px;">
                                                    Enable recurring payment
                                                </label>
                                            </div>

                                            <div class="alert alert-info small" >
                                                You will be redirected to PayPal to complete the payment securely.
                                            </div>

                                            <button type="submit" class="btn btn-warning btn-lg w-100">
                                                Pay with PayPal
                                            </button>
                                        </form>

                                        @endif




                                    </div>



                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 packagedatatable">
                            <table id="nomaldays-table" class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Modules</th>
                                        <th class="border-top-0">Enable</th>
                                        <th class="border-top-0">Limit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $fea_data = [];
                                    $fea_value;
                                    @endphp
                                    @foreach($data['plansDetails']->planfeatures as $key => $fea_val)
                                    @php
                                    $fea_data[$key] = $fea_val->slug;
                                    $fea_value[$fea_val->slug] = $fea_val->value;
                                    @endphp
                                    @endforeach

                                    <?php foreach($data['packagemodule'] as $key => $value){ ?>
                                    <tr class=" <?= $value->name;?> <?= $value->name.'_row_0';?>">
                                        <td>
                                            @if($value->getparent()) <b style="color:#ff9900">-----</b> @endif <?php echo $value->name; ?>: </td>
                                        <td>

                                            <?php if( in_array($value->slug, $fea_data) ){echo '<i class="fa fa-check" style="font-size: 20px;" aria-hidden="true"></i>';}?>

                                            <input style="display:none" class="form-check-input plan" type="checkbox" readonly id="plan{{ $value->id }}" name="plan[]" value="{{ $value->name??'' }}" <?php if( in_array($value->slug, $fea_data) ){echo "checked";}?>>
                                        </td>
                                        <td>
                                            <span name="limit[]" id="limit{{ $value->id }}" class="form-control p-0 border-0"> <?php if( in_array($value->slug, $fea_data) ){echo $fea_value[$value->slug]; }?> </span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation")
                , inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', ')
                , $inputs = $form.find('.required').find(inputSelector)
                , $errorMessage = $form.find('div.error')
                , valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val()
                    , cvc: $('.card-cvc').val()
                    , exp_month: $('.card-expiry-month').val()
                    , exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('#btn-place-payment').before('<div class="alert-danger alert">' + response.error.message + '</div>');
            } else {
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });

    $(document).ready(function() {

       @php
       if( ($planData->signup_fee !== '0.00') && ($planData->slug == $mysubscriptionslug ) && ($current_Date > $subscription_Date)){
       @endphp
           alert('Plan has been expired. Please renew it');
       @php
       }
       @endphp

        $('#number_on_card').bind('keyup paste', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $('#card_expiry_month').mask("99");
        $('#card_expiry_year').mask("9999");
        $('#card_cvc').mask("999");

        $('.plan').click(function() {

            var planid = $(this).data('id');
            var checked = $(this).is(':checked');
            if (checked) {
                $('#limit' + planid).prop('disabled', false);
            } else {
                $('#limit' + planid).prop('disabled', true);
                $('#limit' + planid).val(0);
            }
        });

        $('.ckeditor').ckeditor();
        $("#galleryimgPreview").sortable();
    });

    $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {
            if (input.files) {
                //console.log( input.files.length);
                $('#preview-gallery-img').hide();
                var filesAmount = input.files.length;
                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $("<div class=\"thumbnail \">" +
                            "<img src=\"" + event.target.result + "\" width=\"100px\" height=\"100px\" />" +
                            "<br/><span class=\"closebtn\"><i class=\"fas fa-trash\" style=\"color:#ff0505; \" aria-hidden=\"true\"></i></span>" +
                            "</div>").appendTo(imgPreviewPlaceholder);
                        $(".closebtn").click(function() {
                            $(this).parent(".thumbnail").remove();
                        });

                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#eventgal-image').on('change', function() {
            //document.getElementById("preview-gallery-img").style.display = "none";
            multiImgPreview(this, 'div.galleryimgPreview');

        });
    });

    var eventsloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    $(document).ready(function () {
        // Warning
        $(window).on('beforeunload', function(){
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event){
            // disable warning
            $(window).off('beforeunload');
        });
    });
</script>

<style type="text/css">
    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
        margin: 1px;
    }

    .thumbnail img {
        width: 70px;
        height: 70px;
    }

    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
        /* background: url(http://icons.iconarchive.com/icons/kyo-tux/delikate/512/Close-icon.png); */
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }

    .table td,
    .table th {
        padding: 5px 10px;
    }

    .form-check-input[type="checkbox"] {
        width: 17px;
        height: 17px;
    }

</style>
@endsection
