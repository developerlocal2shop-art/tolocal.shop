@extends('layouts.client.blog')
@section('title','Complete Company information')
@section('content')

@php

$user = auth()->user();

    $getFeatureLinksValue = Helper::getFeatureUsesValue('links');
    $getFeatureAmenitiesValue = Helper::getFeatureUsesValue('amenities');
@endphp

<div class="sub_header_in sticky_header filter_sub_blue_header  add-companytinfo-helpicon">
    <div class="container">
        <h1 style="display: flex;">Complete Company Information
        @if(!empty($data['instruction']->message))
        <div id="helpicon">
            <span id="helpiconText">{{$data['instruction']->message}}</span>
            <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
        </div>
        @endif
        </h1>
    </div>
</div>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container margin_60_35 add-compnayinfo-container">
        <div class="container-fluid add-companyinfo-mobileview">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('directory.submit') }}" onsubmit="return submitForm()" id="directoryForm" method="POST" class="" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            </a>
                                <ul class="nav nav-tabs dir-tabs cust-nav-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#basic" role="tab">
                                            <span>Basic</span>
                                        </a>
                                    </li>
                                    <!-- @if (Helper::getFeatureUses('location') == true) -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#location" role="tab">
                                            <span>Location</span>
                                        </a>
                                    </li>
                                    <!-- @endif
                                    @if (Helper::getFeatureUses('type') == true) -->
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#dirtype" role="tab">
                                            <span>Type</span>
                                        </a>
                                    </li> --}}
                                    <!-- @endif -->
                                    @if (Helper::getFeatureUses('amenities') == true)
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#amenities" role="tab">
                                            <span>Amenities</span>
                                        </a>
                                    </li>
                                    @endif
                                    <!-- @if (Helper::getFeatureUses('media') == true) -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#media" role="tab">
                                            <span>Media</span>
                                        </a>
                                    </li>
                                    <!-- @endif -->
                                    @if (Helper::getFeatureUses('seo') == true)
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#seo" role="tab">
                                            <span>SEO</span>
                                        </a>
                                    </li>
                                    @endif
                                    <!-- @if (Helper::getFeatureUses('schedule-all') == true) -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#schedule" role="tab">
                                            <span>Schedule</span>
                                        </a>
                                    </li>
                                    <!-- @endif -->
                                    @if (Helper::getFeatureUses('links') == true)
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#contact" role="tab">
                                            <span>Link</span>
                                        </a>
                                    </li>
                                    @endif
                                    <!-- @if (Helper::getFeatureUses('claim') == true) -->
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#claim" role="tab">
                                            <span>Claim</span>
                                        </a>
                                    </li>
                                    <!-- @endif -->
                                    <li class="nav-item">
                                        <a class="nav-link " data-toggle="tab" href="#services" role="tab">
                                            <span>Services</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content cust-tab-cont">

                                    <div class="tab-pane active" id="basic" role="tabpanel">

                                    <?php /* ?><ul class="nav nav-tabs " role="tablist">
                                            @foreach($data['languagesData'] as $languagesrow)
                                            <li class="nav-item">
                                                {{--<a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                                    <span>{{ $languagesrow->name }}</span>
                                                </a>--}}
                                            </li>
                                            @endforeach
                                        </ul><?php */ ?>
                                        <div class="tab-content cust-tab-cont">
                                            @foreach($data['languagesData'] as $languagesrow)
                                            <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Name<span class="notranslate" style="color:red;">*</span>
                                                    <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                                    </label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <input id="cat-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('name_'.$languagesrow->code) }}" type="text" name="name_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('name_'.$languagesrow->code) }}" placeholder="Enter Directory Name in {{ $languagesrow->name }}" class="directorynametitle seoinputname form-control p-0 border-0" autocomplete=off> -->
                                                        <input id="cat-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('name_'.$languagesrow->code) }}" type="text" name="name_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('name_'.$languagesrow->code) }}" placeholder="Enter Directory Name" class="directorynametitle seoinputname form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span id="name-{{$languagesrow->code}}" class="text-danger name_error"></span>
                                                    <span class="text-danger">@error('name_en'){{$message}} @enderror</span>

                                                </div>

                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0 notranslate">SEO</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('seo_en') }}" placeholder=" Directory SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off> -->
                                                        <input type="text"  readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('seo_en') }}" placeholder="Directory SEO" class="form-control p-0 border-0 notranslate" autocomplete=off>
                                                    </div>
                                                    <span class="seo_error" class="text-danger"></span>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <textarea rows="5" id="description-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="description_{{$languagesrow->code}}" class="directorydesc form-control p-0 border-0" placeholder="Enter Description in {{ $languagesrow->name }}">{{ old('description_'.$languagesrow->code) }}</textarea> -->
                                                        <textarea rows="5" id="description-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="description_{{$languagesrow->code}}" class="directorydesc form-control p-0 border-0" placeholder="Enter Description">{{ old('description_'.$languagesrow->code) }}</textarea>
                                                    </div>
                                                    <span class="text-danger">@error('description_en'){{$message}} @enderror</span>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">We Offer</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <textarea rows="5" id="weoffer-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="weoffer_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter We Offer in {{ $languagesrow->name }}">{{ old('weoffer_'.$languagesrow->code) }}</textarea> -->
                                                        <textarea rows="5" id="weoffer-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="weoffer_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter We Offer">{{ old('weoffer_'.$languagesrow->code) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">About Us</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <!-- <textarea rows="5" id="aboutus-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="aboutus_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter About Us in {{ $languagesrow->name }}">{{ old('aboutus_'.$languagesrow->code) }}</textarea> -->
                                                        <textarea rows="5" id="aboutus-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="aboutus_{{$languagesrow->code}}" class="form-control p-0 border-0" placeholder="Enter About Us">{{ old('aboutus_'.$languagesrow->code) }}</textarea>
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
                                                            <option value="featured" @if('featured' == old('featuretype')) selected @endif>Featured</option>
                                                            <option value="superfeatured" @if('featured' == old('superfeatured')) selected @endif>Super Featured</option>
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
                                            <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Registered Company Email <span class="notranslate" style="color:red;">*</span><span style="font-weight: 600;">(Can't be change)</span></label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="email" name="email" readonly value="{{auth()->user()->email}}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Name<span class="notranslate" style="color:red;">*</span></label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="name" value="{{ old('name',auth()->user()->name.' '.auth()->user()->last_name) }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Phone Number<span class="notranslate" style="color:red;">*</span></label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="phone_number"  value="{{ old('phone_number',auth()->user()->phone) }}" placeholder="Enter Phone Number" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="height:2px;color:#8898aa">
                                        <div class="form-group ">
                                            <label class="col-md-12 p-0">Trademark Category<span class="notranslate" style="color:red;">*</span></label>
                                            <div class="">
                                                <select class="form-control" name="trademarkcategory" id="trademark-category" onChange="getTrademarkOptions(this)" style="width:100%">
                                                    <option value="">Select Trademark Category</option>
                                                    @foreach($data['trademarkcategory'] as $categorysrow)
                                                    <option value="{{ $categorysrow['id'] }}" @if($categorysrow['id'] == old('trademarkcategory')) selected @endif>
                                                        {{ $categorysrow['name'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger">@error('trademarkcategory'){{$message}} @enderror</span>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Trademark (You may enter own and missing Trademarks from Business Package)</label>
                                            <div class="">

                                                <select multiple class="form-control select2" name="trademark[]" id="par-trademark">
                                                    <option></option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox popular-infoicon">
                                                <input type="checkbox" class="form-check-input" name="popular" id="" value="1">
                                                <label class="custom-control-label" for="1" style="display: inline-flex;"> Popular
                                                    <div id="infotooltip">
                                                        <span id="infotooltipText">Please check this, If you want this directory to show in the list of popular.</span>
                                                        <span><i class="fas fa-info-circle" style="color:#3c3c3e !important; padding-left: 5px;"></i></span>
                                                    </div>
                                                </label>
                                            </div>
                                            <span class="text-danger">@error('popular'){{$message}} @enderror</span>
                                        </div>
                                        @if ($user->hasPermission('add') == true)
                                        <button type="submit" id="listsubmit-basic" class="btn btn-info listsubmit">Save</button>
                                        @endif
                                    </div>

                                    <div class="tab-pane" id="location" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Address</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input id="pac-input" class="form-control p-0 border-0" value="{{ old('address') }}" type="text" placeholder="address" name="address" />
                                                        <span class="text-danger">@error('address'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Country </label>
                                                    <div class="border-bottom">
                                                        <input type="text" name="country_id" readonly id="country_id" value="{{ old('country_id') }}" placeholder="Country" class="form-control p-0 border-0" autocomplete=off>
                                                        <span class="text-danger">@error('country_id'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">State</label>
                                                    <div class="border-bottom">

                                                        <input type="text" name="state_id" readonly id="state_id" value="{{ old('state_id') }}" placeholder="State" class="form-control p-0 border-0" autocomplete=off>

                                                        <span class="text-danger">@error('state_id'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">City</label>
                                                    <div class="border-bottom">
                                                        <input type="text" name="city_id" readonly id="city" value="{{ old('city_id') }}" placeholder="city" class="form-control p-0 border-0" autocomplete=off>

                                                        <span class="text-danger">@error('city_id'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-12">
                                                    <label class="col-md-12 p-0">Place</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="place" id="place" value="{{ old('place') }}" placeholder="Place" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('place'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-12">
                                                    <label class="col-md-12 p-0">Zipcode</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode') }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('zipcode'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="display: none">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Latitude</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Provide latitude for getting the exact result" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('latitude'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="display: none">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0">Longitude</label>
                                                    <div class="col-md-12 border-bottom p-0">
                                                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Provide longitude for getting the exact result" class="form-control p-0 border-0" autocomplete=off>
                                                    </div>
                                                    <span class="text-danger">@error('longitude'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="map" class="map-full map-layout single-listing-map" style="z-index: 50;">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /map -->
                                        @if ($user->hasPermission('add') == true)
                                        <button type="submit" id="listsubmit-location" class="btn btn-info listsubmit">Save</button>
                                        @endif
                                    </div>

                                    <div class="tab-pane" id="amenities" role="tabpanel">
                                        <div class="row">
                                        <input type="hidden" name="links_count" value="<?php echo ($getFeatureAmenitiesValue); ?>" id="amenitycatcount" />
                                            <div class="col-md-12">
                                                <div class="form-group mb-4">
                                                    <label class="p-0">Amenity Category (You can select multiple categories!)</label>
                                                    <div class="">
                                                        <select multiple class="form-control select2" name="amenitycat_id[]" id="amenitycat-dropdown" onChange="getSelectedAmenityOptions(this)" style="width:100%">
                                                            <option value="">Select Amenity Category </option>
                                                            @foreach($data['amenityCategoryData'] as $row)
                                                            <option value="{{ $row['id'] }}"> {{ $row['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row amenitiesdata">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-4">
                                                    <label class="p-0">Add Your Own Amenity</label>

                                                    <input type="text" name="directory_own_amenity" data-role="tagsinput" id="tags_name" value="{{ old('directory_amenity') }}" placeholder="Add Your Own Amenity" class="form-control p-0 border-0">

                                                </div>
                                            </div>
                                        </div>
                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-amenity" class="btn btn-info listsubmit">Save</button>
                                        @endif
                                    </div>
                                    <div class="tab-pane" id="media" role="tabpanel">
                                       <div class="loader" id="loader"></div>
                                        <div class="AJloader" id="AJloader"></div>

                                        <div class="row mb-4">

                                            <div class="col-md-3">
                                                <div class="custom-control custom-radio" style="opacity: 0;">
                                                    <input type="radio" class="pay" name="payimage" value="payable" id="pay" checked="">
                                                    <label class="custom-control-label" for="1">Payable </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6 mobile-alignment-infoicon thumbnail-img-icon" id="multi-imagelistingthumbnail">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0" style="display: flex;">Listing Thumbnail (<span style="color:red">800 X 800</span>)px
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
                                                        <input type="file" class="form-control" name="listing_thumb[]" id="multi-thumbnail-img" >

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
                                            @php
                                                $getFeatureUsesValue = Helper::getFeatureUsesValue('listing-thumbnail-and-listing-cover');
                                                $getFeatureGalleryValue = Helper::getFeatureUsesValue('media');
                                            @endphp
                                        <div class="row">

                                            <div class="col-md-6 infoicon-listingcover-align cover-img-add-icon" id="multi-listingCover">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0" style="display: flex;">Listing Cover Images(<span style="color:red">960 X 450</span>)px
                                                        <div id="tooltip">
                                                            <span id="tooltipText">Best result with (960 X 450)px - other dimensions are acceptable but visualization can be poor.</span>
                                                            <span><i class="fas fa-info-circle" ></i></span>
                                                        </div>
                                                    </label>
                                                    <p>Note : <i class="fas fa-eye" aria-hidden="true"></i>  Image upload at a time - 1 and Max limit - {{ $getFeatureUsesValue??'' }} Pending on my package.</p>

                                                    <div class="col-md-12 imageuploadify">
                                                        <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                        <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                                        <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                                        <input type="hidden" name="array_cover_listing[]" class="form-control" id="listing-cover-array" value="">

                                                        <input type="file" id="cover-img" class="form-control" name="listing_cover[]">
                                                    </div>
                                                    <span class="text-danger">@error('listing_cover.*'){{$message}} @enderror</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 infoicon-multi-gallery-align-mobileview" id="multi-gallery">
                                                <div class="form-group mb-4">
                                                    <label class="col-md-12 p-0" style="display: flex;">Listing gallery images (<span style="color:red">1000 X 1000 </span>)px
                                                        <div id="tooltip">
                                                            <span id="tooltipText">Best result with (1000 X 1000)px - other dimensions are acceptable but visualization can be poor.</span>
                                                            <span><i class="fas fa-info-circle" ></i></span>
                                                        </div>
                                                    </label>
                                                    <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Image upload at a time - 20 and Max limit - {{ $getFeatureGalleryValue??'' }} Pending on my package.</p>
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

                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-media" class="btn btn-info listsubmit">Save</button>
                                        @endif
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
                                                    <span class="text-danger">@error('meta_keywords_'.$languagesrow->code){{$message}} @enderror</span>
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

                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Company Category</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <select class="form-control select2" name="category" id="par-category" onChange="getSubCategory(this.value)" style="width:100%">
                                                    @foreach($data['parent_category'] as $categorysrow)
                                                    <option value="{{ $categorysrow->id }}">{{ $categorysrow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Company Sub-Category</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <select multiple class="form-control select2" name="sub_category[]" id="subcategory" onChange="getSelectedOptions(this)" style="width:100%">
                                                    @foreach($data['child_category'] as $child_categorysrow)
                                                    <option value="{{ $child_categorysrow->id }}" > {{ $child_categorysrow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Tags(select category first!)</label>
                                            <div class="col-md-12 p-0">
                                                <select multiple class="form-control select2" name="simpletags[]" id="par-tags" style="width:100%">
                                                    <option></option>
                                                    @foreach($data['tags'] as $tagsrow)
                                                    <option value="{{ $tagsrow->id }}"> {{ $tagsrow->tags }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-seo" class="btn btn-info listsubmit">Save</button>
                                        @endif
                                    </div>

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


                                        <div class="row mb-4">
                                            <label class="col-md-12">Schedule Template</label>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="pay" name="normaldays" value="no" id="pay" checked="">
                                                    <label class="custom-control-label" for="1">No </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="pay" name="normaldays" value="yes" id="nonpay">
                                                    <label class="custom-control-label" for="1">Yes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4" id="normaldays-template">
                                            <label class="col-md-12 p-0">Normal Days Template</label>
                                            <div class="col-md-12 p-0">

                                                <select class="form-control" id="normaldaytemplateselect" name="normaldaytemplateselect" onchange="getNormalTemplateOption(this.value);" style="width:100%">
                                                    <option>Select Template</option>
                                                    @foreach($data['normaldaysdata'] as $normaldaysdatarow)
                                                    <option value="{{ $normaldaysdatarow->id }}"> {{ $normaldaysdatarow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="normaldays-schedule-table">
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
                                        </div>
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
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="pay" name="publicdays" value="no" id="pay" checked="">
                                                        <label class="custom-control-label" for="1">No </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="pay" name="publicdays" value="yes" id="nonpay">
                                                        <label class="custom-control-label" for="1">Yes</label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group mb-4" id="publicdays-template">
                                            <label class="col-md-12 p-0">Public Holidays Template</label>
                                            <div class="col-md-12 p-0">

                                                <select class="form-control" id="publicdaystemplateselect" name="publicdaystemplateselect" onchange="getpublicTemplateOption(this.value);" style="width:100%">
                                                    <option>Select Template</option>
                                                    @foreach($data['publicdaysdata'] as $publicdaysdatarow)
                                                    <option value="{{ $publicdaysdatarow->id }}"> {{ $publicdaysdatarow->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="add_publicdays-table">
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
                                        </div>
                                        <?php /* ---------------------- END Public holidays --------------------------------  */ ?>
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
                                                            <input type="radio" class="pay" name="exception_days" value="no" id="pay" checked="">
                                                            <label class="custom-control-label" for="1">No </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="pay" name="exception_days" value="yes" id="nonpay">
                                                            <label class="custom-control-label" for="1">Yes</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4" id="exception_days-template">
                                                <label class="col-md-12 p-0">Exceptional Holidays Template</label>
                                                <div class="col-md-12 p-0">

                                                    <select class="form-control" name="exception_daystemplateselect" id="exception-daystemplateselect" onchange ="getExceptionTemplateOption(this.value)" style="width:100%">
                                                        <option>Select Template</option>
                                                        @foreach($data['exeptionaldaysdata'] as $exeptionaldaysdatarow)
                                                        <option value="{{ $exeptionaldaysdatarow->id }}"> {{ $exeptionaldaysdatarow->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="add_exceptiondays_table">
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
                                            </div>
                                            <?php /* ---------------------- END Exceptional holidays --------------------------------  */ ?>
                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-schedule" class="btn btn-info listsubmit">Save</button>
                                        @endif
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
                                            <span class="text-danger">@error('hotel_type'){{$message}} @enderror</span>
                                        </div>
                                        <input type="hidden" id="total_block" name="block_number" value="">
                                        <div class="blocks" id="type_blocks"></div>
                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-dirtype" class="btn btn-info listsubmit">Save</button>
                                        @endif
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
                                        <input type="hidden" name="links_count" value="<?php echo ($getFeatureLinksValue); ?>" id="wesitelinkcount" />
                                        <div class="row mb-4">
                                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                <div class="panel-heading cust-tab-head">
                                                    <h4 class="box-title">Website Link (Max limit - <?php echo ($getFeatureLinksValue); ?>)</h4>
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
                                        @if ($user->hasPermission('add') == true)
                                            <button type="submit" id="listsubmit-contact" class="btn btn-info listsubmit">Save</button>
                                        @endif
                                    </div>
                                    <div class="tab-pane" id="claim" role="tabpanel">

                                        <input type="hidden" name="claim_count" value="" id="claimcount" />
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
                                                            <textarea rows="3" name="claim_description" id="claimtext" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('claim_description') }}</textarea>
                                                        </div>
                                                        <span class="text-danger">@error('claim_description'){{$message}} @enderror</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    @if ($user->hasPermission('add') == true)
                                                        <button type="submit" id="listsubmit-dirtype" class="btn btn-success listsubmit">Add Claim</button>
                                                    @endif
                                                </div>
                                            </div>
                                            </div>
                                    <div class="tab-pane " id="services" role="tabpanel">

                                            <div id="claim_row_1" class="row claimlink">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-4">
                                                        <label class="col-md-12 p-0">Services List - will appear on contact us form on Company page,
allowing customer to specify their question.</label>

                                                        <div class="col-md-12 border-bottom p-0">
                                                            <input type="text" name="directory_services" data-role="tagsinput" id="tags_name" value="" placeholder="Add Services" class="form-control p-0 border-0">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <hr/>

                                            @if ($user->hasPermission('add') == true)
                                                <button type="submit" id="listsubmit-services" class="btn btn-info listsubmit">Save</button>
                                            @endif

                                        </div>
                                        <input type="hidden" id="active-tab" name="active_tab" value="">
                                    <div style="display:none;" class="tab-pane" id="finish" role="tabpanel">
                                        <div class="col-sm-12">
                                            <div class="text-center">
                                                <h2 class="mt-0">
                                                    <i class="entypo-check"></i>
                                                </h2>
                                                <h3 class="mt-0">Thank you !</h3>
                                                <p class="w-75 mb-2 mx-auto">You are almost there. Please check if you have provided all the necessary things.</p>
                                                <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">
                                                <p> <button type="submit" class="btn btn-info">Add</button></p>
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
    <!-- /row -->
    </div>
    <!-- /container -->
</main>



<script type="text/javascript">

    $(document).ready(function() {
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
                                                                    <label class="custom-control-label" for="1"><i class="` + val.icon_class + `" style="color: #636363;"></i> ` + val.title +`</label>
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
    function getpublicTemplateOption(publicseleid){
        $.ajax({
            url: baseUrl + '/company/public/get-publictemplate-by-id',

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
            url: baseUrl + '/company/exception/get-exceptiontemplate-by-id',

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
        $("#loader").fadeOut("slow");
        //jQuery.noConflict();
        $(".select2").select2({
            placeholder: "Select Option"
            , allowClear: true
        });
    });

    $(document).ready(function() {
        var normaldaysselectvalue = $('input[name=normaldays]:checked', '#directoryForm').val();
        var publicselectvalue = $('input[name=publicdays]:checked', '#directoryForm').val();
        var exception_daysselectvalue = $('input[name=exception_days]:checked', '#directoryForm').val();

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


    //$('#normaldays-template').hide();
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

    //$('#publicdays-template').hide();
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
                    // $.each(result.tags, function(key, value) {
                    //     $("#par-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
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

    $(function() {
        $('.icon-picker').iconpicker();
    });

    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select "
            , allowClear: true

        });
    });

    $(document).ready(function() {
        var payble = $('input[name=payimage]:checked', '#directoryForm').val();
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
        $(document).on('click', ".closebtn", function() {

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

            alert(imagevalue);
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
            var payble = $('input[name=payimage]:checked', '#directoryForm').val();
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

                if(getFeatureUsesValue < fileslength){
                    toastr.error('Need to upgrade for more images');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                    return false;
                }
            // var payble = $('input[name=payimage]:checked', '#editsection').val();
            // if (payble != 'payable') {
            //     if ($("#cover-img")[0].files.length > 1) {
            //         $("#cover-img").val(null);
            //         // alert("You can select only 1 images");
            //         return;
            //     }
            // }
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

            //console.log(imagepre+imagepre1);
            multiImgPreview(this, 'div.imgPreview', parseInt(position) + 1, '#lastpositionthumbValue', 2);
        });

        $('#gallist').on('change', function() {
            var fileslength = $("#gallist")[0].files.length;
            if(getFeatureGalleryValue < fileslength){
                toastr.error('Need to upgrade for more images');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                return false;
            }
            // var payble = $('input[name=payimage]:checked', '#editsection').val();
            // if (payble != 'payable') {
            //     if ($("#gallist")[0].files.length > 1) {
            //         $("#gallist").val(null);
            //         alert("You can select only 1 images");
            //         return;
            //     }
            // }
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


    $(document).ready(function() {

        var country_id_default = $('select#country-dropdown option:selected').val();
        var stateidselected = $('#stateid').val();
        var cityidselected = $('#cityid').val();
        console.log("state id: ", stateidselected);
        console.log("city id: ", cityidselected);

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


    function addSection() {
        var typedata = $('input[name=hotel_type]:checked', '#directoryForm').val();
        var blockcount = $('.blocksCount').length;
        // console.log("Block count on edit add section click: ",typedata);
        $('#total_block').val(blockcount);
        getTypeBlock(typedata, blockcount, "add");
    }

    var blockdirectoryId = $('#directoryId').val();
    var blockNumber = $('#total_block_from_database').val();

    function getTypeBlock(typedata, blockcount, addnewonedit) {
        var editvalue = (addnewonedit != "edit") ? "add" : "edit";
        console.log("Block: ", editvalue);
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
        $('<tr class="row_public public_time_slot_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlockPublic(`public_time_slot_row_' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.public_time_slot_row_' + insertBlock);
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
</script>
<script>
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

</script>

<script type="text/javascript">

    $(document).ready(function() {
        $(".listsubmit").on("click", function(e) {
            var active_button = $('ul.dir-tabs li a.active');
            //alert(active_button.text());
            $('#active-tab').val(active_button.text());
        });
    });

    var form = $('#directoryForm'), original = form.serialize()
    form.submit(function() {
        window.onbeforeunload = null
    })
    window.onbeforeunload = function() {
        if (form.serialize() != original)
            return 'Make sure you have save your data before leaving the page!'
    }

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

        $(".seoinputname").focusout(function() {
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
    }
    .thumbnail img {
        width: 70px;
        height: 70px;
    }
    .closebtn {
        display: block;
        position: absolute;
        width: 35px;
        height: 35px;
        top: -12px;
        right: -21px;
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
        width: 70px;
        padding: 3px 8px 3px 8px !important;
        font-size: 16px;
        color: white;
        border-radius: 5px;
        margin-right: 0px;
    }
    .delete_all{
        cursor: pointer;
        background: red;
        width: 90px;
        padding: 3px 8px 3px 8px !important;
        font-size: 16px;
        color: white;
        border-radius: 5px;
        float: right;
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
        transform: translate(-12%, 7%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:200px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #infotooltip:hover:after {
        top: 17px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 20px;
        margin-left: -5px;
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
        top:3px;
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
        border-color: transparent transparent #fff transparent;
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
        top: 88%;
        z-index: 99;
    }
</style>

@endsection
