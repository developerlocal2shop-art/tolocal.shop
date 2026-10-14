@extends('layouts.client.company')
@section('title','Gift Voucher List')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Dashboard</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                <?php $currenturl = url()->current();
                $currenturl = explode("/",$currenturl);
                if(in_array("directory-giftvoucher", $currenturl)){
                    $arrendvalue = end($currenturl);
                    $directoryName = Helper::getDirectory($arrendvalue);
                }else{
                    $arrendvalue = "";
                }
                ?>
                <div class="page-wrapper">
                    <div class="container-fluid">
                        @if(session()->get('msg'))
                        <div class="alert alert-success">
                            {{ session()->get('msg') }}
                        </div><br />
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="white-box">
                                    <form action="{{ route('company.discount.giftvoucher.indexsearch') }}" method="get" class="">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h3 class="box-title">Gift Voucher List</h3>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="d-md-flex">
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <?php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('gift-voucher');
                                                if($getFeatureUsesValue <= count($gift_voucher)){
                                            ?>
                                                <a onclick="alert('Please upgrade member account. you can not add gift voucher greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Voucher</a>
                                                <?php }else{ ?>
                                                @if ($user->hasPermission('add') == true)
                                                <a href="{{ route('company.discount.giftvoucher.createGiftVoucher') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Gift Voucher</a>
                                                @endif
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">S.No</th>
                                                    <th class="border-top-0">Voucher</th>
                                                    <th class="border-top-0">Voucher code</th>
                                                    <th class="border-top-0">Discount Amount</th>
                                                    <th class="border-top-0">Minimum Amount</th>
                                                    <th class="border-top-0">Activation Date</th>
                                                    <th class="border-top-0">Expiry Date</th>
                                                    <th class="border-top-0">Website Link</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0; $datacount = count($gift_voucher); if ($datacount>0){?>
                                                @foreach($gift_voucher as $giftvoucherdata)
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td>{{ $giftvoucherdata->voucher_name }}</td>
                                                    <td>{{ $giftvoucherdata->voucher_code }}</td>
                                                    <td>{{ Helper::numberCurrencyformat($giftvoucherdata->discount_amount) }}</td>
                                                    <td>{{ Helper::numberCurrencyformat($giftvoucherdata->minimum_amount) }}</td>
                                                    <td>{{ $giftvoucherdata->activation_date }}</td>
                                                    <td>{{ $giftvoucherdata->expiry_date }}</td>
                                                    <td>{{ $giftvoucherdata->website_link }}</td>
                                                    <td>
                                                        @if ($user->hasPermission('edit') == true)
                                                        <a class="btn btn-primary btn-sm" href="<?php echo url("/company/discount/giftvoucher/giftvoucher-edit", ['id' => @$giftvoucherdata->id]); ?>">EDIT</a>
                                                        @endif
                                                        @if ($user->hasPermission('delete') == true)
                                                        <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/discount/giftvoucher/giftvoucher-delete",['id' => @$giftvoucherdata->id]); ?>">DELETE</a>
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
                                        {{ $gift_voucher->appends($query)->links() }}
                                        @else
                                        {!! $gift_voucher->links() !!}
                                        @endif
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
        .d-none {
            display: unset !important;
        }
    }
    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: unset;
    }
    .custom-control-label {
        position: unset;
    }
    .red {
        background: none;
    }

</style>
@endsection
