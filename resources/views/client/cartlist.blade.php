@extends('layouts.client.contact')
@section('title','Cart | ToLocal.shop ')
@section('content')
</a>
<?php
$defaultCurrency = Helper::defaultCurrencyData();
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}
$taxValue = Helper::taxValueData();
$cartdata = count(session()->get('cart', []));
?>
<div class="AJloader" id="AJloader"></div>
<div class="sub_header_in sticky_header">
	<div class="container">
		<h1>{{__("message.menu_text_cart")}}</h1>
	</div>
</div>
<div class="container margin_60_35">
	<div class="row justify-content-center">
		<input type="hidden" class="notranslate" value="{{ $currencySymbol }}" id="currency">
		<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
		<div class="col-md-8 carttable_main">
			<table id="cart" class="table table-hover table-condensed">
				@if($cartdata>0)
				<thead>
					<tr>
						<th style="width:50%">Product</th>
						<th style="width:10%">{{__("message.price_text")}}</th>
						<th style="width:8%">{{__("message.quantity_text")}}</th>
						<th style="width:22%" class="text-center">{{__("message.subtotal_text")}}</th>
						<th style="width:10%"></th>
					</tr>
				</thead>
				<tbody>
					@php $total = 0;$subtotl = 0;
					@endphp
					@if(session('cart'))
					@php
					$quantity = 0;
					@endphp
					@foreach(session('cart') as $id => $details)
					@php $subtotl += (float)$details['price'] * $details['quantity'] @endphp
					@php $total += (float)$details['price'] * $details['quantity'] @endphp
					@php
					$seo_slug = $details['product_seo_slug'];
					$cp_id = $details['p_id'];
					@endphp

					<tr data-id="{{ $id }}" id="<?php echo $cp_id ?>">
						<td data-th="Product" class="cart_product">
							<div class="row">
								<div class="col-md-3 col-3 hidden-xs">
									<a href="<?php echo url("product/detail", ['id'=> @$cp_id,'slug' => @$seo_slug]);
										?>">
										@if($details['image'])
										<img src="{{ env('IMAGE_PATH').'images/product/'.$details['image'].'.webp' }}"
											width="100" height="100" class="img-responsive" />
										@else
										<img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}"
											width="100" height="100" class="img-responsive" />
										@endif
									</a>
								</div>
								<div class="col-md-9 col-9">
									<a href="<?php echo url("product/detail", ['id'=> @$cp_id,'slug' => @$seo_slug]);
										?>">
										<h6 class="nomargin">{{ $details['name'] }}</h6>
										@if(!empty($details['product_number']))
										<span style="font-size:14px; color:#000; ">
											Product No: {{ $details['product_number'] }}</span>
										@endif
										@if(!empty($details['attr_name']))
										<p>
											@php
											$attr_name=$details['attr_name'];
											$attr_value=$details['attr_value'];
											$c_attr_value=array_combine($attr_name,$attr_value);
											@endphp
											@foreach ($c_attr_value as $key => $attrval)
											{{ $key }} : {{ $attrval }}<br>
											@endforeach
										</p>
										@endif
										<p>
											@if($details['offer_dicount_type'])
											@if($details['offer_dicount_type'] == 'fixed')
											Offer - {{ $details['offer'] }}
											@else
											Offer - {{ $details['offer'] }}
											@endif
											@endif
											@if($details['shopping_dicount_type'])
											@if($details['shopping_dicount_type'] == 'percentage')
											Shopping Card - {{ $details['shoppingcard'] }}%
											@else
											Shopping Card - {{ $details['shoppingcard'] }}
											@endif
											@endif
										</p>
										@php
										$free_product_id = json_decode($details['free_product_data'])->id??0;
										$getFreeProductDirectoryId =
										Helper::getFreeProductDirectoryId($free_product_id);
										$subtotallprice = (float)$details['price'] * $details['quantity'];
										$minimum_amount = $getFreeProductDirectoryId[0]->minimum_amount??0;
										@endphp
										@if($subtotallprice > $minimum_amount)
										@if(count($getFreeProductDirectoryId[1]) > 0)
										<h6>Free Products</h6>
										<ul class="freeProList">
											@foreach($getFreeProductDirectoryId[1] as $freeProduct)
											@foreach($freeProduct as $freePro)
											<li>
												<?php $imagename = Helper::getProductImage($freePro->id); ?>
												<?php if(!empty($imagename[0]->name_thumbnail)){ ?>
												<img src="{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}"
													style="width: 40px;height: 40px;" />
												<?php }else{ ?>
												<img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}"
													style="width: 40px;height: 40px;" />
												<?php } ?>
												<b>{{ $freePro->name??'' }}</b>
											</li>

											@endforeach
											@endforeach
										</ul>
										@endif
										@endif
									</a>
								</div>
							</div>
						</td>
						<td data-th="Price" class="cart_price notranslate">
							{{ $currencySymbol." ".number_format((float)$details['price'],2) }}</td>
						<td data-th="Quantity" class="cart_qty">
							<input type="hidden" id="prodId" value="{{ $id }}">
							<input type="hidden" class="productstock" id="prodStock"
								value="{{ $details['productstock'] }}">
							<input type="number" style="height: 29px;" value="{{ $details['quantity'] }}"
								class="form-control quantity update-cart"
								onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]{5}"
								min="1" max="100" />
						</td>
						<td class="productSubtotal" data-th="Subtotal" class="text-center cart_qty_price">
							<span class="notranslate">
								@php
								$pricesub = (float)$details['price'] * $details['quantity'];
								@endphp
								{{ $currencySymbol." ".number_format($pricesub,2) }}
							</span>
						</td>
						<td class="actions" data-th="">
							<button class="btn btn-sm save_later"
								onclick="addToSaveForLatter(this, <?php echo $cp_id ?>)">
								<i class="fa fa-download"> Save for later</i>
							</button>
							<button class="btn btn-danger btn-sm remove-from-cart"><i
									class="fa fa-trash-o"></i></button>

						</td>
					</tr>
					@php
					$quantity += $details['quantity'];
					@endphp
					@endforeach
					@endif
				</tbody>
				<tfoot>

					<tr>
						<td class="text-left" id="couponrow">
							<div class="row">
								<div class="col-md-8">
									<input type="text" value="" class="form-control" id="input-coupon-code" placeholder="{{__("message.placeholder_coupon_code_text")}}" />
								</div>
								<div class="col-md-4">
									<button href="javascript::void(0)" style="margin: 7px 0px;"
										class="btn btn-warning btn-sm" id="apply_coupoun">{{__("message.apply_coupon_btn")}}
									</button>
								</div>
							</div>
						</td>
						<td colspan="4" class="text-right">
							<a href="{{ url('/shop') }}" class="btn btn-warning btn-sm continue_shopping"><i
									class="fa fa-angle-left"></i>
								{{__("message.continue_shopping_btn")}}</a>

							<a href="{{ route('remove.from.cart') }}" style="color: #005;font-size: 14px;"
								class="btn btn chek-out clear-btn">{{__("message.clear_cart_btn")}}</a>
						</td>
						
					</tr>
					<tr>
						<td colspan="12" style="padding: 2px 20px;" id="couponrow_error">
							
						</td>
					</tr>
				</tfoot>
				@else
				<thead>
					<tr>
						<th style="width:100%">
							<center>
								<h2>{{__("message.no_item_in_cart_text")}}</h2>
							</center>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="5" class="text-right">
							<a href="{{ url('/shop') }}" class="btn btn-warning btn-sm"><i class="fa fa-angle-left"></i>
								{{__("message.continue_shopping_btn")}}</a>
						</td>
					</tr>
				</tfoot>
				@endif
			</table>
		</div>
		@if(session('cart'))
		<div class="col-md-4 card price_detail" style="padding: 8px;">

			<table>
				<tr>
					<h4 class="text-center"> Price details </h4>
					<div class="IbVNxe"> </div>
				</tr>
				
				<tr id="subtotal">
					
					<td colspan="4" class="text-left"><strong>{{-- {{__("message.subtotal_text")}}: --}}
							Price ({{ $quantity }} item)
						</strong>
					</td>
					<td colspan="4" class="text-right">
						<strong id="subtotalval"><span class="notranslate">
								{{ $currencySymbol." ".number_format($subtotl,2) }}</span></strong>
					</td>
				</tr>
				<?php 
				$priceAftertax = ($subtotl)*($taxValue[0]->taxval/100); 
				$adminFlatShippingData = Helper::getAdminFlatShippingData();
				$flatRateValue = $adminFlatShippingData->flat_rate??0;
				?>
				{{-- <tr id="tax">

					<td colspan="4" class="text-left "><strong>{{__("message.tax_text")}}({{
							$taxValue[0]->taxval}}%):</strong></td>
					<td colspan="4" class="text-right"><strong id="taxvl"><span class="notranslate">{{ $currencySymbol."
								".number_format($priceAftertax,2) }}</span></strong></td>
				</tr> --}}
				<tr id="tax">

					<td colspan="4" class="text-left ">
						<strong>{{__("Flat Rate")}} ({{ $flatRateValue}}) </strong>
					</td>
					<td colspan="4" class="text-right"><strong id="taxvl2">
							<span class="notranslate">{{ $currencySymbol." ".number_format($flatRateValue,2)
								}}</span></strong></td>
				</tr>
				<tr id="totalrow">
					<?php $total = ($flatRateValue)+($total); ?>
					<td colspan="4" class="text-left IbVNxe">
						<strong>
							{{-- {{__("message.grand_total_text")}} --}}
							Total Amount
						</strong>
					</td>
					<td colspan="4" class="text-right IbVNxe">
						<strong id="totalval">
							<span class="notranslate">
								{{ $currencySymbol." ".number_format($total,2) }}
							</span>
					    </strong>
					</td>
				</tr>

				<tr>
					<td colspan="6" class="text-right">
						<div class="btn_home_align notranslate">
							<a href="{{ route('checkout') }}" class="btn_1 rounded chek-out">
								{{-- {{__("message.checkout_btn")}} --}}
								Checkout
							</a>
						</div>
						
					</td>
				</tr>
				
				<tr>
					<td colspan="6">
						<div class="IbVNxe2">Safe and Secure Payments.Easy returns.100% Authentic products.</div>
					</td>
				</tr>
			</table>
		</div>
		@endif

		<div class="col-md-12 saved_for_latter">
			<?php 
			$count = count($saveforlatter); 
			if($count>0){
		    ?>
			<hr>
			<h6> <i class="fa fa-download"></i> Saved For Latter (<span id="count_save_latter">{{ $count }}</span>)</h6>
			<?php 
				}	
			?>
			<table id="cart" class="table table-hover table-condensed">

				<tbody>

					<?php 
                        $i = 0; 
                        if($count>0){	
                    ?>

					@foreach($saveforlatter as $row)
					@if(!empty($row->product->id))
					@php
					$imagename = Helper::getProductImage($row->product_id);
					$urlproduct = Helper::getProductSeoSlug($row->product_id);
					$seo_slug = $row->product->seo_url;
					$p_id = $row->product->id;
					@endphp
					<tr id="save_{{ $row->id }}" style="border-bottom: 1px solid #dee2e6;">
						<td data-th="Product" class="cart_product">
							<div class="row">
								<div class="col-md-2 col-2 hidden-xs">
									<a href="<?php echo url("product/detail", ['id'=> @$p_id,'slug' => @$seo_slug]);
										?>">
										<img src="{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}"
											width="100" height="100" class="img-responsive">
									</a>
									<input type="text" style="height: 29px; width: 101px;" disabled value="Qty: 1"
										class="form-control">
								</div>
								<div class="col-md-6 col-6 right_save_latter">
									<a href="<?php echo url("product/detail", ['id'=> @$p_id,'slug' => @$seo_slug]);
										?>">
										<h6 class="nomargin">{{ $row->product->name??'' }}</h6>
										<span style="font-size:14px; color:#000; ">
											Product No: {{ $row->product->supplier_product_no??'N/A'  }}
										</span>
										<p>
										</p>
									</a>
									<span class="notranslate" style="border-bottom: 1px solid #dee2e6;">
										{{ $currencySymbol }} {{ $row->product->price->regular_price??'' }} </span><br><br>
									
									<input type="hidden" id="regularprice_{{ $p_id }}" value="{{ $row->product->price->regular_price }}" name="regularprice">
									<input type="hidden" id="image_{{ $p_id }}" value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}" name="image">	
									<button class="btn btn-sm save_later" onclick="moveToCart({{ $row->id }}, {{ $p_id }})">
										<i class="fa fa-shopping-cart"> Move to cart</i>
									</button>
									<button class="btn btn-danger btn-sm"
										onclick="deleteSaveForLatter(this, {{ $row->id }})"><i
											class="fa fa-trash-o"></i></button>
								</div>
							</div>
						</td>


					</tr>

					@endif
					@endforeach
					<?php } ?>

					

				</tbody>

			</table>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(".update-cart").change(function (e) {
			e.preventDefault();
			var ele = $(this);
			
			$.ajax({
				url: '{{ route('update.cart') }}',
				method: "patch",
				data: {
					_token: '{{ csrf_token() }}',
					id: ele.parents("tr").attr("data-id"),
					quantity: ele.parents("tr").find(".quantity").val(),
					productstock: ele.parents("tr").find(".productstock").val()
				},
				success: function (response) {
					console.log(response);
					if(parseInt(response.quantity) > parseInt(response.productstock)){
						alert("Required quantity is out of stock");
					}
					window.location.reload();
				}
			});
		});
	});

	function addToSaveForLatter(elem, id) {
		var ele = $(this);
		//console.log(ele.parents("tr").attr("data-id"));

		$('.AJloader').show();
        var user_login = $('#user_id').val();
        if (user_login > 0) {
            $.ajax({
                type: 'POST'
                , url: baseUrl + '/addsaveforlatter/' + id
                , data: {
                    "_token": "{{ csrf_token() }}"
                    , user_login: user_login
                    , id: id
                }
                , success: function(response) {
					$('.AJloader').hide();
                    if (response.msg == 'Product saved for latter') {
                        //$(elem).html('<i class="fas fa-heart"></i>');
                        toastr.success('Product saved for latter');

						$.ajax({
							url: '{{ route('remove.item.cart') }}',
							method: "DELETE",
							data: {
								_token: '{{ csrf_token() }}',
								id: id
							},
							success: function (response) {
								$("#"+id).hide();
								setTimeout( function(){
									window.location.reload();
								}, 1000);
								
							}
						});

                    } else {
                        //$(elem).html('<i class="far fa-heart"></i>');
                        toastr.error('This item is already in save for latter');
                    }
                }
            });
        } else if (user_login == 0) {
			$('.AJloader').hide();
            toastr.error('{{__("message.please_login_first_text")}}');
			setTimeout( function(){
				window.location.href = "{{ url('login') }}";
			}, 1000);
			
        }
    }

	function deleteSaveForLatter(elem, id) {
		$('.AJloader').show();
		if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{ route('remove.item.savelatter') }}',
                method: "get",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
					console.log(response);
					$('#count_save_latter').html(response.countsavelatter);
					$('.AJloader').hide();
					$("#save_"+id).hide();
					if(response.countsavelatter == 0){
                       window.location.reload();
					}
                    
                }
            });
        }
		$('.AJloader').hide();
	};

	function moveToCart(s_id, p_id) {
		$('.AJloader').show();
		if(confirm("Are you sure want to move to cart?")) {

			var product_image = $('#image_'+p_id).val();
			var product_price = $('#regularprice_'+p_id).val();
            $.ajax({
                url: '{{ route('move.item.cart') }}',
                method: "get",
                data: {
                    _token: '{{ csrf_token() }}',
                    s_id: s_id,
					p_id: p_id,
					product_image: product_image,
					product_price: product_price
                },
                success: function (response) {
					console.log(response);
					$('#count_save_latter').html(response.countsavelatter);
					$('.AJloader').hide();
					$("#save_"+s_id).hide();
                    setTimeout( function(){
						window.location.reload();
					}, 1000);
                }
            });
        }
		$('.AJloader').hide();
	};

	

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
		
        if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{ route('remove.item.cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });

	$("#apply_coupoun").click(function (e) {
        e.preventDefault();

		$('.AJloader').show();

		var user_login = $('#user_id').val();
        if (user_login == 0) {
			$('.AJloader').hide();
            toastr.error('{{__("message.please_login_first_text")}}');
			setTimeout( function(){
				window.location.href = "{{ url('login') }}";
			}, 1000);
			return false;
		}

        var ele = $(this);
  		var couponCode = $("#input-coupon-code").val();
		if(couponCode == ''){
			$('.AJloader').hide();
			//$('#couponerror').remove();
			$("#couponrow_error").append('<div id="couponerror" class="alert alert-danger">Coupon code is required</div>');
			return false;
		}
        var currency_curent = $('#currency').val();
		var taxvalue = <?php echo $flatRateValue??''; ?>//$taxValue[0]->taxval??0 ?>;
            $.ajax({
                url: '{{ route('apply.coupon.code.to.cart') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    couponcode: couponCode
                },
				dataType: 'json',
                success: function (response) {
					$('.AJloader').hide();
					if(response.error){
						$('#couponerror').remove();
						$("#couponrow_error").append('<div id="couponerror" class="alert alert-danger">'+response.error+'</div>');
					}
					if(typeof response.productTotal!="undefined" && typeof response.discountval!="undefined"){
						$('#discountrow').remove();
						$('#couponerror').hide();
						$('#subtotalval').html(currency_curent+" "+response.subtotal.toFixed(2));
						//var taxaftercoupon = (response.subtotal)*(taxvalue/100);
						//$('#taxvl').html(currency_curent+" "+taxaftercoupon.toFixed(2));
						$('#taxvl').html(currency_curent+" "+taxvalue.toFixed(2));
						var finaltotal = (response.subtotal+taxvalue)-response.discountval;
						$('#totalval').html(currency_curent+" "+finaltotal.toFixed(2));
						$('#totalrow').before(`<tr id="discountrow">
							<td colspan="4" class="text-right"><strong >Discount:</strong></td>
							<td colspan="4" class="text-right"><strong id="dicount-val">`+currency_curent+" "+response.discountval.toFixed(2)+`</strong></td>
						</tr>`);
						toastr.success('Coupon code is applied successfully!');

						$('#apply_coupoun').prop('disabled', true);

					}
					$(response.couponproductId).each(function(key,value){
						$( "tr[data-id='"+value.id+"']" ).find(".productSubtotal").html( currency_curent+" <strike>"+value.productcartprice+"</strike> "+value.poroductdisprice );
					});
                }
            });
    });

</script>

<style>
	@media (max-width: 767px) {

		table#cart td.cart_price,
		table#cart td.cart_qty,
		#cart td.productSubtotal {
			width: 19% !important;
		}

		.save_later {
			font-size: 13px;
			width: 100px;
			text-align: left;
		}

		.remove-from-cart {
			font-size: 12px;
		}
	}

	.table thead th {
		border-bottom: 0px solid #dee2e6;
	}

	.carttable_main img.img-responsive {
		min-width: 60px;
	}

	.table td,
	.table th {
		vertical-align: middle;
	}

	.IbVNxe {
		border-top: 1px dashed #e0e0e0;
		padding: 12px 0;
		font-size: 16px;
		display: -ms-flexbox;
		-webkit-justify-content: space-between;
		-ms-flex-pack: justify;
		justify-content: space-between;
		color: #388e3c;
		font-weight: 500;
	}

	#couponerror {
		padding: 5px 5px;
		margin: 5px 0px;
		width: auto;
	}

	@media (max-width: 991px) {
		#cart a#apply_coupoun {
			margin-top: 0px !important;
		}

		#cart a.btn.btn-warning {
			/* width: 100%; */
			margin-bottom: 15px;
			display: block;
			max-width: 88%;
			margin-left: 50px;
		}
	}



	@media (max-width: 767px) {

		.right_save_latter {
			margin: 1px 55px;
		}
		.chek-out, .clear-btn {
			width: 100%;
		}
		.notification-count {
			position: relative !important;
			padding: 4px 7px !important;
			margin: 0px 19px !important;
			top: 0 !important;
			right: 0 !important;
		}

		i.fa.fa-shopping-cart {
			margin: -16px -24px 4px 1px !important;
			float: none !important;
			position: relative !important;
		}

		#cart a#apply_coupoun {
			margin-top: 15px !important;
		}

		#cart input#input-coupon-code {
			width: 60%;
		}

		#cart td#couponrow a#apply_coupoun {
			width: 35%;
			float: right;
			position: absolute;
			top: -31px;
			right: 0;
			font-size: 13px;
		}

		#cart td.cart_product {
			padding: 5px 10px;
		}
		#cart td.actions {
			width: 43% !important;
		}
		.table td,
		.table th {
			vertical-align: top;
		}
		.carttable_main img.img-responsive {
			width: 60%;
		}
		.sub_header_in.sticky_header {
			margin-top: 45px;
			padding-bottom: 1px;
		}
		table#cart td.cart_price,
		table#cart td.cart_qty,
		#cart td.productSubtotal {
			line-height: 29px;
		}
		strong { font-weight: 500; }
		.continue_shopping {
			width: 50% !important;
			font-size: 12px;
			margin: 0px -15px;
			background: none;
		}
		a.btn.btn.chek-out.clear-btn {
			text-align: left;
		}
		#cart input#input-coupon-code {
			height: 30px;
		}
		.IbVNxe {
			font-size: 14px;
		}
		.text-left {
			text-align: left !important;
			width: 50% !important;
		}
		tr#subtotal td:nth-child(2),
		tr#tax td:nth-child(2),
		tr#totalrow td:nth-child(2) {
			width: 100% !important;
		}
		h4.text-center {
			display: none;
		}
	}

	.AJloader {
		position: fixed;
		width: 100%;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		background-color: rgba(255, 255, 255, 0.4);
		z-index: 9999;
		display: none;
	}

	input#input-coupon-code {
		font-size: 12px;
	}

	input#input-coupon-code {
		font-size: 12px;
		height: 35px;
		margin: 6px;
	}
</style>

@endsection