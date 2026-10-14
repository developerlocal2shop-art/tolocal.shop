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
                                <h3 class="box-title">Trash Order List</h3>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <div class="order-tab">
                            <a class="{{ (request()->is('admin/orders')) ? 'active' : '' }}" href="{{route('admin.order.index')}}">All({{ Helper::totalAdminOrders() }})</a>
                            <a class="{{ (request()->is('admin/softdeleteorders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.softdeleteorders')}}">Trash({{ Helper::totalTrashOrders() }})</a>
                            <a class="{{ (request()->is('admin/pending-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.pendingorders')}}">Processing({{ Helper::totalPendingOrders() }})</a>
                            <a class="{{ (request()->is('admin/processing-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.processingorders')}}">Packed({{ Helper::totalProcessingOrders() }})</a>
                            <a class="{{ (request()->is('admin/payment-cancel-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.paymentcancelorders')}}">Payment Cancelled({{ Helper::totalPaymentCancelOrders() }})</a>
                            <a class="{{ (request()->is('admin/delivered-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.deliveredorders')}}">Delivered({{ Helper::totalDeliveredOrders() }})</a>
                            <a class="{{ (request()->is('admin/rto-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.rtoorders')}}">RTO({{ Helper::totalRtoOrders() }})</a>
                            <a class="{{ (request()->is('admin/refunded-orders')) ? 'active' : '' }}" style="margin-left:10px;" href="{{route('admin.order.refundedorders')}}">Refunded({{ Helper::totalRefundedOrders() }})</a>
                        </div>
                        <div class="col-md-4" style="margin-top:10px;">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <div class="input-group mb-3">
                                    <select name="bulkaction" class="form-control" id="bulkaction_selected" data-allow-clear="true">
                                        <option value="bulk_action">Bulk Actions</option>
                                        <option value="restoreall">Restore</option>
                                        <option value="deleteallorders">Delete Permanently</option>
                                    </select>
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" name="filter" id="bulkaction" type="submit" value="Apply" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0" style="width: 70px;">
                                    <input type="checkbox" id="checkAll" class="checkall">Check All</th>
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
                                <?php $i = 0; $datacount = count($softdeletedorder); if ($datacount>0){?>
                                @foreach($softdeletedorder as $orderdata)
                                    <tr>
                                        {{-- <td><?php echo ++$i; ?></td> --}}
                                        <td>
                                            <input type="checkbox" name="ids" class="cb-element" value="{!! $orderdata->id !!}" />
                                        </td>
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
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Processing</span>
                                            @endif
                                            @if($orderdata->status==2)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Packed</span>
                                            @endif
                                            @if($orderdata->status==3)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Shipped</span>
                                            @endif
                                            @if($orderdata->status==4)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Payment Cancelled</span>
                                            @endif
                                            @if($orderdata->status==5)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Delivered</span>
                                            @endif
                                            @if($orderdata->status==6)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">RTO</span>
                                            @endif
                                            @if($orderdata->status==7)
                                            <span class="btn btn-primary btn-xs" style="cursor: auto;">Refunded</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('view') == true)
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/trashed-view",['id' => @$orderdata->id]); ?>">VIEW</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/permnentlydeleteorders",['id' => @$orderdata->id]); ?>">DELETE</a>
                                        @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('download') == true)
                                        <a href="{{route('trashedordersgenerate.pdf', $orderdata->id)}}" title="Download Invoice" style="font-size:25px;color: #e31212;"><i class="far fa-file-pdf" aria-hidden="true"></i></a>
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
                        {{ $softdeletedorder->appends($query)->links() }}
                        @else
                        {!! $softdeletedorder->links() !!}
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
        if (!confirm('Are you sure you want to permanently delete this?')) {
            e.preventDefault();
        }
    });

</script>
<script type="text/javascript">
   $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    
    $('#bulkaction').click(function(e){
        e.preventDefault();
        var e = document.getElementById("bulkaction_selected");
        
        var value = e.value;
        //alert(value);
        if(value=='restoreall'){

            var allids = [];

            $("input:checkbox[name=ids]:checked").each(function(){
                allids.push($(this).val());
                // allids.push($(this).attr('data-id'));
            });
            //alert(allids);
            if(allids.length <= 0){
                
                toastr.error('Please select at least one record');
                return false;
            }
            else{
                var check = confirm("Are you sure you want to restore this records!");
                
                if(check==true){
                    var selected_values = allids.join(",");
                    
                    $.ajax({
                        url: baseUrl + '/admin/restore',
                        type: "get",
                        data: {
                            'ids': selected_values,
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(data){
                            console.log(data.success);
                                if(data['success']){
                                
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
                        
                    });
                }
            }
        }
    });

    $('#bulkaction').click(function(e){
        e.preventDefault();
        var e = document.getElementById("bulkaction_selected");
        
        var value = e.value;
        //alert(value);
        if(value=='deleteallorders'){

            var allids = [];

            $("input:checkbox[name=ids]:checked").each(function(){
                allids.push($(this).val());
                // allids.push($(this).attr('data-id'));
            });
            //alert(allids);
            if(allids.length <= 0){
                
                toastr.error('Please select at least one record');
                return false;
            }
            else{
                var check = confirm("Are you sure you want to permanently delete this records!");
                
                if(check==true){
                    var selected_values = allids.join(",");
                    
                    $.ajax({
                        url: baseUrl + '/admin/bulk-delete-permanently',
                        type: "get",
                        data: {
                            'ids': selected_values,
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(data){
                            console.log(data.success);
                                if(data['success']){
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
        }
    });
</script>

@endsection
