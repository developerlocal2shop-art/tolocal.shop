@extends('layouts.adminmaster')
@section('title','Product')
@section('content')

<?php

if (isset($data['productDetails'])) {
    $productDetails = $data['productDetails'];
}
if (isset($data['product_translationDesData'])) {
    $product_translationDesData = $data['product_translationDesData'];
}
if (isset($data['product_categoryData'])) {
    $product_categoryData = $data['product_categoryData'];
}
// echo $productDetails->sku;
   //echo "<pre>"; print_r($product_categoryData);die; 
?>
<style>
    .select2-container-multi {
        width: 300px
    }
    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
    }

    .thumbnail img {
        width: 70px;
        height: 70px;
    }

    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
        /* background: url(http://icons.iconarchive.com/icons/kyo-tux/delikate/512/Close-icon.png); */
        background-size: 100% 100%;
        background-repeat: no-repeat;

    }
    label {
        font-weight: bold;
        color: #203864;
    }
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Product Edit</h4>
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

                <form action="{{ route('admin.product.update', $productDetails->id) }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
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
                            @foreach($product_translationDesData as $product_translationDesDatarow)
                                <?php $languagecodeBasicData = Helper::getLanguagesDataById($product_translationDesDatarow->language_id); ?>
                                <div class="tab-pane {{ $product_translationDesDatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodeBasicData->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Product Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="product_name_{{$languagecodeBasicData->code}}" value="{{ old('product_name_'.$languagecodeBasicData->code, $product_translationDesDatarow->name ?? '') }}" placeholder="Enter Product Name {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0">
                                            <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code}}" value="{{ $product_translationDesDatarow->id }}" class="form-control p-0 border-0">
                                        </div>
                                        <!-- <span class="text-danger">@error('blog_title'){{$message}} @enderror</span> -->
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Short Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="short_des_{{$languagecodeBasicData->code}}" class="ckeditor form-control p-0 border-0">{{ old('short_des_'.$languagecodeBasicData->code, $product_translationDesDatarow->short_content ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Long Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="long_des_{{$languagecodeBasicData->code}}" class="ckeditor form-control p-0 border-0">{{ old('short_des_'.$languagecodeBasicData->code, $product_translationDesDatarow->long_content ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Title</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="meta_title_{{$languagecodeBasicData->code}}" value="{{ old('meta_title_'.$languagecodeBasicData->code, $product_translationDesDatarow->meta_title ?? '') }}" placeholder="Enter Meta Title in {{ $languagecodeBasicData->name }}" class="form-control border-0" autocomplete=off>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="meta_description_{{$languagecodeBasicData->code}}" class="form-control p-0 border-0">{{ old('meta_description_'.$languagecodeBasicData->code, $product_translationDesDatarow->meta_description ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                
                            @endforeach
                            </div>
                        </div>
                    </div>

                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Category</label>
                                <div class="col-md-12 ">

                                    <select multiple class="form-control" name="category[]" id="par-category">
                                        <option></option>
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}" <?php if(in_array($categorysrow->id,$product_categoryData)){echo "selected";} ?>> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">SKU</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="sku" value="{{ old('sku', $productDetails->sku) }}" placeholder="Enter SKU" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                            </div>
                        </div> --}}
                        <input type="hidden" name="sku" value="0" >
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Stock</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="stock" value="{{ old('stock', $productDetails->stock) }}" placeholder="Enter Stock" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('stock'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Out of Stock</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="outstock" id="" value="outofstock">
                                    <label class="custom-control-label" for="1"><i class="fa fa-warning" style="color: #636363;"></i> Out of Stock </label>
                                </div>
                                <span class="text-danger">@error('outstock'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Sales Price</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if(!empty($data['productpriceData'])){ ?>
                                @foreach($data['productpriceData']  as $currencyprrow)
                                <?php //echo "<pre>"; print_r($currencyprrow); ?>
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">{{ $currencyprrow['currency_name'] }}</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="hidden" name="salesprice_{{ $currencyprrow['currency_code'] }}" value="{{ $currencyprrow['id'] }}" class="form-control p-0 border-0">
                                                <input type="text" name="sales_{{ $currencyprrow['currency_code'] }}" value="{{ old('sku', $currencyprrow['sales_price']) }}" placeholder="Enter {{ $currencyprrow['currency_name'] }}" class="form-control border-0" {{ $currencyprrow['currency_base']=="no" ? 'disabled="disabled"' : ''}}>
                                            </div>
                                            <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                @endforeach
                                <?php }else{ ?>
                                    @foreach($data['currency']  as $currencyrow)
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="sales_{{ $currencyrow->code }}" value="{{ old($currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}}>
                                                </div>
                                                <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    @endforeach
                                <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Regular Price</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">    
                        <?php if(!empty($data['productpriceData'])){ ?>         
                            @foreach($data['productpriceData']  as $regularpricerow)
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{ $regularpricerow['currency_name'] }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="regular_{{ $regularpricerow['currency_code'] }}" value="{{ old('sku', $regularpricerow['regular_price']) }}" placeholder="Enter {{ $regularpricerow['currency_name'] }}" class="form-control border-0" {{ $regularpricerow['currency_base']=="no" ? 'disabled="disabled"' : ''}}>
                                        </div>
                                        <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            @endforeach
                        <?php }else{ ?>
                            @foreach($data['currency']  as $currencyrow)
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="regular_{{ $currencyrow->code }}" value="{{ old('sku') }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}}>
                                        </div>
                                        <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            @endforeach
                        <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="single-main-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Main Image</label>
                                <div class="col-md-12 ">
                                    <input type="file" class="form-control" name="main_image" id="main-image" onchange="loadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Gallery Image</label>
                                <div class="col-md-12 ">
                                    <input type="file" class="form-control" name="gal_image[]" id="gal-image" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                    @php
                                    $main_image_id = isset($productDetails->id)? $productDetails->id : "";
                                    $imagename_thumb = App\Http\Controllers\Admin\Product\ProductControllers::get_mainImage($main_image_id);
                                    @endphp
                                    <?php //echo "<pre>";print_r($imagename_thumb); echo $imagename_thumb[0]->name_thumbnail; ?>
                                    <?php if($imagename_thumb){ //echo "first"; ?>
                                        <div class="thumbnail">
                                            <img src="{{ env('IMAGE_PATH').'images/product/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" alt="directory-img" width="70px" height="70px" />
                                        </div>
                                    <?php }else{ ?>
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                                    @php
                                    $gallery_image_id = isset($productDetails->id)? $productDetails->id : "";
                                    $galleryimagename_thumb = App\Http\Controllers\Admin\Product\ProductControllers::get_galleryImage($gallery_image_id);
                                    @endphp
                                    <?php if (!empty($galleryimagename_thumb)) { ?>
                                        @foreach($galleryimagename_thumb as $imagenamerow)
                                            <div class="thumbnail">
                                                <img src="{{ env('IMAGE_PATH').'images/product/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" >
                                                <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                            </div>
                                        @endforeach
                                    <?php }else{ ?>
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Status</label>
                            <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="active" {{ $productDetails->status=='active' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" style="margin-left: 20px;" value="inactive" {{ $productDetails->status=='inactive' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Inactive </label>
                                </div>
                        </div>
                    </div>
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
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
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
    $(document).ready(function() {
        $('.categoryselect').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $("#par-category").select2({
                placeholder: "Select ",
                allowClear: true
        });
    });
</script>
<script>
    var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('preview-main-img');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
</script>
<script>
    $(function() {
            // Multiple images preview with JavaScript
            var multiImgPreview = function(input, imgPreviewPlaceholder) {              
                if (input.files) {
                    //console.log( input.files.length);
                    var filesAmount = input.files.length;
                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        reader.onload = function(event) { 
                            $("<div class=\"thumbnail \">" +
                                "<img src=\"" + event.target.result + "\" width=\"100px\" height=\"100px\" />" +
                                "<br/><span class=\"closebtn\"><i class=\"fas fa-trash\" style=\"color:#ff0505; \" aria-hidden=\"true\"></i></span>" +
                                "</div>").appendTo(imgPreviewPlaceholder);
                            $(".closebtn").click(function(){
                                $(this).parent(".thumbnail").remove();
                            });
                            
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };
            $('#gal-image').on('change', function() {
                document.getElementById("preview-gallery-img").style.display = "none";
                multiImgPreview(this, 'div.galleryimgPreview');
                
            });
        });   

    </script>
    <script>
        $(document).on('click', ".closebtn", function() {
            var val = $(this).closest(".thumbnail");
            var imageid = $(this).closest(".thumbnail").find('img').attr('data-id');
            var imagevalue = '/admin/delete-gallery-img';
            $.ajax({
                url: baseUrl + imagevalue,
                type: "POST",
                data: {id: imageid,_token: '{{csrf_token()}}'},
                dataType: 'json',
                success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        val.remove();
                    }

                }
            });


        });
    </script>
@endsection