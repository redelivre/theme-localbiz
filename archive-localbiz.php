<?php get_header(); ?>

<div id="main-content">
	<div id="content-area" class="clearfix">
		<div class="entry-content">
			<div id="et-boc" class="et-boc">
				<div class="et_builder_inner_content et_pb_gutters3"><?php
					if(
						isset($_REQUEST['localbiz-search-by-name']) ||
						isset($_REQUEST['localbiz-search-by-cep']) ||
						isset($_REQUEST['localbiz-search-by-city']) ||
						!empty(get_query_var('cat'))
					) {
						$categories_html = '
							<div class="et_pb_module et_pb_sidebar_0 et_pb_widget_area et_pb_bg_layout_light clearfix et_pb_widget_area_left et_pb_sidebar_no_border">
								<div id="categories-2" class="et_pb_widget widget_categories"><h4 class="widgettitle">Categorias</h4>
									<ul>
										<li class="cat-item cat-item-437"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/">BEBIDAS</a> (7)
											<ul class="children">
												<li class="cat-item cat-item-441"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/agua-mineral/">Água Mineral</a> (2)
												</li>
												<li class="cat-item cat-item-440"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/cerveja-e-chope/">Cerveja e Chope</a> (1)
												</li>
												<li class="cat-item cat-item-442"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/chas-e-energeticos/">Chás e energéticos</a> (1)
												</li>
												<li class="cat-item cat-item-439"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/destilados/">Destilados</a> (1)
												</li>
												<li class="cat-item cat-item-438"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/fermentados/">Fermentados</a> (1)
												</li>
												<li class="cat-item cat-item-446"><a href="http://localbiz.redelivre.ethymos.com.br/category/bebidas/refrigerantes/">Refrigerantes</a> (1)
												</li>
											</ul>
										</li>
										<li class="cat-item cat-item-1"><a href="http://localbiz.redelivre.ethymos.com.br/category/noticias/">Notícias</a> (2)
										</li>
									</ul>
								</div> <!-- end .et_pb_widget -->
							</div>
						';
						$image_index = 0;
						$qsearch = '';
						$image_html = '';
						if(isset($_REQUEST['localbiz-search-by-name'])) {
							if(empty(sanitize_text_field($_REQUEST['localbiz-search-by-name']))) {
								$qsearch = 'Todos os Localbiz';
							} else {
								$qsearch = '“'.sanitize_text_field($_REQUEST['localbiz-search-by-name']).'"';
							}
						} elseif(isset($_REQUEST['localbiz-search-by-city'])) {
							$qsearch = '“'.sanitize_text_field($_REQUEST['localbiz-search-by-city']).'"';
						} elseif(isset($_REQUEST['localbiz-search-by-cep'])) {
							$qsearch = '“'.sanitize_text_field($_REQUEST['localbiz-search-by-cep']).'"';
						} else {
							$qsearch = single_cat_title( '', false );
							$term = get_queried_object();
							if ( is_category() ) {
								if($term->parent == 0) {
									$imgurl = CatImage::get_category_image_url($term);
									if($imgurl !== false) {
										$image_html = '<div class="et_pb_with_border et_pb_module et_pb_image et_pb_image_'.$image_index++.' et_always_center_on_mobile" style="display: block;position: relative;float: left;margin-bottom:0;"><span class="et_pb_image_wrap has-box-shadow-overlay" style="height: 90px;width:90px;background-image:url(\''.$imgurl.'\');background-repeat: no-repeat;background-position: center;border-radius: 20px 20px 20px 35px;overflow: hidden;border-width: 0;border-color: #ffffff;box-shadow: 0px 2px 12px 0px rgba(0,0,0,0.3);margin-right:2em"><div class="box-shadow-overlay"></div></span></div>';
									}
								}
							}
						}
						global $wp_query;
						$header_text = $image_html.'[et_pb_text _builder_version="3.21.4" header_font="||||||||" header_3_font="||||||||" header_3_text_color="#a6a6a6"]<h1>'.$qsearch.'</h1>
<h3>Exibindo todos os resultados ('.$wp_query->found_posts.')</h3>[/et_pb_text]';
						
						$content = '
[et_pb_section fb_built="1" _builder_version="3.21.4" background_color="#f7f1e8" custom_margin="0px||0px||true" custom_padding="0px||0px||true"][et_pb_row use_custom_width="on" width_unit="off" _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4"]<p>[localbiz_search]</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="3.21.4" custom_padding="40px|0px|0|0px|false|false"][et_pb_row custom_padding="0px|0px|0|0px|false|false" use_custom_width="on" width_unit="off" _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.21.4"]'.$header_text.'[/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" specialty="on" use_custom_width="on" width_unit="off" padding_top_1="0px" _builder_version="3.21.4"][et_pb_column type="1_3"][et_pb_sidebar show_border="off" _builder_version="3.21.4"][/et_pb_sidebar][/et_pb_column][et_pb_column type="2_3" specialty_columns="2"]
						';
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post();
								$row = '[et_pb_row_inner use_custom_gutter="on" gutter_width="1" border_radii="|15px|15px|15px|30px" _builder_version="3.21.4" background_color="#f7f1e8" custom_padding="2em|2em|2em|2em|true|true" custom_margin="||3em" module_class_1="localbiz-card-col1" module_class_2="localbiz-card-col2" link_option_url_new_window="on" link_option_url="'.get_the_permalink().'"]';
								$image_id = get_post_meta(get_the_ID(), 'localbiz-perfil-image-id', true);
								$imgurl = get_stylesheet_directory_uri().'/img/invalid-name.svg';
								if(!empty($image_id)) {
									$imgurl = wp_get_attachment_image_src ( $image_id, 'thumbnail' );
									if(is_array($imgurl)) {
										$imgurl = $imgurl[0];
									}
								}
								$image_html = '<div class="et_pb_with_border et_pb_module et_pb_image et_pb_image_'.$image_index++.' et_always_center_on_mobile"><span class="et_pb_image_wrap has-box-shadow-overlay" style="height: 120px;width:120px;background-image:url(\''.$imgurl.'\');background-repeat: no-repeat;background-position: center;border-radius: 100% 100% 100% 100%;overflow: hidden;border-width: 4px;border-color: #ffffff;box-shadow: 0px 2px 12px 0px rgba(0,0,0,0.3);"><div class="box-shadow-overlay"></div></span></div>';
								$tel = get_post_meta(get_the_ID(), 'tel', true);
								$email = get_post_meta(get_the_ID(), 'email_localbiz', true);
								$col2 = '
[et_pb_column_inner type="1_2" saved_specialty_column_type="2_3"]'.$image_html.'[et_pb_text _builder_version="3.21.4"]<p>[localbiz_share_icons]</p>
<p><a class="localbiz-telefone-link" href="tel:'.$tel.'" target="_blank" rel="noopener noreferrer">'.$tel.'</a></p>
<p><a class="localbiz-mail-link" href="mailto:'.$email.'" target="_blank" rel="noopener noreferrer">'.$email.'</a></p>
[/et_pb_text][/et_pb_column_inner]
								';
								$title = get_the_title();
								$excerpt = get_the_excerpt();
								$endereco = get_post_meta(get_the_ID(), 'endereco', true);
								$numero = get_post_meta(get_the_ID(), 'numero', true);
								$bairro = get_post_meta(get_the_ID(), 'bairro', true);
								$complemento = get_post_meta(get_the_ID(), 'complemento', true);
								$cidade = get_post_meta(get_the_ID(), 'cidade', true);
								$estado = get_post_meta(get_the_ID(), 'estado', true);
								$address1 = '';
								$address2 = '';
								$maplink = '';
								if(!empty($endereco)) {
									$address1 = "$endereco, $numero";
									$address1_map = $address1;
									if(!empty($complemento)){
										$address1 .= ", $complemento";
									}
									if(!empty($bairro)) {
										$address1 .= ", $bairro";
										$address1_map .= ", $bairro";
									}
									$maplink = 'http://maps.google.com/?q='.urlencode($address1_map);
								}
								if(!empty($cidade)) {
									$address2 = "$cidade - $estado";
									if(empty( $maplink )) $maplink = 'http://maps.google.com/?q='.urlencode($address2);
								}
								if(!empty($endereco) && !empty($cidade) ) {
									$maplink = 'http://maps.google.com/?q='.urlencode("$endereco, $numero, $bairro".', '.$address2);
								}
								
								$col3 = '
[et_pb_column_inner type="1_2" saved_specialty_column_type="2_3"][et_pb_text _builder_version="3.21.4" header_font="||||||||" header_2_font="||||||||" header_2_text_color="#ff5500"]<h2>'.$title.'</h2>
<p style="float: left;display: block;width: 100%;">[localbiz_produtosEservicos]</p>[/et_pb_text][et_pb_text _builder_version="3.21.4"]<p>'.$excerpt.'</p>[/et_pb_text][et_pb_text _builder_version="3.21.4"]<p><a href="'.$maplink.'" target="_blank" rel="noopener noreferrer">'.$address1.'</a><br /><a href="'.$maplink.'" target="_blank" rel="noopener noreferrer">'.$address2.'</a></p>[/et_pb_text][/et_pb_column_inner]
								';
								$row .= $col2.$col3.'[/et_pb_row_inner]';
								$row = apply_filters('the_content', $row);
								$content .= $row;
							}
						}
						$content .= '[/et_pb_column][/et_pb_section]';
						echo apply_filters('the_content', $content);
					} else {
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
						//$categories = array();
						$categories = get_categories( $args );
						/*foreach ($categories_parents as $parent) {
							$args['parent'] = $parent->term_id;
							$categories = array_merge($categories, get_categories($args));
						}*/
						
						if ( count($categories) > 0 ) {
							shuffle($categories);
							$session = 10;
							echo apply_filters('the_content', '[et_pb_section fb_built="1" _builder_version="3.21.4" use_background_color_gradient="on" background_color_gradient_start="rgba(255,255,255,0.85)" background_color_gradient_end="rgba(255,255,255,0.13)" background_color_gradient_direction="90deg" background_color_gradient_start_position="20%" background_color_gradient_overlays_image="on" background_image="'.get_stylesheet_directory_uri().'/img/20191109183710_IMG_9733.jpg" border_radii="||30px|30px|" custom_margin="|3%||3%||true" custom_padding="13%||13%||true"][et_pb_row _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4"]<h1>Procurando por negócios locais?</h1>[/et_pb_text][et_pb_text _builder_version="3.21.4" text_font="||||||||" header_font="||||||||" header_5_font="||||||||" header_5_text_color="#ff5500"]<h5>Pesquise pelos negócios locais de seu interesse</h5>[/et_pb_text][/et_pb_column][localbiz_search][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="3.21.4"][et_pb_row _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4"]<h2>Explore por categorias</h2>[/et_pb_text][et_pb_text _builder_version="3.21.4" text_font="||||||||" header_font="||||||||" header_5_font="||||||||" header_5_text_color="#a6a6a6"]<h5>Escolha uma categoria de sua preferência</h5>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section]');?>
								<div class="et_pb_section et_pb_section_<?php echo $session; ?> et_section_regular">
									<div class="et_pb_row et_pb_row_<?php echo $session; ?> et_pb_row_fullwidth">
										<div class="et_pb_column et_pb_column_4_4 et_pb_column_<?php echo $session; ?> et_pb_css_mix_blend_mode_passthrough et-last-child">
											<div class="et_pb_module et_pb_gallery et_pb_gallery_<?php echo $session; ?> et_clickable et_pb_bg_layout_light  et_pb_gallery_grid localbiz-cat-gallery">
												<div class="et_pb_gallery_items et_post_gallery clearfix" data-per_page="1000">
													<?php
													foreach ($categories as $category) {
														$image_src = get_stylesheet_directory_uri().'/img/sem-imagem.gif';
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
								</div> <!-- .et_pb_section --><?php
							/*if ( function_exists( 'wp_pagenavi' ) )
								wp_pagenavi();
							else
								get_template_part( 'includes/navigation', 'index' );*/
						} else {
							get_template_part( 'includes/no-results', 'index' );
						}
					}?>
					<?php LocalBiz::get_footer(true); ?>
				</div>
			</div>
		</div> <!-- .entry-content -->
	</div> <!-- #content-area -->
</div> <!-- #main-content -->

<?php get_footer(); ?>