@extends('layouts.adminmaster')
@section('title','Booking| Listing')
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
                <h4 class="page-title">Booking</h4>
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
                            <h3 class="box-title">Booking List</h3>
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
                    <a class="btn btn-danger d-md-block pull-left ms-3 hidden-xs hidden-sm waves-effect waves-light text-white" id="deleteAllSelectedProducts"  style="margin-top:10px;">Bulk Delete</a>
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0" style="width: 70px;">
                                            <input type="checkbox" id="checkAll" class="checkall">Check All
                                    </th>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Directory</th>
                                    <th class="border-top-0">User Name</th>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Email</th>
                                    <th class="border-top-0">Phone no.</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($bookingdata); if ($datacount>0){?>
                                @foreach($bookingdata as $key => $bookingdatarow)
                                <?php //echo "<pre>"; print_r($bookingdatarow); ?>
                                    <tr>
                                        <!-- <td><?php //echo ++$i; ?></td> -->
                                        <td>
                                            <input type="checkbox" name="ids" class="cb-element" value="{!! $bookingdatarow->id !!}" />
                                        </td>
                                        <td> {{ ($bookingdata->currentpage()-1) * $bookingdata->perpage() + $key + 1 }}</td>
                                        <td>
                                            @php
                                            $directory = Helper::getDirectory($bookingdatarow->directory_id);
                                            echo isset($directory[0]->title)?$directory[0]->title:"None";
                                            @endphp
                                        </td>
                                        {{-- <td>{{ $bookingdatarow->user_id }}</td>  --}}
                                        <td>
                                            @php
                                            $username = Helper::getUserName($bookingdatarow->user_id);
                                            //echo $username[0]->name.' '.$username[0]->last_name;
                                            @endphp
                                            @if (!empty($username))
                                            {{ $username[0]->name.' '.$username[0]->last_name}}
                                            @else

                                            @endif
                                        </td>
                                        <td>{{ $bookingdatarow->name }}</td>
                                        <td>{{ $bookingdatarow->email }}</td>
                                        <td>{{ $bookingdatarow->phone }}</td>
                                        <td>{{ $bookingdatarow->status }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/booking-edit", ['id' => @$bookingdatarow->id]); ?>">EDIT</a>
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/booking-delete", ['id' => @$bookingdatarow->id]); ?>">DELETE</a>
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
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $('#deleteAllSelectedProducts').click(function(e){
        e.preventDefault();
        var allids = [];

        $("input:checkbox[name=ids]:checked").each(function(){
            allids.push($(this).val());
        });
        if(allids.length <= 0){
            
            toastr.error('Please select or checked at least one record');
            return false;
        }
        else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            
            if(check==true){
                var selected_values = allids.join(",");
                
                $.ajax({
                    url: baseUrl + '/admin/selected-delete-booking',
                    type: "get",
                    data: {
                        'ids': selected_values,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(data){
                        console.log(data.success);
                            if(data['success']){
                            //$(".cb-element").each(function(){
                                // $(this).parents("tr").remove()
                            //});
                            toastr.success(data['success']);
                            location.reload();
                        }else if(data['error']){
                            toastr.error(data['error']);
                        }else{
                            toastr.error("Something went wrong");
                        }
                    }
                });
                $.each(allids, function(key,val){
                    //$('table tr').filter("[data-row-id='" + val + "']").remove();
                });
            }
        }
    });

</script>

@endsection
