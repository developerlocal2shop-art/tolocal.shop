@extends('layouts.client.vendor')
@section('title','Offline Reports | Edit')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Offline Visitors</h1>
        <x-client.company_account_menu />
    </div>
</div>

<main>
<div class="container margin_60_35">
<div class="page-wrapper">

    <div class="container-fluid">
        <div class="">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Offline Reports</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <div class="d-md-flex">
                        {{-- @if ($user->hasPermission('add') == true) --}}
                        <a href="{{ route('company.offline.reports') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offline Reports</a>
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('company.offline.reports.update', $rData->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">From date<span class="notranslate" style="color:red; font-size: 16px;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" name="fromdate" value="{{ $rData->fromdate }}" placeholder="fromdate" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('fromdate'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">To date<span class="notranslate" style="color:red; font-size: 16px;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" name="todate" value="{{ $rData->todate }}" placeholder="todate" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('todate'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">City<span class="notranslate" style="color:red; font-size: 16px;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text"  name="city" value="{{ $rData->city }}" placeholder="city" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('city'){{$message}} @enderror</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">Zip code<span class="notranslate" style="color:red; font-size: 16px;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text"  name="zipcode" value="{{ $rData->zipcode }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('zipcode'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">Value<span class="notranslate" style="color:red; font-size: 16px;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="value" value="{{ $rData->value }}" placeholder="value" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('value'){{$message}} @enderror</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Save</button>
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
    #password-strength-status {
        padding: 5px 10px;
        color: #FFFFFF;
        border-radius: 4px;
        margin-top: 5px;
    }
    .medium-password {
        background-color: #b7d60a;
        border: #BBB418 1px solid;
    }
    .weak-password {
        background-color: #ce1d14;
        border: #AA4502 1px solid;
    }
    .strong-password {
        background-color: #12CC1A;
        border: #0FA015 1px solid;
    }
</style>
@endsection
