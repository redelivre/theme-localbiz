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
		$this->add_custom_post();
		$this->create_terms();
		add_action( 'wp_ajax_locabiz_category_selected_action', array($this, 'category_selected_action') );
		add_action( 'wp_ajax_nopriv_locabiz_category_selected_action', array($this, 'category_selected_action') );//for not 'logged in' users.
		add_action( 'save_post', array($this, 'save_post'), 10, 2 );
		add_filter( 'body_class', array($this, 'custom_body_class') );
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
					value="N"> <label for="tem_cnpj">Ainda não possuo CNPJ</label>
			</div>
			<input type="checkbox" name="tem_cnpj" id="tem_cnpj_sim" class="hide" style="display: none;"
				value="S" checked="checked">
		</div>
		<div class="row">
			<div class="title">Razão Social</div>
		</div>
		<div class="row">
			<input name="razao" id="razao" class="Rectangle"
				value="<?php echo get_post_meta($post_id, 'razao', true); ?>"
				required="required">
		</div>
		<?php
	}
	public function enqueue_scripts() {
		wp_enqueue_script ( 'cepjs', get_stylesheet_directory_uri() . '/js/jquery.autocomplete-address.min.js', array('jquery'), '1.0', true);
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
	}
	
	function custom_body_class($classes) {
		if(get_query_var('custom_user_register') || strpos($_SERVER['REQUEST_URI'], '/registro-de-usuario/' !== false ) ){
			$classes[] = 'localbiz-custom-register';
		}
		return $classes;
	}
}

new LocalBiz();