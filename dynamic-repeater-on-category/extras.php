<?php 
	
	/*
		this file sets up things for this example
		if creates a custom post type and a custom taxonomy
		and it loads the field groups for the example
		
		SEE IMPORTANT NOTE IN THE REGISTER METHOD FOR TAXONOMY ARGUMENTS
	*/
	
	new my_dynamic_acf_extension_extras();
	
	class my_dynamic_acf_extension_extras {
		
		public function __construct() {
			add_action('init', array($this, 'register'));
			add_action('acf/include_fields', array($this, 'field_groups'));
		} // end public function __construct
		
		public function field_groups() {
			// this function loads the example field groups
			// this can be used as an example of how to load
			// your own ACF field groups from json files
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
				if ($group === NULL) {
					continue;
				}
				acf_add_local_field_group($group);
			}
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
				
				/*
						*** IMPORTANT NOTE ***
						setting meta_box_cb to an empty string
						causes WP to not show the standard meta box
						for selecting the terms of a taxonomy
						this is important for this example
						because the term must be selected using
						the ACF field and not the meta box
						if the term is selected in the meta box
						none of the code in this example will work
				*/
				'meta_box_cb' => ''
			);
			register_taxonomy('sprocket-category', array($post_type), $args);
			
		} // end public function register
		
	} // end class my_dynamic_acf_extension_extras
	
?>