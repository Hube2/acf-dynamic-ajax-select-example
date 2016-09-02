<?php 
	
	/*
		this file sets up things for this example
		if creates a custom post type and a custom taxonomy
		and it loads the field groups for the example
		
		SEE IMPORTANT NOTE IN THE REGITER METHOD FOR TAXONOMY ARGUMENTS
	*/
	
	new my_dynamic_acf_extension_extras();
	
	class my_dynamic_acf_extension_extras {
		
		public function __construct() {
			add_action('init', array($this, 'register'));
			add_action('acf/include_fields', array($this, 'field_groups'));
		} // end public function __construct
		
		public function field_groups() {
			//echo 'here'; die;
			// this function loads the example field groups
			$path = dirname(__FILE__).'/acf-json';
			if (!is_dir($path) ||
					($files = scandir($path)) == false ||
					!count($files)) {
				return;
			}
			foreach ($files as $file) {
				if (is_dir($path.'/'.$file) || !preg_match('/\.json$/', $file)) {
					continue;
				}
				$json = file_get_contents($path.'/'.$file);
				if (!$json) {
					continue;
				}
				$group = json_decode($json, true);
				//print_r($group); die;
				if ($group === NULL) {
					continue;
				}
				if (acf_local()->is_field_group($group['key'])) {
					echo 'exists'; die;
				}
				//echo 'does not exist'; die;
				//echo '<pre>'; print_r(acf_get_valid_field_group($group)); die;
				//$fields = acf_extract_var($group, 'fields'); //echo '<pre>'; print_r($fields); die;
				//$fields = acf_prepare_fields_for_import( $fields ); echo '<pre>'; print_r($fields); die;
				acf_add_local_field_group($group);
			}
			
			//wp_cache_delete('get_field_groups', 'acf');
		} // end public function field_groups
		
		public function register() {
			// register post type and taxonomy for example
			// this is a dumby post type for this example
			$post_type = 'sprocket';
			$labels = array(
				'name' => 'Sprockets',
				'singular_name' => 'Sproket',
			);
			$taxonomies = array(
			  // this is the taxonomy we'll set up below
				'sprocket-category'
			);
			$args = array(
				'label' => 'Sprockets',
				'labels' => $labels,
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_rest' => false,
				'rest_base' => '',
				'has_archive' => true,
				'show_in_menu' => true,
				'exclude_from_search' => false,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array(
					'slug' => 'blunt-product',
					'with_front' => true
				),
				'query_var' => true,		
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'custom-fields',
					'comments',
					'revisions',
					'author',
					'page-attributes',
					'post-formats'
				),
				'taxonomies' => $taxonomies,				
			);
			register_post_type($post_type, $args );
			
			
			$labels = array(
				'name' => 'Sprocket Categories',
				'singular_name' => 'Sprocket Category',
			);
			$args = array(
				'label' => 'Sprocket Categories',
				'labels' => $labels,
				'public' => true,
				'hierarchical' => true,
				'label' => 'Sprocket Categories',
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'sprocket-category',
					'with_front' => true
				),
				'show_admin_column' => true,
				'show_in_rest' => false,
				'rest_base' => '',
				'show_in_quick_edit' => true,
			);
			register_taxonomy('sprocket-category', array($post_type), $args);
			
		} // end public function register
		
	} // end class my_dynamic_acf_extension_extras
	
?>