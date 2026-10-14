

	<a class="btn btn-warning btn-sm btn-block" id="tree_structure2" style="color: #fff;font-weight: 600;margin: 5px 0px;margin: -35px 0px;float: right;width: 27%;">All Categories, Sub Categories and Tags </a>
	<div class="row" style="display:none;" id="tree_structure_v2">
		<div class="col-sm-12">
			<div class="white-box">

				<div id="accordion" class="tree-stuc">
					<?php $i=1; foreach($data['parent_category'] as $parentCategory){ 
						$get_child_category = Helper::getchildCategory($parentCategory->id); ?>
					<div class="card">
						<div class="card-header area" id="heading-1">
							<h5 class="mb-0">
								<a role="button" data-toggle="collapse" href="#collapse-{{ $parentCategory->id }}"
									aria-expanded="true" aria-controls="collapse-1">{{ $i.'- '.$parentCategory->name
									}}</a>
							</h5>
						</div>
						<div id="collapse-{{ $parentCategory->id }}" class="collapse" data-parent="#accordion"
							aria-labelledby="heading-1">

							<div class="card-body">
								<?php $j='a'; 
									foreach($get_child_category as $childCategory){ 
									$get_listing_tags = Helper::getListingTags($childCategory->id); 
								?>
								<div id="accordion-{{ $childCategory->id }}">
									<div class="card">
										<div class="card-header equipamento" id="heading-1-1">
											<h5 class="mb-0">
												<?php if(count($get_listing_tags)>0){ ?>
												<a class="collapsed" role="button" data-toggle="collapse"
													href="#collapse-1-{{ $childCategory->id }}"
													aria-expanded="false" aria-controls="collapse-1-1">
													{{ $j.'. '.$childCategory->name }}
												</a>
												<?php }else{ ?>
												<span
													style="color: #768494 !important; text-decoration: none;font-size: 14px;">{{
													$j.'. '.$childCategory->name }}</span>
												<?php } ?>
											</h5>
										</div>
										<?php if(count($get_listing_tags)>0){ ?>
										<div id="collapse-1-{{ $childCategory->id }}" class="collapse"
											data-parent="#accordion-{{ $childCategory->id }}"
											aria-labelledby="heading-1-1">
											<div class="card-body">
												<div id="accordion-1-1">
													<div class="card">
														<div class="card-header ponto" id="heading-1-1-1">
															<h5 class="mb-0">
																<span>
																	<?php  
																		foreach($get_listing_tags as $tagsname){ 
																	?>
																	<a href="{{ url('listings?tags='.$tagsname->id) }}" style="color: #000;">
																		{{ $tagsname->tags }},
																	</a>
																	
																	{{-- <a href="{{ route('listingbytag',$tagsname->id) }}" style="color: #000;">
																		{{ $tagsname->tags }},
																	</a> --}}
																	<?php } ?>
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
	<hr>