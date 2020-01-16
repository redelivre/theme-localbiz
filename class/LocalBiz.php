<?php
class LocalBiz {
	public function __construct() {
		add_action('init', array($this, 'init'));
		self::$instance = $this;
	}
	
	private static $instance = null;
	
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			new self();
		}
		return self::$instance;
	}
	
	public function init() {
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
		$this->add_custom_post();
		$this->create_terms();
		add_action( 'wp_ajax_locabiz_category_selected_action', array($this, 'category_selected_action') );
		add_action( 'wp_ajax_nopriv_locabiz_category_selected_action', array($this, 'category_selected_action') );//for not 'logged in' users.
		add_action( 'save_post', array($this, 'save_post'), 10, 2 );
		add_filter( 'body_class', array($this, 'custom_body_class') );
		add_filter( 'wp_title', array($this, 'wp_title'), 10, 1 );
		add_filter( 'pre_get_document_title', array($this, 'wp_title'), 10, 1 );
		add_filter( 'ajax_query_attachments_args', array($this, 'ajax_query_attachments_args') );
		$this->add_upload_files_cap();
		add_action( 'pre_get_posts', array($this, 'search_query'));
		add_shortcode('localbiz_search', array($this, 'search_shortcode'));
		add_shortcode('localbiz_share_icons', array($this, 'share_shortcode'));
		add_shortcode('localbiz_produtosEservicos', array($this, 'produtosEservicos_shortcode'));
		add_shortcode('localbiz_hashtags', array($this, 'tags_shortcode'));
		add_filter('term_link', array($this, 'category_link') );
		add_filter('widget_categories_args', array($this, 'widget_categories_args') );
		add_filter('widget_title', array($this, 'widget_title'), 10, 3 );
		add_filter('mime_types', array($this, 'mime_types'));
	}
	public function enqueue_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	}
	public function add_custom_post()
	{
		$labels = array
		(
				'name' => __('localbiz','localbiz'),
				'singular_name' => __('localbiz','localbiz'),
				'add_new' => __('Adicionar Nova','localbiz'),
				'add_new_item' => __('Adicionar nova localbiz ','localbiz'),
				'edit_item' => __('Editar localbiz','localbiz'),
				'new_item' => __('Nova localbiz','localbiz'),
				'view_item' => __('Visualizar localbiz','localbiz'),
				'search_items' => __('Procurar localbiz','localbiz'),
				'not_found' =>  __('Nenhuma localbiz localizada','localbiz'),
				'not_found_in_trash' => __('Nenhuma localbiz localizada na lixeira','localbiz'),
				'parent_item_colon' => '',
				'menu_name' => __('localbiz','localbiz')
				
		);
		
		$args = array
		(
				'label' => __('localbiz','localbiz'),
				'labels' => $labels,
				'description' => __('localbiz local','localbiz'),
				'public' => true,
				'publicly_queryable' => true, // public
				//'exclude_from_search' => '', // public
				'show_ui' => true, // public
				'show_in_menu' => true,
				'show_in_rest' => true,
				'menu_position' => 5,
				// 'menu_icon' => '',
				//'capability_type' => array('localbiz','localbiz'),
				'map_meta_cap' => true,
				'hierarchical' => false,
				'supports' => array('title', 'editor', 'author', 'excerpt', 'trackbacks', 'revisions', 'comments', 'thumbnail'),
				'register_meta_box_cb' => array($this, 'localbiz_custom_meta'), // função para chamar box na edição
				'taxonomies' => array('post_tag', 'category'), // Taxionomias já existentes relaciondas, vamos criar e registrar na sequência
				'permalink_epmask' => 'EP_PERMALINK ',
				'has_archive' => true, // Opção de arquivamento por slug
				'rewrite' => true,
				'query_var' => true,
				'can_export' => true
		);
		
		register_post_type("localbiz", $args);
	}
	
	public function add_custom_taxonomy() {
		$labels = array
		(
			'name' => __('Regiões', 'localbiz'),
			'singular_name' => __('Região', 'localbiz'),
			'search_items' => __('Procurar por Regiões','localbiz'),
			'all_items' => __('Todas os Regiões','localbiz'),
			'parent_item' => __( 'Região Pai','localbiz'),
			'parent_item_colon' => __( 'Região Pai:','localbiz'),
			'edit_item' => __('Editar Região','localbiz'),
			'update_item' => __('Atualizar uma Região','localbiz'),
			'add_new_item' => __('Adicionar Nova Região','localbiz'),
			'add_new' => __('Adicionar Nova','localbiz'),
			'new_item_name' => __('Nova Região','localbiz'),
			'view_item' => __('Visualizar Região','localbiz'),
			'not_found' =>  __('Nenhuma Região localizada','localbiz'),
			'not_found_in_trash' => __('Nenhuma Região localizada na lixeira','localbiz'),
			'menu_name' => __('Regiões','localbiz')
		);
		
		$args = array
		(
			'label' => __('Regiões','localbiz'),
			'labels' => $labels,
			'public' => true,
			'capabilities' => array(
					'manage_terms' => 'manage_regiao_term',
					'edit_terms' => 'edit_regiao_term',
					'delete_terms' => 'delete_regiao_term',
					'assign_terms' => 'assign_regiao_term'
			),
			//'show_in_nav_menus' => true, // Public
			// 'show_ui' => '', // Public
			'hierarchical' => true,
			//'update_count_callback' => '', //Contar objetos associados
			'rewrite' => true,
			//'query_var' => '',
			//'_builtin' => '' // Core
		);
	
		register_taxonomy('regiao', array('localbiz'), $args);
	}
	public function localbiz_custom_meta() {
		add_meta_box('LocalBiz', 'LocalBiz', array($this, 'localbiz_meta_box'), 'localbiz');
	}
	
	public function localbiz_meta_box() {
		global $post;
		$post_id = $post->ID;
		wp_nonce_field( 'update-localbiz-metas', 'update-localbiz-metas' ); ?>
		<div class="row">
			<div class="title">
				Nome do Responsável
			</div>
		</div>
		<div class="row">
			<input type="text" name="nome-responsavel" class="Rectangle" value="<?php echo get_post_meta($post_id, 'nome-responsavel', true); ?>" required />
		</div>
		<div class="row">
			<div class="title">
				CEP do seu negócio
			</div>
		</div>
		<div class="row">
			<input type="text" id="cep" name="cep" class="Rectangle" value="<?php echo get_post_meta($post_id, 'cep', true); ?>" required />
		</div>
		<div class="row">
			<div class="title title-endereco">
				Endereço
			</div>
		</div>
		<div class="row">
			<input name="endereco" id="endereco"  class="Rectangle" data-autocomplete-address value="<?php echo get_post_meta($post_id, 'endereco', true); ?>" required>
		</div>
		<div class="row">
			<div class="title-bairro">
				Bairro
			</div>
			<div class="title-numero">
				Número
			</div>
		</div>
		<div class="row">
			<input name="bairro" id="bairro" class="Rectangle-bairro" data-autocomplete-neighborhood value="<?php echo get_post_meta($post_id, 'bairro', true); ?>" required>
			<input type="number" name="numero" id="numero" class="Rectangle-numero" value="<?php echo get_post_meta($post_id, 'numero', true); ?>" required >
		</div>
		<div class="row">
			<div class="title title-complemento">
				Complemento
			</div>
		</div>
		<div class="row">
			<input name="complemento" id="complemento" class="Rectangle" value="<?php echo get_post_meta($post_id, 'complemento', true); ?>"  >
		</div>
		<div class="row">
			<div class="title-estado">
				Estado
			</div>
			<div class="title-cidade">
				Cidade
			</div>
		</div>
		<div class="row">
			<input name="estado" id="estado" class="Rectangle-estado" data-autocomplete-state value="<?php echo get_post_meta($post_id, 'estado', true); ?>" required>
			<input name="cidade" id="cidade" class="Rectangle-cidade" data-autocomplete-city value="<?php echo get_post_meta($post_id, 'cidade', true); ?>" required>
		</div>
		<div class="row">
			<div class="Seu-negcio-possui-f">
				Seu negócio possui fins lucrativos?
			</div>
		</div>
		<div class="row">
			<input type="radio" id="fins-sim" name="fins" value="sim" <?php echo get_post_meta($post_id, 'fins', true) == 'sim' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="fins-sim">Sim, possui fins lucrativos.</label>
		</div>
		<div class="row">
			<input type="radio" id="fins-nao" name="fins" value="nao" <?php echo get_post_meta($post_id, 'fins', true) == 'nao' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="fins-nao">Não possui fins lucrativos.</label>
		</div>
		<div class="row">
			<div class="Qual-o-tamanho-do">
				Qual é o tamanho do seu negócio?
			</div>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-individual" name="tamanho" value="Individual" <?php echo get_post_meta($post_id, 'tamanho', true) == 'Individual' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-individual">Individual</label>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-4" name="tamanho" value="4" <?php echo get_post_meta($post_id, 'tamanho', true) == '4' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-4">1 - 4 Funcionários</label>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-5" name="tamanho" value="5" <?php echo get_post_meta($post_id, 'tamanho', true) == '5' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-5">5 - 10 Funcionários</label>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-11" name="tamanho" value="11" <?php echo get_post_meta($post_id, 'tamanho', true) == '11' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-11">11 - 20 Funcionários</label>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-21" name="tamanho" value="21" <?php echo get_post_meta($post_id, 'tamanho', true) == '21' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-21">21 - 50 Funcionários</label>
		</div>
		<div class="row">
			<input type="radio" id="tamanho-mais" name="tamanho" value="mais" <?php echo get_post_meta($post_id, 'tamanho', true) == 'mais' ? 'checked="checked"' : ''; ?> />
				<label class="Lorem-Ipsum" for="tamanho-mais">Mais de 50 Funcionários</label>
		</div>
		<div class="row">
			<div class="title">CNPJ</div>
		</div>
		<div class="row">
			<input name="cnpj" id="cnpj" class="Rectangle"
				value="<?php echo get_post_meta($post_id, 'cnpj', true); ?>"
				required="required">
		</div>
		<div class="row">
			<div class="Check-box-vazio">
				<input type="checkbox" name="tem_cnpj" id="tem_cnpj" class=""
					value="N" <?php echo get_post_meta($post_id, 'tem_cnpj', true) == 'N' ? 'checked="checked"' : ''; ?>> <label for="tem_cnpj">Ainda não possuo CNPJ</label>
			</div>
			<input type="checkbox" name="tem_cnpj" id="tem_cnpj_sim" class="hide" style="display: none;"
				value="S" <?php echo get_post_meta($post_id, 'tem_cnpj', true) == 'S' ? 'checked="checked"' : ''; ?>>
		</div>
		<div class="row">
			<div class="title">Razão Social</div>
		</div>
		<div class="row">
			<input name="razao" id="razao" class="Rectangle"
				value="<?php echo get_post_meta($post_id, 'razao', true); ?>"
				required="required">
		</div>
		<div class="row">
			<div class="form-field term-group-wrap">
				<div>
					<div class="row aleft image-title"><label class=""><?php _e( 'Imagem do perfil', 'localbiz' ); ?></label></div>
					<?php $image_id = get_post_meta ( $post_id, 'localbiz-perfil-image-id', true ); ?>
					<input type="hidden" id="localbiz-perfil-image-id"
						name="localbiz-perfil-image-id" value="<?php echo $image_id; ?>">
					<?php
					$imgurl = false;
					if ( $image_id ) {
						$imgurl = wp_get_attachment_image_src ( $image_id, 'thumbnail' );
						if($imgurl) {
							$imgurl = $imgurl[0];
						}
					} 
					if($imgurl == false){
						$imgurl = get_stylesheet_directory_uri().'/img/invalid-name.svg';
					} ?>
					<div id="localbiz-perfil-image-wrapper">
						<div class="img-Oval" style="background-image: url('<?php echo $imgurl; ?>');background-size: auto;width: 120px;height: 120px;background-position: center;">
						</div>
					</div>
					<div class="row col-1">
						<div class="row marginb05">
							<input
								type="button"
								class="button button-secondary ct_tax_media_button Title-Copy-4"
								id="ct_tax_media_button"
								name="ct_tax_media_button"
								value="<?php _e( 'Carregar Imagem', 'localbiz' ); ?>"
							/>
						</div>
						<div class="row">
							<input
								type="button"
								class="button button-secondary ct_tax_media_remove Title-Copy-4"
								id="ct_tax_media_remove"
								name="ct_tax_media_remove"
								value="<?php _e( 'Remover Imagem', 'localbiz' ); ?>"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="subtitle-2">
				Contato do seu negócio
			</div>
		</div>
		<div class="row">
			<div class="title">
				Telefone do negócio
			</div>
		</div>
		<div class="row">
			<input type="tel" name="tel" id="tel"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'tel', true); ?>" >
		</div>
		<div class="row">
			<div class="title">
				Email do Negócio
			</div>
		</div>
		<div class="row">
			<input type="email" name="email_localbiz" id="email_localbiz"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'email_localbiz', true); ?>" >
		</div>
		<div class="row">
			<div class="title">
				Site do negócio
			</div>
		</div>
		<div class="row">
			<input type="url" name="site" id="site"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'site', true); ?>" >
		</div>
		<div class="row">
			<div class="title">
				Instagram do negócio
			</div>
		</div>
		<div class="row">
			<input name="insta" id="insta"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'insta', true); ?>" >
		</div>
		<div class="row">
			<div class="title">
				Facebook do negócio
			</div>
		</div>
		<div class="row">
			<input name="facebook" id="facebook"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'facebook', true); ?>" >
		</div>
		<div class="row">
			<div class="title">
				Linkedin do negócio
			</div>
		</div>
		<div class="row">
			<input name="linkedin" id="linkedin"  class="Rectangle" value="<?php echo get_post_meta($post_id, 'linkedin', true); ?>" >
		</div>
		<?php
	}
	public function admin_enqueue_scripts($hook) {
		if ( 'post.php' != $hook ) {
			return;
		}
		if( 'localbiz' != get_post_type() ) {
			return;
		}
		$this->enqueue_scripts();
	}
	public function enqueue_scripts() {
		wp_enqueue_script ( 'cepjs', get_stylesheet_directory_uri() . '/js/jquery.autocomplete-address.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('mask', get_stylesheet_directory_uri() . '/js/jquery.mask.min.js', array('jquery'), '', true);
		wp_enqueue_script('localbiz-registro', get_stylesheet_directory_uri() . '/js/registro-de-usuario.js', array('jquery'), '', true);
		if(is_user_logged_in() && isset($_REQUEST['estagio']) && $_REQUEST['estagio'] == 6) {
			wp_enqueue_media(array('post' => $_REQUEST['post_id']));
			wp_enqueue_script('tag-it', get_stylesheet_directory_uri() . '/js/tag-it.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-autocomplete'), '', true);
		}
		wp_localize_script( 'localbiz-registro', 'localbiz', $this->localize_vars() );
		wp_enqueue_style('tag-it', get_stylesheet_directory_uri() . '/css/jquery.tagit.css');
		wp_enqueue_style('tag-it-theme', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css');
	}
	
	public function localize_vars() {
		return array(
			'stylesheet_directory' => get_stylesheet_directory_uri()
		);
	}
	
	public static function check_post_owner($post_id) {
		$post_id = sanitize_text_field($post_id);
		$post_tmp = get_post($post_id);
		if(get_current_user_id() == $post_tmp->post_author && $post_tmp->post_type == 'localbiz') {
			return true;
		}
		return false;
	}
	
	public function create_terms() {
		if(!term_exists('Outros', 'category') ) {
			if(file_exists(get_stylesheet_directory()."/categorias.csv")) {
				//echo "<br/><br/><br/><br/><br/><br/><br/><br/><pre>";
				$file = fopen(get_stylesheet_directory()."/categorias.csv", 'r');
				$new_cat = null;
				while (($line = fgetcsv($file, null,';')) !== FALSE) {
					if(!empty($line[0])) {
						$new_cat = array( );
						$cat_id = wp_insert_term($line[0], 'category', $new_cat);
						if($cat_id instanceof WP_Error) wp_die($cat_id);
						update_term_meta ( $cat_id, 'category-localbiz', 'S' );
					}
					if(!empty($line[1]) && !is_null($new_cat)) {
						$new_subcat = array( 'parent' => $cat_id['term_id'] );
						$subcat_id = wp_insert_term($line[1], 'category', $new_subcat);
						if($subcat_id instanceof WP_Error) wp_die($subcat_id);
						update_term_meta ( $subcat_id, 'category-localbiz', 'S' );
					}
				}
				fclose($file);
			}
		}
		$labels = array
		(
				'name' => __('Produtos e Serviços', 'delibera'),
				'singular_name' => __('Produto ou Serviço', 'delibera'),
				'search_items' => __('Procurar por Produtos e Serviços','delibera'),
				'all_items' => __('Todos os Produtos e Serviços','delibera'),
				'parent_item' => __( 'Produto ou Serviço Pai','delibera'),
				'parent_item_colon' => __( 'Produto ou Serviço Pai:','delibera'),
				'edit_item' => __('Editar Produto ou Serviço','delibera'),
				'update_item' => __('Atualizar um Produto ou Serviço','delibera'),
				'add_new_item' => __('Adicionar Novo Produto ou Serviço','delibera'),
				'add_new' => __('Adicionar Novo','delibera'),
				'new_item_name' => __('Novo Produto ou Serviço','delibera'),
				'view_item' => __('Visualizar Produto ou Serviço','delibera'),
				'not_found' =>  __('Nenhum Produto ou Serviço localizado','delibera'),
				'not_found_in_trash' => __('Nenhum Produto ou Serviço localizado na lixeira','delibera'),
				'menu_name' => __('Produtos e Serviços','delibera')
		);
		
		$args = array
		(
				'label' => __('Produtos e Serviços','delibera'),
				'labels' => $labels,
				'public' => true,
				'capabilities' => array(
						'upload_files'
				),
				//'show_in_nav_menus' => true, // Public
				// 'show_ui' => '', // Public
				'hierarchical' => true,
				//'update_count_callback' => '', //Contar objetos associados
				'rewrite' => true,
				//'query_var' => '',
				//'_builtin' => '' // Core
		);
		
		register_taxonomy('produtoservico', array('localbiz'), $args);
	}
	
	public function wp_title(string $title) {
		if(get_query_var('custom_user_register')) {
			return 'LocalBiz: Cadastro';
		}
		return $title;
	}
	
	public static function category_subcategory_select_form() { ?>
		<div class="row">
			<div class="title">
				Categoria
			</div>
		</div>
		<div class="row">
			<?php $cat_dd_args = array(
					'id' => 'cat',
					'name' => 'cat',
					'class' => 'Rectangle',
					'show_option_none' => 'Selecione uma categoria',
					'option_none_value' => '',
					'orderby' => 'name',
					'depth' => '1',
					'hierarchical' => '1',
					'hide_empty' => '0',
					'value_field' => 'term_id',
					'required' => true,
					'meta_query' => array(
							array( 
									'key'     => 'category-localbiz',
									'value'   => 'N',
									'compare' => '!='
							)
					)
			); ?>
        	<div id="parent_cat_div"><?php wp_dropdown_categories($cat_dd_args); ?></div>
        </div>
        <div class="row">
			<div class="title">
				Sub Categoria
			</div>
		</div>
        <div class="row">
        	<div id="sub_cat_div"><select name="subcat" class="Rectangle" id="subcat" disabled="disabled"><option value="-2">Primeiro selecione uma categoria</option></select></div>
        </div><?php
	}

	public function category_selected_action() {
		if ( isset($_POST['parent_cat_ID']) )
	    {
	    	$parent_cat_ID = sanitize_text_field($_POST['parent_cat_ID']);
	        $has_children = get_categories("hide_empty=0&parent=$parent_cat_ID");
	        if ( $has_children ) {
	        	$subcat_dd_args = array(
	        			'id' => 'subcat',
	        			'name' => 'subcat',
	        			'class' => 'Rectangle',
	        			'show_option_none' => 'Selecione uma sub categoria',
	        			'option_none_value' => '',
	        			'orderby' => 'name',
	        			'depth' => '1',
	        			'hierarchical' => '1',
	        			'hide_empty' => '0',
	        			'value_field' => 'term_id',
	        			'parent' => $parent_cat_ID,
	        			'meta_query' => array(
	        					array(
		        					'key'     => 'category-localbiz',
		        					'value'   => 'N',
		        					'compare' => '!='
	        					)
	        			)
	        	);
	        	wp_dropdown_categories($subcat_dd_args);
	        } else {
	            ?><select name="subcat" class="Rectangle" id="subcat" disabled="disabled"><option value="-1">Sem sub categorias</option></select><?php
	        }
	        die();
	    } // end if
	}
	
	public static function display_error() {
		if(isset($_REQUEST['error'])) : ?>
			<div class="row error">
				<span class="title"><?php _e(sanitize_text_field($_REQUEST['error'])); ?></span>
				<?php if(isset($_REQUEST['WP_Error']) && WP_DEBUG) : ?>
					<pre><?php var_dump($_REQUEST['WP_Error']); ?></pre>
				<?php endif; ?>
			</div>
		<?php endif;
	}
	
	/*
	 * Fix to new created meta field
	 */
	public static function update_cat_default_metas() {
		if(file_exists(get_stylesheet_directory()."/categorias.csv")) {
			$file = fopen(get_stylesheet_directory()."/categorias.csv", 'r');
			while (($line = fgetcsv($file, null,';')) !== FALSE) {
				if(!empty($line[0])) {
					$cat = get_term_by('slug', sanitize_title($line[0]), 'category');
					if($cat instanceof WP_Error) wp_die('get_term Error');
					if($cat instanceof WP_Term)	update_term_meta ( $cat->term_id, 'category-localbiz', 'S' );
					elseif(WP_DEBUG) {
						echo '<pre>';
						var_dump($cat);
						echo $line[0];
						echo '</pre>';
					}
				}
				if(!empty($line[1])) {
					$cat = get_term_by('slug', sanitize_title($line[1]), 'category');
					if($cat instanceof WP_Error) wp_die('get_term Error');
					if($cat instanceof WP_Term)	update_term_meta ( $cat->term_id, 'category-localbiz', 'S' );
					elseif(WP_DEBUG) {
						echo '<pre>';
						var_dump($cat);
						echo $line[1];
						echo '</pre>';
					}
				}
			}
			fclose($file);
			return true;
		}
		return false;
	}
	
	/**
	 * Save the metabox data
	 */
	public function save_post( $post_id, $post ) {
		if ( ! current_user_can( 'edit_locabiz', $post_id ) || get_post_type($post) != 'localbiz' ) {
			return $post_id;
		}
		if ( ! isset( $_POST['update-localbiz-metas'] ) || ! wp_verify_nonce( $_POST['update-localbiz-metas'], 'update-localbiz-metas' ) ) {
			return $post_id;
		}
		update_post_meta($post_id, 'nome-responsavel', sanitize_title($_POST['nome-responsavel']));
		update_post_meta($post_id, 'cep', sanitize_text_field($_POST['cep']));
		update_post_meta($post_id, 'endereco', sanitize_text_field($_POST['endereco']));
		update_post_meta($post_id, 'bairro', sanitize_text_field($_POST['bairro']));
		update_post_meta($post_id, 'numero', sanitize_text_field($_POST['numero']));
		update_post_meta($post_id, 'complemento', sanitize_text_field($_POST['complemento']));
		update_post_meta($post_id, 'estado', sanitize_text_field($_POST['estado']));
		update_post_meta($post_id, 'cidade', sanitize_text_field($_POST['cidade']));
		update_post_meta($post_id, 'fins', sanitize_text_field($_POST['fins']));
		update_post_meta($post_id, 'tamanho', sanitize_text_field($_POST['tamanho']));
		$tem_cnpj = sanitize_text_field($_POST['tem_cnpj']);
		update_post_meta($post_id, 'tem_cnpj', $tem_cnpj);
		if($tem_cnpj == 'S') {
			update_post_meta($post_id, 'cnpj', sanitize_text_field($_POST['cnpj']));
		}
		update_post_meta($post_id, 'razao', sanitize_text_field($_POST['razao']));
		
		if(isset($_REQUEST['localbiz-perfil-image-id']) && ! empty($_REQUEST['localbiz-perfil-image-id']))
			update_post_meta($post_id, 'localbiz-perfil-image-id', sanitize_text_field($_REQUEST['localbiz-perfil-image-id']));
		if(isset($_REQUEST['tel']) && ! empty($_REQUEST['tel']))
			update_post_meta($post_id, 'tel', sanitize_text_field($_REQUEST['tel']));
		if(isset($_REQUEST['email_localbiz']) && ! empty($_REQUEST['email_localbiz']))
			update_post_meta($post_id, 'email_localbiz', sanitize_text_field($_REQUEST['email_localbiz']));
		if(isset($_REQUEST['site']) && ! empty($_REQUEST['site']))
			update_post_meta($post_id, 'site', sanitize_text_field($_REQUEST['site']));
		if(isset($_REQUEST['insta']) && ! empty($_REQUEST['insta']))
			update_post_meta($post_id, 'insta', sanitize_text_field($_REQUEST['insta']));
		if(isset($_REQUEST['facebook']) && ! empty($_REQUEST['facebook']))
			update_post_meta($post_id, 'facebook', sanitize_text_field($_REQUEST['facebook']));
		if(isset($_REQUEST['linkedin']) && ! empty($_REQUEST['linkedin']))
			update_post_meta($post_id, 'linkedin', sanitize_text_field($_REQUEST['linkedin']));
	}
	
	public function custom_body_class($classes) {
		if(get_query_var('custom_user_register') || strpos($_SERVER['REQUEST_URI'], '/registro-de-usuario/' !== false ) ){
			$classes[] = 'localbiz-custom-register';
		}
		return $classes;
	}
	
	public function ajax_query_attachments_args( $query ) {
		$user_id = get_current_user_id();
		if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') ) {
			$query['author'] = $user_id;
		}
		return $query;
	}
	public function add_upload_files_cap() {
		$role = get_role( 'subscriber' ); //The role you want to grant the capability
		$role->add_cap( 'upload_files' );
	}
	public function search_shortcode($atts) {
		$ops = shortcode_atts( array(
				//'foo' => 'something',
				//'bar' => 'something else',
		), $atts );
		$cep = '';
		$city = '';
		$qsearch = '';
		if(isset($_REQUEST['localbiz-search-by-name'])) $qsearch = sanitize_text_field($_REQUEST['localbiz-search-by-name']);
		if(isset($_REQUEST['localbiz-search-by-city'])) $city = sanitize_text_field($_REQUEST['localbiz-search-by-city']);
		if(isset($_REQUEST['localbiz-search-by-cep'])) $cep = sanitize_text_field($_REQUEST['localbiz-search-by-cep']);
		$cidades = LocalBiz::get_meta_values('cidade', 'localbiz', false);
		$cidades_options = '';
		$has_city = false;
		foreach ($cidades as $cidade) {
			if(!empty($cidade)) {
				$selected = '';
				if(!empty($cidade) && mb_strtolower($cidade) == mb_strtolower($city)) {
					$selected = 'selected="selected"';
					$has_city = true;
				}
				$cidades_options .= '<option value="'.$cidade.'" '.$selected.'>'.$cidade.'</option>';
			}
		}
		$search ='
			<form class="localbiz-search-Field-form" action="/localbiz/">
				<input type="hidden" name="post_type" value="'.(isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'localbiz').'" />
				<div class="localbiz-search-Field">
					<span class="localbiz-search-by-name">
						<input type="text" name="localbiz-search-by-name" class="localbiz-search-by-name" placeholder="Pesquise por nome, categoria, produtos…" value="'.$qsearch.'"/>
					</span>
					<select name="localbiz-search-by-city" class="localbiz-search-by-city">
						<option value="" disabled '.($has_city ? '' : 'selected').'>Cidade</option>
						'.$cidades_options.'
					</select>
					<input type="text" name="localbiz-search-by-cep" class="localbiz-search-by-cep" placeholder="CEP" value="'.$cep.'"/>
					<button type="submit" class="localbiz-search-button">Pesquisar</button>
				</div>
			</form>';
		return $search;
	}
	
	public function fix_url($url, $https = true) {
		$prefix = $https ? 'https://' : 'http://';
		$scheme = parse_url($url, PHP_URL_SCHEME);
		if (empty($scheme)) {
			$url = $prefix . ltrim($url, '/');
		}
		return $url;
	}
	
	public function share_shortcode($atts) {
		$ops = shortcode_atts( array(
				'site' => 'yes',
		), $atts );
		$facebook = $this->fix_url(get_post_meta(get_the_ID(), 'facebook', true));
		
		$insta = $this->fix_url(get_post_meta(get_the_ID(), 'insta', true));
		$linkedin = $this->fix_url(get_post_meta(get_the_ID(), 'linkedin', true));
		$site = get_post_meta(get_the_ID(), 'site', true);
		$html = '<div class="localbiz-share-icons">';
		if(!empty($facebook))
			$html .= '<a href="'.$facebook.'" target="_blank"><img src="'.get_stylesheet_directory_uri().'/img/icon-facebook.svg" class="localbiz-share icon-facebook"></a>';
		if(!empty($insta))
			$html .= '<a href="'.$insta.'" target="_blank"><img src="'.get_stylesheet_directory_uri().'/img/icon-insta.svg"	class="localbiz-share icon-insta"></a>';
		if(!empty($linkedin))
			$html .= '<a href="'.$linkedin.'" target="_blank"><img src="'.get_stylesheet_directory_uri().'/img/icon-linkedin.svg" class="localbiz-share icon-linkedin"></a>';
		if(!empty($site) && $ops['site'] == 'yes')
			$html .= '<a href="'.$site.'" target="_blank"><img src="'.get_stylesheet_directory_uri().'/img/icon-site.svg" class="localbiz-share icon-site"></a>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function tags_shortcode($atts) {
		/*$ops = shortcode_atts( array(
				'site' => 'yes',
		), $atts );*/
		$tags = get_the_tags();
		$tags_html = '';
		if($tags !== false && ! $tags instanceof WP_Error) {
			foreach ($tags as $tag) {
				$link = get_term_link($tag);
				$tags_html .= '<span class="localbiz-single-tags">#'.$tag->name.'</span>';
			}
		}
		return $tags_html;
	}
	
	public function produtosEservicos_shortcode($atts) {
		$pEss = get_the_terms(get_the_ID(), 'produtoservico');
		$html = '';
		if($pEss !== false && ! $pEss instanceof WP_Error) {
			for($i = 0; $i < count($pEss) && $i < 5; $i++) {
				$link = get_term_link($pEss[$i]);
				$html .= '<span class="localbiz-produto-e-servico">'.$pEss[$i]->name.'</span>';
			}
		}
		return $html;
	}
	
	/**
	 * Based on https://wordpress.stackexchange.com/questions/9394/getting-all-values-for-a-custom-field-key-cross-post
	 * @param string $key
	 * @param string $type
	 * @param string $status
	 * @return void|array|string[]|NULL[]
	 */
	public static function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
		global $wpdb;
		if( empty( $key ) )
			return;
		
		$r = $wpdb->get_col( $wpdb->prepare( "
	        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
	        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	        WHERE pm.meta_key = %s
	        AND p.post_type = %s".($status === false ? '' : "
	        AND p.post_status = %s"), $key, $type, $status ) );
		return $r;
	}
	
	/**
	 * 
	 * @param WP_Query $query
	 */
	function search_query($query){
		if( $query->is_main_query() && $query->is_post_type_archive('localbiz') ){
			$cep = '';
			$city = '';
			$search = '';
			if(isset($_REQUEST['localbiz-search-by-name'])) $search = sanitize_text_field($_REQUEST['localbiz-search-by-name']);
			if(isset($_REQUEST['localbiz-search-by-city'])) $city = sanitize_text_field($_REQUEST['localbiz-search-by-city']);
			if(isset($_REQUEST['localbiz-search-by-cep'])) $cep = sanitize_text_field($_REQUEST['localbiz-search-by-cep']);
			if(!empty($search)) {
				$query->set('s', $search);
			}
			if(!empty($cep)) {
				//TODO cep
			}
			if(!empty($city)) {
				$meta_query = array(
					array(
							'key' => 'cidade',
							'value' => $city,
							'compare' => 'like'
					)
				);
				$query->set('meta_query', $meta_query);
			}
			//TODO pagination
			$query->set('posts_per_page', -1);
		}
		return $query;
	}
	public function category_link($link) {
		if(is_post_type_archive('localbiz') && strpos($link, 'post_type=') === false ) {
			$link .= '?post_type=localbiz';
		}
		return $link;
	}
	public function widget_categories_args($catargs) {
		if(is_post_type_archive('localbiz') ) {
			$catargs['meta_query'] = array(
				array(
					'key'     => 'category-localbiz',
					'value'   => 'N',
					'compare' => '!='
				)
			);
			if(is_category()) {
				global $wp_query;
				$cat_id = $wp_query->get_queried_object_id();
				$catargs['parent'] = $cat_id;
			}
		}
		return $catargs;
	}
	
	public static function get_footer($echo = false, $html = false) {
		$footer = '
			[et_pb_section fb_built="1" _builder_version="3.21.4" background_image="'.get_stylesheet_directory_uri().'/img/Banner-Cadastro_2x.jpg" background_size="initial" border_radii="|10px|8px|8px|60px" border_color_all="#aaaaaa" border_style_all="ridge" box_shadow_style="preset3" box_shadow_color="rgba(255,151,0,0.3)" custom_margin="|6em||6em||true" custom_margin_phone="|0em||0em" custom_margin_last_edited="on|desktop" custom_padding="50px|0|50px|0px|false|false" z_index_tablet="500" global_module="471"][et_pb_row custom_padding="0px||0px" custom_margin="0px||0px||true" _builder_version="3.21.4"][et_pb_column type="4_4" _builder_version="3.0.47"][et_pb_text _builder_version="3.21.4" text_font="||||||||" text_line_height="1em" header_font="Open Sans|700|||||||" header_font_size="2.5vw" header_font_size_phone="6vw" header_font_size_last_edited="on|desktop" header_line_height="1.2em" header_2_font="Open Sans||||||||" max_width="76%" max_width_last_edited="on|phone" module_alignment="center" custom_margin="|||" z_index_tablet="500"]<h1 style="text-align: center"><span style="color: #ffffff"><strong>Entenda por que somos a plataforma certa para fomentar o seu negócio.</strong></span></h1>
[/et_pb_text][et_pb_button button_url="/registro-de-usuario/" button_text="Cadastrar o seu negócio" button_alignment="center" _builder_version="3.21.4" custom_button="on" button_text_size="17px" button_text_color="#ff5500" button_bg_color="#ffffff" button_border_width="0px" button_border_color="#ffffff" button_border_radius="40px" button_font="Open Sans|600|||||||" button_icon="%%20%%" custom_margin="-10px|||" custom_padding="1em|2em|1em|2em" z_index_tablet="500"][/et_pb_button][et_pb_text _builder_version="3.21.4" custom_margin="-16px||"]<p style="text-align: center"><strong><span style="color: #ffffff">Não requer nenhum pagamento</span></strong></p>
[/et_pb_text][/et_pb_column][/et_pb_row][/et_pb_section]
		';
		$footer_html = '';
		if($html || $echo) {
			$footer_html = apply_filters('the_content', $footer);
		}
		if($echo) {
			echo $footer_html;
			return true;
		}
		return $html ? $footer_html : $footer;
	}
	
	public function widget_title($title, $instance, $id_base) {
		if(is_post_type_archive('localbiz') && is_category() ) {
			return _('Subcategorias');
		}
		return $title;
	}
	
	/**
	 * Based on https://www.codeofaninja.com/2014/06/google-maps-geocoding-example-php.html
	 * @param string $address
	 * @return array|boolean
	 */
	public function geocode($address){
		if(!defined('LOCALBIZ_API')) define('LOCALBIZ_API', false);
		$api_key = LOCALBIZ_API;
		// url encode the address
		$address = urlencode($address);
		
		// google map geocode api url
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$api_key}";
		
		// get the json response
		$resp_json = file_get_contents($url);
		
		// decode the json
		$resp = json_decode($resp_json, true);
		
		// response status will be 'OK', if able to geocode given address
		if($resp['status']=='OK'){
			
			// get the important data
			$lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
			$longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
			$formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
			
			// verify if data is complete
			if($lati && $longi && $formatted_address){
				// put the data in the array
				$data_arr = array(
					'lat' => $lati,
					'lng' => $longi,
					'formatted_address' => $formatted_address
				);
				return $data_arr;
			}else{
				return false;
			}
		}
		else {
			error_log("<strong>Warning: {$resp['status']}</strong>");
			return false;
		}
	}
	public static function save_google_address( $post_id, $address) {
		$localbiz = self::get_instance();
		$address_array = $localbiz->geocode($address);
		if($address_array !== false) {
			update_post_meta($post_id, '.google-map-lat', $address_array['lat']);
			update_post_meta($post_id, '.google-map-lng', $address_array['lng']);
			update_post_meta($post_id, '.google-map-formated', $address_array['formatted_address']);
			return $address_array;
		}
		return false;
	}
	
	public function mime_types($mime_types) {
		if(get_query_var('custom_user_register') || strpos($_SERVER['REQUEST_URI'], '/registro-de-usuario/' !== false ) ){
			foreach ($mime_types as $ext => $type) {
				if(strpos($type, 'image') === false) {
					unset($mime_types[$ext]);
				}
			}
		}
		return $mime_types;
	}
}

new LocalBiz();