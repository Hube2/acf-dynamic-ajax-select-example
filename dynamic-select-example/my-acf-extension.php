<?php 
	
	new my_acf_extension();
	
	class my_acf_extension {
		
		public function __construct() {
			// state field on city 
			add_action('acf/load_field/key=field_579376941cecc', array($this, 'load_state_field_choices'));
			// state field on post
			add_action('acf/load_field/key=field_579376f522130', array($this, 'load_state_field_choices'));
			// city field on post
			add_action('acf/load_field/key=field_5793770922131', array($this, 'load_city_field_choices'));
			// ajax action for loading city choices
			add_action('wp_ajax_load_city_field_choices', array($this, 'ajax_load_city_field_choices'));
			// enqueue js extension for acf
			// do this when ACF in enqueuing scripts
			add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
		} // end public function __construct
		
		public function load_state_field_choices($field) {
			// this funciton dynamically loads the state select field choices
			// from the state post type
			
			// I only want to do this on Posts and the city CPT
			global $post;
			if (!$post ||
			    !isset($post->ID) ||
			    (get_post_type($post->ID) != 'post' && get_post_type($post->ID) != 'city')) {
				return $field;
			}
			
			// get states
			$args = array(
				'post_type' => 'state',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$query = new WP_Query($args);
			$choices = array('' => '-- State --');
			if (count($query->posts)) {
				// populate choices
				foreach ($query->posts as $state) {
					$choices[$state->ID] = $state->post_title;
				}
			}
			$field['choices'] = $choices;
			return $field;
		} // end public function load_state_field_choices
		
		public function load_city_field_choices($field) {
			// this function dynamically loads city field choices
			// based on the currently saved state
			
			// I only want to do this on Posts
			global $post;
			if (!$post ||
			    !isset($post->ID) || 
			    get_post_type($post->ID) != 'post') {
				return $field;
			}
			// get the state post id
			// I generally use get_post_meta() instead of get_field()
			// when building functionality, but get_field() could be
			// subsitited here
			$state = intval(get_post_meta($post->ID, 'state', true));
			$cities = $this->get_cities($state);
			$field['choices'] = $cities;
			return $field;
		} // end public funciton load_city_field_choices
		
		public function enqueue_script() {
			// enqueue acf extenstion
			
			// only enqueue the script on the post page where it needs to run
			/* *** THIS IS IMPORTANT
			       ACF uses the same scripts as well as the same field identification
			       markup (the data-key attribute) if the ACF field group editor
			       because of this, if you load and run your custom javascript on
			       the field group editor page it can have unintended side effects
			       on this page. It is important to alway make sure you're only
			       loading scripts where you need them.
			*/
			global $post;
			if (!$post ||
			    !isset($post->ID) || 
			    get_post_type($post->ID) != 'post') {
				return;
			}
			
			$handle = 'my-acf-extension';
			$version = acf_get_setting('version');
			if (version_compare($acf_version, '5.7.0', '<')) {
				// I'm using this method to set the src because
				// I don't know where this file will be located
				// you should alter this to use the correct fundtions
				// to set the src value to point to the javascript file
				$$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/my-acf-extension.js';
			} else {
				$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/dynamic-select-on-select.js';
			}
			// make this script dependent on acf-input
			$depends = array('acf-input');
			
			wp_enqueue_script($handle, $src, $depends);
		} // end public function enqueue_script
		
		public function ajax_load_city_field_choices() {
			// this funtion is called by AJAX to load cities
			// based on state selecteion
			
			// we can use the acf nonce to verify
			if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
				die();
			}
			$state = 0;
			if (isset($_POST['state'])) {
				$state = intval($_POST['state']);
			}
			$cities = $this->get_cities($state);
			$choices = array();
			foreach ($cities as $value => $label) {
				$choices[] = array('value' => $value, 'label' => $label);
			}
			echo json_encode($choices);
			exit;
		} // end public function ajax_load_city_field_choices
		
		private function get_cities($post_id) {
			// $post_id is post ID of state post
			// get all cities related to the state
			$args = array(
				'post_type' => 'city',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'state',
						'value' => $post_id
					)
				)
			);
			$query = new WP_Query($args);
			$choices = array('' => '-- City --');
			if (count($query->posts)) {
				// populate choices
				foreach ($query->posts as $post) {
					$choices[$post->ID] = $post->post_title;
				}
			}
			return $choices;
		} // end private function get_cities
		
	} // end class my_acf_extension
	
?>
