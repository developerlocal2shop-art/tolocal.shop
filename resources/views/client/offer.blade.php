@extends('layouts.client.blog')
@section('title','Offer')
@section('content')
<?php 
if(isset($data['productDirctoryData'])){
	$productData = $data['productDirctoryData'];
}
if(isset($data['dirctoryData'])){
	$dirctoryData = $data['dirctoryData'];
}
?>
<div class="sub_header_in sticky_header">
    <div class="container">
        <?php if(!empty($productData)){ ?>
        <h1>Products</h1>
        <?php }else if(!empty($dirctoryData)){ ?>
        <h1>Directory</h1>
        <?php }else{ ?>
        <h1>No Post!</h1>
        <?php } ?>
    </div>
</div>
<div class="collapse" id="collapseMap">
</div>
<!-- /Map -->
<div class="container-fluid margin_60_35">
    <div class="row justify-content-md-center">
        <aside class="col-xl-2 order-0" id="sidebar">
            <div id="filters_col">
                <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">Filters </a>
                <form class="filter-form" action="#" method="get" enctype="multipart/form-data">
                    <div class="collapse show" id="collapseFilters">
                        <div class="filter_type">
                            <h6>Category</h6>
                            <ul class="">
                                <li class="">
                                    <label class="container_check"> <i class="fa fa-cutlery" aria-hidden="true"></i> Restaurant <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="restaurant" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Family style <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="family-style" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Fine dining <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="fine-dining" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Cafe bistro <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="cafe-bistro" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Fast food <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="fast-food" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Restaurant buffet <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="restaurant-buffet" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Barbecue <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="barbecue" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Doughnut shops <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="doughnut-shops" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 ">
                                    <label class="container_check"> Juice bars <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="juice-bars" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="">
                                    <label class="container_check"> <i class="fa fa-h-square" aria-hidden="true"></i> Hotel <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="hotel" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Apartment hotel <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="apartment-hotel" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Bed and breakfast <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="bed-and-breakfast" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Casino hotel <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="casino-hotel" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Condo hotel <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="condo-hotel" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Guesthouse <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="guesthouse" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Inn <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="inn" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Motel <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="motel" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="hidden-categories hidden">
                                    <label class="container_check"> <i class="fas fa-shopping-cart"></i> Shopping <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="shopping" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Fashion <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="fashion" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Mobile <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="mobile" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Computer <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="computer" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Jewelry <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="jewelry" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Home and garden <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="home-and-garden" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Bags <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="bags" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Shoes <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="shoes" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Automobile <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="automobile" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="hidden-categories hidden">
                                    <label class="container_check"> <i class="fas fa-gem"></i> Fitness <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="fitness" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Acrobatics <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="acrobatics" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Lycra <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="lycra" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Yoga <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="yoga" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Boxing <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="boxing" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Hammock <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="hammock" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Kettlebell <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="kettlebell" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Cycling <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="cycling" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Pilates <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="pilates" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="hidden-categories hidden">
                                    <label class="container_check"> <i class="fas fa-cut"></i> Beauty <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="beauty" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Hair salon <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="hair-salon" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Hair removal <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="hair-removal" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Nail care <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="nail-care" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Facial <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="facial" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Makeup <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="makeup" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Eye color <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="eye-color" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Med spa <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="med-spa" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li class="ml-3 hidden-categories hidden">
                                    <label class="container_check"> Tattoo <small></small> <!-- Here will be the number of the total listing -->
                                        <input type="checkbox" name="category[]" class="categories" value="tattoo" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                            <a href="javascript::" id="category-toggle-btn" onclick="showToggle(this, 'hidden-categories')">Show more</a>
                        </div>

                        <!-- Price range filter -->
                        <div class="filter_type">
                            <h6>Price limit</h6>
                            <div class="distance"> Price within <span></span> USD</div>
                            <input type="range" class="price-range" min="0" max="2200" step="10" value="0" data-orientation="horizontal" onchange="filter(this)">
                        </div>

                        <div class="filter_type">
                            <h6>Amenities</h6>
                            <ul>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-child" aria-hidden="true"></i> Good for kids <input type="checkbox" class="amenities" name="amenity[]" value="good-for-kids" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-tree" aria-hidden="true"></i> Outdoor seating <input type="checkbox" class="amenities" name="amenity[]" value="outdoor-seating" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-television" aria-hidden="true"></i> Television <input type="checkbox" class="amenities" name="amenity[]" value="television" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-wheelchair" aria-hidden="true"></i> Wheelchair accessible <input type="checkbox" class="amenities" name="amenity[]" value="wheelchair-accessible" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-wifi" aria-hidden="true"></i> Free WiFi <input type="checkbox" class="amenities" name="amenity[]" value="free-wifi" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-car" aria-hidden="true"></i> Parking <input type="checkbox" class="amenities" name="amenity[]" value="parking" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-paw" aria-hidden="true"></i> Pet allowed <input type="checkbox" class="amenities" name="amenity[]" value="pet-allowed" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-diamond" aria-hidden="true"></i> Spa and fitness center <input type="checkbox" class="amenities" name="amenity[]" value="spa-and-fitness-center" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-bathtub" aria-hidden="true"></i> Swimming pool <input type="checkbox" class="amenities" name="amenity[]" value="swimming-pool" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <label class="container_check"> <i class="fa fa-cutlery" aria-hidden="true"></i> BBQ facilities <input type="checkbox" class="amenities" name="amenity[]" value="bbq-facilities" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="hidden-amenities hidden">
                                    <li>
                                        <label class="container_check"> <i class="fas fa-fire"></i> Smoke allowed <input type="checkbox" class="amenities" name="amenity[]" value="smoke-allowed" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="hidden-amenities hidden">
                                    <li>
                                        <label class="container_check"> <i class="fas fa-money-bill-alt"></i> Currency exchange <input type="checkbox" class="amenities" name="amenity[]" value="currency-exchange" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="hidden-amenities hidden">
                                    <li>
                                        <label class="container_check"> <i class="fas fa-shipping-fast"></i> Home delivery <input type="checkbox" class="amenities" name="amenity[]" value="home-delivery" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="hidden-amenities hidden">
                                    <li>
                                        <label class="container_check"> <i class="fas fa-glass-martini"></i> Bar <input type="checkbox" class="amenities" name="amenity[]" value="bar" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                                <div class="hidden-amenities hidden">
                                    <li>
                                        <label class="container_check"> <i class="fas fa-music"></i> Music <input type="checkbox" class="amenities" name="amenity[]" value="music" onclick="filter(this)">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </div>
                            </ul>
                            <a href="javascript::" id="amenity-toggle-btn" onclick="showToggle(this, 'hidden-amenities')">Show more</a>
                        </div>

                        <div class="filter_type">
                            <h6>Cities</h6>
                            <ul>
                                <li>
                                    <div class="">
                                        <input type="radio" id="city_all" name="city" class="city" value="all" onclick="filter(this)" checked>
                                        <label for="city_all">All</label>
                                    </div>
                                </li>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_1" name="city" class="city" value="paris" onclick="filter(this)">
                                            <label for="city_1">Paris</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_2" name="city" class="city" value="lyon" onclick="filter(this)">
                                            <label for="city_2">Lyon</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_3" name="city" class="city" value="nice" onclick="filter(this)">
                                            <label for="city_3">Nice</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_4" name="city" class="city" value="marseille" onclick="filter(this)">
                                            <label for="city_4">Marseille</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_5" name="city" class="city" value="bordeaux" onclick="filter(this)">
                                            <label for="city_5">Bordeaux</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_6" name="city" class="city" value="toulouse" onclick="filter(this)">
                                            <label for="city_6">Toulouse</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_7" name="city" class="city" value="strasbourg" onclick="filter(this)">
                                            <label for="city_7">Strasbourg</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_8" name="city" class="city" value="dijon" onclick="filter(this)">
                                            <label for="city_8">Dijon</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_9" name="city" class="city" value="nantes" onclick="filter(this)">
                                            <label for="city_9">Nantes</label>
                                        </div>
                                    </li>
                                </div>
                                <div class="hidden-cities hidden">
                                    <li>
                                        <div class="">
                                            <input type="radio" id="city_10" name="city" class="city" value="lille" onclick="filter(this)">
                                            <label for="city_10">Lille</label>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                            <a href="javascript::" id="city-toggle-btn" onclick="showToggle(this, 'hidden-cities')"></a>
                        </div>

                        <div class="filter_type">
                            <h6>Opening status</h6>
                            <ul>
                                <li>
                                    <label class="container_check"> <i class=""></i> Open now <input type="checkbox" class="openingStatus" name="with_open" value="open" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="filter_type">
                            <h6>Video</h6>
                            <ul>
                                <li>
                                    <label class="container_check"> <i class=""></i> With video <input type="checkbox" class="video_availability" name="with_video" value="1" onclick="filter(this)">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--/collapse -->
                </form>
                <!-- filter form ends -->
            </div>
            <!--/filters col-->
        </aside>
        <!-- /aside -->

        <div class="col-xl-5 col-md-12 order-xl-1 order-2" id="listings">

            <div class="row">
            <?php 
			if(!empty($productData)){
			foreach($productData as $productrow){ ?>
                <div class="col-lg-6 col-md-6 listing-div " data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
                    <div class="strip grid featured-tag-border">
                        <figure>
                            <a href="javascript::" class="wishlist-icon" onclick="addToWishList(this, '6')">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </a>
                            <a href="javascript::" class="featured-tag-grid">Featured</a>
                            <a href="" id="listing-banner-image-for-9eb131c8e0f5e5e3796606e6246b76e0" class="d-block h-100 img" style="background-image:url('{{asset('assets/client/images/f51408a1aa2c45625be91526079c7c61.jpg')}}')">
                                <div class="read_more"><span>Watch details</span></div>
                            </a>
                            <small>Hotel</small>
                        </figure>
                        <div class="wrapper featured-body">
                            <h3 class="ellipsis"><a href="">{{ $productrow->name }}</a></h3>
                            <small>Lille, France</small>
                        </div>
                       
                    </div>
                </div>
                <?php } }else if(!empty($dirctoryData)){ 
					foreach($dirctoryData as $directoryrow){ ?>
                <div class="col-lg-6 col-md-6 listing-div " data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id="9eb131c8e0f5e5e3796606e6246b76e0">
                    <div class="strip grid featured-tag-border">
                        <figure>
                            <a href="javascript::" class="wishlist-icon" onclick="addToWishList(this, '6')">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </a>
                            <a href="javascript::" class="featured-tag-grid">Featured</a>
                            <a href="" id="listing-banner-image-for-9eb131c8e0f5e5e3796606e6246b76e0" class="d-block h-100 img" style="background-image:url('{{asset('assets/client/images/f51408a1aa2c45625be91526079c7c61.jpg')}}')">
                               <div class="read_more"><span>Watch details</span></div>
                            </a>
                            <small>Hotel</small>
                        </figure>
                        <div class="wrapper featured-body">
                            <h3 class="ellipsis"><a href="">{{ $directoryrow->title }}</a></h3>
                            <small>
                                @php
                                $directoryAddress = Helper::getDirectoryLocation($directoryrow->id);
                                echo $directoryAddress[0]->address;
                                @endphp
                            </small>
                        </div>
                    </div>
                </div>
                <?php } }else{ ?>
                <div class="wrapper featured-body">
                    <h3 class="ellipsis"><a href="">{{__("message.no_record_found_text")}}</a></h3>
                </div>
                <?php } ?>
                <!-- A Single Listing Ends-->
            </div>
        </div>
        <!-- /col -->
        <div class="col-xl-3 order-xl-2 order-1">
            <div class="stiky-map mb-5 mb-xl-0">
                <div id="map" class="map-full map-layout"><iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d79488.92981732363!2d-0.3820522095939976!3d51.49433426815668!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x487673833cc94347%3A0x72d7cfdca507d7e8!2sAuthentic%20Thai%20Food%2C%20Colham%20Ave%2C%20West%20Drayton%20UB7%208EU%2C%20United%20Kingdom!3m2!1d51.512161299999995!2d-0.4695065!4m5!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2slondon!3m2!1d51.5073509!2d-0.1277583!5e0!3m2!1sen!2sin!4v1632736212100!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
            </div>
        </div>
    </div>
</div>
<!-- /container -->
<script type="text/javascript">
    $(document).ready(function() {
        var deviceWidth = $(window).width();
        // windows laptop
        if (deviceWidth >= 1300 && deviceWidth <= 1550) {
            $('#sidebar').removeClass('col-xl-2');
            $('#sidebar').addClass('col-xl-3');
            $('#listings').removeClass('col-xl-5');
            $('#listings').addClass('col-xl-6');
        }
        //Macbook pro Ratina display
        if (deviceWidth >= 2550 && deviceWidth <= 2900) {
            $('#sidebar').removeClass('col-xl-2');
            $('#sidebar').addClass('col-xl-3');
            $('#listings').removeClass('col-xl-5');
            $('#listings').addClass('col-xl-6');
        }
    });

    function filter(elem) {
        var urlPrefix = 'filter_listingsd41d.html?'
        var urlSuffix = "";
        var slectedCategories = "";
        var selectedAmenities = "";
        var selectedCity = "";
        var selectedVideoAvailability = 0;
        var selectedPriceRange = 0;
        var selectedOpeningStatus = "all";

        $('.categories:checked').each(function() {
            (slectedCategories === "") ? slectedCategories = $(this).attr('value'): slectedCategories = slectedCategories + "--" + $(this).attr('value');
        });

        $('.amenities:checked').each(function() {
            (selectedAmenities === "") ? selectedAmenities = $(this).attr('value'): selectedAmenities = selectedAmenities + "--" + $(this).attr('value');
        });

        $('.city:checked').each(function() {
            (selectedCity === "") ? selectedCity = $(this).attr('value'): selectedCity = selectedCity + "--" + $(this).attr('value');
        });

        $('.video_availability:checked').each(function() {
            (selectedVideoAvailability === 0) ? selectedVideoAvailability = $(this).attr('value'): selectedVideoAvailability = selectedVideoAvailability + "--" + $(this).attr('value');
        });
        $('.openingStatus:checked').each(function() {
            (selectedOpeningStatus === 'all') ? selectedOpeningStatus = $(this).attr('value'): selectedOpeningStatus = $(this).attr('value');
        });
        selectedPriceRange = $('.price-range').val();
        urlSuffix = "category=" + slectedCategories + "&&amenity=" + selectedAmenities + "&&city=" + selectedCity + "&&price-range=" + selectedPriceRange + "&&video=" + selectedVideoAvailability + "&&status=" + selectedOpeningStatus;
        window.location.replace(urlPrefix + urlSuffix);
    }

    function addToWishList(elem, listing_id) {
        var isLoggedIn = '';
        if (isLoggedIn === '1') {
            $.ajax({
                type: 'POST'
                , url: 'https://demo.creativeitem.com/atlas/home/add_to_wishlist'
                , data: {
                    listing_id: listing_id
                }
                , success: function(response) {
                    if (response == 'added') {
                        $(elem).html('<i class="fas fa-heart"></i>');
                        toastr.success('Added to wishlist');
                    } else {
                        $(elem).html('<i class="far fa-heart"></i>');
                        toastr.success('Removed from the wishlist');
                    }
                }
            });
        } else {
            loginAlert();
        }
    }

    function showToggle(elem, selector) {
        $('.' + selector).slideToggle();
        console.log($(elem).text());
        if ($(elem).text() === "Show more") {
            $(elem).text('Show less');
        } else {
            $(elem).text('Show more');
        }
    }
</script>

</main>
@endsection
