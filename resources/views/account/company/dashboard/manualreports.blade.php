@extends('layouts.client.vendor')
@section('title','Reports| view')
@section('content')
@php $user = auth()->user(); @endphp
<style>
.form-control {
    margin: 3px 3px 2px -2px;
}
</style>
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Reports| view</h1>
        <x-client.company_account_menu />
    </div>
</div>
<!-- /sub_header -->
<main>
    <div class="container margin_60_35">
        <div class="page-wrapper">
            <div class="container-fluid">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="white-box manualreports">
                    <div class="row dashboard-filter">
                        <div class="col-md-4">
                            <form method="POST" id="regFrm" action="{{ route('company.offline.submit.reports') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row dashbord-option offline-report-list-css mb-4">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-sm" href="{{ route('company.all.visitors') }}">View all</a>
                                        <hr>
                                        <b><i class="fa fa-eye" aria-hidden="true"></i> No. of shop visitors</b>
                                        <label class="mb-4">

                                            Please fill in non digital data for: No. of shop visitors in the shop today or by any dates
                                        </label>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">

                                                <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="City">
                                                <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="Zip Code">
                                            </div>
                                        </div>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">
                                                <input type="hidden" name="type" value="visitors">
                                                <input type="text" name="fromdate" title="From Date" required="" id="fromdate" class="form-control required" placeholder="From date" autocomplete="off">
                                                <input type="text" name="todate" title="To Date" required="" id="todate" class="form-control required" placeholder="To date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="reportsavebtn">
                                            <div class="box-title">
                                                <input type="text" autocomplete="off" name="value" required="" value="" id="value" class="form-control required">
                                                <div class="input-group-append">
                                                    @if ($user->hasPermission('add') == true)
                                                    <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="POST" id="regFrm" action="{{ route('company.offline.submit.reports') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row dashbord-option offline-report-list-css mb-4">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-sm" href="{{ route('company.all.receiptclosing') }}">View all</a>
                                        <hr>
                                        <b><i class="fa fa-eye" aria-hidden="true"></i> No. of receipt closing</b>
                                        <label class="mb-4"> Please fill in non-digital data for: No. of receipt closing in the shop today or by any dates. </label>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">
                                                <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="City">
                                                <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="Zip Code">

                                            </div>
                                        </div>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">
                                                <input type="hidden" name="type" value="receipt_closing">
                                                <input type="text" name="fromdate" title="From Date" required="" id="fromdate_receipt_closing" class="form-control required" placeholder="From date" autocomplete="off">
                                                <input type="text" name="todate" title="To Date" required="" id="todate_receipt_closing" class="form-control required" placeholder="To date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="reportsavebtn">
                                            <div class="box-title">
                                                <input type="text" autocomplete="off" name="value" required="" id="value" class="form-control required">
                                                <div class="input-group-append">
                                                    @if ($user->hasPermission('add') == true)
                                                    <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="POST" id="regFrm" action="{{ route('company.offline.submit.reports') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row dashbord-option offline-report-list-css mb-4">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-sm" href="{{ route('company.allextra.reports') }}">View all</a>
                                        <hr>
                                        <b><i class="fa fa-eye" aria-hidden="true"></i> Extra in the shop</b>
                                        <label class="mb-4"> Please fill in non-digital data for: Extra in the shop today or by any dates. </label>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">
                                                <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="City">
                                                <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="Zip Code">
                                            </div>
                                        </div>
                                        <div class="d-md-flex manualeDateField">
                                            <div class="input-group mb-12">
                                                <input type="hidden" autocomplete="off" name="type" value="extra">
                                                <input type="text" name="fromdate" title="From Date" required="" id="fromdate_extra" class="form-control required" placeholder="From date" autocomplete="off">
                                                <input type="text" name="todate" title="To Date" required="" id="todate_extra" class="form-control required" placeholder="To date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="reportsavebtn">
                                            <div class="box-title">
                                                <input type="text" name="value" required="" id="value" class="form-control required">
                                                <div class="input-group-append">
                                                    @if ($user->hasPermission('add') == true)
                                                    <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
                                                    @endif
                                                </div>
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
<script>
    $(document).on('click', '.submitbtn', function(e) {
        e.preventDefault();
        $(document).find('.required').removeClass('is-invalid');
        $(document).find('.invalid-feedback').remove();
        var obj = $(this);
        let requestTxt = $(this).attr('process-text');
        requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
        var oldhtml = $(this).html();

        var form = $(this).closest("form");
        var url = form.attr('action');
        var method = form.attr('method');
        var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <span class="">${requestTxt}...</span>`;

        var allformdata = new FormData(form[0]);
        $.ajax({
            type: method
            , url: url
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: allformdata
            , mimeType: "multipart/form-data"
            , contentType: false
            , cache: false
            , processData: false
            , dataType: "json"
            , beforeSend: function() {
                obj.attr("disabled", true);
                obj.html(loader);
            },

            success: function(response) {
                console.log(response);
                if (response.status == false) {
                    $.each(response.errors, function(index, value) {
                        form.find('.required').each(function() {
                            var fieldName = $(this).attr('name');

                            if (fieldName == index && value != "") {
                                $(this).addClass("is-invalid");
                                $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                $(this).focus();
                            }
                        });
                    });
                } else {
                    if (typeof(response.message) != "undefined") {
                        console.log(response.message);
                        $("#regFrm")[0].reset();
                        toastr.success(response.message);
                    }
                }
                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
            , error: function(err) {}
        });
    });

</script>
<script>
        $(document).ready(function() {

            $('#fromdate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#todate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});

                $("#fromdate").datepicker({
                    //defaultDate:fromdate,
                    dateFormat: 'yy-mm-dd'
                });
                $("#todate").datepicker({
                    //defaultDate:todate,
                    dateFormat: 'yy-mm-dd'
                });

            $('#fromdate_receipt_closing').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#todate_receipt_closing').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});

                $("#fromdate_receipt_closing").datepicker({
                    //defaultDate:fromdate,
                    dateFormat: 'yy-mm-dd'
                });
                $("#todate_receipt_closing").datepicker({
                    //defaultDate:todate,
                    dateFormat: 'yy-mm-dd'
                });

            $('#fromdate_extra').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#todate_extra').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});

                $("#fromdate_extra").datepicker({
                    //defaultDate:fromdate,
                    dateFormat: 'yy-mm-dd'
                });
                $("#todate_extra").datepicker({
                    //defaultDate:todate,
                    dateFormat: 'yy-mm-dd'
                });
            $('.ui-datepicker').addClass('notranslate');

        })
    </script>
@endsection
