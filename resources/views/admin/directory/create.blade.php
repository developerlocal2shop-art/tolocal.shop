@extends('layouts.adminmaster')
@section('title','Company | Add Company')
@section('content')

<style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
    #map {
        margin: 0 auto;
        width: 100%;
        max-width: 100%;
        height: 50vh;
        min-height: 250px;
    }
    .select2-search__field{
        width: 90px !important;
    }
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f2f5f4;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }

    #target {
        width: 345px;
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

    .select2-container-multi {
        width: 300px
    }

    #drop-region {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.05);
        width: 400px;
        padding: 60px 40px;
        text-align: center;
        cursor: pointer;
        transition: .3s;
    }

    #drop-region:hover {
        box-shadow: 0 0 45px rgba(0, 0, 0, 0.1);
    }

    #image-preview {
        margin-top: 20px;
    }

    #image-preview .image-view {
        display: inline-block;
        position: relative;
        margin-right: 13px;
        margin-bottom: 13px;
    }

    #image-preview .image-view img {
        max-width: 100px;
        max-height: 100px;
    }

    #image-preview .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        z-index: 2;
        background: rgba(255, 255, 255, 0.5);
    }

    .cust-tab-cont .tab-pane.active {
        background: none;
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
    .errorClass { border:  1px solid red !important; }

    .count_uploaded_gallery {
        color: #000;
        border-radius: 50%;
        padding: 0px 8px;
        font-size: 14px;
        margin: 0px 10px 0px -28px;
    }
    button#listsubmit-dirtype {
        margin: 27px 0px 2px 3px;
    }
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Add Company<a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">

                </div>
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
        @if(\Session::has('errorimg'))
        <div class="alert alert-danger">
            {!! \Session::get('errorimg') !!}
        </div>
        @endif

        <!-- Column -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.directory.submit') }}" onsubmit="return submitForm()" id="directoryForm" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="row">
                    <p><strong>Note : <i class="fas fa-eye" aria-hidden="true"></i> If you create a new company please make a vendor from user menu first.</strong></p>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">

                            <ol class="breadcrumb ms-auto">
                                <li>
                                    @if($errors->any('name'))
                                    {!! implode('', $errors->all('<span class="alert-danger">:message</span>&nbsp')) !!}
                                    @endif
                                </li>
                            </ol>

                            <ul class="nav nav-tabs dir-tabs cust-nav-tab" role="tablist">

                                @php
                                    $fieldsWithErrors = ['name_en', 'email', 'phone','phone_number','trademarkcategory'];
                                    $fieldsWithErrors_seo = ['category', 'sub_category'];
                                    $fieldsWithErrors_media = ['listing_thumb', 'listing_cover','listing_gallery'];
                                @endphp

                                <li class="nav-item" >
                                    <a class="nav-link active" @if(array_intersect($fieldsWithErrors, array_keys($errors->getMessages()))) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif data-bs-toggle="tab" href="#basic" role="tab">
                                        <span>Basic</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" @if($errors->has('address')) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif data-bs-toggle="tab" href="#location" role="tab">
                                        <span>Location</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#dirtype" role="tab">
                                        <span>Type</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#amenities" role="tab">
                                        <span>Amenities</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" @if(array_intersect($fieldsWithErrors_media, array_keys($errors->getMessages()))) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif data-bs-toggle="tab" href="#media" role="tab">
                                        <span>Media</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" @if(array_intersect($fieldsWithErrors_seo, array_keys($errors->getMessages()))) style="background: #fdd6dd; border: 1px solid #b7a7a7;" @endif data-bs-toggle="tab" href="#seo" role="tab">
                                        <span>SEO</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#schedule" role="tab">
                                        <span>Schedule</span>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contact" role="tab">
                                        <span>Links</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#claim" role="tab">
                                        <span>Claim</span>
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#finish" role="tab">
                                        <span>Submit</span>
                                    </a>
                                </li> -->

                            </ul>
                            <div class="tab-content cust-tab-cont">

                                <div class="tab-pane active" id="basic" role="tabpanel">

                                    <ul class="nav nav-tabs " role="tablist">
                                        @foreach($data['languagesData'] as $languagesrow)
                                        <li class="nav-item">
                                            {{--<a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                                <span>{{ $languagesrow->name }}</span>
                                            </a>--}}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content cust-tab-cont">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Select Vendor <span style="color:red;">*</span></label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <select class="form-control" name="vendor_name" required id="vendor-name" onChange="getVendorName(this.value)" style="width:100%">
                                                    <option value="">Select Vendor</option>
                                                    @foreach($data['vendorNameId'] as $vendornamerow)
                                                    <option @if($vendornamerow['id'] == old('vendor_name')) selected @endif value="{{ $vendornamerow['id'] }}" >{{ $vendornamerow['name']." ".$vendornamerow['last_name']."(".$vendornamerow['email'].")" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger">@error('vendor_name'){{$message}} @enderror</span>
                                        </div>
                                        @foreach($data['languagesData'] as $languagesrow)
                                        <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">

                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Name<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input id="cat-{{$languagesrow->code}}" maxlength="40" type="text" name="name_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,40)" data-id="{{$languagesrow->code}}" value="{{ old('name_'.$languagesrow->code) }}" placeholder="Enter Directory Name in {{ $languagesrow->name }}" class="directorynametitle seoinputname form-control border-0 @error('name_'.$languagesrow->code) errorClass @enderror" autocomplete=off>
                                                </div>
                                                <span id="name-{{$languagesrow->code}}" class="text-danger name_error"></span>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">SEO</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="Category SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="seo_error" class="text-danger"></span>
                                            </div>


                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Description</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" maxlength="900" id="dire-discription-{{$languagesrow->code}}" name="description_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,900)" class="directorydesc form-control p-0 border-0" placeholder="Enter Description in {{ $languagesrow->name }}">{{ old('description_'.$languagesrow->code) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">We Offer</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" maxlength="900" id="we-offer-{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,900)" name="weoffer_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter We Offer in {{ $languagesrow->name }}">{{ old('weoffer_'.$languagesrow->code) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">About Us</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" maxlength="3500" id="about-us-{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,3500)" name="aboutus_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter About Us in {{ $languagesrow->name }}">{{ old('aboutus_'.$languagesrow->code) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Featured Type</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <select class="form-control" name="featuretype" id="featuretype">
                                                        <option value="none">None</option>
                                                        <option @if('featured' == old('featuretype')) selected @endif value="featured">Featured</option>
                                                        <option @if('featured' == old('superfeatured')) selected @endif value="superfeatured">Super Featured</option>
                                                    </select>
                                                </div>
                                                <span class="text-danger">@error('featuretype'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Google analytics id</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="google_ana_id" value="{{ old('$google_ana_id') }}" placeholder="Enter Google analytics id" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('google_ana_id'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Company Logo (Size: 350 * 100)</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                <input type="file" class="form-control" name="company_logo" id="company_logo">
                                                </div>
                                            </div>
                                            <span class="text-danger">@error('company_logo'){{$message}} @enderror</span>
                                        </div>
                                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                                    </div>
                                    <hr style="height:2px;color:#8898aa">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group mb-6">
                                                <label class="col-md-12 p-0">
                                                    Register Company Email <span style="color:red;">*</span><span style="font-weight: 600;">(Can be change in future under "User" menu)</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input readonly type="email" id="company-email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail address" class="form-control border-0 @error('email') errorClass @enderror" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Contact Person Name</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="company-person-name" name="name" value="{{ old('name') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Contact Person Phone Number<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" id="company-phone" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter Phone Number" class="form-control border-0 @error('phone_number') errorClass @enderror" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="height:2px;color:#8898aa">
                                    <div class="form-group ">
                                        <label class="col-md-12 p-0">Trademark Category<span style="color:red;">*</span></label>
                                        <div class="col-md-12 ">
                                            <select class="form-control @error('trademarkcategory') errorClass @enderror" name="trademarkcategory" id="trademark-category" onChange="getTrademarkOptions(this)" style="width:100%">
                                                <option value="">Select Trademark Category</option>
                                                @foreach($data['trademarkcategory'] as $categorysrow)
                                                <option @if($categorysrow['id'] == old('trademarkcategory')) selected @endif value="{{ $categorysrow['id'] }}">
                                                    {{ $categorysrow['name'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Trademark(select trademark category first!)</label>
                                        <div class="col-md-12 ">

                                            <select multiple class="form-control select2" name="trademark[]" id="par-trademark">
                                                <option></option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4">
                                            {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-check-input" name="popular" id="" value="1">
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

                                    <button type="submit" id="listsubmit-basic" class="btn btn-info listsubmit">submit</button>
                                </div>

                                <div class="tab-pane" id="location" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Address<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input id="pac-input" class="form-control border-0 @error('address') errorClass @enderror" value="{{ old('address') }}" type="text" placeholder="address" name="address" autocomplete=off>
                                                    {{-- <textarea rows="5" name="address" id="addresstext" class="form-control p-0 border-0"></textarea> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Country<span style="color:red;">*</span></label>
                                                <div class="col-md-12 ">
                                                    <input type="text" name="country_id" id="country_id" value="{{ old('country_id')??'' }}" placeholder="Country" readonly class="form-control p-0 border-0" autocomplete=off>

                                                    {{-- <select class="form-control" name="country_id" id="country-dropdown">
                                                        <option value=""> Select Country </option>
                                                        @foreach($data['country'] as $countryrow)
                                                        <option value="{{ $countryrow->id }}"> {{ $countryrow->name }}</option>
                                                    @endforeach
                                                    </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">State<span style="color:red;">*</span></label>
                                                <div class="col-md-12 ">
                                                    <input type="text" name="state_id" id="state_id" value="{{ old('country_id')??'' }}" readonly placeholder="State" class="form-control p-0 border-0" autocomplete=off>

                                                    {{-- <select class="form-control" name="state_id" id="state-dropdown">
                                                        <option value="">Select State</option>
                                                    </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">City<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom">
                                                    <input type="text" name="city_id" id="city"  value="{{ old('city_id')??'' }}" placeholder="city" class="form-control p-0 border-0" autocomplete=off>
                                                    <!-- <select class="form-control" name="city_id" id="city-dropdown">
                                                        <option value="">Select City</option>
                                                    </select> -->
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">


                                        <div class="col-md-4">
                                            <div class="form-group mb-12">
                                                <label class="col-md-12 p-0">Place<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="place" id="place" value="{{ old('place') }}" placeholder="Place" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-12">
                                                <label class="col-md-12 p-0">Zipcode<span style="color:red;">*</span></label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode') }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display: none">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Latitude</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Provide latitude for getting the exact result" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('latitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display: none">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Longitude</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Provide longitude for getting the exact result" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <!-- <span class="text-danger">@error('longitude'){{$message}} @enderror</span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="map" class="map-full map-layout single-listing-map" style="z-index: 50;">
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /map -->

                                    <button type="submit" id="listsubmit-location" class="btn btn-info listsubmit">submit</button>
                                </div>

                                <div class="tab-pane" id="amenities" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Amenity Category</label>
                                                <div class="col-md-12 ">
                                                    <select multiple class="form-control select2" name="amenitycat_id[]" id="amenitycat-dropdown" onChange="getSelectedAmenityOptions(this)" style="width:100%">
                                                        <option value="">Select Amenity Category </option>
                                                        @foreach($data['amenityCategoryData'] as $row)
                                                        <option @if($row['id'] == old('amenitycat_id')) selected @endif value="{{ $row['id'] }}"> {{ $row['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row amenitiesdata">
                                        <?php /* ?>
                                        @foreach($data['amenity'] as $amenityrow)
                                        <div class="col-md-3 p-10">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-check-input" name="amenities[]" id="{{ $amenityrow->id }}" value="{{ $amenityrow->id }}">
                                                <label class="custom-control-label" for="1"><i class="{{ $amenityrow->icon_class }}" style="color: #636363;"></i> {{ $amenityrow->title }} </label>
                                            </div>
                                        </div>
                                        @endforeach
                                        <?php */ ?>
                                    </div>
                                    <button type="submit" id="listsubmit-amenity" class="btn btn-info listsubmit">submit</button>
                                </div>
                                <div class="tab-pane" id="media" role="tabpanel">
                                   <div class="loader" id="loader"></div>
                                    <div class="AJloader" id="AJloader"></div>
                                    <div class="row mb-4">

                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox" style="opacity: 0;">
                                                <input type="radio" class="pay" name="payimage" value="payable" id="pay" checked>
                                                <label class="custom-control-label" for="1">Payable </label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="payimage" value="nonpayable" id="nonpay">
                                                <label class="custom-control-label" for="1">Non Payable </label>
                                            </div>
                                        </div> --}}

                                    </div>
                                    <div class="row">

                                        <div class="col-md-6" id="multi-imagelistingthumbnail">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing Thumbnail (800 X 800)px</label>
                                                <div class="col-md-12 imageuploadify">

                                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                    <input type="hidden" name="array_thumb_listing[]" class="form-control" id="listing-thumb-array" value="">
                                                    <input type="file" class="form-control" name="listing_thumb[]" id="multi-thumbnail-img">

                                                </div>
                                                <span class="text-danger">@error('listing_thumb.*'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Video Provider</label>
                                                <div class="col-md-12 ">
                                                    <select class="form-control" name="video_provider" id="video-provider">
                                                        <option value="youtube">YouTube</option>
                                                        <option value="vimeo">Vimeo</option>
                                                        <option value="html">HTML5</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 previewBlocks">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 thumbnailimgPreview" id="thumbnailimgPreview">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Video URL</label>
                                                <div class="col-md-12 ">
                                                    <input type="text" class="form-control" name="videourl">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-md-6" id="single-listingCover">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing Cover</label>
                                                <div class="col-md-12 ">

                                                    <input type="file" class="form-control" name="single_listing_cover" id="single-cover-img" onchange="coverloadFile(event)">

                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6" id="multi-listingCover">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing Cover (960 X 450)px</label>
                                                <div class="col-md-12 imageuploadify">

                                                    <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                    <input type="hidden" name="array_cover_listing[]" class="form-control" id="listing-cover-array" value="">

                                                    <input type="file" id="cover-img" class="form-control" name="listing_cover[]" multiple>
                                                </div>
                                                <span class="text-danger">@error('listing_cover.*'){{$message}} @enderror</span>
                                            </div>
                                        </div>


                                        {{-- <div class="col-md-6" id="single-gallery">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing gallery images (800 X 600)</label>
                                                <div class="col-md-12 ">

                                                    <input type="file" class="form-control" name="single_listing_gallery" id="single-gallery-img" onchange="galleryloadFile(event)">

                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6" id="multi-gallery">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Listing gallery images (1000 X 1000)px</label>
                                                <div class="col-md-12 imageuploadify">

                                                    <div class="imageuploadify-overlay3" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                    <input type="hidden" name="array_gallery_listing[]" class="form-control" id="listing-gallery-array" value="">
                                                    <input type="file" id="gallist" class="form-control" name="listing_gallery[]" multiple>
                                                </div>
                                                <span class="text-danger">@error('listing_gallery.*'){{$message}} @enderror</span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 previewBlocks">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 imgPreview" id="imgPreview">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-cover-img" alt="directory-img" width="70px" height="70px" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 previewBlocks">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Preview</label>
                                                <div class="col-md-12 listgallerypreview" id="listgallerypreview">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img" alt="directory-img" width="70px" height="70px" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <button type="submit" id="listsubmit-media" class="btn btn-info listsubmit">submit</button>
                                </div>

                                <div class="tab-pane" id="seo" role="tabpanel">

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
                                        @foreach($data['languagesData'] as $languagesrow)
                                        <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="<?php echo $languagesrow->name . "_" . $languagesrow->id; ?>" role="tabpanel">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Meta keywords</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="meta_keywords_{{$languagesrow->code}}" value="{{ old('meta_keywords_'.$languagesrow->code) }}" placeholder="Enter Meta Keywords Name in {{ $languagesrow->name }}" class="metakey form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Meta description</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <textarea rows="5" id="metadesc" name="meta_description_{{$languagesrow->code}}" onkeyup="limitWords(this.id)" class="metaDescp form-control p-0 border-0" placeholder="Enter Meta description Name in {{ $languagesrow->name }}">{{ old('meta_description_'.$languagesrow->code) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    {{-- <div class="form-group ">
                                        <label class="col-md-12 p-0">Directory Category</label>
                                        <div class="col-md-12 ">
                                            <select multiple class="form-control select2" name="category[]" id="tag-category" onChange="getSelectedOptions(this)" style="width:100%">
                                                <option>Select Parent Category</option>
                                                @foreach($data['parent_category'] as $categorysrow)
                                                <option value="{{ $categorysrow->id }}"> {{ $categorysrow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Company Category<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0 @error('category') errorClass @enderror">
                                            <select class="form-control select2" name="category" id="par-category" onChange="getSubCategory(this.value)" style="width:100%">
                                                <option></option>
                                                @foreach($data['parent_category'] as $categorysrow)
                                                <option @if($categorysrow->id == old('category')) selected @endif value="{{ $categorysrow->id }}" >
                                                    {{ $categorysrow->name }}
                                                </option>
                                                <?php /* ?><option value="{{ $categorysrow->id }}" <?php if(in_array($categorysrow->id,$data['categoryData'])){echo "selected";} ?>> {{ $categorysrow->name }}</option><?php */ ?>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Company Sub-Category<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0 @error('sub_category') errorClass @enderror">
                                            <select multiple class="form-control select2 " name="sub_category[]" id="subcategory" onChange="getSelectedOptions(this)" style="width:100%">
                                                @foreach($data['child_category'] as $child_categorysrow)
                                                <option @if($child_categorysrow->id == old('sub_category')) selected @endif value="{{ $child_categorysrow->id }}"> {{ $child_categorysrow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Tags(select category first!)<span style="color:red;">*</span></label>
                                        <div class="col-md-12 ">
                                            <select multiple class="form-control select2" name="simpletags[]" id="par-tags" style="width:100%">
                                                <option></option>
                                                @foreach($data['tags'] as $tagsrow)
                                                <option @if($tagsrow->id == old('simpletags')) selected @endif value="{{ $tagsrow->id }}">
                                                    {{ $tagsrow->tags }}
                                                </option>
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
                                                    <option value="{{ $owntagsrow->id }}"> {{ $owntagsrow->tags }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Directory Own Tags</label>
                                            <div class="col-md-12 ">
                                                <input type="text" name="createnewowntag" value="{{ old('createnewowntag') }}" placeholder="Enter tags with comma seperated eg:tag1,tag2..." class="form-control border-0" autocomplete=off>

                                            </div>
                                        </div>
                                    */?>
                                    <button type="submit" id="listsubmit-seo" class="btn btn-info listsubmit">submit</button>
                                </div>

                                <style>
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
                                        width: 485px;
                                        display: flex;
                                        padding: 8px 2px;
                                        float: right;
                                        justify-content: end;
                                        flex-direction: column;
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
                                        width: 485px;
                                        float: right;
                                        margin-bottom: 8px;
                                        align-items: center;
                                    }

                                    .newschedual .btn-danger[type="button"] {
                                        min-width: 120px;
                                        color: #ffffff;
                                    }

                                    checkbox-input-element input[type="checkbox"] {
                                        position: relative;
                                        left: -140px;
                                    }

                                    .business-hours-container time-input-element[name="min"] {
                                        margin-left: -100px;
                                    }

                                    .business-hours-container .newschedual time-input-element[name="min"] {
                                        margin-left: -50px;
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
                                            width: 335px;
                                        }

                                        .newschedual {
                                            width: 335px;
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

                                </style>


                                <div class="tab-pane" id="schedule" role="tabpanel">
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
                                                    <textarea rows="5" name="add_normal_comment" id="add-comment" class="form-control border-0"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Schedule Template<span style="color:red;">*</span></label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="normaldays" value="no" id="pay" checked="">
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>

                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="normaldays" value="yes" id="nonpay" style="margin-left: 20px;">
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group mb-4" id="normaldays-template">
                                        <label class="col-md-12 p-0">Normal Days Template</label>
                                        <div class="col-md-12 ">
                                            <select class="form-control" id="normaldaytemplateselect" name="normaldaytemplateselect" onchange="getNormalTemplateOption(this.value);" style="width:100%">
                                                <option value="none">Select Template</option>
                                                @foreach($data['normaldaysdata'] as $normaldaysdatarow)
                                                <option value="{{ $normaldaysdatarow->id }}"> {{ $normaldaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <table id="nomaldays-table" class="table text-nowrap">
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
                                        <label class="col-md-12 p-0">Schedule Template</label>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="publicdays" value="no" id="pay" checked="">
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="publicdays" value="yes" id="nonpay">
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4" id="publicdays-template">
                                        <label class="col-md-12 p-0">Public Holidays Template</label>
                                        <div class="col-md-12 ">

                                            <select class="form-control" id="publicdaystemplateselect" name="publicdaystemplateselect" onchange="getpublicTemplateOption(this.value);" style="width:100%">
                                                <option value="none">Select Template</option>
                                                @foreach($data['publicdaysdata'] as $publicdaysdatarow)
                                                <option value="{{ $publicdaysdatarow->id }}"> {{ $publicdaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <table id="publicdays-table" class="table text-nowrap table-info">
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
                                                    <input type="radio" class="pay" name="exception_days" value="no" id="pay" checked="">
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" class="pay" name="exception_days" value="yes" id="nonpay">
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
                                                <option value="{{ $exeptionaldaysdatarow->id }}"> {{ $exeptionaldaysdatarow->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <table id="exceptiondays-table" class="table text-nowrap table-info">
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
                                    <button type="submit" id="listsubmit-schedule" class="btn btn-info listsubmit">submit</button>
                                </div>
                                <div class="tab-pane" id="dirtype" role="tabpanel">
                                    <div class="row">
                                        @foreach($data['type'] as $typerow)
                                        <div class="col-md-3 p-10">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="typecat" name="hotel_type" value="{{ $typerow->id }}" {{ old('type')==$typerow->name ? 'checked' : ''}} data_val="{{ $typerow->name }}">
                                                <label class="custom-control-label" for="1">{{ $typerow->name }} </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="total_block" name="block_number" value="">
                                    <div class="blocks" id="type_blocks"></div>
                                    <button type="submit" id="listsubmit-dirtype" class="btn btn-info listsubmit">submit</button>
                                </div>
                                <div class="tab-pane" id="contact" role="tabpanel">
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
                                                    <input type="text" name="website" value="{{ old('website') }}" placeholder="Enter Website" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('website'){{$message}} @enderror</span>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Facebook</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="www.facebook.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Twitter</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="twitter" value="{{ old('twitter') }}" placeholder="www.twitter.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Linkedin</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="linkedin" value="{{ old('linkedin') }}" placeholder="www.linkedin.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Pinterest</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="pinterest" value="{{ old('pinterest') }}" placeholder="www.pinterest.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Whatsapp</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="www.whatsapp.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Instagram</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="www.instagram.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Tiktok</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="tiktok" value="{{ old('tiktok') }}" placeholder="www.tiktok.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Youtube</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="youtube" value="{{ old('youtube') }}" placeholder="www.youtube.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Snapchat</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="snapchat" value="{{ old('snapchat') }}" placeholder="www.snapchat.com/abc/" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="website_count" value="" id="wesitecount" />
                                    <div class="row mb-4">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <div class="panel-heading cust-tab-head">
                                                <h4 class="box-title">Website Link</h4>
                                            </div>
                                        </div>
                                    </div>
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

                                    <div class="row mb-4">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                            <div class="panel-heading cust-tab-head">
                                                <h4 class="box-title">Assortment Link</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="assort_count" value="" id="assortcount" />
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
                                    <button type="submit" id="listsubmit-contact" class="btn btn-info listsubmit">submit</button>
                                </div>
                                <div class="tab-pane" id="claim" role="tabpanel">

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
                                    <?php /* ?>
                                    <div id="claim_row_1" class="row claimlink">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Claim Subject</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="claim_subject" value="{{ old('claim_subject') }}" placeholder="Enter claim subject" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                                <span class="text-danger">@error('claim_subject'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Claim Description</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <!-- <input type="text" name="website_link_1" value="{{ old('website') }}" placeholder="Enter Website link" class="form-control p-0 border-0" autocomplete=off> -->
                                                    <textarea rows="3" name="claim_description" id="claimtext" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('claim_description') }}</textarea>
                                                </div>
                                                <span class="text-danger">@error('claim_description'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <button type="button" class="btn btn-success" class="add_claim_button" title="Add field" onclick=addmoreClaimSection()>+</button>
                                        </div> -->
                                    </div>
                                    <?php */ ?>

                                    <input type="hidden" id="active-tab" name="active_tab" value="">
                                    <!-- <button type="submit" id="listsubmit-dirtype" class="btn btn-info listsubmit">submit</button> -->
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
                                            <p> <button type="submit" class="btn btn-info">Add</button></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <ol class="breadcrumb ms-auto">
                                <li>
                                    @if($errors->any('name'))
                                    {!! implode('', $errors->all('<span class="alert-danger">:message</span>&nbsp')) !!}
                                    @endif
                                </li>
                            </ol>

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

    function directory_type_delete(id,key,cat_id,directory_id,block_num) {
        if(confirm("Are you sure you want to delete this?")){
            $('#gtype_key_'+key).remove();
        }else{
            return false;
        }
    };

    function directory_pdf_type_delete(id,key,cat_id,directory_id,block_num) {
        if(confirm("Are you sure you want to delete this?")){
            $('#pdf_type_key_'+key).remove();
        }else{
            return false;
        }
    };

    function type_galley_img(key) {

        var files = document.getElementById('type_galley_img_'+key).files;
            if (files.length > 0) {
                $('#gallery_type_pdf_'+key).prop('disabled', true);
                $('#type_galley_img_'+key).prop('disabled', false);
            }else{
                $('#gallery_type_pdf_'+key).prop('disabled', false);
                $('#type_galley_img_'+key).prop('disabled', false);
            }

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

    function gallery_type_pdf(key) {
        var files = document.getElementById('gallery_type_pdf_'+key).files;
        var filesCount = files.length;
        if (filesCount > 0) {
            $('#count_gallery_type_pdf_'+key).show();
            $('#gallery_type_pdf_'+key).prop('disabled', false);
            $('#type_galley_img_'+key).prop('disabled', true);
        }else{
            $('#count_gallery_type_pdf_'+key).hide();
            $('#gallery_type_pdf_'+key).prop('disabled', false);
            $('#type_galley_img_'+key).prop('disabled', false);
        }

            var maxFilesAllowed = 10;
            if (files.length > maxFilesAllowed) {
                $('.listsubmit').prop('disabled', true);
                toastr.error('You can upload a maximum of ' + maxFilesAllowed + ' files at a time.');
            }else{
                $('.listsubmit').prop('disabled', false);
                var fileslength = $("#gallery_type_pdf_"+key)[0].files.length;
                $('#count_gallery_type_pdf_'+key).html(fileslength);
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
                $('#exception-template').remove();
                $('#exceptiondays-table').after(result);

            }
        });
    }

    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select Option"
            , allowClear: true
        });

        $('#par-category').select2({
            placeholder: "Please Select Parent Category"
        });
        // $('#amenitycat-dropdown').on('change', function() {
        //     var amcat_id = this.value;
        //     $(".amenitiesdata").html("");
        //     $.ajax({
        //         url: baseUrl + '/admin/get-amenity-by-amenitycategory',

        //         type: "POST",
        //         data: {
        //             amcat_id: amcat_id,
        //             _token: '{{csrf_token()}}'
        //         },
        //         dataType: 'json',
        //         success: function(result) {
        //             $.each(result.amenity, function(key, value) {
        //                 $(".amenitiesdata").append(`<div class="col-md-3 p-10">
        //                                                 <div class="custom-control custom-checkbox">
        //                                                     <input type="checkbox" class="form-check-input" name="amenities[]" id="`+ value.id +`" value="`+ value.id +`">
        //                                                     <label class="custom-control-label" for="1"><i class="`+ value.icon_class +`" style="color: #636363;"></i> `+ value.title +`</label>
        //                                                 </div>
        //                                             </div>
        //                                     `);
        //             });

        //             // $('#state-dropdown').html('<option value="">Select State</option>');
        //             // $.each(result.states, function(key, value) {
        //             //     $("#state-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
        //             // });
        //             // $('#city-dropdown').html('<option value="">Select State First</option>');
        //         }
        //     });
        // });
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
                    //console.log(result);
                    //$.each(opts, function(optkey, optvalue) {

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
                    //});
                }
            });
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
<script type="text/javascript">
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

</script>
{{-- <script type="text/javascript">
    function getSelectedOptions(sel) {
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
        $("#par-tags").html('');
        $("#par-owntags").html('');
        // return opts;
        if (opts.length != 0) {
            $.ajax({
                url: baseUrl + '/admin/get-tagsandOwntags-by-categoriesid',

                type: "POST"
                , data: {
                    category_id: opts
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log(result);
                    //$('#par-tags').html('<option value="">Select Category First</option>');
                    $.each(result.tags, function(key, value) {
                        $("#par-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                    });
                    $.each(result.owntags, function(key, value) {
                        $("#par-owntags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                    });
                    //$('#city-dropdown').html('<option value="">Select State First</option>');
                }
            });
        }

    }

</script> --}}
<script>
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

         // $("#par-tags").html('');
        // $("#par-owntags").html('');
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

</script>
<script>
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
    function getVendorName(vendor_id) {
        //alert("Category id: "+vendor_id);
        $.ajax({
            url: baseUrl + '/admin/get-vendor-by-vendorid',
            type: "POST",
            data: { vendor_id: vendor_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {
                console.log(result[0].email);
                $("#company-email").val(result[0].email);
                $("#company-person-name").val(result[0].name + " " + result[0].last_name);
                $("#company-phone").val(result[0].phone);
                // $.each(result.vendordata, function(key, value) {
                //     $("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
                // });

            }
        });
    }
</script>
<script type="text/javascript">
    var normaldaysselectvalue = $('input[name=normaldays]:checked', '#directoryForm').val();
    $('#normaldays-template').hide();
    $("input[name=normaldays]").change(function() {

        if ($(this).val() != "yes") {
            $('#normaldays-template').hide();
            $('#nomaldays-table').show();
            $('#nomaldays-template-table').hide();
            $('#normaldaytemplateselect').val("none");
        } else {
            $('#normaldays-template').show();
            $('#nomaldays-table').hide();
        }

    });

    $('#publicdays-template').hide();
    $("input[name=publicdays]").change(function() {

        if ($(this).val() != "yes") {
            $('#publicdays-template').hide();
            $('#publicdays-table').show();
            $('#public-template').hide();
            $('#publicdaystemplateselect').val("none");

        } else {
            $('#publicdays-template').show();
            $('#publicdays-table').hide();
        }

    });
    $('#exception_days-template').hide();
    $("input[name=exception_days]").change(function() {

        if ($(this).val() != "yes") {
            $('#exception_days-template').hide();
            $('#exceptiondays-table').show();
            $('#exception-template').hide();
            $('#exception-daystemplateselect').val("none");
        } else {
            $('#exception_days-template').show();
            $('#exceptiondays-table').hide();
        }

    });

</script>
<script type="text/javascript">
    $(function() {
        $('.icon-picker').iconpicker();
    });

</script>
<script type="text/javascript">
    $(document).ready(function(e) {
        $('#thumbnail-img').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-thumbnail-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });

</script>
<script>
    $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder, type) {
            if (input.files) {
                //console.log( input.files.length);

                $(".imageuploadify-overlay" + type).css("display", "block");

                var filesAmount = input.files.length;
                var k = 0;
                $(imgPreviewPlaceholder).html('');


                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        console.log(k);
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
                $(".imageuploadify-overlay" + type).css("display", "none");
            }
        };
        $('#multi-thumbnail-img').on('change', function() {
            var selectedOption = $('input[name="payimage"]:checked').val();
            //if (selectedOption == 'payable') {
            //document.getElementById("preview-thumbnail-img").style.display = "none";
            multiImgPreview(this, 'div.thumbnailimgPreview', '');
            //}
        });
        $('#cover-img').on('change', function() {
            //document.getElementById("preview-cover-img").style.display = "none";
            multiImgPreview(this, 'div.imgPreview', 2);

        });
        $('#gallist').on('change', function() {
            // document.getElementById("preview-gal-img").style.display = "none";
            multiImgPreview(this, 'div.listgallerypreview', 3);

        });

    });

    $(document).ready(function() {

        $(".listsubmit").on("click", function(e) {
            var active_button = $('ul.dir-tabs li a.active');
            $('#active-tab').val(active_button.text());
            $('.active-tab').val(active_button.text());
        });
        $("#imgPreview,#thumbnailimgPreview,#listgallerypreview").sortable();

    });

    function submitForm() {
        var image_thumb_arr = [];
        var image_cover_arr = [];
        var image_gal_arr = [];
        var image_trade_arr = [];
        // get image ids order listsubmit
        $('#thumbnailimgPreview div').each(function() {
            var id = $(this).data('id');
            image_thumb_arr.push(id);
            //console.log(imageids_arr);
            $("#listing-thumb-array").val(image_thumb_arr);
        });
        $('#imgPreview div').each(function() {
            var id = $(this).data('id');
            image_cover_arr.push(id);
            //console.log(imageids_arr);
            $("#listing-cover-array").val(image_cover_arr);
        });
        $('#listgallerypreview div').each(function() {
            var id = $(this).data('id');
            image_gal_arr.push(id);
            //console.log(imageids_arr);
            $("#listing-gallery-array").val(image_gal_arr);
        });

        return;
    }


    $(document).ready(function() {
        var payble = $('input[name=payimage]:checked', '#directoryForm').val();
        $("input[name=payimage]").change(function() {

            $("#multi-thumbnail-img").val(null);
            $("#cover-img").val(null);
            $("#gallist").val(null);
            $('.ui-state-default').remove();
            if ($(this).val() != "payable") {
                //$('.previewBlocks').hide();
                $('#multi-thumbnail-img').prop("multiple", false);
                $('#cover-img').prop("multiple", false);
                $('#gallist').prop("multiple", false);

            } else {
                $('.previewBlocks').show();
                $('#preview-thumbnail-img').show();
                $('#preview-cover-img').show();
                $('#preview-gal-img').show();
                $('#multi-thumbnail-img').prop("multiple", true);
                $('#cover-img').prop("multiple", true);
                $('#gallist').prop("multiple", true);
            }

        });
    });

</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places"></script>
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

                let latLng = new google.maps.LatLng(-23.6815314, -46.875502);
                console.log(this.inputLat.val());
                if (this.inputLat.val() && this.inputLng.val()) {
                    latLng = new google.maps.LatLng(this.inputLat.val(), this.inputLng.val());
                }

                this.map = new google.maps.Map(this.mapContainer, {
                    zoom: 15
                    , center: latLng
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

</script>

<script>
    $(document).ready(function() {

        $('.typecat').on('click', function() {
            $('#type_blocks').html('');
            var typedata = this.value;
            var typeNamedata = $(this).attr('data_val');
            var blockcount = $('#blockone').length;
            getTypeBlock(typedata, blockcount);
        });

    });

    function addSection() {
        var typedata = $('input[name=hotel_type]:checked', '#directoryForm').val();
        var blockcount = $('.blocksCount').length;
        $('#total_block').val(blockcount);
        getTypeBlock(typedata, blockcount);
    }

    function removeSection(count) {
        $('#block_' + count).remove();
    }

    function getTypeBlock(typedata, blockcount) {
        $.ajax({
            url: baseUrl + '/admin/get-type-block'
            , type: "POST"
            , data: {
                type_value: typedata
                , blockcount: blockcount
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
        //alert(insertBlock);
        $('<tr class="' + blockClass + ' ' + blockClass + '_template_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_template_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_template_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTemplateDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_template_row_' + insertBlock);
    }

    function removeTemplateDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_template_row_' + numItems).remove();
    }

</script>
<script>
    let lineno = 2;

    function addwebSection() {

        $("#weblink_1").after(`
        <div class="row" id="weblink_` + lineno + `">
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
        lineno++;

    }

    function removewebSection(count) {
        $('#weblink_' + count).remove();
    }

</script>
<script>
    let linenoass = 2;

    function addAssortmentSection() {

        $("#assortlink_1").after(`
        <div class="row" id="assortlink_` + linenoass + `">
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
        linenoass++;
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
        });
    });

</script>
<script type="text/javascript">
    var form = $('#directoryForm')
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

@endsection
