@extends('layouts.client.vendor')
@section('title','Order| Listing')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px !important;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    #infotooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #infotooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #infotooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -7px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #infotooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #infotooltip:hover #infotooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }

    @media screen and (max-width: 600px) {
        .confirmed:after {
            top: 194px !important;
        }
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->

<!-- Modal For Update Bulk Role ---->
<div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form name="SendBulkMessageFrm" id="SendBulkMessageFrm" action="{{ url('company/sendBulkMessage') }}" method="post">
                <input type="hidden" name="user_ids" id="user_ids">
                @csrf
                <div class="modal-header cus_header">
                    <h3 class="modal-title"  id="exampleModalLabel">Message</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" required>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                           
                            <div class="col-sm-12">
                                   <textarea id="message" name="message" class="ckeditor form-control p-0 border-0" placeholder="Please type message here"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer cus_footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="float: right;" id="SendBulkMessage">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<main>
    <div class="container margin_60_35">

            <div class="page-wrapper">

                <div class="container-fluid">
                    @if(session()->get('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div><br />
                    @endif



                    <div class="white-box">
                        <form action="{{ route('company.order.indexsearch') }}" method="get" class="">

                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="box-title">Order List</h3>
                                    <button title="Bulk Change Role" class="btn btn-sm btn-primary" id="bulkMessages" data-toggle="modal" data-target="#exampleModal6" data-id="" data-index="" type="button">
                                        <i class="fa fa-snowflake-o" aria-hidden="true"></i>
                                        Send Bulk Messages
                                    </button>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-md-flex">

                                        <div class="input-group mb-3" style="display: flex;">
                                            <div id="tooltip">
                                                <span id="tooltipText">For order id search please add prefix or enter value like ORDID-59.</span>
                                                <span><i class="fas fa-info-circle" ></i></span>
                                            </div>
                                            <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                        
                                        <th class="border-top-0">
                                            <input type="checkbox" id="ckbCheckAll" />
                                        </th>
                                        <th class="border-top-0 notranslate">Order ID / Order Date / Status</th>
                                        <th class="border-top-0">User Details</th>
                                        <th class="border-top-0">Total Amount </th>
                                        <th class="border-top-0">Refund</th>
                                        <th class="border-top-0">Action</th>
                                        <th class="border-top-0">Manage Status</th>
                                        <th class="border-top-0">Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php $i = 0; $datacount = count($order); if ($datacount>0){?>
                                    @foreach($order as $orderdata)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="userid[]" value="{!! $orderdata->user_id??'' !!}" class="checkBoxClass" id="Checkbox{{ $row->id??'' }}" />
                                        </td>
                                        <td>
                                            <?php echo "<b>ID #".$orderdata->id."</b>"; ?> <br>
                                            {{ $orderdata->created_at }} <br>

                                            @if($orderdata->status==1)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Processing</span>
                                            @endif
                                            @if($orderdata->status==2)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Packed</span>
                                            @endif
                                            @if($orderdata->status==3)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Shipped</span>
                                            @endif
                                            @if($orderdata->status==4)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Payment Cancelled</span>
                                            @endif
                                            @if($orderdata->status==5)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Delivered</span>
                                            @endif
                                            @if($orderdata->status==6)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">RTO</span>
                                            @endif
                                            @if($orderdata->status==7)
                                            <span class="btn btn-success btn-sm" style="cursor: auto;">Refunded</span>
                                            @endif
                                        </td>
                                        <td>{{ $orderdata->first_name.' '.$orderdata->last_name }} <br>
                                            {{ $orderdata->phone }} <br>
                                            {{ $orderdata->email }}
                                        </td>
                                      
                                        @if($orderdata->currency_symbol)
                                            <td class="notranslate">{{ $orderdata->currency_symbol }} {{ $orderdata->total_amount+$orderdata->shipping_rate+$orderdata->total_tax }}.00</td>
                                        @else
                                            <td class="notranslate">{{ $orderdata->currency }} {{ $orderdata->total_amount??'' }}</td>
                                        @endif
                                        
                                        <td>
                                        
                                            @if((isset($orderdata->refund_request_status) && (int) $orderdata->refund_request_status === 1) || (isset($orderdata->refund) && (int) $orderdata->refund->status === 1))
                                            <span class="btn btn-danger btn-sm" style="cursor: auto;">Requested</span>
                                            @elseif((int) $orderdata->status === 7)
                                            <span class="btn btn-success btn-sm" style="cursor: auto;">Refunded</span>
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('view') == true)
                                            <a class="btn btn-primary btn-sm" href="{{ route('company.order.view', $orderdata->id) }}"><i class="fa fa-eye"></i> VIEW</a>
                                        @endif
                                         </td>
                                        <td> 
                                            <a class="btn btn-primary btn-sm" href="{{ route('company.order.track', $orderdata->id) }}"> Update Order status</a>
                                        </td>
                                        <td>
                                         @if ($user->hasPermission('download') == true)
                                        <a href="{{route('generate.pdf', $orderdata->id)}}" title="Download Invoice" style="font-size:25px;color: #e31212;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>
                                         @endif
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
                        <div class="d-flex justify-content-center cust-page orderviewpagination">
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
</main>


<script>

$("form#SendBulkMessageFrm").submit(function(e) {
        e.preventDefault();

        var message = CKEDITOR.instances['message'].getData();

        if(message == ''){
            alert('Please enter a message');
            return false;
        }

        var form = $("#SendBulkMessageFrm");
        var formData = new FormData(this);
            formData.append('message', message);		
        $.ajax({
            url: form.attr("action")
            , type: 'POST'
            , data: formData
            , beforeSend: function() {
                $('#SendBulkMessage').prop('disabled', true);
                $('#SendBulkMessage').html('Please Wait');
            }
            , success: function(response) {
                var obj = jQuery.parseJSON(response);
                console.log(obj);
                if (obj.status == "success") {

                    toastr.success(obj.msg);

                    $('#SendBulkMessage').prop('disabled', false);
                    $('#SendBulkMessage').html('Send All');

                    $("#SendBulkMessageFrm")[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 9000);
                } else {

                    toastr.error(obj.msg);

                    $('#SendBulkMessage').prop('disabled', false);
                    $('#SendBulkMessage').html('Save');
                }
            }
            , cache: false
            , contentType: false
            , processData: false
        });
    });

    $(document).ready(function() {

        $("#ckbCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });

        $("#bulkMessages").click(function() {
            var checkedNum = $('input[name="userid[]"]:checked').length;
            if (!checkedNum) {

                toastr.error('{{__("Please check at least one record!")}}');
                return false;
            } else {
                var selectedUId = new Array();
                $('input[name="userid[]"]:checked').each(function() {
                    selectedUId.push(this.value);
                    $('body #user_ids').val(selectedUId);
                });
            }
        });

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
    .modal {
        top: 60px;
    }
    .modal-dialog {
        max-width: 630px;
    }
</style>

@endsection
