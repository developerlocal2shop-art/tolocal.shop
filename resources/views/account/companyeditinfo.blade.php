@extends('layouts.client.vendor')
@section('title','Company information')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php $user = auth()->user(); @endphp
@php
    $getFeatureLinksValue = Helper::getFeatureUsesValue('links');
    $getFeatureAmenitiesValue = Helper::getFeatureUsesValue('amenities');

if (isset($data['basicData'])) {
$basicData = $data['basicData'];
}else{
 $basicData = '';
}
if (isset($data['basicAndTraslationData'])) {
$basicAndTraslationData = $data['basicAndTraslationData'];
}
if (isset($data['locationData'])) {
$locationData = $data['locationData'];
}
if (isset($data['amenityData'])) {

$amenityid = (isset($data['amenityData'][0]->amenities_id) ? $data['amenityData'][0]->amenities_id : "");
$amenityData = explode(',', $amenityid);
}

if (isset($data['mediaData'])) {
$mediaData = $data['mediaData'];
}
if (isset($data['seoAndTraslationData'])) {
$seoAndTraslationData = $data['seoAndTraslationData'];
}
if (isset($data['directoryTypeData'])) {
$directoryTypeData = $data['directoryTypeData'];
}
if (isset($data['directoryTypeDatainreverse'])) {
$directoryTypeDatainreverse = $data['directoryTypeDatainreverse'];
}
if (isset($data['contactData'])) {
$contactData = $data['contactData'];
}
if (isset($data['scheduleData'])) {
$scheduleData = $data['scheduleData'];
}
if (isset($data['websitelink'])) {
$websitelink = $data['websitelink'];
}
if (isset($data['assortlink'])) {
$assortlink = $data['assortlink'];
}
if (isset($data['amenityData'])) {
$amenity = $data['amenityData'];
}
if (isset($data['amenityselectedDataAccordingToAmenityCat'])) {
$amenityselectedDataAccordingToAmenityCat = $data['amenityselectedDataAccordingToAmenityCat'];
}
if (isset($data['instruction'])) {
$instruction = $data['instruction'];
}
@endphp
<style>
    div#directory_tabscon {
        min-height: 500px !important;
    }
    div#claim {
        min-height: 500px !important;
    }
    .modal-content {
            background-color: #fff;
    }
    .margin_60_35 {
        padding-top: 10px;
        padding-bottom: 0px;
    }
    input#otp {
        margin-bottom: 0px !important;
    }
    .gallerytype_pdf {
        margin: 15px 10px 15px 0px;
        border: 4px solid #1f3863;
        padding: 1px;
    }
    button#listsubmit-dirtype {
        margin-top: 24px;
    }
    .custom-checkbox .custom-control-label {
        margin: 0;
    }
    select#publicdaystemplateselect {
        margin: 0px -2px;
    }
    .container, .container-lg, .container-md, .container-sm, .container-xlg, .container-xl {
        max-width: 1320px;
    }
    .custom-control.custom-checkbox .custom-control-label i{margin-right:0px!important}
    input#checkAll {
        margin: 4px 5px 23px 12px;
    }
    input.cb-element {
        position: absolute;
        margin: -3px 0px 0px -3px;
        z-index: 20000;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        background-color: #edebeb;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <x-client.company_account_menu />
    </div>
</div>
<!-- /sub_header -->
<!-- Gallery Image Modal -->
{{-- <div class="modal fade"  id="allgalleryimages_model" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Gallery Images</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview_b" id="listgallerypreview_b">
                        @php
                        $mediadirct_id = isset($mediaData[0]->directory_id)? $mediaData[0]->directory_id : "";
                        $imagenamegal = App\Http\Controllers\Admin\Directory\DirectoryController::getallgalImage($mediadirct_id);
                        @endphp
                        <?php
                        if (!empty($imagenamegal)) { $last_position = end($imagenamegal);?>
                        @foreach($imagenamegal as $imagenamerow)
                        <div class="thumbnail" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                            <!-- @if($imagenamerow->name_thumbnail)
                                <img src="{{env('IMAGE_PATH').'files/directory/'.$imagenamerow->name_thumbnail }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                            @else
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img" alt="directory-img" width="70px" height="70px" class="previewBlocks" />
                            @endif -->

                            @if($imagenamerow->name_thumbnail)
                                <div class="thumbnail" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                                    <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                                    <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                                </div>
                            @else
                                <div class="thumbnail" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                                    <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                                </div>
                            @endif

                            <!-- <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span> -->
                        </div>
                        @endforeach

                        <?php } ?>

                        <input type="hidden" id="lastpositiongallValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade listing_detail_gallery" id="type_galleryModal" role="dialog"  >
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Gallery Images</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview2" >

                        <div class="bulk_delete row" style="display: none;">
                            <input type="checkbox" id="checkAll" class="checkall">All
                            <a class="btn btn-danger btn-sm" id="deleteAllSelectedProducts"  style="margin:0px 10px 10px 15px;">
                                Delete In Bulk
                            </a>
                            <hr>
                        </div>

                        <div class="row type_gallery" id="type_gallery_list">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade listing_detail_gallery" id="type_pdfModal" role="dialog"  >
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Pdf files </h5>
                <a href="" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview2" >

                        <div class="bulk_delete row" style="display: none;">
                            <input style="margin: 0px 6px 20px 4px;" type="checkbox" id="checkAll2" class="checkall"> All
                            <a class="btn btn-danger btn-sm" id="deleteAllSelectedPdf"  style="margin:0px 10px 10px 15px;">
                                Delete In Bulk
                            </a>
                            <hr>
                        </div>

                        <div class="row type_gallery" id="type_pdf_list">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main>
    <div class="container margin_60_35">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body card-body-mobile-alignment">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title helpicon_alignment" style="display: flex;">Company's information
                            @if(!empty($instruction->message))
                            <div id="helpicon">
                                <span id="helpiconText" style="display: none;">{{$instruction->message}}</span>
                                <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                            </div>
                            @endif
                            </h3>
                        </div>
                        <div class="col-md-6">
                        @if (Helper::getFeatureUses('schedule-all') == true)
                            <a href="{{ route('company.addtemplate') }}" class="btn btn-primary d-md-block add-schedule-temp-btn pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
                            Add Custom Schedule Template
                            </a>
                        @endif
                        </div>
                        <div class="col-md-12">
                            <p class="box-title">
                            ALL TEXT INSERTED IN ENGLISH ONLY, all translations done automatically from English only. By using Google translate you easily receive correct text and can insert in each block.
                            </p>
                        </div>
                    </div>
                        <form action="{{ route('updatecompanyinformation') }}" method="POST" class="" enctype="multipart/form-data" id="editsection" onsubmit="return submitForm()">
                            {{ csrf_field() }}
                            <input type="hidden" name="directory_services" id="directory_services_hidden">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <ul class="nav nav-tabs dir-tabs cust-nav-tab notranslate" id="directory_tabs" role="tablist">
                                        <?php
                                            if (!function_exists('active')) {
                                                function active($currect_page) {
                                                    $url = request()->get('target');
                                                    if($currect_page == $url){
                                                        echo 'active';
                                                    }
                                                }
                                            }
                                        ?>
                                        @php
                                            $fieldsWithErrors = ['name_en', 'email', 'phone','phone_number','trademarkcategory'];
                                            $fieldsWithErrors_seo = ['category', 'sub_category'];
                                            $fieldsWithErrors_media = ['listing_thumb', 'listing_cover','listing_gallery'];
                                        @endphp
                                        @if (Helper::getFeatureUses('basic') == true)
                                            <li class="nav-item">
                                                <a @if(array_intersect($fieldsWithErrors, array_keys($errors->getMessages()))) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif class="nav-link <?php if(request()->get('target')!=""){ active('basic'); }else{ echo "active";} ?>" data-toggle="tab" href="#basic" role="tab">
                                                    <span>Basic</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('location') == true)
                                            <li class="nav-item">
                                                <a @if($errors->has('address')) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif class="nav-link <?php active('location');?>" data-toggle="tab" href="#location" role="tab">
                                                    <span>Location</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('type') == true)
                                            <li class="nav-item">
                                                <a class="nav-link <?php active('type');?>" data-toggle="tab" href="#dirtype" role="tab">
                                                    <span>Type</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('amenities') == true)
                                            <li class="nav-item">
                                                <a class="nav-link <?php active('amenities');?>" data-toggle="tab" href="#amenities" role="tab">
                                                    <span>Amenities</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('media') == true)
                                            <li class="nav-item">
                                                <a @if(array_intersect($fieldsWithErrors_media, array_keys($errors->getMessages()))) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif  class="nav-link <?php active('media');?>" data-toggle="tab" href="#media" role="tab">
                                                    <span>Media</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('seo') == true)
                                            <li class="nav-item">
                                                <a class="nav-link notranslate <?php active('seo');?>" data-toggle="tab" href="#seo" role="tab">
                                                    <span>SEO</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('schedule-all') == true)
                                            <li class="nav-item">
                                                <a class="nav-link <?php active('schedule');?>" data-toggle="tab" href="#schedule" role="tab">
                                                    <span>Schedule</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('links') == true)
                                            <li class="nav-item">
                                                <a class="nav-link <?php active('links');?>" data-toggle="tab" href="#links" role="tab">
                                                    <span>Links</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Helper::getFeatureUses('claim') == true)
                                            <li class="nav-item">
                                                <a class="nav-link <?php active('claim');?>" data-toggle="tab" href="#claim" role="tab">
                                                    <span>Claim</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="nav-item">
                                            <a class="nav-link <?php active('services');?>" data-toggle="tab" href="#services" role="tab">
                                                <span>Services</span>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#finish" role="tab">
                                                <span>Submit</span>
                                            </a>
                                        </li> -->
                                    </ul>
                                    <div class="tab-content cust-tab-cont" id="directory_tabscon">
                                        <div class="tab-pane <?php if(request()->get('target')!=""){ active('basic'); }else{ echo "active";} ?> notranslate" id="basic" role="tabpanel">
                                            <ul class="nav nav-tabs" role="tablist">
                                                @foreach($data['languagesData'] as $languagesrow)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                                            <span>{{ $languagesrow->name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content cust-tab-cont">
                                                @foreach($basicAndTraslationData as $basiclanguagesrow)
                                                <?php $languagecodeBasicData = Helper::getLanguagesDataById($basiclanguagesrow->language_id); ?>
                                                <div class="tab-pane {{ $basiclanguagesrow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodeBasicData->name }}" role="tabpanel">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: inline-flex;">Name<span class="notranslate" style="color:red;">*</span>
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">Use Google translate and then copy in the translation.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                            <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <!-- <input type="text" name="name_{{$languagecodeBasicData->code}}" id="cat-{{$languagecodeBasicData->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" data-id="{{$languagecodeBasicData->code}}" value="{{ old('name_'.$languagecodeBasicData->code, $basiclanguagesrow->title ?? '') }}" placeholder="Enter Directory Name in {{ $languagecodeBasicData->name }}" class="directorynametitle seoinputname form-control p-0 border-0" autocomplete=off> -->
                                                            <input type="text" name="name_{{$languagecodeBasicData->code}}" id="cat-{{$languagecodeBasicData->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" data-id="{{$languagecodeBasicData->code}}" value="{{ old('name_'.$languagecodeBasicData->code, $basiclanguagesrow->title ?? '') }}" placeholder="Enter Directory Name" class="directorynametitle seoinputname form-control p-0 notranslate border-0" autocomplete=off>
                                                            <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code}}" value="{{ $basiclanguagesrow->id }}" class="form-control p-0 border-0">
                                                        </div>
                                                        <span class="text-danger">@error('name_en'){{$message}} @enderror</span>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0 notranslate">SEO</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <!-- <input type="text" id="seo-{{$languagecodeBasicData->code}}" name="seo_{{$languagecodeBasicData->code}}" value="{{ $basiclanguagesrow->seo_url }}" placeholder="Directory SEO in {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0" readonly> -->
                                                            <input type="text" id="seo-{{$languagecodeBasicData->code}}" name="seo_{{$languagecodeBasicData->code}}" value="{{ $basicData->slug??'' }}" placeholder="Directory SEO in {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: inline-flex;">Description<span style="color:red;">*</span>
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">All text inserted in English enabling best translation.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <textarea rows="5" name="description_{{$languagecodeBasicData->code}}" id="description-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="directorydesc form-control p-0 border-0">{{ old('description_'.$languagecodeBasicData->code, $basiclanguagesrow->description ?? '') }}</textarea>
                                                        </div>
                                                        <span class="text-danger">@error('description_en'){{$message}} @enderror</span>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: inline-flex;">We Offer
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">All text inserted in English enabling best translation.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <!-- <textarea rows="5" name="weoffer_{{$languagecodeBasicData->code}}" id="weoffer-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter We Offer in {{ $languagecodeBasicData->name }}">{{ old('weoffer_'.$languagecodeBasicData->code, $basiclanguagesrow->we_offer ?? '') }}</textarea> -->
                                                            <textarea rows="5" name="weoffer_{{$languagecodeBasicData->code}}" id="weoffer-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter We Offer">{{ old('weoffer_'.$languagecodeBasicData->code, $basiclanguagesrow->we_offer ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: inline-flex;">About Us
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">All text inserted in English enabling best translation.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <!-- <textarea rows="5" name="aboutus_{{$languagecodeBasicData->code}}" id="aboutus-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter About Us in {{ $languagecodeBasicData->name }}">{{ old('aboutus_'.$languagecodeBasicData->code, $basiclanguagesrow->about_us ?? '') }}</textarea> -->
                                                            <textarea rows="5" name="aboutus_{{$languagecodeBasicData->code}}" id="aboutus-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter About Us">{{ old('aboutus_'.$languagecodeBasicData->code, $basiclanguagesrow->about_us ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 infoicon-featurestype">

                                                        <label class="col-md-12 p-0" style="display: inline-flex;">Featured Type
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">Select <b>Super Featured</b>, If you want this directory to show in "Popular Companies" list on Home page.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <select class="form-control" name="featuretype" id="featuretype">
                                                                <?php /* ?><option value="none" {{ "none"==$basicData->feature_type ? 'selected' : '' }}>None</option><?php */ ?>
                                                                <option value="featured" {{ "featured"==$basicData->feature_type ? 'selected' : '' }}> Featured</option>
                                                                <option value="superfeatured" {{ "superfeatured"==$basicData->feature_type ? 'selected' : '' }}>Super Featured(Popular)
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <span class="text-danger">@error('featuretype'){{$message}} @enderror</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Google analytics id</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="google_ana_id" value="{{ old('$google_ana_id', $basicData->google_analytics_id ?? '') }}" placeholder="Enter Google analytics id" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('google_ana_id'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="createdby" id="createdby" class="form-control" value="{{auth()->user()->id;}}">

                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Company Header's Color</label>
                                                        <div id="cp5c" class="col-md-12 border-bottom p-0 input-group">
                                                        <input type="color" class="form-control input-lg" value="{{ $basicData->company_color }}" name="company_color" id="company_color">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                                          </span>
                                                        </div>
                                                    </div>
                                                    <span class="text-danger">@error('company_logo'){{$message}} @enderror</span>
                                                </div>
                                                <div class="col-md-1">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Company Logo (size: 350 * 100)</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                        <input type="file" class="form-control" name="company_logo" id="company_logo">
                                                        </div>
                                                    </div>
                                                    <span class="text-danger">@error('company_logo'){{$message}} @enderror</span>
                                                </div>
                                                <div class="col-md-3">
                                                    @if (!empty($basicData->company_logo))
                                                    <img src="{{env('IMAGE_PATH').'/'.$basicData->company_logo }}" width="130px">
                                                    @else
                                                    <img src="{{ asset('assets/images/comp.jpg') }}" width="130px" alt="" >
                                                    @endif
                                                </div>
                                            </div>

                                            <?php //echo "<pre>"; print_r($data['categoryData']);?>

                                            {{-- <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Trademark</label>
                                                <div class="col-md-12 ">

                                                    <select multiple class="form-control select2 notranslate" name="trademark[]" id="par-trademark">
                                                        <option></option>
                                                        @foreach($data['trademarks'] as $trademarksrow)
                                                        <option value="{{ $trademarksrow->id }}" <?php //if(in_array($trademarksrow->id,$data['trademarkData'])){echo "selected";} ?>> {{ $trademarksrow->name }}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">Email<span class="notranslate" style="color:red;">*</span>(To change OTP verification)</label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <input type="email" name="email" id="email" value="{{ old('email', isset($contactData[0]->email)?$contactData[0]->email:'') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1" style="display: none;" id="otp_div">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">OTP</label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <input type="text"  name="otp" id="otp" placeholder="Otp" class="form-control p-0 border-0" style="width:100%;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">.</label>
                                                            <div class="col-md-12 p-0">
                                                                <a id="e_verification" id="verifyOtp alert alert-info" style="margin: 8px 5px 5px 0px !important; cursor: pointer; background: #d1ecf1; position: absolute; padding: 5px 10px; border-radius: 15px;">
                                                                    Send Verification
                                                                </a>
                                                                <a id="e_veifyotp" class="e_verifyOtp alert alert-info" style="margin: 8px 5px 5px 0px !important; cursor: pointer; background: #d1ecf1; position: absolute; padding: 5px 10px; border-radius: 15px; display:none;">
                                                                    Verify OTP
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">Name<span class="notranslate" style="color:red;">*</span></label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <input type="text" name="name" value="{{ old('name', isset($contactData[0]->name)?$contactData[0]->name:'') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>
                                                            </div>
                                                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0" style="display: inline-flex;">Phone Number<span style="color:red;">*</span>
                                                                <div id="infotooltip">
                                                                    <span id="infotooltipText">Company phone, displayed for contacts to the company. Enter phone number only don't enter country code.</span>
                                                                    <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                                </div>
                                                            </label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <input type="text" name="phone_number" value="{{ old('phone_number', isset($contactData[0]->phone)?$contactData[0]->phone:'') }}" placeholder="Enter Phone Number" class="form-control p-0 border-0" autocomplete=off>
                                                            </div>
                                                            <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr style="height:2px;color:#8898aa">
                                                <div class="form-group ">
                                                    <?php $dataselectedcategory = isset($data['trademarkDatacate'][0]['trademarkcat_id'])?$data['trademarkDatacate'][0]['trademarkcat_id']:''; ?>
                                                    <label class="col-md-12 p-0">Trademark Category<span class="notranslate" style="color:red;">*</span></label>
                                                    <div class="col-md-12 ">
                                                        <select class="form-control notranslate" name="trademarkcategory" id="trademark-category" onChange="getTrademarkOptions(this)" style="width:100%">
                                                            <option value="">Select Trademark Category</option>
                                                            @foreach($data['trademarkcategory'] as $categorysrow)
                                                            <option value="{{ $categorysrow['id'] }}" <?php if($categorysrow['id']==$dataselectedcategory){echo "selected";} ?>> {{ $categorysrow['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <span class="text-danger">@error('trademarkcategory'){{$message}} @enderror</span>
                                                </div>
                                                <div class="form-group mb-4">

                                                    <label class="col-md-12 p-0">Trademark (You may enter own and missing Trademarks from Business Package)</label>
                                                    <div class="col-md-12 ">
                                                        <select multiple class="form-control select2 notranslate" name="trademark[]" id="par-trademark" style="width:100%">
                                                            <option></option>
                                                            @foreach($data['trademarkselectedDataAccordingToTrademarkCat'] as $trademarksrow)
                                                            <option value="{{ $trademarksrow->id }}" <?php if(in_array($trademarksrow->id,$data['trademarkData'])){echo "selected";} ?>> {{ $trademarksrow->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">PayPal email<span class="notranslate" style="color:red;">*</span></label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="paypal_email" value="{{ old('paypal_email', Auth::user()->paypal_email??'') }}" placeholder="Enter PayPal Email" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('paypal_email'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: inline-flex;">
                                                            PayPal merchant ID (optional)
                                                        </label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="paypal_merchant_id" value="{{ old('paypal_merchant_id', Auth::user()->paypal_merchant_id??'' ) }}" placeholder="Enter PayPal Merchant ID" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php /* ?>
                                                <div class="form-group mb-4">
                                                    {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" name="popular" id="" value="1" {{ $basicData->popular=='1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="1" style="display: inline-flex;"> Popular
                                                            <div id="infotooltip">
                                                                <span id="infotooltipText">Please check this, If you want this directory to show in the list of popular.</span>
                                                                <span><i class="fas fa-info-circle" style="color:#3c3c3e; padding-left: 5px;"></i></span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <span class="text-danger">@error('popular'){{$message}} @enderror</span>
                                                </div>
                                                <?php */ ?>

                                                @if ($user->hasPermission('update') == true)
                                                <button type="submit" id="listsubmit-basic" class="btn btn-primary listsubmit">Save Changes</button>
                                                @endif
                                        </div>

                                        <div class="tab-pane <?php active('location');?>" id="location" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Address</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input id="pac-input" class="form-control" type="text" autocomplete=off placeholder="address" name="address" value="{{ old('address',$locationData[0]->address??'') }}" />
                                                            {{-- <textarea rows="5" name="address" class="form-control p-0 border-0">{{ $locationData[0]->address }}</textarea> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Country</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="country_id" readonly id="country_id" value="{{ old('country_id',$locationData[0]->country_id??'') }}" placeholder="Country" class="form-control p-0 border-0" autocomplete=off>

                                                            {{-- <select class="form-control" name="country_id" id="country-dropdown">
                                                                <option value=""> Select Country </option>
                                                                @foreach($data['country'] as $countryrow)
                                                                <option @if($countryrow->id==old('country_id')) selected @endif value="{{ $countryrow->id }}" {{ $countryrow->id == $locationData[0]->country_id ? 'selected' : '' }}> {{ $countryrow->name }}</option>
                                                            @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">State</label>
                                                        <div class="col-md-12 border-bottom p-0">

                                                            <input type="text" name="state_id" readonly id="state_id" value="{{ $locationData[0]->state_id??'' }}" placeholder="State" class="form-control p-0 border-0" autocomplete=off>

                                                            {{-- <select class="form-control" name="state_id" id="state-dropdown"></select> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">city</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="city_id" readonly id="city" value="{{ old('city',$locationData[0]->city_name??'') }}" placeholder="city" class="form-control p-0 border-0" autocomplete=off>

                                                            {{-- <input type="hidden" id="cityid" value="{{ $locationData[0]->city_id }}"> --}}
                                                            {{-- <select class="form-control" name="city_id" id="city-dropdown"></select> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group mb-12">
                                                        <label class="col-md-12 p-0">Place</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="place" id="place" value="{{ old('place',$locationData[0]->place??'') }}" placeholder="Place" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-12">
                                                        <label class="col-md-12 p-0">Zipcode</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode',$locationData[0]->zipcode??'') }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="display:none;">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Latitude</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="latitude" value="{{ old('latitude', $locationData[0]->latitude??'') }}" placeholder="Provide latitude for getting the exact result" class="form-control p-0 border-0" id="latitude" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="display:none;">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Longitude</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="longitude" value="{{ old('longitude', $locationData[0]->longitude??'') }}" placeholder="Provide longitude for getting the exact result" class="form-control p-0 border-0" id="longitude" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="map" class="map-full map-layout single-listing-map" style="height: 300px; z-index: 50;">
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-location" class="btn btn-primary listsubmit">Save Changes</button>
                                        @endif
                                        </div>

                                        <div class="tab-pane <?php active('amenities');?>" id="amenities" role="tabpanel">

                                            <div class="row">
                                                <input type="hidden" name="links_count" value="<?php echo ($getFeatureAmenitiesValue); ?>" id="amenitycatcount" />
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Amenity Category (You can select multiple categories!)</label>
                                                        <div class="col-md-12 ">

                                                            <select multiple class="form-control select2" name="amenitycat_id[]" id="amenitycat-dropdown" onChange="getSelectedAmenityOptions(this)" style="width:100%">
                                                                <option value="">Select Amenity Category </option>

                                                                @foreach($data['amenityCategoryData'] as $row)
                                                                <option value="{{ $row['id'] }}" <?php if(in_array($row['id'],$data['dataamenityCatIdData'])){echo "selected";} ?>> {{ $row['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row amenitiesdata">
                                                <?php //echo "<pre>"; print_r($amenitygetallselectedDataAccordingToAmenityCat); ?>
                                                @if(!empty($amenitygetallselectedDataAccordingToAmenityCat))
                                                @foreach($amenitygetallselectedDataAccordingToAmenityCat as $key => $amenityrow)
                                                <div class="amenitycategories_name"><span><?php echo $key; ?></span></div>
                                                    @foreach($amenityrow as $ameyrow)
                                                        <div class="col-md-3 p-10">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="form-check-input" name="amenities[]" id="{{ $ameyrow->id }}" value="{{ $ameyrow->id }}" <?php if (in_array($ameyrow->id, $data['getselectetedAmenityData'])) { echo 'checked'; } ?>>
                                                                <label class="custom-control-label" for="1"><i class="{{ $ameyrow->icon_class }}" style="color: #636363 !important;"></i> {{ $ameyrow->title }} </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="p-0">Edit Your Own Amenity</label>
                                                        @php
                                                        $directory_amenity = [];
                                                        @endphp
                                                        @foreach ($data['directory_amenity'] as $val)
                                                        @php $directory_amenity[] = $val->title @endphp
                                                        @endforeach
                                                        <input type="text" name="directory_own_amenity" data-role="tagsinput" id="tags_name" value="{{ old('directory_amenity', implode(',',$directory_amenity) ) }}" placeholder="Add Your Own Amenity" class="form-control p-0 border-0">

                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-amenity" class="btn btn-primary listsubmit">Save Changes</button>
                                            @endif
                                        </div>

                                        <div class="tab-pane cus-title <?php active('media');?>" id="media" role="tabpanel">

                                            <div class="loader" id="loader"></div>
                                            <div class="AJloader" id="AJloader"></div>
                                            <?php $valuePayable = isset($mediaData[0]->payable) ? $mediaData[0]->payable : 0; ?>
                                            <div class="row mb-4">

                                                 <div class="col-md-3">
                                                    <div class="custom-control custom-radio" style="opacity: 0;">
                                                        <input type="radio" class="pay" name="payimage" value="payable" id="pay" checked>

                                                        {{-- <input type="radio" class="pay" name="payimage" value="payable" id="pay" {{ $valuePayable==1 ? 'checked' : '' }}> --}}
                                                        <label class="custom-control-label" for="1">Payable </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-3">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="pay" name="payimage" value="nonpayable" id="nonpay" {{ $valuePayable==0 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="2">Non Payable </label>
                                                    </div>
                                                </div> --}}
                                            </div>
                                            @php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('listing-thumbnail-and-listing-cover');
                                                $getFeatureGalleryValue = Helper::getFeatureUsesValue('media');
                                            @endphp
                                            <div class="row">
                                                <div class="col-md-6 mobile-alignment-infoicon" id="multi-imagelistingthumbnail">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0" style="display: flex;">Company Thumbnail (<span style="color:red">800 X 800</span>)px
                                                            <div id="tooltip">
                                                                <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                                                <span><i class="fas fa-info-circle" ></i></span>
                                                            </div>

                                                        </label>
                                                        <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Image upload at a time - 1</p>
                                                        <div class="col-md-12 imageuploadify">

                                                            <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                            <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                            <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>


                                                            <input type="hidden" name="array_thumb_listing[]" class="form-control" id="listing-thumb-array" value="">
                                                            <input type="hidden" name="array_thumb_listing_new[]" class="form-control" id="listing-thumb-array-new" value="">
                                                            <input type="hidden" name="thumb_hidden_img" class="form-control" id="thumb_hidden_img" value="">

                                                            <input type="file" class="form-control" name="listing_thumb[]" id="multi-thumbnail-img" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 infoicon-listingcover-align" id="multi-listingCover">
                                                    <div class="form-group mb-4">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="p-0 mb-0" style="display: flex;">Selected Company Cover Images (<span style="color:red">960 X 450</span>)px
                                                                <div id="tooltip">
                                                                    <span id="tooltipText">Best result with (960 X 450)px - other dimensions are acceptable but visualization can be poor.</span>
                                                                    <span><i class="fas fa-info-circle" ></i></span>
                                                                </div>
                                                            </label>
                                                            @if ($user->hasPermission('update') == true)
                                                            <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#addCompanyCoverImageModal" style="white-space: nowrap;">
                                                                Add Image with text
                                                            </button>
                                                            @endif
                                                        </div>
                                                        <p>Note : <i class="fas fa-eye" aria-hidden="true"></i>
                                                            Image upload at a time - 1 and Max limit - {{ $getFeatureUsesValue??'' }} Pending on my package. </p>
                                                        <div class="col-md-12 imageuploadify">
                                                            <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                            <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                            <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>
                                                            <input type="hidden" name="array_cover_listing[]" class="form-control" id="listing-cover-array" value="">
                                                            <input type="hidden" name="array_cover_images" class="form-control" id="listing-cover-arrayimage">

                                                            <input type="file" class="form-control" name="listing_cover[]" multiple id="cover-img">
                                                            <span class="text-danger">@error('listing_cover'){{$message}} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 previewBlocks">
                                                        @php
                                                        $mediadirct_id = isset($mediaData[0]->directory_id)? $mediaData[0]->directory_id : "";
                                                        $imagename_thumb = App\Http\Controllers\Admin\Directory\DirectoryController::get_thumbImage($mediadirct_id);
                                                        @endphp
                                                        <label class="col-md-12 p-0">Preview</label>
                                                        <div class="col-md-12 thumbnailimgPreview" id="thumbnailimgPreview">
                                                            <?php $name_thumb = isset($mediaData[0]->name_thumbnail)? $mediaData[0]->name_thumbnail :"";
                                                            if (!empty($imagename_thumb)) { $last_position = end($imagename_thumb); ?>
                                                            @foreach($imagename_thumb as $imagenamerow)
                                                            <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-thumbimages="{{ $imagenamerow->name_thumbnail }}">
                                                                <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="thumb">
                                                                <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                                            </div>
                                                            @endforeach

                                                            <?php } else{ ?>
                                                            {{-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="" class="previewBlocksNew" alt="media-img" width="70px" height="70px"/> --}}
                                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" class="previewBlocks" alt="media-img" width="70px" height="70px" />
                                                            <?php } ?>
                                                            <input type="hidden" id="lastpositionthumbValue" data-payble="<?php echo $valuePayable ?>" value="<?php echo !empty($last_position->position)?$last_position->position:'-1'  ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 previewBlocks">
                                                        <label class="col-md-12 p-0">Preview</label>
                                                        <div class="col-md-12 imgPreview" id="imgPreview">
                                                            @php
                                                            $mediadirct_id = isset($mediaData[0]->directory_id)? $mediaData[0]->directory_id : "";
                                                            $imagenamecover = App\Http\Controllers\Admin\Directory\DirectoryController::getCoverImage($mediadirct_id);
                                                            @endphp

                                                            <?php
                                                            if (!empty($imagenamecover)){
                                                                $last_position = end($imagenamecover);
                                                            ?>
                                                            @foreach($imagenamecover as $imagenamerow)

                                                            <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-coverimages="{{ $imagenamerow->name_thumbnail }}">
                                                                <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="cover">
                                                                @if($imagenamerow->title || $imagenamerow->description)
                                                                <div class="image-metadata-overlay">
                                                                    @if($imagenamerow->title)<strong>{{ $imagenamerow->title }}</strong>@endif
                                                                    @if($imagenamerow->description)<span>{{ $imagenamerow->description }}</span>@endif
                                                                </div>
                                                                @endif
                                                                <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                                            </div>
                                                            @endforeach

                                                            <?php } else{ ?>

                                                            <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-cover-img" alt="directory-img" width="70px" height="70px" class="previewBlocks" /> -->
                                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-cover-img" class="previewBlocks" alt="media-img" width="70px" height="70px" />
                                                            <?php } ?>

                                                            <input type="hidden" id="lastpositioncoverValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Video Provider</label>
                                                        <div class="col-md-12 ">
                                                            <?php $videoplayer = isset($mediaData[0]->video_provider) ? $mediaData[0]->video_provider : ""; ?>
                                                            <select class="form-control" name="video_provider" id="video-provider">
                                                                <option value="youtube" {{ "youtube"==$videoplayer ? 'selected' : '' }}>YouTube</option>
                                                                <option value="vimeo" {{ "vimeo"==$videoplayer ? 'selected' : '' }}>Vimeo</option>
                                                                <option value="html" {{ "html"==$videoplayer ? 'selected' : '' }}>HTML5</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Video URL</label>
                                                        <div class="col-md-12 ">
                                                            <input type="text" class="form-control" name="videourl" value="{{ old('videourl', isset($mediaData[0]->video_url))? $mediaData[0]->video_url:'' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">

                                                <div class="col-md-12 infoicon-multi-gallery-align" id="multi-gallery">
                                                    @php
                                                        $imagenamegalleryChunk = App\Http\Controllers\Admin\Directory\DirectoryController::getgalImageChunk($mediadirct_id);

                                                        $imagenamegall = App\Http\Controllers\Admin\Directory\DirectoryController::getallgalImage($mediadirct_id);
                                                        if($imagenamegall){
                                                            $count_imagenamegallery = count($imagenamegall);
                                                        }else{
                                                            $count_imagenamegallery = 0;
                                                        }

                                                    @endphp
                                                    <div class="form-group mb-4">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <label class="p-0 mb-0" style="display: flex;">Company gallery images (<span style="color:red">1000 X 1000 </span>)px
                                                                <div id="tooltip">
                                                                    <span id="tooltipText">Best result with (1000 X 1000 )px - other dimensions are acceptable but visualization can be poor.</span>
                                                                    <span><i class="fas fa-info-circle" ></i></span>
                                                                </div>
                                                            </label>
                                                            @if ($user->hasPermission('update') == true)
                                                            <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#addCompanyGalleryImageTextModal" style="white-space: nowrap;">Add Image with text</button>
                                                            @endif
                                                        </div>
                                                         <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Image upload at a time - <b>20</b> and Max limit - <b>{{ $getFeatureGalleryValue??'' }}</b> for my package. Now <b>{{ $count_imagenamegallery }}</b> images uploaded</p>

                                                        <div class="col-md-12 imageuploadify">

                                                            <div class="imageuploadify-overlay3" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                            <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                            <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload<br>You have to upload multiple images at a time</span>
                                                            <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                            <input type="hidden" name="array_gallery_listing[]" class="form-control" id="listing-gallery-array" value="">
                                                            <input type="hidden" name="array_gallery_images" class="form-control" id="listing-gallery-arrayimage">
                                                            <input type="file" id="gallist" class="form-control" name="listing_gallery[]" multiple>
                                                        </div>

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4 previewBlocks">
                                                        @php
                                                            $imagenamegal = App\Http\Controllers\Admin\Directory\DirectoryController::getgalImage($mediadirct_id);
                                                        @endphp
                                                        <?php if (!empty($imagenamegal)) { ?>
                                                        <div class="delete_all">
                                                            <div class="bulk_delete row" style="">
                                                                <input type="checkbox" id="checkAll_gal" class="checkall_gal" style="margin: 8px 5px 10px 5px;">All
                                                                <a class="btn btn-danger btn-sm" id="deleteAllSelectedGal" style="margin: 0px 0px 0px 15px;width: auto;">
                                                                    Delete In Bulk
                                                                </a> <hr>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <label class="col-md-12 p-0">Preview</label>

                                                        <div class="col-md-12 listgallerypreview show-more-height" id="listgallerypreview">
                                                            <?php
                                                            if (!empty($imagenamegalleryChunk)) { $last_position = end($imagenamegalleryChunk);
                                                                foreach($imagenamegalleryChunk as $imageDirectory){
                                                                    foreach($imageDirectory as $imagenamerow){
                                                            ?>
                                                            @if($imagenamerow->name_thumbnail)
                                                                <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                                                                    <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                                                                    @if($imagenamerow->title || $imagenamerow->description)
                                                                    <div class="image-metadata-overlay">
                                                                        @if($imagenamerow->title)<strong>{{ $imagenamerow->title }}</strong>@endif
                                                                        @if($imagenamerow->description)<span>{{ $imagenamerow->description }}</span>@endif
                                                                    </div>
                                                                    @endif
                                                                    <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                                                                    <input type="checkbox" name="gal_ids" class="cb-element" value="{{ $imagenamerow->id }}">
                                                                </div>
                                                            @else
                                                                <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                                                                    <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                                                                    <input type="checkbox" name="gal_ids" class="cb-element" value="{{ $imagenamerow->id }}">
                                                                </div>
                                                            @endif

                                                            <?php } }  } else{ ?>

                                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img" alt="directory-img" width="70px" height="70px" class="previewBlocks" />
                                                            <?php } ?>
                                                            <input type="hidden" id="lastpositiongallValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />


                                                        </div>


                                                        <?php /* ?>
                                                        @php
                                                        $imagenamegal = App\Http\Controllers\Admin\Directory\DirectoryController::getallgalImage($mediadirct_id);
                                                        @endphp
                                                        @if (count($imagenamegal) > 12)
                                                            {{-- <div class="see_all" data-toggle="modal" data-target="#allgalleryimages_model">See All</div> --}}
                                                            <div class="see_all show_more" id="gallery_all">More</div>
                                                        @endif
                                                        <?php */?>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->hasPermission('update') == true)
                                            <div class="row">
                                                <div class="col-md-12">
                                                <p><strong>Note : </strong><i class="fas fa-eye" aria-hidden="true"></i> (Save after each upload)</p>
                                                    <button type="submit" id="listsubmit-media" class="btn btn-primary listsubmit" style="width: 300px;">Save Changes</button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="tab-pane <?php active('seo');?> notranslate" id="seo" role="tabpanel">
                                            <div class="">
                                                <?php /* ?><ul class="nav nav-tabs" role="tablist">
                                                    @foreach($data['languagesData'] as $languagesrow)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="<?php echo "#" . $languagesrow->name . "_" . $languagesrow->id; ?>" role="tab">
                                                            <span>{{ $languagesrow->name }}</span>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul><?php */ ?>
                                                <div class="tab-content cust-tab-cont">
                                                    @foreach($seoAndTraslationData as $seoLanguagedatarow)
                                                    <?php $languagecodenameData = Helper::getLanguagesDataById($seoLanguagedatarow->language_id); ?>
                                                    <div class="tab-pane {{ $seoLanguagedatarow->language_id == 1 ? 'active' : '' }}" id="<?php echo $languagecodenameData->name . "_" . $languagecodenameData->id; ?>" role="tabpanel">
                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">Meta keywords</label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <input type="text" name="meta_keywords_{{$languagecodenameData->code}}" value="{{ old('meta_keywords_'.$languagecodenameData->code, $seoLanguagedatarow->meta_keyword) }}" placeholder="Enter Meta Keywords Name in {{ $languagecodenameData->name }}" class="metakey form-control p-0 border-0" autocomplete=off>
                                                                <input type="hidden" name="seolangtranlation_{{$languagecodenameData->code}}" value="{{ $seoLanguagedatarow->id }}" class="form-control p-0 border-0">
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-4">
                                                            <label class="col-md-12 p-0">Meta description</label>
                                                            <div class="col-md-12 border-bottom p-0">
                                                                <textarea rows="5" id="metadesc" name="meta_description_{{$languagecodenameData->code}}" onkeyup="limitWords(this.id)" class="metaDescp form-control p-0 border-0">{{ old('meta_description_'.$languagecodenameData->code, $seoLanguagedatarow->meta_description) }}</textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    @endforeach
                                                </div>

                                                @php
                                                    $getCategoryValue = Helper::getFeatureUsesValue('category');
                                                    $getsubCategoryValue = Helper::getFeatureUsesValue('sub-category');
                                                    $getTagsValue = Helper::getFeatureUsesValue('tags');
                                                @endphp

                                                <?php
                                                    if (isset($data['categoryData'][0])) {
                                                        $mainCategorrId = $data['categoryData'][0];
                                                    }else{
                                                        $mainCategorrId = "";
                                                    }
                                                ?>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Company Category</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <select class="form-control notranslate" name="category" id="par-category" onChange="getSubCategory(this.value)" style="width:100%">
                                                            @foreach($data['parent_category'] as $categorysrow)
                                                                <option value="{{ $categorysrow->id }}" {{ $categorysrow->id == $mainCategorrId ? 'selected' : '' }}>
                                                                    {{ $categorysrow->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Sub Category (Package limit - {{ $getsubCategoryValue }})</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <select multiple class="form-control select2" name="sub_category[]" id="subcategory" onChange="getSelectedOptions(this)" style="width:100%">
                                                            @foreach($data['child_category'] as $child_categorysrow)
                                                            <option value="{{ $child_categorysrow['id'] }}" <?php if(in_array($child_categorysrow['id'],$data['subcategoryData'])){echo "selected";} ?>> {{ $child_categorysrow['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class=" p-0">Tags(select category first!) (Package limit - {{ $getTagsValue }})</label>
                                                    <div class=" ">
                                                        <select multiple class="form-control select2 notranslate" name="simpletags[]" id="par-tags" onChange="changeSimpletags(this)" style="width:100%">

                                                            @foreach($data['tags'] as $tagsrow)
                                                                <option value="{{ $tagsrow['id'] }}" <?php if(in_array($tagsrow['id'],$data['tagsData'])){echo "selected";} ?>>
                                                                     {{ $tagsrow['tags'] }} - {{ $tagsrow['id'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <label class=" p-0">Note: If the tags you thought aren't on the dropdown list <a href="{{ route('company.request.newticket') }}"> click here </a> for admin request!</label>
                                                </div>

                                                <?php /* ----------- DON'T UNCOMMENT OR DELETE IT TILL FURTHER NOITFY. (BY MADHULIKA) ----------------------------
                                                <div class="form-group mb-4">
                                                    <label class=" p-0">Own Tags(select category first!)</label>
                                                    <div class=" ">
                                                        <select multiple class="form-control select2" name="owntags[]" id="par-owntags" style="width:100%">
                                                            <option></option>
                                                            @foreach($data['owntags'] as $owntagsrow)
                                                            <option value="{{ $owntagsrow->id }}" <?php if(in_array($owntagsrow->id,$data['ownTagsData'])){echo "selected";} ?>> {{ $owntagsrow->tags }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class=" p-0">Select Directory Own Tags</label>
                                                    <div class=" ">
                                                        <select multiple class="form-control select2" name="direcowntags[]" id="par-dircowntags" style="width:100%">
                                                            <option></option>
                                                            @foreach($data['directoryowntags'] as $owntagsrow)
                                                            <option value="{{ $owntagsrow->id }}" <?php if(in_array($owntagsrow->id,$data['directoryownTagsData'])){echo "selected";} ?>> {{ $owntagsrow->tag_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class=" p-0">Create New Directory Own Tags</label>
                                                    <div class=" ">
                                                        <input type="text" name="createnewowntag" value="{{ old('createnewowntag') }}" placeholder="Enter tags with comma seperated eg:tag1,tag2..." class="form-control border-0" autocomplete=off>
                                                    </div>
                                                </div>
                                                */?>
                                            </div>
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-seo" class="btn btn-primary listsubmit">Save Changes</button>
                                            @endif
                                        </div>


                                        <div class="tab-pane <?php active('schedule');?>" id="schedule" role="tabpanel">

                                                <a class="btn btn-info mb-4 schedule_active_tab"  id="normaldays" >Normal days</a>
                                                <a class="btn btn-info mb-4"  id="exceptionalholidays" >Exceptional holidays</a>
                                                <a class="btn btn-info mb-4" id="publicholidays" >Public holidays</a>

                                                <input type="hidden" name="holiday_status" id="holiday_status" value="{{ $basicData->holiday_status??'' }}">

                                                <div class="normal_days_div" id="normal_days_div" style="display:block;">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                            <div class="panel-heading cust-tab-head">
                                                                <h4 class="box-title">Normal days</h4>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-4">
                                                                <label class="col-md-12 p-0">Add Comment</label>
                                                                <div class="col-md-12 border-bottom p-0">
                                                                    <textarea rows="5" name="add_normal_comment" id="add-comment" class="form-control border-0 " placeholder="Enter Comment">{{ old('$add_normal_comment', $basicData->schedule_normal_comment ?? '') }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="">
                                                        <div class="row mb-4">
                                                        <label class="col-md-12">Schedule Template</label>
                                                            <div class="col-md-3">
                                                                <div class="custom-control  custom-radio">
                                                                    <input type="radio" class="pay" name="normaldays" value="no" id="pay" {{ $basicData->is_normaltemplate=='no' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">No </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="pay" name="normaldays" value="yes" id="nonpay" {{ $basicData->is_normaltemplate=='yes' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-4" id="normaldays-template" style="display:none;">
                                                        <label class=" p-0">Normal Days Template</label>
                                                        <div class="">

                                                            <select class="form-control" id="normaldaytemplateselect" name="normaldaytemplateselect" onchange="getNormalTemplateOption(this.value);" style="width:100%">
                                                                <option value="none">Select Template</option>
                                                                @foreach($data['normaldaysdata'] as $normaldaysdatarow)
                                                                <option value="{{ $normaldaysdatarow->id }}" {{ $normaldaysdatarow->id == $basicData->is_normaltemplate_id ? 'selected' : '' }}> {{ $normaldaysdatarow->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <?php if($basicData->is_normaltemplate=='yes'){ ?>
                                                        <div class="table-schedule">
                                                            <table id="nomaldays-template-table" class="table text-nowrap" style="display:none;">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="border-top-0">Day</th>
                                                                        <th class="border-top-0">Closed</th>
                                                                        <th class="border-top-0">Open</th>
                                                                        <th class="border-top-0">Closed</th>
                                                                        <th class="border-top-0">Add Hours</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                    $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                                    "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                                    "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                                    ?>
                                                                    <?php if(count($scheduleData)!=0){ ?>
                                                                    <?php foreach($scheduleData as $value=>$dayDetail){ ?>
                                                                    <?php $j=0; foreach($dayDetail as $dayData){ ?>
                                                                    <tr class=" <?= $value.'_template';?> <?= $value.'_template_row_'.$j;?>">
                                                                        <td> <?= $j==0?$value.':':''; ?></td>
                                                                        <td>
                                                                            <?php if($j==0){?>
                                                                            <input class="form-check-input" type="checkbox" name="<?php echo $value; ?>_template_is_closed" value="1" <?php if($dayData['is_closed']){echo "checked";}?>>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control" name="<?= $value;?>_template_hours_min[]">
                                                                                <?php foreach($timeValue as $rowtime){ ?>
                                                                                <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['open_time']){echo "selected" ;}?>>
                                                                                    <?php echo $rowtime ?>
                                                                                </option>
                                                                                <?php } ?>

                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control" name="<?= $value;?>_template_hours_max[]">
                                                                                <?php foreach($timeValue as $rowtime){ ?>
                                                                                <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['close_time']){echo "selected" ;}?>>
                                                                                    <?php echo $rowtime ?>
                                                                                </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($j==0){?><button type="button" class="btn btn-info" id="" onclick="addTemplateDayBlock('<?= $value.'_template';?>')">+ Hours</button>
                                                                            <?php }else{?>
                                                                            <button class="btn btn-danger" type="button" onclick="removeTemplateDayBlock('<?= $value.'_template';?>',<?= $j;?>)">- Hours</button>
                                                                            <?php } ?>
                                                                        <td>
                                                                    </tr>
                                                                    <?php $j++; } ?>
                                                                    <?php } ?>
                                                                    <?php } ?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="table-schedule">
                                                        <table id="nomaldays-table" class="table text-nowrap" style="display:none;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-top-0">Day</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                    <th class="border-top-0">Open</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                    <th class="border-top-0">Add Hours</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <?php
                                                                $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                            "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                            "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                                ?>

                                                                <?php if(count($scheduleData)!=0 && $basicData->is_normaltemplate!='yes'){ ?>
                                                                <?php foreach($scheduleData as $value=>$dayDetail){ ?>
                                                                <?php $j=0; foreach($dayDetail as $dayData){ ?>
                                                                <tr class=" <?= $value;?> <?= $value.'_row_'.$j;?>">
                                                                    <td> <?= $j==0?$value.':':''; ?></td>
                                                                    <td>
                                                                        <?php if($j==0){?>
                                                                        <input class="form-check-input" type="checkbox" name="<?php echo $value; ?>_is_closed" value="1" <?php if($dayData['is_closed']){echo "checked";}?>>
                                                                        <?php  } ?>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="<?= $value;?>_hours_min[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['open_time']){echo "selected" ;}?>>
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="<?= $value;?>_hours_max[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['close_time']){echo "selected" ;}?>>
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <?php if($j==0){?><button type="button" class="btn btn-info" id="" onclick="addDayBlock('<?= $value;?>')">+ Hours</button>
                                                                        <?php }else{?>
                                                                        <button class="btn btn-danger" type="button" onclick="removeDayBlock('<?= $value;?>',<?= $j;?>)">- Hours</button>
                                                                        <?php } ?>
                                                                    <td>
                                                                </tr>
                                                                <?php $j++; } ?>
                                                                <?php } ?>
                                                                <?php }else{ ?>
                                                                <?php foreach($days as $key => $value){ ?>
                                                                <tr class=" <?= $value;?> <?= $value.'_row_0';?>">
                                                                    <td> <?php echo $value; ?>:</td>
                                                                    <td> <input class="form-check-input" type="checkbox" name="<?php echo $value; ?>_is_closed" value="1"></td>
                                                                    <td>
                                                                        <select class="form-control" name="<?= $value;?>_hours_min[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="<?= $value;?>_hours_max[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td> <button type="button" class="btn btn-info" id="" onclick="addDayBlock('<?= $value;?>')">+ Hours</button>
                                                                    <td>
                                                                </tr>

                                                                <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    @if(Helper::getFeatureUses('schedule-normal') == false)
                                                    <?php
                                                        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                        $timeValue = array(
                                                            "00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00",
                                                            "05:30","06:00","06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30",
                                                            "11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00",
                                                            "16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30",
                                                            "22:00","22:30","23:00","23:30","24:00");
                                                    ?>
                                                     @endif
                                                </div>



                                                <div class="exceptional_days_div" id="exceptional_days_div" style="display:none;">
                                                    <?php /* ---------------------- START Exceptional holidays --------------------------------  */ ?>
                                                    <div class="row mb-4">
                                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                            <div class="panel-heading cust-tab-head">
                                                                <h4 class="box-title"> Exceptional holidays</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="row mb-4">
                                                            <label class="col-md-12">Schedule Template</label>
                                                            <div class="col-md-3">
                                                            <div class="custom-control custom-radio">
                                                                    <input type="radio" class="pay" name="exception_days" value="no" id="pay" {{ $basicData->is_exceptionaltemplate=='no' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">No </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="pay" name="exception_days" value="yes" id="nonpay" {{ $basicData->is_exceptionaltemplate=='yes' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-4 row" style="margin: 2px;" id="exception_days-template">
                                                        <label class="col-md-12 p-0">Exceptional Holidays Template</label>

                                                        <div class="col-md-4 cus-title"><br>
                                                            <div class="form-group mb-4">
                                                                <label class="col-md-12 p-0">Template Name</label>
                                                                <div class="col-md-12 border-bottom p-0">
                                                                    <input type="text" name="exceptional_template_name" id="exceptional_template_name" value="{{ old('exceptional_template_name') }}" placeholder="Enter Exceptional Template Name" class="form-control p-0 border-0" autocomplete=off>
                                                                </div>
                                                                <span class="text-danger">@error('exceptional_template_name'){{$message}} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <button type="submit" id="exceptional_template_name_btn" style="margin: 55px 0px 2px;" class="btn btn-info">Add</button>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" name="exception_daystemplateselect_admin" id="exception-daystemplateselect_admin" onchange ="getExceptionTemplateOption(this.value,'admin')" style="width:100%">
                                                                <option value="none">Admin Template</option>
                                                                @foreach($data['exeptionaldaysdata_admin'] as $exeptionaldaysdatarow)
                                                                <option value="{{ $exeptionaldaysdatarow->id }}" {{ $exeptionaldaysdatarow->id == $basicData->is_exceptionaltemplate_id ? 'selected' : '' }}> {{ $exeptionaldaysdatarow->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" name="exception_daystemplateselect" id="exception-daystemplateselect" onchange ="getExceptionTemplateOption(this.value,'vendor')" style="width:100%">
                                                                <option value="none">Vendor Template</option>
                                                                @foreach($data['exeptionaldaysdata'] as $exeptionaldaysdatarow)
                                                                <option value="{{ $exeptionaldaysdatarow->id }}" {{ $exeptionaldaysdatarow->id == $basicData->is_exceptionaltemplate_id ? 'selected' : '' }}> {{ $exeptionaldaysdatarow->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="file" style="width: 219px;float: left;" target="_blank" name="exeptional_template_impt_file" id="exeptional_template_impt_file" style="float: left;width: 70%; margin: -2px 5px;" class="form-control">
                                                            <a class="btn btn-success btn-sm" style="text-align: right;margin: 5px;color: #fff;" id="exeptional_templateimpt_btn">Import Data </a>
                                                        </div>

                                                        <div class="col-md-3 import-style">
                                                            <a href="{{ asset('assets/client/format_Import_holidaytemplate_inner.xlsx') }}" download title="Download Exc format">
                                                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Sample Format
                                                            </a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="exeptional_template_upload-status" style="text-align: right; text-align: right;margin: 5px 310px;"></div>
                                                        </div>

                                                    </div>

                                                    <input type="hidden" name="exeActualdataforyesno" value="{{ $basicData->is_exceptionaltemplate }}">
                                                    <input type="hidden" name="exeActualdataforselectedTemplateId" value="{{ $basicData->is_exceptionaltemplate_id }}">

                                                    <?php if($basicData->is_exceptionaltemplate=='yes'){if($data['scheduleExceptionHolidayDatacount']!=0){ ?>
                                                    <div class="table-exceptional">
                                                        <table id="temp-exceptiondaysdetails-table" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Date</th>
                                                                <th class="border-top-0">Holiday</th>
                                                                <th class="border-top-0">Day</th>
                                                                <th class="border-top-0">Opening</th>
                                                                <th class="border-top-0">Closing</th>
                                                                <th class="border-top-0">Closed</th>
                                                                <th class="border-top-0 delete_default_exceptional_dv">Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach($data['scheduleExceptionHolidayData'] as $holidayrow)
                                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="default_exceptional_holi_<?php echo $holidayrow->id; ?>">
                                                                <td>
                                                                    {{ $holidayrow->custom_date }}
                                                                </td>
                                                                <td>
                                                                    {{ $holidayrow->note }}
                                                                </td>
                                                                <td>
                                                                    {{ $holidayrow->day }}
                                                                </td>
                                                                <td>
                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                    <p><?php echo "Open Time: ".$extratime->open_time; ?></p>
                                                                    <?php } ?>

                                                                </td>
                                                                <td>
                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                    <p><?php echo "Close Time: ".$extratime->close_time; ?></p>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    @if($holidayrow->closed == 1 || $holidayrow->closed == 'Yes')
                                                                        Yes
                                                                    @else
                                                                    No
                                                                    @endif
                                                                </td>
                                                                <td class="delete_default_exceptional_dv">
                                                                    <button type="button" class="btn btn-sm btn-danger delete_default_exceptional" data-id="{{ $holidayrow->id }}">Delete</button>
                                                                </td>

                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        </table>
                                                    </div>

                                                    <?php /* ---------------------- START EDIT TEMPLATE SECTION Exceptional holidays --------------------------------  */ ?>
                                                        <?php

                                                        if($data['scheduleExceptionHolidayDatacount'] > 0){ ?>
                                                        <div class="custom-control custom-checkbox editDataTemplateexception">
                                                            <input type="checkbox" class="form-check-input" name="editDataTemplateexception" id="edit_tempalte_checkbox_exception" value="one">
                                                            {{-- <label class="custom-control-label" for="1"><i class="far fa-edit pub-holiday-css" title="Edit Data" style="color: #636363;"></i> Edit </label> --}}
                                                            <label class="custom-control-label btn btn-success" for="1" style="padding: 0px 20px 0px 28px; color: #fff;"><i class="far fa-edit pub-holiday-css" title="Edit Data" style="color: #636363;"></i> Edit </label>
                                                        </div>
                                                        <?php } ?>
                                                        <div id="edit_template_exception_table">
                                                            <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                    $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                                "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                                "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                            ?>

                                                            <?php if($data['scheduleExceptionHolidayDatacount']!=0){ ?>
                                                                <table class="table table-bordered" >
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border-top-0">Date</th>
                                                                            <th class="border-top-0">Holiday</th>
                                                                            <th class="border-top-0">Day</th>
                                                                            <th class="border-top-0">Opening</th>
                                                                            <th class="border-top-0">Closing</th>
                                                                            <th class="border-top-0">Closed</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($data['scheduleExceptionHolidayData'] as $holidayrow)
                                                                        <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                        <input type="hidden" name="template_real_id[]" value="{{ $holidayrow->id }}">
                                                                        <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="yes_exceptional_holi_<?php echo $holidayrow->id; ?>">
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">
                                                                                    <input type="date" name="edit_template_custom_date[]" value="{{ $holidayrow->custom_date }}" class="form-control  border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">
                                                                                    <input type="text" name="edit_template_note[]" value="{{ $holidayrow->note }}" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>

                                                                                <select class="form-control" name="edit_exceptional_day[]">
                                                                                    <?php foreach($days as $rowval){ ?>
                                                                                    <option value="<?php echo $rowval ?>" {{ $rowval == $holidayrow->day ? 'selected' : '' }}>
                                                                                    <?php echo $rowval ?>
                                                                                    </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_time_hours_min[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->open_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>

                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_time_hours_max[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->close_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-group mb-4">
                                                                                    <div class="col-md-12 border-bottom ">
                                                                                        <select name="closed" class="form-control border-0">
                                                                                            <option value="No">No</option>
                                                                                            <option value="Yes">Yes</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>

                                                            <div class="form-group mb-4">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" class="btn btn-info listsubmit">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php /* ---------------------- END EDIT TEMPLATE SECTION Exceptional holidays --------------------------------  */ ?>

                                                    <?php } } ?>
                                                    <?php if($basicData->is_exceptionaltemplate=='no'){
                                                    if($data['scheduleExceptionHolidayDatacount']!=0){ ?>
                                                    <div class="exceptional-schedule-table">
                                                        <table id="exceptiondaysdetails-table" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="border-top-0">Date</th>
                                                                <th class="border-top-0">Holiday</th>
                                                                <th class="border-top-0">Day</th>
                                                                <th class="border-top-0">Opening</th>
                                                                <th class="border-top-0">Closing</th>
                                                                <th class="border-top-0">Closed</th>
                                                                <th class="border-top-0">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($data['scheduleExceptionHolidayData'] as $holidayrow)
                                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="no_exceptional_holi_<?php echo $holidayrow->id; ?>">
                                                                <td>
                                                                    {{ $holidayrow->custom_date }}
                                                                </td>
                                                                <td>
                                                                    {{ $holidayrow->note }}
                                                                </td>
                                                                <td>
                                                                    {{ $holidayrow->day }}
                                                                </td>
                                                                <td>
                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                    <p><?php echo "Open Time: ".$extratime->open_time; ?></p>
                                                                    <?php } ?>

                                                                </td>
                                                                <td>
                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                    <p><?php echo "Close Time: ".$extratime->close_time; ?></p>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    {{ $holidayrow->closed }}
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-danger delete_no_exceptional" data-id="{{ $holidayrow->id }}">Delete</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        </table>
                                                    </div>

                                                    <?php /* ---------------------- START EDIT SECTION Exceptional holidays --------------------------------  */ ?>

                                                    <div class="row exceptional_holidays_checkbox">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="custom-control custom-checkbox ">
                                                                <input type="checkbox" class="form-check-input" name="editDataexception" id="edit_checkboe_exception" value="one">
                                                                <label class="custom-control-label btn btn-success" for="1" style="padding: 0px 20px 0px 28px; color: #fff;"><i class="far fa-edit pub-holiday-css" title="Edit Data" style="color: #636363;"></i> Edit </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 import-style">
                                                            <a href="{{ asset('assets/client/format_Import_holidaytemplate_inner.xlsx') }}" download title="Download Exc format">
                                                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Sample Format
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <input type="file" style="width: 219px;float: left;" target="_blank" name="exceptional_file" id="exceptional_impt_file" style="float: left;width: 70%; margin: -2px 5px;" class="form-control">
                                                                <a class="btn btn-success btn-sm" style="text-align: right;margin: 5px;color: #fff;" id="exceptional_impt_btn">Import Data</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="exceptional_upload_status" style="text-align: right;"></div>
                                                        </div>
                                                    </div>

                                                    <div id="edit_exception_table">
                                                            <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                    $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                                "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                                "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                            ?>

                                                            <?php if(count($data['scheduleExceptionHolidayData'])!=0){ ?>
                                                                <table class="table table-bordered" >
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border-top-0">Date</th>
                                                                            <th class="border-top-0">Holiday</th>
                                                                            <th class="border-top-0">Day</th>
                                                                            <th class="border-top-0">Opening</th>
                                                                            <th class="border-top-0">Closing</th>
                                                                            <th class="border-top-0">Closed</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($data['scheduleExceptionHolidayData'] as $key => $holidayrow)
                                                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                            <input type="hidden" name="real_id[]" value="{{ $holidayrow->id }}">
                                                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="tblholi_<?php echo $holidayrow->id; ?>">
                                                                                <td>
                                                                                    <div class="col-md-12 border-bottom ">

                                                                                        <input type="date" name="edit_custom_date[]" value="{{ $holidayrow->custom_date }}" class="form-control  border-0" autocomplete=off>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="col-md-12 border-bottom ">
                                                                                        <input type="text" name="edit_note[]" value="{{ $holidayrow->note }}" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="col-md-12 border-bottom ">
                                                                                        <select class="form-control" name="edit_exceptional_day[]">
                                                                                            <?php foreach($days as $rowval){ ?>
                                                                                            <option value="<?php echo $rowval ?>" {{ $rowval == $holidayrow->day ? 'selected' : '' }}>
                                                                                            <?php echo $rowval ?>
                                                                                            </option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                                        <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_time_hours_min[]">
                                                                                            <?php foreach($timeValue as $rowtime){?>
                                                                                                <option value="{{ $rowtime }}" {{ $rowtime == $extratime->open_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php foreach($opentimeholiday as $extratime){?>
                                                                                        <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_time_hours_max[]">
                                                                                            <?php foreach($timeValue as $rowtime){?>
                                                                                                <option value="{{ $rowtime }}" {{ $rowtime == $extratime->close_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="hidden" name="template_exceptional_is_closed[{{ $key }}]" value="No">
                                                                                    <input class="form-check-input" type="checkbox" name="template_exceptional_is_closed[{{ $key }}]" value="Yes" {{ $holidayrow->closed === 'Yes' ? 'checked' : '' }}>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>
                                                            <div class="form-group mb-4">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" class="btn btn-info listsubmit">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php /* ---------------------- END EDIT SECTION Exceptional holidays --------------------------------  */ ?>
                                                    <?php } } ?>
                                                    <div class="table-schedule">
                                                        <table id="exceptiondays-table" class="table text-nowrap table-info table-placeholder-css" style="display:none;" >
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-top-0">Date</th>
                                                                    <th class="border-top-0">Holiday</th>
                                                                    <th class="border-top-0">Day</th>
                                                                    <th class="border-top-0">Opening</th>
                                                                    <th class="border-top-0">Closing</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                    <th class="border-top-0">Add Hours</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="row_exceptional execption_time_slot_row_0">
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <input type="date" name="exception_date" class="form-control  border-0" autocomplete=off>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <input type="text" name="exception_note" placeholder="Holiday note" class="form-control border-0" autocomplete=off>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="col-md-12 border-bottom ">
                                                                            <select class="form-control" name="exception_day">
                                                                                <?php foreach($days as $rowval){ ?>
                                                                                <option value="<?php echo $rowval ?>" >
                                                                                <?php echo $rowval ?>
                                                                                </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="exception_time_hours_min[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="exception_time_hours_max[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <select name="exception_closed" class="form-control border-0">
                                                                                    <option value="No">No</option>
                                                                                    <option value="Yes">Yes</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-primary" id="" onclick="addTimeBlockException()">+ Hours</button>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td><button type="submit" id="listsubmit-schedule" class="btn btn-warning listsubmit">Save Time Slot</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php /* ---------------------- END Exceptional holidays --------------------------------  */ ?>
                                                </div>

                                                <div class="public_days_div" id="public_days_div" style="display:none;">
                                                    <?php /* ---------------------- Public holidays --------------------------------  */ ?>

                                                    <div class="row mb-4">
                                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                            <div class="panel-heading cust-tab-head">
                                                                <h4 class="box-title">Public holidays</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="row mb-4">
                                                        <label class="col-md-12">Schedule Template</label>
                                                            <div class="col-md-3">
                                                                <div class="custom-control  custom-radio">
                                                                    <input type="radio" class="pay public_h_radio" name="publicdays" value="no" id="pay" {{ $basicData->is_publictemplate=='no' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">No </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="custom-control  custom-radio">
                                                                    <input type="radio" class="pay public_h_radio" name="publicdays" value="yes" id="nonpay" {{ $basicData->is_publictemplate=='yes' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="1">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-4 row" style="margin: 2px;" id="publicdays-template" style="display:none;">

                                                        <div class="col-md-4 cus-title"><br>
                                                            <div class="form-group mb-4">
                                                                <label class="col-md-12 p-0">Public Template Name</label>
                                                                <div class="col-md-12 border-bottom p-0">
                                                                    <input type="text" name="public_template_name" id="public_template_name" value="{{ old('public_template_name') }}" placeholder="Enter Template Name" class="form-control p-0 border-0" autocomplete=off>
                                                                </div>
                                                                <span class="text-danger">@error('public_template_name'){{$message}} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <button type="submit" id="public_template_btn" style="margin: 55px 0px 2px;" class="btn btn-info">Add</button>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class=" p-0">Public Holidays Template Admin</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class=" p-0">Public Holidays Template Vendor</label>
                                                        </div>
                                                        <div class="col-md-2 import-style">
                                                            <a href="{{ asset('assets/client/format_Import_holidaytemplate_inner.xlsx') }}" download title="Download Exc format">
                                                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Sample Format
                                                            </a>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="publicdaystemplateselect_admin" id="publicdaystemplateselect_admin" onchange="getpublicTemplateOption(this.value,'admin');" style="width:100%">
                                                                <option value="none">Select Template</option>
                                                                @foreach($data['publicdaysdata_admin'] as $publicdaysdatarow)
                                                                <option value="{{ $publicdaysdatarow->id }}" {{ $publicdaysdatarow->id == $basicData->is_publictemplate_id ? 'selected' : '' }}>
                                                                    {{ $publicdaysdatarow->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1"> </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="publicdaystemplateselect" id="publicdaystemplateselect" onchange="getpublicTemplateOption(this.value,'vendor');" style="width:100%">
                                                                <option value="none">Select Template</option>
                                                                @foreach($data['publicdaysdata'] as $publicdaysdatarow)
                                                                <option value="{{ $publicdaysdatarow->id }}" {{ $publicdaysdatarow->id == $basicData->is_publictemplate_id ? 'selected' : '' }}>
                                                                    {{ $publicdaysdatarow->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="file" style="width: 219px;float: left;" target="_blank" name="public_template_impt_file" id="public_template_impt_file" style="float: left;width: 70%; margin: -2px 5px;" class="form-control">
                                                            <a class="btn btn-success btn-sm" style="text-align: right;margin: 5px;color: #fff;" id="public_templateimpt_btn">Import Template Data </a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="template_upload-status" style="text-align: right; text-align: right;margin: 5px 310px;"></div>
                                                        </div>

                                                    </div>

                                                    <input type="hidden" name="actualdataforyesno" value="{{ $basicData->is_publictemplate }}">
                                                    <input type="hidden" name="actualdataforselectedTemplateId" value="{{ $basicData->is_publictemplate_id }}">

                                                    <?php
                                                    if($basicData->is_publictemplate=='yes'){
                                                        if($data['scheduleHolidayDatacount']!=0){
                                                    ?>

                                                    <div class="temp-schecule-table">
                                                        <table id="temp-publicdaysdetails-table" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-top-0">Date</th>
                                                                    <th class="border-top-0">Holiday</th>
                                                                    <th class="border-top-0">Day</th>
                                                                    <th class="border-top-0">Opening</th>
                                                                    <th class="border-top-0">Closing</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                    <th class="border-top-0">Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($data['scheduleHolidayData'] as $holidayrow)
                                                                <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="public_holi_<?php echo $holidayrow->id; ?>">
                                                                    <td>
                                                                        {{ $holidayrow->custom_date }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $holidayrow->note }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $holidayrow->day??'' }}
                                                                    </td>
                                                                    <td>
                                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                                        <p><?php echo "Open Time: ".$extratime->open_time; ?></p>
                                                                        <?php } ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                                        <p><?php echo "Close Time: ".$extratime->close_time; ?></p>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-check-input" type="checkbox" disabled value="Yes" {{ ($holidayrow->closed == '1' || $holidayrow->closed == 'Yes') ? 'checked' : '' }}>

                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger deleterow" data-id="{{ $holidayrow->id }}">Delete</button>
                                                                    </td>

                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" name="editDataTemplatepublic" id="edit_tempalte_checkbox_public" value="one">
                                                        <label class="custom-control-label btn btn-success" for="1" style="padding: 0px 20px 0px 28px; color: #fff;">
                                                            <i class="far fa-edit pub-holiday-css" title="Edit Data" style="color: #636363;"></i> Edit </label>
                                                    </div>
                                                    <?php /* ---------------------- START EDIT TEMPLATE SECTION Public holidays --------------------------------  */ ?>
                                                        <div id="edit_template_public_table">
                                                            <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                    $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                                "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                                "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                            ?>

                                                            <?php if($data['scheduleHolidayData']!=0){ ?>
                                                                <table class="table table-bordered" >
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border-top-0">Date</th>
                                                                            <th class="border-top-0">Holiday</th>
                                                                            <th class="border-top-0">Day</th>
                                                                            <th class="border-top-0">Opening</th>
                                                                            <th class="border-top-0">Closing</th>
                                                                            <th class="border-top-0">Closed </th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($data['scheduleHolidayData'] as $key => $holidayrow)
                                                                        <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                        <input type="hidden" name="public_template_real_id[]" value="{{ $holidayrow->id }}">
                                                                        <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="public_holi_<?php echo $holidayrow->id; ?>">
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">

                                                                                    <input type="date" name="edit_public_template_custom_date[]" value="{{ $holidayrow->custom_date }}" class="form-control  border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">
                                                                                    <input type="text" name="edit_public_template_note[]" value="{{ $holidayrow->note }}" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control" name="edit_day[]">
                                                                                    <?php foreach($days as $rowval){ ?>
                                                                                    <option value="<?php echo $rowval ?>" {{ strtolower($rowval) == strtolower($holidayrow->day) ? 'selected' : '' }}>
                                                                                    <?php echo $rowval ?>
                                                                                    </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_public_time_hours_min[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->open_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>

                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_public_time_hours_max[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->close_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </td>

                                                                            <td>
                                                                                <input type="hidden" name="template_holiday_is_closed[{{ $key }}]" value="No">
                                                                                <input class="form-check-input" type="checkbox" name="template_holiday_is_closed[{{ $key }}]" value="Yes" {{ $holidayrow->closed == 'Yes' || $holidayrow->closed === '1' ? 'checked' : '' }}>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>

                                                            <div class="form-group mb-4">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" class="btn btn-info listsubmit">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php /* ---------------------- END EDIT TEMPLATE SECTION Public holidays --------------------------------  */ ?>

                                                    <?php }} ?>
                                                    <?php
                                                    if($basicData->is_publictemplate=='no'){
                                                        if($data['scheduleHolidayDatacount']!=0){
                                                    ?>
                                                    <div class="table-public">
                                                        <table id="publicdaysdetails-table" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-top-0">Date</th>
                                                                    <th class="border-top-0">Holiday</th>
                                                                    <th class="border-top-0">Day</th>
                                                                    <th class="border-top-0">Opening</th>
                                                                    <th class="border-top-0">Closing</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                    <th class="border-top-0">Action</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach($data['scheduleHolidayData'] as $key => $holidayrow)
                                                                <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                <tr id="tblholi_<?php echo $holidayrow->id; ?>" id="no_public_holi_<?php echo $holidayrow->id; ?>">
                                                                    <td>
                                                                        {{ $holidayrow->custom_date }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $holidayrow->note }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $holidayrow->day??'' }}
                                                                    </td>
                                                                    <td>
                                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                                        <p><?php echo "Open Time: ".$extratime->open_time; ?></p>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                                        <p><?php echo "Close Time: ".$extratime->close_time; ?></p>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-check-input" type="checkbox" disabled  value="Yes" {{ $holidayrow->closed === 'Yes' ? 'checked' : '' }}>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger deleterow" data-id="{{ $holidayrow->id }}">Delete</button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row align-items-center public_h_checkbox">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="form-check-input " name="editDataPublic" id="edit_checkbox_public" value="one">
                                                                <label class="custom-control-label btn btn-success" for="1" style="padding: 0px 20px 0px 28px; color: #fff;">
                                                                    <i class="far fa-edit pub-holiday-css" title="Edit Data" style="color: #636363;"></i> Edit </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 import-style">
                                                            <a href="{{ asset('assets/client/format_Import_holidaytemplate_inner.xlsx') }}" download title="Download Exc format">
                                                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Sample Format
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <input type="file" style="width: 219px;float: left;" target="_blank" name="public_file" id="public_impt_file" style="float: left;width: 70%; margin: -2px 5px;" class="form-control">
                                                                <a class="btn btn-success btn-sm" style="text-align: right;margin: 5px;color: #fff;" id="public_impt_btn">Import Data</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="upload-status" style="text-align: right;"></div>
                                                        </div>
                                                    </div>

                                                    <?php /* ---------------------- START EDIT SECTION Public holidays --------------------------------  */ ?>
                                                        <div id="edit_public_table">
                                                            <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                                                    $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                                                "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                                                "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                                            ?>

                                                            <?php if($data['scheduleHolidayData']!=0){ ?>
                                                                <table class="table table-bordered" >
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border-top-0">Date</th>
                                                                            <th class="border-top-0">Holiday</th>
                                                                            <th class="border-top-0">Day</th>
                                                                            <th class="border-top-0">Opening</th>
                                                                            <th class="border-top-0">Closing</th>
                                                                            <th class="border-top-0">Closed</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($data['scheduleHolidayData'] as $key => $holidayrow)
                                                                        <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                                                        <input type="hidden" name="public_real_id[]" value="{{ $holidayrow->id }}">
                                                                        <tr id="tblholi_<?php echo $holidayrow->id; ?>" class="no_public_holi_<?php echo $holidayrow->id; ?>">
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">

                                                                                    <input type="date" name="edit_public_custom_date[]" value="{{ $holidayrow->custom_date }}" class="form-control  border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="col-md-12 border-bottom ">
                                                                                    <input type="text" name="edit_public_note[]" value="{{ $holidayrow->note }}" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control" name="edit_day[]">
                                                                                    <?php foreach($days as $rowval){ ?>
                                                                                    <option value="<?php echo $rowval ?>" {{ $rowval == $holidayrow->day ? 'selected' : '' }}>
                                                                                    <?php echo $rowval ?>
                                                                                    </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_public_notemplate_time_hours_min[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->open_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>

                                                                            </td>
                                                                            <td>
                                                                                <?php foreach($opentimeholiday as $extratime){?>
                                                                                    <select class="form-control" name="<?php echo $holidayrow->id; ?>_edit_public_notemplate_time_hours_max[]">
                                                                                        <?php foreach($timeValue as $rowtime){?>
                                                                                            <option value="{{ $rowtime }}" {{ $rowtime == $extratime->close_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" name="template_holiday_is_closed[{{ $key }}]" value="No">
                                                                                <input class="form-check-input" type="checkbox" name="template_holiday_is_closed[{{ $key }}]" value="Yes" {{ $holidayrow->closed === 'Yes' ? 'checked' : '' }}>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>

                                                            <div class="form-group mb-4">
                                                                <div class="col-sm-12">
                                                                    <button type="submit" class="btn btn-info listsubmit">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php /* ---------------------- END EDIT SECTION Public holidays --------------------------------  */ ?>

                                                    <?php }} ?>
                                                    <div class="table-schedule">
                                                        <table id="publicdays-table" class="table text-nowrap table-info table-placeholder-css" style="display:none;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-top-0">Date</th>
                                                                    <th class="border-top-0">Holiday</th>
                                                                    <th class="border-top-0">Day</th>
                                                                    <th class="border-top-0">Opening</th>
                                                                    <th class="border-top-0">Closing</th>
                                                                    <th class="border-top-0">Add Hours</th>
                                                                    <th class="border-top-0">Closed</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="row_public public_time_slot_row_0">
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <input type="date" name="custom_date" class="form-control  border-0" autocomplete=off>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <input type="text" name="note" placeholder="Holiday title" class="form-control border-0" autocomplete=off>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="day">
                                                                            <?php foreach($days as $rowval){ ?>
                                                                            <option value="<?php echo $rowval ?>" >
                                                                            <?php echo $rowval ?>
                                                                            </option>
                                                                            <?php } ?>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="time_hours_min[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="time_hours_max[]">
                                                                            <?php foreach($timeValue as $rowtime){ ?>
                                                                            <option value="<?php echo $rowtime ?>">
                                                                                <?php echo $rowtime ?>
                                                                            </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-primary" id="" onclick="addTimeBlockPublic()">+ Hours</button>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group mb-4">
                                                                            <div class="col-md-12 border-bottom ">
                                                                                <select name="closed" class="form-control border-0">
                                                                                    <option value="No">No</option>
                                                                                    <option value="Yes">Yes</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4"></td>
                                                                    <td><button type="submit" id="listsubmit-schedule" class="btn btn-warning listsubmit">Save Time Slot</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


                                                @if ($user->hasPermission('update') == true)
                                                    <button type="submit" id="listsubmit-schedule" class="btn btn-primary listsubmit">Save Changes</button>
                                                @endif

                                        </div>

                                        <div class="tab-pane <?php active('type');?>" id="dirtype" role="tabpanel">

                                            <div class="row">
                                                <?php $radioselectecheckeddata = isset($directoryTypeData[0]->category_id)? $directoryTypeData[0]->category_id : 0;
                                                $blockNum = isset($directoryTypeDatainreverse[0]->block_num)? $directoryTypeDatainreverse[0]->block_num : "novalue";
                                                ?>
                                                @foreach($data['type'] as $typerow)
                                                <div class="col-md-3 p-10">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="typecat" name="hotel_type" value="{{ $typerow->id }}" {{ $radioselectecheckeddata==$typerow->id ? 'checked' : ''}} data_val="{{ $typerow->name }}">
                                                        <label class="custom-control-label" for="1">{{ $typerow->name }} </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <input type="hidden" id="total_block_from_database" name="block_number_from_database" value="{{ $blockNum }}">
                                            <input type="hidden" id="directoryId" name="directory_Id" value="{{ isset($directoryTypeData[0]->directory_id)?$directoryTypeData[0]->directory_id:'' }}">
                                            <input type="hidden" id="selected-cat-id" name="selected_cat_id" value="{{ isset($directoryTypeData[0]->category_id)?$directoryTypeData[0]->category_id:0 }}">
                                            <input type="hidden" id="total_block" name="block_number" value="">
                                            <div class="blocks" id="type_blocks"></div>
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-dirtype" class="btn btn-primary listsubmit">Save Changes</button>
                                            @endif
                                        </div>

                                        <div class="tab-pane <?php active('links');?>" id="links" role="tabpanel">
                                            <div class="row mb-4">
                                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                    <div class="panel-heading cust-tab-head">
                                                        <h4 class="box-title">Basic Link Details</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Website</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="website" value="{{ old('website', isset($contactData[0]->website)?$contactData[0]->website:'') }}" placeholder="Enter Website" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Facebook</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="facebook" value="{{ old('facebook', isset($contactData[0]->facebook)?$contactData[0]->facebook:'') }}" placeholder="www.facebook.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Twitter</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="twitter" value="{{ old('twitter', isset($contactData[0]->twitter)?$contactData[0]->twitter:'') }}" placeholder="www.twitter.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Linkedin</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="linkedin" value="{{ old('linkedin', isset($contactData[0]->linkedin)?$contactData[0]->linkedin:'') }}" placeholder="www.linkedin.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Pinterest</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="pinterest" value="{{ old('pinterest', isset($contactData[0]->pinterest)?$contactData[0]->pinterest:'') }}" placeholder="www.pinterest.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Whatsapp</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="whatsapp" value="{{ old('whatsapp', isset($contactData[0]->whatsapp)?$contactData[0]->whatsapp:'') }}" placeholder="www.whatsapp.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Instagram</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="instagram" value="{{ old('instagram', isset($contactData[0]->instagram)?$contactData[0]->instagram:'') }}" placeholder="www.instagram.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Tiktok</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="tiktok" value="{{ old('tiktok', isset($contactData[0]->tiktok)?$contactData[0]->tiktok:'') }}" placeholder="www.tiktok.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Youtube</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="youtube" value="{{ old('youtube', isset($contactData[0]->youtube)?$contactData[0]->youtube:'') }}" placeholder="www.youtube.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Snapchat</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="snapchat" value="{{ old('snapchat', isset($contactData[0]->snapchat)?$contactData[0]->snapchat:'') }}" placeholder="www.snapchat.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="website_count" value="<?php echo count($websitelink); ?>" id="wesitecount" />
                                            <input type="hidden" name="links_count" value="<?php echo ($getFeatureLinksValue); ?>" id="wesitelinkcount" />
                                            <div class="row mb-4">
                                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                    <div class="panel-heading cust-tab-head">
                                                        <h4 class="box-title">Website Link (Max limit - <?php echo ($getFeatureLinksValue); ?>)</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $i=1; if(count($websitelink)!=0){ foreach($websitelink as $websitelinkrow){ ?>
                                            <div id="weblink_<?php echo $i; ?>" class="row weblink">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Website Name</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="website_name_<?php echo $i; ?>" value="{{ old('website', $websitelinkrow->name ?? '') }}" placeholder="Enter Website Name" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Website link</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="website_link_<?php echo $i; ?>" value="{{ old('website', $websitelinkrow->link ?? '') }}" placeholder="Enter Website link" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php if($i!=1){ ?>
                                                    <button type="button" class="btn btn-danger" class="remove_web_button" title="remove field" onclick=removewebSection(<?php echo $i; ?>)>-</button>
                                                    <?php }else{ ?>
                                                    <button type="button" class="btn btn-success" class="add_web_button" title="Add field" onclick=addwebSection()>+</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php $i++; } }else{ ?>
                                            <div id="weblink_1" class="row weblink">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Website Name</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="website_name_1" value="{{ old('website') }}" placeholder="Enter Website Name" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Website link</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="website_link_1" value="{{ old('website') }}" placeholder="Enter Website link" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-success" class="add_web_button" title="Add field" onclick=addwebSection()>+</button>
                                                </div>
                                            </div>

                                            <?php } ?>

                                            <div class="row mb-4">
                                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                    <div class="panel-heading cust-tab-head">
                                                        <h4 class="box-title">Assortment Link (Max limit - <?php echo ($getFeatureLinksValue); ?>)</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="assort_count" value="<?php echo count($assortlink); ?>" id="assortcount" />
                                            <?php $ai=1; if(count($assortlink)!=0){ foreach($assortlink as $assortlinkrow){ ?>
                                            <div id="assortlink_<?php echo $ai; ?>" class="row assortlink">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Assortment Name</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="assortment_name_<?php echo $ai; ?>" value="{{ old('website', $assortlinkrow->name ?? '') }}" placeholder="Enter Assortment Name" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('assortment_name'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Assortment link</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="assortment_link_<?php echo $ai; ?>" value="{{ old('assortment_link', $assortlinkrow->link ?? '') }}" placeholder="Enter Assortment link" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php if($ai!=1){ ?>
                                                    <button type="button" class="btn btn-danger" class="remove_assortment_button" title="remove field" onclick=removeAssortmentSection(<?php echo $ai; ?>)>-</button>
                                                    <?php }else{ ?>
                                                    <button type="button" class="btn btn-success" class="add_assortment_button" title="Add field" onclick=addAssortmentSection()>+</button>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                            <?php $ai++; } }else{ ?>
                                            <div id="assortlink_1" class="row assortlink">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Assortment Name</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="assortment_name_1" value="{{ old('website') }}" placeholder="Enter Assortment Name" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('assortment_name'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Assortment link</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="assortment_link_1" value="{{ old('assortment_link') }}" placeholder="Enter Assortment link" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-success" class="add_assortment_button" title="Add field" onclick=addAssortmentSection()>+</button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-contact" class="btn btn-primary listsubmit">Save Changes</button>
                                            @endif
                                        </div>

                                        <div class="tab-pane <?php active('claim');?>" id="claim" role="tabpanel">

                                            <div id="claim_row_1" class="row claimlink">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Claim Subject</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="claim_subject" value="{{ old('claim_subject') }}" placeholder="Enter claim subject" class="form-control p-0 border-0" autocomplete=off>
                                                        </div>
                                                        <span class="text-danger">@error('claim_subject'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Claim Description</label>
                                                        <div class="col-md-12 border-bottom p-0">
                                                            <!-- <input type="text" name="website_link_1" value="{{ old('website') }}" placeholder="Enter Website link" class="form-control p-0 border-0" autocomplete=off> -->
                                                            <textarea rows="3" placeholder="Enter Description" name="claim_description" id="claimtext" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('claim_description') }}</textarea>
                                                        </div>
                                                        <span class="text-danger">@error('claim_description'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" id="listsubmit-claim" class="btn btn-success listsubmit">Add Claim</button>
                                                    <!-- <button type="button" class="btn btn-success" class="add_claim_button" title="Add field" onclick=addmoreClaimintoTable($basicData->id)>Add Claim</button> -->
                                                </div>
                                            </div>
                                            <hr/>
                                            <?php if($data['directory_claim_count']>0){ ?>


                                                    <?php foreach($data['directory_claim'] as $value){ ?>
                                                        <div class="claim-data-result">
                                                            <p><strong>Subject</strong></p>
                                                            <p>{{ $value->subject }}</p>
                                                            <p><strong>Description</strong></p>
                                                            <p>{{ $value->description }}</p>
                                                        </div>
                                                    <?php } ?>



                                            <?php } ?>


                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-claim-final" class="btn btn-info listsubmit">Save Changes</button>
                                            @endif
                                        </div>

                                        <div class="tab-pane <?php active('services');?>" id="services" role="tabpanel">

                                            <div id="claim_row_1" class="row claimlink">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Services List - will appear on contact us form on Company page, allowing customer to specify their question.</label>
                                                        @php
                                                        $directory_services = [];
                                                        @endphp
                                                        @foreach ($data['directory_services'] as $val)
                                                        @php $directory_services[] = $val->name @endphp
                                                        @endforeach

                                                        <div class="col-md-12 border-bottom p-0">

                                                            <input type="text" data-role="tagsinput" id="tags_name_directory_services" value="{{ old('directory_services', implode(',',$directory_services) ) }}" placeholder="Add Services" class="form-control p-0 border-0">
                                                        </div>
                                                        <span class="text-danger">@error('directory_services'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr/>

                                            <input type="hidden" id="active-tab" class="active-tab" name="active_tab" value="">
                                            @if ($user->hasPermission('update') == true)
                                            <button type="submit" id="listsubmit-services" class="btn btn-info listsubmit">Save Changes</button>
                                            @endif
                                        </div>


                                        <div style="display:none;" class="tab-pane" id="finish" role="tabpanel">
                                            <div class="col-sm-12">
                                                <div class="text-center">
                                                    <h2 class="mt-0">
                                                        <i class="entypo-check"></i>
                                                    </h2>
                                                    <h3 class="mt-0">Thank you !</h3>
                                                    <p class="w-75 mb-2 mx-auto">You are almost there. Please check if you have provided all the necessary things.</p>
                                                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}">

                                                    <p> <button type="submit" class="btn btn-info listsubmit">update</button></p>

                                                </div>
                                            </div>
                                        </div>
                            </div>
                    </div>
                </div>


                </form>
            </div>
        </div>
    </div>
    <!-- Column -->
    </div>
    </div>
    </div>
</main>

<div class="modal fade" id="addCompanyCoverImageModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyCoverImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyCoverImageModalLabel">Add Company Cover Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="companyCoverImageUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="companyCoverImageUploadErrors" class="alert alert-danger" style="display: none;" role="alert"></div>
                    <div id="companyCoverImageUploadSuccess" class="alert alert-success" style="display: none;" role="status"></div>
                    <div class="form-group">
                        <label for="company-cover-image-file">Image File</label>
                        <input type="file" class="form-control" id="company-cover-image-file" name="image" accept="image/jpeg,image/png,image/gif,image/bmp,image/webp" required>
                    </div>
                    <div class="form-group">
                        <label for="company-cover-image-title">Title</label>
                        <input type="text" class="form-control" id="company-cover-image-title" name="title" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label for="company-cover-image-description">Description</label>
                        <textarea class="form-control" id="company-cover-image-description" name="description" rows="4" maxlength="5000" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="companyCoverImageUploadButton">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addCompanyGalleryImageTextModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyGalleryImageTextModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyGalleryImageTextModalLabel">Add Company Gallery Image with Text</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="companyGalleryImageTextUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="companyGalleryImageTextUploadErrors" class="alert alert-danger" style="display: none;" role="alert"></div>
                    <div id="companyGalleryImageTextUploadSuccess" class="alert alert-success" style="display: none;" role="status"></div>
                    <div class="form-group">
                        <label for="company-gallery-image-file">Image File</label>
                        <input type="file" class="form-control" id="company-gallery-image-file" name="image" accept="image/jpeg,image/png,image/gif,image/bmp,image/webp" required>
                    </div>
                    <div class="form-group">
                        <label for="company-gallery-image-title">Title</label>
                        <input type="text" class="form-control" id="company-gallery-image-title" name="title" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label for="company-gallery-image-description">Description</label>
                        <textarea class="form-control" id="company-gallery-image-description" name="description" rows="4" maxlength="5000" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="companyGalleryImageTextUploadButton">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#imgPreview').on('mouseenter focusin', '.thumbnail:has(.image-metadata-overlay)', function () {
            $('#imgPreview').addClass('metadata-popup-active');
        }).on('mouseleave focusout', '.thumbnail:has(.image-metadata-overlay)', function () {
            $('#imgPreview').removeClass('metadata-popup-active');
        });

        $('#listgallerypreview').on('mouseenter focusin', '.thumbnail:has(.image-metadata-overlay)', function () {
            $('#listgallerypreview').addClass('metadata-popup-active');
        }).on('mouseleave focusout', '.thumbnail:has(.image-metadata-overlay)', function () {
            $('#listgallerypreview').removeClass('metadata-popup-active');
        });

        var $form = $('#companyCoverImageUploadForm');
        var $errors = $('#companyCoverImageUploadErrors');
        var $success = $('#companyCoverImageUploadSuccess');
        var $uploadButton = $('#companyCoverImageUploadButton');

        $('#addCompanyCoverImageModal').on('show.bs.modal', function () {
            $errors.hide().text('');
            $success.hide().text('');
        });

        $form.on('submit', function (event) {
            event.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            $errors.hide().text('');
            $success.hide().text('');
            $uploadButton.prop('disabled', true).text('Uploading...');
            var uploadSucceeded = false;

            $.ajax({
                url: @json(route('company.cover-image.upload')),
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    uploadSucceeded = true;
                    $success.text(
                        response.message + ' Title: ' + response.image.title +
                        ' Description: ' + response.image.description
                    ).show();
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                },
                error: function (xhr) {
                    var message = 'The image could not be uploaded. Please try again.';
                    var response = xhr.responseJSON;

                    if (response && response.errors) {
                        var validationMessages = [];
                        $.each(response.errors, function (field, messages) {
                            $.each(messages, function (index, validationMessage) {
                                validationMessages.push(validationMessage);
                            });
                        });
                        message = validationMessages.join(' ');
                    } else if (response && response.message) {
                        message = response.message;
                    }

                    $errors.text(message).show();
                },
                complete: function () {
                    if (!uploadSucceeded) {
                        $uploadButton.prop('disabled', false).text('Upload');
                    } else {
                        $uploadButton.text('Uploaded');
                    }
                }
            });
        });

        var $galleryForm = $('#companyGalleryImageTextUploadForm');
        var $galleryErrors = $('#companyGalleryImageTextUploadErrors');
        var $gallerySuccess = $('#companyGalleryImageTextUploadSuccess');
        var $galleryUploadButton = $('#companyGalleryImageTextUploadButton');

        $('#addCompanyGalleryImageTextModal').on('show.bs.modal', function () {
            $galleryErrors.hide().text('');
            $gallerySuccess.hide().text('');
        });

        $galleryForm.on('submit', function (event) {
            event.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            $galleryErrors.hide().text('');
            $gallerySuccess.hide().text('');
            $galleryUploadButton.prop('disabled', true).text('Uploading...');
            var uploadSucceeded = false;

            $.ajax({
                url: @json(route('company.gallery-image-with-text.upload')),
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    uploadSucceeded = true;
                    $gallerySuccess.text(
                        response.message + ' Title: ' + response.image.title +
                        ' Description: ' + response.image.description
                    ).show();
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                },
                error: function (xhr) {
                    var message = 'The gallery image could not be uploaded. Please try again.';
                    var response = xhr.responseJSON;

                    if (response && response.errors) {
                        var validationMessages = [];
                        $.each(response.errors, function (field, messages) {
                            $.each(messages, function (index, validationMessage) {
                                validationMessages.push(validationMessage);
                            });
                        });
                        message = validationMessages.join(' ');
                    } else if (response && response.message) {
                        message = response.message;
                    }

                    $galleryErrors.text(message).show();
                },
                complete: function () {
                    if (!uploadSucceeded) {
                        $galleryUploadButton.prop('disabled', false).text('Upload');
                    } else {
                        $galleryUploadButton.text('Uploaded');
                    }
                }
            });
        });
    });
</script>

<script type="text/javascript">

    function initAutocomplete() {

        let Map = {
            mapContainer: document.getElementById('map')
            , inputAutocomplete: document.getElementById('pac-input')
            , inputLat: $("input[name=latitude]")
            , inputLng: $("input[name=longitude]")
            , inputCountry: $("input[name=country_id]")
            , inputState: $("input[name=state_id]")
            , inputzipcode: $("input[name=zipcode]")
            , inputCity: $("input[name=city_id]")
            , inputPlace: $("input[name=place]")
            , map: {}
            , geocoder: new google.maps.Geocoder()
            , autocomplete: {}
            , init: function() {
                let _this = this;

                this.autocomplete = new google.maps.places.Autocomplete(this.inputAutocomplete);

                let latitude = Number(this.inputLat.val());
                let longitude = Number(this.inputLng.val());
                let latLng = new google.maps.LatLng(0, 0);
                if (Number.isFinite(latitude) && Number.isFinite(longitude) && this.inputLat.val() !== '' && this.inputLng.val() !== '') {
                    latLng = new google.maps.LatLng(latitude, longitude);
                }

                this.map = new google.maps.Map(this.mapContainer, {
                    zoom: 15
                    , center: latLng
                });

                var marker = new google.maps.Marker({
                    position: latLng
                    , map: this.map
                    , title: 'Hello World!'
                    , draggable: true
                });

                marker.addListener('dragend', function() {
                    _this.inputLat.val(marker.getPosition().lat());
                    _this.inputLng.val(marker.getPosition().lng());
                    _this.geocodePosition(marker.getPosition());
                    _this.map.setCenter(marker.getPosition());
                })

                $('a[href="#location"]').on('shown.bs.tab', function() {
                    google.maps.event.trigger(_this.map, 'resize');
                    _this.map.setCenter(marker.getPosition());
                });

                this.autocomplete.addListener('place_changed', function() {
                    let place = _this.autocomplete.getPlace();
                    _this.inputLat.val(place.geometry.location.lat());
                    _this.inputLng.val(place.geometry.location.lng());

                    var pin = place.address_components[place.address_components.length - 1].long_name;
                    var country = place.address_components[place.address_components.length - 2].long_name;
                    var state = place.address_components[place.address_components.length - 3].long_name;
                    var city = place.address_components[place.address_components.length - 4].long_name;
                    var placel = place.address_components[2].long_name;

                    document.getElementById('country_id').value = country;
                    document.getElementById('state_id').value = state;
                    document.getElementById('city').value = city;
                    document.getElementById('zipcode').value = pin;
                    document.getElementById('place').value = placel;

                    let latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());

                    // create marker
                    let marker = new google.maps.Marker({
                        position: latlng
                        , map: _this.map
                        , draggable: true
                    });
                    _this.map.setCenter(latlng);

                    marker.addListener('dragend', function() {
                        //alert(marker);
                        _this.inputLat.val(marker.getPosition().lat());
                        _this.inputLng.val(marker.getPosition().lng());
                        _this.geocodePosition(marker.getPosition());
                        _this.map.setCenter(marker.getPosition());
                    })

                })
            }
            , geocodePosition: function(pos) {
                let _this = this;

                this.geocoder.geocode({
                    latLng: pos
                }, function(responses) {
                    if (responses && responses.length > 0) {
                        _this.updateMarkerAddress(responses[0].formatted_address);

                        var pin = responses[0].address_components[responses[0].address_components.length - 1].long_name;
                        var country = responses[0].address_components[responses[0].address_components.length - 2].long_name;
                        var state = responses[0].address_components[responses[0].address_components.length - 3].long_name;
                        var city = responses[0].address_components[responses[0].address_components.length - 4].long_name;
                        var place = responses[0].address_components[2].long_name;

                        document.getElementById('country_id').value = country;
                        document.getElementById('state_id').value = state;
                        document.getElementById('city').value = city;
                        document.getElementById('zipcode').value = pin;
                        document.getElementById('place').value = place;

                    } else {
                        _this.updateMarkerAddress('Nenhuma coordenada encontrada');
                    }
                });
            }
            , updateMarkerAddress: function(str) {
                this.inputAutocomplete.value = str;
            }
            , renderMap: function($el) {
                let _this = this;
                let $markers = $el.find('.marker');

                let args = {
                    zoom: 16
                    , center: new google.maps.LatLng(0, 0)
                    , mapTypeId: google.maps.MapTypeId.ROADMAP
                    , streetViewControl: false
                    , mapTypeControl: false
                };

                let map = new google.maps.Map($el[0], args);
                map.markers = [];
                $markers.each(function() {
                    _this.add_marker($(this), map);
                });

                _this.center_map(map);
            }
            , add_marker: function($marker, map) {
                let latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
                let marker = new google.maps.Marker({
                    position: latlng
                    , map: map
                });
                map.markers.push(marker);
            }
            , center_map: function(map) {
                let bounds = new google.maps.LatLngBounds();

                $.each(map.markers, function(i, marker) {
                    let latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                    bounds.extend(latlng);
                });

                if (map.markers.length == 1) {
                    map.setCenter(bounds.getCenter());
                    map.setZoom(16);
                } else {
                    map.fitBounds(bounds);
                }
            },

        };

        $(document).ready(function() {
            Map.init();
        });
    }


    $(document).ready(function(){


        $('form').on('submit', function () {
            $('#directory_services_hidden').val(
                $('#tags_name_directory_services').val()
            );
        });

        var exception_daystemplateselect_admin = $('#exception-daystemplateselect_admin').val();
        if(exception_daystemplateselect_admin != 'none'){
            $('.delete_default_exceptional_dv').hide();
        }

        $("#checkAll_gal").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        @if ($basicData->holiday_status == 'normal_holiday')
            $('#publicholidays').removeClass('schedule_active_tab');
            $('#exceptionalholidays').removeClass('schedule_active_tab');
            $('#normaldays').addClass('schedule_active_tab');
            $('.normal_days_div').show();
            $('.exceptional_days_div').hide();
            $('.public_days_div').hide();
            $('#holiday_status').val('normal_holiday');
        @endif
        @if($basicData->holiday_status == 'exceptional_holiday')
            $('#publicholidays').removeClass('schedule_active_tab');
            $('#normaldays').removeClass('schedule_active_tab');
            $('#exceptionalholidays').addClass('schedule_active_tab');
            $('.normal_days_div').hide();
            $('.exceptional_days_div').show();
            $('.public_days_div').hide();
            $('#holiday_status').val('exceptional_holiday');
        @endif

        @if($basicData->holiday_status == 'public_holiday')
            $('#exceptionalholidays').removeClass('schedule_active_tab');
            $('#normaldays').removeClass('schedule_active_tab');
            $('#publicholidays').addClass('schedule_active_tab');
            $('.normal_days_div').hide();
            $('.exceptional_days_div').hide();
            $('.public_days_div').show();
            $('#holiday_status').val('public_holiday');
        @endif

        $("#normaldays").click(function(){
            $('#publicholidays').removeClass('schedule_active_tab');
            $('#exceptionalholidays').removeClass('schedule_active_tab');
            $(this).addClass('schedule_active_tab');
            $('.normal_days_div').show();
            $('.exceptional_days_div').hide();
            $('.public_days_div').hide();
            $('#holiday_status').val('normal_holiday');
        });

        $("#exceptionalholidays").click(function(){
            $('#publicholidays').removeClass('schedule_active_tab');
            $('#normaldays').removeClass('schedule_active_tab');
            $(this).addClass('schedule_active_tab');

            $('.normal_days_div').hide();
            $('.exceptional_days_div').show();
            $('.public_days_div').hide();
            $('#holiday_status').val('exceptional_holiday');
        });

        $("#publicholidays").click(function(){
            $('#exceptionalholidays').removeClass('schedule_active_tab');
            $('#normaldays').removeClass('schedule_active_tab');
            $(this).addClass('schedule_active_tab');

            $('.normal_days_div').hide();
            $('.exceptional_days_div').hide();
            $('.public_days_div').show();
            $('#holiday_status').val('public_holiday');
        });

        var publicdaystemplate_selected_admin =$("#publicdaystemplateselect_admin").val();
        if(publicdaystemplate_selected_admin != 'none'){
            $.ajax({
                url: baseUrl + '/company/public/get-publictemplate-by-id',
                type: "POST",
                data: {
                    publicsele_id: publicdaystemplate_selected_admin,
                    type: 'admin',
                    _token: '{{csrf_token()}}'
                },
                dataType: 'html',
                success: function(result) {
                    $('#temp-publicdaysdetails-table').hide();
                    $('#public-template').remove();
                    $('#publicdays-table').after(result);
                }
            });
        }

        $("#publicdaystemplateselect_admin").change(function(){
            $('#publicdaystemplateselect').val('none');
        });

        $("#publicdaystemplateselect").change(function(){
            $('#publicdaystemplateselect_admin').val('none');
        });

        $("#exception-daystemplateselect_admin").change(function(){
            $('#exception-daystemplateselect').val('none');
            $('.editDataTemplateexception').hide();
        });

        $("#exception-daystemplateselect").change(function(){
            $('#exception-daystemplateselect_admin').val('none');
            $('.editDataTemplateexception').hide();
        });


        $('.select2').select2().on('select2:open', function (e) {
            $('.select2-container').addClass('notranslate');
        });

        $('#editsection').submit(function() {
        $('#loader').show();
        return true;
        });

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#checkAll2").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $('#deleteAllSelectedGal').click(function(e){

        e.preventDefault();
        var allids = [];
        $("input:checkbox[name=gal_ids]:checked").each(function(){
            allids.push($(this).val());
        });

        console.log('allids', allids);

        if(allids.length <= 0){
            toastr.error('Please select at least one record');
            return false;
        }else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            if(check==true){
                var selected_values = allids.join(",");
                $.ajax({
                    url: baseUrl + '/deleteCheckedListingGallery',
                    type: "get",
                    data: {
                        'ids': selected_values,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(data){
                        console.log(data.success);
                         if(data['success']){
                            toastr.success(data['success']);

                            setTimeout(function(){
                                location.reload();
                            }, 2000);

                        }else if(data['error']){
                            toastr.error(data['error']);
                        }else{
                            toastr.error("Something went wrong");
                        }
                    }
                });
                $.each(allids, function(key,val){
                    //$('table tr').filter("[data-row-id='" + val + "']").remove();
                });
            }
        }
    });

    $('#deleteAllSelectedProducts').click(function(e){
        e.preventDefault();
        var allids = [];
        $("input:checkbox[name=ids]:checked").each(function(){
            allids.push($(this).val());
        });

        if(allids.length <= 0){
            toastr.error('Please select at least one record');
            return false;
        }else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            if(check==true){
                var selected_values = allids.join(",");
                $.ajax({
                    url: baseUrl + '/selectedgallerytypedelete',
                    type: "get",
                    data: {
                        'ids': selected_values,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(data){
                        console.log(data.success);
                         if(data['success']){
                            toastr.success(data['success']);

                            setTimeout(function(){
                                location.reload();
                            }, 2000);

                        }else if(data['error']){
                            toastr.error(data['error']);
                        }else{
                            toastr.error("Something went wrong");
                        }
                    }
                });
                $.each(allids, function(key,val){
                    //$('table tr').filter("[data-row-id='" + val + "']").remove();
                });
            }
        }
    });

    $('#deleteAllSelectedPdf').click(function(e){
        e.preventDefault();
        var allids = [];
        $("input:checkbox[name=ids]:checked").each(function(){
            allids.push($(this).val());
        });

        if(allids.length <= 0){
            toastr.error('Please select at least one record');
            return false;
        }else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            if(check==true){
                var selected_values = allids.join(",");
                $.ajax({
                    url: baseUrl + '/selectedpdftypedelete',
                    type: "get",
                    data: {
                        'ids': selected_values,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(data){
                        console.log(data.success);
                         if(data['success']){
                            toastr.success(data['success']);

                            setTimeout(function(){
                                location.reload();
                            }, 2000);

                        }else if(data['error']){
                            toastr.error(data['error']);
                        }else{
                            toastr.error("Something went wrong");
                        }
                    }
                });
                $.each(allids, function(key,val){
                    //$('table tr').filter("[data-row-id='" + val + "']").remove();
                });
            }
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Get the token from the meta tag
        }
    });

            $("#e_verification").click(function(event) {
                event.preventDefault();
                var register_email = $('#email').val();
              //alert(register_phone);
                $('#e_verification').prop('disabled', true);
                $('#e_verification').html('Wait......');
                axios.post('/sendcompany-emailotp', {
                        email: register_email,
                    })
                    .then(function(response) {
                        if (response.data.status == 'error') {
                            //toastr.error(response.data.msg);
                            var myObj = response.data.error;
                            $.each(myObj, function(key, val) {
                                //swal("error!", val[0], "error");br
                                toastr.error('Error', val[0]);
                            });
                            $('#e_verification').prop('disabled', false);
                            $('#e_verification').html('Send Verification ');
                            $("#otp_div").hide();
                        } else {
                            toastr.success(response.data.msg);

                            $("#otp_div").show();
                            $("#e_veifyotp").show();
                            $("#e_verification").hide();
                            $('#e_verification').prop('disabled', false);
                            $('#e_veifyotp').html('Verify OTP');
                        }
                    })
                    .catch(function(error) {});
                return false;
            });

            $("#e_veifyotp").click(function(event) {
                event.preventDefault();
                $('#e_veifyotp').prop('disabled', true);
                $('#e_veifyotp').html('Wait......');

                var otp = $("#otp").val();
                var email = $("#email").val();
                axios.post('/verify-cotp ', {
                        otp: otp,
                        email: email,
                    })
                    .then(function(response) {
                        // $("#user"+user).html(response.data.bal);
                        if (response.data.status == '201') {
                            var myObj = response.data.error;
                            $.each(myObj, function(key, val) {
                                // swal("error!", val[0], "error");br
                                toastr.error('Error', val[0]);
                                //$('#' + key).css('border', '1px solid #f27863');
                            });
                            $('#e_veifyotp').prop('disabled', false);
                            $('#e_veifyotp').html('Get OTP');

                        } else {

                            $('#e_veifyotp').prop('disabled', false);
                            $('#e_veifyotp').html('Get OTP');
                            if (response.data.isSuccess == 'true') {
                                $('#email').prop('readonly', true);
                                $('#otp').prop('readonly', true);
                                $('#otp_div').hide();
                                window.verify = 1;
                                toastr.success('Email id updated successfully!');
                                $('#e_veifyotp').css('border', '1px solid green');
                                $('#e_veifyotp').css('color', '#128807');
                                $('#e_veifyotp').html('Verified');
                                //$('#verified_status').val('yes');
                                $('#e_veifyotp').prop('disabled', true);
                            } else {
                                toastr.error('Invalid Otp');
                            }
                        }
                    }).catch(function(error) {});
                return false;
            });


            $('#exceptional_impt_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#exceptional_impt_file')[0].files[0];
                var directoryId = $('#directoryId').val();

                if (!fileInput) {
                    alert('Please select a file to import.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('exceptional_file', fileInput);
                formData.append('directoryId', directoryId);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.exceptional.holiday") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        $('#exceptional_upload_status').html('<span class="text-success">Exceptional holidays File imported successfully!</span>');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        $('#exceptional_upload_status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });

            $('#public_impt_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#public_impt_file')[0].files[0];
                var directoryId = $('#directoryId').val();

                if (!fileInput) {
                    alert('Please select a file to import.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('public_file', fileInput);
                formData.append('directoryId', directoryId);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.public.holiday") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        $('#upload-status').html('<span class="text-success">Public holidays File imported successfully!</span>');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        $('#upload-status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });

            $('#public_templateimpt_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#public_template_impt_file')[0].files[0];
                var directoryId = $('#directoryId').val();
                var template_id = $('#publicdaystemplateselect').val();

                if (template_id == 'none') {
                    alert('Please select vendor template first.');
                    return;
                }
                if (!fileInput) {
                    alert('Please select a file to import.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('public_file', fileInput);
                formData.append('template_id', template_id);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.holidayempinner") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        $('#template_upload-status').html('<span class="text-success">Public holidays File imported successfully!</span>');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        $('#template_upload-status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });

            $('#exeptional_templateimpt_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#exeptional_template_impt_file')[0].files[0];
                var directoryId = $('#directoryId').val();
                var template_id = $('#exception-daystemplateselect').val();

                if (template_id == 'none') {
                    alert('Please select vendor template first.');
                    return;
                }
                if (!fileInput) {
                    alert('Please select a file to import.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('public_file', fileInput);
                formData.append('template_id', template_id);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.exceptional.holidayempinner") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        $('#exeptional_template_upload-status').html('<span class="text-success">Exeptional holidays File imported successfully!</span>');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        $('#exeptional_template_upload-status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });


            $('#public_template_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#public_template_name').val();
                var directoryId = $('#directoryId').val();
                var createdby = $('#createdby').val();

                if (!fileInput) {
                    alert('Template name is required.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('public_template_file_name', fileInput);
                formData.append('directoryId', directoryId);
                formData.append('createdby', createdby);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.holidaytemp.name") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        //$('#template_upload-status').html('<span class="text-success">Public template saved successfully!</span>');
                        alert('Public template saved successfully!');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error. Please try again');
                        //$('#template_upload-status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });

            $('#exceptional_template_name_btn').click(function(e){
                e.preventDefault();
                // Get the file input
                var fileInput = $('#exceptional_template_name').val();
                var directoryId = $('#directoryId').val();
                var createdby = $('#createdby').val();

                if (!fileInput) {
                    alert('Exceptional Template name is required.');
                    return;
                }
                // Create FormData object
                var formData = new FormData();
                formData.append('exceptional_template_file_name', fileInput);
                formData.append('directoryId', directoryId);
                formData.append('createdby', createdby);
                // AJAX request to upload file
                $.ajax({
                    url: '{{ route("vendor.import.exceptional.temp.name") }}', // Update with your server-side URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for file uploads
                    contentType: false,  // Required for file uploads
                    success: function(response) {
                        //$('#template_upload-status').html('<span class="text-success">Public template saved successfully!</span>');
                        alert('Exceptional template saved successfully!');
                        setTimeout(function(){
                            window.location.href = '/company-information?target=schedule';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error. Please try again');
                        //$('#template_upload-status').html('<span class="text-danger">Error importing file. Please try again.</span>');
                        console.error(error);
                    }
                });
            });

        $(".show_more").click(function () {
            if($(".listgallerypreview").hasClass("show-more-height")) {
                $(this).text("Less");
            } else {
                $(this).text("More");
            }

            $(".listgallerypreview").toggleClass("show-more-height");
        });


        $('#edit_exception_table').hide();
        var edit_checkboe_exception = $('input[name=editDataexception]:checked').val();

        $("input[name=editDataexception]").change(function() {
            if ($('input[name=editDataexception]:checked').is(':checked')) {
                console.log("Check edit box value fiest one equal ");
                $('#edit_exception_table').show();
            } else {
                console.log("Check edit box value not equal ");
                $('#edit_exception_table').hide();
            }
        });

        $('#edit_template_exception_table').hide();
        var edit_tempalteCheckbox_exception = $('input[name=editDataTemplateexception]:checked').val();

        $("input[name=editDataTemplateexception]").change(function() {
            if ($('input[name=editDataTemplateexception]:checked').is(':checked')) {
                $('#edit_template_exception_table').show();
            } else {
                $('#edit_template_exception_table').hide();
            }
        });

        $('#edit_template_public_table').hide();
        var edit_tempalteCheckbox_exception = $('input[name=editDataTemplatepublic]:checked').val();

        $("input[name=editDataTemplatepublic]").change(function() {
            if ($('input[name=editDataTemplatepublic]:checked').is(':checked')) {
                $('#edit_template_public_table').show();
            } else {
                $('#edit_template_public_table').hide();
            }
        });

        $('#edit_public_table').hide();
        var edit_tempalteCheckbox_exception = $('input[name=editDataPublic]:checked').val();

        $("input[name=editDataPublic]").change(function() {
            if ($('input[name=editDataPublic]:checked').is(':checked')) {
                $('#edit_public_table').show();
            } else {
                $('#edit_public_table').hide();
            }
        });

    });

    function getSubCategory(cat_id) {

        var count_category = $("#par-category").val().length;
        var count_category_permision = {{ $getCategoryValue }};
        if(count_category_permision < count_category){
            toastr.error('Please upgrade member account. you can not add category greater than ' + count_category_permision);
            $("#par-category").val(null).trigger('change');
            return false;
        }

        $("#subcategory").html('');
        $("#par-tags").html('');

        $.ajax({
            url: baseUrl + '/get-subcategories-by-categoryid',
            type: "POST",
            data: { category_id: cat_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {
                $.each(result.subcat, function(key, value) {
                    $("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
                    //$('.select2-selection__rendered').addClass('notranslate');
                });
                //$('.select2-dropdown').addClass('notranslate');
            }
        });
    }

    function getTrademarkOptions(selcat) {
        var selobj = selcat.value;
        $("#par-trademark").html('');
        $.ajax({
            url: baseUrl + '/company/get-trademarks-by-categoriesid',

            type: "POST"
            , data: {
                category_id: selobj
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                $.each(result.trademark, function(key, value) {
                    $("#par-trademark").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    }

    function getSelectedAmenityOptions(sel) {
        var opts = [], opt;
        var len = sel.options.length;

        var amenityCount = $('#amenitycatcount').val();

            for (var i = 0; i < len; i++) {
                opt = sel.options[i];

                if (opt.selected) {
                    opts.push(opt.value);
                }
                console.log("opts : ", opts);
                console.log("length : ", opts.length);


            }
            if(opts.length > amenityCount){
                $('#amenitycat-dropdown option:selected').remove();
                $(".amenitiesdata").html("");
                window.setTimeout(function(){location.reload()},3000);
                toastr.error('Please upgrade member account. you can not add amenity category greater than ' + amenityCount);
                return false;
            }

            $(".amenitiesdata").html("");
            // return opts;
            if (opts.length != 0) {
                $.ajax({
                    url: baseUrl + '/company/get-amenity-by-amenitycategory',

                    type: "POST"
                    , data: {
                        amcat_id: opts
                        , _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                            $.each(result, function(key, value) {
                                $(".amenitiesdata").append(`<div class="amenitycategories_name"><span>`+key+`</span></div>`);
                                $.each(value, function(key, val) {

                                    $(".amenitiesdata").append(`<div class="col-md-3 p-10">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="form-check-input" name="amenities[]" id="` + val.id + `" value="` + val.id + `">
                                                                    <label class="custom-control-label" for="1"><i class="` + val.icon_class + `" style="color: #636363 !important;"></i> ` + val.title +`</label>
                                                                </div>
                                                            </div>
                                                    `);
                                });

                            });

                    }
                });
            }

    }

    function getNormalTemplateOption(teplateId) {
        $.ajax({
            url: baseUrl + '/company/normal/get-normaltemplate-by-id',

            type: "POST",
            data: {
                teplate_id: teplateId,
                _token: '{{csrf_token()}}'
            },
            dataType: 'html',
            success: function(result) {
                //console.log(result);
                $('#nomaldays-template-table').remove();
                $('#normaldays-template').after(result);

            }
        });
    }

    function getpublicTemplateOption(publicseleid,type){

        $.ajax({
            url: baseUrl + '/company/public/get-publictemplate-by-id',

            type: "POST",
            data: {
                publicsele_id: publicseleid,
                type: type,
                _token: '{{csrf_token()}}'
            },
            dataType: 'html',
            success: function(result) {
                $('#temp-publicdaysdetails-table').hide();
                $('#public-template').remove();
                $('#publicdays-table').after(result);

            }
        });
    }

    function getExceptionTemplateOption(exceptionseleid,type){
        $.ajax({

            url: baseUrl + '/company/exception/get-exceptiontemplate-by-id',
            type: "POST",
            data: {
                exceptionsele_id: exceptionseleid,
                type:type,
                _token: '{{csrf_token()}}'
            },
            dataType: 'html',
            success: function(result) {
                //console.log(result);
                $('#temp-exceptiondaysdetails-table').hide();
                $('#exception-template').remove();
                $('#exceptiondays-table').after(result);

            }
        });
    }


    $(document).ready(function() {

        $("#loader").fadeOut("slow");
        $(".select2").select2({
            placeholder: "Select Option",
            //dropdownCssClass: "notranslate",
            //allowClear: true
        });

        var normaldaysselectvalue = $('input[name=normaldays]:checked', '#editsection').val();
        var publicselectvalue = $('input[name=publicdays]:checked', '#editsection').val();
        var exception_daysselectvalue = $('input[name=exception_days]:checked', '#editsection').val();

        if (normaldaysselectvalue != "yes") {
            $('#normaldays-template').hide();
            $('#nomaldays-template-table').hide();
            $('#nomaldays-table').show();
            $('#normaldaytemplateselect').val("none");
        } else {
            $('#normaldays-template').show();
            $('#nomaldays-template-table').show();
            $('#nomaldays-table').hide();
        }
        if (publicselectvalue != "yes") {
            $('#publicdays-template').hide();
            $('#publicdays-table').show();
            $('#publicdaysdetails-table').show();
            $('#temp-publicdaysdetails-table').hide();
            $('#publicdaystemplateselect').val("none");
        } else {
            $('#publicdays-template').show();
            $('#publicdays-table').hide();
            $('#publicdaysdetails-table').hide();
            $('#temp-publicdaysdetails-table').show();
        }
        if (exception_daysselectvalue != "yes") {
            $('#exception_days-template').hide();
            $('#exceptiondays-table').show();
            $('#temp-exceptiondaysdetails-table').hide();
            $('#exception-daystemplateselect').val("none");
        } else {
            $('#exception_days-template').show();
            $('#exceptiondays-table').hide();
            $('#exceptiondaysdetails-table').hide();
            $('#temp-exceptiondaysdetails-table').show();
        }
    });

    var public_h_radio_val = $('.public_h_radio').val();
       if(public_h_radio_val == 'yes'){
            $('.public_h_checkbox').hide();
            $('#edit_public_table').hide();
       }else{
        $('.public_h_checkbox').show();
        $('#edit_public_table').show();
       }

    $(".public_h_radio").change(function() {

       var public_h_radio_val = $(this).val();
       if(public_h_radio_val == 'yes'){
            $('.public_h_checkbox').hide();
            $('#edit_public_table').hide();
       }else{
        $('.public_h_checkbox').show();
        $('#edit_public_table').show();
       }

    });

    $("input[name=normaldays]").change(function() {

        if ($(this).val() != "yes") {
            $('#normaldays-template').hide();
            $('#nomaldays-template-table').hide();
            $('#nomaldays-table').show();
            $('#normaldaytemplateselect').val("none");
            $('#nomaldays-template-table').remove();
        } else {
            $('#normaldays-template').show();
            $('#nomaldays-template-table').show();
            $('#nomaldays-table').hide();
        }

    });

    $("input[name=publicdays]").change(function() {

        if ($(this).val() != "yes") {
            $('#publicdays-template').hide();
            $('#publicdays-table').show();
            $('#publicdaysdetails-table').show();
            $('#temp-publicdaysdetails-table').hide();
            $('#public-template').hide();
            $('#publicdaystemplateselect').val("none");
        } else {
            $('#publicdays-template').show();
            $('#publicdays-table').hide();
            $('#publicdaysdetails-table').hide();
            $('#temp-publicdaysdetails-table').show();
        }

    });

    $("input[name=exception_days]").change(function() {
        if ($(this).val() != "yes") {
            $('#exception_days-template').hide();
            $('#exceptiondays-table').show();
            $('#exceptiondaysdetails-table').show();
            $('#temp-exceptiondaysdetails-table').hide();
            $('#exception-daystemplateselect').val("none");
            $('#exception-template').remove();

            $('.exceptional_holidays_checkbox').show();
            $('#edit_exception_table').show();

        } else {

            $('.exceptional_holidays_checkbox').hide();
            $('#edit_exception_table').hide();

            $('#exception_days-template').show();
            $('#exceptiondays-table').hide();
            $('#exceptiondaysdetails-table').hide();
            $('#temp-exceptiondaysdetails-table').show();
        }
    });



    function changeSimpletags(sel) {

        var count_tags = $("#par-tags").val().length;
        var count_tags_permision = {{ $getTagsValue }};
        if(count_tags_permision < count_tags){
            toastr.error('Please upgrade member account. you can not add tags greater than ' + count_tags_permision);
            $("#par-tags").val(null).trigger('change');
            return false;
        }

    }

    function getSelectedOptions(sel) {

        var count_subcategory = $("#subcategory").val().length;
        var count_subcategory_permision = {{ $getsubCategoryValue }};
        if(count_subcategory_permision < count_subcategory){
            toastr.error('Please upgrade member account. you can not add sub category greater than ' + count_subcategory_permision);
            $("#subcategory").val(null).trigger('change');
            $("#par-tags").val(null).trigger('change');
            return false;
        }

        var opts = [], opt;
        var len = sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = sel.options[i];
            if (opt.selected) {
                opts.push(opt.value);
            }
        }
        var selectedvalueTags = $("#par-tags").val();
        var selectedvalueTagsqwer = [];
        for(var k=0;k<selectedvalueTags.length;k++){
            selectedvalueTagsqwer.push(parseInt(selectedvalueTags[k]));
        }

        if (opts.length != 0) {
            $.ajax({
                url: baseUrl + '/get-dirtags-by-subcategoriesid',

                type: "POST"
                , data: {
                    category_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log(result);
                    $("#par-tags").html("");
                    var valuetags = "";

                    $.each(result.tags, function(key, value) {
                        if(jQuery.inArray(value.id,selectedvalueTagsqwer) == -1){
                            valuetags = "";
                        }else{
                            valuetags = "selected";
                        }
                        $("#par-tags").append('<option value="' + value.id + '"'+valuetags+'>' + value.tags + '</option>');
                        $("#par-tags").append('<option value="aa">aa</option>');
                    });

                }
            });
        }

    }

    $(document).on('click', ".deleterow", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = "#tblholi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/delete-holiday-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
            //console.log('Thing was saved to the database.');
        }
    });

    $(document).on('click', ".delete_no_exceptional", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = ".no_exceptional_holi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/delete-exceptional-holiday-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
            //console.log('Thing was saved to the database.');
        }
    });

    $(document).on('click', ".delete_yes_public", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = ".tblholi_yes_public_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/delete-public-holiday-yes-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
        }
    });

    $(document).on('click', ".delete_yes_exceptional", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = ".delete_yes_exceptional_tr_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/delete-exceptional-holiday-yes-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
            //console.log('Thing was saved to the database.');
        }
    });

    $(document).on('click', ".delete_default_exceptional", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = ".default_exceptional_holi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/delete-exceptional-holiday-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
            //console.log('Thing was saved to the database.');
        }
    });

    $(function() {
        $('.icon-picker').iconpicker();
    });

    $(document).ready(function(e) {

        var payble = $('input[name=payimage]:checked', '#editsection').val();
        $("input[name=payimage]").change(function() {
            // $("#multi-thumbnail-img").val(null);
            // $("#cover-img").val(null);
            // $("#gallist").val(null);
            // $('.existing-img').remove();
            // $('.previewBlocksNew').hide();
            if ($(this).val() != "payable") {
                // $('.previewBlocks').hide();
                // $('#multi-thumbnail-img').prop("multiple", false);
                // $('#cover-img').prop("multiple", false);
                // $('#gallist').prop("multiple", false);
            } else {
                // $('.previewBlocks').show();
                // $('#preview-thumbnail-img').show();
                // $('#preview-cover-img').show();
                // $('#preview-gal-img').show();
                // $('#multi-thumbnail-img').prop("multiple", true);
                // $('#cover-img').prop("multiple", true);
                // $('#gallist').prop("multiple", true);
            }

        });


        $('#thumbnail-img').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-thumbnail-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on('click', ".closebtn", function() {

            if(confirm("Are you sure you want to delete this?")){

                var val = $(this).closest(".thumbnail");
                var imageid = $(this).closest(".thumbnail").find('img').attr('data-id');
                var imagevalue = $(this).closest(".thumbnail").find('img').attr('data-value');
                if (imagevalue == "gal") {
                    imagevalue = '/company/get-gallery-img';
                } else if (imagevalue == "cover") {
                    imagevalue = '/company/get-cover-img';
                } else if (imagevalue == "thumb") {
                    imagevalue = '/company/get-thumb-img';
                } else {
                    imagevalue = "";
                }

                $.ajax({
                    url: baseUrl + imagevalue
                    , type: "POST"
                    , data: {
                        id: imageid
                        , _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        if (result == true) {
                            val.remove();
                        }
                        setTimeout(function(){
                        window.location.href = baseUrl + "/company-information?target=media";
                        }, 1000);
                    }
                });


            }else{
                return false;
            }

            // alert(imagevalue);
            $.ajax({
                url: baseUrl + imagevalue
                , type: "POST"
                , data: {
                    id: imageid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    if (result == true) {
                        val.remove();
                    }
                    setTimeout(function(){
                    window.location.href = baseUrl + "/company-information?target=media";
                    }, 1000);
                }
            });
        });

        $(document).on('click', ".delete_all_gallery", function() {
            if (!confirm('Are you sure you want to delete all gallery images?')) {
                e.preventDefault();
                return false;
           }
            var directory_id =  {{isset($mediaData[0]->directory_id)? $mediaData[0]->directory_id : ""}};
            galleryimageurl = '/company/gallery-image-delete-all';
            $.ajax({
                url: baseUrl + galleryimageurl
                , type: "POST"
                , data: {
                    directory_id: directory_id
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                     console.log(result);
                    if (result) {
                        toastr.success('Gallery has been deleted');
                        $('.delete_all').hide();
                        $('.listgallerypreview').html('<img src="{{ asset('assets/plugins/images/placeholder.png') }}" width="70px" height="70px" class="previewBlocks" />');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }else{
                        toastr.error('Something went wrong');
                    }

                }
            });
        });
    });

</script>

@php
    $getFeatureUsesValue = Helper::getFeatureUsesValue('listing-thumbnail-and-listing-cover');
    $getFeatureGalleryValue = Helper::getFeatureUsesValue('media');
    $getFeatureLinksValue = Helper::getFeatureUsesValue('links');
@endphp

<script>

        function type_galley_img(key) {

            var files = document.getElementById('type_galley_img_'+key).files;
            if (files.length > 0) {
                $('#gallery_type_pdf_'+key).prop('disabled', true);
                $('#type_galley_img_'+key).prop('disabled', false);
            }else{
                $('#gallery_type_pdf_'+key).prop('disabled', false);
                $('#type_galley_img_'+key).prop('disabled', false);
            }
            var maxFilesAllowed = 10;
            if (files.length > maxFilesAllowed) {
                $('.listsubmit').prop('disabled', true);
                toastr.error('You can upload a maximum of ' + maxFilesAllowed + ' files at a time.');
            }else{
                $('.listsubmit').prop('disabled', false);
                var fileslength = $("#type_galley_img_"+key)[0].files.length;
                $('#count_uploaded_gallery_'+key).html(fileslength);
            }
        }

        function gallery_type_pdf(key) {

            var fileInput = document.getElementById('gallery_type_pdf_' + key);
            if (fileInput && fileInput.files) {
                var filesCount = fileInput.files.length;

                if (filesCount > 0) {
                    $('#count_gallery_type_pdf_'+key).show();
                    $('#gallery_type_pdf_'+key).prop('disabled', false);
                    $('#type_galley_img_'+key).prop('disabled', true);
                }else{
                    $('#count_gallery_type_pdf_'+key).hide();
                    $('#gallery_type_pdf_'+key).prop('disabled', false);
                    $('#type_galley_img_'+key).prop('disabled', false);
                }
                console.log(filesCount);
                $('#count_gallery_type_pdf_'+key).html(filesCount);
            }
        }



    $(function() {

       var getFeatureUsesValue = {{ $getFeatureUsesValue }};
       var getFeatureGalleryValue = {{ $getFeatureGalleryValue }};

        var multiImgPreview = function(input, imgPreviewPlaceholder, position, positionId, type) {

            var countFiles = input.files.length;
            var imgPath = input.value;
            var k = position;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var imgPreviewPlaceholder = $(imgPreviewPlaceholder);

            $(".imageuploadify-overlay" + type).css("display", "block");
                    for (var i=0; i<input.files.length; i++) {
                        var ext = input.files[i].name.substr(-3);
                        if(ext!== "mp4" && ext!== "m4v" && ext!== "fv4" && ext!== "MOV" && ext!== "WMV" && ext!== "HTML5" && ext!== "AVI")  {

                            if (typeof(FileReader) != "undefined") {
                            var total = k - 1;
                            var reader = new FileReader();
                            reader.onload = function(event) {
                                total += 1;

                                $("<div class=\"thumbnail newimage ui-state-default ui-sortable-handle\" data-position=" + total + ">" +
                                    "<img src=\"" + event.target.result + "\" width=\"100px\" height=\"100px\" />" +
                                    "<br/><span class=\"closebtn\"><i class=\"fas fa-trash\" style=\"color:#ff0505; \" aria-hidden=\"true\"></i></span>" +
                                    "</div>").appendTo(imgPreviewPlaceholder);
                                $(".closebtn").click(function() {
                                    $(this).parent(".thumbnail").remove();
                                });

                                i++;
                            }
                            setTimeout(() => {
                                $(positionId).val(i - 1);
                                $(".imageuploadify-overlay" + type).css("display", "none");
                            }, 1000);
                            imgPreviewPlaceholder.show();
                            reader.readAsDataURL(input.files[i]);
                             } else {
                                alert("This browser does not support FileReader.");
                                $(".imageuploadify-overlay" + type).css("display", "none");
                            }
                        } else {
                            alert("Videos files like mp4, m4v, fv4 ... are not accepted");
                            $(".imageuploadify-overlay" + type).css("display", "none");
                        }
                    }
        };

        $("#fileUpload").on('change', function() {
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-holder");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $("<img />", {
                                "src": e.target.result
                                , "class": "thumb-image"
                            }).appendTo(image_holder);
                        }
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }
                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });


        $('#multi-thumbnail-img').on('change', function() {
            var fileslength = $("#multi-thumbnail-img")[0].files.length;
            if(getFeatureUsesValue < fileslength){
                toastr.error('Please upgrade member account. you can not upload files greater than ' + getFeatureUsesValue);
                return false;
            }
            if (fileslength > 0) {
                alert("Click on 'Save' button after each upload");
                    let offset = $('#listsubmit-media').offset().top - 50; // Adjust -50 to match header height
                    $('html, body').animate({
                        scrollTop: offset
                    }, 1000);
            }
            var payble = $('input[name=payimage]:checked', '#editsection').val();
            if (payble != 'payable') {
                if ($("#multi-thumbnail-img")[0].files.length > 1) {
                    $("#multi-thumbnail-img").val(null);
                    alert("You can select only 1 images");
                    return;
                }
            }
            var position = $('#lastpositionthumbValue').val();
            console.log("POSITION: ", position);
            $("#preview-thumbnail-img").hide();
            position = position ? position : 0;
            multiImgPreview(this, 'div.thumbnailimgPreview', parseInt(position) + 1, '#lastpositionthumbValue', '');
        });

        $('#cover-img').on('change', function() {
            var fileslength = $("#cover-img")[0].files.length;
            if (fileslength > 0) {
                alert("Click on 'Save' button after each upload");
                let offset = $('#listsubmit-media').offset().top - 50; // Adjust -50 to match header height
                $('html, body').animate({
                    scrollTop: offset
                }, 1000);
            }
                if(getFeatureUsesValue < fileslength){
                    toastr.error('Need to upgrade for more images');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                    return false;
                }
            var payble = $('input[name=payimage]:checked', '#editsection').val();
            if (payble != 'payable') {
                if ($("#cover-img")[0].files.length > 1) {
                    $("#cover-img").val(null);
                    alert("You can select only 1 images");
                    return;
                }
            }
            var position = $('#lastpositioncoverValue').val();
            $("#preview-cover-img").hide();
            position = position ? position : 0;

            var imagepre = $('#imgPreview .existing-img').length+1;
            var imagepre1 = $('#imgPreview .newimage').length;
            if (getFeatureUsesValue < imagepre+imagepre1) {
                toastr.error('Need to upgrade for more images');
                setTimeout(function () {
                    location.reload();
                }, 2000);
                return false;
            }
            multiImgPreview(this, 'div.imgPreview', parseInt(position) + 1, '#lastpositionthumbValue', 2);
        });

        $('#gallist').on('change', function() {
            var fileslength = $("#gallist")[0].files.length;
            if (fileslength > 0) {
                alert("Click on 'Save' button after each upload");
            }
            if(getFeatureGalleryValue < fileslength){
                toastr.error('Need to upgrade for more images');
                setTimeout(function () {
                    location.reload();
                }, 2000);
                return false;
            }
            var payble = $('input[name=payimage]:checked', '#editsection').val();
            if (payble != 'payable') {
                if ($("#gallist")[0].files.length > 1) {
                    $("#gallist").val(null);
                    alert("You can select only 1 images");
                    return;
                }
            }
            var position = $('#lastpositiongallValue').val();
            $("#preview-gal-img").hide();
            position = position ? position : 0;

            var imagepre = $('#listgallerypreview .existing-img').length+1;

            var imagepre1 = $('#listgallerypreview .newimage').length;

            if (getFeatureGalleryValue < imagepre+imagepre1) {
                toastr.error('Need to upgrade for more images');
                setTimeout(function () {
                    location.reload();
                }, 2000);
                return false;
            }
            multiImgPreview(this, 'div.listgallerypreview', parseInt(position) + 1, '#lastpositiongallValue', '');
        });

    });

    $(document).ready(function() {
        $("#imgPreview,#thumbnailimgPreview,#listgallerypreview").sortable();

        $('#helpiconText').show();

        $('#type_gallery_list').sortable({
            update: function(event, ui) {
            //console.log("event - ",event); console.log("ui - ",ui);

            var innerDivs = $('#type_gallery_list .gallerytypeimages');
            var gallery_typeimages = [];
            var gallery_position = [];
            var gallery_type_id = [];
            innerDivs.each(function() {
                gallery_type_id.push($(this).data('id'));
                gallery_typeimages.push($(this).data('gallerytypeimages'));
                gallery_position.push($(this).data('position'));
            });
            console.log('data_gallery_type_id --', gallery_type_id);
            console.log('data_gallery_typeimages --', gallery_typeimages);
            console.log('data_gallery_position - ', gallery_position);
            //var specificDataId = innerDivs.eq(1).data('id');

                $.ajax({
                    url: baseUrl + '/update-directory-type-galleries-sort',
                    type: "POST",
                    data: {
                        gallery_type_id: gallery_type_id,
                        gallery_typeimages: gallery_typeimages,
                        gallery_position: gallery_position,
                        _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        if(result.type == 'success'){
                            toastr.success(result.message);
                        }else{
                            toastr.error(result.message);
                        }
                    }
                });

            }
        });

        var payble = $('input[name=payimage]:checked', '#directoryForm').val();
        $("input[name=payimage]").change(function() {
            $("#multi-thumbnail-img").val(null);
            $("#cover-img").val(null);
            $("#gallist").val(null);
            $('.newimage ').remove();
            $('.existing-img').remove();
        });
    });

    function submitForm() {
        var image_thumb_arr_exist = [];
        var image_thumb_arr_new = [];
        var image_cover_arr = [];
        var image_gal_arr = [];
        var image_trade_arr = [];
        // get image ids order listsubmit
        $('#thumbnailimgPreview div').each(function() {
            if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
                var id = $(this).data('position');
                image_thumb_arr_new.push(id);
                $("#listing-thumb-array-new").val(image_thumb_arr_new);
            }
        });
        var thumbimages = [];
        $('.thumbnailimgPreview div').each(function() {
            thumbimages.push($(this).data('thumbimages'));
        });
        $('#thumb_hidden_img').val(thumbimages.join(','));
        $('#imgPreview div').each(function() {
            if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
                var id = $(this).data('position');
                image_cover_arr.push(id);
                $("#listing-cover-array").val(image_cover_arr);
            }
        });
        var coverimg = [];
        $('.imgPreview div').each(function() {
            coverimg.push($(this).data('coverimages'));
        });
        $('#listing-cover-arrayimage').val(coverimg.join(','));
        $('#listgallerypreview div').each(function() {
            if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
                var id = $(this).data('position');
                image_gal_arr.push(id);
                $("#listing-gallery-array").val(image_gal_arr);
            }
        });
        var gallerypreview = [];
        $('.listgallerypreview div').each(function() {
            gallerypreview.push($(this).data('galimages'));
        });
        $('#listing-gallery-arrayimage').val(gallerypreview.join(','));
        return;
    }

    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    var coverloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-cover-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    var galleryloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-gal-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>

<script type="text/javascript">

    // function initAutocomplete() {

    //     let Map = {
    //         mapContainer: document.getElementById('map')
    //         , inputAutocomplete: document.getElementById('pac-input')
    //         , inputLat: $("input[name=latitude]")
    //         , inputLng: $("input[name=longitude]")
    //         , inputCountry: $("input[name=country_id]")
    //         , inputState: $("input[name=state_id]")
    //         , inputzipcode: $("input[name=zipcode]")
    //         , inputCity: $("input[name=city_id]")
    //         , inputPlace: $("input[name=place]")
    //         , map: {}
    //         , geocoder: new google.maps.Geocoder()
    //         , autocomplete: {}
    //         , init: function() {
    //             let _this = this;

    //             this.autocomplete = new google.maps.places.Autocomplete(this.inputAutocomplete);

    //             //let latLng = new google.maps.LatLng(-23.6815314, -46.875502);
    //             //console.log(this.inputLat.val());
    //             if (this.inputLat.val() && this.inputLng.val()) {
    //                 latLng = new google.maps.LatLng(this.inputLat.val(), this.inputLng.val());
    //             }

    //             this.map = new google.maps.Map(this.mapContainer, {
    //                 zoom: 15
    //                 , center: latLng
    //             });

    //             var marker = new google.maps.Marker({
    //                 position: latLng
    //                 , map: this.map
    //                 , title: 'Hello World!'
    //                 , draggable: true
    //             });

    //             marker.addListener('dragend', function() {
    //                 _this.inputLat.val(marker.getPosition().lat());
    //                 _this.inputLng.val(marker.getPosition().lng());
    //                 _this.geocodePosition(marker.getPosition());
    //                 map.setCenter(marker.getPosition());
    //             })

    //             this.autocomplete.addListener('place_changed', function() {
    //                 let place = _this.autocomplete.getPlace();
    //                 _this.inputLat.val(place.geometry.location.lat());
    //                 _this.inputLng.val(place.geometry.location.lng());

    //                 var pin = place.address_components[place.address_components.length - 1].long_name;
    //                 var country = place.address_components[place.address_components.length - 2].long_name;
    //                 var state = place.address_components[place.address_components.length - 3].long_name;
    //                 var city = place.address_components[place.address_components.length - 4].long_name;
    //                 var placel = place.address_components[2].long_name;

    //                 document.getElementById('country_id').value = country;
    //                 document.getElementById('state_id').value = state;
    //                 document.getElementById('city').value = city;
    //                 document.getElementById('zipcode').value = pin;
    //                 document.getElementById('place').value = placel;

    //                 let latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());

    //                 // create marker
    //                 let marker = new google.maps.Marker({
    //                     position: latlng
    //                     , map: _this.map
    //                     , draggable: true
    //                 });
    //                 _this.map.setCenter(latlng);

    //                 marker.addListener('dragend', function() {
    //                     //alert(marker);
    //                     _this.inputLat.val(marker.getPosition().lat());
    //                     _this.inputLng.val(marker.getPosition().lng());
    //                     _this.geocodePosition(marker.getPosition());
    //                     _this.map.setCenter(marker.getPosition());
    //                 })

    //             })
    //         }
    //         , geocodePosition: function(pos) {
    //             let _this = this;

    //             this.geocoder.geocode({
    //                 latLng: pos
    //             }, function(responses) {
    //                 if (responses && responses.length > 0) {
    //                     _this.updateMarkerAddress(responses[0].formatted_address);

    //                     var pin = responses[0].address_components[responses[0].address_components.length - 1].long_name;
    //                     var country = responses[0].address_components[responses[0].address_components.length - 2].long_name;
    //                     var state = responses[0].address_components[responses[0].address_components.length - 3].long_name;
    //                     var city = responses[0].address_components[responses[0].address_components.length - 4].long_name;
    //                     var place = responses[0].address_components[2].long_name;

    //                     document.getElementById('country_id').value = country;
    //                     document.getElementById('state_id').value = state;
    //                     document.getElementById('city').value = city;
    //                     document.getElementById('zipcode').value = pin;
    //                     document.getElementById('place').value = place;

    //                 } else {
    //                     _this.updateMarkerAddress('Nenhuma coordenada encontrada');
    //                 }
    //             });
    //         }
    //         , updateMarkerAddress: function(str) {
    //             this.inputAutocomplete.value = str;
    //         }
    //         , renderMap: function($el) {
    //             let _this = this;
    //             let $markers = $el.find('.marker');

    //             let args = {
    //                 zoom: 16
    //                 , center: new google.maps.LatLng(0, 0)
    //                 , mapTypeId: google.maps.MapTypeId.ROADMAP
    //                 , streetViewControl: false
    //                 , mapTypeControl: false
    //             };

    //             let map = new google.maps.Map($el[0], args);
    //             map.markers = [];
    //             $markers.each(function() {
    //                 _this.add_marker($(this), map);
    //             });

    //             _this.center_map(map);
    //         }
    //         , add_marker: function($marker, map) {
    //             let latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
    //             let marker = new google.maps.Marker({
    //                 position: latlng
    //                 , map: map
    //             });
    //             map.markers.push(marker);
    //         }
    //         , center_map: function(map) {
    //             let bounds = new google.maps.LatLngBounds();

    //             $.each(map.markers, function(i, marker) {
    //                 let latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
    //                 bounds.extend(latlng);
    //             });

    //             if (map.markers.length == 1) {
    //                 map.setCenter(bounds.getCenter());
    //                 map.setZoom(16);
    //             } else {
    //                 map.fitBounds(bounds);
    //             }
    //         },

    //     };

    //     $(document).ready(function() {
    //         Map.init();
    //     });
    // }

    function directory_pdf_type_delete(id,key,cat_id,directory_id,block_num) {

        if(confirm("Are you sure you want to delete this?")){

            $.ajax({
                url: baseUrl + '/delete-directory-type-pdf',
                type: "POST",
                data: {
                    id: id,
                    cat_id: cat_id,
                    directory_id: directory_id,
                    block_num: block_num,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {

                    console.log(result);

                    if(result.type == 'success'){
                        $('#pdf_type_key_'+key).remove();
                        toastr.success(result.message);


                        if(result.count == 0){
                            location.reload();
                        }

                    }else{
                        toastr.error(result.message);
                    }

                }
            });

        }else{

            return false;
        }
    };

    function directory_type_delete(id,key,cat_id,directory_id,block_num) {

        if(confirm("Are you sure you want to delete this?")){

            $.ajax({
                url: baseUrl + '/delete-directory-type-gallery',
                type: "POST",
                data: {
                    id: id,
                    cat_id: cat_id,
                    directory_id: directory_id,
                    block_num: block_num,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {

                    console.log(result);

                    if(result.type == 'success'){
                        $('#gtype_key_'+key).remove();
                        toastr.success(result.message);

                        if(result.count == 0){
                            location.reload();
                        }

                    }else{
                        toastr.error(result.message);
                    }

                }
            });

        }else{

            return false;
        }


    };

    function view_type_gallery_button(cat_id,directory_id,block_num) {

        $.ajax({
            url: baseUrl + '/get-directory-type-galleries',
            type: "POST",
            data: {
                cat_id: cat_id,
                directory_id: directory_id,
                block_num: block_num,
                _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {

                $('.bulk_delete').show();
                $("#type_gallery_list").html('');
                $('#type_galleryModal').modal('show');

                $.each(result.DirectoryTypeGallery, function(key, value) {
                    $("#type_gallery_list").append(`

                    <div class="thumbnail existing-img col-md-2 col-2 gallerytypeimages" data-id="${value.id}" data-position="${value.position}" data-gallerytypeimages="${value.image_name}" id="gtype_key_${key}">
                        <input type="checkbox" name="ids" class="cb-element" value="${value.id}" />
                        <a href="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" data-lightbox="allGalleryImages">
                            <img data-id="${value.id}" src="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" class="img-fluid gtype_img"/>
                        </a>
                        <span class="closebtn2">
                            <i  id="gtype_key_delete_${key}" onClick="directory_type_delete('${value.id}','${key}','${cat_id}','${directory_id}','${block_num}')" class="fas fa-trash" style="color:#ff0505;margin: -8px;" aria-hidden="true"></i>
                        </span>
                    </div>

                    `);
                });
            }
        });
    };

    function view_type_pdf_button(cat_id,directory_id,block_num) {

        $.ajax({
            url: baseUrl + '/get-directory-type-pdf',
            type: "POST",
            data: {
                cat_id: cat_id,
                directory_id: directory_id,
                block_num: block_num,
                _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {

                $('.bulk_delete').show();
                $("#type_pdf_list").html('');
                $('#type_pdfModal').modal('show');

                $.each(result.DirectoryTypePdf, function(key, value) {
                    $("#type_pdf_list").append(`

                    <div class="col-md-12 gallerytype_pdf" data-id="${value.id}" data-position="${value.position}" data-gallerytypeimages="${value.pdf_name}" id="pdf_type_key_${key}">
                        <input type="checkbox" name="ids" class="cb-element" value="${value.id}" />
                        <iframe
                            src="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.pdf_name}?embedded=true"
                            style="width: 100%; height: 400px; border: none;"
                            frameborder="0">
                        </iframe>
                        <br>
                        <span class="closebtn2">
                            <i  id="pdf_type_key_delete_${key}" onClick="directory_pdf_type_delete('${value.id}','${key}','${cat_id}','${directory_id}','${block_num}')" class="fas fa-trash" style="color:#ff0505;margin: -8px;" aria-hidden="true"></i>
                        </span>
                    </div>

                    `);
                });
            }
        });
    };

    $(document).ready(function() {


        $('.modal_header_close_btn').on('click', function() {
            $('#type_galleryModal').modal('hide');
        });


        var country_id_default = $('select#country-dropdown option:selected').val();
        var stateidselected = $('#stateid').val();
        var cityidselected = $('#cityid').val();

        $.ajax({
            url: baseUrl + '/get-states-by-country'
            , type: "POST"
            , data: {
                country_id: country_id_default
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                $('#state-dropdown').html('<option value="">Select State</option>');
                $.each(result.states, function(key, value) {
                    var valuestate = value.id == stateidselected ? "selected" : "";
                    $("#state-dropdown").append('<option value="' + value.id + '" ' + valuestate + ' >' + value.name + '</option>');
                });
                $('#city-dropdown').html('<option value="">Select State First</option>');
            }
        });

        var stateidselected = $('#stateid').val();
        $.ajax({
            url: baseUrl + '/get-cities-by-state'
            , type: "POST"
            , data: {
                state_id: stateidselected
                , _token: '{{ csrf_token() }}'
            }
            , dataType: 'json'
            , success: function(result) {

                $('#city-dropdown').html('<option value="">Select City</option>');
                $.each(result.cities, function(key, value) {
                    var valuecity = value.id == cityidselected ? "selected" : "";
                    //console.log(cityidselected);
                    $("#city-dropdown").append('<option value="' + value.id + '" ' + valuecity + ' >' + value.name + '</option>');

                });
            }
        });

        $('#country-dropdown').on('change', function() {
            var country_id = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: baseUrl + '/get-states-by-country',

                type: "POST"
                , data: {
                    country_id: country_id
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#state-dropdown').html('<option value="">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $("#state-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#city-dropdown').html('<option value="">Select State First</option>');
                }
            });
        });


        $('#state-dropdown').on('change', function() {
            var state_id = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
                    $('#city-dropdown').html('<option value="">Select City</option>');
                    $.each(result.cities, function(key, value) {
                        $("#city-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });

    });


    $(document).ready(function() {
        var typedata1 = $('input[name=hotel_type]:checked').val();

        var blockcount = $('#blockone').length;

        if (typedata1 >= 1) {
            // console.log("test type");
            getTypeBlock(typedata1, blockcount, "edit");
        }
        $('.typecat').on('click', function() {
            $('#type_blocks').html('');
            var typedata = this.value;
            var typeNamedata = $(this).attr('data_val');
            var blockcount = $('#blockone').length;
            getTypeBlock(typedata, blockcount, "add");
        });

    });


    function addSection() {
        var typedata = $('input[name=hotel_type]:checked', '#editsection').val();
        var blockcount = $('.blocksCount').length;
        // console.log("Block count on edit add section click: ",typedata);
        $('#total_block').val(blockcount);
        getTypeBlock(typedata, blockcount, "add");
    }

    var blockdirectoryId = $('#directoryId').val();
    var blockNumber = $('#total_block_from_database').val();

    function getTypeBlock(typedata, blockcount, addnewonedit) {

        var editvalue = (addnewonedit != "edit") ? "add" : "edit";
        //console.log("Block: ", editvalue);
        $.ajax({
            url: baseUrl + '/get-type-block'
            , type: "POST"
            , data: {
                type_value: typedata
                , blockcount: blockcount
                , editval: editvalue
                , blockdirectoryId: (blockdirectoryId != "") ? blockdirectoryId : ""
                , blockNumber: (blockNumber != "") ? blockNumber : ""
                , _token: '{{ csrf_token() }}'
            }
            , dataType: 'html'
            , success: function(result) {
                $('#type_blocks').append(result);

            }
        });
    }

    function removeSection(count) {
        $('#block_' + count).remove();
    }


    function addTimeBlockPublic() {
        var numItems = $('.row_public').length;
        var insertBlock = parseInt(numItems - 1);
        $('<tr class="row_public public_time_slot_row_' + numItems + '"> <td> </td> <td></td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlockPublic(`public_time_slot_row_' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.public_time_slot_row_' + insertBlock);
    }

    function removeTimeBlockPublic(blockClass) {
        $('.' + blockClass).remove();
    }
    function addTimeBlockException() {
        var numItems = $('.row_exceptional').length;
        var insertBlock = parseInt(numItems - 1);
        $('<tr class="row_exceptional execption_time_slot_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlockExecption(`execption_time_slot_row_' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.execption_time_slot_row_' + insertBlock);
    }
    function removeTimeBlockExecption(blockClass) {
        $('.' + blockClass).remove();
    }
    function addDayBlock(blockClass) {
        var numItems = $('.' + blockClass).length;
        var insertBlock = parseInt(numItems - 1);

        $('<tr class="' + blockClass + ' ' + blockClass + '_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_row_' + insertBlock);
    }
    function removeDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_row_' + numItems).remove();
    }
    function addTemplateDayBlock(blockClass) {
        var numItems = $('.' + blockClass).length;
        var insertBlock = parseInt(numItems - 1);
        $('<tr class="' + blockClass + ' ' + blockClass + '_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTemplateDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_row_' + insertBlock);
    }

    function removeTemplateDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_row_' + numItems).remove();
    }


    let lineno = $('#wesitecount').val();
    lineno++;
    var websiteaddcount = 0;
    function addwebSection() {
        var linksCount = $('#wesitelinkcount').val();
        websiteaddcount++;
        if(websiteaddcount>=linksCount){
            toastr.error('Please upgrade member account. you can not add products greater than ' + linksCount);
            return false;
        }else{
        $("#weblink_1").after(`
        <div class="row weblink" id="weblink_` + lineno + `">
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">Website Name</label>
                    <div class="col-md-12 border-bottom p-0">
                        <input type="text" name="website_name_` + lineno + `" value="{{ old('website') }}" placeholder="Enter Website Name" class="form-control p-0 border-0" autocomplete=off>
                    </div>
                    <span class="text-danger">@error('website'){{$message}} @enderror</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">Website link</label>
                    <div class="col-md-12 border-bottom p-0">
                        <input type="text" name="website_link_` + lineno + `" value="{{ old('website') }}" placeholder="Enter Website link" class="form-control p-0 border-0" autocomplete=off>
                    </div>
                    <span class="text-danger">@error('website'){{$message}} @enderror</span>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" class="remove_web_button" title="Remove field" onclick=removewebSection(` + lineno + `)>-</button>
            </div>
        </div>
        `);
       $('#wesitecount').val(lineno);
        }
    }
    function removewebSection(count) {
        $('#weblink_' + count).remove();
    }

    let linenoass = $('#assortcount').val();
    linenoass++;
    var assortmenteaddcount = 0;
    function addAssortmentSection() {

        var linksCount = $('#wesitelinkcount').val();
        assortmenteaddcount++;
        if(assortmenteaddcount>=linksCount){
            toastr.error('Please upgrade member account. you can not add products greater than ' + linksCount);
            return false;
        }else{
            $("#assortlink_1").after(`
            <div class="row assortlink" id="assortlink_` + linenoass + `">
                <div class="col-md-4">
                    <div class="form-group mb-4">
                        <label class="col-md-12 p-0">Assortment Name</label>
                        <div class="col-md-12 border-bottom p-0">
                            <input type="text" name="assortment_name_` + linenoass + `" value="{{ old('website') }}" placeholder="Enter Assortment Name" class="form-control p-0 border-0" autocomplete=off>
                        </div>
                        <span class="text-danger">@error('assortment_name'){{$message}} @enderror</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-4">
                        <label class="col-md-12 p-0">Assortment link</label>
                        <div class="col-md-12 border-bottom p-0">
                            <input type="text" name="assortment_link_` + linenoass + `" value="{{ old('assortment_link') }}" placeholder="Enter Assortment link" class="form-control p-0 border-0" autocomplete=off>
                        </div>
                        <span class="text-danger">@error('assortment_link'){{$message}} @enderror</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger" class="remove_assortment_button" title="Remove field" onclick=removeAssortmentSection(` + linenoass + `)>-</button>
                </div>
            </div>
            `);
            $('#assortcount').val(linenoass);
        }

    }

    function removeAssortmentSection(count) {
        $('#assortlink_' + count).remove();
    }

   function type_galley_submit(key){
            var active_button = $('ul.dir-tabs li a.active');
            $('.active-tab').val(active_button.text());
   }

    $(document).ready(function() {

        $(".listsubmit").on("click", function(e) {
            var active_button = $('ul.dir-tabs li a.active');
            $('#active-tab').val(active_button.text());
            $('.active-tab').val(active_button.text());
        });

        function beforeUnloadHandler(e) {
            e.preventDefault();
            e.returnValue = '';
            return 'Changes you made may not be saved.';
        }
        // Attach the beforeunload event listener
        $(window).on('beforeunload', beforeUnloadHandler);

        // Remove beforeunload event when the Save Changes button is clicked
        $('#listsubmit-basic').on('click', function (e) {

            e.preventDefault();
            // Show confirmation dialog
            if (confirm('Are you sure you want to save changes?')) {
                $(window).off('beforeunload', beforeUnloadHandler); // Remove beforeunload warning
                $(this).closest('form').submit(); // Submit the form
            }

        });

    });

    var form = $('#editsection'), original = form.serialize()

    $(document).ready(function() {
        var getFeatureAmenitiesValue = {{ $getFeatureAmenitiesValue }};
            $('#tags_name').on('change', function() {
                var ownamenitylength = $('.badge-success').length;
                if(getFeatureAmenitiesValue < ownamenitylength){
                    $('.badge-success').remove();
                    $('#tags_name').val('');
                    toastr.error('Please upgrade member account. you can not add own amenity greater than ' + getFeatureAmenitiesValue);
                    return false;
                }
            });
    });

    $('.directorydesc').on('keypress keyup keydown',function(event) {
        // create the event
        var press = jQuery.Event(event.type);
        var code = event.keyCode || event.which;
        press.which = code ;
        // trigger
        $('.metaDescp').val(this.value);
        $('.metaDescp').trigger(event.type, {'event': press});
        });

    $('.directorynametitle').on('keypress keyup keydown',function(event) {
        // create the event
        var press = jQuery.Event(event.type);
        var code = event.keyCode || event.which;
        press.which = code ;
        // trigger
        $('.metakey').val(this.value);
        $('.metakey').trigger(event.type, {'event': press});
    });

    //5 words limit
    function limitWords(id) {
        var maxWords=6;
        var d=document.getElementById(id);
        if ( d.value.split(' ').length > maxWords ) {
            t=d.value.substring(0,d.value.lastIndexOf(' '));
            d.value=t.substring(0,t.lastIndexOf(' ')+1);
        }
    }

    $(document).ready(function() {

        $("#loader").fadeOut("slow");

        $(".seoinputname_bkp").change(function() {
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-" + languagecode).val();
            console.log("languagecode: ", languagecode);
            console.log("Actual Name: ", nameText);
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/directoryseo'
                    , data: {
                        catname: nameText
                        , directory: 'directory'
                        , _token: '{{csrf_token()}}'
                    }
                    , success: function(result) {
                        console.log(result.seoChangeData);

                        if (result.categoryTypeDetails != "") {
                            $("#seo-" + languagecode).val(result.categoryTypeDetails);
                        } else {
                            $("#seo-" + languagecode).val(result.seoChangeData);
                        }
                    }
                });
            } else {
                $("#name-" + languagecode).text("Enter Directory Name First!");
            }
        });

    });

</script>

<style>
    .btn-info:hover {
        color: #fff;
        background-color: rgb(255 157 7);
        border-color: rgb(255 157 7);
    }
    .image-metadata-overlay {
        padding: 6px !important;
    }
    .schedule_active_tab{
        background-color: rgb(255 157 7);
        color: #ffffff;
    }
    .btn-info {
        color: #fff !important;
    }
    .show-more-height{
    /*height: 260px; */
    overflow: hidden;
    }
    a#deleteAllSelectedGal {
        border-left: 1px solid;
    }
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f2f5f4;
    }
    #map {
        margin: 0 auto;
        width: 100%;
        max-width: 100%;
        height: 50vh;
        min-height: 250px;
    }
    .business-hours-container {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        align-content: space-between;
        justify-content: space-around;
        align-items: center;
        font-family: sans-serif;
    }
    .business-hours-container ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .business-hours-container>ul>list-item-element>li>span {
        display: inline-block;
        width: 100px;
    }
    .business-hours-container>ul>li>span {
        display: inline-block;
        width: 100px;
        font-weight: 600;
    }
    .business-hours-container>ul>li {
        list-style: none;
        display: flex;
        flex-direction: row;
        align-items: center;
        align-content: center;
        padding: 10px;
    }
    .business-hours-container>ul>list-item-element>li {
        list-style: none;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        align-content: center;
        padding: 10px;
    }

    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
        margin: 15px 10px;
    }

    .thumbnail img {
        width: 85px;
        height: 80px;
        border: 2px solid #CCC;
    }

    .image-metadata-overlay {
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        z-index: 1;
        width: 240px;
        max-height: 160px;
        padding: 10px 12px;
        overflow-y: auto;
        color: #333;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        font-size: 13px;
        line-height: 1.45;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-50%);
        transition: opacity 0.15s ease;
        overflow-wrap: anywhere;
    }

    .image-metadata-overlay strong,
    .image-metadata-overlay span {
        display: block;
    }

    .thumbnail:hover {
        z-index: 2;
    }

    .thumbnail:hover .image-metadata-overlay,
    .thumbnail:focus-within .image-metadata-overlay {
        opacity: 1;
        visibility: visible;
    }

    #listgallerypreview.metadata-popup-active {
        overflow: visible;
    }

    #imgPreview.metadata-popup-active {
        overflow: visible;
        position: relative;
        z-index: 2;
    }

    .closebtn {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        top: -11px;
        right: -41px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }

    .select2-container-multi {
        width: 300px
    }
    .bootstrap-tagsinput .tag {
        padding: 5px;
        margin: 0px 0px 0px 10px;
    }
    .business-hours-container ul {
        width: 100%;
    }
    #schedule span {
        text-transform: capitalize;
    }
    .business-hours-container>ul>list-item-element>li>span {
        padding: 0 5px;
        width: 25%;
    }
    .business-hours-container>ul>list-item-element>li {
        padding: 5px 0px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    #schedule .btn.btn-info {
        margin-top: 0px;
    }
    #schedule ul li {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
    }
    #schedule ul li select {
        height: 30px;
        border-radius: 4px;
        background-color: #e2e2e2;
        border: none;
        padding: 0px 5px;
    }
    #schedule ul li.my-first-nav {
        padding: 10px 5px;
        background-color: #131112;
        color: #ffffff;
        margin-bottom: 18px;
        justify-content: space-between;
        width: 100%;
    }
    /* for new tab */
    .newschedual {
        width: 500px;
        display: flex;
        padding: 0px 2px;
        float: right;
        justify-content: end;
        flex-direction: column;
    }
    .newschedual list-item-element {
        padding: 8px 0px;
        margin-bottom: 4px;
    }
    .newschedual select {
        height: 30px;
        border-radius: 4px;
        background-color: #e2e2e2;
        border: none;
        padding: 0px 5px;
    }
    .weekley-name {
        display: flex;
        justify-content: space-between;
        width: 500px;
        float: right;
        margin-bottom: 8px;
        align-items: center;
    }
    .weekley-name .btn-danger[type="button"] {
        min-width: 120px;
        color: #ffffff;
    }
    checkbox-input-element input[type="checkbox"] {
        position: relative;
        left: -140px;
    }
    .business-hours-container time-input-element[name="min"] {
        margin-left: -130px;
    }
    .business-hours-container .weekley-name time-input-element[name="min"] {
        margin-left: -46px;
    }
    #schedule[name="business_hours"] ul li {
        border-bottom: none;
    }
    @media (min-width: 1920px) {
        .newschedual,
        .weekley-name {
            width: 775px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -300px;
        }
        .business-hours-container time-input-element[name="min"] {
            margin-left: -250px;
        }
    }
    /* Mac */
    @media (min-width: 1600px) and (max-width: 1680px) {
        .newschedual,
        .weekley-name {
            width: 685px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -250px;
        }
        .business-hours-container time-input-element[name="min"] {
            margin-left: -250px;
        }
    }
    /* Mac */
    @media (max-width: 991px) {
        checkbox-input-element input[type="checkbox"] {
            left: -80px;
        }
        .weekley-name {
            width: 350px;
        }
        .newschedual {
            width: 350px;
        }
    }
    /* tablet */
    @media (min-width: 991px) and (max-width: 1199px) {
        .business-hours-container time-input-element[name="min"] {
            margin-left: -100px;
        }
        .newschedual,
        .weekley-name {
            width: 430px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -120px;
        }
    }
    /*tablet landscape*/
    @media (max-width: 767px) {}
    /*mobile*/
    .amenitycategories_name {
        border-top: 1px dashed #484848;
        position: relative;
        margin: 20px 0;
        width: 100%;
        }
    .amenitycategories_name span {
        position: relative;
        top: -17px;
        background: #e4e4e4;
        margin: 0 auto;
        width: 200px;
        display: block;
        font-size: 16px;
        text-align: center;
        color: #000;
        font-weight: 400;
        padding: 3px;
        border-radius: 5px;
    }

    .modal-backdrop {
        position: absolute;
    }
    div#allgalleryimages_model .modal-header {
        background: #1f3863;
    }
    div#allgalleryimages_model .modal-header h5.modal-title {
        color: #fff;
    }
    div#allgalleryimages_model .modal-header button.close {
        color: #fff;
    }
    .see_all{
        cursor: pointer;
        background: green;
        width: 56px;
        padding: 3px 8px 3px 8px !important;
        font-size: 16px;
        color: white;
        border-radius: 5px;
        margin-right: 0px;
    }
    .delete_all {
        cursor: pointer;
        background: #625d58;
        width: 159px;
        padding: 0px 0px 0px 18px !important;
        font-size: 15px;
        color: white;
        line-height: 30px;
        border-radius: 5px;
    }
    div#public_holiday_model .modal-header {
        background: #1f3863;
    }
    div#public_holiday_model .modal-header h5.modal-title {
        color: #fff;
    }
    div#public_holiday_model .modal-header button.close {
        color: #fff;
    }
    div#public_holiday_model table {
        width: 100%;
    }
    div#public_holiday_model table td {
        padding: 2px 5px;
    }
    div#public_holiday_model .modal-body .col-md-12 > table > tbody > tr:nth-child(odd) {
        background: #d1def5;
    }
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f2f5f4;
    }
    #map {
        margin: 0 auto;
        width: 100%;
        max-width: 100%;
        height: 50vh;
        min-height: 250px;
    }
    .business-hours-container {
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        align-content: space-between;
        justify-content: space-around;
        align-items: center;
        font-family: sans-serif;
    }
    .business-hours-container ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .business-hours-container>ul>list-item-element>li>span {
        display: inline-block;
        width: 100px;
    }
    .business-hours-container>ul>li>span {
        display: inline-block;
        width: 100px;
        font-weight: 600;
    }
    .business-hours-container>ul>li {
        list-style: none;
        display: flex;
        flex-direction: row;
        align-items: center;
        align-content: center;
        padding: 10px;
    }
    .business-hours-container>ul>list-item-element>li {
        list-style: none;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        align-content: center;
        padding: 10px;
    }
    .select2-container-multi {
        width: 300px
    }
    .business-hours-container ul {
        width: 100%;
    }
    #schedule span {
        text-transform: capitalize;
    }
    .business-hours-container>ul>list-item-element>li>span {
        padding: 0 5px;
        width: 25%;
    }
    .business-hours-container>ul>list-item-element>li {
        padding: 5px 0px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    #schedule .btn.btn-info {
        margin-top: 0px;
    }
    #schedule ul li {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
    }
    #schedule ul li select {
        height: 30px;
        border-radius: 4px;
        background-color: #e2e2e2;
        border: none;
        padding: 0px 5px;
    }
    #schedule ul li.my-first-nav {
        padding: 10px 5px;
        background-color: #131112;
        color: #ffffff;
        margin-bottom: 18px;
        justify-content: space-between;
        width: 100%;
    }
    /* for new tab */
    .newschedual {
        width: 500px;
        display: flex;
        padding: 0px 2px;
        float: right;
        justify-content: end;
        flex-direction: column;
    }
    .newschedual list-item-element {
        padding: 8px 0px;
        margin-bottom: 4px;
    }
    .newschedual select {
        height: 30px;
        border-radius: 4px;
        background-color: #e2e2e2;
        border: none;
        padding: 0px 5px;
    }
    .weekley-name {
        display: flex;
        justify-content: space-between;
        width: 500px;
        float: right;
        margin-bottom: 8px;
        align-items: center;
    }
    .weekley-name .btn-danger[type="button"] {
        min-width: 120px;
        color: #ffffff;
    }
    checkbox-input-element input[type="checkbox"] {
        position: relative;
        left: -140px;
    }
    .business-hours-container time-input-element[name="min"] {
        margin-left: -130px;
    }
    .business-hours-container .weekley-name time-input-element[name="min"] {
        margin-left: -46px;
    }
    #schedule[name="business_hours"] ul li {
        border-bottom: none;
    }
        .table-schedule{overflow:auto}
        .table-schedule .form-check-input[type="checkbox"] {
        position: relative;
    }
   #nomaldays-table select.form-control {
        white-space: nowrap;
        width: 100px;
    }
    #nomaldays-table input.form-control {
        white-space: nowrap;
        width: 150px;
    }
    .temp-schecule-table{overflow:auto;}
    @media (min-width: 1920px) {
        .newschedual,
        .weekley-name {
            width: 775px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -300px;
        }
        .business-hours-container time-input-element[name="min"] {
            margin-left: -250px;
        }
    }
    /* Mac */
    @media (min-width: 1600px) and (max-width: 1680px) {
        .newschedual,
        .weekley-name {
            width: 685px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -250px;
        }
        .business-hours-container time-input-element[name="min"] {
            margin-left: -250px;
        }
    }
    /* Mac */
    @media (max-width: 991px) {
        checkbox-input-element input[type="checkbox"] {
            left: -80px;
        }
        .weekley-name {
            width: 350px;
        }
        .newschedual {
            width: 350px;
        }
    }
    /* tablet */
    @media (min-width: 991px) and (max-width: 1199px) {
        .business-hours-container time-input-element[name="min"] {
            margin-left: -100px;
        }
        .newschedual,
        .weekley-name {
            width: 430px;
        }
        checkbox-input-element input[type="checkbox"] {
            left: -120px;
        }
    }
    /*tablet landscape*/
    @media (max-width: 767px) {
        #listsubmit-contact{
            margin-top: 10px;
        }
        .cust-tab-head {
            margin-top: 10px;
        }
    }
    /*mobile*/
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
    #helpicon {
        top:8px;
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #helpiconText {
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
    #helpicon:hover:after {
        top: 14px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }

    #helpiconText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #helpicon:hover #helpiconText {
        visibility: visible;
        opacity: 1;
        top: 60%;
        z-index: 99;
    }
    .modal {
        top: 90px;
    }
    .modal-header {
        background: #1f3863;
    }
    .modal-title {
        color: #fff;
    }
    .modal-header .close {
        color: #fff;
    }
    .gtype_img {
        border: 2px solid #1f3863;
    }
    .closebtn2 {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        top: -12px;
        right: -21px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }
    .count_uploaded_gallery {
        position: absolute;
        left: -28px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        padding: 0px 8px;
        font-size: 12px;
        top: 10px;
    }
    .count_gallery_type_pdf {
        display:none;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        padding: 0px 8px;
        font-size: 12px;
        margin: 0px 6px 0px -15px;
    }
    .bulk_delete.row {
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
    }
</style>

@endsection
