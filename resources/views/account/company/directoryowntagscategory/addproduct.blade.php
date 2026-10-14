@extends('layouts.client.company')
@section('title','Add new product')
@section('content')
<style>
    .mandatory_field{
        font-weight: bold;
    }
    .pointimp{
        font-size: large;
    }
    
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Add New Product</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->

<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">

            <div class="col-md-12">
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif


            </div>
        </div>


        <!--Main Area-->
        <form action="{{ route('company.product.submit') }}" method="POST" id="addpro" class="" enctype="multipart/form-data">

            {{ csrf_field() }}

            <input type="hidden" id="lati" name="lati" >
            <input type="hidden" id="long" name="long" >
            <div class="row">
                <div class="col-md-12">
                    <h3 class="box-title">Add product</h3>
                    <?php /* ?>
                    <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                        @foreach($data['languagesData'] as $languagesrow)
                        <li class="nav-item">
                            <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                <span>{{ $languagesrow->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <?php */ ?>
                    <div class="tab-content cust-tab-cont">
                        @foreach($data['languagesData'] as $languagesrow)
                        <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0 mandatory_field">Product Name<span class="notranslate pointimp" style="color:red;">*</span>
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <!-- <input type="text" name="product_name_{{$languagesrow->code}}" id="product-name-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('product_name_'.$languagesrow->code) }}" placeholder="Enter Product Name in {{ $languagesrow->name }}" class="prod-name form-control @if ($errors->has('product_name_en')) border border-danger @else border-0 @endif"> -->
                                    <input type="text" name="product_name_{{$languagesrow->code}}" id="product-name-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('product_name_'.$languagesrow->code) }}" placeholder="Enter Product Name in {{ $languagesrow->name }}" class="prod-name form-control @if ($errors->has('product_name_en')) border border-danger @else border border-primary @endif">
                                </div>
                                <span class="text-danger">@error('product_name_en'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Short Description in {{ $languagesrow->name }}</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <!-- <textarea rows="5" name="short_des_{{$languagesrow->code}}" id="cke_short-dis-<?php echo $languagesrow->code; ?>" maxlength="40" onkeyup="charCount(this.value,this.id,40)" placeholder="Enter Short Description in {{ $languagesrow->name }}" class="short-dis form-control border-0">{{ old('short_des_'.$languagesrow->code) }}</textarea> -->
                                    <textarea rows="5" name="short_des_{{$languagesrow->code}}" id="cke_short-dis-<?php echo $languagesrow->code; ?>" maxlength="40" onkeyup="charCount(this.value,this.id,40)" placeholder="Enter Short Description" class="short-dis form-control border-0">{{ old('short_des_'.$languagesrow->code) }}</textarea>
                                </div>
                                <span class="text-danger" id='cke_short-des-<?php echo $languagesrow->code; ?>'></span>
                                <span class="text-danger">@error('short_des_en'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Long Description in {{ $languagesrow->name }}</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="long_des_{{$languagesrow->code}}" id="long-dis-<?php echo $languagesrow->code; ?>" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="ckeditor long-dis form-control border-0">{{ old('long_des_'.$languagesrow->code) }}</textarea>
                                </div>
                                <span class="text-danger" id='long-des-<?php echo $languagesrow->code; ?>'></span>
                                <span class="text-danger">@error('long_des_en'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Meta Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <!-- <input type="text" name="meta_title_{{$languagesrow->code}}" value="{{ old('meta_title_'.$languagesrow->code) }}" placeholder="Enter Meta Title in {{ $languagesrow->name }}" class="metaTitle form-control border-0" autocomplete=off> -->
                                    <input type="text" name="meta_title_{{$languagesrow->code}}" value="{{ old('meta_title_'.$languagesrow->code) }}" placeholder="Enter Meta Title" class="metaTitle form-control border-0" autocomplete=off>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Meta Description</label>
                                <div class="col-md-12 border-bottom p-0">
                                    {{-- <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class="form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea> --}}
                                    <!-- <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class=" metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea> -->
                                    <textarea rows="5" name="meta_description_{{$languagesrow->code}}" id="product-metadesc-{{$languagesrow->code}}" placeholder="Enter Meta Description" class=" metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
            {{-- <div class="row cus-title">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Directory</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <?php// $allDirectory = Helper::getAllDirectory(); ?>
                                    <select class="form-control" name="directoryId" id="directoryId">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                            <option value="{{ $directoryrow->id }}"> {{ $directoryrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('directoryId'){{$message}} @enderror</span>
                            </div>
                        </div>
                </div> --}}
    <div class="row cus-title">




        <?php /*
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tags</label>
                                <div class="">
                                    <select multiple class="form-control" name="vendor_own_tags[]" id="vendor-tags">
                                        <option></option>
                                        @foreach($data['tagsownDirectory'] as $tagsrow)
                                        <option value="{{ $tagsrow->id }}" > {{ $tagsrow->tag_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        */?>

        {{-- <div class="col-md-12">
                <div class="form-group mb-4">
                <label class="col-md-12 p-0">Category</label>
                    <div class="">
                        <select multiple class="form-control" name="category[]" id="par-category">
                            <option></option>
                            @foreach($data['parent_category'] as $categorysrow)
                                <option value="{{ $categorysrow->id }}"> {{ $categorysrow->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div> --}}
    <div class="col-md-4 notranslate">
        <div class="form-group mb-4">
            <label class="col-md-12 p-0">Product Parent Category</label>
            <div class="">

                <select class="form-control notranslate" name="category" id="pcategory">
                    <option>Select parent category</option>
                    @foreach($data['parent_category'] as $categorysrow)
                    <option class="notranslate" value="{{ $categorysrow->id }}" @if(old('category')== $categorysrow->id) selected @endif> {{ $categorysrow->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4 notranslate">
        <div class="form-group mb-4">
            <label class="col-md-12 p-0"> Sub Category(select parent category first!)</label>
            <div class=" ">
                <select multiple class="form-control notranslate" translate="no" lang="en" name="subproductcat[]" id="par-subcat" onChange="getSelectedOptions(this)">
                    <option></option>
                    @foreach($data['productsubcat'] as $productsubcatrow)
                    <option class="notranslate" translate="no" value="{{ $productsubcatrow->id }}"></option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Tags</label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="text" name="vendor_own_tags" data-role="tagsinput" id="tags" value="{{ old('vendor_own_tags') }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                </div>
            </div>
        </div> --}}
    <div class="col-md-4 notranslate">
        <div class="form-group mb-4">
            <label class="col-md-12 p-0">Tags(select category first!)</label>
            <div class="col-md-12 border-bottom p-0">
                <select multiple class="form-control notranslate" name="vendor_own_tags[]" id="pro-tags">
                    <option></option>
                    @foreach($data['tags'] as $tagsrow)
                    <option class="notranslate" value="{{ $tagsrow->id }}"> {{ $tagsrow->tags }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    </div>

    

    <div class="row cus-title">

        @if(count($attributesData) > 0)
        @foreach($attributesData as $key => $val)
         
         @if($val->attributeStatus == 1 || $val->attributeStatus == null)
            <input type="hidden" value="{{ $val->id }}" data-skip-name="true" name="attr_id[]" data-name="attr_id[]">
            <div class="col-md-3">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">{{ $val->name??'' }}</label>
                    <div class="col-md-12 border-bottom p-0">

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group mb-4">
                    <div class="col-md-12 border-bottom p-0">
                        <input type="text" name="attr_value[]" id="attr_value" data-name="attr_value[]" data-id="{{ $val->id }}" data-role="tagsinput" placeholder="Attribute value" class="form-control p-0 border-0 attr_value">
                    </div>
                    <span class="text-danger">@error('attr_value'){{$message}} @enderror</span>
                </div>
            </div>
            
         @endif
       
        @endforeach
        @endif

        @if(count($attributesData) > 0)
        <div class="col-md-12">
            <a class="btn btn-success" id="add_variation" style="cursor: pointer; color: #fff;">
            Add all attribute values for add product variation
            </a>
            <hr>
            <div class="form-group mb-4 variations" style="display:none;">

                <div class="select_attribute">
                    
                </div>

                <div id="weblink_1" class="row weblink">
                
                    <div class="col-md-5">
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0 notranslate">ENA</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="text" name="v_ena[]" value="" placeholder="Enter ena number" class="form-control p-0 border-0 v_ena" autocomplete=off>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Stock</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="number" name="v_stock[]" value="" placeholder="Enter product stock" class="form-control p-0 border-0 v_stock_count" autocomplete=off>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" class="add_web_button" style="margin: 28px 0px;" title="Add field" onclick=addwebSection()>+</button>
                    </div>
                </div>

            </div>
        </div>
        @endif


    </div>

    <div class="row cus-title">
        <input type="hidden" name="sku" value="0" >
        <div class="col-md-4">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0 mandatory_field notranslate">Default ENA<span class="notranslate pointimp" style="color:red;">*</span></label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="text" min="0" name="ena" value="{{ old('ena') }}" placeholder="Enter ENA" class="form-control p-0 @if ($errors->has('ena')) border border-danger @else border border-primary @endif">
                </div>
                <span class="text-danger">@error('ena'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Total Stock</label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="number" id="default_stock" min="0" oninput="this.value = Math.abs(this.value)" name="stock" value="{{ old('stock') }}" placeholder="Enter Stock" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('stock'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Out of Stock</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="form-check-input" name="outstock" id="" value="outofstock">
                    <label class="custom-control-label" for="1"><i class="fa fa-warning" style="color: #636363;"></i> Out of Stock </label>
                </div>
                <hr>
                <span class="text-danger">@error('outstock'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">In-store only</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="form-check-input" name="in_store_only" id="" value="in_store_only">
                    <label class="custom-control-label" for="1"><i class="fa fa-warning" style="color: #636363;"></i> In-store </label>
                </div>
                <hr>
                <span class="text-danger">@error('in_store_only'){{$message}} @enderror</span>
            </div>
        </div>
    </div>

    <div class="row cus-title">
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Product No. Type</label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="text" name="product_number_type" value="{{ old('product_number_type') }}" placeholder="Enter product number type" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('product_number_type'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Supplier Product No</label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="text" name="supplier_product_no" value="{{ old('supplier_product_no') }}" placeholder="Enter supplier product no" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('supplier_product_no'){{$message}} @enderror</span>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Supplier Name</label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="Enter Supplier Name" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('supplier_name'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Supplier Name</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="form-check-input" name="is_visible_on_shop_detailpage" id="" value="yes" {{old('is_visible_on_shop_detailpage')=='yes' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="1"> Visible on shop details page </label>
                </div>
                <span class="text-danger">@error('is_visible_on_shop_detailpage'){{$message}} @enderror</span>
            </div>
        </div>
        <?php /* ?>
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Opening date </label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="date" min="0" name="opening_date" value="{{ old('opening_date') }}" placeholder="Enter recommended info" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('opening_date'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Closing date </label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="date" min="0" name="closing_date" value="{{ old('closing_date') }}" placeholder="Enter recommended info" class="form-control p-0 border-0">
                </div>
                <span class="text-danger">@error('closing_date'){{$message}} @enderror</span>
            </div>
        </div>
        <?php */ ?>
    </div>
    <div class="row mb-4">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="panel-heading cust-tab-head">
                <h4 class="box-title">Product Activation and Expiry Date</h4>
            </div>
        </div>
    </div>
    <div class="row mb-4 cus-title">
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Set Default Duration</label>
                <div class="custom-control custom-radio">
                    <input type="radio" class="pay" name="default_status" value="yes" {{ old('default_status')=="yes" ? 'checked' : '' }}>
                    <label class="custom-control-label" for="1">Yes</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="pay" name="default_status" value="no" {{ old('default_status')=="no" ? 'checked' : '' }} style="margin-left: 20px;">
                    <label class="custom-control-label" for="1">No</label>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="duration-month">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Set Months</label>
                <div class="custom-control custom-radio">
                    <input type="radio" class="pay" name="set_months" value="6_months" {{ old('set_months')=="6_months" ? 'checked' : '' }}>
                    <label class="custom-control-label" for="1">6 months </label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" class="pay" name="set_months" value="12_months" {{ old('set_months')=="12_months" ? 'checked' : '' }} style="margin-left: 40px;">
                    <label class="custom-control-label" for="1">12 months </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row cus-title">
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="col-md-12 mandatory_field p-0">Product Active Date<span class="notranslate" style="color:red;">*</span></label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="date" min="0" id="active-date" name="opening_date" value="{{ old('opening_date') }}" placeholder="Enter recommended info" class="form-control @if ($errors->has('opening_date')) border border-danger @else border border-primary @endif" autocomplete=off>
                </div>
                
                <span class="text-danger">@error('opening_date'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="col-md-12 mandatory_field p-0">Product Expiry Date<span class="notranslate" style="color:red;">*</span></label>
                <div class="col-md-12 border-bottom p-0">
                    <input type="date" min="0" id="expiry-date" name="closing_date" value="{{ old('closing_date') }}" placeholder="Enter recommended info" class="form-control @if ($errors->has('closing_date')) border border-danger @else border border-primary @endif" autocomplete=off>
                </div>
                <span class="text-danger">@error('closing_date'){{$message}} @enderror</span>
            </div>
        </div>
    </div>
    <div class="row" style="display:none;">
        <div class="row mb-4">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="panel-heading cust-tab-head">
                    <h4 class="box-title">Sales Price <span style="color:red;">*</span></h4>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['currency'] as $currencyrow)
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                    <div class="col-md-12 border-bottom p-0">
                    {{-- {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}} --}}
                        <input type="text" name="sales_{{ $currencyrow->code }}" value="{{ old($currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" @if($currencyrow->code != 'SEK') readonly @else  @endif >
                    </div>
                    <span class="text-danger">@error('sale_SEK'){{$message}} @enderror</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="cus-title">
        <div class="row mb-4">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="panel-heading cust-tab-head">
                    <h4 class="box-title">Regular Price <span class="notranslate" style="color:red;">*</span></h4>
                </div>
            </div>
        </div>

        <div class="row">

            <?php 
                $countryToCurrency = [
                    'Sweden' => 'SEK',
                    //GBP
                    'United Kingdom' => 'GBP',
                    'Guernsey' => 'GBP',
                    'Isle of Man' => 'GBP',
                    'Jersey' => 'GBP',
                    'Saint Helena' => 'GBP',
                    //PLN
                    'Poland' => 'PLN',
                    //DKK
                    'Denmark' => 'DKK',
                    'Greenland' => 'DKK',
                    'Faroe Islands' => 'DKK',
                    //NOK
                    'Norway' => 'NOK',
                    'Quisling regime' => 'NOK',
                    //EUR
                    'Andorra' => 'EUR',
                    'Austria' => 'EUR',
                    'Belgium' => 'EUR',
                    'Croatia' => 'EUR',
                    'Cyprus' => 'EUR',
                    'Estonia' => 'EUR',
                    'Finland' => 'EUR',
                    'France' => 'EUR',
                    'Germany' => 'EUR',
                    'Greece' => 'EUR',
                    'Ireland' => 'EUR',
                    'Italy' => 'EUR',
                    'Latvia' => 'EUR',
                    'Lithuania' => 'EUR',
                    'Luxembourg' => 'EUR',
                    'Malta' => 'EUR',
                    'Monaco' => 'EUR',
                    'Montenegro' => 'EUR',
                    'Netherlands' => 'EUR',
                    'Portugal' => 'EUR',
                    'San Marino' => 'EUR',
                    'Slovakia' => 'EUR',
                    'Slovenia' => 'EUR',
                    'Spain' => 'EUR',
                    //BGN
                    'Bulgaria' => 'BGN',
                    //HRK
                    'Croatia' => 'HRK',
                    //RON
                    'Romania' => 'RON',
                    //CZK
                    'Czech Republic' => 'CZK',
                    //HUF
                    'Hungary' => 'HUF',
                ];
                $country_code = '';
                if (isset($countryToCurrency[$country])) {
                    $country_code = $countryToCurrency[$country];
                }
                
            ?>

            @foreach($data['currency'] as $currencyrow)
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0 mandatory_field">{{ $currencyrow->name }}</label>
                    <div class="col-md-12 border-bottom p-0 ">
                        <input type="text" name="regular_{{ $currencyrow->code }}"  @if($currencyrow->code == 'SEK') value="{{ old('regular_SEK') }}"  @endif placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0 @if ($errors->has('regular_SEK')) border border-danger @else border border-primary @endif" {{ $currencyrow->code != $country_code ? 'disabled="disabled"' : ''}}>
                    </div>
                    
                </div>
            </div>
            @endforeach
            <span class="text-danger">@error('regular_SEK'){{$message}} @enderror</span>
        </div>
    </div>
    <hr />
    <div class="row cus-title">
        <div class="col-md-6 main-img-infoicon add-pro-mainimg" id="single-main-image">
            <div class="form-group mb-4">
                <label class="p-0" style="display: flex;">Main Image<span class="notranslate" style="color:red;">*</span>(800 X 800)px
                    <div id="tooltip">
                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                        <span><i class="fas fa-info-circle"></i></span>
                    </div>
                </label>
                <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Image upload at a time - 1</p>
                <div class=" @if ($errors->has('main_image')) imageuploadify-error @else imageuploadify @endif">
                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File Here To Upload</span>
                    {{-- <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button> --}}

                    <input type="file" name="main_image" id="main-image" class="form-control"  onchange="loadFile(event)">
                </div>
                <span class="text-danger">@error('main_image'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Preview</label>
                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                </div>
            </div>
        </div>
        
    </div>
    <div class="row cus-title">
        <div class="col-md-6 galimg-infoicon add-pro-galimg" id="multi-gal-image">
            <div class="form-group mb-4">
                <label class="p-0" style="display: flex;">Gallery Image (800 X 800)px
                    <div id="tooltip">
                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                        <span><i class="fas fa-info-circle"></i></span>
                    </div>
                </label>
                <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Gallery Image - Max 20</p>
                <div class="imageuploadify">
                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload<br>You have to upload multiple images at a time</span>

                    <input type="file" class="form-control" name="gal_image[]" id="gal-image" multiple>
                </div>
                <span class="text-danger help-block">@error('gal_image'){{$message}} @enderror</span>
                @foreach($errors->all() as $error)
                @if($error=="The gal_image.0 must be a file of type: jpeg, png, jpg, gif, svg.")
                <span class="text-danger">Only image file are allowed.</span>
                @endif
                @endforeach
                <!-- <span class="text-danger">@error('gal_image'){{$message}} @enderror</span> -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="col-md-12 p-0">Preview</label>
                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                </div>
            </div>
        </div>
    </div>
    <div class="row cus-title">


        <div class="col-lg-4 col-sm-6" id="single-video">
            <div class="form-group mb-4">
                <label class="p-0" style="display: flex;">Video</label>

                <input type="file" class="form-control" name="video" id="video">

                <span class="text-danger">@error('video'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6" id="single-video">
            <div class="form-group mb-4">
                <label class="p-0" style="display: flex;">Video Url</label>

                <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}" id="video_link">

                <span class="text-danger">@error('video_link'){{$message}} @enderror</span>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="form-group mb-4">
                <label class="col-md-1211">Is free product</label>
                <div class="col-md-1211">
                    <span>
                        <select name="is_free_product" class="form-control">
                            <option value="0" {{ "0"==old('is_free_product') ? 'selected' : '' }}> No </option>
                            <option value="1" {{old('is_free_product')=='1' ? 'selected' : '' }}> Yes </option>
                        </select>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6" id="single-video">
            <div class="form-group mb-4">
                <label class="p-0" style="display: flex;">Similar Product Code</label>

                <input type="text" class="form-control" value="{{ old('equal_product') }}" name="equal_product" id="equal_product">

                <span class="text-danger">@error('equal_product'){{$message}} @enderror</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-4">
                {{-- <label class="col-md-12 p-0">Popular</label> --}}
                <div class="custom-control custom-checkbox popular-infoicon">
                    <input type="checkbox" class="form-check-input" name="popular" id="" value="1" {{old('popular')=='1' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="1" style="display: inline-flex;"> Popular
                        <div id="infotooltip">
                            <span id="infotooltipText">Please check this, If you want this product to show in the list of popular.</span>
                            <span><i class="fas fa-info-circle" style="color:#3c3c3e !important;"></i></span>
                        </div>
                    </label>
                </div>
                <span class="text-danger">@error('popular'){{$message}} @enderror</span>
            </div>
        </div>
    </div>

    {{-- <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="pay" name="status" value="active" >
                                        <label class="custom-control-label" for="1">Active </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="pay" name="status" value="inactive" style="margin-left: 20px;" checked="">
                                        <label class="custom-control-label" for="1">Inactive </label>
                                    </div>
                            </div>
                        </div>

                    </div> --}}
    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}">
    <input type="hidden" name="createdby" id="hidden_variation_clicked" value="0">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                
                    <button type="submit" id="product_btn" class="btn btn-primary" >Save</button>
               
            </div>
        </div>
    </div>
    </form>

    </div>
    <!-- /row -->

    <!-- /row -->
    </div>
    <!-- /container -->
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>

    //$('.ckeditor').ckeditor();
    // Initialize CKEditor
    ClassicEditor
    .create(document.querySelector('.ckeditor'))
    .then(editor => {
        console.log('Editor was initialized', editor);
    })
    .catch(error => {
        console.error('Error during CKEditor initialization', error);
    });

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

    let lineno = 2;

    function addwebSection() {

        $("#weblink_1").after(`
        
        <div class="row" id="weblink_` + lineno + `">
        <hr>
        <div class="col-md-12 select_attribute_` + lineno + `">
           
        </div>
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0 notranslate">ENA</label>
                    <div class="col-md-12 border-bottom p-0">
                        <input type="text" name="v_ena[]" value="" placeholder="Enter ena number" class="form-control p-0 border-0" autocomplete=off>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="col-md-12 p-0">Stock</label>
                    <div class="col-md-12 border-bottom p-0">
                        <input type="number" name="v_stock[]" value="" placeholder="Enter stock " class="form-control p-0 border-0 v_stock_count" autocomplete=off>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" style="margin: 28px 0px;" class="remove_web_button" title="Remove field" onclick=removewebSection(` + lineno + `)>-</button>
            </div>
        </div>
        `);

        $('input[name="attr_value[]"]').each(function() {

            if($(this).val() != ''){
                //$('.select_attribute_' + lineno).append('<select name="v_attr"> <option value="'+ $(this).val() +'">' + $(this).val() + '</option> </select>');
           
                var myArr = $(this).val();
                result = myArr.split(',');
                var container = $('<select name="v_attr_value[]" >');
                $.each(result, function(index, item) {
                    console.log(item);
                    container.append('<option value="'+ item +'">' + item + '</option>');
                });
                $('.select_attribute_' + lineno).append('<select name="v_attr_value[]">'+container.html()+' </select>');
            }
        });

        $('#wesitecount').val(lineno);

        lineno++;
    }

    function removewebSection(count) {
        $('#weblink_' + count).remove();
    }

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.categoryselect').select2();
        $("#galleryimgPreview").sortable();
    });
   
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
    var today = currentdate.getFullYear() + "-" + (month) + "-" + (day);
    $("input[name=set_months]").change(function() {
        if ($(this).val() == "6_months") {
            var sixMonthsFromNow = new Date(currentdate.setMonth(currentdate.getMonth() + 6));
            var sixday = ("0" + sixMonthsFromNow.getDate()).slice(-2);
            var sixmonth = ("0" + (sixMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftersixmonthdate = sixMonthsFromNow.getFullYear() + "-" + (sixmonth) + "-" + (sixday);

            $('#active-date').val(today);
            $('#expiry-date').val(aftersixmonthdate);
        }
        if ($(this).val() == "12_months") {
            var twelveMonthsFromNow = new Date(currentdate.setMonth(currentdate.getMonth() + 12));
            var twelveday = ("0" + twelveMonthsFromNow.getDate()).slice(-2);
            var twelvemonth = ("0" + (twelveMonthsFromNow.getMonth() + 1)).slice(-2);
            var aftertwelvemonthdate = twelveMonthsFromNow.getFullYear() + "-" + (twelvemonth) + "-" + (twelveday);
            $('#active-date').val(today);
            $('#expiry-date').val(aftertwelvemonthdate);
        }

    });
    // ----------------------  set date 6 months and 12 months code end ---------------

</script>
<script>
    $(document).ready(function() {



        $('.v_stock_count').on('keyup', function() {

            var total = 0;
            $('.v_stock_count').each(function() {
               
                total += parseInt($(this).val());
            });
            $('#default_stock').val(total);
            //$('#default_stock').prop('readonly',true);
        });

        $("#par-category, #par-subcat, #vendor-tags, #pro-tags").select2({
            placeholder: "Select "
            , allowClear: true
        }).on("select2:open", function () {
            // Apply notranslate class and translate="no" to Select2-generated elements
            $('.select2-dropdown').addClass('notranslate').attr('translate', 'no');
            $('.select2-selection').addClass('notranslate').attr('translate', 'no');
        });

       
        $(".attr_value").change(function() {
            $('.variations').hide();
        });

        $('#add_variation').click(function() {
            $('.select_attribute').html(' ');
            $('#hidden_variation_clicked').val(1);

            //alert($('[name*=attr_value][value!=""]').length);
            
            var returnValue = false;
            $('input[name="attr_value[]"]').each(function() {
                if($(this).val() != ''){

                    returnValue = true;

                    $('.variations').show();
                    var myArr = $(this).val();
                    result = myArr.split(',');
                    var container = $('<select name="v_attr_value[]" >');
                    $.each(result, function(index, item) {
                        console.log(item);
                        container.append('<option value="'+ item +'">' + item + '</option>');
                    });
                
                   $('.select_attribute').append('<select name="v_attr_value[]">'+container.html()+' </select>');

                }
            });

             if (!returnValue) {
                toastr.error('Please insert attributes first!!');
                return false;
            }

        });


        $("#video").click(function() {

            <?php
            if (Helper::getFeatureUses('product-video') != true) {
                ?>
                toastr.error('You can not insert video. please upgrade package!');
                return false; <?php
            } ?>
        });

    });

</script>

<script>
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
        $('#gal-image').on('change', function() {
            document.getElementById("preview-gallery-img").style.display = "none";
            multiImgPreview(this, 'div.galleryimgPreview');

        });
    });

</script>

<script type="text/javascript">
    $('.prod-name').on('keypress keyup keydown', function(event) {
        // create the event
        var press = jQuery.Event(event.type);
        var code = event.keyCode || event.which;
        press.which = code;
        // trigger
        $('.metaTitle').val(this.value);
        $('.metaTitle').trigger(event.type, {
            'event': press
        });
    });


    $('.metaTitle').on('keypress keyup keydown change', function(event) {
        // sample
        $('.metaTitle').val(this.value);
        console.log(event.type);
    });

</script>

<script type="text/javascript">
    $('.short-dis').on('keypress keyup keydown', function(event) {
        // create the event
        var press = jQuery.Event(event.type);
        var code = event.keyCode || event.which;
        press.which = code;
        // trigger
        $('.metaDescp').val(this.value);
        $('.metaDescp').trigger(event.type, {
            'event': press
        });
    });


    $('.metaDescp').on('keypress keyup keydown change', function(event) {
        // sample
        $('.metaDescp').val(this.value);
        console.log(event.type);
    });

</script>
<script>
    function getSelectedOptions(sel) {
        var opts = [] , opt;

        var len = sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = sel.options[i];
            if (opt.selected) {
                opts.push(opt.value);
            }
        }

        var selectedvalueTags = $("#pro-tags").val();
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
                    $("#pro-tags").html("");
                    $.each(result.tags, function(key, value) {
                        $("#pro-tags").append('<option value="' + value.id + '">' + value.title + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });
                }
            });
        }

    }

</script>
<script>

    $(document).ready(function() {

        $("#product_btn").click(function() {

            var parsubcat = $('#par-subcat').val();
            localStorage.setItem('product_subcat_Id', parsubcat);
            var protags = $('#pro-tags').val();
            localStorage.setItem('product_tags', protags);


            var attr_value_arr = $('.attr_value').val();
            var v_ena_arr = $('.v_ena').val();
            var v_stock_count_arr = $('.v_stock_count').val();
            var is_variation = $('#hidden_variation_clicked').val();
            if(is_variation == 0){
                if (attr_value_arr.length === 0) { } else {
                    toastr.error('Add all attribute values to add product variation.');
                    return false;
                }
            }else{
                if (v_ena_arr.length === 0 || v_stock_count_arr.length === 0) { 
                    toastr.error('Please enter ENA and Stock of product variation.');
                    return false;
                }
            }

        });
        
        var pcategory = $('#pcategory').val();
        if(pcategory !== 'Select parent category'){
            setTimeout(
            function() 
            {
                var token = "{{ csrf_token() }}";
                $.ajax({
                    url: baseUrl + '/get-subprocat-by-productcatid'
                    , type: "post"
                    , data: {
                        cat_id: pcategory, _token: token
                    }
                    , success: function(responce) {
                        //console.log(responce);
                        var productId1 = localStorage.getItem('product_subcat_Id');
                        console.log('responce - ',responce);

                        if (localStorage.getItem("product_subcat_Id") === null) {
                            var arr = [];
                        }else{
                            var arr = productId1.split(',');
                        }
                        $('#par-subcat').html(responce);
                        $('#par-subcat').val(arr).change();
                        //$("#pro-tags").html('');
                    }
                });
            }, 2000);
        }


        //var subcat = $('#par-subcat').val();
        //alert(subcat);
        //if(subcat !== null){
            setTimeout(
            function() 
            {
                var productId1 = localStorage.getItem('product_tags');
                        if (localStorage.getItem("product_tags") === null) {
                            var arr = [];
                        }else{
                            var arr = productId1.split(',');
                        }
                        console.log(productId1);
                        console.log(arr);
                        $('#pro-tags').val(arr).change();

            }, 3000);
        //}


            
    });

    $(document).on('change', '#pcategory', function() {
        var cat_id = $(this).val();
        var token = "{{ csrf_token() }}";
        $.ajax({
            url: baseUrl + '/get-subprocat-by-productcatid'
            , type: "post"
            , data: {
                cat_id: cat_id, _token: token
            }
            , success: function(responce) {
                console.log(responce);
                $('#par-subcat').html(responce);
                $("#pro-tags").html('');
            }
        });
    });

    //$("#par-subcat").html('');
    $("#pro-tags").html('');

</script>

<script type="text/javascript">
    $(document).ready(function() {
        
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
            return 'Changes you made may not be saved.';
        });

        $('body').on('click', function(e) {
            var target, href;
            target = $(e.target);
            if (e.target.tagName === 'A' || target.parents('a').length > 0) {
                e.preventDefault();
                if (window.confirm("Do you want to leave the function?")) {
                    if (e.target.tagName === 'A') {
                        href = target.attr('href');
                    } else {
                        href = target.parents('a').first().attr('href');
                    }
                    window.location.href = href;
                }
            }
        });
        
        // Form Submit
        $(document).on("submit", "form", function(event) {
            // disable warning
            $(window).off('beforeunload');
        });
    });



    $('#video').change(function() {
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf" || ext == "txt" || ext == "xlsx" || ext == "xls" || ext == "doc" || ext == "dot" || ext == "docx" || ext == "GIF" || ext == "xml" || ext == "csv") {
            alert("Only Video file is accepted");
            $(this).val('');
        }
    });

</script>

<style type="text/css">
    .custom-control-label {
        position: unset;
    }

    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: unset;
    }

    .bootstrap-tagsinput {
        height: 37px !important;
    }

    #tooltip {
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

    #infotooltip {
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
    .ck.ck-editor__editable_inline>:last-child {
        min-height: 50px;
    }
    select {
        margin: 0px 10px 5px 0px;
        padding: 2px 10px 2px 10px;
    }
</style>

@endsection
