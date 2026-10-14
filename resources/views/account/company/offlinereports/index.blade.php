@extends('layouts.client.vendor')
@section('title','offline| Visitors')
@section('content')
@php $user = auth()->user(); @endphp

<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Offline Reports</h1>
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

                <style>
                    .hotel-search {
                        margin-right: 25px;
                        width: 200px;
                    }

                </style>
                <div class="row">
                    <div class="col-sm-12">

                        <form action="" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="box-title">Filter Reports </h3>
                                </div>

                                <div class="col-md-1">
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-md-flex">

                                        <div class="input-group mb-12">

                                            @if(request()->path() == 'company/allvisitors')
                                            <input type="hidden" name="module" value="visitors">
                                            @elseif(request()->path() == 'company/allreceiptclosing')
                                            <input type="hidden" name="module" value="receiptclosing">
                                            @elseif(request()->path() == 'company/allextra/reports')
                                            <input type="hidden" name="module" value="extra">
                                            @endif
                                            <input type="hidden" name="type" value="filter">
                                            <input type="text" name="fromdate" autocomplete="off" value="{{ session('filterdata')['fromdate']??'' }}" id="fromdate" required class="form-control" placeholder="From date">
                                            <input type="text" name="todate" autocomplete="off" value="{{ session('filterdata')['todate']??'' }}" id="todate" required class="form-control" placeholder="To date">

                                            <div class="input-group-append">
                                                <input class="btn btn-primary" name="search" type="submit" value="Go!">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <br>


                        <div class="white-box">


                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="box-title">

                                        @if(request()->path() == 'company/allvisitors')
                                        Visitors Reports List
                                        @elseif(request()->path() == 'company/allreceiptclosing')
                                        Receipt closing Reports List
                                        @elseif(request()->path() == 'company/allextra/reports')
                                        Extra Reports List
                                        @endif

                                    </h3>
                                </div>

                                <div class="col-md-4">



                                    <?php
                                $getFeatureUsesValue = Helper::getFeatureUsesValue('offline-reports');
                                if($getFeatureUsesValue <= count($reportsdata)){
                            ?>
                                    <a onclick="alert('Please upgrade profile. you can not add offline reports greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offline Reports</a>
                                    <?php }else{ ?>
                                    @if ($user->hasPermission('add') == true)
                                    <a href="{{ route('company.offline.reports') }}" class="btn btn-primary  d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offline Reports</a>
                                    @endif
                                    <?php } ?>


                                </div>
                            </div>

                            <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">S.No</th>

                                            <th class="border-top-0">From date</th>
                                            <th class="border-top-0">To date</th>
                                            <th class="border-top-0">Value</th>
                                            <th class="border-top-0">City</th>
                                            <th class="border-top-0">Zip Code</th>
                                            <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                $i = 0;
                                $datacount = count($reportsdata);
                                if ($datacount>0){
                                ?>
                                        @foreach($reportsdata as $rdata)
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td>{{ $rdata->fromdate }}</td>
                                            <td>{{ $rdata->todate }}</td>
                                            <td>{{ $rdata->value }}</td>
                                            <td>{{ $rdata->city??'N/A' }}</td>
                                            <td>{{ $rdata->zipcode??'N/A' }}</td>
                                            <td>
                                                @if ($user->hasPermission('status') == true)
                                                <input data-id="{{$rdata->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($rdata->status==1) ? 'checked' : '' }} onChange="changeStatus('<?php echo $rdata->status ?>','<?php echo $rdata->id ?>');">
                                                @endif
                                            </td>
                                            <td style="width: 15%;">
                                                @if ($user->hasPermission('edit') == true)
                                                <a class="btn btn-primary btn-sm" href="{{ route('company.offline.reports.edit',$rdata->id) }} ">EDIT</a>
                                                @endif
                                                @if ($user->hasPermission('delete') == true)
                                                <a class="btn btn-danger btn-sm show_confirm" href="{{ route('company.offline.reports.destroy',$rdata->id) }}">DELETE</a>
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
                                {{ $reportsdata->appends($query)->links() }}
                                @else
                                {!! $reportsdata->links() !!}
                                @endif
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

    $(document).ready(function() {

        $("#fromdate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#todate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('.ui-datepicker').addClass('notranslate');
    });

    var fromdate = "{{ session('filterdata')['fromdate']??'' }}";
    var todate = "{{ session('filterdata')['todate']??'' }}";

    console.log(fromdate)

    $('#fromdate').val(fromdate);
    $('#todate').val(todate);

    $(function() {

        $(".ranges ul li").click(function() {
            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder", "Please select date range");
        });

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#fromdate').val(start.format('YYYY-MM-DD'));
            $('#todate').val(end.format('YYYY-MM-DD'));
            fromdate
        }

        $('#reportrange').daterangepicker({
            singleDatePicker: true
            , showDropdowns: true
            , startDate: start
            , endDate: end
            , ranges: {
                'Today': [moment(), moment()]
                , 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
                , 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
                , 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                , 'This Month': [moment().startOf('month'), moment().endOf('month')]
                , 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        
    });


    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    function changeStatus(statusdata, id) {

        var status = (statusdata != 0) ? 0 : 1;

        console.log(status);
        console.log("under statusChange : ", statusdata);
        console.log("user id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/company/offline-reports/changeStatus'
            , data: {
                'status': status
                , 'id': id
            }
            , success: function(data) {
                console.log(data.success);
            }
        });
    }

</script>
<style>
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }

    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }

</style>
@endsection
