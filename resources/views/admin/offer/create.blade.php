@extends('layouts.adminmaster')
@section('title','Add Offer')
@section('content')

<style>
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 45px !important;
    }
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

    .cust-tab-cont .tab-pane.active {
        background: none;
    }

</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Offer</h4>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">
        @if(session()->get('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.offer.submit') }}" onsubmit="return submitForm()" id="offerform" method="POST" class="" enctype="multipart/form-data">

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

                            {{-- <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                @foreach($data['languagesData'] as $languagesrow)
                                <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul><br> --}}

                            <div class="tab-content cust-tab-cont">
                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Offer Title<span style="color:red;">*</span>
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" id="offer-input-<?php echo $languagesrow->code; ?>" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="offer_title_{{$languagesrow->code}}" value="{{ old('offer_title_'.$languagesrow->code) }}" placeholder="Enter Offer Title in {{ $languagesrow->name }}" class="offer-name form-control border-0"> -->
                                            <input type="text" id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="offer_title_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('offer_title_'.$languagesrow->code) }}" placeholder="Enter Offer Title in {{ $languagesrow->name }}" class="seoinputname offer-name form-control border-0">
                                        </div>
                                        <span class="text-danger" id='remainingC'></span>
                                        <span class="text-danger">@error('offer_title_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off>
                                        </div>
                                        <span class="seo_error" class="text-danger"></span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="offer_description_{{$languagesrow->code}}" id="offer_description-<?php echo $languagesrow->code; ?>" maxlength="500" onkeyup="charCount(this.value,this.id,500)" placeholder="Enter Offer Description in {{ $languagesrow->name }}" class="des form-control p-0 border-0">{{ old('offer_description_'.$languagesrow->code) }}</textarea>
                                        </div>
                                        <span class="text-danger" id='remainingC-des'></span>
                                        <span class="text-danger">@error('offer_description_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Title</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}" placeholder="Enter Meta Title in English" class="metaTitle form-control border-0" autocomplete=off>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            {{-- <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class="form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea> --}}
                                            <textarea rows="5" name="meta_description_en" id="product-metadesc-en" maxlength="50" placeholder="Enter Meta Description in English" class=" metaDescp form-control border-0">{{ old('meta_description_en') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-12">

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Directory<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <?php $allDirectory = Helper::getAllDirectory(); ?>
                                    <select class="form-control" name="directoryId" id="directoryId">
                                        <option></option>
                                        @foreach($allDirectory as $directoryrow)
                                        <option value="{{ $directoryrow->id }}"> {{ $directoryrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('directoryId'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Product<span style="color:red;">*</span></label><br>
                                <div class="col-md-12" id="productdata">
                                    <select multiple class="form-control" name="productId[]" id="productId"></select>
                                </div>
                                <span class="text-danger">@error('productId'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6" id="offer-banner-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Offer Banner Image<span style="color:red;">*</span> (1920 X 650)px</label>
                                <div class="col-md-12 imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="offer_banner_image" id="banner-image" onchange="loadbannerFile(event)">
                                </div>
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
                        <div class="col-md-6" id="offer-main-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Offer Image<span style="color:red;">*</span> (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="offer_image" id="main-image" onchange="loadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Gallery Image<span style="color:red;">*</span> (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify">

                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>


                                    <input type="hidden" name="array_gallery_listing[]" class="form-control" id="listing-gallery-array" value="">
                                    <input type="file" class="form-control" name="offer_gallery_image[]" id="offergal-image" multiple>
                                </div>
                                <span class="text-danger">max 20 pictures allowed!</span>
                                <span class="text-danger">@error('offer_gallery_image'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4 cus-title">
                            <label class="col-md-12 p-0">Status</label>
                            <div class="col-md-12 ">

                                <select class="form-control" name="offer_status">
                                    <option value="none">None</option>
                                    <option value="upcoming"> Upcoming</option>
                                    <option value="ongoing"> Ongoing </option>
                                    <option value="archive"> Archive </option>
                                    <option value="unpublished"> unpublished</option>
                                    <option value="displayed"> Displayed</option>
                                    <option value="expired"> Expired</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <?php /* ?><div class="row mb-4">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="panel-heading cust-tab-head">
                                <h4 class="box-title">Offer Type</h4>
                            </div>
                        </div>
                    </div><?php */ ?>

                    <?php /* ?> <div id="product_div" class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Enter a Product Ids</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="product_id" value="{{ old('product_id') }}" placeholder="Enter Product id with comma seperated. ex: 23,45,67" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('sku'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div id="dirlink_1" class="row cus-title dir-link">

                        <div class="col-md-5">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Enter a Directory Ids</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="dir_id" value="{{ old('dir_id') }}" placeholder="Enter Directory id with comma seperated. ex: 3,5,7" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('dir_id'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>
                    <?php */ ?>

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
                                    <label class="col-md-12 p-0">Award</label>
                                    <div class="custom-control custom-checkbox product-item">
                                        <input type="checkbox" class="pay pay2" name="offer_discount" value="pricediscount" style="margin-left: 15px;" onclick="singleCheck(this)">
                                        <label class="custom-control-label" for="1">Price Discount </label>
                                    </div>
                                    <div class="custom-control custom-checkbox product-item">
                                        <input type="checkbox" class="pay pay2" name="offer_discount" value="percentagediscount" style="margin-left: 15px;" onclick="singleCheck(this)">
                                        <label class="custom-control-label" for="1">Percentage discount</label>
                                    </div>
                                    <div class="custom-control product-item custom-checkbox">
                                        <input type="checkbox" class="pay pay2" name="offer_discount" value="new" style="margin-left: 15px;" onclick="singleCheck(this)">
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
                                        <input type="text" id="pricedis" name="price_dis" value="" placeholder="Enter Price Discount in amount" class="form-control border-0" onkeypress="validate(event)">
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
                                        <input type="text" id="perdis" name="dis_per" value="" placeholder="Enter Percentage Discount" class="form-control border-0" onkeypress="validate(event)">
                                    </div>
                                    <span class="text-danger">@error('dis_per'){{$message}} @enderror</span>
                                </div>
                            </div>
                        </div>
                    </div>

                     <input type="hidden" name="discount_type" id="discount_type">

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
                                <div class="col-md-12 ">

                                    <select class="form-control" name="parentcategory" id="par-category" onChange="getSubCategory(this.value)">
                                        <option>None</option>
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}"> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Child Category</label>
                                <div class="col-md-12 ">

                                    <select multiple class="form-control" name="childcategory[]" id="child-category" onChange="getSelectedOptions(this)">
                                        <option>None</option>
                                        @foreach($data['productsubcat'] as $childcategorysrow)
                                        <option value="{{ $childcategorysrow->id }}"> {{ $childcategorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tags</label>
                                <div class="col-md-12 ">

                                    <select multiple class="form-control" name="offertags[]" id="offer-tags">
                                        <option>None</option>
                                        @foreach($data['tags'] as $tagsrow)
                                        <option value="{{ $tagsrow->id }}"> {{ $tagsrow->tags }}</option>
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
                    <div class="row mb-4 cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Set Default Duration</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_status" value="yes">
                                    <label class="custom-control-label" for="1">Yes</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_status" value="no" style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="duration-month">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Set Months</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="set_months" value="6_months">
                                    <label class="custom-control-label" for="1">6 months </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="set_months" value="12_months" style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">12 months </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Offer Active Date<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="active-date" name="active_date" value=""  class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('active_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Offer Expiry Date<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="expiry-date" name="expiry_date"  class="form-control  border-0" autocomplete=off>
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

    function singleCheck(clickedCheckbox) {
        var checkboxes = document.querySelectorAll('.pay2');
        checkboxes.forEach(function(checkbox) {
            if (checkbox !== clickedCheckbox) {
                checkbox.checked = false;
            }
        });

        if (clickedCheckbox.checked) {} else { }

    }

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
    $('#duration-month').hide();
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
            var sixMonthsFromNow = new Date(currentdate.setMonth(currentdate.getMonth() + 6));
            var sixday = ("0" + sixMonthsFromNow.getDate()).slice(-2);
            var sixmonth = ("0" + (sixMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftersixmonthdate = sixMonthsFromNow.getFullYear()+"-"+(sixmonth)+"-"+(sixday) ;

            $('#active-date').val(today);
            $('#expiry-date').val(aftersixmonthdate);
        }
        if($(this).val() == "12_months"){
            var twelveMonthsFromNow = new Date(currentdate.setMonth(currentdate.getMonth() + 12));
            var twelveday = ("0" + twelveMonthsFromNow.getDate()).slice(-2);
            var twelvemonth = ("0" + (twelveMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftertwelvemonthdate = twelveMonthsFromNow.getFullYear()+"-"+(twelvemonth)+"-"+(twelveday) ;
            $('#active-date').val(today);
            $('#expiry-date').val(aftertwelvemonthdate);
        }

    });
    $(document).ready(function() {
        $('#directoryId').on('change', function() {
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
            placeholder: "Select option"
            , allowClear: true
        });
    });

</script>

<script>
    $(document).ready(function() {
        // var discountoffer = $('input[name=offer_discount]:checked', '#offerform').val();
        // console.log("Radio selected: ",discountoffer)
        $("#percentagediscount").hide();
        $("#valuediscount").hide();
        $("#pricediscount").hide();
        $("#pricereduction").hide();
        $("#quantitativediscount").hide();

        $("input[name=offer_discount]").change(function() {

            if ($(this).val() == "percentagediscount") {

                $('#discount_type').val('percentage');
                $('#pricedis').val('');
                $("#percentagediscount").show();
                $("#valuediscount").hide();
                $("#pricediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
            } else if ($(this).val() == "pricediscount") {
                $('#discount_type').val('fixed');
                $('#perdis').val('');
                $("#pricediscount").show();
                $("#percentagediscount").hide();
                $("#valuediscount").hide();
                $("#pricereduction").hide();
                $("#quantitativediscount").hide();
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

</script>

<script>
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

</script>

<script>
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
</script>

<script type="text/javascript">
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
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/admin/offerslug'
                    , data: {
                        catname: nameText
                        , directory: 'offer'
                        , _token: '{{csrf_token()}}'
                    }
                    , success: function(result) {
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



    /* Product subcategory and Tags */

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
                    $.each(result.tags, function(key, value) {
                        $("#offer-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });
                }
            });
        }

    }
</script>
@endsection
