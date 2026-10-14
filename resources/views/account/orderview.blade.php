@extends('layouts.client.vendor')
@section('title','User Address')
@section('content')
</a>
<?php ini_set('max_execution_time', 180); ?>
<style>
    p {
        margin: 15px;
    }
   .tracking-container {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        max-width: 100%;
        width: 100%;
    }
    .col-xl-3.col-md-3.col-6.grandtotalorderrow {
        text-align: right;
    }

    .status {
      display: flex;
      justify-content: normal;
      align-items: center;
      margin-bottom: 15px;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 5px;
    }

    .status p {
      margin: 0;
      font-weight: bold;
      color: #555;
    }

    .status .step-item {
      font-weight: bold;
      color: #888;
      margin: 5px;
      padding: 5px 10px;
      border-radius: 20px;
      background-color: #ddd;
    }


    .status .step-item.active {
        color: #fff;
        border: 2px solid green;
        background: green;
        margin: 5px;
    }

    .status .step-item.cross {
        color: #fff;
        border: 2px solid red;
        background: red;
        margin: 5px;
    }

    .cancelled:after {
        content: "";
        background-color: red;
        position: absolute;
        width: 5px;
        height: 46px;
        top: 240px;
        left: 65px;
        display: block;
    }

    .rto_active:after {
        content: "";
        background-color: #ee851e;
        position: absolute;
        width: 5px;
        height: 46px;
        top: 240px;
        left: 65px;
        display: block;
    }

    .status .step-item.rto_active {
        color: #fff;
        border: 2px solid #ee851e;
        background: #ee851e;
        margin: 5px;
    }

    .confirmed:after {
        content: "";
        background-color: green;
        position: absolute;
        width: 5px;
        height: 45px;
        top: 160px;
        left: 65px;
        display: block;
    }

    .packed:after {
        content: "";
        background-color: green;
        position: absolute;
        width: 5px;
        height: 46px;
        top: 240px;
        left: 65px;
        display: block;
    }

    .shipped:after {
        content: "";
        background-color: green;
        position: absolute;
        width: 5px;
        height: 46px;
        top: 319px;
        left: 65px;
        display: block;
    }



</style>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Request a refund</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <form id="bookingform" class="contact-us-form" action="{{ route('refund.request') }}" method="post">
                        {{ csrf_field() }}
                    <div class="modal-body">
                        <span class="respondata"></span>
                        <select class="form-control required" name="reason" required="">
                            <option value=""> Select Reason</option>
                            <option value="performance and quality not adequate"> performance and quality not adequate</option>
                            <option value="product damaged but shipping box ok"> product damaged but shipping box ok</option>
                            <option value="Item arrived too late"> Item arrived too late</option>
                            <option value="Wrong item was sent"> Wrong item was sent</option>
                            <option value="Item defective or doesn't work">Item defective or doesn't work</option>
                            <option value="others">Others</option>

                        </select>
                        <input type="hidden" name="order_id" value="{{ $orderData->id }}">
                        <br>
                        <textarea class="form-control required" name="comment" required="" placeholder="Please enter comment"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-default submitbtn">Request refund</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9">
                <div class="sub_header_in sticky_header filter_sub_blue_header">
                    <div class="container">
                        <h1>Order Detail</h1>
                    </div>
                </div>


            <?php $adminFlatShippingData = Helper::getAdminFlatShippingData(); ?>
            <?php

                $flatRateValue = $adminFlatShippingData->flat_rate;

            ?>
            @php
            $billingcountryName = Helper::getCountryName($billing_address->country);
            $billingstateName = Helper::getStateName($billing_address->state);
            $billingCityName = Helper::getCityName($billing_address->city_id);
            $shippingcountryName = Helper::getCountryName($shiping_address->country);
            $shippingstateName = Helper::getStateName($shiping_address->state);
            $shippingCityName = Helper::getCityName($shiping_address->city_id);
            //dd($billingCityName);
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
                                    <div>
                                        <h3 class="box-title">Order ID #{{ $orderData->id }}
                                            <span style="font-size:14px;">
                                                ({{ $orderData->created_at->toDateString() }} - {{ $orderData->created_at->format('H:i:s') }})
                                            </span>
                                        </h3>
                                        <p>Print Invoice (pdf) <a href="{{route('generate.pdf', $orderData->id)}}" title="Download Invoice" style="font-size:20px;color: #f00;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></p>
                                    </div>
                                    <p>Payment method - <b>{{ $orderData->payment_method }}</b></p>
                                </div>
                            </div>

                            <div class="tracking-container">

                                <div class="status confirmed">
                                    <p class="step-item active">
                                        <i class="fas fa-check"></i>
                                        <br>
                                        <p>Order Confirmed, <i style="font-size: 12px; font-weight: 500;">{{ $orderData->created_at->toDateString() }} - {{ $orderData->created_at->format('H:i:s') }}</i></p>
                                    </p>
                                </div>

                                @if($orderData->status == 4)

                                    <div class="status cancelled">
                                        <p class="step-item  cross">
                                            <i class="fa fa-window-close" aria-hidden="true"></i>
                                            <br>
                                            <p> Payment Cancelled
                                                @if($orderData->status == 4)
                                                <i style="font-size: 12px; font-weight: 500;">
                                                    {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                                </i>
                                                @endif
                                            </p>
                                        </p>
                                        @if($orderData->status == 4)
                                        <span style="margin: 10px -180px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                        @endif
                                    </div>

                                @elseif($orderData->status == 6)

                                    <div class="status rto">
                                        <p class="step-item rto_active">
                                            <i class="fas fa-car"></i>
                                            <br>
                                            <p> RTO
                                                <i style="font-size: 12px; font-weight: 500;">
                                                    {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                                </i>
                                            </p>
                                        </p>
                                        <span style="margin: margin: 12px -150px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                    </div>

                                @elseif($orderData->status == 7)

                                    <div class="status cancelled">
                                        <p class="step-item  cross">
                                            <i class="fa fa-undo" aria-hidden="true"></i>

                                            <br>
                                            <p> Refunded
                                                @if($orderData->status == 7)
                                                <i style="font-size: 12px; font-weight: 500;">
                                                    {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                                </i>
                                                @endif
                                            </p>
                                        </p>
                                        @if($orderData->status == 7)
                                        <span style="margin: 10px -180px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                        @endif
                                    </div>

                                @else
                                    <div class="status packed">
                                        <p class="step-item @if($orderData->status == 2 || $orderData->status == 3 || $orderData->status == 5) active @endif">
                                            {{-- @if($orderData->status == 2 || $orderData->status == 3)
                                            <i class="fas fa-check"></i>
                                            @else --}}
                                            <i class="fa fa-inbox" aria-hidden="true"></i>
                                            {{-- @endif --}}
                                            <br>
                                            <p> Packed
                                                @if($orderData->status == 2)
                                                <i style="font-size: 12px; font-weight: 500;">
                                                    {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                                </i>
                                                @endif
                                            </p>
                                        </p>
                                        @if($orderData->status == 2)
                                        <span style="margin: 10px -180px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                        @endif
                                    </div>

                                    <div class="status shipped">
                                    <p class="step-item @if($orderData->status == 3 || $orderData->status == 5) active @endif">

                                        <i class="fa fa-ambulance" aria-hidden="true"></i>

                                        <br>
                                        <p> Shipped
                                            @if($orderData->status == 3)
                                                <i style="font-size: 12px; font-weight: 500;">
                                                    {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                                </i>
                                            @endif
                                        </p>
                                    </p>
                                        @if($orderData->status == 3)
                                        <span style="margin: 10px -180px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                        @endif
                                    </div>

                                    <div class="status delivered">
                                    <p class="step-item @if($orderData->status == 5) active @endif">
                                        <i class="fa fa-address-book" aria-hidden="true"></i>
                                        <br>
                                        <p> Delivered
                                            @if($orderData->status == 5)
                                            <i style="font-size: 12px; font-weight: 500;">
                                                {{ $orderData->updated_at->toDateString() }} - {{ $orderData->updated_at->format('H:i:s') }}
                                            </i>
                                            ( <i style="font-size: 12px; font-weight: 500;"> Expected delivery date was : {{ $orderData->expected_delivery_date??'' }} </i> )
                                            @else
                                            <i style="font-size: 12px; font-weight: 500;"> Expected delivery date is : {{ $orderData->expected_delivery_date??'' }} </i>
                                            @endif
                                        </p>
                                    </p>
                                        @if($orderData->status == 5)
                                        <span style="margin: 15px -455px -15px;"><br>{{ $orderData->order_status_description }}</span>
                                        @endif
                                    </div>

                                @endif

                              </div>


                            <div class="alert alert-success d-none" id="msg_div">
                                <span id="res_message"></span>
                            </div>

                            @if($orderData->status == 5 && !$orderData->refund && $isPaypalOrder)
                            <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#myModal">
                                Request full refund
                            </button>
                            @endif
                            @if($orderData->refund && $orderData->status != 7)
                            <button type="button" class="btn btn-primary btn-sm mb-4" disabled>
                                Refund request submitted
                            </button>
                            @endif
                            @if($orderData->status == 7)
                            <button type="button" class="btn btn-success btn-sm mb-4" disabled>
                                Refunded
                            </button>
                            @endif
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
                                            <div rows="5" style="height: auto; padding: 13px !important;" name="billing_address" class="form-control p-0 border-0">
                                                Name - {{ $orderData->first_name.' '.$orderData->last_name }} <br>
                                                Email - {{ $orderData->email }} <br>
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
                                            <div rows="5" style="height: auto; padding: 13px !important;" name="billing_address" class="form-control p-0 border-0">
                                                {{ $billing_address->address1}} <br>
                                                {{$billing_address->address2}} <br>
                                                @if ($billing_address->city !=null)
                                                {{ $billing_address->city.', '.$billingstateName->name??''}} <br>
                                                @else
                                                @if($billingCityName)
                                                    {{ $billingCityName->name??''}},
                                                @endif
                                                {{ $billingstateName->name??''}} <br>
                                                @endif

                                                {{$billingcountryName->name??''}} <br>
                                                {{$billing_address->zipcode }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <h4 class="box-title"><label class="col-md-12 panel-heading cust-tab-head" style="padding-top: 3px;">Shipping Address</label></h4>
                                        <div class="col-md-12 p-0">
                                            <div rows="5" style="height: auto; padding: 13px !important;" name="shipping_address" class="form-control p-0 border-0">
                                                {{ $shiping_address->address1}} <br>
                                                {{$shiping_address->address2}} <br>
                                                @if($shiping_address->city !=null)
                                                {{ $shiping_address->city.', '.$shippingstateName->name}} <br>
                                                @else
                                                {{ $shippingCityName->name.', '.$shippingstateName->name}} <br>
                                                @endif
                                                {{$shippingcountryName->name}} <br>
                                                {{$shiping_address->zipcode }}
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
                                <div class="col-md-2">
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
                                            <p><span>Total:</span>
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
                                    {{ $rowdata->product_qty }}
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
                                    <i class="fa fa-truck" aria-hidden="true" style="font-size: 33px; color: #62626238;"></i>
                                </div>
                                <div class="col-md-9 col-6">
                                    <?php $orderItemData = Helper::getOrderItem($orderData->id);
                                    $orderTotal = isset($orderItemData[0]->total)?$orderItemData[0]->total:0;
                                    $orderDiscount = isset($orderItemData[0]->discount)?$orderItemData[0]->discount:0;
                                    ?>
                                    @if(!empty($rowdata->shoppingcard_type))
                                    <span style="font-weight: 700;">Shopping card : </span><br>
                                    @endif

                                    @if(!empty($rowdata->offer_type))
                                    <span style="font-weight: 700;"> Offer : </span><br>
                                    @endif
                                </div>
                                <div class="col-md-2 col-4" style="text-align: center; padding: 2px 0px 0px 30px;">

                                    <span class="notranslate">
                                        @if(!empty($rowdata->shoppingcard_type))
                                            @if($rowdata->shoppingcard_type == 'percentage')
                                                {{ $rowdata->shoppingcard }}%
                                            @endif

                                            {{ Helper::formatCurrency($orderData, $rowdata->shoppingcard) }}

                                        <br>
                                    @endif
                                    </span>

                                    <span class="notranslate">
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
                                    </span>
                                </div>
                            </div>
                            <div class="row totalorderlistcol" style="background-color: #c9c8c838; padding: 20px;">
                                <div class="col-xl-6 col-md-6 col-12">
                                </div>
                                <div class="col-xl-3 col-md-3 col-6">
                                    <span style="font-weight: 700; ">Items Subtotal:</span><br>
                                    <span style="font-weight: 700; padding-left: 38px;">Shipping:</span><br>
                                    @if($orderData->total_tax > 0)
                                    <span style="font-weight: 700; padding-left: 38px;">Tax:</span><br>
                                    @endif
                                    <span style="font-weight: 700; padding-left: 38px;">Flat Rate:</span><br>
                                    <?php if($orderDiscount>0){ ?>
                                    <span style="font-weight: 700; ">Items Discount:</span><br>
                                    <?php } ?>
                                        <hr>
                                    <span style="font-weight: 700; padding-left: 20px; ">Order Total:</span>
                                </div>
                                <div class="col-xl-3 col-md-3 col-6 grandtotalorderrow">
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
                                    @if(!empty($flatRateValue))
                                        {{ Helper::formatCurrency($orderData, $flatRateValue) }}
                                    @else
                                        @if($orderData->currency_symbol)
                                            {{ $orderData->currency_symbol }} 0.00
                                        @else
                                            {{ $orderData->currency }} 0.00
                                        @endif
                                    @endif
                                    </span>
                                   <br>
                                   <hr>
                                    <b>
                                    <?php if($orderDiscount>0){ ?>
                                    <span>{{ "(-)".$orderDiscount }}.00</span><br>
                                    <span class="notranslate">
                                         {{ Helper::formatCurrency($orderData, (($ordertotal+$orderData->shipping_rate+$flatRateValue)+($orderData->total_tax))-($orderDiscount)) }}
                                    </span>
                                    <?php }else{ ?>
                                    <span class="notranslate">
                                        {{ Helper::formatCurrency($orderData, $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue) }}
                                    </span>
                                    <?php } ?>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <br>
                                </br>
                                <hr>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>
<script>
$(document).on('click', '.submitbtn', function(e) {
        e.preventDefault();

        $(document).find('.required').removeClass('is-invalid');
        $(document).find('.invalid-feedback').remove();

        var obj = $(this);
        let requestTxt = $(this).attr('process-text');
        requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
        var oldhtml = $(this).html();

        var form = $(this).closest("form");
        var url = form.attr('action');
        var method = form.attr('method');
        var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
<span class="">${requestTxt}...</span>`;

        var allformdata = new FormData(form[0]);
        $.ajax({
            type: method
            , url: url
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: allformdata
            , mimeType: "multipart/form-data"
            , contentType: false
            , cache: false
            , processData: false
            , dataType: "json"
            , beforeSend: function() {
                obj.attr("disabled", true);
                obj.html(loader);
            },

            success: function(response) {
                console.log(response);

                if (response.status == false) {
                    $.each(response.errors, function(index, value) {
                        form.find('.required').each(function() {
                            var fieldName = $(this).attr('name');
                            if (fieldName == index && value != "") {
                                $(this).addClass("is-invalid");
                                $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                $(this).focus();
                            }
                        });
                    });

                    if (typeof(response.message) != "undefined") {
                        $('.respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');

                        window.setTimeout(function() {
                            $('.respondata').hide();
                        }, 3000);
                    }

                } else {
                    if (typeof(response.message) != "undefined") {
                        console.log(response.message);
                        $("#bookingform")[0].reset();
                        $('.respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');
                        window.setTimeout(function() {
                            $('.respondata').hide();
                        }, 3000);


                    }

                }
                window.setTimeout(function() {
                            location.reload(true);
                }, 5000);


                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
            , error: function(err) {
                var response = err.responseJSON || {};
                var message = response.message || response.error || 'Unable to submit the refund request. Please try again.';
                $('.respondata').text(message).css('color', 'red');
                obj.attr("disabled", false);
                obj.html(oldhtml);
            }
        });

    });
</script>
<style>
    .hotel-search {
        margin-right: 25px;
        width: 200px;
    }

    div#myModal {
        margin: 50px 0px;
    }
.red {
    background: #fff !important;
}
</style>
@endsection
