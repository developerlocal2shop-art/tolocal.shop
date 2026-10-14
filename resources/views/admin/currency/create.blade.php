@extends('layouts.adminmaster')
@section('title','Currency')
@section('content')
 
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Currency</h4>
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

                <form action="{{ route('admin.currency.submit') }}" method="POST" class="" >

                    {{ csrf_field() }}
                    <div class="row cus-title">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Code</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="currency_code" id="currency-code" value="{{ old('currency_code') }}" placeholder="Enter Currency Code" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('currency_code'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="currency_name" id="currency-name" value="{{ old('currency_name') }}" placeholder="Enter Currency Code" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('currency_name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Symbol</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="currency_symbol" id="currency-symbol" value="{{ old('currency_symbol') }}" placeholder="Enter Currency Symbol" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('currency_symbol'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row cus-title">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12">Base Currency</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="base_currency" value="yes" id="pay">
                                    <label class="custom-control-label" for="1">Yes </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="base_currency" value="no" style="margin-left: 20px;" id="nonpay" checked="">
                                    <label class="custom-control-label" for="1">No </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Active" >
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Inactive" style="margin-left: 20px;" checked="">
                                    <label class="custom-control-label" for="1">Inactive </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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

<script type="text/javascript">
    
    $(document).ready(function() {
        $('.categoryselect').select2();
    });
</script>
@endsection