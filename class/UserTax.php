<?php
/**
 * Based on https://github.com/nopio/wordpress_user_taxonomy_tutorial/blob/master/user-category-taxonomy.php all rights to user created it
 */

class UserTax {
	private $user_tax_name;
	private $user_tax_meta_key;
	private $post_type;

	public function __construct($user_tax_name, $user_tax_meta_key, $post_type='post') {
		if(
				!empty($user_tax_name) &&
				!empty($user_tax_meta_key) &&
				is_string($user_tax_name) &&
				is_string($user_tax_meta_key)
		) {
			$this->user_tax_name = $user_tax_name;
			$this->user_tax_meta_key = $user_tax_meta_key;
			$this->post_type = $post_type;
			add_action( 'init', array($this, 'init'), 11 );
		} else {
			throw new Exception(__('Taxonomy and meta cannot be empty', 'localbiz'));
		}
	}

	function init() {
		if(taxonomy_exists($this->user_tax_name)) {
			register_taxonomy_for_object_type($this->user_tax_name, 'user');
			add_action( 'admin_menu', array( $this, 'add_user_categories_admin_page') );
			add_filter( 'submenu_file', array( $this, 'set_user_category_submenu_active') );
			add_action( 'show_user_profile', array( $this, 'admin_user_profile_category_select') );
			add_action( 'edit_user_profile', array( $this, 'admin_user_profile_category_select') );
			add_action( 'personal_options_update', array( $this, 'admin_save_user_categories') );
			add_action( 'edit_user_profile_update', array( $this, 'admin_save_user_categories') );
		} else {
			throw new Exception(__('Taxonomy does not exist: '.$this->user_tax_name, 'localbiz'));
		}
	}
	
	function add_user_categories_admin_page() {
		$taxonomy = get_taxonomy( $this->user_tax_name );
		add_users_page(
				esc_attr( $taxonomy->labels->menu_name ),//page title
				esc_attr( $taxonomy->labels->menu_name ),//menu title
				$taxonomy->cap->manage_terms,//capability
				'edit-tags.php?taxonomy=' . $taxonomy->name//menu slug
				);
	}
	
	function set_user_category_submenu_active( $submenu_file ) {
		global $parent_file;
		if( 'edit-tags.php?taxonomy=' . $this->user_tax_name == $submenu_file ) {
			$parent_file = 'users.php';
		}
		return $submenu_file;
	}
	
	function admin_user_profile_category_select( $user ) {
		$taxonomy = get_taxonomy( $this->user_tax_name );
		
		if ( !user_can( $user, 'author' ) ) {
			return;
		}
		?>
		<table class="form-table">
			<tr>
				<th>
					<label for="<?php echo $this->user_tax_meta_key ?>"><?php echo $taxonomy->labels->name ?></label>
				</th>
				<td>
					<?php
						$user_category_terms = get_terms( array(
							'taxonomy' => $this->user_tax_name,
							'hide_empty' => 0
						) );
						
						$select_options = array();
						
						foreach ( $user_category_terms as $term ) {
							$select_options[$term->term_id] = $term->name;
						}
						
						$meta_values = get_user_meta( $user->ID, $this->user_tax_meta_key, true );
						
						echo $this->custom_form_select(
							$this->user_tax_meta_key,
							$meta_values,
							$select_options,
							'',
							array( 'multiple' =>'multiple' )
						);
					?>
				</td>
			</tr>
		</table>
		<?php
	}
	
	function custom_form_select( $name, $value, $options, $default_var ='', $html_params = array() ) {
		if( empty( $options ) ) {
			$options = array( '' => '---escolha---');
		}
	
		$html_params_string = '';
		
		if( !empty( $html_params ) ) {
			if ( array_key_exists( 'multiple', $html_params ) ) {
				$name .= '[]';
			}
			foreach( $html_params as $html_params_key => $html_params_value ) {
				$html_params_string .= " {$html_params_key}='{$html_params_value}'";
			}
		}
	
		echo "<select name='{$name}'{$html_params_string}>";
		
		foreach( $options as $options_value => $options_label ) {
			if( ( is_array( $value ) && in_array( $options_value, $value ) )
				|| $options_value == $value ) {
				$selected = " selected='selected'";
			} else {
				$selected = '';
			}
			if( empty( $value ) && !empty( $default_var ) && $options_value == $default_var ) {
				$selected = " selected='selected'";
			}
			echo "<option value='{$options_value}'{$selected}>{$options_label}</option>";
		}
	
		echo "</select>";
	}
	
	function admin_save_user_categories( $user_id ) {
		$tax = get_taxonomy( $this->user_tax_name );
		$user = get_userdata( $user_id );
	
		if ( !user_can( $user, 'author' ) ) {
			return false;
		}
		
		$new_categories_ids = $_POST[$this->user_tax_meta_key];
		$user_meta = get_user_meta( $user_id, $this->user_tax_meta_key, true );
		$previous_categories_ids = array();
		
		if( !empty( $user_meta ) ) {
			$previous_categories_ids = (array)$user_meta;
		}
	
		if( ( current_user_can( 'administrator' ) && $_POST['role'] != 'author' ) ) {
			delete_user_meta( $user_id, $this->user_tax_meta_key );
			$this->update_users_categories_count( $previous_categories_ids, array() );
		} else {
			update_user_meta( $user_id, $this->user_tax_meta_key, $new_categories_ids );
			$this->update_users_categories_count( $previous_categories_ids, $new_categories_ids );
		}
	}
	
	function update_users_categories_count( $previous_terms_ids, $new_terms_ids ) {
		global $wpdb;
	
		$terms_ids = array_unique( array_merge( (array)$previous_terms_ids, (array)$new_terms_ids ) );
		
		if( count( $terms_ids ) < 1 ) { return; }
		
		foreach ( $terms_ids as $term_id ) {
			$count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = %s AND meta_value LIKE %s",
					$this->user_tax_meta_key,
					'%"' . $term_id . '"%'
				)
			);
			//$wpdb->update( $wpdb->term_taxonomy, array( 'count' => $count ), array( 'term_taxonomy_id' => $term_id ) );
		}
	}
	
	function show_authors_in_categories() {
		$categories = get_terms(array(
			'taxonomy' => $this->user_tax_name,
			'hide_empty' => true
		));
		
		echo '<ul>';
		foreach( $categories as $category ) {
			echo '<li>';
			echo $category->name;
			echo " (#{$category->count})";
				$args = array( 
					'role' => 'Author', 
					'meta_key'				=> $this->user_tax_meta_key,
					'meta_value'			=> '"' . $category->term_id . '"',
					'meta_compare'		=> 'LIKE'
				);
	
				$authors = new WP_User_Query( $args );
	
				echo '<ul>';
					foreach( $authors->results as $author ) {
						echo '<li>';
							echo $author->display_name;
						echo '</li>';
					}
				echo '</ul>';
			
			echo '</li>';
		}
		echo '</ul>';
	}
}

new UserTax('produtoservico', '.localbiz-produtoservico', 'localbiz');