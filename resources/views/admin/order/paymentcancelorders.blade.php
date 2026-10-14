@extends('layouts.adminmaster')
@section('title','Order| Listing')
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
    .pagination{
        float: right !important;
    }
    .total-orders{
        margin-top:10px;
        font-size: 14px;
        border-radius: 3px;
    }

</style>


<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Order</h4>
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

        <style>.hotel-search { margin-right: 25px; width:200px;}</style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="box-title">Payment Cancel Order List</h3>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <div class="order-tab">
                            <a class="{{ (request()->is('admin/orders')) ? 'active' : '' }}" href="{{route('admin.order.index')}}">All({{ Helper::totalAdminOrders() }})</a>
                            <a class="{{ (request()->is('admin/softdeleteorders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.softdeleteorders')}}">Trash({{ Helper::totalTrashOrders() }})</a>
                            <a class="{{ (request()->is('admin/pending-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.pendingorders')}}">Pending({{ Helper::totalPendingOrders() }})</a>
                            <a class="{{ (request()->is('admin/processing-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.processingorders')}}">Processing({{ Helper::totalProcessingOrders() }})</a>
                            <a class="{{ (request()->is('admin/payment-cancel-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.paymentcancelorders')}}">Payment Cancelled({{ Helper::totalPaymentCancelOrders() }})</a>
                            <a class="{{ (request()->is('admin/delivered-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.deliveredorders')}}">Delivered({{ Helper::totalDeliveredOrders() }})</a>
                            <a class="{{ (request()->is('admin/rto-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.rtoorders')}}">RTO({{ Helper::totalRtoOrders() }})</a>
                            <a class="{{ (request()->is('admin/refunded-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.refundedorders')}}">Refunded({{ Helper::totalRefundedOrders() }})</a>
                        </div>
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Order Id</th>
                                    <th class="border-top-0">Full Name</th>
                                    <!-- <th class="border-top-0">Phone</th> -->
                                    <th class="border-top-0">Email</th>
                                    <th class="border-top-0">Total Amount</th>
                                    <th class="border-top-0">Total Tax</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                    <th class="border-top-0">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($paymentcancelorder); if ($datacount>0){?>
                                @foreach($paymentcancelorder as $orderdata)
                                    <tr>
                                        {{-- <td><?php echo ++$i; ?></td> --}}
                                        <td>#{{ $orderdata->id }}</td>
                                        <td>{{ $orderdata->first_name.' '.$orderdata->last_name }}</td>
                                        <!-- <td>{{ $orderdata->phone }}</td> -->
                                        <td>{{ $orderdata->email }}</td>
                                        @if($orderdata->currency_symbol)
                                            <td>{{ $orderdata->currency_symbol }} {{ $orderdata->total_amount??'' }}</td>
                                            <td>{{ $orderdata->currency_symbol }} {{ $orderdata ->total_tax }}</td>
                                        @else
                                            <td>{{ $orderdata->currency }} {{ $orderdata->total_amount??'' }}</td>
                                            <td>{{ $orderdata->currency }} {{ $orderdata ->total_tax }}</td>
                                        @endif
                                        <td>
                                        @if($orderdata->status==1)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">Pending</span>
                                        @endif
                                        @if($orderdata->status==2)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">Processing</span>
                                        @endif
                                        @if($orderdata->status==3)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">Payment Cancelled</span>
                                        @endif
                                        @if($orderdata->status==4)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">Delivered</span>
                                        @endif
                                        @if($orderdata->status==5)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">RTO</span>
                                        @endif
                                        @if($orderdata->status==6)
                                        <span class="btn btn-primary btn-xs" style="cursor: auto;">Refunded</span>
                                        @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('view') == true)
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/view",['id' => @$orderdata->id]); ?>">VIEW</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/order-delete",['id' => @$orderdata->id]); ?>">DELETE</a>
                                        @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('download') == true)
                                        <a href="{{route('generate.pdf', $orderdata->id)}}" title="Download Invoice" style="font-size:25px;color: #e31212;"><i class="far fa-file-pdf" aria-hidden="true"></i></a>
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
                        {{ $paymentcancelorder->appends($query)->links() }}
                        @else
                        {!! $paymentcancelorder->links() !!}
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
@endsection
