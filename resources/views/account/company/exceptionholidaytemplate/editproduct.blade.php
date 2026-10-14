@extends('layouts.client.company')
@section('title','Edit product')
<meta name="google" content="notranslate">

@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">
	<div class="container">
		<h1>Edit Product</h1>
        <x-client.company_account_menu/>
	</div>
	<!-- /container -->
</div>
<!-- /sub_header -->

<!-- Main page -->
<main>
	<div class="container margin_60_35">
		<div class="row">

			<div class="col-lg-12">
				<div class="row">
					<div class="col-md-12">
                    
                        @if(session()->get('msg'))
                            <div class="alert alert-success">
                                {{ session()->get('msg') }}
                            </div><br />
                        @endif

                        @if(Session::has('error'))
                            <div class="alert alert-danger">
                            {{ Session::get('error') }}
                            @php
                            Session::forget('error');
                            @endphp
                            </div>
                        @endif

                        @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                            @php
                                Session::forget('success');
                            @endphp
                        </div>
                        @endif
					</div>
				</div>

				@php

				if (isset($data['productDetails'])) {
					$productDetails = $data['productDetails'];
				}
				if (isset($data['product_translationDesData'])) {
					$product_translationDesData = $data['product_translationDesData'];
				}
				if (isset($data['product_categoryData'])) {
					$product_categoryData = $data['product_categoryData'];
				}
                if (isset($data['tags'])) {
                    $product_tagData = $data['tags'];
                }
                if(isset($data['pro_subcat'])){
                    $productsubcat = $data['pro_subcat'];
                }
                if(isset($data['product_seo'])){
                    $productseo = $data['product_seo'];
                }
				@endphp
					<!--Main Area-->
					<form action="{{ route('company.product.update', $productDetails->id) }}" method="POST" class="" enctype="multipart/form-data" id="editpro" onsubmit="return submitForm()">

                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="box-title">Edit Product</h3>
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
                                        <label class="col-md-12 p-0">Product Name
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" name="product_name_{{$languagecodeBasicData->code}}" id="product-name-{{$languagecodeBasicData->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('product_name_'.$languagecodeBasicData->code, $product_translationDesDatarow->name ?? '') }}" placeholder="Enter Product Name {{ $languagecodeBasicData->name }}" class="prod-name form-control border-0"> -->
                                            <input type="text" name="product_name_{{$languagecodeBasicData->code}}" id="product-name-{{$languagecodeBasicData->code}}" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('product_name_'.$languagecodeBasicData->code, $product_translationDesDatarow->name ?? '') }}" placeholder="Enter Product Name" class="prod-name form-control border-0">
                                            <input type="hidden" name="langtranlation_{{$languagecodeBasicData->code}}" value="{{ $product_translationDesDatarow->id }}" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('product_name_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Short Description(Allow to Enter 40 Charecter only!)</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <textarea rows="5" id="cke_short-dis-<?php echo $languagecodeBasicData->code; ?>" maxlength="50" onkeyup="charCount(this.value,this.id,50)" name="short_des_{{$languagecodeBasicData->code}}" placeholder="Enter Short Description in {{ $languagesrow->name }}" class="short-dispro form-control border-0">{{ old('short_des_'.$languagecodeBasicData->code, strip_tags($product_translationDesDatarow->short_content) ?? '') }}</textarea> -->
                                            <textarea rows="5" id="cke_short-dis-<?php echo $languagecodeBasicData->code; ?>" maxlength="50" onkeyup="charCount(this.value,this.id,50)" name="short_des_{{$languagecodeBasicData->code}}" placeholder="Enter Short Description" class="short-dispro form-control border-0">{{ old('short_des_'.$languagecodeBasicData->code, strip_tags($product_translationDesDatarow->short_content) ?? '') }}</textarea>
                                        </div>
                                        <span class="text-danger">@error('short_des_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Long Description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="long_des_{{$languagecodeBasicData->code}}" class="ckeditor long-dis form-control p-0 border-0">{{ old('short_des_'.$languagecodeBasicData->code, $product_translationDesDatarow->long_content ?? '') }}</textarea>
                                        </div>
                                        <span class="text-danger">@error('long_des_en'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta Title</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" name="meta_title_{{$languagecodeBasicData->code}}" value="{{ old('meta_title_'.$languagecodeBasicData->code, $product_translationDesDatarow->meta_title ?? '') }}" placeholder="Enter Meta Title in {{ $languagecodeBasicData->name }}" class="metaTitle form-control border-0" autocomplete=off> -->
                                            <input type="text" name="meta_title_{{$languagecodeBasicData->code}}" value="{{ old('meta_title_'.$languagecodeBasicData->code, $product_translationDesDatarow->meta_title ?? '') }}" placeholder="Enter Meta Title" class="metaTitle form-control border-0" autocomplete=off>
                                        </div>
                                    </div>
                                    <input type="hidden" name="page_type" value="{{ $productseo[0]->page_type??'' }}" class="form-control p-0 border-0">
                                    <input type="hidden" name="module_id" value="{{ $productseo[0]->module_id??'' }}" class="form-control p-0 border-0">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="meta_description_{{$languagecodeBasicData->code}}" id="product-metadesc-{{$languagecodeBasicData->code}}" class="metaDescp form-control border-0">{{ old('meta_description_'.$languagecodeBasicData->code, $product_translationDesDatarow->meta_description ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>

                            @endforeach
                            </div>
                        </div>
                    </div>
					<input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">

                    <div class="row">

                        {{-- <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Category</label>
                                <div class="">

                                    <select multiple class="form-control" name="category[]" id="par-category">
                                        <option></option>
                                        @foreach($data['parent_category'] as $categorysrow)
                                        <option value="{{ $categorysrow->id }}" <?php if(in_array($categorysrow->id,$product_categoryData)){echo "selected";} ?>> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-3 notranslate">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Parent Category</label>
                                <div class="">

                                    <select class="form-control" name="category" id="pcategory">
                                        <option value="0">Select category</option>
                                        @foreach($data['parent_category'] as $categorysrow)

                                        <option value="{{ $categorysrow->id }}" @if($categorysrow->id==$productDetails->cat_id) selected @endif> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 notranslate">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Sub Category(select product parent category first!)</label>
                                <div class=" ">

                                    <select multiple class="form-control" name="subproductcat[]" id="par-subcat" onChange="getSelectedOptions(this)">

                                        {{-- @foreach($data['productsubcat'] as $productsubcatrow) --}}

                                            {{-- @foreach($productDetails->subcat as $subcat) --}}
                                            {{-- <option value="{{ $productsubcatrow->id }}" @if($productsubcatrow->id==$subcat->category_id) selected @endif>  --}}
                                            {{-- <option value="{{ $subcat->category_id }}" selected>
                                            {{ Helper::getCatName($subcat->category_id)->name??'' }}
                                            </option>
                                            @endforeach --}}

                                        {{-- @endforeach --}}

                                        @foreach($productsubcat as $productsubcatrow)
                                        <option value="{{ $productsubcatrow['id'] }}" <?php if(in_array($productsubcatrow['id'], $data['product_categoryData'])){echo "selected";} ?>> {{ $productsubcatrow['name'] }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 notranslate">
                            {{-- <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tags</label>
                                <div class="">
                                    <input type="text" name="vendor_own_tags" data-role="tagsinput" id="tags" value="{{ old('vendor_own_tags', $productDetails->tags) }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                                    <?php /* $tags  = explode(",",$productDetails->tags); ?>
                                    <select multiple class="form-control" name="vendor_own_tags[]" id="vendor-tags">
                                        <option value=0>None</option>
                                        @foreach($data['tagsownDirectory'] as $tagsrow)
                                        <option value="{{ $tagsrow->id }}" <?php if(in_array($tagsrow->id,$tags)){echo "selected";} ?>> {{ $tagsrow->tag_name }}</option>
                                        @endforeach
                                    </select>
                                    <?php */ ?>
                                </div>
                            </div> --}}
                            <div class="form-group mb-4 notranslate">
                                <label class="col-md-12 p-0">Tags(select category first!) </label>
                                <div class="notranslate">
                                    <?php $tags  = explode(",",$productDetails->tags); ?>
                                    <select multiple class="form-control notranslate" name="vendor_own_tags[]" id="pro-tags" >
                                        <option></option>
                                        @foreach($product_tagData as $tagsrow)
                                        <option class="notranslate" value="{{ $tagsrow->id }}" <?php if(in_array($tagsrow->id, $tags)){echo "selected";} ?>> {{ $tagsrow->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row cus-title">
                        {{-- <div class="col-md-12">
                            <div class="form-group mb-4">
                                {!! $attributes !!}
                            </div>
                        </div> --}}

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
                                            <input type="text" name="attr_value[]" data-name="attr_value[]" id="attr_value" data-role="tagsinput" value="{{ $val->productAttributes->attribute_value??'' }}" placeholder="Attribute value" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('attr_value'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            @endif    
                        @endforeach
                        @endif


                        <div class="col-md-12">
                            
                                <a class="btn btn-success btn-sm" id="add_variation" style="cursor: pointer; color: #fff;">
                                @if(count($attributesVariationData) == 0) Add all attribute values for add product variation @else Get all attribute values for update product variation @endif
                                </a>
                            
                            
                            <hr>
                            <div class="form-group mb-4 variations" style="display:@if(count($attributesVariationData) == 0) none @endif ;">

                                @if(count($attributesVariationData) == 0)
                                <div class="select_attribute">
                                           
                                </div>
                                <div id="weblink_1" class="row weblink">
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
                                            <label class="col-md-12 p-0"> Stock</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="number" name="v_stock[]" value="0" placeholder="Enter product stock" class="form-control p-0 border-0 v_stock_count" autocomplete=off>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success" class="add_web_button" style="margin: 28px 0px;" title="Add field" onclick=addwebSection()>+</button>
                                    </div>
                                </div>
                                @endif

                                @php
                                    $v = 1;
                                    $v_stock_count = 0;
                                @endphp
                                @foreach ($attributesVariationData as $key => $attrVarVal )
                                   @php
                                       $attribute_value = explode(',',$attrVarVal->attribute_value);
                                   @endphp
                                    <div id="weblink_{{ $v }}" class="row weblink">
                                        <div class="col-md-12">
                                        <div class="select_attribute">
                                            @foreach ($attribute_value as $attr_val)
                                                <select name="v_attr_value[]">
                                                <option value="{{ $attr_val }}">{{ $attr_val }}</option>
                                                </select>
                                            @endforeach
                                        </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0 notranslate">ENA</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="v_ena[]" value="{{ $attrVarVal->v_ena??'' }}" placeholder="Enter ena number" class="form-control p-0 border-0" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">Stock</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="number" name="v_stock[]" value="{{ $attrVarVal->v_stock??'' }}" placeholder="Enter product stock" class="form-control p-0 border-0 v_stock_count" autocomplete=off>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-1">
                                            @if($key == 0)
                                                <button type="button" class="btn btn-success" class="add_web_button" style="margin: 28px 0px;" title="Add field" onclick=addwebSection()>+</button>
                                            @else
                                            <button type="button" class="btn btn-danger" style="margin: 28px 0px;" title="Remove field" onclick="removewebSection(2)">-</button>
                                            @endif
                                            
                                        </div>
                                        
                                    </div>

                                    @php
                                        $v_stock_count += $attrVarVal->v_stock;
                                         $v++;
                                    @endphp

                                @endforeach
                                
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
                                <label class="col-md-12 p-0 notranslate">Default ENA</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" min="0" name="ena" value="{{ old('ena', $productDetails->ena??'') }}" placeholder="Enter ENA" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('ena'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Total Stock</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" min="0" oninput="this.value = Math.abs(this.value)" id="default_stock" name="stock" value="{{ old('stock', $productDetails->stock) }}" placeholder="Enter Stock" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('stock'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Out of Stock</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="outstock" id="" value="outofstock" {{ $productDetails->outofstock=='outofstock' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1"><i class="fa fa-warning" style="color: #636363;"></i> Out of Stock </label>
                                </div>
                                 <hr>
                                <span class="text-danger">@error('outstock'){{$message}} @enderror</span>
                            </div>
                           
                        </div>
                        <div class="col-md-2">
                            {{ dd($productDetails->in_store_only) }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">In-store only</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="in_store_only" id="" value="in_store_only" {{ $productDetails->in_store_only=='in_store_only' ? 'checked' : '' }}>
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
                                <label class="col-md-12 p-0"> Product No. Type</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="product_number_type" value="{{ old('product_number_type', $productDetails->product_number_type??'') }}" placeholder="Enter product number type" required class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('product_number_type'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Supplier Product No. </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="supplier_product_no" value="{{ old('supplier_product_no', $productDetails->supplier_product_no??'') }}" placeholder="Enter supplier product no" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('supplier_product_no'){{$message}} @enderror</span>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Supplier Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="supplier_name" value="{{ old('supplier_name', $productDetails->supplier_name??'') }}" placeholder="Enter Supplier Name" class="form-control p-0 border-0">
                                </div>
                                
                                <span class="text-danger">@error('supplier_name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Supplier Name</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="is_visible_on_shop_detailpage" id="" value="yes" {{ $productDetails->is_visible_on_shop_detailpage=='yes' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1"> Visible on shop details page </label>
                                </div>
                                <hr>
                                <span class="text-danger">@error('is_visible_on_shop_detailpage'){{$message}} @enderror</span>
                            </div>
                        </div>

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
                                    <input type="radio" class="pay" name="default_status" value="yes" {{ $productDetails->default_duration=='yes' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Yes</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="pay" name="default_status" value="no" {{ $productDetails->default_duration=='no' ? 'checked' : '' }} style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" id="duration-month">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Set Months</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="pay" name="set_months" value="6_months" {{ $productDetails->default_month=='6_months' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">6 months </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="pay" name="set_months" value="12_months" {{ $productDetails->default_month=='12_months' ? 'checked' : '' }} style="margin-left: 40px;">
                                    <label class="custom-control-label" for="1">12 months </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Active Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="active-date"  min="0" name="opening_date" value="{{ old('opening_date', $productDetails->opening_date??'') }}" placeholder="Enter recommended info" class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('opening_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Product Expiry Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="expiry-date" min="0" name="closing_date" value="{{ old('closing_date', $productDetails->closing_date??'') }}" placeholder="Enter recommended info"  class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('closing_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
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

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">{{ $currencyprrow['currency_name'] }}</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="hidden" name="salesprice_{{ $currencyprrow['currency_code']??'' }}" value="{{ $currencyprrow['id'] }}" class="form-control p-0 border-0">
                                                <input type="text" name="sales_{{ $currencyprrow['currency_code']??'' }}" value="{{ old('sales_'.$currencyprrow['currency_code'], $currencyprrow['sales_price']) }}" placeholder="Enter {{ $currencyprrow['currency_name'] }}" class="form-control border-0" disabled="disabled">
                                            </div>
                                            <span class="text-danger">@error('sales_'.$currencyprrow['currency_code']) {{$message}} @enderror</span>
                                        </div>
                                    </div>
                                @endforeach
                                <?php }else{ ?>

                                    @foreach($data['currency']  as $currencyrow)

                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                                <div class="col-md-12 border-bottom p-0">
                                                    <input type="text" name="sales_{{ $currencyrow->code??'' }}" value="{{ old('sales_'.$currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" class="form-control border-0" {{ $currencyrow->base_currency=="no" ? 'disabled="disabled"' : ''}}>
                                                </div>
                                                <span class="text-danger">@error('sales_'.$currencyrow->code){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    @endforeach
                                <?php } ?>



                        </div>
                    </div>
                    <div class="row1">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Regular Price</h4>
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

                        <?php if(!empty($data['productpriceData'])){ ?>
                            @foreach($data['productpriceData']  as $regularpricerow)
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{ $regularpricerow['currency_name'] }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="regular_{{ $regularpricerow['currency_code'] }}" value="{{ old('regular_'.$regularpricerow['currency_code'], $regularpricerow['regular_price']) }}" @if($regularpricerow['currency_code'] != $country_code) readonly @else required @endif placeholder="Enter {{ $regularpricerow['currency_name'] }}" class="form-control border-0" {{ $regularpricerow['currency_base']=="no" ? ' "' : ''}}>
                                        </div>
                                        <span class="text-danger">@error('regular_'.$regularpricerow['currency_code']){{$message}} @enderror</span>
                                    </div>
                                </div>
                            @endforeach
                        <?php }else{ ?>
                            @foreach($data['currency']  as $currencyrow)
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{ $currencyrow->name }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="regular_{{ $currencyrow->code }}" value="{{ old('regular_'.$currencyrow->code) }}" placeholder="Enter {{ $currencyrow->name }}" @if($currencyrow->code != 'SEK') readonly @else required @endif class="form-control border-0" {{ $currencyrow->code != $country_code ? 'disabled="disabled"' : ''}}>
                                        </div>
                                        <span class="text-danger">@error('regular_'.$currencyrow->code ){{$message}} @enderror</span>
                                    </div>
                                </div>
                            @endforeach
                        <?php } ?>


                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 main-img-infoicon" id="single-main-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Main Image (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle" ></i></span>
                                    </div>
                                </label>
                                <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Image upload at a time - 1</p>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File Here To Upload</span>
                                    {{-- <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button> --}}

                                    <input type="file" class="form-control" name="main_image" id="main-image" onchange="loadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 mainimgPreview" id="mainimgPreview">
                                    @php
                                    $imagename_thumb = $productDetails->image[0]->path??'';
                                    @endphp
                                    @if($imagename_thumb)
                                        <div class="thumbnail">
                                            <img src="{{ env('IMAGE_PATH').$imagename_thumb.'.webp' }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                        </div>
                                    @else
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-6 galimg-infoicon" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Gallery Image (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle" ></i></span>
                                    </div>
                                </label>
                                <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> Gallery Image - Max 20</p>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s)Here To Upload<br>You have to upload multiple images at a time</span>
                                    {{-- <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button> --}}

                                    <input type="hidden" name="thumb_hidden_img" id="thumb_hidden_img" value="">
                                    <input type="file" class="form-control" name="gal_image[]" id="gal-image" multiple>
                                </div>
                                <span class="text-danger">@error('gal_image'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">

                                    <?php if (count($productDetails->gallery) != 0) { ?>
                                        @foreach($productDetails->gallery as $imagenamerow)
                                            <div class="thumbnail" data-thumbimages="{{ $imagenamerow->name_thumbnail }}">
                                                <img src="{{ env('IMAGE_PATH').$imagenamerow->path.'.webp' }}" data-id="{{ $imagenamerow->id }}" >
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
                    <hr/>
                    <div class="row cus-title">
                     
                        

                       
                        <div class="col-md-2" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Video Url</label>

                                    <input type="text" class="form-control" name="video_link" id="video_link" value="{{ old('video_link', $productDetails->video_link??'') }}">

                                <span class="text-danger">@error('video_link'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-1211">Is free product</label>
                                <div class="col-md-1211">
                                    <span>
                                    <select name="is_free_product" class="form-control">
                                        <option value="0" @if($productDetails->is_free_product == 0) selected @endif> No </option>
                                        <option value="1" @if($productDetails->is_free_product == 1) selected @endif> Yes </option>
                                    </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Similar Product Code</label>

                                    <input type="text" class="form-control" name="equal_product" id="equal_product" value="{{ old('equal_product', $productDetails->equal_product??'') }}">

                                <span class="text-danger">@error('equal_product'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                {{-- <label class="col-md-12 p-0">Popular</label> --}}
                                <div class="custom-control custom-checkbox pro-popular-infoicon" style="padding: 25px 0px 0px;">
                                    <input type="checkbox" class="form-check-input" name="popular" id="" value="1" {{ $productDetails->popular=='1' ? 'checked' : '' }}>
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
                    @php
                    $video = $productDetails->video[0]->video_link??'';
                    @endphp

                    
                    <div class="row">
                        

                         <div class="col-md-3" id="single-video">
                            <div class="form-group mb-4">
                                <label class="p-0" style="display: flex;">Video</label>

                                    <input type="file" class="forsm-control" name="video" id="video">

                                <span class="text-danger">@error('video'){{$message}} @enderror</span>
                            </div>

                        </div>
                           
                           @if(!empty($video))
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="col-md-12">Preview</label>
                                    <div class="col-md-12">
                                        @php
                                        $video = $productDetails->video[0]->video_link??'';
                                        @endphp
                                        @if($video)

                                            <video width="100" height="150" controls preload="auto" style="opacity: unset !important;">
                                                <source src="{{ env('IMAGE_PATH').'files/productvideo/'.$video }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif


                         
                    </div>
                    {{-- <div class="row">
                        <div class="form-group mb-4">
                            <label class="col-md-12">Is free product</label>
                            <div class="col-md-12">
                                <span>
                                <select name="is_free_product">
                                    <option value="0" @if($productDetails->is_free_product == 0) selected @endif> No </option>
                                    <option value="1" @if($productDetails->is_free_product == 1) selected @endif> Yes </option>
                                </select>
                                </span>
                            </div>
                        </div>
                    </div> --}}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">

                                @if ($user->hasPermission('update') == true)
                                    <button type="submit" class="btn btn-primary">Save</button>
                                @endif

                            </div>
                        </div>
                    </div>
                </form>



				</div>
				<!-- /row -->
			</div>


		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</main>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>


<script>

    document.querySelectorAll('.notranslate').forEach(element => {
        element.setAttribute('translate', 'no');
    });

    //$('.ckeditor').ckeditor();
     // Initialize CKEditor
     ClassicEditor
        .create(document.querySelector('.ckeditor'))
        .then(editor => {
        })
        .catch(error => {
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
    // ----------------------  set date 6 months and 12 months code start ---------------
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
        
        <?php
            if(count($attributesVariationData) > 0){
        ?>
           $('#default_stock').val({{ $v_stock_count }});
           //$('#default_stock').prop('readonly',true);
        <?php
            }
        ?>

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
            const count = $("#attr_value").length;
            
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


        
    
    
        $('#par-category,#par-subcat, #vendor-tags, #pro-tags').select2().on("select2:open", function () {
            // Apply notranslate class and translate="no" to Select2-generated elements
            $('.select2-dropdown').addClass('notranslate').attr('translate', 'no');
            $('.select2-selection').addClass('notranslate').attr('translate', 'no');
        });
        $("#galleryimgPreview").sortable();

         $("#video").click(function() {

            <?php if (Helper::getFeatureUses('product-video') != true){ ?>
                toastr.error('You can not update video. please upgrade package!');
                return false;
            <?php
            }
            ?>
        });

    });
</script>
<script>
    $(document).ready(function() {
        // $("#pcategory, #par-subcat, #directoryId, #pro-tags").select2({
        //         placeholder: "Select ",
        //         allowClear: true
        // }).on("select2:open", function () {
        //     // Apply notranslate class and translate="no" to Select2-generated elements
        //     $('.select2-dropdown').addClass('notranslate').attr('translate', 'no');
        //     $('.select2-selection').addClass('notranslate').attr('translate', 'no');
        // });
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
                //document.getElementById("preview-gallery-img").style.display = "none";
                multiImgPreview(this, 'div.galleryimgPreview');

            });
        });

    </script>
    <script>
        $(document).on('click', ".closebtn", function() {

            if(confirm("Are you sure you want to delete this?")){
                var val = $(this).closest(".thumbnail");
                var imageid = $(this).closest(".thumbnail").find('img').attr('data-id');
                var imagevalue = '/company/delete-gallery-img';
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
                
            }else{
                return false;
            }

        });



    function submitForm() {

        var thumbimages = [];
        $('.galleryimgPreview div').each(function() {
            thumbimages.push($(this).data('thumbimages'));
            //alert(thumbimages);
        });
        $('#thumb_hidden_img').val(thumbimages.join(','));

        return;
    }


    </script>

<script>

    $(document).on('change','#pcategory', function(){

        var cat_id = $(this).val();
        var token = "{{ csrf_token() }}";
        $.ajax({
        url: baseUrl + '/get-subprocat-by-productcatid',
        type: "post",
        data: {cat_id:cat_id, _token:token},
        success: function(responce){

            console.log(responce);
            $('#par-subcat').html(responce);
            $("#pro-tags").html('');

            }
        });
    });

    //$("#par-subcat").html('');

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

        //console.log("Selected Value: ", selectedvalueTags);
        var selectedvalueTagsqwer = [];
        for(var k=0;k<selectedvalueTags.length;k++){
            selectedvalueTagsqwer.push(parseInt(selectedvalueTags[k]));
        }
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
                    var valuetags = "";

                    $.each(result.tags, function(key, value) {
                        if(jQuery.inArray(value.id,selectedvalueTagsqwer) == -1){ valuetags = ""; }else{ valuetags = "selected"; }
                        $("#pro-tags").append('<option class="notranslate" value="' + value.id + '"'+valuetags+'>' + value.title + '</option>');
                        // $("#par-tags").append('<option value="' + value.id + '" ' + valuetags + ' >' + value.tags + '</option>');
                    });

                }
            });
        }

    }

</script>
<script type="text/javascript">
$(document).ready(function () {

    
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault(); e.returnValue = '';
        return 'Changes you made may not be saved.';
    });

    $(document).on("submit", "form", function(event){
        $(window).off('beforeunload');
    });

});
</script>


<script type="text/javascript">
    $('.prod-name').on('keypress keyup keydown',function(event) {
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
    $('.short-dispro').on('keypress keyup keydown',function(event) {
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
</script>

	<style>
    .bootstrap-tagsinput {
        height: 37px !important;
    }
	.custom-control-label {
		position: unset;
	}
	[type="radio"]:checked, [type="radio"]:not(:checked) {
		position: unset;
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
    .custom-control.custom-checkbox .custom-control-label {
        margin-bottom: -4px;
    }
    .ck.ck-editor__editable_inline>:last-child {
        
        min-height: 50px;
    }
    select {
        margin: 0px 10px 5px 0px;
        padding: 2px 10px 2px 10px;
    }
    .notranslate {
        translate: no;
    }
    .notranslate, .select2-dropdown, .select2-selection {
        translate: no;
    }
	</style>
@endsection
