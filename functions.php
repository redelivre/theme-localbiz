<?php
class LocalBiz {
	public function __construct() {
		add_action('init', array($this, 'init'));
	}
	public function init() {
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		$this->add_custom_post();
		$this->create_terms();
		add_action('wp_ajax_locabiz_category_selected_action', array($this, 'category_selected_action') );
		add_action('wp_ajax_nopriv_locabiz_category_selected_action', array($this, 'category_selected_action') );//for not 'logged in' users.
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
				'register_meta_box_cb' => array($this, 'post_custom_meta'), // função para chamar box na edição
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
	public function post_custom_meta() {
		echo 'meta';
		return 'meta';
	}
	
	public function enqueue_scripts() {
		wp_enqueue_script('cepjs', get_stylesheet_directory_uri() . '/js/jquery.autocomplete-address.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('mask', get_stylesheet_directory_uri() . '/js/jquery.mask.min.js', array('jquery'), '', true);
		wp_enqueue_script('locabiz-registro', get_stylesheet_directory_uri() . '/js/registro-de-usuario.js', array('jquery'), '', true);
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
			/*$new_cat = array( 'cat_name' => 'A – Agropecuária, Floresta e Pesca', 'category_description' => '', 'category_parent' => '' );
			$cat_id = wp_insert_category($new_cat);
			
			}*/
			if(file_exists(get_stylesheet_directory()."/categorias.csv")) {
				//echo "<br/><br/><br/><br/><br/><br/><br/><br/><pre>";
				$file = fopen(get_stylesheet_directory()."/categorias.csv", 'r');
				$new_cat = null;
				while (($line = fgetcsv($file, null,';')) !== FALSE) {
					if(!empty($line[0])) {
						$new_cat = array( );
						$cat_id = wp_insert_term($line[0], 'category', $new_cat);
						if($cat_id instanceof WP_Error) wp_die($cat_id);
					}
					if(!empty($line[1]) && !is_null($new_cat)) {
						$new_subcat = array( 'parent' => $cat_id['term_id'] );
						$subcat_id = wp_insert_term($line[1], 'category', $new_subcat);
						if($subcat_id instanceof WP_Error) wp_die($subcat_id);
					}
				}
				fclose($file);
				//print_r($cats);
				//echo "</pre>";
			}
		}
	}
	
	public static function category_subcategory_select_form() { ?>
		<div class="row">
			<div class="title">
				Categoria
			</div>
		</div>
		<div class="row">
        	<div id="parent_cat_div"><?php wp_dropdown_categories("id=cat&name=cat&class=Rectangle&show_option_none=Selecione uma categoria&orderby=name&depth=1&hierarchical=1&hide_empty=0&value_field=term_id"); ?></div>
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
	            wp_dropdown_categories("id=subcat&name=subcat&class=Rectangle&hide_empty=0&value_field=term_id&orderby=name&parent=$parent_cat_ID");
	        } else {
	            ?><select name="subcat" class="Rectangle" id="subcat" disabled="disabled"><option value="-1">Sem subcategorias</option></select><?php
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
}

$localbiz = new LocalBiz();