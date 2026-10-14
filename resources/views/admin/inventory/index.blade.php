@extends('layouts.adminmaster')
@section('title','Inventory| Listing')
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
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Inventory</h4>
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
                <form action="{{ route('admin.inventory.indexsearch') }}" method="post" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="box-title">Inventory List</h3>
                        </div>
                        <div class="col-md-9">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <div class="input-group mb-7">
                                    <div class="hotel-search" style="width: 90%;">
                                        <select name="sort" id="sort" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select option">
                                            <option value="" >Sort By</option>
                                            <option value="all">All</option>
                                            <option value="instock">Instock</option>
                                            <option value="outofstock">Out of stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text"  name="searchtext" id="search" value="{{ $searchdata }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                <!-- <th class="border-top-0">#</th> -->
                                    <th class="border-top-0">Product ID</th>
                                    <th class="border-top-0">Product Name</th>
                                    <th class="border-top-0">SKU</th>
                                    <th class="border-top-0">Stock</th>
                                    <th class="border-top-0">Out of Stock</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($productData); if ($datacount>0){?>
                                @foreach($productData as $productdatarow)
                                    <tr>
                                        <!-- <td><?php //echo ++$i; ?></td> -->
                                        <td>{{ $productdatarow->id }}</td>
                                        <td>{{ $productdatarow->name }}</td>
                                        <td>{{ $productdatarow->sku }}</td>
                                        <form action="{{ route('admin.inventory.update', $productdatarow->id) }}" method="POST">
                                        {{ csrf_field() }}
                                            <td><input type="number"  min="0" oninput="this.value = Math.abs(this.value)" name="stock" value="{{ old('stock', $productdatarow->stock) }}" class="form-control border-1"></td>
                                            <td><input type="checkbox" class="form-check-input" name="outstock" id="" value="outofstock" {{ $productdatarow->outofstock=='outofstock' ? 'checked' : '' }}></td> 
                                            <td><button type="submit" style="margin-top: 0px;min-width: 0px;" title="save" class="btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button></td>   
                                        </form>                        
                                        
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
                        {{ $productData->appends($query)->links() }}
                        @else
                        {!! $productData->links() !!}
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