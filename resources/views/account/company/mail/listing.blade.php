@extends('layouts.client.vendor')
@section('title','Mail| Listing')
@section('content')
@php $userr = auth()->user(); @endphp

<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Mail Listing</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="page-wrapper">

                <div class="container-fluid">
                    @if(session()->get('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div><br />
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">
                                <form action="{{ route('company.mail.indexsearch') }}" method="get" class="">

                                    <div class="row">
                                        <div class="col-lg-2 col-md-3">
                                            <h3 class="box-title">Mail List</h3>
                                        </div>
                                        <div class="col-lg-1 col-md-2">
                                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 9px 10px; border: 1px solid #ccc; width: 100%">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span></span> <i class="fa fa-caret-down"></i>
                                            </div>
                                            {{-- <i class="fa fa-calendar"></i>
                                        <input type="text" name="reportrange" id="reportrange" required placeholder="Please sele" class="form-control"> --}}
                                        </div>
                                        <div class="col-lg-5 col-md-7">
                                            <div class="d-md-flex">
                                                <div class="input-group mb-3">
                                                    <input type="text" name="fromdate" title="From Date" value="{{ $fromDate }}" id="fromdate" class="form-control" placeholder="From date">
                                                    <input type="text" name="todate" title="To Date" value="{{ $toDate }}" id="todate" class="form-control" placeholder="To date">
                                                    <input type="text" name="searchtext" id="search" value="{{ $searchtext??'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-10 col-8">

                                            <?php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('mail');
                                                if($getFeatureUsesValue <= count($maildata)){
                                            ?>
                                            <a onclick="alert('Please upgrade member account. you can not add user mail greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary mail-add-btn d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
                                            <?php }else{ ?>
                                            @if ($userr->hasPermission('add') == true)
                                            <a href="{{ route('company.mailtemplate.create') }}" style="float:left;" class="btn btn-primary d-md-block ms-3 hidden-xs hidden-sm waves-effect waves-light text-white companyAdd add-mail">Add Mail Template</a>

                                            @endif
                                            <?php } ?>

                                        </div>
                                        <div class="col-md-2 col-4">
                                            <a href="{{ route('company.mail.create') }}" class="btn btn-primary mail-add-btn d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white companyAdd">Send New</a>
                                        </div>


                                    </div>
                                </form>
                                <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                                <div class="table-responsive">
                                    <table class="table mailtable">
                                        <thead>
                                            <tr>
                                                <!-- <th class="border-top-0">#</th> -->
                                                <th class="border-top-0">Mail to</th>
                                                <th class="border-top-0">Subject</th>
                                                {{-- <th class="border-top-0" style="width:400px;white-space: normal;word-break: break-word;">Content</th> --}}
                                                <th class="border-top-0" style="width:100px;white-space: normal;word-break: break-word;">Status</th>
                                                <th class="border-top-0" style="width:150px;white-space: normal;word-break: break-word;">Date</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=0; $datacount = count($maildata); if ($datacount>0){ foreach ($maildata as $row){ //echo "<pre>"; print_r($row);                                     ?>
                                            <tr>
                                                <!-- <td><?php echo ++$i ?></td> -->
                                                <td><?php echo $row->to_email ?></td>
                                                <td><?php echo $row->subject ?></td>
                                                {{-- <td><?php //echo $row->content ?></td> --}}
                                                <td><?php echo $row->status ?></td>
                                                <td><?php echo $row->send_date ?></td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm" href="<?php echo url("/company/view-mail",['id' => @$row->id]); ?>">VIEW</a>
                                                    <!-- <a class="btn btn-danger btn-xs show_confirm"  href="<?php //echo url("/company/view-mail",['id' => @$row->id]); ?>" >DELETE</a> -->
                                                </td>
                                            </tr>
                                            <?php } }else{?>
                                            <tr>
                                                <td align="center" colspan="4">No Data Found</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="d-flex justify-content-center cust-page">
                                    @if(isset($query))
                                    {{ $maildata->appends($query)->links() }}
                                    @else
                                    {!! $maildata->links() !!}
                                    @endif

                                </div>
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
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    $(document).ready(function() {

        $( "#fromdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#todate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            
            $('.ui-datepicker').addClass('notranslate');
        
    })

    $(function() {

        $(".ranges ul li").click(function() {
            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder", "Please select date range");
        });

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#fromdate').val(start.format('YYYY-MM-DD'));
            $('#todate').val(end.format('YYYY-MM-DD'));

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

        $('input[name="reportrange"]').val('');
        $('input[name="reportrange"]').attr("placeholder", "Please select date range");
        //cb(start, end);
    });

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

    .pagination {
        float: right !important;
    }

    .mailtable.table td,
    .mailtable.table th {
        white-space: normal;
        word-break: break-word;
    }

    @media(max-width:991px) {
        .add-mail {
            float: right !important;
        }
    }

    @media(max-width:767px) {
        .mailtable.table td,
        .mailtable.table th {
            white-space: nowrap !important;
            word-break: normal;
        }
        div#reportrange {
            margin-bottom: 10px;
        }
    }

</style>
@endsection
