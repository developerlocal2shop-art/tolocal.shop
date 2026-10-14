@extends('layouts.adminmaster')
@section('title','Product')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
<style>
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 100px !important;
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

    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        display: block;
        padding: 4px 6px;
        color: #555;
        vertical-align: middle;
        border-radius: 4px;
        max-width: 100%;
        line-height: 22px;
        cursor: text;
    }

    .bootstrap-tagsinput input {
        border: none;
        box-shadow: none;
        outline: none;
        background-color: transparent;
        padding: 0 6px;
        margin: 0;
        width: auto;
        max-width: inherit;
    }

    .cust-tab-cont .tab-pane.active {
        background: none;
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
        transform: translate(-12%, 7%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:180px;
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

    @media(max-width:991px)
    {
        .productselectdir span.select2 {
            width: 100%!important;
        }
        .productsubcatselect span.select2 {
            width: 100%!important;
        }

    }

    @media(max-width:480px)
    {
        .page-wrapper>.container-fluid, .page-wrapper>.container-lg, .page-wrapper>.container-md, .page-wrapper>.container-sm, .page-wrapper>.container-xl, .page-wrapper>.container-xxl {
            padding: 15px;
        }

        .card .card-body {
            padding: 15px;
        }
    }
    .errorClass { border:  1px solid red !important; }
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Product</h4>
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

                <form action="{{ route('admin.product.submit') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >
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
                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Product Name<span style="color:red;">*</span>
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom @error('product_name_'.$languagesrow->code) errorClass @enderror">
                                            <input type="text" maxlength="20" name="product_name_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,50)" id="prd_name-<?php echo $languagesrow->code; ?>" value="{{ old('product_name_'.$languagesrow->code) }}" placeholder="Enter Product Name in {{ $languagesrow->name }}" class="prod-name form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('product_name_'.$languagesrow->code){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Short Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" maxlength="40" name="short_des_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,100)" id="cke_short-dis-<?php echo $languagesrow->code; ?>" placeholder="Enter Short Description in {{ $languagesrow->name }}" class="short-dis form-control border-0">{{ old('short_des_'.$languagesrow->code) }}</textarea>
                                        </div>
                                        <span class="text-danger" id='cke_short-des-<?php echo $languagesrow->code; ?>'></span>
                                        <span class="text-danger">@error('short_des_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Long Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="10" maxlength="5" name="long_des_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,500)" id="long-dis-<?php echo $languagesrow->code; ?>" class="ckeditor long-dis form-control border-0">{{ old('long_des_'.$languagesrow->code) }}</textarea>
                                        </div>
                                        <span class="text-danger" id='long-des-<?php echo $languagesrow->code; ?>'></span>
                                        <span class="text-danger">@error('long_des_en'){{$message}} @enderror</span>
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
                                            {{-- <textarea rows="5" maxlength="40" name="meta_description_{{$languagesrow->code}}" onkeyup="charCount(this.value,this.id,40)" id="meta-dis-<?php echo $languagesrow->code; ?>" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class="metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea> --}}
                                            <textarea rows="5" maxlength="40" name="meta_description_{{$languagesrow->code}}" id="meta-dis-<?php echo $languagesrow->code; ?>" placeholder="Enter Meta Description in {{ $languagesrow->name }}" class="metaDescp form-control border-0">{{ old('meta_description_'.$languagesrow->code) }}</textarea>
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
                                <label class="col-md-12 p-0">Select Directory</label>
                                <div class="col-md-12 border-bottom p-0 productselectdir">
                                    <?php $allDirectory = Helper::getAllDirectory(); ?>
                                    <select class="form-control" name="directoryId" id="directoryId" onChange="getAttributeBasedOnDirectoryOptions(this.value)">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                        <option value="{{ $directoryrow->id }}"> {{ $directoryrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('directoryId'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>

                    <?php /* ?> <div class="row cus-title">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Category</label>
                                <div class="col-md-12 ">

                                    <select multiple class="form-control" name="category[]" id="par-category">
                                        <option></option>
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}"> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> <?php */ ?>
                    <div class="row cus-title">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Parent Category</label>
                                <div class="">

                                    <select class="form-control" name="category" id="pcategory">
                                        <option>Select Parent Category</option>
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}"> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Sub Category(select product parent category first!)</label>
                                <div class="productsubcatselect">
                                    <select multiple class="form-control" name="subproductcat[]" id="par-subcat" onChange="getSelectedOptions(this)">
                                        <option></option>
                                        @foreach($data['productsubcat'] as $productsubcatrow)
                                        <option value="{{ $productsubcatrow->id }}"></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tags(select category first!)</label>
                                <div class="col-md-12 ">
                                    <select multiple class="form-control select2" name="vendor_own_tags[]" id="pro-tags" style="width:100%">
                                        <option></option>
                                        @foreach($data['tags'] as $tagsrow)
                                        <option value="{{ $tagsrow->id }}"> {{ $tagsrow->tags }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                        <div class="col-md-3" id="supplier-checkbox-col">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Supplier Details</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="is_visible_on_shop_detailpage" id="" value="yes">
                                    <label class="custom-control-label" for="1"> Visible on shop details page </label>
                                </div>
                                <span class="text-danger">@error('is_visible_on_shop_detailpage'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div id="default-prod-attribute-row">
                            <div class="col-md-12">
                                <hr>
                            </div>
                            @if(count($attributesData) > 0)
                            @foreach($attributesData as $key => $val)
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{ $val->name??'' }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="hidden" value="{{ $val->id }}" data-skip-name="true" name="attr_id[]" data-name="attr_id[]">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group mb-4">
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" min="0" name="attr_value[]" data-name="attr_value[]" data-role="tagsinput" value="" placeholder="Attribute value" class="form-control p-0 border-0 attr_value">
                                        </div>
                                        <span class="text-danger">@error('attr_value'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif

                            @if(count($attributesData) > 0)
                            <div class="col-md-12">
                                <a class="btn btn-success" id="add_variation" style="cursor: pointer; color: #fff;">
                                Add all attribute values to add product variation
                                </a>
                                <hr>
                                <div class="form-group mb-4 variations" style="display:none;">

                                    <div class="select_attribute">
                                        
                                    </div>

                                    <div id="weblink_1" class="row weblink">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">ENA</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="v_ena[]" value="" placeholder="Enter ena number" class="form-control p-0 border-0 v_ena" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Stock</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" name="v_stock[]" value="" placeholder="Enter product stock" class="form-control p-0 border-0 v_stock_count" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-success" class="add_web_button" style="margin: 28px 0px;" title="Add field" onclick=addwebSection()>+</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                             @endif


                        </div>



                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Default ENA</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" min="0" name="ena" value="{{ old('ena') }}" placeholder="Enter ENA" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('ena'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <input type="hidden" name="sku" value="0" >
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Total Stock</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" min="0" oninput="this.value = Math.abs(this.value)" name="stock" id="default_stock" value="{{ old('stock') }}" placeholder="Enter Stock" class="form-control p-0 border-0">
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
                               
                                <span class="text-danger">@error('in_store_only'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>

                    <?php /* ?> <div class="row cus-title">

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Opening date </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" min="0" name="opening_date" value="{{ old('opening_date') }}" placeholder="Enter recommended info" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('opening_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Closing date </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" min="0" name="closing_date" value="{{ old('closing_date') }}" placeholder="Enter recommended info" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('closing_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div> <?php */ ?>
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
                                <label class="col-md-12 p-0">Product Activation Date<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom @error('opening_date') errorClass @enderror">
                                    <input type="date" min="0" id="active-date" name="opening_date" value="{{ old('opening_date') }}" placeholder="Enter recommended info" class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('opening_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Expiry Date<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom @error('closing_date') errorClass @enderror">
                                    <input type="date" min="0" id="expiry-date" name="closing_date" value="{{ old('closing_date') }}" placeholder="Enter recommended info" class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('closing_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row" style="display:none;">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Sales Price</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($data['currency'] as $currencyrow)
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="sales_{{ $currencyrow->code }}" value="{{ old($currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}}>
                                    </div>
                                    <span class="text-danger">@error('sales'){{$message}} @enderror</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div> --}}
                    <div class="row cus-title">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Regular Price</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($data['currency'] as $currencyrow)
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <?php /* ?> <input type="text" name="regular_{{ $currencyrow->code }}" value="{{ old('sku') }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}}> <?php */ ?>
                                        <input type="text" name="regular_{{ $currencyrow->code }}" value="{{ old('regular_'.$currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" @if($currencyrow->code != 'SEK') readonly @else required @endif>
                                    </div>
                                    <span class="text-danger">@error('sales'){{$message}} @enderror</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <hr />
                    <div class="row cus-title">
                        <div class="col-md-6" id="single-main-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Main Image <span style="color:red;">*</span> (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify @error('main_image') errorClass @enderror">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

                                    <input type="file" class="form-control" name="main_image" id="main-image" onchange="loadFile(event)">
                                </div>
                                <span class="text-danger">@error('main_image'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Gallery Image (800 X 800)px</label>
                                <div class="col-md-12 imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>

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
                    </div>
                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                </div>
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
                        <div class="col-md-6" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Video</label>

                                <input type="file" class="form-control" name="video" id="video">

                                <span class="text-danger">@error('video'){{$message}} @enderror</span>

                            </div>
                        </div>
                        <div class="col-md-6" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Video Url</label>

                                <input type="text" class="form-control" name="video_link" id="video_link">

                                <span class="text-danger">@error('video_link'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4">
                                <label class="col-md-12">Is free product</label>
                                <div class="col-md-12">
                                    <span>
                                        <select name="is_free_product" class="form-control">
                                            <option value="0"> No </option>
                                            <option value="1"> Yes </option>
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Similar Product Code</label>

                                <input type="text" class="form-control" name="equal_product" id="equal_product">

                                <span class="text-danger">@error('equal_product'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4 my-2">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0"></label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="popular" id="" value="1">
                                    <label class="custom-control-label" for="1" style="display: inline-flex;"> Popular
                                        <div id="tooltip">
                                            <span id="tooltipText">Please check this, If you want this product to show in the list of popular.</span>
                                            <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                        </div>
                                    </label>
                                </div>
                                <span class="text-danger">@error('popular'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="createdby" id="hidden_variation_clicked" value="0">
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" id="save_product" class="btn btn-info">Save</button>
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

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>

    $(document).ready(function() {
        document.querySelectorAll('.ckeditor').forEach((el) => {
            ClassicEditor.create(el)
                .catch(error => console.error(error));
        });
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
                    <label class="col-md-12 p-0">ENA</label>
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

    $(document).ready(function() {

        
        $('#save_product').on('click', function() {
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
        
        $('.categoryselect').select2();
        
        $('.v_stock_count').on('keyup', function() {
            setTimeout(function(){
                var total = 0;
                $('.v_stock_count').each(function() {
                    total += parseInt($(this).val());
                });
                $('#default_stock').val(total);
                //$('#default_stock').prop('readonly',true);
            }, 1000);
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
                        //console.log(item);
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

    });

    // ----------------------  set date 6 months and 12 months code start ---------------
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
    

    $(document).ready(function() {
        $("#par-category, #par-subcat, #directoryId, #product_directory-tags, #pro-tags").select2({
            placeholder: "Select Option "
            , allowClear: true
        });
    });

    function getAttributeBasedOnDirectoryOptions(selectedDirectory) {
        if (selectedDirectory > 0) {
            $("#default-prod-attribute-row").remove();
        }

        $.ajax({
            url: baseUrl + '/admin/get-product-attribute-by-directoryid'
            , type: "post"
            , data: {
                directory_id: selectedDirectory
                , _token: '{{csrf_token()}}'
            }
            , success: function(responce) {
                $("#selected-prod-attribute-row-directory").remove();
                //console.log(responce);
                if (responce.length > 0) {

                    $("#supplier-checkbox-col").after(`<div id="selected-prod-attribute-row-directory"><div class="col-md-12"><hr></div></div>`);
                    $.each(responce, function(key, value) {

                        $("#selected-prod-attribute-row-directory").append(`<div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">` + value.name + `</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="hidden" value="` + value.id + `" data-skip-name="true" name="attr_id[]" data-name="attr_id[]">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group mb-4">
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" min="0" name="attr_value[]" data-name="attr_value[]" data-role="tagsinput" value="" placeholder="Attribute value" class="form-control p-0 border-0">
                                            </div>
                                            <span class="text-danger">@error('attr_value'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>`);
                    });

                }
                // $('#par-subcat').html(responce);

            }
        });
    }

    function getDirectoryTagsOptions(seldirectory) {
        var selobj = seldirectory.value;
        //alert(selobj);
        //console.log(selobj);
        $("#product_directory-tags").html('');
        $.ajax({
            url: baseUrl + '/admin/get-tags-by-directoryid',

            type: "POST"
            , data: {
                directory_id: selobj
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                if (selobj == 0) {
                    $.each(result.tags, function(key, value) {
                        $("#product_directory-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');
                    });
                } else {
                    $.each(result.directoryTags, function(key, value) {
                        $("#product_directory-tags").append('<option value="' + value.id + '">' + value.tag_name + '</option>');
                    });
                }

            }
        });
    }


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


    function charCount(textvalue, id, maxlenght) {
        var len = 0;
        var maxchartitle = 40;
        console.log(maxlenght);
        console.log(id);
        len = textvalue.length
        console.log(len);
        if (len > maxlenght) {
            return false;
        } else if (len > 0) {
            if ($(".errorshortcontent").length >= 1) {
                $('.errorshortcontent').remove();
            }
            $("#" + id).after("<span class='text-danger errorshortcontent'>" + (maxlenght - len) + "/" + (maxlenght) + " characters left! </span>");
        } else {
            if ($(".errorshortcontent").length >= 1) {
                $('.errorshortcontent').remove();
            }
            $("#" + id).after("<span class='text-danger errorshortcontent'>" + (maxlenght - len) + "/" + (maxlenght) + " characters left! </span>");
        }

    }

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
    $(document).on('change', '#pcategory', function() {

        var cat_id = $(this).val();
        var token = "{{ csrf_token() }}";
        $.ajax({
            url: baseUrl + '/get-subproductcat-by-productcategoryid'
            , type: "post"
            , data: {
                cat_id: cat_id
                , _token: token
            }
            , success: function(responce) {

                console.log(responce);
                $('#par-subcat').html(responce);
                $("#pro-tags").html('');


            }
        });
    });

    $("#pro-tags").html('');

</script>
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

        var selectedvalueTags = $("#pro-tags").val();

        //$("#par-tags").html('');
        // $("#par-owntags").html('');
        console.log("Selected Value: ", selectedvalueTags);
        // var selectedvalueTagsqwer = ["2","10","12"];
        // console.log("Selected Value second: ",selectedvalueTagsqwer);
        // return opts;
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

                    $("#pro-tags").html("");

                    $.each(result.tags, function(key, value) {

                        //if($.inArray(value.id, selectedvalueTags)) { value.tags = selectedvalueTags ; }else{ valuetags = ""; }

                        //console.log("VALUE : ",valuetags);
                        $("#pro-tags").append('<option value="' + value.id + '">' + value.tags + '</option>');

                        //$("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });

                }
            });
        }

    }

    //Video file validation

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

@endsection
