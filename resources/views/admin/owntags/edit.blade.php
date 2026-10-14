@extends('layouts.adminmaster')
@section('title','Blog')
@section('content')

<?php

if (isset($data['tagseditdata'])) {
    $tagsEdit = $data['tagseditdata'];
}
if (isset($data['all_category'])) {
    $category = $data['all_category'];
}
//  echo "<pre>"; print_r($data['blogAndTraslationData']);die; 
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Own Tag Edit</h4>
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

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.owntags.update', $tagsEdit->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Category<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control select2" data-allow-clear="true" data-placeholder="Select category">
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $tagsEdit['category_id'] ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('category'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Own Tags</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="tags_name" value="{{ $tagsEdit->tags }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                    <div class="form-group mb-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info">Update</button>
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
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
</script>

<script>
    /*$(document).ready(function() {
            $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
             console.log(categorytype_id);
                $("#category").html('');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: baseUrl+'/admin/parent-category',
                    data: {'cat_type_id': categorytype_id, _token: '{{csrf_token()}}'},
                    success: function(result){
                        $('#category').html('<option value="0">None</option>'); 
                        $.each(result,function(key,value){
                        $("#category").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });

            });
        });*/
</script>
@endsection