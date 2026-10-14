@extends('layouts.adminmaster')
@section('title','Tags')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

<?php
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>

<style>
    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        display: block;
        padding: 4px 6px;
        color: #555;
        vertical-align: middle;
        border-radius: 4px;
        max-width: 100%;
        line-height: 22px;
        cursor: text;
    }
    .bootstrap-tagsinput input {
        border: none;
        box-shadow: none;
        outline: none;
        background-color: transparent;
        padding: 0 6px;
        margin: 0;
        width: auto;
        max-width: inherit;
    }
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">Tags (Insert tags in English it will convert it in other Languages.)</h4>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">

        @if(session('msg'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.tags.submit') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Company Category<span style="color:red;">*</span> <span style="font-size: 14px; font-weight: 400;">Note: Tags must be unique within each category. </span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control categoryselect select2" onChange="getSubCategory(this.value)" data-allow-clear="true" data-placeholder="Select category">
                                        <option value="">Choose Category</option>
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('category'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Company Sub Category<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="subcategory" id="subcategory" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select sub-category">

                                    </select>
                                </div>
                                <span class="text-danger">@error('subcategory'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Tags(insert multiple tags via press Enter)</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="tags_name" data-role="tagsinput" id="tags" value="{{ old('name') }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column -->


    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.categoryselect').select2();
    });

    function getSubCategory(cat_id) {
        //alert("Category id: "+cat_id);
        $("#subcategory").html('');
        $.ajax({
            url: baseUrl + '/admin/get-subcategories-by-categoryid',
            type: "POST",
            data: { category_id: cat_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {
                $.each(result.subcat, function(key, value) {
                    $("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
                });

            }
        });
    }
</script>
@endsection
