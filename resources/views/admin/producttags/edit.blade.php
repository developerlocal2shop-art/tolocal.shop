@extends('layouts.adminmaster')
@section('title','ProductTags')
@section('content')

<?php

if (isset($data['tagseditdata'])) {
    $tagsEdit = $data['tagseditdata'];
}
if (isset($data['get_parent_category'])) {
    $category = $data['get_parent_category'];
}
if (isset($data['get_child_category'])) {
    $childCategory = $data['get_child_category'];
}
//  echo "<pre>"; print_r($data['blogAndTraslationData']);die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Tag Edit</h4>
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

                <form action="{{ route('admin.producttags.update', $tagsEdit->id) }}" method="POST" class="">

                    {{ csrf_field() }}

                    <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                        @foreach($data['languagesData'] as $languagesrow)
                        <li class="nav-item">
                            <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                <span>{{ $languagesrow->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>   

                    <div class="tab-content cust-tab-cont">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Category<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control select2" onChange="getSubCategory(this.value)" data-allow-clear="true" data-placeholder="Select category">
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $tagsEdit['category_id'] ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('category'){{$message}} @enderror</span>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Sub-Category<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="subcategory" id="subcategory" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select sub-category">
                                        @foreach($childCategory as $childcat)
                                        <option value="{{ $childcat->id }}" {{ $childcat->id == $tagsEdit['child_cat_id'] ? 'selected' : '' }}>{{ $childcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('subcategory'){{$message}} @enderror</span>
                            </div>
                        </div>
                        
                            {{-- <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Tags</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="tags_name" value="{{ $tagsEdit->tags }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                                    </div>
                                </div>
                            </div> --}}

                            @foreach($data['tagTraslationData'] as $trow)
                            <?php $languagecodenameData = Helper::getLanguagesDataById($trow->language_id); ?>
                            <div class="tab-pane {{ $trow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodenameData->name }}" role="tabpanel">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Tag
                                    <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                    </label>
                                    <div class="col-md-12 border-bottom @error('tags_name_'.$languagecodenameData->code) errorClass @enderror">
                                        <input type="text" id="cat-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="tags_name_{{$languagecodenameData->code}}" data-id="{{$languagecodenameData->code}}" value="{{ old('tags_name' ,$trow->title) }}" placeholder="Enter tag title {{ $languagecodenameData->name }}" class="blognametitle seoinputname form-control border-0">
                                        <input type="hidden" name="tranlation_{{$languagecodenameData->code}}" value="{{ $trow->id }}" class="form-control p-0 border-0">
                                    
                                    </div>
                                    <span class="text-danger">@error('tags_name_'.$languagecodenameData->code){{$message}} @enderror</span>
                                </div>
                                
                            </div>
                            @endforeach
                        
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
    function getSubCategory(cat_id) {
        //alert("Category id: "+cat_id);
        $("#subcategory").html('');
        $.ajax({
            url: baseUrl + '/get-subproductcategory-by-productcategoryid',
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
