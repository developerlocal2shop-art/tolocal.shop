@extends('layouts.client.vendor')
@section('title','Voucher List')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header">

    <div class="container">
        <h1>Company Voucher List</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->


<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">

            <div class="col-lg-12">
                <?php $currenturl = url()->current();
			$currenturl = explode("/",$currenturl);
			if(in_array("directory-voucher", $currenturl)){
				$arrendvalue = end($currenturl);
				$directoryName = Helper::getDirectory($arrendvalue);
			}else{
				$arrendvalue = "";
			}

			?>

                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif


                <div class="white-box">
                    <form action="{{ route('company.discount.voucher.indexsearch') }}" method="get" class="">

                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="box-title">Voucher List</h3>
                            </div>
                            <div class="col-lg-1 col-md-2">
                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 9px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                                {{-- <i class="fa fa-calendar"></i>
                        <input type="text" name="reportrange" id="reportrange" required placeholder="Please sele" class="form-control"> --}}
                            </div>
                            <div class="col-lg-9 col-md-10">
                                <div class="d-md-flex">


                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <input type="text" title="Search Activation Date" name="searchdate" id="searchdate" value="{{ $searchDateAct }}" class="form-control" placeholder="Search Activation Date" aria-label="Search" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <input type="text" title="Search Expiry Date" name="searchdateexpiry" id="searchdateexpiry" value="{{ $searchDateExp }}" class="form-control" placeholder="Search Expiry Date" aria-label="Search" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                    <div class="input-group mb-3 ">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-12">

                                <?php
                            $getFeatureUsesValue = Helper::getFeatureUsesValue('coupons');
                            if($getFeatureUsesValue <= count($voucher)){
                        ?>
                                <a onclick="alert('Please upgrade member account. you can not add coupon greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Voucher</a>
                                <?php }else{ ?>
                                @if ($user->hasPermission('add') == true)
                                <a href="{{ route('company.discount.voucher.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Voucher</a>
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
                                    <th class="border-top-0">Voucher Name</th>
                                    <th class="border-top-0">Voucher code</th>
                                    <th class="border-top-0">Discount Type</th>
                                    <th class="border-top-0">Discount Value</th>
                                    <th class="border-top-0">Activation Date</th>
                                    <th class="border-top-0">Expiry Date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $datacount = count($voucher);
                                if ($datacount>0){?>
                                @foreach($voucher as $voucherdata)
                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td>{{ $voucherdata->voucher_name }}</td>
                                    <td>{{ $voucherdata->voucher_code }}</td>
                                    <td>{{ $voucherdata->discount_type }}</td>
                                    <td>{{ $voucherdata->discount_value }}</td>
                                    <td>{{ $voucherdata->activation_date }}</td>
                                    <td>{{ $voucherdata->expiry_date }}</td>
                                    <td>{{ $voucherdata->status }}</td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-sm" href="<?php echo url("/company/discount/voucher/voucher-edit", ['id' => @$voucherdata->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/discount/voucher/voucher-delete",['id' => @$voucherdata->id]); ?>">DELETE</a>
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
                        {{ $voucher->appends($query)->links() }}
                        @else
                        {!! $voucher->links() !!}
                        @endif
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
            $("#searchdate").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#searchdateexpiry").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        $('.ui-datepicker').addClass('notranslate');
    })

    $(function() {

        $(".ranges ul li").click(function() {
            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder", "Please select date range");
        });

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#searchdate').val(start.format('YYYY-MM-DD'));
            $('#searchdateexpiry').val(end.format('YYYY-MM-DD'));
            searchdate
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

</script>
<style>
    @media (max-width: 991px) {
        .hotel-search {
            margin-right: 10px;
        }
    }
    @media (max-width: 767px) {
        .d-none {
            display: unset !important;
        }
    }
    @media(max-width:414px) {
        .hotel-search,
        div#reportrange {
            margin-right: 0;
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

@endsection
