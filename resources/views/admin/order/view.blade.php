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
    .cus-title label {
        color: #fff;
    }
    ul.freeProList {
        display: flex;
        padding: 2px;
        margin: 0;
        align-items: center;
    }
    ul.freeProList li {
        margin-right: 5px;
        padding-right: 0px;
        border: 1px solid #e9e9e9;
    }
    ul.freeProList li b {
        color: #203864;
        font-weight: 500;
        font-size: 12px;
        padding-right: 10px;
    }
    ul, ol {
        list-style: none;
        margin: 0 0 20px 0;
        padding: 0;
    }
</style>

<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Reason for Return</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <p><b>Reason </b> - {{ $orderData->refund->reason??'' }}</p>
                    <p><b>Comment </b> - {{ $orderData->refund->comment??'' }}</p>
                </div>
            </div>
        </div>
    </div>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Order View</h4>
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

        <?php $adminFlatShippingData = Helper::getAdminFlatShippingData(); ?>
        <?php
            $flatRateValue = $adminFlatShippingData->flat_rate;

        ?>
        
        @php
            $billingcountryName = Helper::getCountryName($billing_address[0]->country);
            $billingstateName = Helper::getStateName($billing_address[0]->state);
            $billingCityName = Helper::getCityName($billing_address[0]->city_id);
            $shippingcountryName = Helper::getCountryName($shiping_address[0]->country);
            $shippingstateName = Helper::getStateName($shiping_address[0]->state);
            $shippingCityName = Helper::getCityName($shiping_address[0]->city_id);
        @endphp
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <div class="row">
                        <div class="col-md-8 ">
                            <div class=" mb-4">
                            
                                <div class="col-md-12">
                                <input type="hidden" name="email" id="email" value="{{ $orderData->email??'' }}">
                                <input type="hidden" name="phone" id="phone" value="{{ $orderData->phone??'' }}">
                                <div class="form-group">
                                    <label for="message_contact">Order status<span style="color:red;">*</span></label>
                                    <select class="form-control" name="status" id="select_status" onchange="selectStatus()">
                                        @foreach ($orderStatus as $key => $sval)
                                        <?php //if($sval->id != 5){ ?>
                                            @if ($orderData->status == 7)
                                               @if($key == 0)
                                                <option value="7" selected disabled>Refunded</option>
                                               @endif             
                                            @else
                                                <option value="{{ $sval->id }}" {{ $sval->id == $orderData->status ? 'selected' : '' }} > {{ $sval->name }}</option>
                                            @endif
                                        <?php //} ?>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message_contact">Message<span style="color:red;">*</span></label>
                                    <textarea class="form-control" placeholder="Please enter message of order status." style="height: 120px;" name="order_status_description" id="order_status_description">{{ $orderData->order_status_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    @if ($user->hasPermission('status') == true)
                                    <button type="submit" class="btn btn-info" <?php if($orderData->status == 7){echo $btndisable="disabled='disabled'";} ?> id="orderbutton" onclick="changeStatus('<?php echo $orderData->status ?>','<?php echo $orderData->id ?>');">
                                    Update status
                                    </button>
                                    @endif
                                </div>

                                </div>
                            </div>
                        </div>
                        
                            <div class="col-md-4" style="margin-top: 10px;">
                               
                                @if($orderData->refund)
                                    <button type="button" class="btn btn-primary btn-sm" style="float: right;margin: 25px 15px;" data-toggle="modal" data-target="#myModal">
                                    View refund request
                                    </button>
                                    @if((int) $orderData->refund->status === 1 && (int) $orderData->status !== 7)
                                        <p class="text-warning" style="float: right; clear: right;">Refund request pending</p>
                                    @elseif((int) $orderData->status === 7)
                                        <p class="text-success" style="float: right; clear: right;">Refund completed</p>
                                    @endif
                                @endif
                                
                            </div>
                        
                        
                    </div>
                    

                    <div class="row">
                        <div class="col-md-8" style="display: flex;">
                            <div><h3 class="box-title">Order #{{ $orderData->id }} Details</h3></div>
                        @if ($user->hasPermission('download') == true)
                            <div style="margin-left: 10px;"><a href="{{route('generate.pdf', $orderData->id)}}" title="Download Invoice" style="font-size:20px;color: #e31212;"><i class="far fa-file-pdf" aria-hidden="true"></i></a></div>
                        @endif
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Order #{{ $orderData->id }} Details</h3>
                        </div>
                    </div> -->

                    <div class="alert alert-success d-none" id="msg_div">
                      <span id="res_message"></span>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Customer Information</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="mb-4">
                                <div class="p-0">
                                    <div rows="5" style="height: auto; padding: 13px !important;" name="billing_address" class="form-control p-0 border-0" >
                                    Name  - {{ $orderData->first_name.' '.$orderData->last_name }}  <br>
                                     Email  -  {{ $orderData->email }} <br>
                                      Phone - {{ $orderData->phone }} <br>
                                       Created - {{ $orderData->created_at->toDateString() }} - {{ $orderData->created_at->format('H:i:s') }}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <h4 class="box-title"><label class="col-md-12 panel-heading cust-tab-head" style="padding-top: 3px;">Billing Address</label></h4>
                                <div class="col-md-12 p-0">
                                    <div rows="5" style="height: auto; padding: 13px !important;" name="billing_address" class="form-control p-0 border-0" >
                                    {{ $billing_address[0]->address1}}  <br>
                                    {{$billing_address[0]->address2}} <br>
                                    @if ($billing_address[0]->city !=null)
                                        {{ $billing_address[0]->city.' ,'.$billingstateName->name}} <br>
                                    @else
                                            @if($billingCityName)
                                                {{ $billingCityName->name??''}}  
                                            @endif
                                        {{ $billingstateName->name??''}} <br>
                                    @endif

                                    {{$billingcountryName->name??''}} <br>
                                    {{$billing_address[0]->zipcode }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <h4 class="box-title"><label class="col-md-12 panel-heading cust-tab-head" style="padding-top: 3px;">Shipping Address</label></h4>
                                <div class="col-md-12 p-0">
                                    <div rows="5" style="height: auto; padding: 13px !important;" name="shipping_address" class="form-control p-0 border-0" >
                                    {{ $shiping_address[0]->address1}}  <br>
                                    {{$shiping_address[0]->address2}} <br>
                                    @if($shiping_address[0]->city !=null)
                                        {{ $shiping_address[0]->city.' ,'.$shippingstateName->name}} <br>
                                    @else
                                        {{ $shippingCityName->name.' ,'.$shippingstateName->name}} <br>
                                    @endif
                                    {{$shippingcountryName->name}} <br>
                                    {{$shiping_address[0]->zipcode }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row" style="background-color: #c9c8c838; padding-top: 12px;">
                        <div class="col-md-6">
                            <label style="font-weight: 700;">Item</label>
                        </div>
                        <div class="col-md-2" >
                            <label style="font-weight: 700;">Cost</label>
                        </div>
                        <div class="col-md-2">
                            <label style="font-weight: 700;">Qty</label>
                        </div>
                        <div class="col-md-2">
                            <label style="font-weight: 700;">Total</label>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 12px;">
                        <?php $orderItemData = Helper::getOrderItem($orderData->id); ?>
                        @foreach($orderItemData as $rowdata)
                        <div class="col-md-6">
                            <div class="row">
                                <?php $orderImagedata = Helper::getOrderImage($rowdata->product_id); ?>
                                <?php if(!empty($orderImagedata[0]->name_thumbnail)){ ?>
                                <div class="col-md-3 thumbnail" style="margin-top: 4px;">
                                    <img src="{{ env('IMAGE_PATH').'images/product/'.$orderImagedata[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" />
                                </div>
                                <?php }else{ ?>
                                    <div class="col-md-3 thumbnail" style="margin-top: 4px;">
                                        <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}" width="70px" height="70px" />
                                    </div>
                                <?php } ?>
                                <div class="col-md-9" style="margin-top: 4px;">
                                    {{ $rowdata->product_name }}
                                    @if(!empty($rowdata->attr_name))
                                            <p>
												@php
													$attr_name=explode(',',$rowdata->attr_name);
													$attr_value=explode(',',$rowdata->attr_value);
													$c_attr_value=array_combine($attr_name,$attr_value);
												@endphp
												@foreach ($c_attr_value as $key => $attrval)
													{{ $key }} : {{ $attrval }}<br>
												@endforeach
											</p>
                                            @endif


                                    @php
                                        $free_product_id = $rowdata->free_product_id??0;
                                        $getFreeProductDirectoryId = Helper::getFreeProductDirectoryId($free_product_id);
                                    @endphp
                                        @if(count($getFreeProductDirectoryId[1]) > 0)
                                        <hr>
                                        <h6>Free Products</h6>
                                        <ul class="freeProList">
                                            @foreach($getFreeProductDirectoryId[1] as $freeProduct)
                                            @foreach($freeProduct as $freePro)
                                            <li>
                                                <?php $imagename = Helper::getProductImage($freePro->id); ?>
                                                <?php if(!empty($imagename[0]->name_thumbnail)){ ?>
                                                    <img src="{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}" style="width: 40px;height: 40px;" />
                                                <?php }else{ ?>
                                                    <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}" style="width: 40px;height: 40px;" />
                                                <?php } ?>
                                                <b>{{ $freePro->name??'' }}</b>
                                            </li>
                                            @endforeach
                                            @endforeach
                                            </ul>
                                        @endif

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            @if($orderData->currency_symbol)
                                {{ $orderData->currency_symbol }} {{ $rowdata->product_price??'' }}
                            @else
                                {{ $orderData->currency }} {{ $rowdata->product_price??'' }}
                            @endif
                        </div>
                        <div class="col-md-2">
                            {{ $rowdata->product_qty }}
                        </div>
                        <div class="col-md-2">
                            @if($orderData->currency_symbol)
                                {{ $orderData->currency_symbol }} {{ $rowdata->total??'' }}
                            @else
                                {{ $orderData->currency }} {{ $rowdata->total??'' }}
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row" style="padding: 10px;">
                        <div class="col-md-1">
                            <i class="fa fa-truck" aria-hidden="true" style="font-size: 33px; color: #62626238;"></i>
                        </div>
                        <div class="col-md-9">
                            <span style="font-weight: 700;">Flat rate : </span><br>
                            <?php $orderItemData = Helper::getOrderItem($orderData->id); //echo "<pre>"; print_r($orderItemData);
                            $orderTotal = isset($orderItemData[0]->total)?$orderItemData[0]->total:0;
                            $orderDiscount = isset($orderItemData[0]->discount)?$orderItemData[0]->discount:0;
                            ?>
                            {{--<span>Items:</span>
                             @foreach($orderItemData as $rowdata)
                            <span>{{ $rowdata->product_name }}*{{ $rowdata->product_qty }} </br></span>
                            @endforeach --}}

                            @if(!empty($rowdata->shoppingcard_type))
                            <span style="font-weight: 700;">Shopping card : </span><br>
                            @endif

                            @if(!empty($rowdata->offer_type))
                                    <span style="font-weight: 700;">   Offer : </span><br>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(!empty($flatRateValue))
                                @if($orderData->currency_symbol)
                                    {{ $orderData->currency_symbol }} {{$flatRateValue}}
                                @else
                                    {{ $orderData->currency }} {{$flatRateValue}} 
                                @endif
                            @else
                                @if($orderData->currency_symbol)
                                    {{ $orderData->currency_symbol }} 0.00
                                @else
                                    {{ $orderData->currency }} 0.00
                                @endif
                            @endif
                            <br>

                            @if(!empty($rowdata->shoppingcard_type))
                                @if($rowdata->shoppingcard_type == 'percentage')
                                    {{ $rowdata->shoppingcard }}%
                                @else
                                    @if($orderData->currency_symbol)
                                        {{ $orderData->currency_symbol }} {{ $rowdata->shoppingcard }}.00
                                    @else
                                        {{ $orderData->currency }} {{ $rowdata->shoppingcard }}.00
                                    @endif
                                @endif
                                <br>
                            @endif


                            @if(!empty($rowdata->offer_type))
                                @if($rowdata->offer_type == 'fixed')
                                    @if($orderData->currency_symbol)
                                        {{ $orderData->currency_symbol }} {{ $rowdata->offer }}.00
                                    @else
                                        {{ $orderData->currency }} {{ $rowdata->offer }}.00
                                    @endif
                                @else
                                        {{ $rowdata->offer }}
                                @endif
                            @endif

                        </div>
                    </div>
                    <div class="row" style="background-color: #c9c8c838; padding: 20px;">
                        <div class="col-md-7">
                        </div>
                        <div class="col-md-3">
                            <span style="font-weight: 700; ">Items Subtotal:</span><br>
                            <span style="font-weight: 700; padding-left: 38px;">Shipping:</span><br>
                            <span style="font-weight: 700; padding-left: 38px;">Tax:</span><br>
                            <?php if($orderDiscount>0){ ?>
                                <span style="font-weight: 700; ">Items Discount:</span><br>
                            <?php } ?>

                            <span style="font-weight: 700; padding-left: 20px; ">Order Total:</span>
                        </div>
                        <div class="col-md-2">
                            @php $ordertotal = 0 @endphp
                            @foreach($orderItemData as $rowdata)
                            @php $ordertotal += $rowdata->product_price*$rowdata->product_qty @endphp
                            @endforeach
                            <span>
                                @if($orderData->currency_symbol)
                                    {{ $orderData->currency_symbol }} {{ $ordertotal }}
                                @else
                                    {{ $orderData->currency }} {{ $ordertotal }}
                                @endif
                            </span><br>
                            @if ($orderData->shipping_rate)
                                <span>
                                    @if($orderData->currency_symbol)
                                        {{$orderData->currency_symbol }} {{$orderData->shipping_rate}}.00
                                    @else
                                        {{$orderData->currency }} {{$orderData->shipping_rate}}.00
                                    @endif
                                </span><br>
                            @else
                                <span>
                                @if($orderData->currency_symbol)
                                    {{ $orderData->currency_symbol }} 0.00
                                @else
                                    {{ $orderData->currency }} 0.00
                                @endif
                                </span><br>
                            @endif
                            <span>
                                @if($orderData->currency_symbol)
                                    {{ $orderData->currency_symbol }} {{ $orderData->total_tax }}
                                @else
                                    {{ $orderData->currency }} {{ $orderData->total_tax }}
                                @endif
                            </span><br>

                            <?php if($orderDiscount>0){ ?>
                                <span>
                                    @if($orderData->currency_symbol)
                                        {{ $orderData->currency_symbol }} {{ "(-)".$orderDiscount }}.00
                                    @else
                                        {{ $orderData->currency }} {{ "(-)".$orderDiscount }}.00
                                    @endif
                                </span><br>
                                <span>
                                    @if($orderData->currency_symbol)
                                        {{$orderData->currency_symbol }} {{ (($ordertotal+$orderData->shipping_rate+$flatRateValue)+($orderData->total_tax))-($orderDiscount) }}
                                    @else
                                        {{$orderData->currency }} {{ (($ordertotal+$orderData->shipping_rate+$flatRateValue)+($orderData->total_tax))-($orderDiscount) }}
                                    @endif
                                    </span>
                            <?php }else{ ?>
                                <span>
                                    @if($orderData->currency_symbol)
                                        {{$orderData->currency_symbol }} {{ $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue }}
                                    @else
                                        {{$orderData->currency }} {{ $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue }}
                                    @endif
                                    </span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    function selectStatus(){
        var selectedText = $('#select_status').find("option:selected").text().toLowerCase();;
        $("#order_status_description").val('Your product has been'+selectedText);
    }

    function changeStatus(statusdata,id){

        if (confirm("Are you sure you want to continue!") == true) {
           //text = "You pressed OK!";
        
            var status = $("#select_status").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var order_status_description = $("#order_status_description").val();

            //alert(phone);
            $.ajax({
                type: "POST",
                data: {
                    status: status,
                    email: email,
                    phone: phone,
                    order_status_description: order_status_description,
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                url: baseUrl+'/admin/view/'+id,
                beforeSend: function(){
                    $('#orderbutton').prop('disabled', true);
                    $('#orderbutton').html('Please Wait');
                },
                success: function(response){

                    $('#orderbutton').prop('disabled', false);
                    $('#orderbutton').html('Update status');

                    if(response.error){
                        toastr.error(response.error);
                        setTimeout(function() { window.location.reload(); }, 2000);
                    }else{
                        toastr.success(response.success);
                        setTimeout(function() { window.location.reload(); }, 2000);
                    }
                   
                }
            });

        } else {
            return false;
        }

    }

    $("#select_status").change(function(){
        var sid = $(this).val();
        if(sid == 6){
            $('#orderbutton').html('Refund');
        }else{
            $('#orderbutton').html('Update status');
        }
        
    });

</script>

@endsection
