<?php if(count($most_viewed_listings) > 0){ ?>
    <div class="container-fluid  margin_80_55">
		<div class="main_title_2">
			<span><em></em></span>
			<h2>Most Viewed Companies</h2>
		</div>
        <div id="sploffer" class="owl-carousel owl-theme listing_gllary_img most_viewed_listings" >
            <?php 
			foreach($most_viewed_listings as $row){ 
			$imagename = Helper::getDirectoryimage($row->id); 
			?>
            <div class="item" data-aos="zoom-in">
                <div class="strip grid">
                    <figure>
					<input class="product-wishlist" type="hidden" value="{{ $row->id }}" name="product_id" id="product_id">
					<input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
						<a href="javascript:void(0);" productid="{{ $row->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $row->id ?>)">
							<?php
							if(!in_array($row->id, $wislistdirectoryId)){ ?> <i class="fa fa-heart-o" aria-hidden="true"></i>
							<?php }else{ ?> <i class="fa fa-heart" aria-hidden="true"></i> <?php } ?>
						</a>
					<span class="featured-tag-grid">
					@if($row->feature_type == 'superfeatured') Super featured @else	Featured @endif
					</span>
					<?php if(!empty($imagename)){ ?>
						<a href="<?php echo url('listings/details', ['id' => $row->slug??'']); ?>">
                            <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}" class="img-fluid" alt="" width="400" height="266">
                        </a>
					<?php }else{ ?>
						<a href="<?php echo url('listings/details', ['id' => $row->slug??'']); ?>">
                            <img src="{{ asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg') }}" class="img-fluid" alt="" width="400" height="266">
                        </a>
					<?php } ?>
                    </figure>
					<p class="featured-footer mb-0" style="text-align: center;"> <a>{{ $row->title}}</a> </p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>