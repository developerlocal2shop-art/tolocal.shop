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

$categoryUrls = [];$subcategoryUrls = []; $producttagsUrls = []; $amenityUrls = [];
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
<aside class="col-xl-3 order-0" id="sidebar">
    <div id="filters_col">


        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters"
            id="filters_col_bt">{{__("message.filters_title")}} </a>
        <!-- Filter form starts-->
        <form class="filter-form" action="#" method="get" enctype="multipart/form-data">


            <div class="collapse show" id="collapseFilters">
                <input type="hidden" id="urlvalue" value="{{ $arrendvalue }}" />
                <input type="hidden" id="searchtext_search" value="{{ request()->search??'' }}" />
                <input type="hidden" id="cityname_search" value="{{ request()->city_name??'' }}" />
                <?php //if(in_array("shop", $currenturl) || in_array("tags-product", $currenturl)){ ?>

                <div class="filter_type acc_shop_filter">
                    @php
 
                    $pcat_vald = [];
                    $psubcat_vald = [];
                    $pcat_vald_tags = [];
                    @endphp
                    @foreach ($data['pData'] as $pcat_val)
                    @php
                    if(isset($pcat_val->cat_id)){
                    $pcat_vald[] = $pcat_val->cat_id;
                    }

                    if(isset($pcat_val->sub_cat_id)){
                    $psubcat_vald[] = $pcat_val->sub_cat_id;
                    }
                    $pcat_vald_tags[] = $pcat_val->tags;
                    @endphp
                    @endforeach
                    @php
                    $pcat_tag = implode (",",$pcat_vald_tags);
                    $pcat_tag_pro = explode (",",$pcat_tag);

                    //dd($pcat_tag_pro);
                    @endphp

                    <h6>{{__("message.filters_category_subtitle")}} </h6>

                    <?php //if(in_array("shop", $currenturl)){ ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">

                                <div id="accordion" class="tree-stuc accordion">
                                    <?php 
                                                
                                    $i=1; 
                                    foreach($data2['product_parent_category'] as $parentCategory){ 
                                        $get_child_category = Helper::getProductchildCategory($parentCategory->id); 
                                    ?>
                                    <div class="card">
                                        @if (in_array($parentCategory->id, $pcat_vald))
                                        <div class="card-header area" id="heading-1">
                                            <h5 class="mb-0">
                                                <span class="container_check">
                                                    <label class="">
                                            <input type="checkbox" name="category[]" class="categories sk_fade_only" value="{{ $parentCategory->id }}" onclick="filter(this)" {{ in_array($parentCategory->id, $categoryUrls) ? ' checked' :'' }} >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </span>
                                                @if(count($get_child_category) > 0)
                                                <a role="button" data-toggle="collapse"
                                                    href="#collapse_shop-{{ $parentCategory->id }}"
                                                    aria-expanded="false" aria-controls="collapse-1">{{
                                                    $parentCategory->name }}
                                                </a>
                                                @else
                                                <span id="LparentCategory">
                                                    {{ $parentCategory->name }}
                                                </span>
                                                @endif
                                            </h5>
                                        </div>
                                        @endif

                                        <?php 
                                            $subcatids = array();
                                            foreach($get_child_category as $childCategory){
                                                $subcatids[] = $childCategory->id;
                                            }
                                            $tagsid = array();
                                            $get_listing_tags = Helper::getAllProductTagsListingArray($subcatids); 
                                            foreach($get_listing_tags as $tagsids){
                                                $tagsid[] = $tagsids->id;
                                            }
                                        ?>
                                        <div id="collapse_shop-{{ $parentCategory->id }}"
                                            class="@if(in_array($parentCategory->id, $categoryUrls) || array_intersect($subcatids, $subcategoryUrls) || array_intersect($tagsid, $producttagsUrls)) @elseif(in_array($parentCategory->id, $categoryUrls)) @else collapse @endif"
                                            data-parent="#accordion" aria-labelledby="heading-1">

                                            <div class="card-body">
                                                <?php 
                                                            $j='a'; 
                                                            foreach($get_child_category as $childCategory){
                                                            $get_listing_tags = Helper::getAllProductTagsListing($childCategory->id); 
                                                            //echo "<pre>"; print_r(count($get_listing_tags));
                                                            ?>
                                                <div id="accordion-{{ $childCategory->id }}">

                                                    <div class="card">
                                                        <div class="card-header equipamento" id="heading-1-1">


                                                            

                                                            @if (in_array($childCategory->id, $psubcat_vald))
                                                            <span class="mb-0">
                                                                <label class="container_check">
                                                                   
                                                                    <small></small>
                                                                    <!-- Here will be the number of the total listing -->
                                                                    <input type="checkbox" name="category[]" class="subcategories sk_fade_only"
                                                                        value="{{ $childCategory->id }}"
                                                                        onclick="filter(this)" {{
                                                                        in_array($childCategory->id, $subcategoryUrls) ?
                                                                    ' checked' : '' }}>
                                                                    <span class="checkmark"></span>
                                                                </label>

                                                                @if(count($get_listing_tags) > 0)
                                                                <a class="collapsed" role="button"
                                                                    data-toggle="collapse"
                                                                    href="#collapse_shop-1-{{ $childCategory->id }}"
                                                                    aria-expanded="false" aria-controls="collapse-1-1">
                                                                    {{ $childCategory->name }}
                                                                </a>
                                                                @else
                                                                <span>
                                                                    {{ $childCategory->name }}
                                                                </span>
                                                                @endif
                                                            </span>
                                                            @endif

                                                        </div>
                                                        <?php if(count($get_listing_tags)>0){ 
                                                                        
                                                                $subcattagsid = array();
                                                                foreach($get_listing_tags as $tagsidsub){
                                                                    $subcattagsid[] = $tagsidsub->id;
                                                                }
                                                        ?>
                                                        <div id="collapse_shop-1-{{ $childCategory->id }}"
                                                            class="@if(in_array($childCategory->id, $subcategoryUrls) || array_intersect($subcattagsid, $producttagsUrls)) @elseif(in_array($childCategory->id, $subcategoryUrls)) @else collapse @endif"
                                                            data-parent="#accordion-{{ $childCategory->id }}"
                                                            aria-labelledby="heading-1-1">
                                                            <div class="card-body filter-scroll">
                                                                <div id="accordion-1-1">
                                                                    <div class="card">
                                                                        <div class="card-header ponto"
                                                                            id="heading-1-1-1">
                                                                            <h5 class="mb-0">
                                                                                <span>
                                                                                    <?php
                                                                                    foreach($get_listing_tags as $tagsname){
                                                                                        
                                                                                        if (in_array($tagsname->id, $pcat_tag_pro)){
                                                                                        
                                                                                        if($tagsname->child_cat_id == $childCategory->id){
                                                                                        ?>
                                                                                    <label class="container_check"> {{
                                                                                        ($tagsname->tags!='')?$tagsname->tags:$tagsname->tags
                                                                                        }} <small></small>
                                                                                        <!-- Here will be the number of the total listing -->
                                                                                        <input type="checkbox"
                                                                                            name="category[]" class="protags sk_fade_only"
                                                                                            value="{{ $tagsname->id }}"
                                                                                            onclick="filter(this)" {{
                                                                                            in_array($tagsname->id,
                                                                                        $producttagsUrls) ? ' checked' :
                                                                                        '' }}>
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                    {{-- <a
                                                                                        href="{{ route('product.details.tag',$tagsname->id) }}"
                                                                                        style="color: #000;">
                                                                                        {{ $tagsname->tags }},
                                                                                    </a> --}}
                                                                                    <?php } } } ?>
                                                                                </span>

                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php $j++; } ?>
                                            </div>

                                        </div>
                                    </div>
                                    <?php $i++; } ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php //} ?>



                </div>
                <?php //} ?>
                <?php //if(in_array("category-product", $currenturl) || in_array("listings-product", $currenturl) || in_array("tags-product", $currenturl)){ ?>
                <input type="hidden" id="urlvalue-cat-product" value="category-product" />
                <input type="hidden" id="urlvalue-list-product" value="listings-product" />
                <input type="hidden" id="urlvalue-tags-product" value="tags-product" />
                <?php //} ?>
                <?php //if(in_array("category-product", $currenturl) || in_array("shop", $currenturl) || in_array("tags-product", $currenturl)){ ?>
                <!-- Price range filter -->
               
                {{-- <div class="filter_type">
                    <h6>{{__("message.price_limit_filter")}}</h6>

                    <div class="distance"> {{__("message.price_within_filter")}} <span></span> {{ $currencycode }}</div>
                    <input type="range" class="price-range" min="0" max="{{ $maxPrice }}" step="10"
                        value="{{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:0) }}"
                        data-orientation="horizontal" onchange="filter(this)">
                </div> --}}

                {{-- <div class="filter_type widget widget_price_filter">
                    <h6>{{__("message.price_limit_filter")}}</h6>
                    <div class="price_slider_wrapper">
                        <div class="price_label"><span class="from2">{{ $currencycode }} {{ $data['minprice']->regular_price??0 }}</span> — <span class="to3">{{ $currencycode }} {{ $data['maxprice']->regular_price??0 }}</span></div>
                        <section class="range-slider">
                            <span class="rangeValues"></span>
                            <input onchange="filter(this)" class="price-range-min"  value="{{ trim(isset($configData['minPrice'])?$configData['minPrice']:1) }}" min="{{ $data['minprice']->regular_price??0 }}" max="{{ $data['maxprice']->regular_price }}" step="10" type="range" >
                            <input onchange="filter(this)" class="price-range" value="{{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:$data['maxprice']->regular_price) }}"  max="{{ $data['maxprice']->regular_price??0 }}"  type="range">
                        </section>
                    </div>
                </div> --}}

                <div class="filter_type widget widget_price_filter">
                    <h6>{{__("message.price_limit_filter")}}</h6>
                    <div class="price_slider_wrapper">
                        <div class="price_label">
                           <b style="font-size: 12px;"> <span class="from2">{{ $currencycode }} {{ $data['minprice']??0 }}</span> — 
                            <span class="to3">{{ $currencycode }} {{ $data['maxprice']??0 }}</span></b>
                        </div>
                        <section class="range-slider">
                            <span class="rangeValues"> 
                               
                                {{ trim(isset($configData['minPrice'])?$configData['minPrice']:$data['minprice']) }}
                                — 
                                {{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:$data['maxprice']) }}</span>
                            <input onchange="filter(this)" class="price-range-min" value="{{ trim(isset($configData['minPrice'])?$configData['minPrice']:1) }}" min="{{ $data['minprice']??0 }}" max="{{ $data['maxprice'] }}" step="10" type="range" >
                            <input onchange="filter(this)" class="price-range" value="{{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:$data['maxprice']) }}"  max="{{ $data['maxprice']??0 }}" type="range">
                        </section>
                    </div>
                </div>

                <div class="widget widget_categories filter_type">
                    <h6>Rating </h6>
                    <ul>
                        @foreach (range(5, 2) as $review)
                            <li>
                                <input type="radio"
                                       @if (isset($_GET['review']) && $_GET['review'] == $review) checked @endif
                                       class="review_filter"
                                       id="review_filter"
                                       onclick="filter(this)"
                                       value="{{ $review }}"
                                       name="review_filter">
                                {{ $review }} <i class="fa fa-star"></i>
                                @if ($review != 5) & above @endif
                            </li>
                        @endforeach
                    </ul>
                    @if (isset($_GET['review']) || isset($_GET['price']) || isset($_GET['cat']) ) 
                    <a class="active rounded-5" href="{{ url('shop') }}" id="home-tab2">See all products</a>
                    @endif
                    
                </div>
                

                <?php //} ?>
            </div>
            <!--/collapse -->
        </form>
        <!-- filter form ends -->
    </div>
    <!--/filters col-->
</aside>
<script type="text/javascript">

    function filter(elem) {
        var valueurl = $('#urlvalue').val();
        var valueurlCatPro = $('#urlvalue-cat-product').val();
        var valueurlListPro = $('#urlvalue-list-product').val();
        var valueurlTagsPro = $('#urlvalue-tags-product').val();

        if(valueurl=="shop"){
            var urlPrefix 	= 'shop?';
        }
        if(valueurl=="listings"){
            var urlPrefix 	= 'listings?';
        }
        if(valueurl=="search"){
            var urlPrefix 	= 'search?';
        }
        if(valueurlCatPro=="category-product"){
            var urlPrefix 	= valueurl+'?';
        }
        if(valueurlListPro=="listings-product"){
            var urlPrefix 	= valueurl+'?';
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
			(slectedCategories === "") ? slectedCategories = $(this).attr('value') : slectedCategories = slectedCategories + "&cat=" + $(this).attr('value');
		});

        $('.subcategories:checked').each(function() {
            (slectedsubCategories === "") ? slectedsubCategories = $(this).attr('value'): slectedsubCategories = slectedsubCategories + "&subcat=" + $(this).attr('value');
        });

        $('.protags:checked').each(function() {
            (slectedProductTags === "") ? slectedProductTags = $(this).attr('value'): slectedProductTags = slectedProductTags + "&tags=" + $(this).attr('value');
        });

		$('.amenities:checked').each(function() {
			(selectedAmenities === "") ? selectedAmenities = $(this).attr('value') : selectedAmenities = selectedAmenities + "&amenity=" + $(this).attr('value');
		});

        $('.categories,.subcategories,.protags,.amenities').click(function() {
            $('.review_filter').prop('checked', false);
        });
        
        $(document).on('change', '.price-range-min, .price-range', function() {
            $('.review_filter').prop('checked', false);
        });


        selectedPriceMinRange = $('.price-range-min').val();
        selectedPriceRange = $('.price-range').val();

        selectedReview = $('.review_filter:checked').val();

        console.log('selectedReview - ',selectedReview);

        // 
       
    setTimeout(function() {
        if ($('.review_filter').is(':checked')) {
                urlSuffix = "review=" + selectedReview;
                window.location.replace(urlPrefix + urlSuffix);
        }else{
            if(valueurl=="search"){
                if(slectedCategories!=""){
                    urlSuffix = "search="+$('#searchtext_search').val()+"&cat="+slectedCategories+"&city_name="+$('#cityname_search').val();
                    window.location.replace(urlPrefix+urlSuffix);
                }
            }else{

                console.log('ddd - ');

                if(slectedCategories!="" && selectedAmenities!=""){
                    urlSuffix = "cat="+slectedCategories+"&amenity="+selectedAmenities;
                    window.location.replace(urlPrefix+urlSuffix);
                }else if (slectedCategories != "" && slectedsubCategories != "" && slectedProductTags == "" && selectedPriceRange==0) {
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
                }else if((slectedCategories!="" && slectedsubCategories=="" && slectedProductTags==""  && (selectedPriceRange!=0 && selectedPriceRange!==undefined)) && selectedAmenities==""){
                    
                    urlSuffix = "cat="+slectedCategories+"&price="+selectedPriceMinRange+"-"+selectedPriceRange;
                    window.location.replace(urlPrefix+urlSuffix);
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
                }
                else if(slectedCategories!=""){
                    urlSuffix = "cat="+slectedCategories;
                    window.location.replace(urlPrefix+urlSuffix);
                }else if (slectedsubCategories != "") {
                    urlSuffix = "subcat=" + slectedsubCategories;
                    window.location.replace(urlPrefix + urlSuffix);
                }else if (slectedProductTags != "") {
                    urlSuffix = "tags=" + slectedProductTags;
                    window.location.replace(urlPrefix + urlSuffix);
                }else if(selectedAmenities!=""){
                    urlSuffix = "amenity="+selectedAmenities;
                    window.location.replace(urlPrefix+urlSuffix);
                }else if(selectedPriceRange!=0){
                    urlSuffix = "price="+selectedPriceMinRange+"-"+selectedPriceRange;
                    window.location.replace(urlPrefix+urlSuffix);
                }else{
                    window.location.replace(urlPrefix);
                }
            }
        }
    }, 0);

	}
</script>
<style>
    .collapse:not(.show) {
        display: none !important;
    }

    .down-icon {
        font-size: 16px;
        float: right;
        margin-right: 0px;
        margin-top: -25px;
    }
    [type="radio"]:checked, [type="radio"]:not(:checked) {
        position: relative !important;
        left: 0px !important;
    }

.range-slider input[type="range"] {
    -webkit-appearance: none;
    width: 100%;
    height: 5px;
    background: #ddd;
    border-radius: 5px;
    outline: none;
    padding: 0;
    margin: 0;
}

.rangeslider__fill {
    margin: -4px !important;
    opacity: 0;
}
.rangeslider {
    position: absolute;
}
.range-slider input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
    box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.3);
}

.range-slider input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
    box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.3);
}
[type="radio"]:not(:checked) {
    position: relative;
    left: 0px;
}
.widget_categories li {
    margin-bottom: 10px;
}
.filter_type h6 {
    font-size: 16px;
}
.range-slider .rangeValues {
    display: block;
    margin-top: 13px;
    margin-bottom: 15px;
}
.widget {
    margin-bottom: 50px;
}

.rangeslider__handle {
    position: relative;
    z-index: 999;
}

</style>