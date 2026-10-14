@extends('layouts.adminmaster')
@section('title','Product Category| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<style>
    .toggle.btn {
        min-width: 90px !important;
    }
    .child-category-row {
        display: table-row;
    }
    .togglet {
        color: #ff7676;
        margin: 0px 5px;
        font-size: 18px;
    }
    svg {
            width: 3%;
        margin: 5px;
    }
    .opent {
        color: #9d2727;
    }
    p.text-sm.text-gray-700.leading-5 {
        margin: 10px 5px;
    }
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: none;
    }
    .flex.justify-between.flex-1.sm\:hidden {
        display: none;
    }
    .pagination {
        width: 100%;
    }

    .pagination {
        display: block;
    }

    .pagination {
        float: left;
    }

    .categories_data .parent-category-row {
        background: #f5f7fa;
    }

    .categories_data .parent-category-row td {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        padding: 12px 15px;
        border-top: 2px solid #ddd;
    }

    .categories_data .child-category-row td {
        background: #fff;
    }

    .categories_data .category-indent {
        color: #999;
        margin-right: 8px;
        font-size: 16px;
    }

    .categories_data .child-category-row:hover td {
        background: #f9f9f9;
    }

    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
        .d-none{
            display: unset !important;
        }

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
    .hotel-search { margin-right: 25px; width:90%;}
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Product Categories</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    <a href="{{ route('admin.product.category.tree') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">View Product Tree Structure</a>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.productcategory.create')}}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Product Category</a>
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
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.productcategories.indexsearch') }}" method="POST" class="">
                {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="box-title">Product Categories List</h3>
                        </div>
                        <div class="col-md-9">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <div class="input-group mb-7">
                                    <div class="hotel-search">
                                        <select name="category" id="category" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                                            <option value="" >Select parent product category</option>
                                            @foreach($parent_category as $cat)
                                            <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group mb-3">
                                    <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                    </div>
                                </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- <h3 class="box-title">Categories List</h3> -->
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive categories_data">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Image</th>
                                    <th class="border-top-0">Product Category Name</th>
                                    <th class="border-top-0">Position</th>
                                    <th class="border-top-0">Parent Product Category</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentParent = '';
                                    $parentIndex = 0;
                                @endphp
                                @foreach($product_categories as $row)
                                    @php
                                        $parentName = $row->parent->name ?? 'No Parent Category';
                                    @endphp
                                    @if($currentParent != $parentName)

                                        <tr class="parent-category-row" data-parent="{{ Str::slug($parentName) }}"  style="cursor:pointer;">
                                            <td colspan="7">
                                                <i class="fa fa-chevron-{{ $parentIndex == 0 ? 'down' : 'right' }} toggle-arrow togglet"></i>
                                                <i class="fa fa-folder-open opent"></i>
                                                <strong>{{ $parentName }}</strong>
                                            </td>
                                        </tr>

                                        @php
                                            $currentParent = $parentName;
                                            $isFirstParent = ($parentIndex == 0);
                                            $parentIndex++;
                                        @endphp
                                    @endif

                                        <tr class="child-category-row child-{{ Str::slug($parentName) }}" @if(!$isFirstParent) style="display:none;" @endif>

                                            <td>
                                                <?php if($row->upload_id>0){ ?>
                                                    <?php $imagename = Helper::getProductCategoryimage($row->upload_id); ?>
                                                    <img src="{{ env('IMAGE_PATH').'files/productcategory/'.($imagename[0]->name_thumbnail) }}" alt="user-img" width="36" class="img-circle">
                                                <?php }else{ ?>
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" alt="user-img" width="36" class="img-circle">
                                                <?php } ?>
                                            </td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->sort_value }}</td>
                                            <td>
                                                @if ($row->parent)
                                                {{ $row->parent->name}}
                                                @else
                                                None
                                                @endif

                                            </td>
                                            <td>{{ $row->status }}</td>
                                            <td>
                                            {{-- @if ($user->hasPermission('edit') == true) --}}
                                                <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/productcategory-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                            {{-- @endif --}}
                                            {{-- @if ($user->hasPermission('delete') == true) --}}
                                                <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/productcategory-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                            {{-- @endif --}}
                                            </td>
                                        </tr>
                                    
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    

                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $product_categories->appends($query)->links() }}
                        @else
                        {!! $product_categories->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

       $('.parent-category-row').click(function() {

            let parent = $(this).data('parent');

            $('.child-' + parent).slideToggle(150);

            $(this)
                .find('.toggle-arrow')
                .toggleClass('fa-chevron-down fa-chevron-right');

        });

    });

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>
<!-- <script>

        $(function() {
            $('.toggle-class').change(function() {

            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: baseUrl+'/admin/changeStatus',
                    data: {'status': status, 'user_id': user_id},
                    success: function(data){
                        console.log(data.success)
                    }
                });
            })
        })

    </script> -->
@endsection
