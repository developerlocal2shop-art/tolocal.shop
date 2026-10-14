@extends('layouts.client.company')
@section('title','Order| Listing')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Order Listing</h1>
        <x-client.company_account_menu />
    </div>
</div>
<!-- /sub_header -->
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
                            </div>
                            <div class="col-md-4">
                                <div class="d-md-flex">

                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Full Name</th>
                                    <th class="border-top-0">Phone</th>
                                    <th class="border-top-0">Email</th>
                                    <th class="border-top-0">Total Amount</th>
                                    <th class="border-top-0">Total Tax</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0; $datacount = count($order);
                                    if ($datacount>0){?>
                                @foreach($order as $orderdata)
                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td>{{ $orderdata->first_name.' '.$orderdata->last_name }}</td>
                                    <td>{{ $orderdata->phone }}</td>
                                    <td>{{ $orderdata->email }}</td>
                                    <td>{{ $orderdata->total_amount }}</td>
                                    <td>{{ $orderdata ->total_tax }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('company.order.view', $orderdata->id) }}">VIEW</a>
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
</main>

@endsection
