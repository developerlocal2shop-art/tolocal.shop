@extends('layouts.adminmaster')
@section('title','Edit Shopping Card')
@section('content')

<?php

if (isset($data['shoppingcardEditDetails'])) {
    $shoppingcardEditDetails = $data['shoppingcardEditDetails'];
}
if (isset($data['shoppingcardMetaTag'])) {
    $shoppingcardMetaTag = $data['shoppingcardMetaTag'];
}
if(isset($data['pro_subcat'])){
    $productsubcat = $data['pro_subcat'];
}
if (isset($data['tags'])) {
    $product_tagData = $data['tags'];
}
   //echo "<pre>"; print_r($offerEditDetails);die;
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

    .cust-tab-cont .tab-pane.active {
        background: none;
    }

</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Shopping Card Edit<a class="btn btn-xs" title="Link Product" target="_blank" href="<?php echo url("shopping-card/detail", ['id' => @$shoppingcardEditDetails->slug]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a></h4>
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
        @if(session()->get('product_add_error'))
        <div class="alert alert-danger">
            {{ session()->get('product_add_error') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.shoppingcard.update', $shoppingcardEditDetails->id) }}" method="POST" id="shoppingcardformedit" class="" enctype="multipart/form-data" onsubmit="return submitForm()">

                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >

                    <div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Basic Info</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
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

                            @if(count($data['shoppingcard_translationDesData']) > 0)

                                @foreach($data['shoppingcard_translationDesData'] as $shoppingcard_translationDesDatarow)
                                    <?php
                                    $languagecodeBasicData = Helper::getLanguagesDataById($shoppingcard_translationDesDatarow->language_id);
                                    //dd($languagecodeBasicData->name);
                                    ?>
                                    <div class="tab-pane {{ $shoppingcard_translationDesDatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodeBasicData->name??'' }}" role="tabpanel">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Shopping Card Title<span style="color:red;">*</span>
                                            <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                            </label>
                                            <div class="col-md-12 border-bottom p-0">
                                                {{-- <input type="text" id="shoppingcard-input" maxlength="40" onkeyup="charCount(this.value,this.id,40)" data-limit="40" name="shoppingcard_title" value="{{ old('shoppingcard_title', $shoppingcardEditDetails->title ) }}" placeholder="Enter Offer Title" class="form-control border-0"> --}}
                                                <input type="text" maxlength="40" name="shoppingcard_title_{{$languagecodeBasicData->code}}" onkeyup="charCount(this.value,this.id,40)" id="cat-{{$languagecodeBasicData->code}}" data-id="{{$languagecodeBasicData->code}}" value="{{ old('shoppingcard_title_'.$languagecodeBasicData->code, $shoppingcard_translationDesDatarow->title ?? '') }}" placeholder="Enter Shopping card title in {{ $languagecodeBasicData->name }}" class="seoinputname shoppingcard-name form-control border-0">
                                                <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code}}" value="{{ $shoppingcardEditDetails->id }}" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">@error('shoppingcard_title_en'){{$message}} @enderror</span>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">SEO</label>
                                            <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="seo-{{$languagecodeBasicData->code}}" name="seo_{{$languagecodeBasicData->code}}" value="{{ $shoppingcardEditDetails->slug }}" placeholder="Shopping card SEO in {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0" readonly >
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Description</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <textarea rows="5" maxlength="500" name="shoppingcard_description_{{$languagecodeBasicData->code}}" onkeyup="charCount(this.value,this.id,500)" id="shoppingcard_description-{{$languagecodeBasicData->code}}" class="des form-control p-0 border-0">{{ old('shoppingcard_description_'.$languagecodeBasicData->code, $shoppingcard_translationDesDatarow->description ?? '') }}</textarea>
                                            </div>
                                            <span class="text-danger">@error('shoppingcard_description_en'){{$message}} @enderror</span>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Meta Title</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" name="meta_title_{{$languagecodeBasicData->code??''}}" value="{{ old('meta_title_'.$languagecodeBasicData->code??'', $shoppingcard_translationDesDatarow->meta_title ?? '') }}" placeholder="Enter Meta Title in English" class="metaTitle form-control border-0" autocomplete=off>
                                            </div>
                                        </div>
                                        <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code??''}}" value="{{ $shoppingcard_translationDesDatarow->id }}" class="form-control border-0">
                                        
                                        <input type="hidden" name="page_type" value="{{ $shoppingcardMetaTag->page_type??'' }}" class="form-control p-0 border-0">
                                        <input type="hidden" name="module_id" value="{{ $shoppingcardMetaTag->module_id??'' }}" class="form-control p-0 border-0">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Meta description</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <textarea rows="5" name="meta_description_{{$languagecodeBasicData->code??''}}" id="meta_description_{{$languagecodeBasicData->code??''}}" class="metaDescp form-control border-0">{{ old('meta_description_'.$languagecodeBasicData->code??'', $shoppingcard_translationDesDatarow->meta_description ?? '') }}</textarea>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            @else

                            <input type="hidden" name="langtranlation_id" value="0" class="form-control border-0">


                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Title<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            {{-- <input type="text" id="shoppingcard-input" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="shoppingcard_title" value="{{ old('shoppingcard_title') }}" placeholder="Enter Shopping card Title" class="shoppingcard-name form-control border-0"> --}}
                                            <input type="text"  maxlength="40" name="shoppingcard_title_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,40)" id="shoppingcard-input-<?php echo $languagesrow->code; ?>" value="{{ old('shoppingcard_title_'.$languagesrow->code) }}" placeholder="Enter Shopping card Title in {{ $languagesrow->name }}" class="shoppingcard-name form-control border-0">
                                        </div>
                                        <span class="text-danger" id='remainingC'></span>
                                        <span class="text-danger">@error('shoppingcard_title_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" maxlength="500" name="shoppingcard_description_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,500)" id="shoppingcard_description-<?php echo $languagesrow->code; ?>" class="des form-control p-0 border-0">{{ old('shoppingcard_description_'.$languagesrow->code) }}</textarea>
                                        </div>
                                        <span class="text-danger" id='shoppingcard-des-<?php echo $languagesrow->code; ?>'></span>
                                        <span class="text-danger">@error('shoppingcard_description_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Title</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="meta_title_{{$languagesrow->code}}" value="{{ old('meta_title_'.$languagesrow->code) }}" placeholder="Enter Meta Title in {{ $languagesrow->name }}" class="metaTitle form-control border-0" autocomplete=off>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class=" metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                @endforeach

                            @endif

                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Directory*</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <?php $allDirectory = Helper::getAllDirectory(); ?>
                                    <select class="form-control" name="directoryId" id="directoryId">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                        <option value="{{ $directoryrow->id }}" {{ $shoppingcardEditDetails->directory_link==$directoryrow->id ? 'selected' : '' }}> {{ $directoryrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('directoryId'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Product<span style="color:red;">*</span></label><br>
                                <?php
                                $allProductRelatedToDirectory = Helper::getallProductRelatedToDirectory($shoppingcardEditDetails->directory_link);
                                //echo "<pre>"; print_r($allProductRelatedToDirectory);
                                $product_ids = explode(",",$shoppingcardEditDetails->product_id); ?>
                                <div class="col-md-12" id="productdata">
                                    <select multiple class="form-control" name="productId[]" id="productId">
                                        <option>None</option>
                                        @foreach($allProductRelatedToDirectory as $productrow)
                                        <option value="{{ $productrow['id'] }}" <?php if(in_array($productrow['id'],$product_ids)){echo "selected";} ?>> {{ $productrow['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('productId'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6" id="shoppingcard-banner-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Shopping Card Banner Image<span style="color:red;">*</span> (1920 X 650)px</label>
                                <div class="col-md-12 imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="shoppingcard_banner_image" id="banner-image" onchange="loadbannerFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Banner Preview</label>
                                <div class="col-md-12 bannerimgPreview" id="bannerimgPreview">
                                    @php
                                    $banner_image_id = isset($shoppingcardEditDetails->id)? $shoppingcardEditDetails->id : "";
                                    $imagename_thumb = App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::get_bannerImage($banner_image_id);
                                    @endphp
                                    <?php if($imagename_thumb){ ?>
                                    <div class="thumbnail">
                                        <img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" id="preview-banner-img" alt="banner-img" width="70px" height="70px" />
                                    </div>
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-banner-img" alt="banner-img" width="70px" height="70px" />
                                    <?php } ?>
                                    <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-banner-img" alt="banner-img" width="70px" height="70px" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6" id="offer-main-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Shopping Card Image<span style="color:red;">*</span> (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>
                                    <input type="file" class="form-control" name="shoppingcard_image" id="main-image" onchange="loadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Gallery Image<span style="color:red;">*</span> (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify">

                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload<br>You have to upload multiple images at a time</span>
                                    <input type="hidden" name="thumb_hidden_img" id="thumb_hidden_img" value="">
                                    <input type="file" class="form-control" name="shoppingcardgal_image[]" id="shoppingcardgal-image" multiple>
                                </div>
                                <span class="text-danger">max 20 pictures allowed!</span>
                                <span class="text-danger">@error('shoppingcardgal_image'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                    @php
                                    $main_image_id = isset($shoppingcardEditDetails->id)? $shoppingcardEditDetails->id : "";
                                    $imagename_thumb = App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::get_mainImage($main_image_id);
                                    @endphp
                                    <?php if($imagename_thumb){ //echo "first"; ?>
                                    <div class="thumbnail">
                                        <img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                    </div>
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                    <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" /> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                                    @php
                                    $gallery_image_id = isset($shoppingcardEditDetails->id)? $shoppingcardEditDetails->id : "";
                                    $galleryimagename_thumb = App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::get_galleryImage($gallery_image_id);
                                    @endphp
                                    <?php if (!empty($galleryimagename_thumb)) { ?>
                                    @foreach($galleryimagename_thumb as $imagenamerow)
                                    <div class="thumbnail" data-thumbimages="{{ $imagenamerow->name_thumbnail }}">
                                        <img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}">
                                        <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                    </div>
                                    @endforeach
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                    <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" /> -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Status</label>
                            <div class="col-md-12 ">

                                <select class="form-control" name="shoppingcard_status">
                                    <option value="none" {{ "none"==$shoppingcardEditDetails->status ? 'selected' : '' }}>None</option>
                                    <option value="upcoming" {{ "upcoming"==$shoppingcardEditDetails->status ? 'selected' : '' }}> Upcoming</option>
                                    <option value="ongoing" {{ "ongoing"==$shoppingcardEditDetails->status ? 'selected' : '' }}> Ongoing </option>
                                    <option value="archive" {{ "archive"==$shoppingcardEditDetails->status ? 'selected' : '' }}> Archive </option>
                                    <option value="unpublished" {{ "unpublished"==$shoppingcardEditDetails->status ? 'selected' : '' }}> unpublished</option>
                                    <option value="displayed" {{ "displayed"==$shoppingcardEditDetails->status ? 'selected' : '' }}> Displayed</option>
                                    <option value="expired" {{ "expired"==$shoppingcardEditDetails->status ? 'selected' : '' }}> Expired</option>

                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Discount</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Award</label>

                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="shoppingcard_discount" value="pricediscount" {{ $shoppingcardEditDetails->discount_selectedvalue=="pricediscount" ? 'checked' : '' }} style="margin-left: 15px;">
                                    <label class="custom-control-label" for="1">Price Discount </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="shoppingcard_discount" value="percentagediscount" {{ $shoppingcardEditDetails->discount_selectedvalue=="percentagediscount" ? 'checked' : '' }} style="margin-left: 15px;">
                                    <label class="custom-control-label" for="1">Percentage discount</label>
                                </div>
                                <div class="custom-control product-item custom-radio">
                                    <input type="radio" class="pay" name="shoppingcard_discount" value="new" {{ $shoppingcardEditDetails->discount_selectedvalue=="new" ? 'checked' : '' }} style="margin-left: 15px;">
                                    <label class="custom-control-label" for="1">New</label>
                                </div>


                            </div>
                        </div>
                    </div>
                    <br />
                    <input type="hidden" id="discount-firstvalue" value="{{ $shoppingcardEditDetails->discount_firstvalue }}" />
                    <input type="hidden" id="discount-secondvalue" value="{{ $shoppingcardEditDetails->discount_secondvalue }}" />

                    <div class="row cus-title" id="pricediscount">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Enter Price Discount in amount</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" id="price-dis" name="price_dis" value="{{ old('price_dis') }}" placeholder="Enter Discount in amount" class="form-control border-0" onkeypress="validate(event)">
                                </div>
                                <span class="text-danger">@error('dis_per'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title" id="percentagediscount">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Enter Percentage Discount(No need to enter % sign)</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" id="dis-per" name="dis_per" value="{{ old('dis_per') }}" placeholder="Enter Discount" class="form-control border-0" onkeypress="validate(event)">
                                </div>
                                <span class="text-danger">@error('dis_per'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Searchability</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Parent Category</label>
                                <div class="col-md-12 ">
                                    <!-- <?php $search_parent_category  = explode(",",$shoppingcardEditDetails->search_parent_category); ?>
                                    <select multiple class="form-control" name="parentcategory[]" id="par-category">
                                        <option>None</option> -->
                                    <select class="form-control" name="parentcategory" id="par-category" onChange="getSubCategory(this.value)">
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}" @if($categorysrow->id==$shoppingcardEditDetails->search_parent_category) selected @endif> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Child Category</label>
                                <div class="col-md-12 ">
                                    <?php $search_child_category  = explode(",",$shoppingcardEditDetails->search_child_category); ?>
                                    <select multiple class="form-control" name="childcategory[]" id="child-category" onChange="getSelectedOptions(this)">
                                        <!-- <option>None</option> -->
                                        @foreach($productsubcat as $childcategorysrow)
                                        <option value="{{ $childcategorysrow['id'] }}" <?php if(in_array($childcategorysrow['id'],$search_child_category)){echo "selected";} ?>> {{ $childcategorysrow['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tags</label>
                                <div class="col-md-12 ">
                                    <?php $tags  = explode(",",$shoppingcardEditDetails->tags); ?>
                                    <select multiple class="form-control" name="shoppingcardtags[]" id="offer-tags">
                                        <!-- <option>None</option> -->
                                        @foreach($product_tagData as $tagsrow)
                                        <option value="{{ $tagsrow['id'] }}" <?php if(in_array($tagsrow['id'],$tags)){echo "selected";} ?>> {{ $tagsrow['tags'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Date when the shopping card will be displayed</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Set Default Duration</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_status" value="yes" {{ $shoppingcardEditDetails->default_duration=='yes' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Yes</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_status" value="no" {{ $shoppingcardEditDetails->default_duration=='no' ? 'checked' : '' }} style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="duration-month">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Set Months</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="set_months" value="6_months" {{ $shoppingcardEditDetails->default_month=='6_months' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">6 months </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="set_months" value="12_months" {{ $shoppingcardEditDetails->default_month=='12_months' ? 'checked' : '' }} style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">12 months </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Shopping Card Active Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="active-date" name="active_date" value="{{ $shoppingcardEditDetails->active_date }}" class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('active_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Shopping Card Expiry Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="expiry-date" name="expiry_date" value="{{ $shoppingcardEditDetails->expire_date }}"  class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('expiry_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-12" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Video</label>

                                    <input type="file" class="form-control" name="video" id="video" >

                                <span class="text-danger">@error('video'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    @php
                    $video = $shoppingcardEditDetails->video??'';
                    @endphp

                    <div class="row">
                        @if(!empty($video))
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12">Preview</label>
                                <div class="col-md-12">
                                    @if($video)
                                        <video width="200" height="120" controls preload="auto" style="opacity: unset !important;">
                                            <source src="{{ env('IMAGE_PATH').'files/shoppingcard/'.$video }}" type="video/mp4">
                                        </video>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
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
<script>
    function showPosition() {
        if(navigator.geolocation) {
            //navigator.geolocation.getCurrentPosition(showMap, showError);
            navigator.geolocation.getCurrentPosition(function(position) {
                    var positionInfo1 = position.coords.latitude;
                    var positionInfo2 =  position.coords.longitude;
                    document.getElementById("lati").value = positionInfo1;
                    document.getElementById("long").value = positionInfo2;
                    //console.log("lat"+positionInfo1+"log"+positionInfo2);
                    <?php 
                    //} }
                    //$lati ="<script>document.write(positionInfo1)</script>"; 
                    //$long ="<script>document.write(positionInfo2)</script>";            
                    ?>
                });
        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }
    window.onload = showPosition;
</script>
<script>
    var defaultselectedData = $('input[name=default_status]:checked').val();
    if(defaultselectedData=='no'){
        $('#duration-month').hide();
    }

    $("input[name=default_status]").change(function() {

        if ($(this).val() != "yes") {
            $('#duration-month').hide();
            $('input[name=set_months]').prop('checked', false);
            $('#active-date').val('');
            $('#expiry-date').val('');
        } else {
            $('#duration-month').show();
        }

    });
        var currentdate = new Date();
        var day = ("0" + currentdate.getDate()).slice(-2);
        var month = ("0" + (currentdate.getMonth() + 1)).slice(-2);
        var today = currentdate.getFullYear()+"-"+(month)+"-"+(day) ;
    $("input[name=set_months]").change(function() {
        if ($(this).val() == "6_months") {
            var now = new Date();
            var sixMonthsFromNow = new Date(now.setMonth(now.getMonth() + 6));
            var sixday = ("0" + sixMonthsFromNow.getDate()).slice(-2);
            var sixmonth = ("0" + (sixMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftersixmonthdate = sixMonthsFromNow.getFullYear()+"-"+(sixmonth)+"-"+(sixday) ;
            $('#active-date').val(today);
            $('#expiry-date').val(aftersixmonthdate);
        }
        if($(this).val() == "12_months"){
            var nowcurr = new Date();
            var twelveMonthsFromNow = new Date(nowcurr.setMonth(nowcurr.getMonth() + 12));
            var twelveday = ("0" + twelveMonthsFromNow.getDate()).slice(-2);
            var twelvemonth = ("0" + (twelveMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftertwelvemonthdate = twelveMonthsFromNow.getFullYear()+"-"+(twelvemonth)+"-"+(twelveday) ;
            $('#active-date').val(today);
            $('#expiry-date').val(aftertwelvemonthdate);
        }

    });
    $(document).ready(function() {

        $("#galleryimgPreview").sortable();

        $('#directoryId').on('change', function() {
            $("#productId").val("");
            $("#productId").trigger("change");
            var directory_id = this.value;
            //$("#state-dropdown").html('');
            $.ajax({
                url: baseUrl + '/admin/get-product-by-directory',

                type: "POST"
                , data: {
                    directory_id: directory_id
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#productId').html(`<option value="none">None</option>`);
                    $.each(result.productsdata, function(key, value) {
                        $("#productId").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    //$('#productId').append('</select>');
                }
            });
        });
    });

</script>
<script>
    $(document).ready(function() {
        $("#par-category, #child-category, #offer-tags, #directoryId, #productId").select2({
            placeholder: "Select "
            , allowClear: true
        });
    });

</script>

<script>
    $(document).ready(function() {
        var discountoffer = $('input[name=shoppingcard_discount]:checked', '#shoppingcardformedit').val();
        var discountfirstvalue = $('#discount-firstvalue').val();
        var discountsecondvalue = $('#discount-secondvalue').val();
        console.log("Radio selected: ", discountoffer)
        if (discountoffer != "") {
            if (discountoffer == "percentagediscount") {
                $('#dis-per').val(discountfirstvalue);
                $("#percentagediscount").show();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if (discountoffer == "valuediscount") {
                $('#spent-dis').val(discountfirstvalue);
                $('#customer-spending').val(discountsecondvalue);
                $("#percentagediscount").hide();
                $("#valuediscount").show();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if (discountoffer == "pricediscount") {
                $('#price-dis').val(discountfirstvalue);
                $("#pricediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if (discountoffer == "pricereduction") {
                $('#originalprice').val(discountfirstvalue);
                $('#priceafterdiscount').val(discountsecondvalue);
                $("#pricereduction").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#quantitativediscount").hide();
            } else if (discountoffer == "quantitativediscount") {
                $('#many-buy').val(discountfirstvalue);
                $('#many-pay').val(discountsecondvalue);
                $("#quantitativediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
            } else {
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            }
        } else {
            $("#percentagediscount").hide();
            $("#valuediscount").hide();
            $("#pricediscount").hide();
            $("#pricereduction").hide();
            $("#quantitativediscount").hide();
        }

        $("input[name=shoppingcard_discount]").change(function() {
            if ($(this).val() == "percentagediscount") {
                $("#percentagediscount").show();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if ($(this).val() == "valuediscount") {
                $("#percentagediscount").hide();
                $("#valuediscount").show();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if ($(this).val() == "pricediscount") {
                $("#pricediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if ($(this).val() == "pricereduction") {
                $("#pricereduction").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#quantitativediscount").hide();
            } else if ($(this).val() == "quantitativediscount") {
                $("#quantitativediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
            } else {
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            }

        });
    });

</script>
<script>
    $(document).ready(function() {
        var offerType = $('input[name=offer_type]:checked', '#shoppingcardformedit').val();
        if (offerType != "") {
            if (offerType == "product") {
                $("#product_div").show();
                $(".dir-link").hide();
            } else if (offerType == "directory") {
                $("#product_div").hide();
                $(".dir-link").show();
            } else {
                $("#product_div").hide();
                $(".dir-link").hide();
            }
        } else {
            $("#product_div").hide();
            $(".dir-link").hide();
        }
        // console.log("Radio selected: ",discountoffer)

        $("input[name=offer_type]").change(function() {
            if ($(this).val() == "product") {
                $("#product_div").show();
                $(".dir-link").hide();
            } else if ($(this).val() == "directory") {
                $("#product_div").hide();
                $(".dir-link").show();
            } else {
                $("#product_div").hide();
                $(".dir-link").hide();
            }

        });
    });

</script>

<script>
    var loadbannerFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-banner-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-main-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    function submitForm() {
        var thumbimages = [];
        $('.galleryimgPreview div').each(function() {
            thumbimages.push($(this).data('thumbimages'));

        });
        //alert(thumbimages);
        $('#thumb_hidden_img').val(thumbimages.join(','));
        return;
    }

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
                        $(".closebtn").click(function() {
                            $(this).parent(".thumbnail").remove();
                        });

                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#shoppingcardgal-image').on('change', function() {
            //document.getElementById("preview-gallery-img").style.display = "none";
            multiImgPreview(this, 'div.galleryimgPreview');

        });
    });

</script>

<script>
    $(document).on('click', ".closebtn", function() {
        var val = $(this).closest(".thumbnail");
        var imageid = $(this).closest(".thumbnail").find('img').attr('data-id');
        var imagevalue = '/admin/delete-shoppingcardgallery-img';
        $.ajax({
            url: baseUrl + imagevalue
            , type: "POST"
            , data: {
                id: imageid
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                // console.log('imageid',result);
                if (result == true) {
                    val.remove();
                }

            }
        });


    });

</script>

<script>
    let linenoass = $('#dirlinkcount').val();

    function addDirLinkSection() {

        $("#dirlink_1").after(`
        <div class="row cus-title dir-link" id="dirlink_` + linenoass + `">
            <div class="col-md-5">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">Enter a Directory Link</label>
                    <div class="col-md-12 border-bottom p-0">
                        <input type="text" name="dir_link_` + linenoass + `" value="{{ old('website') }}" placeholder="Enter Directory Link" class="form-control p-0 border-0" autocomplete=off>
                    </div>
                    <span class="text-danger">@error('dir_link'){{$message}} @enderror</span>
                </div>
            </div>

            <div class="col-md-1">
                <button type="button" class="btn btn-danger" class="remove_dir_link_button" title="Remove field" onclick=removeDirLinkSection(` + linenoass + `)>-</button>
            </div>
        </div>
        `);
        $('#dirlinkcount').val(linenoass);
        linenoass++;
    }

    function removeDirLinkSection(count) {
        $('#dirlink_' + count).remove();
    }

</script>
<script type="text/javascript">
$('.shoppingcard-name').on('keypress keyup keydown',function(event) {
  // create the event
   var press = jQuery.Event(event.type);
   var code = event.keyCode || event.which;
   press.which = code ;
 
    //$('.metaTitle').val(this.value);
    //$('.metaTitle').trigger(event.type, {'event': press});
});


$('.metaTitle').on('keypress keyup keydown change',function(event) {
    //$('.metaTitle').val(this.value);
});
</script>

<script type="text/javascript">
$('.des').on('keypress keyup keydown',function(event) {
  // create the event
   var press = jQuery.Event(event.type);
   var code = event.keyCode || event.which;
   press.which = code ;
    //$('.metaDescp').val(this.value);
    //$('.metaDescp').trigger(event.type, {'event': press});
});


$('.metaDescp').on('keypress keyup keydown change',function(event) {
    //$('.metaDescp').val(this.value);
});

$('#video').change(function(){
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf" || ext == "txt" || ext == "xlsx" || ext == "xls" || ext == "doc" || ext == "dot" || ext == "docx" || ext == "GIF" || ext == "xml" || ext == "csv")
        {
            alert("Only Video file is accepted");
            $(this).val('');
        }
    });

    //Prevent Negative value from discount
    function validate(event) {
      if (event.key == "-") {
        event.preventDefault();
        return false;
      }
    }   
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#loader").fadeOut("slow");

        $(".seoinputname-bk").focusout(function() {
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-" + languagecode).val();
            console.log("languagecode: ", languagecode);
            console.log("Actual Name: ", nameText);
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/admin/shoppingcard-slug'
                    , data: {
                        catname: nameText
                        , directory: 'shoppingcard'
                        , _token: '{{csrf_token()}}'
                    }
                    , success: function(result) {
                        console.log(result.seoChangeData);

                        if (result.shoppingcardypeDetails != "") {
                            $("#seo-" + languagecode).val(result.shoppingcardypeDetails);
                        } else {
                            $("#seo-" + languagecode).val(result.seoChangeData);
                        }
                    }
                });
            } else {
                $("#name-" + languagecode).text("Enter Shoppingcard Title First!");
            }
        });

    });

    function getSubCategory(cat_id) {
        $("#child-category").html('');
        $("#offer-tags").html('');
        $.ajax({
            url: baseUrl + '/admin/get-offer-subprocat-by-productcatid',
            type: "POST",
            data: { cat_id: cat_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {
                console.log(result);
                $.each(result.subcat, function(key, value) {
                    $("#child-category").append('<option value="' + value.id + '">' + value.name + '</option>');
                });

            }
        });
    }

    function getSelectedOptions(sel) {
        var opts = []
            , opt;
        var len = sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = sel.options[i];
            if (opt.selected) {
                opts.push(opt.value);
            }
        }

        var selectedvalueTags = $("#offer-tags").val();
        //console.log("Selected Value: ", selectedvalueTags);
        var selectedvalueTagsqwer = [];
        for(var k=0;k<selectedvalueTags.length;k++){
            selectedvalueTagsqwer.push(parseInt(selectedvalueTags[k]));
        }
        if (opts.length != 0) {
            $.ajax({
                //url: baseUrl + '/get-tags-by-categoriesid',
                url: baseUrl + '/get-product-tags-by-subcategoriesid',
                type: "POST"
                , data: {
                    category_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log(result);
                    $("#offer-tags").html("");
                    var valuetags = "";
                    $.each(result.tags, function(key, value) {
                        if(jQuery.inArray(value.id,selectedvalueTagsqwer) == -1){ valuetags = ""; }else{ valuetags = "selected"; }
                        $("#offer-tags").append('<option value="' + value.id + '"'+valuetags+'>' + value.tags + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });
                }
            });
        }

    }

</script>
@endsection
