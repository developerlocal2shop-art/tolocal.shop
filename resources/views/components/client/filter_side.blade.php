<?php 
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
    $currencySymbol = session()->get('currency_code')['currsymbol'];
    $currencycode = session()->get('currency_code')['currcode'];
}else{
    $currencySymbol = $defaultCurrency[0]->symbol;
    $currencycode = $defaultCurrency[0]->code;
}
$currentfullurlfilter                   = url()->full();
$currenturlafterfirstval                = explode("?",$currentfullurlfilter);
$currenturlafterfirstvalvariable        = explode("=",$currenturlafterfirstval[1]??'');

$filterfirstvalue = $currenturlafterfirstvalvariable[0];
$countvalueofparam = count($currenturlafterfirstval);
$currenturl = url()->current();
$currenturl = explode("/",$currenturl);   

$arrendvalue = end($currenturl);

$maxPrice = "1000000";
if(!empty($data['maxprice'])){
    $maxPrice = $data['maxprice']->regular_price;
}
$params = ['cat' => [], 'subcat' => [], 'tags' => [], 'amenity' => [], 'trademark' => [], 'price' => []];
$categoryUrls = []; $subcategoryUrls = []; $listingtagsUrls = []; $amenityUrls = [];$trademarkUrls = [];
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
    $listingtagsUrls = $params['tags'];
}
if (!empty($params['amenity'])) {
    $amenityUrls = $params['amenity'];
}
if (!empty($params['trademark'])) {
    $trademarkUrls = $params['trademark'];
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
        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">
            {{__("message.filters_title")}} 
        </a>
        <!-- Filter form starts-->
        <form class="filter-form" action="#" method="get" enctype="multipart/form-data">
            <div class="collapse show" id="collapseFilters">
                <input type="hidden" id="urlvalue" value="{{ $arrendvalue }}" />
                <input type="hidden" id="searchtext_search" value="{{ request()->search??'' }}" />
                <input type="hidden" id="cityname_search" value="{{ request()->city_name??'' }}" />

                <input type="hidden" id="filter_cat" value="{{ request()->cat??'' }}" />
                <input type="hidden" id="filter_subcat" value="{{ request()->subcat??'' }}" />
                <input type="hidden" id="filter_tags" value="{{ request()->tags??'' }}" />
                <input type="hidden" id="filter_amenity" value="{{ request()->amenity??'' }}" />
                <input type="hidden" id="filter_trademark" value="{{ request()->trademark??'' }}" />
                <input type="hidden" id="filter_firstvalue" value="{{ $filterfirstvalue??'' }}" />
                <input type="hidden" id="count_valueofparam" value="{{ $countvalueofparam??'' }}" />

                <?php if((in_array("category", $currenturl))){ 
                    $categorySelectedId = Helper::categorySelectedId($arrendvalue); 
                ?>
                <input type="hidden" id="urlvalue-cat-directory" value="category" />
                <input type="hidden" id="cat-directoryendvalue" value="{{$arrendvalue}}" />
                <div class="filter_type checkbox_alignment">
                    <hr>

                    @if(isset($_GET['city_name']))
                    <a class="filter_nearby" href="{{ url('directories-map') }}?city={{ $_GET['city_name'] }}">
                        Find near by store on map
                    </a>
                    @else
                    <a class="filter_nearby" href="{{ url('directories-map') }}">
                        Find near by store on map
                    </a>
                    @endif

                    @php

                    $dsubcat_vald = [];
                    $dcat_vald_tags = [];
                    @endphp
                    @foreach ($data['dirData'] as $dcat_val)
                    @php
                    if(isset($dcat_val->dir_cat_id)){
                    $dsubcat_vald[] = $dcat_val->dir_cat_id;
                    }
                    $dcat_vald_tags[] = $dcat_val->tags_id;
                    @endphp
                    @endforeach
                    @php
                    $dcat_tag = implode (",",$dcat_vald_tags);
                    $dcat_tag_pro = explode (",",$dcat_tag);
                    @endphp

                    <h6>{{__("message.filters_category_subtitle")}} </h6>
                    <ul class="panel-group" id="accordion">
                        <?php 
                            foreach($data['categories'] as $index =>$parentcat){ 
                                if($parentcat->id == $categorySelectedId->id){    
                        ?>
                        <li class="" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $index }}" aria-expanded="true">
                            <div class="parentcat_filter">
                                <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i>
                                {{ ($parentcat->title!='')?$parentcat->title:$parentcat->name }} <small></small>
                            </div>
                            <?php 
                            foreach($data['subcategories'] as $subcaticon){
                            if($subcaticon->parent_id == $parentcat->id){ ?>
                            <i class="fa fa-caret-down down-icon" aria-hidden="true"></i>
                            <?php 
                            } }  
                            ?>
                        </li>
                        <?php
                            foreach($data['subcategories'] as $subcat){    
                            if($subcat->parent_id == $parentcat->id){
                        ?>
                        @if(in_array($parentcat->id, $categoryUrls))
                        @php
                        $collapse = '';
                        @endphp
                        @elseif(in_array($subcat->id, $subcategoryUrls))
                        @php
                        $collapse = '';
                        @endphp
                        @else
                        @php
                        $collapse = 'collapse';
                        @endphp
                        @endif
                        <li class="ml-3 panel-collapse {{ $collapse }}" id="collapse-{{ $index }}">
                            <label class="container_check"> {{ ($subcat->title!='')?$subcat->title:$subcat->name }}
                                <small></small> <!-- Here will be the number of the total listing -->
                                <input type="checkbox" name="category[]" class="subcategories sk_fade_only" value="{{ $subcat->id }}"
                                    onclick="filter(this)" {{ in_array($subcat->id, $subcategoryUrls) ? ' checked' : ''
                                }}>
                                <span class="checkmark"></span>
                            </label>
                        </li>
                    <?php 
                    $tagcounter = 1;
                        foreach($data['listingalltags'] as $dircattags){
                            if($dircattags->child_cat_id == $subcat->id){
                                if(in_array($dircattags->id, $dcat_tag_pro)){
                                    $tagcounter++;
                                    if($tagcounter > 1){
                                       $hiddencls = "hidden-tags-".$subcat->id." hidden";
                                    }else{
                                       $hiddencls = "";
                                    }
                        ?>
                        <div class="{{ $hiddencls }}">
                            <li class="ml-3 panel-collapse @if(in_array($parentcat->id, $categoryUrls)) @elseif(in_array($subcat->id, $subcategoryUrls)) @elseif(in_array($dircattags->id, $listingtagsUrls)) @else collapse @endif"
                                id="collapse-{{ $index }}" style="padding-left: 15px;">
                                <label class="container_check"> {{
                                    ($dircattags->tags!='')?$dircattags->tags:$dircattags->tags }} <small></small>
                                    <input type="checkbox" name="category[]" class="protags sk_fade_only"
                                        value="{{ $dircattags->id }}" onclick="filter(this)" {{
                                        in_array($dircattags->id, $listingtagsUrls) ? ' checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                        </div>

                        <?php 
                        }  } } 
                        $tagshidden = "hidden-tags-".$subcat->id;
                        ?>
                        @if($tagcounter > 1)
                        <li class="ml-3 panel-collapse @if(in_array($parentcat->id, $categoryUrls)) @elseif(in_array($subcat->id, $subcategoryUrls)) @elseif(in_array($dircattags->id, $listingtagsUrls)) @else collapse @endif"
                            id="collapse-{{ $index }}" style="padding-left: 15px;">
                            <a href="javascript::" id="tags-toggle-btn"
                                onclick="showToggle(this, '<?php echo $tagshidden ?>')">{{__("message.show_more")}}</a>
                        </li>
                        @endif
                        <?php } } } } ?>
                    </ul>

                </div>
                <?php } ?>

                <?php if((!in_array("category-product", $currenturl)) && (!in_array("listings-product", $currenturl)) && (!in_array("category", $currenturl))){ //echo "test second section"; ?>
                <div class="filter_type checkbox_alignment acc_shop_filter">
                    <hr>
                    @if(isset($_GET['city_name']))
                    <a class="filter_nearby" target="_blank"
                        href="{{ url('directories-map') }}?city={{ $_GET['city_name'] }}">
                        Find near by store on map
                    </a>
                    @else

                        <?php
                            $currentUrl = trim(request()->getRequestUri(), '/');
                        ?>
                        @if($currentUrl == 'listings')
                        <a class="filter_nearby" target="_blank" href="{{ url('directories-map') }}">
                            Find near by store on map
                        </a>
                        @else
                        @if(request('search_string'))
                        <a class="filter_nearby" target="_blank"
                            href="{{ url('directories-map') }}?search_string={{ request('search_string') }}&cat=all&filters=&timeSelected=">
                            Find near by store on map
                        </a>
                        @else
                        <a class="filter_nearby" target="_blank"
                            href="{{ url('directories-map') }}?cat={{ request('cat') }}&subcat={{ request('subcat') }}&tags={{ request('tags') }}&amenity={{ request('amenity') }}&trademark={{ request('trademark') }}">
                            Find near by store on map
                        </a>
                        @endif
                    @endif
                    @endif

                    @php
                    $dircat_vald = [];
                    $dsubcat_vald = [];
                    $dcat_vald_tags = [];
                    $language_id = Helper::getselectedLanguage();
                    @endphp

                    @foreach ($data['dirData'] as $dcat_val)
                    @php
                    
                    if(isset($dcat_val->dir_cat_id)){
                    $dircat_vald[] = $dcat_val->dir_cat_id;
                    }
                    if(isset($dcat_val->dir_sub_cat_id)){
                    $dsubcat_vald[] = $dcat_val->dir_sub_cat_id;
                    }

                    $getDirectoryTags = Helper::getDirectoryTagsDirId($dcat_val->id,$dcat_val->dir_cat_id,$language_id);
                    if($getDirectoryTags){
                        foreach($getDirectoryTags as $tagsrow){
                              $dcat_vald_tags[] = $tagsrow->tag_id??'';
                        }
                    }

                    @endphp
                    @endforeach
                    @php
                    //dd($dcat_vald_tags);
                    $dcat_tag = implode (",",$dcat_vald_tags);
                    $dcat_tag_pro = explode (",",$dcat_tag);
                    $all_related_dir_category = $dircat_vald;
                    $all_related_dir_sub_category = $dsubcat_vald;
                    @endphp
                    <h6>{{__("message.filters_category_subtitle")}} </h6>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">

                                <div id="accordion" class="tree-stuc accordion">
                                <?php 
                                    $i=0; 
                                    foreach($data2['parent_category'] as $parentCategory){ 
                                        $get_child_category = Helper::getchildCategory($parentCategory->id); 
                                    ?>
                                    <div class="card ">
                                        @if(isset($_GET['city_name']))

                                        <div class="card-header area" id="heading-1">

                                            <h5 class="mb-0">
                                                @if(in_array($parentCategory->id, $all_related_dir_category))
                                                        <span class="container_check parentCategory">
                                                            <label class="">
                                                                <input type="checkbox" name="category[]" class="categories sk_fade_only"
                                                                    value="{{ $parentCategory->id }}" onclick="filter(this)" {{ in_array($parentCategory->id, $categoryUrls) ? ' checked' : '' }} >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </span>
                                                    @if(count($get_child_category) > 0)
                                                        <a role="button" data-toggle="collapse" href="#collapse_shop-{{ $parentCategory->id }}" aria-expanded="false" aria-controls="collapse-1">
                                                            {{ $parentCategory->name }}  
                                                        </a>
                                                    @else
                                                        <span id="LparentCategory">
                                                            {{ $parentCategory->name }} 
                                                        </span>
                                                    @endif
                                                @endif
                                            </h5>
                                        </div>

                                        @else

                                        <div class="card-header area" id="heading-1">
                                            <h5 class="mb-0 parentCategory">

                                                <span class="container_check">
                                                    <label class="">
                                                        <input type="checkbox" name="category[]" class="categories sk_fade_only" value="{{ $parentCategory->id }}" onclick="filter(this)" {{ in_array($parentCategory->id, $categoryUrls) ? ' checked' : '' }} >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </span>
                                                @if(count($get_child_category) > 0)
                                                    {{-- @if(in_array($childCategory->id, $all_related_dir_sub_category)) --}}
                                                        <a role="button" data-toggle="collapse"  href="#collapse_shop-{{ $parentCategory->id }}" aria-expanded="false" aria-controls="collapse-1">
                                                            {{ $parentCategory->name }} 
                                                        </a>
                                                    {{-- @else
                                                        <span id="LparentCategory">
                                                            {{ $parentCategory->name }} 
                                                        </span>
                                                    @endif --}}
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
                                            $get_listing_tags = Helper::getListingTagsArray($subcatids); 
                                            foreach($get_listing_tags as $tagsids){
                                                $tagsid[] = $tagsids->id;
                                            }
                                        ?>

                                        <div id="collapse_shop-{{ $parentCategory->id }}" class="@if(in_array($parentCategory->id, $categoryUrls) || array_intersect($subcatids, $subcategoryUrls) || array_intersect($tagsid, $listingtagsUrls))@else collapse @endif" data-parent="#accordion" aria-labelledby="heading-1">

                                            <div class="card-body">
                                                <?php 
                                                    $j='a'; 
                                                    foreach($get_child_category as $childCategory){
                                                    $get_listing_tags = Helper::getListingTags($childCategory->id); 
                                                ?>
                                                <div id="accordion-{{ $childCategory->id }}" class="parentCategory">
                                                    <div class="card">
                                                        
                                                        @if(in_array($childCategory->id, $all_related_dir_sub_category))
                                                        <div class="card-header equipamento childCategory_div" id="heading-1-1">

                                                            <span @foreach ($get_listing_tags as $get_listing_tags_val) @if(in_array($get_listing_tags_val->id, $dcat_vald_tags)) class="mb-0" @endif @endforeach >
                                                                <label class="container_check">
                                                                    <small></small>
                                                                    <input type="checkbox" name="category[]" class="subcategories sk_fade_only" value="{{ $childCategory->id }}" onclick="filter(this)" {{ in_array($childCategory->id, $subcategoryUrls) ?' checked' : '' }}>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                @if(count($get_listing_tags) > 0)
                                                                <a class="collapsed childCategory" role="button" data-toggle="collapse" href="#collapse_shop-1-{{ $childCategory->id }}"
                                                                    aria-expanded="false" aria-controls="collapse-1-1">
                                                                    {{ $childCategory->name }}
                                                                </a>
                                                                @else
                                                                <a class="collapsed childCategory_else"> {{ $childCategory->name }} </a>
                                                                @endif
                                                            </span>

                                                        </div>
                                                        @endif

                                                        <?php 
                                                            if(count($get_listing_tags)>0){
                                                                $subcattagsid = array();
                                                                foreach($get_listing_tags as $tagsidsub){
                                                                    $subcattagsid[] = $tagsidsub->id;
                                                            }
                                                        ?>
                                                        <div id="collapse_shop-1-{{ $childCategory->id }}" class="@if(in_array($childCategory->id, $subcategoryUrls)||array_intersect($subcattagsid, $listingtagsUrls)) @else collapse @endif" data-parent="#accordion-{{ $childCategory->id }}" aria-labelledby="heading-1-1">
                                                            <div class="card-body filter-scroll">
                                                                <div id="accordion-1-1">
                                                                    <div class="card">
                                                                        <div class="card-header ponto dcat_tag_pro" id="heading-1-1-1">
                                                                            <h5 class="mb-0">
                                                                                
                                                                                <?php
                                                                                    foreach($get_listing_tags as $tagsname){ 
                                                                                        if($tagsname->child_cat_id == $childCategory->id){
                                                                                ?>
                                                                                    @if(in_array($tagsname->id, $dcat_tag_pro))
                                                                                    <label class="container_check"> {{ ($tagsname->tags!='')?$tagsname->tags:$tagsname->tags }} <small></small>
                                                                                        <input type="checkbox" name="category[]" class="protags sk_fade_only" value="{{ $tagsname->id }}" onclick="filter(this)" {{ in_array($tagsname->id,$listingtagsUrls) ? ' checked' :'' }}>
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
                                                                                    @endif

                                                                                <?php } } ?>
                                                                                
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

                </div>
                <?php } ?>

            <?php if(in_array("category-product", $currenturl) || in_array("listings-product", $currenturl) || in_array("tags", $currenturl) || in_array("trademark", $currenturl)){ //echo "test third section";?>
            <input type="hidden" id="urlvalue-cat-product" value="category-product" />
            <input type="hidden" id="urlvalue-list-product" value="listings-product" />
            <?php } ?>

            <?php 
            /* --------------------------------------------------------  PRICE RANGE FILTER ON SIDE BAR  --------------------------------------------------------- */
            if(in_array("shop", $currenturl) || in_array("category-product", $currenturl) || in_array("listings-product", $currenturl)){ //echo "test fourth section";?>
                <div class="filter_type">
                    <h6>{{__("message.price_limit_filter")}}</h6>
                    <div class="distance notranslate"> {{__("message.price_within_filter")}} <span></span> {{ $currencycode }}</div>
                    <input type="range" class="price-range" min="0" max="{{ $maxPrice }}" step="100" value="{{ trim(isset($configData['maxPrice'])?$configData['maxPrice']:0) }}" data-orientation="horizontal" onchange="filter(this)">
                </div>
            <?php } ?>

            <hr>
            <div class="filter-scroll">
            <?php 
            /* --------------------------------------------------------  AMINITIES FILTER ON SIDE BAR  --------------------------------------------------------- */
            if((in_array("listings", $currenturl) || in_array("tags", $currenturl) || in_array("trademark", $currenturl) || in_array("category", $currenturl)) && (!isset($_GET['city_name'])) ){ //echo "test fifth section";?>
                <div class="filter_type">
                    <h6>{{__("message.filters_amenities_subtitle")}}</h6>
                    <ul>
                        @foreach($data['amenity'] as $rowamenity)
                        <?php
                        $hiddencls = $rowamenity->id > 5 ? 'hidden-amenities hidden' : '';
                        ?>
                        <div class="{{ $hiddencls }}">
                            <li>
                                <label class="container_check"> 
                                    <i class="{{ $rowamenity->icon_class }}" aria-hidden="true"></i> {{ $rowamenity->title }}
                                    <input type="checkbox" class="amenities sk_fade_only" name="amenity[]"
                                        value="{{ $rowamenity->id }}" onclick="filter(this)" {{
                                        in_array($rowamenity->id, $amenityUrls) ? ' checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                        </div>
                        @endforeach
                    </ul>
                    <a href="javascript::" id="amenity-toggle-btn"
                        onclick="showToggleAm(this, 'hidden-amenities')">{{__("message.show_more")}} </a>
                </div>
            <?php } ?>
                
            <?php
            /* --------------------------------------------------------  TRADEMARK FILTER ON SIDE BAR   ------------------------------------------------------- */
            if((in_array("listings", $currenturl) || in_array("tags", $currenturl) || in_array("trademark", $currenturl) || in_array("category", $currenturl)) && (!isset($_GET['city_name'])) ){ //echo "test sixth section";?>
                <div class="filter_type">
                    <h6>Trademarks</h6>
                    <ul class="">
                        @foreach($data['trademark'] as $rowtrademark)
                        <?php
                        $hiddencls = $rowtrademark->id > 11 ? 'hidden-trademarks hidden' : '';
                        ?>
                        <div class="{{ $hiddencls }}">
                            <li>
                                <label class="container_check notranslate"> 
                                <img src="{{ env('IMAGE_PATH').'files/trademark/'.$rowtrademark->name_thumbnail }}" class="img-fluid" alt="" width="50" height="40" style="border: 1px solid #ccc; margin: 10px 2px; padding: 3px;">
                                {{ $rowtrademark->name }}
                                    <input type="checkbox" class="trademark sk_fade_only" name="trademark[]"
                                        value="{{ $rowtrademark->id }}" onclick="filter(this)" {{
                                        in_array($rowtrademark->id, $trademarkUrls) ? ' checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                        </div>
                        @endforeach
                    </ul>
                    <a href="javascript::" id="trademark-toggle-btn"
                        onclick="showToggle(this, 'hidden-trademarks')">{{__("message.show_more")}} </a>
                </div>
                <?php } ?>
            </div>
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
        var citynameSearch = $('#cityname_search').val();
        var valueurlCatPro = $('#urlvalue-cat-product').val();
        var valueurlListPro = $('#urlvalue-list-product').val();
        var valueurlCatDirectory = $('#urlvalue-cat-directory').val();
        var CatDirectoryEndValue = $('#cat-directoryendvalue').val();
        var filter_cat = $('#filter_cat').val();
        var filter_subcat = $('#filter_subcat').val();
        var filter_tags = $('#filter_tags').val();
        var filter_amenity = $('#filter_amenity').val();
        var filter_trademark = $('#filter_trademark').val();
        var filter_firstvalue = $('#filter_firstvalue').val();
        var count_value_param = $('#count_valueofparam').val();
        var fullurl = "";

        if (valueurl == "shop") {
            var urlPrefix = 'shop?';
        }
        if (valueurl == "listings") {
            var urlPrefix = 'listings?';
        }
        if (valueurl == "search_string") {
            var urlPrefix = 'search_string?';
        }
        if (valueurlCatPro == "category-product") {
            var urlPrefix = valueurl + '?';
        }
        if (valueurlListPro == "listings-product") {
            var urlPrefix = valueurl + '?';
        }
        if (valueurlCatDirectory == "category") {
            var urlPrefix = CatDirectoryEndValue + '?';
        }
        var urlSuffix = "";
        var urlSuffix1 = "";
        //var slectedCategories = "all";
        var slectedCategories = "";
        var slectedsubCategories = "";
        var selectedAmenities = "";
        var selectedTrademarks = "";
        var selectedCity = "";
        var selectedVideoAvailability = 0;
        var selectedPriceRange = 0;
        var selectedOpeningStatus = "all";
        var slectedDirectoryTags = "";

        $('.categories:checked').each(function() {
            (slectedCategories === "") ? slectedCategories = $(this).attr('value'): slectedCategories = slectedCategories + "&cat=" + $(this).attr('value');
        });
        $('.subcategories:checked').each(function() {
            (slectedsubCategories === "") ? slectedsubCategories = $(this).attr('value'): slectedsubCategories = slectedsubCategories + "&subcat=" + $(this).attr('value');
        });
        $('.amenities:checked').each(function() {
            (selectedAmenities === "") ? selectedAmenities = $(this).attr('value'): selectedAmenities = selectedAmenities + "&amenity=" + $(this).attr('value');
        });
        $('.trademark:checked').each(function() {
            (selectedTrademarks === "") ? selectedTrademarks = $(this).attr('value'): selectedTrademarks = selectedTrademarks + "&trademark=" + $(this).attr('value');
        });
        $('.protags:checked').each(function() {
            (slectedDirectoryTags === "") ? slectedDirectoryTags = $(this).attr('value'): slectedDirectoryTags = slectedDirectoryTags + "&tags=" + $(this).attr('value');
        });

        selectedPriceMinRange = 0;
        selectedPriceRange = $('.price-range').val();
        if (valueurl == "listings" && citynameSearch != "") {
            
            urlSuffix = "search_string="+ $('#searchtext_search').val() +"&cat=" + slectedCategories + "&city_name=" + $('#cityname_search').val();
            if(slectedCategories != ""){
                if (slectedCategories!="" && filter_firstvalue!="cat" && count_value_param > 1) {
                    urlSuffix1 += "&cat=" + slectedCategories;
                }
            }

            if(slectedsubCategories != ""){
                if (slectedsubCategories!="" && filter_firstvalue!="subcat" && count_value_param > 1) {
                    urlSuffix1 += "&subcat=" + slectedsubCategories;
                }
            }

            if(slectedDirectoryTags != ""){
                if (slectedDirectoryTags!="" && filter_firstvalue!="tags" && count_value_param > 1) {
                    urlSuffix1 += "&tags=" + slectedDirectoryTags;
                }
            }
            
            fullurl = urlPrefix + urlSuffix + urlSuffix1;
            
            fullurl1 = fullurl.split("?");
            fullurl = fullurl1[1].charAt(0);
            if(fullurl=="&"){
                var fullcorrecturl = urlPrefix + fullurl1[1].substring(1);
            }else{
                var fullcorrecturl = urlPrefix + urlSuffix + urlSuffix1;
            }
            window.location.replace(fullcorrecturl);
            
        } else {
            if(slectedCategories != ""){
                if (slectedCategories!="" && filter_firstvalue!="cat" && count_value_param > 1) {
                    urlSuffix1 += "&cat=" + slectedCategories;
                }else{
                    urlSuffix = "cat=" + slectedCategories;
                }
            }
            if(slectedsubCategories!=""){
                
                if (slectedsubCategories != "" && filter_firstvalue!="subcat" && count_value_param > 1) {
                    urlSuffix1 += "&subcat=" + slectedsubCategories;
                }else{
                    urlSuffix = "subcat=" + slectedsubCategories;
                }
            }
            if(slectedDirectoryTags!=""){
                
                if (slectedDirectoryTags != "" && filter_firstvalue!="tags" && count_value_param > 1) {
                    urlSuffix1 += "&tags=" + slectedDirectoryTags;
                }else{
                    urlSuffix = "tags=" + slectedDirectoryTags;
                }
            }
            if(selectedAmenities!=""){
                if (selectedAmenities != "" && filter_firstvalue!="amenity" && count_value_param > 1) {
                    urlSuffix1 += "&amenity=" + selectedAmenities;
                }else{
                    urlSuffix = "amenity=" + selectedAmenities;
                }
            }
            if(selectedTrademarks!=""){
                if (selectedTrademarks != "" && filter_firstvalue!="trademark" && count_value_param > 1) { 
                    urlSuffix1 += "&trademark=" + selectedTrademarks;
                }else{ 
                    urlSuffix = "trademark=" + selectedTrademarks;
                }
            }
            fullurl = urlPrefix + urlSuffix + urlSuffix1;
            fullurl1 = fullurl.split("?");
            fullurl = fullurl1[1].charAt(0);
            if(fullurl=="&"){
                var fullcorrecturl = urlPrefix + fullurl1[1].substring(1);
            }else{
                var fullcorrecturl = urlPrefix + urlSuffix + urlSuffix1;
            }
            window.location.replace(fullcorrecturl);
        }
    }
</script>

<style> 
.collapse:not(.show) { display: none !important; } .down-icon {  font-size: 16px; float: right; margin-right: -8px; margin-top: -33px;  }  
</style>