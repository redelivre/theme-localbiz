<?php get_header(); ?>

<div id="main-content">
		<div id="content-area" class="clearfix">
				<?php
				$args = array(
						'parent'              => 0,
						'hide_empty'          => 0,
						'order'               => 'ASC',
						'orderby'             => 'name',
						'taxonomy'            => 'category',
						'meta_query' => array(
								array(
										'key'     => 'category-localbiz',
										'value'   => 'N',
										'compare' => '!='
								)
						)
				);
				$categories = array();
				$categories_parents = get_categories( $args );
				foreach ($categories_parents as $parent) {
					$args['parent'] = $parent->term_id;
					$categories = array_merge($categories, get_categories($args));
				}
				if ( count($categories) > 0 ) {
					shuffle($categories);?>
					<div class="entry-content">
						<div id="et-boc" class="et-boc">
							<div class="et_builder_inner_content et_pb_gutters3">
								<div class="et_pb_section et_pb_section_0 et_section_regular">
									<div class="et_pb_row et_pb_row_0 et_pb_row_fullwidth">
										<div class="et_pb_column et_pb_column_4_4 et_pb_column_0    et_pb_css_mix_blend_mode_passthrough et-last-child">
											<div class="et_pb_module et_pb_gallery et_pb_gallery_0 et_clickable et_pb_bg_layout_light  et_pb_gallery_grid">
												<div class="et_pb_gallery_items et_post_gallery clearfix" data-per_page="1000">
													<?php
													foreach ($categories as $category) {
														$image_src = 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQA87sNsd91yyPwllkqYjwRDOVIMWCIjVTraxAoP3IXlB3HKIbZ';
														$image_id = get_term_meta ( $category -> term_id, 'category-image-id', true );
														if($image_id > 0) {
															$image_array = wp_get_attachment_image_src($image_id);
															$image_src = $image_array[0];
														}
														?>
														<div class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light" style="display: block;">
															<div class="landscape">
																<a href="<?php echo get_category_link($category); ?>" title="<?php echo $category->name; ?>">
																	<img src="<?php echo $image_src; ?>" alt="<?php echo $category->name; ?>">
																	<h3 class="et_pb_gallery_title"><?php echo $category->name; ?></h3>
																</a>
															</div>
														</div><?php
													}
													?>
												</div><!-- .et_pb_gallery_items -->
											</div><!-- .et_pb_gallery -->
										</div> <!-- .et_pb_column -->
									</div> <!-- .et_pb_row -->
								</div> <!-- .et_pb_section -->			
							</div>
						</div>
					</div> <!-- .entry-content --><?php
					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				} else {
					get_template_part( 'includes/no-results', 'index' );
				}
				?>
		</div> <!-- #content-area -->
</div> <!-- #main-content -->

<?php get_footer(); ?>