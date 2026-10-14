@extends('layouts.client.company')
@section('title','Create Gift Voucher')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Create Gift Voucher</h1>
        <x-client.company_account_menu />
    </div>
</div>
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
                                <h4 class="page-title">Add Gift Voucher</h4>
                                <form action="{{ route('company.discount.giftvoucher.submitGiftVoucher') }}" id="voucherform" method="POST" class="" enctype="multipart/form-data">
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
                                                <label class="col-md-12 p-0">Voucher Title<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="voucher_title" name="voucher_title" value="{{ old('voucher_title') }}" placeholder="Enter Voucher Ttile" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('voucher_title'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row cus-title">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Voucher Description<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="voucher_description" name="voucher_description" value="{{ old('voucher_description') }}" placeholder="Enter Voucher Description" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('voucher_description'){{$message}} @enderror</span>
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
                                    </div>
                                    <div class="row cus-title">
                                        <input type="hidden" name="directoryId" id="directoryId" class="form-control" value="{{auth()->user()->directory_id}}">
                                       
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Discount Amount</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" min="0" oninput="this.value = 
                                            !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" id="discount_amount" name="discount_amount" value="{{ old('discount_amount') }}" placeholder="Enter discount amount" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('discount_amount'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
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
                                    <div class="row cus-title">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Gift Voucher Active Date<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="date" value="{{ old('activation_date') }}" name="activation_date" class="form-control  border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('activation_date'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Gift Voucher Expiry Date<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="date" value="{{ old('expiry_date') }}" name="expiry_date" class="form-control  border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('expiry_date'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Website Link(insert a valid link!)<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="url" id="website_link" name="website_link" value="{{ old('website_link') }}" placeholder="Enter Website Link" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('website_link'){{$message}} @enderror</span>
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
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script type="text/javascript">
    $(document).ready(function() {
        $("#par-directory").select2({
            placeholder: "Select "
            , allowClear: true
        });
    });

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
<style>
[type="radio"]:checked, [type="radio"]:not(:checked) {
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
