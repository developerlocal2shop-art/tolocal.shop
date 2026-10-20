@extends('layouts.adminmaster')
@section('title','Company | Edit Company')
@section('content')

<style>
    a#deleteAllSelectedGal {
        border-left: 1px solid;
    }
    .btn-group-sm>.btn, .btn-sm {
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
        border-radius: .2rem;
    }
    .thumbnail img {
        width: 85px;
        height: 80px;
        border: 2px solid #CCC;
    }
    .bulk_delete.row {
        margin-bottom: 10px;
    }
    .delete_all {
        cursor: pointer !important;
        background: #dcd8d8 !important;
        width: 159px !important;
        padding: 0px 0px 0px 18px !important;
        font-size: 15px !important;
        color: white;
        line-height: 1px !important;
        border-radius: 5px !important;
    }
    .gallerytype_pdf {
        margin: 15px 10px 15px 0px;
        border: 4px solid #1f3863;
        padding: 1px;
    }
    button#listsubmit-dirtype {
        margin-top: 24px;
    }
    a.btn.btn-success.mt-4 {
        width: 139px;
        margin: 2px 4px;
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
        display: none;
        background-color: #007bff;
        color: white;
        border-radius: 30%;
        padding: 0px 8px;
        font-size: 12px;
        margin: 21px 0px 0px 5px;
        position: absolute;
    }

    .select2-search__field{
        width: 44px !important;
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
        /*min-width: 500px;*/
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
        width: 74px;
        height: 70px;
        margin: 5px 5px;
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

    .select2-container-multi {
        width: 300px
    }

    .cust-tab-cont .tab-pane.active {
        background: none;
    }

    .modal-backdrop {
        position: absolute;
        top: unset;
    }

    div#allgalleryimages_model .modal-header {
        background: #1f3863;
    }

    div#allgalleryimages_model .modal-header h5.modal-title {
        color: #fff;
    }

    div#allgalleryimages_model .modal-header button.close {
        color: #fff;
        background: #1f3863;
        border: #1f3863;
    }
    .see_all{
        cursor: pointer;
        background: green;
        width: 70px;
        padding: 3px 8px 3px 8px !important;
        font-size: 16px;
        color: white;
        border-radius: 5px;

    }

    .delete_all{
        cursor: pointer;
        background: red;
        width: 203px;
        padding: 3px 8px 3px 8px !important;
        font-size: 16px;
        color: white;
        border-radius: 5px;
    }

    .btn.btn-info.listsubmit {
        min-width: 376px !important;
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

    /* .business-hours-container .newschedual time-input-element[name="min"] {margin-left: -50px;} */
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
        transform: translate(-12%, 7%);
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
        top: 15px;
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
    .count_uploaded_gallery {
        color: #000;
        border-radius: 50%;
        padding: 2px 7px;
        font-size: 16px;
        margin: 11px 0px 0px -91px;
        position: absolute;
    }
    .gtype_img {
        width: 100px !important;
        height: 100px !important;
        margin: 10px 0px;
    }
    .closebtn2 {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        margin: 0px 100px;
    }

    .closebtn3 {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        right: -29px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        margin: -16px 1px;
    }
    .gtype_img_sk {
        width: auto;
    }
    button#listsubmit-dirtype {
        margin: 27px 0px 2px 3px;
    }
    .bulk_delete.row {
        margin-bottom: 9px;
        width: auto;
        padding: 10px 0px 0px 25px;
    }
    input.cb-element {
        position: absolute;
        margin: -4px 0px 0px -4px;
        z-index: 20000;
        width: auto;
    }
    input#checkAll {
        margin: 3px 5px 22px 15px;
        width: auto;
    }
    .gtype_img {
        border: 2px solid #1f3863;
    }
</style>

<?php

    if (isset($data['basicData'])) {
        $basicData = $data['basicData'];
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
?>

<!-- Gallery Image Modal -->
<div class="modal fade"  id="allgalleryimages_model" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="col-md-12 listgallerypreview3" id="listgallerypreview3">
                        @php
                        $mediadirct_id = isset($mediaData->directory_id)? $mediaData->directory_id : "";
                        $imagenamegal = App\Http\Controllers\Admin\Directory\DirectoryController::getallgalImage($mediadirct_id);
                        @endphp
                        <?php
                        if (!empty($imagenamegal)) { $last_position = end($imagenamegal);?>
                        @foreach($imagenamegal as $imagenamerow)
                        <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                            @if($imagenamerow->name_thumbnail)
                                <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
                            @else
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img" alt="directory-img" width="70px" height="70px" class="previewBlocks" />
                            @endif

                            <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                        </div>
                        @endforeach

                        <?php } ?>

                        <input type="hidden" id="lastpositiongallValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Company <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a></h4>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">


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
                            <div class="col-md-12 listgallerypreview3" >

                                <div class="bulk_delete row" style="display: none;">
                                    <input type="checkbox" id="checkAll" class="checkall">All
                                    <a class="btn btn-danger btn-sm" id="deleteAllSelectedProducts"  style="margin: 0px 0px 11px 10px;width: auto;">
                                        Delete In Bulk
                                    </a> <hr>
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
                                    <input style="margin: -7px 5px 10px 6px;width: 16px;" type="checkbox" id="checkAll2" class="checkall"> All
                                    <a class="btn btn-danger btn-sm" id="deleteAllSelectedPdf"  style="margin:0px 10px 10px 15px;width: 100px;color: #fff;">
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


        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        @if(\Session::has('errorimg'))
        <div class="alert alert-danger">
            {!! \Session::get('errorimg') !!}
        </div>
        @endif
        <!-- Column -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.directory.update', $basicData->id) }}" method="POST" class="" enctype="multipart/form-data" id="editsection" onsubmit="return submitForm()">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <ul class="nav nav-tabs dir-tabs cust-nav-tab" id="directory_tabs" role="tablist">
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

                                <li class="nav-item">
                                    <a class="nav-link <?php if(request()->get('target')!=""){ active('basic'); }else{ echo "active";} ?>" data-toggle="tab" href="#basic" role="tab">
                                        <span>Basic</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('location');?>" data-toggle="tab" href="#location" role="tab">
                                        <span>Location</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('type');?>" data-toggle="tab" href="#dirtype" role="tab">
                                        <span>Type</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('amenities');?>" data-toggle="tab" href="#amenities" role="tab">
                                        <span>Amenities</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('media');?>" data-toggle="tab" href="#media" role="tab">
                                        <span>Media</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('seo');?>" data-toggle="tab" href="#seo" role="tab">
                                        <span>SEO</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('schedule');?>" data-toggle="tab" href="#schedule" role="tab">
                                        <span>Schedule</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php active('links');?>" data-toggle="tab" href="#links" role="tab">
                                        <span>Links</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php active('claim');?>" data-toggle="tab" href="#claim" role="tab">
                                        <span>Claim</span>
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#finish" role="tab">
                                        <span>Submit</span>
                                    </a>
                                </li> -->

                            </ul>
                            <div class="tab-content cust-tab-cont" id="directory_tabscon">

                                <div class="tab-pane <?php if(request()->get('target')!=""){ active('basic'); }else{ echo "active";} ?>" id="basic" role="tabpanel">

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
                                                <label class="col-md-12 p-0">Name</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="cat-{{$languagecodeBasicData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="name_{{$languagecodeBasicData->code}}" data-id="{{$languagecodeBasicData->code}}" value="{{ old('name_'.$languagecodeBasicData->code, $basiclanguagesrow->title ?? '') }}" placeholder="Enter Directory Name in {{ $languagecodeBasicData->name }}" class="directorynametitle seoinputname form-control p-0 border-0" autocomplete=off>
                                                    <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code}}" value="{{ $basiclanguagesrow->id }}" class="form-control p-0 border-0">
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">SEO</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <!-- <input type="text" id="seo-{{$languagecodeBasicData->code}}" name="seo_{{$languagecodeBasicData->code}}" value="{{ $basiclanguagesrow->seo_url }}" placeholder="Directory SEO in {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0" readonly > -->
                                                    <input type="text" id="seo-{{$languagecodeBasicData->code}}" name="seo_{{$languagecodeBasicData->code}}" value="{{ $basicData->slug }}" placeholder="Directory SEO in {{ $languagecodeBasicData->name }}" class="form-control p-0 border-0" readonly >
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Description</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" name="description_{{$languagecodeBasicData->code}}" maxlength="900" id="direc-des{{$languagecodeBasicData->code}}" onkeyup="charCount(this.value,this.id,900)" class="directorydesc form-control p-0 border-0">{{ old('description_'.$languagecodeBasicData->code, $basiclanguagesrow->description ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">We Offer</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" name="weoffer_{{$languagecodeBasicData->code}}" id="we-offer-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter We Offer in {{ $languagecodeBasicData->name }}">{{ old('weoffer_'.$languagecodeBasicData->code, $basiclanguagesrow->we_offer ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">About Us</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" name="aboutus_{{$languagecodeBasicData->code}}" id="about-us-{{$languagecodeBasicData->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="form-control p-0 border-0" placeholder="Enter About Us in {{ $languagecodeBasicData->name }}">{{ old('aboutus_'.$languagecodeBasicData->code, $basiclanguagesrow->about_us ?? '') }}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Featured Type</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <select class="form-control" name="featuretype" id="featuretype">
                                                        <option value="none" {{ "none"==$basicData->feature_type ? 'selected' : '' }}>None</option>
                                                        <option value="featured" {{ "featured"==$basicData->feature_type ? 'selected' : '' }}> Featured</option>
                                                        <option value="superfeatured" {{ "superfeatured"==$basicData->feature_type ? 'selected' : '' }}>Super Featured</option>
                                                    </select>
                                                </div>
                                                <span class="text-danger">@error('featuretype'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Google analytics id</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="google_ana_id" value="{{ old('$google_ana_id', $basicData->google_analytics_id ?? '') }}" placeholder="Enter Google analytics id" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('google_ana_id'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                                        <div class="col-md-3">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Company Logo (Size: 350 * 100)</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                <input type="file" class="form-control" name="company_logo" id="company_logo">
                                                </div>
                                            </div>
                                            <span class="text-danger">@error('company_logo'){{$message}} @enderror</span>
                                        </div>
                                        <div class="col-md-2">
                                            @if (!empty($basicData->company_logo))
                                            <img src="{{env('IMAGE_PATH').'/'.$basicData->company_logo }}" width="130px">
                                            @else
                                            <img src="{{ asset('assets/images/comp.jpg') }}" width="130px" alt="" >
                                            @endif

                                        </div>

                                    </div>
                                    <?php //echo "<pre>"; print_r($data['categoryData']);?>

                                    <hr style="height:2px;color:#8898aa">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Registered Company Email <span style="font-weight: 600;">(Can't be change)</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="email" name="email" readonly value="{{ old('email', isset($contactData->email)?$contactData->email:'') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Contact Person Name</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="name" @if($basicData->created_by != 1)  readonly @endif value="{{ old('name', isset($contactData->name)?$contactData->name:'') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Contact Person Phone Number </label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="phone_number" @if($basicData->created_by != 1)  readonly @endif value="{{ old('phone_number', isset($contactData->phone)?$contactData->phone:'') }}" placeholder="Enter Phone Number" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="height:2px;color:#8898aa">
                                    <div class="form-group ">
                                        <?php $dataselectedcategory = isset($data['trademarkDatacate'][0]['trademarkcat_id'])?$data['trademarkDatacate'][0]['trademarkcat_id']:''; ?>
                                        <label class="col-md-12 p-0">Trademark Category</label>
                                        <div class="col-md-12 ">
                                            <select class="form-control" name="trademarkcategory" id="trademark-category" onChange="getTrademarkOptions(this)" style="width:100%">
                                                <option value="">Select Trademark Category</option>
                                                @foreach($data['trademarkcategory'] as $categorysrow)
                                                <option value="{{ $categorysrow['id'] }}" <?php if($categorysrow['id']==$dataselectedcategory){echo "selected";} ?>> {{ $categorysrow['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Trademark</label>
                                        <div class="col-md-12 ">
                                            <?php /* echo "<pre>"; print_r($data['trademarkselectedDataAccordingToTrademarkCat']); die; */ ?>
                                            <select multiple class="form-control select2" name="trademark[]" id="par-trademark" style="width:100%">
                                                <option></option>
                                                @foreach($data['trademarkselectedDataAccordingToTrademarkCat'] as $trademarksrow)
                                                <option value="{{ $trademarksrow->id }}" <?php if(in_array($trademarksrow->id,$data['trademarkData'])){echo "selected";} ?>> {{ $trademarksrow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <hr style="height:2px;color:#8898aa">
                                    <div class="form-group ">
                                        @php $status = $basicData->status??'Active'; @endphp
                                        <label class="col-md-12 p-0">Status</label>
                                        <div class="col-md-12 ">
                                            <select class="form-control" name="status" id="status" style="width:100%">
                                                <option value="Active" @if($status=='Active' ) selected @endif>Active</option>

                                                <option value="Inactive" @if($status=='Inactive' ) selected @endif> Inactive</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4">
                                            {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-check-input" name="popular" id="" value="1" {{ $basicData->popular=='1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="1" style="display: inline-flex;"> Popular
                                                    <div id="tooltip">
                                                        <span id="tooltipText">Please check this, If you want this directory to show in the list of popular.</span>
                                                        <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                                    </div>
                                                </label>
                                            </div>
                                            <span class="text-danger">@error('popular'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-basic" class="btn btn-info listsubmit">Submit</button>
                                    </div>

                                </div>

                                <div class="tab-pane <?php active('location');?>" id="location" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Address</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input id="pac-input" class="form-control" type="text" placeholder="address" name="address" value="{{ old('address',$locationData[0]->address) }}" />
                                                    {{-- <textarea rows="5" name="address" class="form-control p-0 border-0">{{ $locationData[0]->address }}</textarea> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Country</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="country_id" id="country_id" readonly value="{{ old('country_id',$locationData[0]->country_id)??'' }}" placeholder="Country" class="form-control p-0 border-0" autocomplete=off>
                                                    {{-- <select class="form-control" name="country_id" id="country-dropdown">
                                                        <option value=""> Select Country </option>
                                                        @foreach($data['country'] as $countryrow)
                                                        <option value="{{ $countryrow->id }}" {{ $countryrow->id == $locationData[0]->country_id ? 'selected' : '' }}> {{ $countryrow->name }}</option>
                                                    @endforeach
                                                    </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">State</label>
                                                <div class="col-md-12 border-bottom p-0">


                                                    <input type="text" name="state_id" readonly id="state_id" value="{{ old('country_id',$locationData[0]->state_id)??'' }}" placeholder="State" class="form-control p-0 border-0" autocomplete=off>

                                                    {{-- <select class="form-control" name="state_id" id="state-dropdown"></select> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">city</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="city_id" id="city" readonly value="{{ old('city',$locationData[0]->city_name) }}" placeholder="City" class="form-control p-0 border-0" autocomplete=off>
                                                    <!-- <input type="hidden" id="cityid" value="{{ $locationData[0]->city_id }}">
                                                    <select class="form-control" name="city_id" id="city-dropdown"></select> -->
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-12">
                                                <label class="col-md-12 p-0">Place</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="place" id="place" value="{{ old('place',$locationData[0]->place) }}" placeholder="Place" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-12">
                                                <label class="col-md-12 p-0">Zipcode</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode',$locationData[0]->zipcode) }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display:none;">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Latitude</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="latitude" value="{{ old('latitude', $locationData[0]->latitude) }}" placeholder="Provide latitude for getting the exact result" class="form-control p-0 border-0" id="latitude" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display:none;">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Longitude</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="longitude" value="{{ old('longitude', $locationData[0]->longitude) }}" placeholder="Provide longitude for getting the exact result" class="form-control p-0 border-0" id="longitude" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="map" class="map-full map-layout single-listing-map" style="z-index: 50;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-location" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>

                                <div class="tab-pane <?php active('amenities');?>" id="amenities" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Amenity Category</label>
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
                                        <?php $datacount =count($amenityrow); ?>
                                        <?php if($datacount!=0){ ?>
                                            @foreach($amenityrow as $ameyrow)
                                                <div class="col-md-3 p-10">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" name="amenities[]" id="{{ $ameyrow->id }}" value="{{ $ameyrow->id }}" <?php if (in_array($ameyrow->id, $data['getselectetedAmenityData'])) { echo 'checked'; } ?>>
                                                        <label class="custom-control-label" for="1"><i class="{{ $ameyrow->icon_class }}" style="color: #636363;"></i> {{ $ameyrow->title }} </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        <?php }else{ ?>
                                            <div class="col-md-12" style="text-align:center;">
                                                <span>No Amenities in this amenity category! </span>
                                            </div>
                                        <?php } ?>


                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-amenity" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>



                                <div class="tab-pane <?php active('media');?>" id="media" role="tabpanel">
                                    <div class="loader" id="loader"></div>
                                    <div class="AJloader" id="AJloader"></div>
                                    <div class="row mb-4">
                                        <?php $valuePayable = isset($mediaData->payable) ? $mediaData->payable : 0; ?>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox" style="opacity: 0;">
                                                {{-- <input type="radio" class="pay" name="payimage" value="payable" id="pay" {{ $valuePayable==1 ? 'checked' : '' }}> --}}
                                                <input type="radio" class="pay" name="payimage" value="payable" id="pay" checked>
                                                <label class="custom-control-label" for="1">Payable </label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="payimage" value="nonpayable" id="nonpay" {{ $valuePayable==0 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="2">Non Payable </label>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6" id="multi-imagelistingthumbnail">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0"> Company Thumbnail (<span style="color:red">800 X 800</span>)px</label>
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
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Video Provider</label>
                                                <div class="col-md-12 ">
                                                    <?php $videoplayer = isset($mediaData->video_provider) ? $mediaData->video_provider : ""; ?>
                                                    <select class="form-control" name="video_provider" id="video-provider">
                                                        <option value="youtube" {{ "youtube"==$videoplayer ? 'selected' : '' }}>YouTube</option>
                                                        <option value="vimeo" {{ "vimeo"==$videoplayer ? 'selected' : '' }}>Vimeo</option>
                                                        <option value="html" {{ "html"==$videoplayer ? 'selected' : '' }}>HTML5</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group mb-4 previewBlocks">
                                                @php
                                                $mediadirct_id = isset($mediaData->directory_id)? $mediaData->directory_id : "";
                                                $imagename_thumb = App\Http\Controllers\Admin\Directory\DirectoryController::get_thumbImage($mediadirct_id);
                                                @endphp
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 thumbnailimgPreview" id="thumbnailimgPreview">
                                                    <?php $name_thumb = isset($mediaData->name_thumbnail)? $mediaData->name_thumbnail :"";
                                                     if (!empty($imagename_thumb)) {
                                                        $last_position = end($imagename_thumb);
                                                    ?>
                                                    @foreach($imagename_thumb as $imagenamerow)
                                                        <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-thumbimages="{{ $imagenamerow->name_thumbnail }}">
                                                            <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="thumb">
                                                            <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>
                                                        </div>
                                                    @endforeach

                                                    <?php } else{ ?>
                                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" class="previewBlocks" alt="media-img" width="70px" height="70px" />
                                                    <?php } ?>
                                                    <input type="hidden" id="lastpositionthumbValue" data-payble="<?php echo $valuePayable ?>" value="<?php echo !empty($last_position->position)?$last_position->position:'-1'  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Video URL</label>
                                                <div class="col-md-12 ">
                                                    <input type="text" class="form-control" name="videourl" value="{{ old('videourl', isset($mediaData->video_url))? $mediaData->video_url:'' }}">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="col-md-12" id="multi-listingCover">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing Cover Images(<span style="color:red">960 X 450</span>)px</label>
                                                <div class="col-md-12 imageuploadify">
                                                    <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                    <input type="hidden" name="array_cover_listing[]" class="form-control" id="listing-cover-array" value="">
                                                    <input type="hidden" name="array_cover_images" class="form-control" id="listing-cover-arrayimage">

                                                    <input type="file" class="form-control" name="listing_cover[]" id="cover-img" multiple>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group mb-4 previewBlocks">
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 imgPreview" id="imgPreview">
                                                    @php
                                                    $mediadirct_id = isset($mediaData->directory_id)? $mediaData->directory_id : "";
                                                    $imagenamecover = App\Http\Controllers\Admin\Directory\DirectoryController::getCoverImage($mediadirct_id);
                                                    @endphp

                                                    <?php
                                                    if (count($imagenamecover) != 0){
                                                        $last_position = end($imagenamecover);
                                                    ?>
                                                    @foreach($imagenamecover as $imagenamerow)

                                                    <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-coverimages="{{ $imagenamerow->name_thumbnail }}">
                                                        <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="cover">
                                                        <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                                    </div>
                                                    @endforeach

                                                    <?php } else{ ?>

                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-cover-img" alt="directory-img" width="70px" height="70px" class="previewBlocks" />
                                                    <?php } ?>

                                                    <input type="hidden" id="lastpositioncoverValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

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
                                                <label class="col-md-12 p-0" style="display: flex;">Listing gallery images (<span style="color:red">1000 X 1000 </span>)px
                                                    <div id="tooltip">
                                                        <span id="tooltipText">Best result with (1000 X 1000)px - other dimensions are acceptable but visualization can be poor.</span>
                                                        <span><i class="fas fa-info-circle" ></i></span>
                                                    </div>
                                                </label>

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
                                                        All <input type="checkbox" id="checkAll_gal" class="checkall_gal" style="margin: -8px 0px 4px 20px;">
                                                    </div>
                                                    <a class="btn btn-danger btn-sm" id="deleteAllSelectedGal" style="margin: 0px 0px 0px 15px;width: auto;">
                                                        Delete In Bulk
                                                    </a>
                                                </div>
                                                <?php } ?>
                                                <hr>
                                                <label class="col-md-12 p-0">Preview</label>

                                                <div class="col-md-12 listgallerypreview show-more-height" id="listgallerypreview">
                                                    <?php
                                                    if (!empty($imagenamegalleryChunk)) {
                                                        $last_position = end($imagenamegalleryChunk);
                                                        foreach($imagenamegalleryChunk as $imageDirectory){
                                                            foreach($imageDirectory as $imagenamerow){
                                                    ?>
                                                    @if($imagenamerow->name_thumbnail)
                                                        <div class="thumbnail existing-img" data-position="{{ $imagenamerow->position }}" data-galimages="{{ $imagenamerow->name_thumbnail }}">
                                                            <img src="{{env('IMAGE_PATH').'images/directory/'.$imagenamerow->name_thumbnail.'.webp' }}" data-id="{{ $imagenamerow->id }}" data-value="gal">
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

                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-media" class="btn btn-info listsubmit">Submit</button>
                                    </div>

                                </div>



                                <div class="tab-pane <?php active('seo');?>" id="seo" role="tabpanel">
                                    <div class="row">
                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach($data['languagesData'] as $languagesrow)
                                            {{-- <li class="nav-item">
                                                <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="<?php echo "#" . $languagesrow->name . "_" . $languagesrow->id; ?>" role="tab">
                                                    <span>{{ $languagesrow->name }}</span>
                                                </a>
                                            </li> --}}
                                            @endforeach
                                        </ul>

                                        <div class="tab-content cust-tab-cont">

                                             <div class="tab-pane active" id="English_1" role="tabpanel">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Meta keywords</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="meta_keywords_en" value="{{ old('meta_keywords_en', $seoAndTraslationData->meta_keyword??'') }}" placeholder="Enter Meta Keywords Name in english" class="metakey form-control p-0 border-0" autocomplete=off>
                                                        <input type="hidden" name="seolangtranlation_en" value="English_1" class="form-control p-0 border-0">
                                                    </div>
                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Meta description</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <textarea rows="5" id="metadesc" name="meta_description_en"  onkeyup="limitWords(this.id)" class=" metaDescp form-control p-0 border-0">{{ old('meta_description_en', $seoAndTraslationData->meta_description??'') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                    <input type="hidden" name="page_type" value="{{ $seoAndTraslationData->page_type??'' }}" class="form-control p-0 border-0">
                                    <input type="hidden" name="module_id" value="{{ $seoAndTraslationData->module_id??'' }}" class="form-control p-0 border-0">


                                        </div>
                                        {{-- <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Directory Category</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <select multiple class="form-control select2" name="category[]" id="par-category" onChange="getSelectedOptions(this)" style="width:100%">
                                                    @foreach($data['parent_category'] as $categorysrow)
                                                    <option value="{{ $categorysrow->id }}" <?php if(in_array($categorysrow->id,$data['categoryData'])){echo "selected";} ?>> {{ $categorysrow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
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
                                                <select class="form-control select2" name="category" id="par-category" onChange="getSubCategory(this.value)" style="width:100%">
                                                    @foreach($data['parent_category'] as $categorysrow)
                                                    <option value="{{ $categorysrow->id }}" {{ $categorysrow->id == $mainCategorrId ? 'selected' : '' }}>{{ $categorysrow->name }}</option>
                                                    <?php /* ?><option value="{{ $categorysrow->id }}" <?php if(in_array($categorysrow->id,$data['categoryData'])){echo "selected";} ?>> {{ $categorysrow->name }}</option><?php */ ?>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Company Sub-Category</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <select multiple class="form-control select2" name="sub_category[]" id="subcategory" onChange="getSelectedOptions(this)" style="width:100%">
                                                    @foreach($data['child_category'] as $child_categorysrow)
                                                    <option value="{{ $child_categorysrow['id'] }}" <?php if(in_array($child_categorysrow['id'],$data['subcategoryData'])){echo "selected";} ?>> {{ $child_categorysrow['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Tags(select category first!)</label>
                                            <div class="col-md-12 ">
                                                <select multiple class="form-control select2" name="simpletags[]" id="par-tags" style="width:100%">
                                                    <option></option>
                                                    @foreach( $data['tags'] as $tagsrow)
                                                    <option value="{{ $tagsrow['id'] }}" <?php if(in_array($tagsrow['id'], $data['tagsData'])){echo "selected";} ?>> {{ $tagsrow['tags'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php /* ----------- DON'T UNCOMMENT IT TILL FURTHER NOITFY. (BY MADHULIKA) ----------------------------
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Own Tags(select category first!)</label>
                                            <div class="col-md-12 ">
                                                <select multiple class="form-control select2" name="owntags[]" id="par-owntags" style="width:100%">
                                                    <option></option>
                                                    @foreach($data['owntags'] as $owntagsrow)
                                                    <option value="{{ $owntagsrow->id }}" <?php if(in_array($owntagsrow->id,$data['ownTagsData'])){echo "selected";} ?>> {{ $owntagsrow->tags }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Select Directory Own Tags</label>
                                            <div class="col-md-12 ">
                                                select multiple class="form-control select2" name="direcowntags[]" id="par-dircowntags" style="width:100%">
                                                    <option></option>
                                                    @foreach($data['directoryowntags'] as $owntagsrow)
                                                    <option value="{{ $owntagsrow->id }}" <?php if(in_array($owntagsrow->id,$data['directoryownTagsData'])){echo "selected";} ?>> {{ $owntagsrow->tag_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Create New Directory Own Tags</label>
                                            <div class="col-md-12 ">
                                                <input type="text" name="createnewowntag" value="{{ old('createnewowntag') }}" placeholder="Enter tags with comma seperated eg:tag1,tag2..." class="form-control border-0" autocomplete=off>
                                            </div>
                                        </div>
                                        */?>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-seo" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>


                                <div class="tab-pane <?php active('schedule');?>" id="schedule" role="tabpanel">
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
                                                    <textarea rows="5" name="add_normal_comment" id="add-comment" class="form-control border-0">{{ old('$add_normal_comment', $basicData->schedule_normal_comment ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Schedule Template</label>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="normaldays" value="no" id="pay" {{ $basicData->is_normaltemplate=='no' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="normaldays" value="yes" id="nonpay" {{ $basicData->is_normaltemplate=='yes' ? 'checked' : '' }} style="margin-left: 20px;">
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group mb-4" id="normaldays-template" style="display:none;">
                                        <label class="col-md-12 p-0">Normal Days Template</label>
                                        <div class="col-md-12 ">

                                            <select class="form-control" name="normaldaytemplateselect" onchange="getNormalTemplateOption(this.value);" style="width:100%">
                                                <option>Select Template</option>
                                                @foreach($data['normaldaysdata'] as $normaldaysdatarow)
                                                <option value="{{ $normaldaysdatarow->id }}" {{ $normaldaysdatarow->id == $basicData->is_normaltemplate_id ? 'selected' : '' }}> {{ $normaldaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <?php if($basicData->is_normaltemplate=='yes'){ ?>
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
                                                <tr class=" <?= $value;?> <?= $value.'_template_row_'.$j;?>">
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
                                                        <?php if($j==0){?><button type="button" class="btn btn-info" id="" onclick="addTemplateDayBlock('<?= $value;?>')">+ Hours</button>
                                                        <?php }else{?>
                                                        <button class="btn btn-danger" type="button" onclick="removeTemplateDayBlock('<?= $value;?>',<?= $j;?>)">- Hours</button>
                                                        <?php } ?>
                                                    <td>
                                                </tr>
                                                <?php $j++; } ?>
                                                <?php } ?>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    <?php } ?>

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
                                            <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
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

                                    <?php /* ---------------------- START Public holidays --------------------------------  */ ?>

                                    <div class="row mb-4">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <div class="panel-heading cust-tab-head">
                                                <h4 class="box-title">Public holidays</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="publicdays" value="no" id="pay" {{ $basicData->is_publictemplate=='no' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="publicdays" value="yes" id="nonpay" {{ $basicData->is_publictemplate=='yes' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4" id="publicdays-template" style="display:none;">
                                        <label class="col-md-12 p-0">Public Holidays Template</label>
                                        <div class="col-md-12 ">

                                            <select class="form-control" id="publicdaystemplateselect" name="publicdaystemplateselect" onchange="getpublicTemplateOption(this.value);" style="width:100%">
                                                <option value="none">Select Template</option>
                                                @foreach($data['publicdaysdata'] as $publicdaysdatarow)
                                                <option value="{{ $publicdaysdatarow->id }}" {{ $publicdaysdatarow->id == $basicData->is_publictemplate_id ? 'selected' : '' }}> {{ $publicdaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="actualdataforyesno" value="{{ $basicData->is_publictemplate }}">
                                    <input type="hidden" name="actualdataforselectedTemplateId" value="{{ $basicData->is_publictemplate_id }}">
                                    <?php if($basicData->is_publictemplate=='yes'){if($data['scheduleHolidayDatacount']!=0){ ?>
                                    <table id="temp-publicdaysdetails-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['scheduleHolidayData'] as $holidayrow)
                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                <td>
                                                    {{ $holidayrow->custom_date }}
                                                </td>
                                                <td>
                                                    {{ $holidayrow->note }}
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
                                                    <button type="button" class="btn btn-danger deleterow" data-id="{{ $holidayrow->id }}">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <?php }} ?>
                                    <?php if($basicData->is_publictemplate=='no'){if($data['scheduleHolidayDatacount']!=0){ ?>
                                    <table id="publicdaysdetails-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['scheduleHolidayData'] as $holidayrow)
                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                <td>
                                                    {{ $holidayrow->custom_date }}
                                                </td>
                                                <td>
                                                    {{ $holidayrow->note }}
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
                                                    <button type="button" class="btn btn-danger deleterow" data-id="{{ $holidayrow->id }}">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <?php }} ?>
                                    <table id="publicdays-table" class="table text-nowrap table-info" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Add Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="row_public time_slot_row_0">
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
                                                            <input type="text" name="note" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                        </div>
                                                    </div>
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
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td><button type="submit" id="listsubmit-schedule" class="btn btn-warning listsubmit">Save Time Slot</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php /* ---------------------- END Public holidays --------------------------------  */ ?>
                                    <?php /* ---------------------- START Exceptional holidays --------------------------------  */ ?>
                                    <div class="row mb-4">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <div class="panel-heading cust-tab-head">
                                                <h4 class="box-title"> Exceptional holidays</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row mb-4">
                                        <label class="col-md-12 p-0">Schedule Template</label>
                                            <div class="col-md-3">
                                               <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="exception_days" value="no" id="pay" {{ $basicData->is_exceptionaltemplate=='no' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="exception_days" value="yes" id="nonpay" {{ $basicData->is_exceptionaltemplate=='yes' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4" id="exception_days-template">
                                        <label class="col-md-12 p-0">Exceptional Holidays Template</label>
                                        <div class="col-md-12 ">

                                            <select class="form-control" id="exception-daystemplateselect" name="exception_daystemplateselect" onchange ="getExceptionTemplateOption(this.value)" style="width:100%">
                                                <option value="none">Select Template</option>
                                                @foreach($data['exeptionaldaysdata'] as $exeptionaldaysdatarow)
                                                <option value="{{ $exeptionaldaysdatarow->id }}" {{ $exeptionaldaysdatarow->id == $basicData->is_exceptionaltemplate_id ? 'selected' : '' }}> {{ $exeptionaldaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="exeActualdataforyesno" value="{{ $basicData->is_exceptionaltemplate }}">
                                    <input type="hidden" name="exeActualdataforselectedTemplateId" value="{{ $basicData->is_exceptionaltemplate_id }}">
                                    <?php if($basicData->is_exceptionaltemplate=='yes'){if($data['scheduleExceptionHolidayDatacount']!=0){ ?>
                                    <table id="temp-exceptiondaysdetails-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['scheduleExceptionHolidayData'] as $holidayrow)
                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                <td>
                                                    {{ $holidayrow->custom_date }}
                                                </td>
                                                <td>
                                                    {{ $holidayrow->note }}
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
                                                    <button type="button" class="btn btn-danger deleterowexecption" data-id="{{ $holidayrow->id }}">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <?php } } ?>
                                    <?php if($basicData->is_exceptionaltemplate=='no'){if($data['scheduleExceptionHolidayDatacount']!=0){ ?>
                                    <table id="exceptiondaysdetails-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['scheduleExceptionHolidayData'] as $holidayrow)
                                            <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>
                                            <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                <td>
                                                    {{ $holidayrow->custom_date }}
                                                </td>
                                                <td>
                                                    {{ $holidayrow->note }}
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
                                                    <button type="button" class="btn btn-danger deleterowexecption" data-id="{{ $holidayrow->id }}">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <?php } } ?>
                                    <table id="exceptiondays-table" class="table text-nowrap table-info" style="display:none;" >
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Note</th>
                                                <th class="border-top-0">Open</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Add Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="row_public time_slot_row_0">
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
                                                            <input type="text" name="exception_note" placeholder="Add note" class="form-control border-0" autocomplete=off>
                                                        </div>
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
                                                    <button type="button" class="btn btn-primary" id="" onclick="addexceptionTimeBlockPublic()">+ Hours</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td><button type="submit" id="listsubmit-schedule" class="btn btn-warning listsubmit">Save Time Slot</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php /* ---------------------- END Exceptional holidays --------------------------------  */ ?>
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-schedule" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>
                                <div class="tab-pane <?php active('type');?>" id="dirtype" role="tabpanel">
                                    <div class="row">
                                        <?php $radioselectecheckeddata = isset($directoryTypeData[0]->category_id)? $directoryTypeData[0]->category_id : 0;
                                        $blockNum = isset($directoryTypeDatainreverse[0]->block_num)? $directoryTypeDatainreverse[0]->block_num : "novalue";
                                         ?>
                                        @foreach($data['type'] as $typerow)
                                        <div class="col-md-3 p-10">
                                            <div class="custom-control custom-checkbox">
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
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-dirtype" class="btn btn-info listsubmit">Submit</button>
                                    </div>
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
                                                    <input type="text" name="website" value="{{ old('website', isset($contactData->website)?$contactData->website:'') }}" placeholder="Enter Website" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Facebook</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="facebook" value="{{ old('facebook', isset($contactData->facebook)?$contactData->facebook:'') }}" placeholder="www.facebook.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Twitter</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="twitter" value="{{ old('twitter', isset($contactData->twitter)?$contactData->twitter:'') }}" placeholder="www.twitter.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Linkedin</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="linkedin" value="{{ old('linkedin', isset($contactData->linkedin)?$contactData->linkedin:'') }}" placeholder="www.linkedin.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Pinterest</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="pinterest" value="{{ old('pinterest', isset($contactData->pinterest)?$contactData->pinterest:'') }}" placeholder="www.pinterest.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Whatsapp</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="whatsapp" value="{{ old('whatsapp', isset($contactData->whatsapp)?$contactData->whatsapp:'') }}" placeholder="www.whatsapp.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Instagram</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="instagram" value="{{ old('instagram', isset($contactData->instagram)?$contactData->instagram:'') }}" placeholder="www.instagram.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Tiktok</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="tiktok" value="{{ old('tiktok', isset($contactData->tiktok)?$contactData->tiktok:'') }}" placeholder="www.tiktok.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Youtube</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="youtube" value="{{ old('youtube', isset($contactData->youtube)?$contactData->youtube:'') }}" placeholder="www.youtube.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Snapchat</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="snapchat" value="{{ old('snapchat', isset($contactData->snapchat)?$contactData->snapchat:'') }}" placeholder="www.snapchat.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="website_count" value="<?php echo count($websitelink); ?>" id="wesitecount" />
                                    <div class="row mb-4">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <div class="panel-heading cust-tab-head">
                                                <h4 class="box-title">Website Link</h4>
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
                                                <h4 class="box-title">Assortment Link</h4>
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
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-links" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>
                                <div class="tab-pane <?php active('claim');?>"  id="claim" role="tabpanel">

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
                                                    <textarea rows="3" name="claim_description" id="claimtext" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('claim_description') }}</textarea>
                                                </div>
                                                <span class="text-danger">@error('claim_description'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" id="listsubmit-dirtype" class="btn btn-success listsubmit">Add Claim</button>
                                            <!-- <button type="button" class="btn btn-success" class="add_claim_button" title="Add field" onclick=addmoreClaimintoTable($basicData->id)>Add Claim</button> -->
                                        </div>
                                    </div>
                                    <hr/>
                                    <?php if($data['directory_claim_count']>0){ ?>
                                        <div class="row">
                                            <?php foreach($data['directory_claim'] as $value){ ?>
                                                <div class="claim-data-result">
                                                    <p><strong>Subject</strong></p>
                                                    <p>{{ $value->subject }}</p>
                                                    <p><strong>Description</strong></p>
                                                    <p>{{ $value->description }}</p>
                                                </div>
                                            <?php } ?>

                                    <?php } ?>
                                    <!-- <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Claim</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" name="claim" id="claimtext" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('$claim', $basicData->claim ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <input type="hidden" id="active-tab" class="active-tab" name="active_tab" value="">
                                    <div class="text-center">
                                        <button type="submit" id="listsubmit-dirtype" class="btn btn-info listsubmit">Submit</button>
                                    </div>
                                </div>
                                <div style="display:none;" class="tab-pane" id="finish" role="tabpanel">
                                    <div class="col-sm-12">
                                        <div class="text-center">
                                            <h2 class="mt-0">
                                                <i class="entypo-check"></i>
                                            </h2>
                                            <h3 class="mt-0">Thank you !</h3>
                                            <p class="w-75 mb-2 mx-auto">You are almost there. Please check if you have provided all the necessary things.</p>
                                            <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                                            <p> <button type="submit" class="btn btn-info">update</button></p>
                                        </div>
                                    </div>
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

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $("#checkAll2").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
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

    function type_galley_submit(key){
        var active_button = $('ul.dir-tabs li a.active');
        $('.active-tab').val(active_button.text());
    }

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
                        <span class="closebtn3">
                            <i  id="pdf_type_key_delete_${key}" onClick="directory_pdf_type_delete('${value.id}','${key}','${cat_id}','${directory_id}','${block_num}')" class="fas fa-trash" style="color:#ff0505;margin: -8px;" aria-hidden="true"></i>
                        </span>
                        <input type="checkbox" name="ids" class="cb-element" value="${value.id}" />
                        <iframe
                            src="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.pdf_name}?embedded=true"
                            style="width: 100%; height: 400px; border: none;"
                            frameborder="0">
                        </iframe>


                    </div>

                    `);
                });
            }
        });
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

                $("#type_gallery_list").html('');
                $('#type_galleryModal').modal('show');
                $('.bulk_delete').show();

                $.each(result.DirectoryTypeGallery, function(key, value) {
                    $("#type_gallery_list").append(`

                    <div class="existing-img gtype_img_sk col-md-2 col-2 gallerytypeimages" data-id="${value.id}" data-position="${value.position}" data-gallerytypeimages="${value.image_name}" id="gtype_key_${key}">
                        <input type="checkbox" name="ids" class="cb-element" value="${value.id}" />
                        <span class="closebtn2">
                            <i  id="gtype_key_delete_${key}" onClick="directory_type_delete('${value.id}','${key}','${cat_id}','${directory_id}','${block_num}')" class="fas fa-trash" style="color:#ff0505;margin: -8px;" aria-hidden="true"></i>
                        </span>
                        <a href="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" data-lightbox="allGalleryImages">
                            <img data-id="${value.id}" src="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" class="img-fluid gtype_img"/>
                        </a>
                    </div>

                    `);
                });
            }
        });
    };

    function type_galley_img(key) {
        var files = document.getElementById('type_galley_img_'+key).files;
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

    function getTrademarkOptions(selcat) {
        var selobj = selcat.value;
        //console.log(selobj);
        $("#par-trademark").html('');
        $.ajax({
            url: baseUrl + '/admin/get-trademarks-by-categoriesid',

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
        var opts = []
            , opt;
        var len = sel.options.length;
        //console.log("Length: ",len);
        for (var i = 0; i < len; i++) {
            opt = sel.options[i];

            if (opt.selected) {
                opts.push(opt.value);
                // alert(opt.value);
                //console.log("opt: ",opt.value);
            }
            console.log("opts : ", opts);
        }
        $(".amenitiesdata").html("");
        // return opts;
        if (opts.length != 0) {
            $.ajax({
                url: baseUrl + '/admin/get-amenity-by-amenitycategory',

                type: "POST"
                , data: {
                    amcat_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                        $.each(result, function(key, value) {
                            $(".amenitiesdata").append(`<div class="amenitycategories_name"><span>`+key+`</span></div>`);

                            if(value.length!=0){
                                $.each(value, function(key, val) {
                                    $(".amenitiesdata").append(`<div class="col-md-3 p-10">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="form-check-input" name="amenities[]" id="` + val.id + `" value="` + val.id + `">
                                                    <label class="custom-control-label" for="1"><i class="` + val.icon_class + `" style="color: #636363;"></i> ` + val.title +`</label>
                                                </div>
                                            </div>
                                    `);
                                });
                            }else{
                                $(".amenitiesdata").append(`<div class="col-md-12" style="text-align:center;">
                                            <span>No Amenities in this amenity category! </span>
                                        </div>
                                `);
                            }

                        });


                    // $.each(result.amenity, function(key, value) {
                    //     $(".amenitiesdata").append(`<div class="col-md-3 p-10">
                    //                                     <div class="custom-control custom-checkbox">
                    //                                         <input type="checkbox" class="form-check-input" name="amenities[]" id="` + value.id + `" value="` + value.id + `">
                    //                                         <label class="custom-control-label" for="1"><i class="` + value.icon_class + `" style="color: #636363;"></i> ` + value.title + `</label>
                    //                                     </div>
                    //                                 </div>
                    //                         `);
                    // });
                }
            });
        }

    }
    function getNormalTemplateOption(teplateId) {
        $.ajax({
            url: baseUrl + '/admin/get-normaltemplate-by-id',

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
    function getpublicTemplateOption(publicseleid){
        $.ajax({
            url: baseUrl + '/admin/get-publictemplate-by-id',

            type: "POST",
            data: {
                publicsele_id: publicseleid,
                _token: '{{csrf_token()}}'
            },
            dataType: 'html',
            success: function(result) {
                //console.log(result);
                $('#temp-publicdaysdetails-table').hide();
                $('#public-template').remove();
                $('#publicdays-table').after(result);

            }
        });
    }
    function getExceptionTemplateOption(exceptionseleid){
        $.ajax({
            url: baseUrl + '/admin/get-exceptiontemplate-by-id',

            type: "POST",
            data: {
                exceptionsele_id: exceptionseleid,
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

        $("#checkAll_gal").click(function(){
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

        $("#loader").fadeOut("slow");

        $('.modal_header_close_btn').on('click', function() {
            $('#type_galleryModal').modal('hide');
        });

        var normaldaysselectvalue = $('input[name=normaldays]:checked', '#editsection').val();
        var publicselectvalue = $('input[name=publicdays]:checked', '#editsection').val();
        var exception_daysselectvalue = $('input[name=exception_days]:checked', '#editsection').val();
       // console.log("Normal daye value: ", normaldaysselectvalue);
        if (normaldaysselectvalue != "yes") {
            $('#normaldays-template').hide();
            $('#nomaldays-template-table').hide();
            $('#nomaldays-table').show();
        } else {
            $('#normaldays-template').show();
            $('#nomaldays-template-table').show();
            $('#nomaldays-table').hide();
        }
        if (publicselectvalue != "yes") {
            $('#publicdays-template').hide();
            $('#publicdays-table').show();
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
            $('#publicdaysdetails-table').hide();
            $('#temp-exceptiondaysdetails-table').show();
        }
    });


    $("input[name=normaldays]").change(function() {

        if ($(this).val() != "yes") {
            $('#normaldays-template').hide();
            $('#nomaldays-template-table').hide();
            $('#nomaldays-table').show();
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
        } else {
            $('#exception_days-template').show();
            $('#exceptiondays-table').hide();
            $('#exceptiondaysdetails-table').hide();
            $('#temp-exceptiondaysdetails-table').show();
        }
    });

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

        var selectedvalueTags = $("#par-tags").val();

        console.log("Selected Value: ", selectedvalueTags);
        var selectedvalueTagsqwer = [];
        for(var k=0;k<selectedvalueTags.length;k++){
            selectedvalueTagsqwer.push(parseInt(selectedvalueTags[k]));
        }
        // var selectedvalueTagsqwer = ["2","10","12"];
        // console.log("Selected Value second: ",selectedvalueTagsqwer);
        // return opts;
        if (opts.length != 0) {
            $.ajax({
                //url: baseUrl + '/get-tags-by-categoriesid',
                url: baseUrl + '/get-dirtags-by-subcategoriesid',

                type: "POST"
                , data: {
                    category_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log(result);
                    //$('#par-tags').html('<option value="">Select Category First</option>');
                    // $.each(result.tags, function(key, value) {

                    //     //if($.inArray(value.id, selectedvalueTagsqwer)) { valuetags = "selected"; }else{ valuetags = ""; }

                    //     //console.log("VALUE : ",valuetags);
                    //     $("#par-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                    //     // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    // });
                    // $.each(result.owntags, function(key, value) {
                    //     $("#par-owntags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                    // });
                    $("#par-tags").html("");
                    var valuetags = "";

                    $.each(result.tags, function(key, value) {
                        if(jQuery.inArray(value.id,selectedvalueTagsqwer) == -1){ valuetags = ""; }else{ valuetags = "selected"; }
                        $("#par-tags").append('<option value="' + value.id + '"'+valuetags+'>' + value.tags + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });

                }
            });
        }

    }

    function getSubCategory(cat_id) {
        //alert("Category id: "+cat_id);
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
                });

            }
        });
    }

    $(document).on('click', ".deleterow", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = "#tblholi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/admin/delete-holiday-record';
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

    $(document).on('click', ".deleterowexecption", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = "#tblholi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/admin/delete-execption_holiday-record';
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

        $(".select2").select2({
            placeholder: "Select "
            , allowClear: true

        });

        var payble = $('input[name=payimage]:checked', '#editsection').val();
        $("input[name=payimage]").change(function() {

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
                    imagevalue = '/admin/get-gallery-img';
                } else if (imagevalue == "cover") {
                    imagevalue = '/admin/get-cover-img';
                } else if (imagevalue == "thumb") {
                    imagevalue = '/admin/get-thumb-img';
                } else {
                    imagevalue = "";
                }
                //console.log('imageid',imageid);
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
            }
            else{
                return false;
            }

        });

        $(document).on('click', ".delete_all_gallery", function() {
            if (!confirm('Are you sure you want to delete all gallery images?')) {
                e.preventDefault();
                return false;
           }
            var directory_id =  {{isset($mediaData->directory_id)? $mediaData->directory_id : ""}};
            galleryimageurl = '/admin/gallery-image-delete-all';
            //console.log('imageid',imageid);
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
                        //val.remove();
                        toastr.success('Gallery has been deleted');
                        // setTimeout(function() {
                        //     location.reload();
                        // }, 500);
                        $('.delete_all').hide();
                        //$('.listgallerypreview').hide();
                        $('.listgallerypreview').html('<img src="{{ asset('assets/plugins/images/placeholder.png') }}" width="70px" height="70px" class="previewBlocks" />');
                    }else{
                        toastr.error('Something went wrong');
                    }

                }
            });


        });
    });


    $(function() {

        var multiImgPreview = function(input, imgPreviewPlaceholder, position, positionId, type) {

            var countFiles = input.files.length;
            var imgPath = input.value;
            var k = position;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

            var imgPreviewPlaceholder = $(imgPreviewPlaceholder);

            $(".imageuploadify-overlay" + type).css("display", "block");

            //if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {

                    var total = k - 1;
                    //for (var i = 0; i < countFiles; i++) {
                    for (var i=0; i<input.files.length; i++) {
                        var ext = input.files[i].name.substr(-3);
                        if(ext!== "mp4" && ext!== "m4v" && ext!== "fv4" && ext!== "MOV" && ext!== "WMV" && ext!== "HTML5" && ext!== "AVI")  {

                            if (typeof(FileReader) != "undefined") {

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

            //} else {
                //alert("Please select images only");
                //$(".imageuploadify-overlay" + type).css("display", "none");
            //}
        };

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

            var payble = $('input[name=payimage]:checked', '#editsection').val();
            if (payble != 'payable') {
                if ($("#cover-img")[0].files.length > 1) {
                    $("#cover-img").val(null);
                    alert("You can select only 1 images");
                    return;
                }
            }
            var position = $('#lastpositioncoverValue').val();
            //if(position=='novalue' ||position=='-1'){
            $("#preview-cover-img").hide();
            //}
            position = position ? position : 0;
            multiImgPreview(this, 'div.imgPreview', parseInt(position) + 1, '#lastpositionthumbValue', 2);

        });


        $('#gallist').on('change', function() {
            var fileslength = $("#gallist")[0].files.length;
            if (fileslength > 0) {
                alert("Click on 'Save' button after each upload");
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


            multiImgPreview(this, 'div.listgallerypreview', parseInt(position) + 1, '#lastpositiongallValue', '');
        });


    });
    $(document).ready(function() {
        $("#imgPreview,#thumbnailimgPreview,#listgallerypreview").sortable();

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

    // function submitForm() {

    //     var image_thumb_arr_exist = [];
    //     var image_thumb_arr_new = [];
    //     var image_cover_arr = [];
    //     var image_gal_arr = [];
    //     var image_trade_arr = [];

    //     $('#thumbnailimgPreview div').each(function() {
    //         if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
    //             var id = $(this).data('position');
    //             image_thumb_arr_new.push(id);
    //             $("#listing-thumb-array-new").val(image_thumb_arr_new);
    //         }
    //     });
    //     var thumbimages = [];
    //     $('.thumbnailimgPreview div').each(function() {
    //         thumbimages.push($(this).data('thumbimages'));
    //     });
    //     $('#thumb_hidden_img').val(thumbimages.join(','));

    //     $('#imgPreview div').each(function() {
    //         if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
    //             var id = $(this).data('position');
    //             image_cover_arr.push(id);
    //             $("#listing-cover-array").val(image_cover_arr);
    //         }
    //     });

    //     var coverimg = [];
    //     $('.imgPreview div').each(function() {
    //         coverimg.push($(this).data('coverimages'));
    //     });
    //     $('#listing-cover-arrayimage').val(coverimg.join(','));

    //     $('#listgallerypreview div').each(function() {
    //         if ($(this).hasClass("newimage") || $(this).hasClass("existing-img")) {
    //             var id = $(this).data('position');
    //             image_gal_arr.push(id);
    //             //console.log(imageids_arr);
    //             $("#listing-gallery-array").val(image_gal_arr);
    //         }
    //     });

    //     var gallerypreview = [];
    //     $('.listgallerypreview div').each(function() {
    //         gallerypreview.push($(this).data('galimages'));
    //     });
    //     $('#listing-gallery-arrayimage').val(gallerypreview.join(','));

    //     return;
    // }

</script>


<script>
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

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo env('GOOGLE_MAP_API_KEY') ?>&callback=initAutocomplete&libraries=places"></script>
<script>

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

                //let latLng = new google.maps.LatLng(-23.6815314, -46.875502);
                //console.log(this.inputLat.val());
                if (this.inputLat.val() && this.inputLng.val()) {
                    latLng = new google.maps.LatLng(this.inputLat.val(), this.inputLng.val());
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
                    map.setCenter(marker.getPosition());
                })

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

</script>

<script>
    $(document).ready(function() {
        var country_id_default = $('select#country-dropdown option:selected').val();
        var stateidselected = $('#stateid').val();
        var cityidselected = $('#cityid').val();
        console.log("state id: ", stateidselected);
        console.log("city id: ", cityidselected);
        $.ajax({
            url: baseUrl + '/admin/get-states-by-country'
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
        $.ajax({
            url: baseUrl + '/admin/get-cities-by-state'
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
                    $("#city-dropdown").append('<option value="' + value.id + '" ' + valuecity + ' >' + value.name + '</option>');
                });
            }
        });

        $('#country-dropdown').on('change', function() {
            var country_id = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: baseUrl + '/admin/get-states-by-country',

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
                url: baseUrl + '/admin/get-cities-by-state'
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

</script>
<script>
    $(document).ready(function() {
        var typedata1 = $('input[name=hotel_type]:checked').val();

        var blockcount = $('#blockone').length;
        console.log(blockdirectoryId);
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

</script>
<script>
    function addSection() {
        var typedata = $('input[name=hotel_type]:checked', '#editsection').val();
        var blockcount = $('.blocksCount').length;
        // console.log("Block count on edit add section click: ",typedata);
        $('#total_block').val(blockcount);
        getTypeBlock(typedata, blockcount, "add");
    }

</script>
<script>
    var blockdirectoryId = $('#directoryId').val();
    var blockNumber = $('#total_block_from_database').val();

    function getTypeBlock(typedata, blockcount, addnewonedit) {
        var editvalue = (addnewonedit != "edit") ? "add" : "edit";
        console.log("Block: ", editvalue);
        $.ajax({
            url: baseUrl + '/admin/get-type-block'
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

</script>
<script>
    function removeSection(count) {
        $('#block_' + count).remove();
    }

</script>
<script type="text/javascript">
    function addTimeBlockPublic() {
        var numItems = $('.row_public').length;
        var insertBlock = parseInt(numItems - 1);
        // alert(insertBlock);
        $('<tr class="row_public time_slot_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlock(`time_slot_row_' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.time_slot_row_' + insertBlock);
    }

    function removeTimeBlock(blockClass) {
        $('.' + blockClass).remove();
    }

    function addDayBlock(blockClass) {
        var numItems = $('.' + blockClass).length;
        var insertBlock = parseInt(numItems - 1);
        // alert(insertBlock);
        $('<tr class="' + blockClass + ' ' + blockClass + '_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_row_' + insertBlock);
    }

    function removeDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_row_' + numItems).remove();
    }
    function addTemplateDayBlock(blockClass) {
        var numItems = $('.' + blockClass).length;
        var insertBlock = parseInt(numItems - 2);
        var numItemstemp = parseInt(numItems - 1);
        // alert(insertBlock);
        $('<tr class="' + blockClass + ' ' + blockClass + '_template_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_template_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_template_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTemplateDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_template_row_' + insertBlock);
    }

    function removeTemplateDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_template_row_' + numItems).remove();
    }

</script>

<script>
    let lineno = $('#wesitecount').val();
    lineno++;

    function addwebSection() {

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

    function removewebSection(count) {
        // var countweb = $('.weblink').length;
        // console.log(countweb);
        $('#weblink_' + count).remove();
    }

</script>
<script>
    let linenoass = $('#assortcount').val();
    linenoass++;

    function addAssortmentSection() {

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

    function removeAssortmentSection(count) {
        $('#assortlink_' + count).remove();
    }

</script>

<script>
    $(document).ready(function() {
        $(".listsubmit").on("click", function(e) {
            var active_button = $('ul.dir-tabs li a.active');
            $('#active-tab').val(active_button.text());
            $('.active-tab').val(active_button.text());
        });
    });

</script>
<script type="text/javascript">
    var form = $('#editsection')
        , original = form.serialize()

    form.submit(function() {
        window.onbeforeunload = null
    })

    window.onbeforeunload = function() {
        if (form.serialize() != original)
            return 'Make sure you have save your data before leaving the page!'
    }

</script>

<script type="text/javascript">
    $('.directorydesc').on('keypress keyup keydown',function(event) {
  // create the event
   var press = jQuery.Event(event.type);
   var code = event.keyCode || event.which;
   press.which = code ;
  // trigger
    $('.metaDescp').val(this.value);
    $('.metaDescp').trigger(event.type, {'event': press});
    });

    $('.metaDescp').on('keypress keyup keydown',function(event) {
    // sample
    console.log(event.type);
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

    $('.metakey').on('keypress keyup keydown',function(event) {
        // sample
        console.log(event.type);
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
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#loader").fadeOut("slow");

        $(".seoinputname_bkp").focusout(function() {
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-" + languagecode).val();
            console.log("languagecode: ", languagecode);
            console.log("Actual Name: ", nameText);
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/admin/directoryseo'
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
@endsection
