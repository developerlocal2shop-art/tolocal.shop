@extends('layouts.adminmaster')
@section('title','Currency| Listing')
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
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Currency</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.currency.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Currency</a>
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

        <style>
            .hotel-search { margin-right: 25px; width:200px;}

        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.currency.indexsearch') }}" method="get" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Currency List</h3>
                        </div>

                            <div class="col-md-4">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Code</th>
                                    <th class="border-top-0">Symbol</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($addedcurrency); if ($datacount>0){?>
                                @foreach($addedcurrency as $key => $addedcurrencyrow)
                                    <tr>
                                        <!-- <td><?php echo ++$i; ?></td> -->
                                        <td> {{ ($addedcurrency->currentpage()-1) * $addedcurrency->perpage() + $key + 1 }}</td>
                                        <td><?php if($addedcurrencyrow->base_currency=="yes"){ ?><b style="color: #11d511;">{{ $addedcurrencyrow->name }}</b> <?php }else{ ?>{{ $addedcurrencyrow->name }}<?php }?></td>
                                        <td>{{ $addedcurrencyrow->code }}</td>
                                        <td>{{ $addedcurrencyrow->symbol }}</td>
                                        <td>{{ $addedcurrencyrow->status }}</td>
                                        <td>
                                        @if ($user->hasPermission('edit') == true)
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/currency-edit", ['id' => @$addedcurrencyrow->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                           <?php /* ?> <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/currency-delete", ['id' => @$addedcurrencyrow->id]); ?>">DELETE</a> <?php */ ?>
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
                        {{ $addedcurrency->appends($query)->links() }}
                        @else
                        {!! $addedcurrency->links() !!}
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
