@extends('layouts.client.contact')
@section('title','Sitemap Categories and Pages | tolocal.shop ')
@section('content')
</a>
	<style>
	.margin_60_35 { padding-top: 25px; padding-bottom: 25px; }
	.map_sec1 a { color: #333333 !important; font-weight: 400; }
	.map_sec1 { margin-bottom: 15px;}
	.map_sec1 h2 { font-size: 20px; }
	.map_sec1 a:hover { color: #dc3545 !important; text-decoration: underline !important; }
	.container-fluid {padding-right: 40px;padding-left: 40px;}
	i.fas {font-size: 15px; }
	i.far {font-size: 15px; }
	.map_sec1 h5 { font-size: 18px; }

	@media screen and (max-width:767px){
		.sub_header_in.sticky_header {
			margin-top: 55px;
		}
		.container-fluid.margin_60_35 {
			padding-left: 25px;
			padding-right: 25px;
		}
    }
	</style>

	<div class="sub_header_in sticky_header">
		<div class="container-fluid">
		<h1>Sitemap Categories and Pages</h1>
		</div>
	</div>
	<div class="container-fluid margin_60_35">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				<h4 style="text-decoration: underline;font-size: 22px;">Product Categories</h4>
				<?php foreach($procategories as $parentcat){ ?>
					<div class="map_sec1">
						<a target="_blank" href="{{ url('shop?cat='.$parentcat->id.'') }}">
							<h5>
								<span> <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i> </span>
								{{ ($parentcat->name!='')?$parentcat->name:$parentcat->name }} </h5>
						</a>
						
						<?php foreach($prosubcategories as $subcat){ 
						if($subcat->parent_id == $parentcat->id){
						?>
							
						<a target="_blank" href="{{ url('shop?subcat='.$subcat->id.'') }}">{{ ($subcat->name!='')?$subcat->name:$subcat->name }}</a> |
							
						<?php } }  ?>
					</div>
					<?php } ?>

		    </div>

			<div class="col-lg-12">
				<h4 style="text-decoration: underline;font-size: 22px;">Pages</h4>
				
					<div class="map_sec1">
						<a><h5><i class="fas fa-book" aria-hidden="true"></i> {{__("All Pages Links")}}</h5></a>

						<a target="_blank" href="{{ route('shop') }}">{{__("Shop")}}</a> | 
						<a target="_blank" class="notranslate" href="{{ route('listing') }}"> Companies </a> | 
						<a target="_blank" href="{{ route('about') }}">{{__("message.footer_text_about")}}</a> | 
						<a target="_blank" href="{{ route('terms-and-condition') }}">{{__("message.footer_text_terms_and_conditions")}}</a> | 
						<a target="_blank" href="{{ route('privacy-policy') }}">{{__("message.footer_text_privacy_policy")}}</a> | 
						<a target="_blank" href="{{ route('faq') }}">{{__("message.footer_text_faq")}}</a> | 
						<a target="_blank" href="{{ route('event') }}">{{__("Events")}}</a> | 
						<a target="_blank" href="{{ route('page.sitemap') }}">{{__("Sitemap")}}</a> | 
						<a target="_blank" href="{{ route('contact') }}">{{ ucfirst(__("message.menu_text_contact"))}}</a> | 
						<a target="_blank" href="{{ route('blog') }}">{{ ucfirst(__("message.menu_text_blog"))}}</a> | 
						<a target="_blank" href="{{ route('wishlist') }}"> Whishlist </a> | 
						<a target="_blank" href="{{ route('cart.list') }}"> Cart </a>
					</div>
					
		    </div>

			<div class="col-lg-12">
				<h4 style="text-decoration: underline;font-size: 22px;">Company Categories</h4>
				<?php foreach($data['categories'] as $parentcat){ ?>
					<div class="map_sec1">
						<h5>
							<a target="_blank" href="{{ url('listings?cat='.$parentcat->id.'') }}" >
								<span> <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i> </span>
							 {{ ($parentcat->title!='')?$parentcat->title:$parentcat->name }}
						    </a>
						</h5>
						<?php 
						foreach($data['subcategories'] as $subcat){ 
							if($subcat->parent_id == $parentcat->id){
						?>
						<a target="_blank" class="link" href="{{ url('listings?subcat='.$subcat->id.'') }}">{{ ($subcat->title!='')?$subcat->title:$subcat->name }}</a> <span>|</span>
						<?php } }  ?>
					</div>
				<?php } ?>
		    </div>
	    </div>
    </div>
@endsection