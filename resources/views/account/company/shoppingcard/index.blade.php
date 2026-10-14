@extends('layouts.client.vendor')
@section('title','Shopping Card')
@section('content')
@php $user = auth()->user(); @endphp
<style>
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
        transform: translate(-12%, 3px);
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
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Company's Shopping Card</h1>
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
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">

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
            </div>
        </div>
        <!--Main Area-->

        <div class="white-box">
            <div class="row">

                <div class="col-md-3 shoppingcard-helpicon">
                    <h3 class="box-title" style="display: flex;">Shopping Card
                    @if(!empty($instruction->message))
                    <div id="helpicon">
                        <span id="helpiconText">{{$instruction->message}}</span>
                        <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                    </div>
                    @endif
                    </h3>
                </div>
                <div class="col-md-9">
                    <form action="{{ route('company.shoppingcard.indexsearch') }}" method="get" class="">
                        <div class="row">

                            <div class="col-lg-2 col-md-2">
                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 9px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>


                            <div class="col-lg-8 col-md-8">
                                <div class="d-md-flex">
                                    <div class="input-group mb-3">
                                        <input type="text" name="fromdate" title="From Date" value="{{ $fromDate }}" id="fromdate" class="form-control" placeholder="From date">
                                        <input type="text" name="todate" title="To Date" value="{{ $toDate }}" id="todate" class="form-control" placeholder="To date">
                                        <input type="text" name="searchtext" id="search" value="{{ $searchtext??'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <?php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('shopping-card');
                                                if($getFeatureUsesValue <= count($shoppingcarddata)){
                                            ?>
                                <a onclick="alert('Please upgrade profile. you can not add shopping card greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
                                <?php }else{ ?>
                                @if ($user->hasPermission('add') == true)
                                <a href="{{ route('company.addshoppingcard') }}" class="btn btn-primary d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
                                @endif
                                <?php } ?>
                            </div>


                        </div>

                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">S.No</th>
                            <th class="border-top-0">Title</th>
                            <th class="border-top-0">Image</th>
                            <th class="border-top-0">Discount</th>
                            <th class="border-top-0">Visitors</th>
                            <th class="border-top-0">Likes</th>
                            <th class="border-top-0">Expiry date</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0; $datacount = count($shoppingcarddata);
                        if ($datacount>0){ ?>
                        @foreach($shoppingcarddata as $shoppingcarddatarow)
                        @php
                          $getDataOfferWinner = Helper::OfferWinnerByCompanyId($shoppingcarddatarow->id);
                        @endphp
                        {{-- {{ dd($shoppingcarddatarow) }} --}}
                        <tr>
                            <td>
                                <?php echo ++$i; ?>
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
                                @if($shoppingcarddatarow->discount_selectedvalue == "percentagediscount")
                                    {{ $shoppingcarddatarow->discount_firstvalue }} %
                                @else
                                  {{ $currencySymbol }}  {{ $shoppingcarddatarow->discount_firstvalue }}
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
                            {{-- <td>{{ $shoppingcarddatarow->active_date }}</td> --}}
                            <td>{{ $shoppingcarddatarow->exipre_date }}</td>
                            <td>
                                @if ($user->hasPermission('edit') == true)
                                <a class="btn btn-primary btn-sm" href="<?php echo url("/company/shoppingcard-edit", ['id' => @$shoppingcarddatarow->id]); ?>">EDIT</a>
                                @endif
                                @if ($user->hasPermission('delete') == true)
                                <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/shoppingcard-delete", ['id' => @$shoppingcarddatarow->id]); ?>">DELETE</a>
                                @endif

                                @if($getDataOfferWinner > 0)
                                    <a class="btn btn-success btn-sm" href="<?php echo url("/company/shoppingcard/customerprize", ['id' => @$shoppingcarddatarow->id]); ?>">
                                        Customer's Prize
                                    </a>
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


    <script type="text/javascript">
        $(document).ready(function() {

            $('#fromdate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#todate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});


                $("#fromdate").datepicker({
                    //defaultDate:fromdate,
                    dateFormat: 'yy-mm-dd'
                });
                $("#todate").datepicker({
                    //defaultDate:todate,
                    dateFormat: 'yy-mm-dd'
                });

            $('.ui-datepicker').addClass('notranslate');
        });

        $(function() {

            $(".ranges ul li").click(function() {
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder", "Please select date range");
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#fromdate').val(start.format('YYYY-MM-DD'));
                $('#todate').val(end.format('YYYY-MM-DD'));

            }

            $('#reportrange').daterangepicker({
                singleDatePicker: true
                , showDropdowns: true
                , startDate: start
                , endDate: end
                , ranges: {
                    'Today': [moment(), moment()]
                    , 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
                    , 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
                    , 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                    , 'This Month': [moment().startOf('month'), moment().endOf('month')]
                    , 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder", "Please select date range");

            //cb(start, end);

        });

        $('.show_confirm').click(function(e) {
            if (!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });

    </script>





    </div>
    <!-- /row -->

    <!-- /row -->
    </div>
    <!-- /container -->
</main>

@endsection
