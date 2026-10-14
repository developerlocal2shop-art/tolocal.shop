@extends('layouts.client.vendor')
@section('title','Create offer')
@section('content')

<style>
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px !important;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    #infotooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #infotooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #infotooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -7px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #infotooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #infotooltip:hover #infotooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Dashboard</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->
<main>
    <div class="container margin_60_35">
        <div class="row1">
            <div class="page-wrapper">
                <div class="container-fluid add-companyinfo-mobileview">
                    @if(session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div><br />
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h4 class="page-title">Offer</h4>
                            <form action="{{ route('company.offer.submit') }}" onsubmit="return submitForm()" id="offerform" method="POST" class="" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <input type="hidden" id="lati" name="lati" >
                                <input type="hidden" id="long" name="long" >
                                <div class="row mb-4">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="panel-heading cust-tab-head" >
                                        <span style="display: inline-flex;">
                                            <h4 class="box-title">Select Product For Offer</h4>
                                            <div id="infotooltip">
                                                <span id="infotooltipText">To use the Product in several Offers, previous Offers must be removed.</span>
                                                <span><i class="fas fa-info-circle" style="color:#dadada; padding-left: 0px; padding-top: 12px;"></i></span>
                                            </div>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    
                                    <div class="col-md-8">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Select Product<span class="notranslate" style="color:red;">*</span></label><br>
                                            <div class="col-md-12 border-bottom p-0 offerproduct" id="productdata">
                                                <select multiple class="form-control" name="productId[]" id="productId"></select>
                                            </div>
                                            <span class="text-danger">@error('productId'){{$message}} @enderror</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Status</label>
                                            <div class="col-md-12 p-0">

                                                <select class="form-control" name="offer_status">
                                                    <option value="none" {{ "none"==old('offer_status') ? 'selected' : '' }}>None</option>
                                                    <option value="upcoming" {{ "upcoming"==old('offer_status') ? 'selected' : '' }}> Upcoming</option>
                                                    <option value="ongoing" {{ "ongoing"==old('offer_status') ? 'selected' : '' }}> Ongoing </option>
                                                    <option value="archive" {{ "archive"==old('offer_status') ? 'selected' : '' }}> Archive </option>
                                                    <option value="unpublished" {{ "unpublished"==old('offer_status') ? 'selected' : '' }}> unpublished</option>
                                                    <option value="displayed" {{ "displayed"==old('offer_status') ? 'selected' : '' }}> Displayed</option>
                                                    <option value="expired" {{ "expired"==old('offer_status') ? 'selected' : '' }}> Expired</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="panel-heading cust-tab-head">
                                            <h4 class="box-title">Basic Info(Your text automatically convert in other language)</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <input type="hidden" name="directoryId" id="directoryId"  value="{{auth()->user()->directory_id}}">

                                    <div class="col-md-12">
                                    <?php /* ?>
                                        <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                            @foreach($data['languagesData'] as $languagesrow)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                                    <span>{{ $languagesrow->name }}</span>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul><br>
                                    <?php */ ?>
                                        <div class="tab-content cust-tab-cont">
                                            @foreach($data['languagesData'] as $languagesrow)
                                            <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                                
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0" style="display: flex;">Offer Title
                                                    <div id="infotooltip">
                                                        <span id="infotooltipText">Always insert text in English, use Google Translation by clicking on the symbol that allows you to write in your native language and copy the text.</span>
                                                        <span><i class="fas fa-info-circle" style="color:#434242; padding-left: 5px;"></i></span>
                                                    </div>    
                                                    <span class="notranslate" style="color:red;">*</span>
                                                    <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                                    </label>
                                                    
                                                    <div class="col-md-12 border-bottom p-0">
                                                       <!-- <input type="text" id="offer-input-<?php echo $languagesrow->code; ?>" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="offer_title_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('offer_title_'.$languagesrow->code) }}" placeholder="Enter Offer Title in {{ $languagesrow->name }}" class="seoinputname offer-name form-control @if ($errors->has('offer_title_en')) border border-danger @else border-0 @endif"> -->
                                                       <!-- <input type="text" id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="offer_title_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('offer_title_'.$languagesrow->code) }}" placeholder="Enter Offer Title in {{ $languagesrow->name }}" class="seoinputname offer-name form-control @if ($errors->has('offer_title_en')) border border-danger @else border-0 @endif"> -->
                                                       <input type="text" id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="offer_title_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('offer_title_'.$languagesrow->code) }}" placeholder="Enter Offer Title in {{$languagesrow->name}}" class="seoinputname offer-name form-control @if ($errors->has('offer_title_en')) border border-danger @else border-0 @endif">
                                                    </div> 
                                                    <!-- <span class="text-danger" id='remainingC'></span> -->
                                                    <span class="text-danger">@error('offer_title_en'){{$message}} @enderror</span>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">SEO</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off> -->
                                                        <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="SEO" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="seo_error" class="text-danger"></span>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Description in {{$languagesrow->name}}<span class="notranslate" style="color:red;">*</span></label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <textarea rows="5" name="offer_description_{{$languagesrow->code}}" id="offer_description-<?php echo $languagesrow->code; ?>" maxlength="500" onkeyup="charCount(this.value,this.id,500)" placeholder="Enter Description in {{ $languagesrow->name }}" class="des form-control p-0 @if ($errors->has('offer_description_en')) border border-danger @else border-0 @endif">{{ old('offer_description_'.$languagesrow->code) }}</textarea> -->
                                                        <textarea rows="5" name="offer_description_{{$languagesrow->code}}" id="offer_description-<?php echo $languagesrow->code; ?>" maxlength="500" onkeyup="charCount(this.value,this.id,500)" placeholder="Enter Description" class="des form-control p-0 @if ($errors->has('offer_description_en')) border border-danger @else border-0 @endif">{{ old('offer_description_'.$languagesrow->code) }}</textarea>
                                                    </div>
                                                    <!-- <span class="text-danger" id='remainingC-des'></span> -->
                                                    <span class="text-danger">@error('offer_description_en'){{$message}} @enderror</span>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Meta Title</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <input type="text" name="meta_title_{{$languagesrow->code}}" value="{{ old('meta_title_'.$languagesrow->code) }}" placeholder="Enter Meta Title in {{ $languagesrow->name }}" class="metaTitle form-control border-0" autocomplete=off> -->
                                                        <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}" placeholder="Enter Meta Title" class="metaTitle form-control border-0" autocomplete=off>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Meta Description</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" class=" metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea> -->
                                                        <textarea rows="5" name="meta_description_en" id="product-metadesc-en" maxlength="50" placeholder="Enter Meta Description" class=" metaDescp form-control border-0">{{ old('meta_description_en') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                                
                                <div class="row cus-title">
                                    <div class="col-md-6 offer-bannerimg-infoicon" id="offer-banner-image">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0" style="display: flex;">Offer Banner Image<span class="notranslate" style="color:red;">*</span> <span class="img-dimension">(1920 X 650)px</span>
                                                <div id="tooltip">
                                                    <span id="tooltipText">Best result with (1920 X 650)px - other dimensions are acceptable but visualization can be poor.</span>
                                                    <span><i class="fas fa-info-circle" ></i></span>
                                                </div>
                                            </label>
                                            <div class="col-md-12  @if ($errors->has('offer_banner_image')) imageuploadify-error @else imageuploadify @endif">
                                                <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                                <input type="file" class="form-control" name="offer_banner_image" id="banner-image" onchange="loadbannerFile(event)">
                                            </div>
                                            <span class="text-danger">@error('offer_banner_image'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Banner Preview</label>
                                            <div class="col-md-12 bannerimgPreview" id="bannerimgPreview">
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-banner-img" alt="banner-img" width="70px" height="70px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-6 offer-mainimg-infoicon" id="offer-main-image">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0" style="display: flex;">Offer Image<span class="notranslate" style="color:red;">*</span> (800 X 800)px
                                                <div id="tooltip">
                                                    <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                                    <span><i class="fas fa-info-circle" ></i></span>
                                                </div>
                                            </label>
                                            <div class="col-md-12 @if ($errors->has('offer_image')) imageuploadify-error @else imageuploadify @endif">
                                                 <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                                <input type="file" class="form-control" name="offer_image" id="main-image" onchange="loadFile(event)">
                                            </div>
                                            <span class="text-danger">@error('offer_image'){{$message}} @enderror</span>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 offer-galimg-infoicon" id="multi-gal-image">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0" style="display: flex;">Gallery Image <span class="notranslate" style="color:red;">*</span> <span class="img-dimension">(800 X 800)px </span> &nbsp;&nbsp; <span class="text-danger"> max 20 pictures allowed!</span>
                                                <div id="tooltip">
                                                    <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                                    <span><i class="fas fa-info-circle" ></i></span>
                                                </div>
                                            </label>
                                            <div class="col-md-12 @if ($errors->has('offer_gallery_image')) imageuploadify-error @else imageuploadify @endif">
                                                <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                                <input type="hidden" name="array_gallery_listing[]" class="form-control" id="listing-gallery-array" value="">
                                                <input type="file" class="form-control" name="offer_gallery_image[]" id="offergal-image" multiple>
                                            </div>
                                            
                                                <span class="text-danger">@error('offer_gallery_image'){{$message}} @enderror</span>

                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Preview</label>
                                                    <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                                                    </div>
                                                </div>
                                            
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
                                <div id="discount_display_item">
                                    <div class="row cus-title">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Award<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="custom-control custom-radio product-item">
                                                    <input type="radio" class="pay" name="offer_discount" value="pricediscount" checked {{ old('offer_discount')=="pricediscount" ? 'checked' : '' }} style="margin-left: 15px;">
                                                    <label class="custom-control-label" for="1">Price Discount </label>
                                                </div>
                                                <div class="custom-control custom-radio product-item">
                                                    <input type="radio" class="pay" name="offer_discount" value="percentagediscount" {{ old('offer_discount')=="percentagediscount" ? 'checked' : '' }} style="margin-left: 15px;">
                                                    <label class="custom-control-label" for="1">Percentage discount</label>
                                                </div>
                                                <div class="custom-control product-item custom-radio">
                                                    <input type="radio" class="pay" name="offer_discount" value="new" {{ old('offer_discount')=="new" ? 'checked' : '' }} style="margin-left: 15px;">
                                                    <label class="custom-control-label" for="1">New</label>
                                                </div>
                                            </div>
                                            <span class="text-danger">@error('offer_discount'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <br />

                                    <div class="row cus-title" id="pricediscount">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Enter Price Discount in amount</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="pricedis" name="price_dis" value="{{ old('price_dis') }}" placeholder="Enter Price Discount in amount" class="form-control border-0" onkeypress="validate(event)">
                                                </div>
                                                <span class="text-danger">@error('price_dis'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row cus-title" id="percentagediscount">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Enter Percentage Discount(No need to enter % sign)</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="perdis" name="dis_per" value="{{ old('dis_per') }}" placeholder="Enter Percentage Discount" class="form-control border-0" onkeypress="validate(event)">
                                                </div>
                                                <span class="text-danger">@error('dis_per'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4" id="instore_div">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Voucher Code (It will be use in store only)<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="voucher_code" name="voucher_code" value="" placeholder="Enter Voucher Code" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('voucher_code'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Minimum Purchase Amount (in store only for customer club member)<span class="notranslate" style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" min="0" oninput="this.value = !!this.value &amp;&amp; Math.abs(this.value) &gt;= 0 ? Math.abs(this.value) : null" id="minimum_amount" name="minimum_amount" value="" placeholder="Enter Purchase Minimum Amount" class="form-control border-0">
                                                </div>
                                                <span class="text-danger">@error('minimum_amount'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <br />

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
                                            <div class="col-md-12 p-0">

                                                <select class="form-control" name="parentcategory" id="par-category" onChange="getSubCategory(this.value)">
                                                    <option>None</option>
                                                    @foreach($data['parent_category'] as $categorysrow)
                                                    <option value="{{ $categorysrow->id }}" @if(old('parentcategory')== $categorysrow->id) selected @endif> {{ $categorysrow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Child Category</label>
                                            <div class="col-md-12 p-0">

                                                <select multiple class="form-control" name="childcategory[]" id="child-category" onChange="getSelectedOptions(this)">
                                                    <option>None</option>
                                                    @foreach($data['productsubcat'] as $childcategorysrow)
                                                    <option value="{{ $childcategorysrow->id }}" @if (old("childcategory")){{ (in_array($childcategorysrow->id, old("childcategory")) ? "selected":"") }}@endif> {{ $childcategorysrow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Tags</label>
                                            <div class="col-md-12 p-0">

                                                <select multiple class="form-control" name="offertags[]" id="offer-tags">
                                                    <option>None</option>
                                                    @foreach($data['tags'] as $tagsrow)
                                                    <option value="{{ $tagsrow->id }}" @if (old("offertags")){{ (in_array($tagsrow->id, old("offertags")) ? "selected":"") }}@endif> {{ $tagsrow->tags }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="panel-heading cust-tab-head">
                                            <h4 class="box-title">Date when the offer will be displayed</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Offer Active Date<span class="notranslate" style="color:red;">*</span></label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="date" name="active_date" value="{{ old('active_date') }}" class="form-control  @if ($errors->has('active_date')) border border-danger @else border-0 @endif" autocomplete=off>
                                            </div>
                                            <span class="text-danger">@error('active_date'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Offer Expiry Date<span class="notranslate" style="color:red;">*</span></label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="date" name="expiry_date" id="expiry_date_picker" value="{{ old('expiry_date') }}" class="form-control  @if ($errors->has('expiry_date')) border border-danger @else border-0 @endif" autocomplete=off>
                                            </div>
                                            <span class="text-danger">@error('expiry_date'){{$message}} @enderror</span>
                                        </div>
                                    </div>


                                    <div class="col-md-12" id="single-video">
                                        <div class="form-group mb-4">
                                            <label class="p-0" style="display: flex;">Video</label>

                                                <input type="file" class="form-control" name="video" id="video">

                                            <span class="text-danger">@error('video'){{$message}} @enderror</span>
                                        </div>
                                    </div>

                                </div>

                                <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <div class="col-sm-12">
                                                <button type="submit" id="offer_btn" class="btn btn-info">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Column -->


                </div>

            </div>
        </div>
    </div>

    </div>
</main>
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
    $(document).ready(function() {
        
        $("#par-category, #child-category, #offer-tags, #productId").select2({
            placeholder: "Select option"
            , allowClear: true
        });
    

            $("#offer_btn").click(function() {
                var productId = $('#productId').val();
                localStorage.setItem('offer_productId', productId);
            });
        
            var directory_id = {{auth()->user()->directory_id}};
            $.ajax({
                url: baseUrl + '/company/product/get-product-by-directory',
                type: "POST",
                data: {
                    directory_id: directory_id, _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {

                    var productId1 = localStorage.getItem('offer_productId');
                    if (localStorage.getItem("offer_productId") === null) {
                        var arr = [];
                    }else{
                        var arr = productId1.split(',');
                    }

                    //$('#productId').html(`<option value="none">None</option>`);
                    $.each(result.productsdata, function(key, value) {
                        $("#productId").append('<option value="' + value.id + '">' + value.name +"-"+ value.id +'</option>');
                        $('#productId').val(arr).change();
                    });

                }
            });
        //});
    });

    $(document).ready(function() {
        var discountoffer = $('input[name=offer_discount]:checked', '#offerform').val();
        var discountfirstvalue = $('#pricedis').val();
        var discountsecondvalue = $('#perdis').val();
        console.log("Radio selected: ", discountoffer)
        if (discountoffer != "") {
            if (discountoffer == "percentagediscount") {
                $('#dis-per').val(discountfirstvalue);
                $("#percentagediscount").show();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if (discountoffer == "valuediscount") {
                $('#spent-dis').val(discountfirstvalue);
                $('#customer-spending').val(discountsecondvalue);
                $("#percentagediscount").hide();
                $("#valuediscount").show();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if (discountoffer == "pricediscount") {
                $('#price-dis').val(discountfirstvalue);
                $("#pricediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if (discountoffer == "pricereduction") {
                $('#originalprice').val(discountfirstvalue);
                $('#priceafterdiscount').val(discountsecondvalue);
                $("#pricereduction").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if (discountoffer == "quantitativediscount") {
                $('#many-buy').val(discountfirstvalue);
                $('#many-pay').val(discountsecondvalue);
                $("#quantitativediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#instore_div").css("display", "ruby");
            } else {
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").hide();
            }
        } else {
            $("#percentagediscount").hide();
            $("#valuediscount").hide();
            $("#pricediscount").hide();
            $("#pricereduction").hide();
            $("#quantitativediscount").hide();
            $("#instore_div").css("display", "ruby");
        }

        $("input[name=offer_discount]").change(function() {
            if ($(this).val() == "percentagediscount") {
                $("#percentagediscount").show();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if ($(this).val() == "valuediscount") {
                $("#percentagediscount").hide();
                $("#valuediscount").show();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if ($(this).val() == "pricediscount") {
                $("#pricediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if ($(this).val() == "pricereduction") {
                $("#pricereduction").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").css("display", "ruby");
            } else if ($(this).val() == "quantitativediscount") {
                $("#quantitativediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#instore_div").css("display", "ruby");
            } else {
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
                $("#instore_div").hide();
            }

        });
    });


    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-main-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    var loadbannerFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-banner-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };


    $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {
            if (input.files) {
                //console.log( input.files.length);
                var filesAmount = input.files.length;
                var k = 0;
                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $("<div class=\"thumbnail ui-state-default ui-sortable-handle\" data-id=" + k + ">" +
                            "<img src=\"" + event.target.result + "\" width=\"100px\" height=\"100px\" />" +
                            "<br/><span class=\"closebtn\"><i class=\"fas fa-trash\" style=\"color:#ff0505; \" aria-hidden=\"true\"></i></span>" +
                            "</div>").appendTo(imgPreviewPlaceholder);
                        $(".closebtn").click(function() {
                            $(this).parent(".thumbnail").remove();
                        });
                        k++;
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#offergal-image').on('change', function() {
            document.getElementById("preview-gallery-img").style.display = "none";
            multiImgPreview(this, 'div.galleryimgPreview');

        });
    });
    $(document).ready(function() {
        $("#galleryimgPreview").sortable();

    });

    function submitForm() {

        var image_gal_arr = [];

        $('#galleryimgPreview div').each(function() {
            var id = $(this).data('id');
            image_gal_arr.push(id);
            $("#listing-gallery-array").val(image_gal_arr);
        });

        return;
    }

    let linenoass = 2;

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
$(document).ready(function () {
    // Warning
    $(window).on('beforeunload', function(){
        return "Any changes will be lost";
    });
    // Form Submit
    $(document).on("submit", "form", function(event){
        // disable warning
        $(window).off('beforeunload');
    });
});

    $('.offer-name').on('keypress keyup keydown',function(event) {
  // create the event
   var press = jQuery.Event(event.type);
   var code = event.keyCode || event.which;
   press.which = code ;
  // trigger
    $('.metaTitle').val(this.value);
    $('.metaTitle').trigger(event.type, {'event': press});
    });


    $('.metaTitle').on('keypress keyup keydown change',function(event) {
    // sample
    $('.metaTitle').val(this.value);
    console.log(event.type);
    });

    $('.des').on('keypress keyup keydown',function(event) {
  // create the event
   var press = jQuery.Event(event.type);
   var code = event.keyCode || event.which;
   press.which = code ;
  // trigger
    $('.metaDescp').val(this.value);
    $('.metaDescp').trigger(event.type, {'event': press});
    });

    $('.metaDescp').on('keypress keyup keydown change',function(event) {
    // sample
    $('.metaDescp').val(this.value);
    console.log(event.type);
    });

    //Video file validation
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

        $(".seoinputname").focusout(function() {
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-" + languagecode).val();
            console.log("languagecode: ", languagecode);
            console.log("Actual Name: ", nameText);
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/company/offerseo'
                    , data: {
                        catname: nameText
                        , directory: 'offer'
                        , _token: '{{csrf_token()}}'
                    }
                    , success: function(result) {
                        console.log(result.seoChangeData);

                        if (result.offerTypeDetails != "") {
                            $("#seo-" + languagecode).val(result.offerTypeDetails);
                        } else {
                            $("#seo-" + languagecode).val(result.seoChangeData);
                        }
                    }
                });
            } else {
                $("#name-" + languagecode).text("Enter Offer Title First!");
            }
        });

    });

    function getSubCategory(cat_id) {
        $("#child-category").html('');
        $("#offer-tags").html('');
        $.ajax({
            url: baseUrl + '/get-offer-subprocat-by-productcatid',
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
        if (opts.length != 0) {
            $.ajax({
                //url: baseUrl + '/get-tags-by-categoriesid',
                url: baseUrl + '/get-tags-by-subcategoriesid',
                type: "POST"
                , data: {
                    category_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log(result);
                    $("#offer-tags").html("");
                    $.each(result.tags, function(key, value) {
                        $("#offer-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });
                }
            });
        }

    }
    
</script>
<style>
    .select2-container--default .select2-selection--single {
        border-right: 1px solid #aaaaaa !important;
    }
    #productdata span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
    .select2-container-multi {
        width: 300px
    }
    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:220px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
        right: 0;
        transform: translate(6%, 7%);
    }
    .imageuploadify-error{
        border: 2px dashed red;
        position: relative;
        min-width: 250px;
        max-width: 1000px;
        margin: auto;
        display: flex;
        flex-direction: column;
        text-align: center;
        background-color: #fff;
        color: #3aa0ff;
        padding: 20px !important;
    }
</style>

@endsection
