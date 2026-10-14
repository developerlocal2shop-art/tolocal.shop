@extends('layouts.adminmaster')
@section('title','Ticket Request| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
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

<?php

//echo "<pre>"; print_r($data);
//die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Ticket Request</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    <!-- <a href="" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Edit Booking</a> -->
                </div>
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

        <style>
            .hotel-search { margin-right: 25px; width:200px;}

        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.booking.indexsearch') }}" method="get" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Ticket Request List</h3>
                        </div>

                            <div class="col-md-4">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $bookingsearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                    </form>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Directory</th>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Title</th>
                                    <th class="border-top-0">Email</th>
                                    <th class="border-top-0">Phone</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($bookingdata); if ($datacount>0){?>
                                @foreach($bookingdata as $key => $bookingdatarow)
                                    <tr>
                                        <!-- <td><?php echo ++$i; ?></td> -->
                                        <td> {{ ($bookingdata->currentpage()-1) * $bookingdata->perpage() + $key + 1 }}</td>
                                        <td>
                                            @php
                                            $directory = Helper::getDirectory($bookingdatarow->directory_id);
                                            echo isset($directory[0]->title)?$directory[0]->title:"None";
                                            @endphp
                                        </td>
                                        <td>{{ $bookingdatarow->vendor->name??'' }} {{ $bookingdatarow->vendor->last_name??'' }}</td>
                                         <td>{{ $bookingdatarow->title }}</td>
                                        <td>{{ $bookingdatarow->email }}</td>
                                        <td>{{ $bookingdatarow->phone }}</td>
                                        
                                        <td>
                                        @if ($user->hasPermission('status') == true)
                                        <input data-id="{{$bookingdatarow->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" disabled data-on="Accepted" data-off="Pending" {{ ($bookingdatarow->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $bookingdatarow->status ?>','<?php echo $bookingdatarow->id ?>');">
                                        @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{ route('admin.ticket.view',$bookingdatarow->id) }} ">View</a>
                                            <a class="btn btn-danger btn-xs show_confirm" href="{{ route('admin.ticket.destroy',$bookingdatarow->id) }}">DELETE</a>
                                        </td>
                                    </tr>
                                @endforeach
                            <?php }else{ ?>
                                    <tr>
                                        <td align="center" colspan="10">No Data Found</td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $bookingdata->appends($query)->links() }}
                        @else
                        {!! $bookingdata->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

<script type="text/javascript">
    /*function changeStatus(statusdata, id) {
        var status = (statusdata != 'Inactive') ? 'Inactive' : 'Active';
        console.log(status);
        console.log("under statusChange : ", statusdata);
        console.log("user id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/ticket/changeTicketStatus'
            , data: {
                'status': status
                , 'id': id
            }
            , success: function(data) {
                console.log(data.success);
            }
        });
    }*/

</script>

@endsection
