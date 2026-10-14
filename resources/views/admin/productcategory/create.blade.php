@extends('layouts.adminmaster')
@section('title','Product Category | Add Product Category')
@section('content')

<style>
    .cust-tab-cont .tab-pane.active {
        background: none;
    }
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Add Product Category</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.productcategory.submitProductCategory')}}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Basic</h4>
                            </div>
                            <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                @foreach($data['languagesData'] as $languagesrow)
                                <!-- <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li> -->
                                @endforeach
                            </ul>

                            <div class="tab-content cust-tab-cont">
                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Name</label>
                                        <div class="col-md-12 border-bottom p-0">

                                            <input id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" type="text" data-id="{{$languagesrow->code}}" name="name_{{$languagesrow->code}}" value="{{ old('$oldvalname') }}" placeholder="Enter Category Name in {{ $languagesrow->name }}" class="seoinputname form-control p-0 border-0" autocomplete=off>


                                        </div>
                                        <span id="name-{{$languagesrow->code}}" class="text-danger name_error"></span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="Category SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off>
                                        </div>
                                        <span class="seo_error" class="text-danger"></span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="cat-des-{{$languagesrow->code}}" maxlength="500" onkeyup="charCount(this.value,this.id,500)" name="description_{{$languagesrow->code}}" class="form-control p-0 border-0">{{ old('description_en') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <span class="text-danger">@error('name_en'){{$message}} @enderror</span>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Parent Category</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="pcategory" id="category">
                                        <option value="0"> None </option>
                                        @foreach($data['parent_category'] as $parentcategory)
                                        <option value="{{ $parentcategory->id }}"> {{ $parentcategory->name }}</option>
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
                                    <input type="text" name="icon_class" class="form-control icon-picker" autocomplete="off" value="{{ old('icon_class') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 cus-title">
                        <label class="col-md-12">Featured</label>
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="pay" name="cat_feature" value="yes">
                                <label class="custom-control-label" for="1">Yes </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="pay" name="cat_feature" value="no" style="margin-left: 20px;">
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
                                </div>
                                <span class="text-danger">@error('image'){{$message}} @enderror</span>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Preview</label>
                                    <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Sort Value</label>
                                    <input type="number" min=0 name="sort_value" class="form-control" autocomplete="off" value="" placeholder="Enter value">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                            <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}" >
                                <button type="submit" class="btn btn-info">Save</button>
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
<script type="text/javascript">
    $(document).ready(function(){

        $(".seoinputname").focusout(function(){
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-"+languagecode).val();
            console.log("languagecode: ",languagecode);
            console.log("Actual Name: ",nameText);
            if(nameText!=""){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: baseUrl+'/admin/categoryseo',
                    data: {
                        'catname': nameText,
                        'category':'cate',
                        _token: '{{csrf_token()}}'
                    },
                    success: function(result) {
                        console.log(result.seoChangeData);

                        if(result.categoryTypeDetails!=""){
                            $("#seo-"+languagecode).val(result.categoryTypeDetails);
                        }else{
                            $("#seo-"+languagecode).val(result.seoChangeData);
                        }
                    }
                });
            }else{
                $("#name-"+languagecode).text("Enter Product Category Name First!");
            }
        });
        // $("#tutsmake").keyup(function(){
        //     $("#tutsmake").css("background-color", "yellow");
        // });
    });
</script>

{{-- <script>
    $(document).ready(function() {
        $("#fields").hide();
        $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
            //console.log(categorytype_id);
            $("#category").html('');

            if (categorytype_id == 2) {
                $("#fields").show();
            } else {
                $("#fields").hide();
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: baseUrl + '/admin/parent-category',
                data: {
                    'cat_type_id': categorytype_id,
                    _token: '{{csrf_token()}}'
                },
                success: function(result) {
                    $('#category').html('<option value="0">None</option>');
                    $.each(result, function(key, value) {
                        $("#category").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

        });
    });
</script> --}}
<script type="text/javascript">
    var categoryloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function(){
        var output = document.getElementById('preview-thumbnail-img');
        output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>
@endsection
