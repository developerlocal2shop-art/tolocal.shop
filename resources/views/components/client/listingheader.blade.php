<div id="page">
    <!-- Header -->
<header class="header_in ">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-12">
				<div id="logo">
					<a href="{{ URL::to('/') }}">
						<img src="{{ asset('assets/client/images/dark_logo.png') }}" width="165" height="35" alt="" class="logo_sticky">
					</a>
				</div>
			</div>
			<div class="col-lg-9 col-12">
				<x-Clientmenu/>			
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
	<!-- search_mobile -->
	<div class="layer"></div>
	<div id="search_mobile">
		<a href="#" class="side_panel"><i class="icon_close"></i></a>
		<div class="custom-search-input-2">
			<div class="form-group">
				<input class="form-control" type="text" placeholder="What are you looking..">
				<i class="fa fa-search" aria-hidden="true"></i>
			</div>
			<div class="form-group">
				<input class="form-control" type="text" placeholder="Where">
				<i class="icon_pin_alt"></i>
			</div>
			<select class="wide">
				<option>All Categories</option>
				<option>Shops</option>
				<option>Hotels</option>
				<option>Restaurants</option>
				<option>Bars</option>
				<option>Events</option>
				<option>Fitness</option>
			</select>
			<input type="submit" value="Search">
		</div>
	</div>
	<!-- /search_mobile -->
</header>

<!-- Main page -->
<main>
<div id="results">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4 col-10">
				<h4><strong>8</strong> Result for all</h4>
			</div>
			<div class="col-lg-9 col-md-8 col-2">
				<a href="#0" class="search_mob btn_search_mobile"></a> <!-- /open search panel -->
				<form action="#" method="GET">
					<div class="row no-gutters custom-search-input-2 inner">
						<div class="col-lg-8">
							<div class="form-group">
								<input class="form-control" name="search_string" type="text" value="" placeholder="What are you looking for...">
								<i class="fa fa-search a" aria-hidden="true"></i>
							</div> 
						</div>
						<div class="col-lg-3">
							<select class="wide" name="selected_category_id">
								<option value="">All categories</option>
								<option value="1">Restaurant</option>
								<option value="2">Family style</option>
								<option value="3">Fine dining</option>
								<option value="4">Cafe bistro</option>
								<option value="5">Fast food</option>
								<option value="6">Restaurant buffet</option>
								<option value="7">Barbecue</option>
								<option value="8">Doughnut shops</option>
								<option value="9">Juice bars</option>
								<option value="10">Hotel</option>
								<option value="11">Apartment hotel</option>
								<option value="12">Bed and breakfast</option>
								<option value="13">Casino hotel</option>
								<option value="14">Condo hotel</option>
								<option value="15">Guesthouse</option>
								<option value="16">Inn</option>
								<option value="17">Motel</option>
								<option value="18">Shopping</option>
								<option value="19">Fashion</option>
								<option value="20">Mobile</option>
								<option value="21">Computer</option>
								<option value="22">Jewelry</option>
								<option value="23">Home and garden</option>
								<option value="24">Bags</option>
								<option value="25">Shoes</option>
								<option value="26">Automobile</option>
								<option value="27">Fitness</option>
								<option value="28">Acrobatics</option>
								<option value="29">Lycra</option>
								<option value="30">Yoga</option>
								<option value="31">Boxing</option>
								<option value="32">Hammock</option>
								<option value="33">Kettlebell</option>
								<option value="34">Cycling</option>
								<option value="35">Pilates</option>
								<option value="37">Beauty</option>
								<option value="38">Hair salon</option>
								<option value="39">Hair removal</option>
								<option value="40">Nail care</option>
								<option value="41">Facial</option>
								<option value="42">Makeup</option>
								<option value="43">Eye color</option>
								<option value="44">Med spa</option>
								<option value="45">Tattoo</option>
						</select>
						</div>
						<div class="col-lg-1">
							<input type="submit" value="Search">
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- /row -->
		<div class="search_mob_wp">
			<div class="custom-search-input-2">
				<form action="https://demo.creativeitem.com/atlas/home/search" method="GET">
				<div class="form-group">
					<input class="form-control" name = "search_string" type="text" placeholder="What are you looking for...">
					<i class="fa fa-search" aria-hidden="true"></i>
				</div>
				<select class="wide" name="selected_category_id">
					<option>All categories</option>
					<option value="1">Restaurant</option>
					<option value="2">Family style</option>
					<option value="3">Fine dining</option>
					<option value="4">Cafe bistro</option>
					<option value="5">Fast food</option>
					<option value="6">Restaurant buffet</option>
					<option value="7">Barbecue</option>
					<option value="8">Doughnut shops</option>
					<option value="9">Juice bars</option>
					<option value="10">Hotel</option>
					<option value="11">Apartment hotel</option>
					<option value="12">Bed and breakfast</option>
					<option value="13">Casino hotel</option>
					<option value="14">Condo hotel</option>
					<option value="15">Guesthouse</option>
					<option value="16">Inn</option>
					<option value="17">Motel</option>
					<option value="18">Shopping</option>
					<option value="19">Fashion</option>
					<option value="20">Mobile</option>
					<option value="21">Computer</option>
					<option value="22">Jewelry</option>
					<option value="23">Home and garden</option>
					<option value="24">Bags</option>
					<option value="25">Shoes</option>
					<option value="26">Automobile</option>
					<option value="27">Fitness</option>
					<option value="28">Acrobatics</option>
					<option value="29">Lycra</option>
					<option value="30">Yoga</option>
					<option value="31">Boxing</option>
					<option value="32">Hammock</option>
					<option value="33">Kettlebell</option>
					<option value="34">Cycling</option>
					<option value="35">Pilates</option>
					<option value="37">Beauty</option>
					<option value="38">Hair salon</option>
					<option value="39">Hair removal</option>
					<option value="40">Nail care</option>
					<option value="41">Facial</option>
					<option value="42">Makeup</option>
					<option value="43">Eye color</option>
					<option value="44">Med spa</option>
					<option value="45">Tattoo</option>
				</select>
				<input type="submit" value="Search">
			</form>
			</div>
		</div>
		<!-- /search_mobile -->
	</div>
	<!-- /container -->
</div>
<!-- /results -->
<!-- /filters -->