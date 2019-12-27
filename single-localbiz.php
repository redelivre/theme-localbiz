<?php get_header(); ?>

<div id="main-content">
	<div id="content-area" class="clearfix">
		<div class="entry-content">
			<div id="et-boc" class="et-boc">
				<div class="et_builder_inner_content et_pb_gutters3"><?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							$image_id = get_post_meta(get_the_ID(), 'localbiz-perfil-image-id', true);
							$imgurl = get_stylesheet_directory_uri().'/img/invalid-name.svg';
							if(!empty($image_id)) {
								$imgurl = wp_get_attachment_image_src ( $image_id, 'thumbnail' );
								if(is_array($imgurl)) {
									$imgurl = $imgurl[0];
								}
							}
							$tel = get_post_meta(get_the_ID(), 'tel', true);
							$email = get_post_meta(get_the_ID(), 'email_localbiz', true);
							$site = get_post_meta(get_the_ID(), 'site', true);
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
								if(!empty($complemento)) $address1 .= ", $complemento";
								if(!empty($bairro)) $address1 .= ", $bairro";
								$maplink = $address1;
							}
							if(!empty($cidade)) {
								$address2 = "$cidade - $estado";
								if(empty( $maplink )) $maplink = $address2;
							}
							if(!empty($endereco) && !empty($cidade) ) {
								$maplink = "$endereco, $numero, $bairro".', '.$address2;
							}
							$instagram_clientID = false; // TODO next version
							$content =
								'[et_pb_section fb_built="1" _builder_version="3.21.4" background_color="#f7f1e8" custom_margin="0px||0px||true" custom_padding="0px||0px||true"][et_pb_row use_custom_width="on" width_unit="off" _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4"]<p>[localbiz_search]</p>[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="3.21.4" background_image="'.(get_stylesheet_directory_uri().'/img/localbiz_single_top@2x.jpg').'" custom_margin="0px||0px||true" custom_padding="0|0px|0|0px|false|false" custom_css_main_element="height:100px;"][et_pb_row _builder_version="3.21.4" use_custom_width="on" width_unit="off" custom_width_percent="72%"][et_pb_column type="4_4" _builder_version="3.21.4"][et_pb_image src="'.$imgurl.'" _builder_version="3.21.4" border_radii="on|100%|100%|100%|100%" border_width_all="4px" border_color_all="#ffffff" box_shadow_style="preset1" box_shadow_blur="12px" max_width="120px" custom_margin="20px||"][/et_pb_image][/et_pb_column][/et_pb_row][/et_pb_section][et_pb_section fb_built="1" _builder_version="3.21.4" custom_padding="0px||"][et_pb_row _builder_version="3.21.4" custom_margin="0px||" custom_padding="0px||"][et_pb_column type="3_5" _builder_version="3.21.4"][et_pb_post_title meta="off" featured_image="off" _builder_version="3.21.4" title_font="||||||||" title_text_color="#ff5500" title_font_size="42px"][/et_pb_post_title][/et_pb_column][et_pb_column type="2_5" _builder_version="3.21.4"][/et_pb_column][/et_pb_row][et_pb_row _builder_version="3.21.4"][et_pb_column type="3_5" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px"]<p>Produtos e Serviços</p>
		<p>[localbiz_produtosEservicos]</p><p>&nbsp;</p>[/et_pb_text][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px" custom_padding="||0px" custom_margin="||1em"]<p>Sobre</p>[/et_pb_text][et_pb_text _builder_version="3.21.4"]'.get_the_content().'[/et_pb_text][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px"]<p>Compartilhe</p>[/et_pb_text][et_pb_text _builder_version="3.21.4"]<pre><code>[addtoany]</code></pre>[/et_pb_text][/et_pb_column][et_pb_column type="2_5" _builder_version="3.21.4"][et_pb_text _builder_version="3.21.4"]<p>[localbiz_share_icons site="no"]</p><p>&nbsp;</p>
		'.(!empty($site) ? '<p><a href="#fone" target="_blank" rel="noopener noreferrer" class="localbiz-single-contact-link"><img src="'.(get_stylesheet_directory_uri().'/img/icon-site.svg').'" alt="" class="alignnone size-full localbiz-single-contact-img" width="24" height="24" /></a> '.$site.'</p>' : '').'
		'.(!empty($tel) ? '<p><a href="#fone" target="_blank" rel="noopener noreferrer" class="localbiz-single-contact-link"><img src="'.(get_stylesheet_directory_uri().'/img/icon-telefone.svg').'" alt="" class="alignnone size-full localbiz-single-contact-img" width="24" height="24" /> '.$tel.'</a></p>' : '').'
		'.(!empty($email) ? '<p><a href="#fone" target="_blank" rel="noopener noreferrer" class="localbiz-single-contact-link"><img src="'.(get_stylesheet_directory_uri().'/img/icon-email.svg').'" alt="" class="alignnone size-full localbiz-single-contact-img" width="24" height="24" /></a> <a href="#email" target="_blank" rel="noopener noreferrer">'.$email.'</a></p>' : '').'
		[/et_pb_text][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px"]<p>Localização</p>[/et_pb_text][et_pb_map _builder_version="3.21.4" address="'.$maplink.'"][et_pb_map_pin title="'.get_the_title().'" pin_address="'.$maplink.'" _builder_version="3.21.4"][/et_pb_map_pin][/et_pb_map][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px"]<p>Hashtags</p>[/et_pb_text][et_pb_text _builder_version="3.21.4"]<p>[localbiz_hashtags]</p>[/et_pb_text]'.($instagram_clientID ? '[et_pb_text _builder_version="3.21.4" text_font="||||||||" text_text_color="#ff5500" text_font_size="16px"]<p>Fotos do Instagram</p>[/et_pb_text]' : '').'[/et_pb_column][/et_pb_row][/et_pb_section]
							';
							echo apply_filters('the_content', $content);
						}
					} else {
						get_template_part( 'includes/no-results', 'index' );
					}?>
				</div>
			</div>
		</div> <!-- .entry-content -->
	</div> <!-- #content-area -->
</div> <!-- #main-content -->

<?php get_footer(); ?>