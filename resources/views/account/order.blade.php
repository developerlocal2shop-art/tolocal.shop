@extends('layouts.client.vendor')
@section('title','User Address')
@section('content')


<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9">
                <div class="sub_header_in sticky_header filter_sub_blue_header">
                    <div class="container">
                        <h1>Order List</h1>
                    </div>
                </div>

            <div class="row">
               
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                                @php
                                Session::forget('error');
                                @endphp
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive myaccount_order_tbl">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Order ID / Order Date / Status</th>
                                    <th class="border-top-0"> User Detail</th>
                                    <th class="border-top-0">Total Amount</th>
                                    <th class="border-top-0">Payment Details</th>
                                    <th class="border-top-0">Action</th>
                                    <th class="border-top-0">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $count = count($orderData); if($count>0){	?>
                                @foreach($orderData as $row)
                                <tr>
                                    <td>
                                        <a class="btn btn-sm" href="<?php echo url("/account/order-detail", ['id' => $row['id']]); ?>">
                                            <b style="margin: 0px -9px;">ID #<?php echo $row['id'];?></b>
                                        </a> <br>
                                        {{ $row['created_at'] }} <br>
                                        @if($row->status==1)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Processing</span>
                                            @endif
                                            @if($row->status==2)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Packed</span>
                                            @endif
                                            @if($row->status==3)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Shipped</span>
                                            @endif
                                            @if($row->status==4)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">Payment Cancelled</span>
                                            @endif
                                            @if($row->status==5)
                                            <span class="btn btn-warning btn-sm" style="cursor: auto;">Delivered</span>
                                            @endif
                                            @if($row->status==6)
                                            <span class="btn btn-primary btn-sm" style="cursor: auto;">RTO</span>
                                            @endif
                                            @if($row->status==7)
                                            <span class="btn btn-success btn-sm" style="cursor: auto;">Refunded</span>
                                            @endif
                                    </td>
                                    <td>
                                        <?php echo $row['first_name'];?> <?php echo $row['last_name'];?><br>
                                        <?php echo $row['email'];?>
                                    </td>                                
                                    
                                    <?php if($row['currency_symbol']){ ?>
                                       
                                        <td class="notranslate">
                                            <?php echo $row['currency_symbol'] ;?> <?php echo $row['total_amount']+$row['shipping_rate']+$row['total_tax'];?>.00
                                        </td>
                                    <?php }else{ ?>
                                        {{-- <td class="notranslate">
                                            <?php// echo $row['currency'] ;?> <?php// echo $row['total_tax'];?>
                                        </td> --}}
                                        <td class="notranslate">
                                            <?php echo $row['currency'] ;?> <?php echo $row['total_amount'];?>
                                        </td>
                                    <?php } ?>
                                   <td>
                                    Payment method - {{ $row['payment_method'] }} <br> 

                                    @if($row['payment_status'])
                                         Payment status - <span class="btn btn-success btn-sm" style="cursor: auto;margin: 2px 12px;">{{ $row['payment_status'] }}</span><br>
                                    @endif
                                   
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="<?php echo url("/account/order-detail", ['id' => $row['id']]); ?>">Track Order</a>
                                    </td>
                                    <td><a href="{{route('generate.pdf', $row['id'])}}" title="Download Invoice" style="font-size:25px;color: #e31212;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>
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
                    <div class="d-flex justify-content-center cust-page orderviewpagination">
                        @if(isset($query))
                        {{ $orderData->appends($query)->links() }}
                        @else
                        {!! $orderData->links() !!}
                        @endif
                    </div>
                </div>

            </div>

                <!-- /row -->
            </div>

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<script>
    $(document).ready(function() {
			
    });
</script>
@endsection
