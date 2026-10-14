@extends('layouts.adminmaster')
@section('title','Free Products Discount')
@section('content')
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
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Free Products Edit</h4>
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

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.discount.freeproductsdiscount.updateProductDiscount', $productsDiscountDetails->id) }}" id="voucherform" method="POST" class="" enctype="multipart/form-data">
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
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Select Directory List</label>
                                <div class="col-md-12 ">
                                     <?php $allDirectory = Helper::getAllDirectory(); ?>
                                    <select class="form-control" id="directoryId" name="directoryId">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                            <option value="{{ $directoryrow->id }}" {{ $productsDiscountDetails->directory_id==$directoryrow->id ? 'selected' : '' }}> {{ $directoryrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('directoryId'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Minimum Amount</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number"  min="0" oninput="this.value = 
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
                                $allProductRelatedToDirectory = Helper::getallWithoutFreeProductRelatedToDirectory($productsDiscountDetails->directory_id);
                                //$product_ids = explode(",",$productsDiscountDetails->product_id); ?>
                                
                                <div class="col-md-12 " id="productdata">
                                    <select class="form-control" name="productId" id="productId">
                                        <option>None</option>
                                        @foreach($allProductRelatedToDirectory as $productsrow)
                                       
                                            <option value="{{ $productsrow['id'] }}" <?php if($productsrow['id'] == $productsDiscountDetails->product_id){ echo "selected"; } ?>> 
                                             {{ $productsrow['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Free Product List</label>
                                <?php 
                                $allProductRelatedToDirectory = Helper::getallWithFreeProductRelatedToDirectory($productsDiscountDetails->directory_id);
                                $product_ids = explode(",",$productsDiscountDetails->product_ids); ?>
                                <div class="col-md-12 " id="productdata">
                                    <select multiple class="form-control" name="free_productId[]" id="free_productId">
                                        <option>None</option>
                                        @foreach($allProductRelatedToDirectory as $productsrow)
                                            <option value="{{ $productsrow['id'] }}" <?php if(in_array($productsrow['id'],$product_ids)){echo "selected";} ?>> {{ $productsrow['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
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
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script>
    $(document).ready(function() {
        $('#directoryId').on('change', function() {
            $("#productId").val("");
            $("#productId").trigger("change");

            $("#free_productId").val("");
            $("#free_productId").trigger("change");

            
            var directory_id = this.value;
            //$("#state-dropdown").html('');
            $.ajax({
                url: baseUrl + '/admin/get-freeproduct-by-directory',

                type: "POST",
                data: {
                    directory_id: directory_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {

                    $('#productId').html(`<option value="none">None</option>`);
                    $.each(result.productsdata, function(key, value) {
                        $("#productId").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $('#free_productId').html(`<option value="none">None</option>`);
                    $.each(result.freeproductsdata, function(key, value) {
                        $("#free_productId").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                }
            });
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