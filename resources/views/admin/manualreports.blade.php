@extends('layouts.adminmaster')
@section('title','Dashboard')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }
    i.fa.fa-eye {
        color: #d82f2f;
    }
    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }

    .pagination {
        float: right !important;
    }

    .manualreports .dashboard-filter {
        background: #f5f5f5;
        padding: 10px 0px;
        border-radius: 15px;
        border: 2px solid #ddd;
    }

    .manualreports textarea {
        border: 2px solid #004dda;
        border-radius: 0;
        background: #4473c5;
        color: #fff;
        text-align: center;
        padding: 10px !important;
        margin-bottom: 5px !important;
    }

    .manualeDateField {
        background: #ededed;
        padding: 8px 0;
        margin-bottom: 0;
    }

    .manualreports .box-title {
        background: #eeeeeb;
        padding: 10px 0px;
        border-radius: 50px;
        position: relative;
    }

    .manualreports textarea:focus {
        background: #2f4a7c;
        border: 2px solid #004dda;
        color: #fff;
    }

    .manualreports .input-group-append input.btn {
        border-radius: 0 50px 50px 0;
    }

    .manualreports .input-group-append input.btn {
        border-radius: 0 50px 50px 0;
        background: #7179da;
        border-color: #7179da;
    }

    .manualreports .box-title input[type="text"] {
        width: 100%;
        border: 1px solid #004dda;
        background: #fff;
        color: #000;
        text-align: center;
        padding: 0px 65px 0px 10px !important;
        border-radius: 0 50px 50px 0;
    }

    .red {
        background: none !important;
    }

    .manualreports .col-lg-12 {
        padding-left: 0;
        padding-right: 0;
    }

    .manualreports .input-group-append {
        position: absolute;
        right: 0;
        top: 10px;
    }

    .manualreports .input-group-append button.submitbtn {
        border-radius: 0 50px 50px 0;
        height: 35px;
    }

    span.red.invalid-feedback {
        font-size: 12px;
        font-weight: normal;
    }

</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Offline Reports</h4>
            </div>

        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="container-fluid">

        @if ($user->hasPermission('view') == true)

        

        <div class="white-box manualreports">

            <div class="row dashboard-filter">

                <div class="col-md-4">

                    <form method="POST" id="regFrm" action="{{ route('admin.offline.submit.reports') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row dashbord-option mb-4">
                            <div class="col-md-12">

                                <a class="btn btn-primary btn-sm" href="{{ route('admin.all.visitors') }}">View all</a><hr>
                                
<b><i class="fa fa-eye" aria-hidden="true"></i> No. of shop visitors</b>
                                <label class="mb-4"> Please fill in non digital data for: No. of shop visitors in the shop today or by any dates </label>

                                <div class="d-md-flex manualeDateField">
                                    <div class="input-group mb-12">
                                        
                                        <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="city">
                                        <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="zip code">

                                    </div>
                                    
                                </div>
                                <div class="d-md-flex manualeDateField">
                                    <div class="input-group mb-12">
                                        <input type="hidden" name="type" value="visitors">
                                        <input type="date" name="fromdate" title="From Date" required="" id="fromdate" class="form-control required" placeholder="From date">
                                        <input type="date" name="todate" title="To Date" required="" id="todate" class="form-control required" placeholder="To date">

                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="box-title">
                                        <input type="text" autocomplete="off" name="value" required="" value="" id="value" class="form-control required">
                                        <div class="input-group-append">
                                            <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
                                            {{-- <input class="btn btn-primary" name="search" type="submit" value="Go!"> --}}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <form method="POST" id="regFrm" action="{{ route('admin.offline.submit.reports') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row dashbord-option mb-4">

                            <div class="col-md-12">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.all.receiptclosing') }}">View all</a><hr>
                                <b><i class="fa fa-eye" aria-hidden="true"></i> No. of receipt closing</b>
                                <label class="mb-4">Please fill in non-digital data for: No. of receipt closing in the shop today or by any dates. </label>

                                <div class="d-md-flex manualeDateField">
                                    <div class="input-group mb-12">
                                        
                                        <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="city">
                                        <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="zip code">

                                    </div>
                                    
                                </div>
                                <div class="d-md-flex manualeDateField">

                                    <div class="input-group mb-12">

                                        <input type="hidden" name="type" value="receipt_closing">
                                        <input type="date" name="fromdate" title="From Date" required="" id="fromdate" class="form-control required" placeholder="From date">
                                        <input type="date" name="todate" title="To Date" required="" id="todate" class="form-control required" placeholder="To date">

                                    </div>

                                </div>

                                <div class="col-lg-12 col-md-12">

                                    <div class="box-title">

                                        <input type="text" autocomplete="off" name="value" required="" id="value" class="form-control required">
                                        <div class="input-group-append">
                                            {{-- <input class="btn btn-primary" name="search" type="submit" value="Go!"> --}}
                                            <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <form method="POST" id="regFrm" action="{{ route('admin.offline.submit.reports') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row dashbord-option mb-4">

                            <div class="col-md-12">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.allextra.reports') }}">View all</a><hr>
                                <b><i class="fa fa-eye" aria-hidden="true"></i> Extra in the shop</b>
                                <label class="mb-4"> Please fill in non-digital data for: Extra in the shop today or by any dates.  </label>

                                <div class="d-md-flex manualeDateField">
                                    <div class="input-group mb-12">
                                        
                                        <input type="text" name="city" title="city" required="" id="city" class="form-control required" placeholder="city">
                                        <input type="text" name="zipcode" title="Zipcode" required="" id="zipcode" class="form-control required" placeholder="zip code">

                                    </div>
                                    
                                </div>
                                <div class="d-md-flex manualeDateField">

                                    <div class="input-group mb-12">
                                        <input type="hidden" name="type" value="extra">
                                        <input type="date" name="fromdate" title="From Date" required=""  id="fromdate" class="form-control required" placeholder="From date">
                                        <input type="date" name="todate" title="To Date" required=""  id="todate" class="form-control required" placeholder="To date">

                                    </div>

                                </div>

                                <div class="col-lg-12 col-md-12">

                                    <div class="box-title">

                                        <input type="text" autocomplete="off" name="value" required="" id="value" class="form-control required">
                                        <div class="input-group-append">
                                            {{-- <input class="btn btn-primary" disabled name="search" type="submit" value="Save!"> --}}
                                            <button type="submit" style="cursor:pointer" class="btn btn-primary submitbtn" process-text="Requested">Save!</button>
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
@endif


</div>

</div>

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

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
                        //$('.respondata').html('<p>'+response.message+'</p>');

                    }

                }
                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
            , error: function(err) {}
        });

    });

</script>

@endsection
