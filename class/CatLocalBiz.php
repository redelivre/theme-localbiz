<?php
/**
 * Plugin class
 **/
if (! class_exists ( 'CatLocalBiz' )) {
	class CatLocalBiz {
		public function __construct() {
			add_action ( 'admin_init', array (
					$this,
					'admin_init'
			) );
			self::$instance = $this;
		}
		private static $instance = null;
		public static function get_instance() {
			if (! isset ( self::$instance )) {
				new self ();
			}
			return self::$instance;
		}

		/*
		 * Initialize the class and start calling our hooks and filters
		 * @since 1.0.0
		 */
		public function admin_init() {
			add_action ( 'category_add_form_fields', array (
					$this,
					'add_category_localbiz'
			), 10, 2 );
			add_action ( 'created_category', array (
					$this,
					'save_category_localbiz'
			), 10, 2 );
			add_action ( 'category_edit_form_fields', array (
					$this,
					'update_category_localbiz'
			), 10, 2 );
			add_action ( 'edited_category', array (
					$this,
					'updated_category_localbiz'
			), 10, 2 );
		}

		/*
		 * Add a form field in the new category page
		 * @since 1.0.0
		 */
		public function add_category_localbiz($taxonomy) {?>
			<div class="form-field term-group">
				<h1><label for="category-localbiz"><?php _e( 'Local Biz?', 'localbiz' ); ?></label></h1>
				<label for="category-localbiz"><?php _e('Sim', 'localbiz'); ?></label>
				<input type="radio" id="category-localbiz" name="category-localbiz"
					class="category-localbiz" value="S">
				<label for="category-localbiz"><?php _e('Não', 'localbiz'); ?></label>
				<input type="radio" id="category-localbiz" name="category-localbiz"
					class="category-localbiz" value="N">
			</div><?php
		}

		/*
		 * Save the form field
		 * @since 1.0.0
		 */
		public function save_category_localbiz($term_id, $tt_id) {
			if (isset ( $_POST ['category-localbiz'] ) && '' !== $_POST ['category-localbiz']) {
				$localbiz = sanitize_text_field ( $_POST ['category-localbiz'] );
				update_term_meta ( $term_id, 'category-localbiz', $localbiz, true );
			}
		}

		/*
		 * Edit the form field
		 * @since 1.0.0
		 */
		public function update_category_localbiz($term, $taxonomy) {?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="category-localbiz"><?php _e( 'Local Biz?', 'localbiz' ); ?></label>
				</th>
				<td>
			       <?php $localbiz = get_term_meta ( $term -> term_id, 'category-localbiz', true ); ?>
			       <label for="category-localbiz"><?php _e('Sim', 'localbiz'); ?></label>
					<input type="radio" id="category-localbiz" name="category-localbiz"
						class="category-localbiz" value="S" <?php echo $localbiz == 'S' || empty($localbiz) ? 'checked' : ''; ?>>
					<label for="category-localbiz"><?php _e('Não', 'localbiz'); ?></label>
					<input type="radio" id="category-localbiz" name="category-localbiz"
						class="category-localbiz" value="N" <?php echo $localbiz == 'N' ? 'checked' : ''; ?>>
				</td>
			</tr><?php
		}

		/*
		 * Update the form field value
		 * @since 1.0.0
		 */
		public function updated_category_localbiz($term_id, $tt_id) {
			if (isset ( $_POST ['category-localbiz'] ) && '' !== $_POST ['category-localbiz']) {
				$localbiz = sanitize_text_field ( $_POST ['category-localbiz'] );
				update_term_meta ( $term_id, 'category-localbiz', $localbiz );
			}
		}
	}

	new CatLocalBiz ();
}