@extends('layouts.adminmaster')
@section('title','Order Status| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
        .d-none{
            display: unset !important;
        }

    }
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
    .hotel-search {
        margin-right: 25px;
        width: 200px;
    }
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Order Status</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.orderstatus.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Order Status</a>
                    @endif
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

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('admin.orderstatus.indexsearch') }}" method="get" class="">

                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="box-title">Order Status List</h3>
                            </div>

                            <div class="col-md-4">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="border-top-0"> Name</th>
                                   
                                    <th class="border-top-0"> Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  $datacount = count($order); if ($datacount>0){ ?>
                                @foreach($order as $key => $orderdata)
                                <tr>
                                    <!-- <td>{{ $i++ }}</td> -->
                                    <td> {{ ($order->currentpage()-1) * $order->perpage() + $key + 1 }}</td>
                                    <td>{{ $orderdata->name }}</td>
                                   
                                    <td>
                                    @if ($user->hasPermission('status') == true)
                                    <input data-id="{{$orderdata->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($orderdata->active=='1') ? 'checked' : '' }} onChange="changeStatus('<?php echo $orderdata->active ?>','<?php echo $orderdata->id ?>');"></td>
                                    @endif
                                    <td>
                                    @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin.orderstatus.edit', $orderdata->id) }}">Edit</a>
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
                        {{ $order->appends($query)->links() }}
                        @else
                        {!! $order->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script type="text/javascript">
    function changeStatus(statusdata, id) {
        var status = (statusdata != '0') ? '0' : '1';
        console.log(status);
        console.log("under statusChange : ", statusdata);
        console.log("user id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/order-status/changeStatus'
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
@endsection
