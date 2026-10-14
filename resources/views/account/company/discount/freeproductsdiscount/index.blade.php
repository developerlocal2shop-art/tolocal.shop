@extends('layouts.client.vendor')
@section('title','Products Discount List')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Company Dashboard</h1>
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
                                    <form action="{{ route('company.discount.freeproductsdiscount.indexsearch') }}" method="get" class="">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-5">
                                                <h3 class="box-title">Free Products Discount List</h3>
                                            </div>

                                            <div class="col-md-4 mobile-view">
                                                <div class="d-md-flex">

                                                    <div class="input-group mb-3">
                                                        <input type="text" name="searchtext" id="search" value="" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">

                                                <?php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('free-products-discount');
                                                if($getFeatureUsesValue <= count($free_products_discount)){
                                            ?>
                                                <a onclick="alert('Please upgrade member account. you can not add free products discount greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Voucher</a>
                                                <?php }else{ ?>
                                                @if ($user->hasPermission('add') == true)
                                                <a href="{{ route('company.discount.freeproductsdiscount.createProductDiscount') }}" class="btn btn-primary ">Add Free Products Discount</a>
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
                                                    <th class="border-top-0">Description</th>
                                                    <th class="border-top-0">Minimum Amount</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0; $datacount = count($free_products_discount); if ($datacount>0){?>
                                                @foreach($free_products_discount as $datarow)
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td>{{ $datarow->title }}</td>
                                                    <td>{{ $datarow->description }}</td>
                                                    <td>{{ Helper::numberCurrencyformat($datarow->minimum_amount) }} </td>
                                                    <td>
                                                        @if ($user->hasPermission('edit') == true)
                                                        <a class="btn btn-primary btn-sm" href="<?php echo url("/company/discount/freeproductsdiscount/productdiscount-edit", ['id' => @$datarow->id]); ?>">EDIT</a>
                                                        @endif
                                                        @if ($user->hasPermission('delete') == true)
                                                        <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/discount/freeproductsdiscount/productdiscount-delete",['id' => @$datarow->id]); ?>">DELETE</a>
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
                                        {{ $free_products_discount->appends($query)->links() }}
                                        @else
                                        {!! $free_products_discount->links() !!}
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
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>

<style>
    @media (min-width: 779px) and (max-width: 991px) {
        .mobile-view {
            margin-left: -35px !important;
        }

    }

</style>
@endsection
