@extends('layouts.client.blog')
@section('title','Shop | ATLAS Directory Listing CMS')
@section('content')
<?php
	if(isset($data)){$productData = $data;}
	$defaultCurrency = Helper::defaultCurrencyData();
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}
	$wislistProductId=array();
	foreach($wishlists as $wishid){
		$wislistProductId[]=$wishid['product_id'];
	}
	$search_string = request()->search_string;
	if((request()->cat)>0){
		$cat = request()->cat;
	}else{
		$cat = "";
	}
?>
<div class="sub_header_in sticky_header">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-10">
				<h1><?php echo $categoryRelatedData; ?></h1>
			</div>
			<div class="col-lg-6 col-md-6 col-2">
				<a href="#0" class="search_mob btn_search_mobile"></a> 
				<form action="{{ route('category.product',$catslug) }}" method="GET">
					<div class="row no-gutters custom-search-input-2 inner">
						<div class="col-lg-10">
							<div class="form-group">
								<input class="form-control" name="search_string" type="text" value="{{ isset($search_string)?$search_string:''; }}" placeholder="{{__("message.header_search_text")}}">
								<i class="fa fa-search" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-lg-2">
							<input type="submit" value="{{__("message.header_search_text_search_btn")}}">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="margin_60_35">
	<div class="row justify-content-md-center">
		<x-Clientfilter/>
		<div class="col-xl-8 col-md-12 order-xl-1 order-2" id="listings">
			<div class="row">
				<?php
				if(count($productData)!=0){
					foreach($productData as $productrow){
					$productprice = Helper::getProductprice($productrow->id,$currencycode);
					$validityOfOffer = Helper::getValidityOfOffer($productrow->id);
					$productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
					$productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
					$productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;

					if(!empty($validityOfOffer)){
						if($productRegularPrice>0 && $productSalesPrice>0){
							$priceGoestoCart = $productSalesPrice;
						}else{
							$priceGoestoCart = $productRegularPrice;
						}
					}else{
						$priceGoestoCart = $productRegularPrice;
					}
					?>
				<div class="col-lg-4 col-md-4 listing-div " data-marker-id="9eb131c8e0f5e5e3796606e6246b76e0" id = "9eb131c8e0f5e5e3796606e6246b76e0">
					<div class="strip grid featured-tag-border">
						<figure>
							<input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
							<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
								<a href="javascript:void(0);" productid="{{ $productrow->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
									<?php
									if(!in_array($productrow->id, $wislistProductId)){ ?>
										<i class="fa fa-heart-o" aria-hidden="true"></i>
									<?php }else{ ?>
										<i class="fa fa-heart" aria-hidden="true"></i>
									<?php } ?>
								</a>
							<?php $imagename = Helper::getProductImage($productrow->id); ?>
							<a href="<?php echo url('shop/details', ['id' => $productrow->id]); ?>" class="d-block h-100 img"
							<?php if(!empty($imagename)){ ?>
								style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')"
							<?php }else{ ?>
								style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')"
							<?php } ?>
							>
							<div class="read_more">
							{{-- <form action="{{ route('add.to.cart',$productrow->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								<input type="hidden" value="{{ $productrow->id }}" name="id">
								<input type="hidden" value="{{ ($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name }}" name="name">
								<input type="hidden" value="{{ $priceGoestoCart }}" name="price">
								<input type="hidden" value="{{ $productRegularPrice }}" name="regularprice">
								<input type="hidden" value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}"  name="image">
								<input type="hidden" value="1" name="quantity">
								@if ($productrow->stock > 0 && !$productrow->outofstock == 'outofstock')
                                    <button class="btn "><i class="fa fa-shopping-bag"></i>{{__("message.add_to_cart_btn")}}</button>
                                @else
                                <span style=" color:red;">Out Of Stock</span>
                                @endif
							</form> --}}
							<button class="btn "><i class="fa fa-eye"></i> View More </button>
							</div>
								<div class="pro-icn">
									<a href="wishlist.html" class="w-c-q-icn"><i class="fa fa-heart"></i></a>
									<a href="cart.html" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
								</div>
							</a>
						</figure>
						<div class="wrapper featured-body">
							<h3 class="ellipsis"><a href="<?php echo url('shop/details', ['id' => $productrow->id]); ?>">{{ ($productrow->product_lang_name!='')?$productrow->product_lang_name:$productrow->name }}</a></h3>
							<p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
						</div>
						<ul class="featured-footer mb-0">
							<?php if(!empty($validityOfOffer)){  ?>
								<li>
								<?php if($productSalesPrice>0){ ?>
									<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productSalesPrice }}</span></div>
								<?php } ?>
								<?php if($productRegularPrice>0 && $productSalesPrice==0){?>
									<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
								<?php }else if($productRegularPrice>0){ ?>
									<div class="pro-price"><span class="old-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
								<?php } ?>
								</li>
							<?php }else{ ?>
								<li>
									<div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
								</li>
							<?php } ?>
							<li style="display:none;"><div class="score"><span> Awesome <em> 2 Reviews </em> </span><strong>5.0</strong></div></li>
						</ul>
					</div>
				</div>
				<?php } }else{ ?>
					<div class="text-center">
						<h2>{{__("message.no_record_found_text")}}</h2>
					</div>
				<?php } ?>
			</div>
				{{$productData->links('pagination::bootstrap-4')}}
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
	if (deviceWidth >= 2550 && deviceWidth <= 2900) {
		$('#sidebar').removeClass('col-xl-2');
		$('#sidebar').addClass('col-xl-3');
		$('#listings').removeClass('col-xl-5');
		$('#listings').addClass('col-xl-6');
	}
});

    function addToWishList(elem,id) {
		var user_login   = $('#user_id').val();
		$('.AJloader').show();

		if (user_login >0) {
			$.ajax({
				type : 'POST',
				url: baseUrl+'/add-to-wishlist/'+id,
				data: {"_token": "{{ csrf_token() }}",user_login : user_login, id:id },
				success : function(response) {
					if (response.msg == 'Added to wishlist') {
						$(elem).html('<i class="fas fa-heart"></i>');
						toastr.success('{{__("message.added_to_wishlist_text")}}');
					}else if(response.msg == 'This item is already in wishlist'){
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('{{__("message.this_item_is_already_in_wishlist_text")}}');
					}else{
						$(elem).html('<i class="far fa-heart"></i>');
						toastr.success('{{__("message.removed_from_the_wishlist_text")}}');
					}
					$('.AJloader').hide();
				}
			});
		}else if(user_login ==0){
			toastr.error('{{__("message.please_login_first_text")}}');
			 window.setTimeout(function() {
				 $('.AJloader').hide();
				window.location.href = "{{ url('login') }}";
			}, 3000);

		}
	}
	function showToggle(elem, selector) {
		$('.'+selector).slideToggle();
		console.log($(elem).text());
		if($(elem).text() === "Show more")
    {
        $(elem).text('Show less');
    }
    else
    {
        $(elem).text('Show more');
    }
	}
</script>

</main>
<style>
	.read_more button {
		background-color: #fcfcfc;
		background-color: rgba(255, 255, 255, 0.8);
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		-ms-border-radius: 20px;
		border-radius: 20px;
		display: inline-block;
		color: #222;
		font-size: 12px;
		font-size: 0.75rem;
		padding: 5px 10px;
	}
	.old-price{
		text-decoration: line-through;
		color:dimgray;
		font-size: 12px;
    	font-weight: 160;
	}
</style>
@endsection
