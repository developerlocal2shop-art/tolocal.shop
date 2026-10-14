@extends('layouts.client.vendor')
@section('title','Edit Voucher')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Edit Voucher</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                <?php
            if (isset($data['voucherDetails'])) {
                $voucherDetails = $data['voucherDetails'];
            }
            ?>
                <div class="page-wrapper">

                    <div class="container-fluid">
                        @if(session()->get('msg'))
                        <div class="alert alert-success">
                            {{ session()->get('msg') }}
                        </div><br />
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <h4 class="page-title">Edit Voucher</h4>
                                <form action="{{ route('company.discount.voucher.updateVoucher', $voucherDetails->id) }}" id="voucherform" method="POST" class="" enctype="multipart/form-data">
                                    {{ csrf_field() }}


                                    <div class="row cus-title">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Voucher Name<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="voucher_name" name="voucher_name" value="{{ old('voucher_name', $voucherDetails->voucher_name ) }}" placeholder="Enter Voucher Name" class="form-control border-0">
                                                </div>
                                                <span class="text-danger" id='remainingC'></span>
                                                <span class="text-danger">@error('voucher_name'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Voucher Code<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="voucher_code" name="voucher_code" value="{{ old('voucher_code', $voucherDetails->voucher_code ) }}" placeholder="Enter Voucher Code" class="form-control border-0">
                                                </div>
                                                <span class="text-danger" id='remainingC'></span>
                                                <span class="text-danger">@error('voucher_code'){{$message}} @enderror</span>
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
                                    !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', $voucherDetails->minimum_amount ) }}" placeholder="Enter Minimum Amount" class="form-control border-0">
                                                </div>
                                                <span class="text-danger" id='remainingC'></span>
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
                                                    <input type="radio" class="pay" name="discount_type" style="margin-left: 15px;" value="fixeddiscount" {{ $voucherDetails->discount_type=='fixeddiscount' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Fixed Discount </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="pay" name="discount_type" style="margin-left: 15px;" value="percentagediscount" {{ $voucherDetails->discount_type=='percentagediscount' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Percentage Discount</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Status</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="pay" name="status" value="active" {{ $voucherDetails->status=='active' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Active </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="pay" name="status" style="margin-left: 20px;" value="inactive" {{ $voucherDetails->status=='inactive' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Inactive </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <input type="hidden" id="discount-value" value="{{ $voucherDetails->discount_value }}" />
                                    <div class="row cus-title" id="fixeddiscount">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Enter Discount Value</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" min="0" id="pricedis" name="price_dis" value="{{ old('price_dis') }}" placeholder="Enter Discount Value" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('price_dis'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row cus-title" id="percentagediscount">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Enter Percentage Discount</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" min="0" id="perdis" name="dis_per" value="{{ old('dis_per') }}" placeholder="Enter Discount Value" class="form-control border-0">
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
                                                    <input type="date" name="activation_date" value="{{ $voucherDetails->activation_date }}" class="form-control  border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('start_date'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Voucher Expiry Date<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="date" name="expiry_date" value="{{ $voucherDetails->expiry_date }}" class="form-control  border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('end_date'){{$message}} @enderror</span>
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
<script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#par-directory").select2({
            placeholder: "Select "
            , allowClear: true
        });
    
        var discountoffer = $('input[name=discount_type]:checked', '#voucherform').val();
        var discountvalue = $('#discount-value').val();
        console.log("Radio selected: ", discountoffer)
        if (discountoffer != "") {
            if (discountoffer == "percentagediscount") {
                $('#perdis').val(discountvalue);
                $("#percentagediscount").show();
                $("#fixeddiscount").hide();;
            } else if (discountoffer == "fixeddiscount") {
                $('#pricedis').val(discountvalue);
                $("#percentagediscount").hide();
                $("#fixeddiscount").show();
            } else {
                $("#percentagediscount").hide();
                $("#fixeddiscount").hide();
            }
        } else {
            $("#percentagediscount").hide();
            $("#fixeddiscount").hide();
        }

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
