@extends('layouts.client.vendor')
@section('title','Add Voucher')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Add Voucher</h1>
        <x-client.company_account_menu />
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="card">
                    <div class="card-body">
                        <h4 class="page-title">Add Voucher</h4>
                        <form action="{{ route('company.discount.voucher.submitVoucher') }}" id="voucherform" method="POST" class="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row cus-title">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Voucher Name<span class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="voucher_name" name="voucher_name" value="{{ old('voucher_name') }}" placeholder="Enter Voucher Name" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('voucher_name'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Voucher Code<span class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="voucher_code" name="voucher_code" value="{{ old('voucher_code') }}" placeholder="Enter Voucher Code" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('voucher_code'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Minimum Purchase Amount (in store only for customer club member)<span class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="number" min="0" oninput="this.value = !!this.value &amp;&amp; Math.abs(this.value) &gt;= 0 ? Math.abs(this.value) : null" id="minimum_amount" name="minimum_amount" value="" placeholder="Enter Purchase Minimum Amount" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('minimum_amount'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row cus-title">
                                <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Minimum Amount</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="number" min="0" oninput="this.value = 
                                    !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount') }}" placeholder="Enter Minimum Amount" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('minimum_amount'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row cus-title">
                                <div class="col-md-6">
                                    <label class="col-md-12 p-0">Discount Type<span class="notranslate" style="color:red;">*</span></label>
                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="pay" name="discount_type" value="fixeddiscount" style="margin-left: 15px;">
                                            <label class="custom-control-label" for="1">Fixed Discount </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="pay" name="discount_type" value="percentagediscount" style="margin-left: 15px;">
                                            <label class="custom-control-label" for="1">Percentage Discount</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Status</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="pay" name="status" value="active">
                                            <label class="custom-control-label" for="1">Active </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="pay" name="status" value="inactive" style="margin-left: 20px;" checked="">
                                            <label class="custom-control-label" for="1">Inactive </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="row cus-title" id="fixeddiscount">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Enter Discount Value</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="number" min="0" id="pricedis" name="price_dis" value="" placeholder="Enter Discount Value" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('price_dis'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row cus-title" id="percentagediscount">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Enter Percentage Discount</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="number" min="0" id="perdis" name="dis_per" value="" placeholder="Enter Discount Value" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('dis_per'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row cus-title">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Voucher Active Date<span class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="date" name="activation_date" class="form-control  border-0" autocomplete=off>
                                        </div>
                                        <span class="text-danger">@error('activation_date'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Voucher Expiry Date<span class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="date" name="expiry_date" class="form-control  border-0" autocomplete=off>
                                        </div>
                                        <span class="text-danger">@error('expiry_date'){{$message}} @enderror</span>
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

                    <!-- Column -->


                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#directoryId").select2({
            placeholder: "Select option"
            , allowClear: true
        });
    
        $("#percentagediscount").hide();
        $("#fixeddiscount").hide();
        $("input[name=discount_type]").change(function() {
            if ($(this).val() == "percentagediscount") {
                $('#pricedis').val('');
                $("#percentagediscount").show();
                $("#fixeddiscount").hide();
            } else if ($(this).val() == "fixeddiscount") {
                $('#perdis').val('');
                $("#fixeddiscount").show();
                $("#percentagediscount").hide();
            } else {
                $("#percentagediscount").hide();
                $("#fixeddiscount").hide();
            }

        });
    
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
<style>
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
