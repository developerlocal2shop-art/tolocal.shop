<?php
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
    $currencySymbol = session()->get('currency_code')['currsymbol'];
    $currencycode = session()->get('currency_code')['currcode'];
}else{
    $currencySymbol = $defaultCurrency[0]->symbol;
    $currencycode = $defaultCurrency[0]->code;
}
$currenturl = url()->current();
$currenturl = explode("/",$currenturl);
$arrendvalue = end($currenturl);
$maxPrice = "20000";
$params = ['cat' => [], 'amenity' => [], 'price' => []];

$categoryUrls = [];$subcategoryUrls = [];$producttagsUrls = [];$amenityUrls = [];
if (isset($_SERVER['QUERY_STRING'])) {
    $query  = explode('&', $_SERVER['QUERY_STRING']);
} else {
    $query = [];
}
if (!empty($query[0])) {
    foreach ($query as $param) {
        list($name, $value) = explode('=', $param);
        $params[urldecode($name)][] = urldecode($value);
    }
}
if (!empty($params['cat'])) {
    $categoryUrls = $params['cat'];
}
if (!empty($params['subcat'])) {
    $subcategoryUrls = $params['subcat'];
}
if (!empty($params['tags'])) {
    $producttagsUrls = $params['tags'];
}
if (!empty($params['amenity'])) {
    $amenityUrls = $params['amenity'];
}
if (!empty($params['price'])) {
    $price = $params['price'][0];
    $price = explode('-', $price);
    $configData['minPrice'] = $price[0];
    $configData['maxPrice'] = $price[1];
}
?>
<aside class="col-xl-2 order-0" id="sidebar">
    <div id="filters_col">
        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">{{__("message.filters_title")}} </a>
        <!-- Filter form starts-->
        <form class="filter-form" action="#" method="get" enctype="multipart/form-data">
            <div class="collapse show" id="collapseFilters">
                <input type="hidden" id="urlvalue" value="{{ $arrendvalue }}" />
                <input type="hidden" id="searchtext_search" value="{{ request()->search??'' }}" />
                <input type="hidden" id="cityname_search" value="{{ request()->city_name??'' }}" />
                <?php if(in_array("listings-product", $currenturl) || in_array("tags-product", $currenturl)){ ?>
                    <div class="filter_type">
                        @php
                        $pcat_vald = [];
                        $pcat_vald_tags = [];
                        $psubcat_vald = [];
                        @endphp
                        @foreach ($data['pData'] as $pcat_val)
                        @php
                        $pcat_vald[] = $pcat_val->cat_id;
                        $psubcat_vald[] = $pcat_val->pro_cat_id;
                        $pcat_vald_tags[] = $pcat_val->tags;
                        @endphp
                        @endforeach
                        @php 
                        $pcat_tag = implode (",",$pcat_vald_tags);
                        $pcat_tag_pro = explode (",",$pcat_tag);
                        @endphp
                        @if(count($pcat_vald) > 0)
                        <h6>{{__("message.filters_category_subtitle")}} </h6>
                        @endif

                        <ul class="panel-group" id="accordion">

                            <?php foreach($data['categories'] as $index =>$parentcat){ ?>
                            @if (in_array($parentcat->id, $pcat_vald))
                                <li class="panel-collapse" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $index }}">
                                    <div class="parentcat_filter">
                                        <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i>
                                        {{ ($parentcat->name!='')?$parentcat->name:$parentcat->name }} <small></small> <!-- Here will be the number of the total listing -->
                                    </div>
                                    <span class="container_check">
                                        <label class="">
                                            <input type="checkbox" name="category[]" class="categories" value="{{ $parentcat->id }}" onclick="filter(this)" {{ in_array($parentcat->id, $categoryUrls) ? ' checked' : '' }}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </span>
                                    <?php foreach($data['subcategories'] as $subcaticon){
                                        if($subcaticon->parent_id == $parentcat->id){?>
                                        @if(in_array($subcaticon->id, $psubcat_vald))
                                            <i class="fa fa-caret-down down-icon" aria-hidden="true"></i>
                                        @endif
                                    <?php }} ?>
                                </li>
                            @endif
                            <?php foreach($data['subcategories'] as $subcat){
                                if($subcat->parent_id == $parentcat->id){
                            ?>
                           
                           @if(in_array($subcat->id, $psubcat_vald))
                                <li class="ml-3 panel-collapse @if(in_array($parentcat->id, $categoryUrls))  @elseif(in_array($subcat->id, $subcategoryUrls)) @else collapse @endif" id="collapse-{{ $index }}">
                                    <label class="container_check"> {{ ($subcat->name!='')?$subcat->name:$subcat->name }} <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="subcategories" value="{{ $subcat->id }}" onclick="filter(this)" {{ in_array($subcat->id, $subcategoryUrls) ? ' checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            @endif

                            <?php foreach($data['producttags'] as $procattags){
                                if($procattags->child_cat_id == $subcat->id){
                            ?>
                            @if(in_array($procattags->id, $pcat_tag_pro))    
                                <li class="ml-3 panel-collapse @if(in_array($parentcat->id, $categoryUrls)) @elseif(in_array($subcat->id, $subcategoryUrls)) @elseif(in_array($procattags->id, $producttagsUrls)) @else collapse @endif" id="collapse-{{ $index }}" style="padding-left: 15px;">
                                    <label class="container_check"> {{ ($procattags->tags!='')?$procattags->tags:$procattags->tags }} <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="protags" value="{{ $procattags->id }}" onclick="filter(this)" {{ in_array($procattags->id, $producttagsUrls) ? ' checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            @endif   
                            <?php 
                            } } } } 
                            
                            } ?>
                            
                        </ul>
                </div>
                <?php } ?>

                <?php if(in_array("category-product", $currenturl) || in_array("listings-product", $currenturl) || in_array("tags-product", $currenturl)){ ?>
                <input type="hidden" id="urlvalue-cat-product" value="category-product" />
                <input type="hidden" id="urlvalue-list-product" value="listings-product" />
                <input type="hidden" id="urlvalue-tags-product" value="tags-product" />
                <?php } ?>

                <?php if(in_array("category-product", $currenturl) || in_array("listings-product", $currenturl) || in_array("tags-product", $currenturl)){ ?>
                <div class="filter_type">
                    <h6>{{__("message.price_limit_filter")}}</h6>
                    <div class="distance notranslate"> {{__("message.price_within_filter")}} <span></span> {{ $currencycode }}</div>
                    <input type="range" class="price-range" min="0" max="{{ $maxPrice }}" step="10" value="{{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:0) }}" data-orientation="horizontal" onchange="filter(this)">
                </div>
                <?php } ?>

            </div>
            <!--/collapse -->
        </form>
        <!-- filter form ends -->
    </div>
</aside>
<script type="text/javascript">
    function filter(elem) {
        var valueurl = $('#urlvalue').val();
        var valueurlCatPro = $('#urlvalue-cat-product').val();
        var valueurlListPro = $('#urlvalue-list-product').val();
        var valueurlTagsPro = $('#urlvalue-tags-product').val();
        
        if (valueurl == "shop") {
            var urlPrefix = 'shop?';
        }
        if (valueurl == "listings") {
            var urlPrefix = 'listings?';
        }
        if (valueurl == "search") {
            var urlPrefix = 'search?';
        }
        if (valueurlCatPro == "category-product") {
            var urlPrefix = valueurl + '?';
        }
        if (valueurlListPro == "listings-product") {
            var urlPrefix = valueurl + '?';
        }
        if (valueurlTagsPro == "tags-product") {
            var urlPrefix = valueurl + '?';
        }
        var urlSuffix = "";
        var slectedCategories = "";
        var slectedsubCategories = "";
        var slectedProductTags = "";
        var selectedAmenities = "";
        var selectedCity = "";
        var selectedVideoAvailability = 0;
        var selectedPriceRange = 0;
        var selectedOpeningStatus = "all";
        $('.categories:checked').each(function() {
            (slectedCategories === "") ? slectedCategories = $(this).attr('value'): slectedCategories = slectedCategories + "&cat=" + $(this).attr('value');
        });
        $('.subcategories:checked').each(function() {
            (slectedsubCategories === "") ? slectedsubCategories = $(this).attr('value'): slectedsubCategories = slectedsubCategories + "&subcat=" + $(this).attr('value');
        });
        $('.protags:checked').each(function() {
            (slectedProductTags === "") ? slectedProductTags = $(this).attr('value'): slectedProductTags = slectedProductTags + "&tags=" + $(this).attr('value');
        });
        $('.amenities:checked').each(function() {
            (selectedAmenities === "") ? selectedAmenities = $(this).attr('value'): selectedAmenities = selectedAmenities + "&amenity=" + $(this).attr('value');
        });
        selectedPriceMinRange = 0;
        selectedPriceRange = $('.price-range').val();
        console.log(selectedPriceRange);
        if (valueurl == "search") {
            if (slectedCategories != "") {
                urlSuffix = "search=" + $('#searchtext_search').val() + "&cat=" + slectedCategories + "&city_name=" + $('#cityname_search').val();
                window.location.replace(urlPrefix + urlSuffix);
            }
        } else {
            if (slectedCategories != "" && selectedAmenities != "") {
                urlSuffix = "cat=" + slectedCategories + "&amenity=" + selectedAmenities;
                window.location.replace(urlPrefix + urlSuffix);
            } else if (slectedCategories != "" && slectedsubCategories != "" && slectedProductTags == "" && selectedPriceRange==0) {
                urlSuffix = "cat=" + slectedCategories + "&subcat=" + slectedsubCategories;
                window.location.replace(urlPrefix + urlSuffix);
            }else if (slectedCategories != "" && slectedsubCategories != "" && slectedProductTags != "" && selectedPriceRange==0) {
                urlSuffix = "cat=" + slectedCategories+"&subcat=" + slectedsubCategories + "&tags=" + slectedProductTags;
                window.location.replace(urlPrefix + urlSuffix);
            }else if (slectedCategories != "" && slectedProductTags != "" && selectedPriceRange==0) {
                urlSuffix = "cat=" + slectedCategories + "&tags=" + slectedProductTags;
                window.location.replace(urlPrefix + urlSuffix);
            }else if (slectedsubCategories != "" && slectedProductTags != "" && selectedPriceRange==0) {
                urlSuffix = "subcat=" + slectedsubCategories + "&tags=" + slectedProductTags;
                window.location.replace(urlPrefix + urlSuffix);
            }else if ((slectedCategories != "" && slectedsubCategories=="" && slectedProductTags=="" && (selectedPriceRange != 0 && selectedPriceRange !== undefined)) && selectedAmenities == "") {
                urlSuffix = "cat=" + slectedCategories + "&price=" + selectedPriceMinRange + "-" + selectedPriceRange;
                window.location.replace(urlPrefix + urlSuffix);
            }else if((slectedsubCategories!="" && slectedCategories=="" && slectedProductTags=="" && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "subcat="+slectedsubCategories+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if((slectedCategories!="" && slectedsubCategories!="" && slectedProductTags=="" && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "cat="+slectedCategories+"&subcat="+slectedsubCategories+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if((slectedProductTags!=""  && slectedCategories=="" && slectedsubCategories=="" && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "tags="+slectedProductTags+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if((slectedCategories!="" && slectedsubCategories!="" && slectedProductTags!=""  && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "cat="+slectedCategories+"&subcat="+slectedsubCategories+"&tags="+slectedProductTags+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if((slectedCategories!="" && slectedProductTags!=""  && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "cat="+slectedCategories+"&tags="+slectedProductTags+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if((slectedsubCategories!="" && slectedProductTags!=""  && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                urlSuffix = "subcat="+slectedsubCategories+"&tags="+slectedProductTags+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                window.location.replace(urlPrefix+urlSuffix);
            }else if (slectedCategories != "") {
                urlSuffix = "cat=" + slectedCategories;
                window.location.replace(urlPrefix + urlSuffix);
            } else if (slectedsubCategories != "") {
                urlSuffix = "subcat=" + slectedsubCategories;
                window.location.replace(urlPrefix + urlSuffix);
            } else if (slectedProductTags != "") {
                urlSuffix = "tags=" + slectedProductTags;
                window.location.replace(urlPrefix + urlSuffix);
            }
            else if (selectedAmenities != "") {
                urlSuffix = "amenity=" + selectedAmenities;
                window.location.replace(urlPrefix + urlSuffix);
            } else if (selectedPriceRange != 0) {
                urlSuffix = "price=" + selectedPriceMinRange + "-" + selectedPriceRange;
                window.location.replace(urlPrefix + urlSuffix);
            } else {
                window.location.replace(urlPrefix);
            }        
        }
    }

</script>
<style>
    .collapse:not(.show) {
        display: none !important;
    }

    .down-icon {
        font-size: 16px;
        float: right;
        margin-right: -8px;
        margin-top: -33px;
    }

</style>
