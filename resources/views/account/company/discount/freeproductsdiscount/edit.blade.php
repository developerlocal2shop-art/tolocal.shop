<?php
if (isset($data['productsDiscountDetails'])) {
    $productsDiscountDetails = $data['productsDiscountDetails'];
}
?>
<style>
    .select2-container-multi {
        width: 300px
    }

    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
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

    .cus-title label {
        font-weight: bold;
        color: #203864;
    }
    .select2-container--default .select2-selection--single {
        border-right: 1px solid #aaa !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
    }
    .select2-container .select2-selection--single {
        height: 32px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        margin-top: 1px !important;
    }
</style>

@extends('layouts.client.vendor')
@section('title','Edit Products Discount')
@section('content')

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

                        <div class="card">
                            <div class="card-body">
                                <h4 class="page-title">Free Products Discount Edit</h4>

                                <form action="{{ route('company.discount.freeproductsdiscount.updateProductDiscount', $productsDiscountDetails->id) }}" id="voucherform" method="POST" class="" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row cus-title">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Title</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="title" name="title" value="{{ old('title', $productsDiscountDetails->title ) }}" placeholder="Enter Voucher Name" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('title'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Description</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="description" name="description" value="{{ old('description', $productsDiscountDetails->description ) }}" placeholder="Enter Voucher Name" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('description'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row cus-title">
                                        <input type="hidden" name="directoryId" id="directoryId" class="form-control" value="{{auth()->user()->directory_id}}">

                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Minimum Amount</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" min="0" oninput="this.value = 
                                    !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', $productsDiscountDetails->minimum_amount ) }}" placeholder="Enter Minimum Amount" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('minimum_amount'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row cus-title">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Product List</label>
                                                <?php 
                                                $product_ids = explode(",",$productsDiscountDetails->product_ids);
                                                ?>
                                                <div class="col-md-12 p-0" id="productdata">
                                                    <select class="form-control" name="productId" id="productId">
                                                        <option>None</option>
                                                        @foreach($data['allProductRelatedToDirectory'] as $productsrow)
                                                        <option value="{{ $productsrow['id'] }}" <?php if($productsrow['id'] == $productsDiscountDetails->product_id){echo "selected";} ?>> {{ $productsrow['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Free Product List</label>
                                                <?php 
                                                $product_ids = explode(",",$productsDiscountDetails->product_ids);
                                                ?>
                                                <div class="col-md-12 p-0" id="productdata">
                                                    <select multiple class="form-control" name="free_productId[]" id="free_productId">
                                                        <option>None</option>
                                                        @foreach($data['allFreeProductRelatedToDirectory'] as $productsrow)
                                                        <option value="{{ $productsrow['id'] }}" <?php if(in_array($productsrow['id'],$product_ids)){echo "selected";} ?>> {{ $productsrow['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-info">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Column -->


                    </div>

                </div>
            </div>
        </div>
    </div>
    <main>
        <script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                
                var directory_id = {
                    {
                        auth() - > user() - > directory_id
                    }
                }

                $.ajax({
                    url: baseUrl + '/company/get-product-by-directory',

                    type: "POST"
                    , data: {
                        directory_id: directory_id
                        , _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        $('#productId').html(`<option value="none">None</option>`);
                        $.each(result.productsdata, function(key, value) {
                            $("#productId").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            
            
                // $("#list-product, #productId, #free_productId").select2({
                //     placeholder: "Select "
                //     , allowClear: true
                // });
                // Warning
                $(window).on('beforeunload', function() {
                    return "Any changes will be lost";
                });
                // Form Submit
                $(document).on("submit", "form", function(event) {
                    // disable warning
                    $(window).off('beforeunload');
                });
            });

        </script>
         <script>
            $(document).ready(function() {
                $("#list-product, #productId, #free_productId").select2({
                        placeholder: "Select ",
                        allowClear: true
                });
            });
        </script>
        @endsection
