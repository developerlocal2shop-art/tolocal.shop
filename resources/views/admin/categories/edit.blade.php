@extends('layouts.adminmaster')
@section('title','Category | Add Category')
@section('content')
<style>
    .cust-tab-cont .tab-pane.active {
        background: none;
    }
</style>
<?php
$singleCategoryData = $data['singleCategoryData'];
$languagesData = $data['languagesData'];
$languageAndTraslationData = $data['languageAndTraslationData'];
?>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Category</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.category.updateCategory', $singleCategoryData->id) }}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head" style="border:0px;background-color:#2f323e; border-radius :4px 4px 0px 0px; color:white;padding:6px 15px;">
                                <h4 class="box-title">Basic</h4>
                            </div>
                            <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                @foreach($languagesData as $languagesrow)
                                <?php //echo "<pre>"; print_r($languagesrow); ?>
                                <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{$languagesrow->name}}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content cust-tab-cont">
                                @foreach($languageAndTraslationData as $languagesdatarow)
                                <?php $languagecodenameData = Helper::getLanguagesDataById($languagesdatarow->language_id); //echo "<pre>"; print_r($languagecodenameData); ?>
                                <div class="tab-pane {{ $languagesdatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodenameData->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="cat-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="name_{{$languagecodenameData->code}}" value="{{ $languagesdatarow->title }}" placeholder="Enter Category Name in {{ $languagecodenameData->name }}" class="form-control p-0 border-0" autocomplete=off>
                                            <input type="hidden" name="langtranlation_{{$languagecodenameData->code}}" value="{{ $languagesdatarow->id }}" >
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="seo_{{$languagecodenameData->code}}" value="{{ $languagesdatarow->seo_url }}" placeholder="Category SEO in {{ $languagecodenameData->name }}" class="form-control p-0 border-0" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="cat-des-{{$languagecodenameData->code}}" maxlength="500" onkeyup="charCount(this.value,this.id,500)" name="description_{{$languagecodenameData->code}}" class="form-control p-0 border-0">{{ $languagesdatarow->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Category Type</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="categorytype" id="categorytype">
                                        <option value=""> Select </option>
                                        @foreach($data['categoriestype'] as $category)
                                        <?php if($category->title!="Sale"){ ?>
                                            <?php /* ?><option value="{{ $category->id }}" {{ $category->id == $singleCategoryData->category_type_id ? 'selected' : '' }}> {{ $category->title }}</option><?php */ ?>
                                        <option value="{{ $category->id }}" {{ $category->title == "Listing" ? 'selected' : '' }}> {{ $category->title }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('categorytype'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Parent Category</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <?php //dd($data['parent_category']); ?>
                                    <select class="form-control" name="pcategory" id="category">
                                        <option value="0"> None </option>
                                        @foreach($data['parent_category'] as $parentcategory)
                                        <option value="{{ $parentcategory->id }}" {{ $parentcategory->id == $singleCategoryData->parent_id ? 'selected' : '' }}> {{ $parentcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('pcategory'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Icon</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="icon_class" class="form-control icon-picker" value=" {{ $singleCategoryData->icon_class }} " autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 cus-title">
                            <label class="col-md-12">Featured</label>
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="pay" name="cat_feature" value="yes" {{ $singleCategoryData->feature=='yes' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="1">Yes </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="pay" name="cat_feature" value="no" style="margin-left: 20px;" {{ $singleCategoryData->feature=='no' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="1">No </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Main image (800 X 800)px</label>
                                    <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>
                                    <input type="file" class="form-control" name="image" onchange="categoryloadFile(event)">

                                    </div>

                                    <input type="hidden" name="uplaodId" value="{{ $singleCategoryData->upload_id }}">
                                </div>
                                <span class="text-danger">@error('image'){{$message}} @enderror</span>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Preview</label>
                                    <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                        <?php if($singleCategoryData->upload_id!=0){ ?>
                                        <?php $imagename = Helper::getCategoryimage($singleCategoryData->upload_id); ?>
                                        <img src="{{ env('IMAGE_PATH').'files/category/'.$imagename[0]->name_thumbnail }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                        <?php }else{ ?>
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                        <?php } ?>
                                        <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" /> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Sort Value</label>
                                    <input type="number" min=0 name="sort_value" class="form-control"  value="{{ $singleCategoryData->sort_value }}" placeholder="Enter value">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="fields">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading field-head">
                                    <h4 class="box-title">Fields</h4>
                                </div>
                                <?php if (isset($data['getCheckedField'])) {
                                    $insertField = $data['getCheckedField'];
                                    $insertField = explode(',', $insertField);
                                }  ?>
                                @foreach($data['getCusFields'] as $cusfield)

                                <label class="btn btn-light-info text-info font-weight-medium">
                                    <div class="form-check mr-sm-2">
                                        <input type="checkbox" name="catfields[]" value="{{ $cusfield->id }}" <?php if (in_array($cusfield->id, $insertField)) {
                                                                                                                    echo 'checked';
                                                                                                                } ?> class="form-check-input">
                                        <label class="form-check-label" for="checkbox1">{{ $cusfield->name }}</label>
                                    </div>
                                </label>

                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                                    <button type="submit" class="btn btn-info">Update Category</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script type="text/javascript">
    $(function() {
        $('.icon-picker').iconpicker();
    });

</script>
<script>
    $(document).ready(function() {
        var cattypevalue = $('select#categorytype option:selected').val();
        if (cattypevalue == 2) {
            $("#fields").show();
        } else {
            $("#fields").hide();
        }
        //console.log('cattypevalue',cattypevalue);
        $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
            console.log(categorytype_id);
            $("#category").html('');

            if (categorytype_id == 2) {
                $("#fields").show();
            } else {
                $("#fields").hide();
            }
            $.ajax({
                type: "POST"
                , dataType: "json"
                , url: baseUrl + '/admin/parent-category'
                , data: {
                    'cat_type_id': categorytype_id
                    , _token: '{{csrf_token()}}'
                }
                , success: function(result) {
                    $('#category').html('<option value="0">None</option>');
                    $.each(result, function(key, value) {
                        $("#category").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

        });
    });

</script>
<script type="text/javascript">
    var categoryloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>
@endsection
