@extends('layouts.client.vendor')
@section('title','Order| View')
@section('content')
<?php ini_set('max_execution_time', 180); ?>

<div class="sub_header_in sticky_header">
    @php
        $url = URL::current();
        $segments = explode('/', rtrim($url, '/'));
        $secondLastSlug = $segments[count($segments) - 3];
    @endphp
    <div class="container">
        <h1>Order View</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">


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
            <div class="container-fluid">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">

                            <div class="row">
                                <div class="col-md-12" style="display: flex;">
                                    <div><h3 class="box-title">Order #{{ $orderData->id }} Details</h3></div>
                                    <div style="margin-left: 10px;"><a href="{{route('generate.pdf', $orderData->id)}}" title="Download Invoice" style="font-size:30px;color: #f00;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 ">
                                    <div class=" mb-4">
                                    
                                    <input type="hidden" name="email" id="email" value="{{ $orderData->email??'' }}">
                                    <input type="hidden" name="phone" id="phone" value="{{ $orderData->phone??'' }}">

                                    @if($secondLastSlug == 'track')
                                        
                                        <div class="">
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
                                                            <?php //if($orderData->status != 5){ ?>
                                                                <option value="{{ $sval->id }}" {{ $sval->id == $orderData->status ? 'selected' : '' }} > {{ $sval->name }}</option>
                                                            <?php //} ?>
                                                        @endif
                                                    <?php //} ?>
                                                @endforeach
                                            </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="message_contact">Message<span style="color:red;">*</span></label>
                                                <textarea class="form-control" placeholder="Please enter message of order status." style="height: 120px;" name="order_status_description" id="order_status_description">{{ $orderData->order_status_description }}</textarea>
                                            </div>
                                                <button type="submit" class="btn btn-primary updatestatusbtn" <?php if($orderData->status == 7){echo $btndisable="disabled='disabled'";} ?> id="orderbutton" onclick="changeStatus('<?php echo $orderData->status ?>','<?php echo $orderData->id ?>');">
                                                    Update status
                                                </button>
                                            
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top: 25px;">
                                    @if($orderData->refund)
                                        <button type="button" class="btn btn-primary btn-sm" style="float: right;margin: 4px 15px;" data-toggle="modal" data-target="#myModal">
                                        View Return request 
                                        </button>
                                    @endif
                                </div>
                            </div>

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
                                            <div style="height: auto; padding: 13px !important;" name="billing_address" class="form-control p-0 border-0" >
                                            {{ $billing_address[0]->address1}}  <br>
                                            {{$billing_address[0]->address2}} <br>
                                            @if ($billing_address[0]->city !=null)
                                                {{ $billing_address[0]->city.', ' .$billingstateName->name??''}} <br>
                                            @else
                                            @if($billingCityName)
                                                {{ $billingCityName->name??''}},
                                            @endif
                                                {{ $billingstateName->name??''}} <br>
                                            @endif

                                            {{$billingcountryName->name??''}} <br>
                                            {{$billing_address[0]->zipcode??'' }}
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
                                                {{ $shiping_address[0]->city.', '.$shippingstateName->name}} <br>
                                            @else
                                                {{ $shippingCityName->name.', '.$shippingstateName->name}} <br>
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
                            <div class="row order_view_subhead" style="background-color: #c9c8c838; padding-top: 12px;">
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
                            <div class="row orderviewitemlist" style="padding-top: 12px;">
                                <?php $orderItemData = Helper::getOrderItem($orderData->id); ?>
                                @foreach($orderItemData as $rowdata)
                                <div class="col-md-6 col-12">
                                    <div class="row orderlistdetails">
                                        <div class="col-xl-3 col-md-3 col-8 prodname_order">
                                            {{ $rowdata->product_name }}
                                        </div>
                                        <?php $orderImagedata = Helper::getOrderImage($rowdata->product_id); ?>
                                        <?php if(!empty($orderImagedata[0]->name_thumbnail)){ ?>
                                        <div class="col-xl-2 col-md-3 col-4 oderitemimg" style="margin-top: 4px;">
                                            <img src="{{ env('IMAGE_PATH').'images/product/'.$orderImagedata[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" />
                                        </div>
                                        <?php }else{ ?>
                                            <div class="col-xl-2 col-md-3 col-4 oderitemimg" style="margin-top: 4px;">
                                                <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}" width="70px" height="70px" />
                                            </div>
                                        <?php } ?>
                                        <div class="col-xl-7 col-md-6 col-12" style="margin-top: 4px;">

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
                                                <!-- <hr> -->
                                                <h6>Free Products</h6>
                                                <ul class="freeProList freeproimg_alignment">
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
                                    <div class="row orderlistdetailsmobile">
                                        <?php $orderImagedata = Helper::getOrderImage($rowdata->product_id); ?>
                                        <?php if(!empty($orderImagedata[0]->name_thumbnail)){ ?>
                                        <div class="col-md-3 col-3 alignmentorder" style="margin-top: 4px;">
                                            <img src="{{ env('IMAGE_PATH').'images/product/'.$orderImagedata[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" />
                                        </div>
                                        <?php }else{ ?>
                                            <div class="col-md-3 col-3" style="margin-top: 4px;">
                                                <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}" width="70px" height="70px" />
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-9 col-9 orderdetail_view" style="margin-top: 4px;">
                                            <h5>{{ $rowdata->product_name }}</h5>
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
                                            <p><span>Cost:</span>
                                            @if($orderData->currency_symbol)
                                                {{ $orderData->currency_symbol }} {{ $rowdata->product_price??'' }}
                                            @else
                                                {{ $orderData->currency }} {{ $rowdata->product_price??'' }}
                                            @endif
                                            </p>
                                            <p><span>Qty:</span>
                                                {{ $rowdata->product_qty??'' }}
                                            </p>
                                            <p><span class="notranslate">Total:</span>
                                            @if($orderData->currency_symbol)
                                                {{ $orderData->currency_symbol }} {{ $rowdata->total??'' }}
                                            @else
                                                {{ $orderData->currency }} {{ $rowdata->total??'' }}
                                            @endif
                                            </p>
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
                                <div class="col-md-2 col-4 notranslate orderlistdetails">
                                    @if($orderData->currency_symbol)
                                        {{ $orderData->currency_symbol }} {{ $rowdata->product_price??'' }}
                                    @else
                                        {{ $orderData->currency }} {{ $rowdata->product_price??'' }}
                                    @endif
                                </div>
                                <div class="col-md-2 col-4 orderlistdetails">
                                    {{ $rowdata->product_qty??'' }}
                                </div>
                                <div class="col-md-2 col-4 notranslate orderlistdetails">
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
                    <hr>
                        <div class="white-box">
                            <div class="row alignmentorder" style="padding: 10px;">
                                <div class="col-md-1 col-2">
                                    {{-- <i class="fa fa-truck" aria-hidden="true" style="font-size: 33px; color: #62626238;"></i> --}}
                                </div>
                                <div class="col-md-9 col-6">
                                    
                                    <?php $orderItemData = Helper::getOrderItem($orderData->id); //echo "<pre>"; print_r($orderItemData);
                                    $orderTotal = isset($orderItemData[0]->total)?$orderItemData[0]->total:0;
                                    $orderDiscount = isset($orderItemData[0]->discount)?$orderItemData[0]->discount:0;
                                    ?>
                                   
                                    @if(!empty($rowdata->shoppingcard_type))
                                    <span style="font-weight: 700;">Shopping card : </span><br>
                                    @endif

                                    @if(!empty($rowdata->offer_type))
                                         <span style="font-weight: 700;">   Offer : </span><br>
                                    @endif

                                </div>
                                <div class="col-md-2 col-4">
                                    
                                    <span class="notranslate">
                                    @if(!empty($rowdata->shoppingcard_type))
                                        @if($rowdata->shoppingcard_type == 'percentage')
                                            {{ $rowdata->shoppingcard }}%
                                        @else
                                            {{ Helper::formatCurrency($orderData, $rowdata->shoppingcard) }}
                                        @endif
                                        <br>
                                    @endif
                                    </span>
                                    <span class="notranslate">
                                    @if(!empty($rowdata->offer_type))
                                        @if($rowdata->offer_type == 'fixed')
                                            {{ Helper::formatCurrency($orderData, $rowdata->offer) }}
                                        @else
                                             {{ $rowdata->offer }}
                                        @endif
                                    @endif
                                    </span>
                                </div>
                            </div>
                            <div class="row totalorderlistcol" style="background-color: #c9c8c838; padding: 20px;">
                                <div class="col-xl-6 col-md-6 col-12">
                                </div>
                                <div class="col-xl-3 col-md-3 col-6" style="text-align: right;">
                                    <span style="font-weight: 700; ">Items Subtotal:</span><br>
                                    <span style="font-weight: 700; padding-left: 38px;">Shipping:</span><br>
                                    @if($orderData->total_tax > 0)
                                    <span style="font-weight: 700; padding-left: 38px;">Tax:</span><br>
                                    @endif
                                    <span style="font-weight: 700; padding-left: 38px;">Flat rate :</span><br>
                                    
                                    <?php if($orderDiscount>0){ ?>
                                        <span style="font-weight: 700; ">Items Discount:</span><br>
                                    <?php } ?>
<hr>
                                    <span style="font-weight: 700; padding-left: 20px; ">Order Total:</span>
                                </div>
                                <div class="col-xl-3 col-md-3 col-6 grandtotalorderrow" style="text-align: right; padding: 4px 85px;">
                                    @php $ordertotal = 0 @endphp
                                    @foreach($orderItemData as $rowdata)
                                    @php $ordertotal += $rowdata->product_price*$rowdata->product_qty @endphp
                                    @endforeach
                                    <span class="notranslate">
                                        {{ Helper::formatCurrency($orderData, $ordertotal) }}
                                    </span><br>
                                    @if ($orderData->shipping_rate)
                                        <span class="notranslate">
                                            {{ Helper::formatCurrency($orderData, $orderData->shipping_rate) }}
                                        </span><br>
                                    @else
                                        <span class="notranslate">
                                            @if($orderData->currency_symbol)
                                                {{ $orderData->currency_symbol }} 0.00
                                            @else
                                                {{ $orderData->currency }} 0.00
                                            @endif
                                        </span><br>
                                    @endif
                                    @if($orderData->total_tax > 0)
                                        <span class="notranslate">
                                            {{ Helper::formatCurrency($orderData, $orderData->total_tax) }}
                                        </span><br>
                                    @endif
                                    <span class="notranslate">
                                            {{ Helper::formatCurrency($orderData, $flatRateValue) }}
                                    </span><br>
                                    
                                    <hr>
                                    <?php if($orderDiscount>0){ ?>
                                        <span>{{ "(-)". Helper::formatCurrency($orderData, $orderDiscount) }}</span><br>
                                        <span class="notranslate">
                                            {{ Helper::formatCurrency($orderData, (($ordertotal+$orderData->shipping_rate+$flatRateValue)+($orderData->total_tax))-($orderDiscount)) }}
                                            
                                        </span>
                                    <?php }else{ ?>
                                        <span class="notranslate">
                
                                            {{ Helper::formatCurrency($orderData, $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue) }}
                                        </span>
                                    <?php } ?>
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

   function selectStatus(){
    var selectedText = $('#select_status').find("option:selected").text().toLowerCase();;
      $("#order_status_description").val('Your product has been'+selectedText);
   }

    function changeStatus(statusdata,id){

        if (confirm("Are you sure you want to continue!") == true) {

        

        var status = $("#select_status").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var order_status_description = $("#order_status_description").val();
        //alert(status);
        $.ajax({
            type: "POST",
            data: {
                status: status,
                email:email,
                phone: phone,
                order_status_description: order_status_description,
                id: id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            url: baseUrl+'/company/view/'+id,
            beforeSend: function(){
				$('#orderbutton').prop('disabled', true);
			    $('#orderbutton').html('Please Wait');
			},
            success: function(response){
               
                $('#orderbutton').prop('disabled', false);
                $('#orderbutton').html('Update status');

                if(response.error){
                    window.setTimeout(function() {
                    }, 2000);
                    toastr.error(response.error);
                }else{
                    toastr.success(response.success);
                    window.setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
                
            }
        });
        } else {
            return false;
        }
    }

    $("#select_status").change(function(){
        var sid = $(this).val();
        if(sid == 7){
            $('#orderbutton').html('Refund');
        }else{
            $('#orderbutton').html('Update status');
        }
        
    });

</script>
<style>
div#myModal {
    margin: 50px 0px;
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

    .hotel-search { margin-right: 25px; width:200px;}

</style>
@endsection
