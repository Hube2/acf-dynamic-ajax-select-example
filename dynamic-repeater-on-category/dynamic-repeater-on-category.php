<?php 
	
	// look at comments in extras.php
	include(dirname(__FILE__).'/extras.php');
	
	new my_dynamic_acf_extension();
	
	class my_dynamic_acf_extension {
		
		public function __construct() {
			// enqueue js extension for acf
			// do this when ACF in enqueuing scripts
			add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
			// ajax action for loading values
			add_action('wp_ajax_load_features_from_term', array($this, 'load_features_from_term'));
			// make the feature name field on post read only
			add_filter('acf/load_field/key=field_57c99507d8ee7', array($this, 'readonly'));
		} // end public function __construct
		
		public function readonly($field) {
			$field['readonly'] = true;
			return $field;
		} // end public function readonly
		
		public function load_features_from_term() {
			// this is the ajax function that gets the feature values from the selected term
			
			// we can use the acf nonce to verify the request
			if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
				echo json_encode(false);
				exit;
			}
			
			// make sure our other values are set
			if (!isset($_POST['term_id']) || !isset($_POST['post_id'])) {
				echo json_encode(false);
				exit;
			}
			
			// term id needs to be an integer
			$term_id = intval($_POST['term_id']);
			// post id must be an integer
			$post_id = intval($_POST['post_id']);
			
			//echo $post_id; exit;
			
			// the taxonomy of the term
			// we should not need to use this since split terms
			// but get_term_by() still requires the taxonomy
			// why? who knows, it seems that this WP function is a little behind
			$taxonomy = 'sprocket-category';
			
			$term = get_term_by('id', $term_id, $taxonomy);
			//print_r($term); exit;
			
			if (!$term) {
				// no term found
				echo json_encode(false);
				exit;
			}
			
			// array to hold the return values
			$values = array();
			
			// see if this term is the term already set for this post
			// if it is return the current values for the post if there are any
			$current_term = get_field('category', $post_id);
			//echo $current_term; exit;
			//print_r(get_field('features', $post_id)); exit;
			//echo $current_term,'=',$term_id; exit;
			if ($current_term == $term_id) {
				//echo 'get_current_values'; exit;
				if (have_rows('features', $post_id)) {
					//echo 'have_rows'; exit;
					while(have_rows('features', $post_id)) {
						the_row();
						$values[] = array(
							// key value pairs for fields to be updated
							// by callback function
							'field_57c99507d8ee7' => get_sub_field('feature'),
							'field_57c99515d8ee8' => get_sub_field('value')
						);
					} // end while have rows
				} // end if have rows
			} // end if is current term
			//print_r($values); exit;
			if (count($values)) {
				// values from post, return them
				echo json_encode($values);
				exit;
			}
			//echo 'wtf'; exit;
			// no existing value
			// get features from term
			if (have_rows('features', $taxonomy.'_'.$term_id)) {
				while (have_rows('features', $taxonomy.'_'.$term_id)) {
					the_row();
					$values[] = array(
						// key value pairs for fields to be updated
						// by callback function
						'field_57c99507d8ee7' => get_sub_field('feature'),
						'field_57c99515d8ee8' => ''
					);
				} // end while have rows
			} // end if have rows
			
			echo json_encode($values);
			exit;
			
		} // end public function load_features_from_term
		
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
			    get_post_type($post->ID) != 'sprocket') {
				return;
			}
			
			// the handle should be changed to your own unique handle
			$handle = 'my-acf-extension';
			
			// I'm using this method to set the src because
			// I don't know where this file will be located
			// you should alter this to use the correct fundtions
			// to set the src value to point to the javascript file
			$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/dynamic-repeater-on-category.js';
			// make this script dependent on acf-input
			$depends = array('acf-input');
			
			wp_register_script($handle, $src, $depends);
			
			// localize the script with the current post id
			// we will need the current post ID to get existing
			// values from the post
			
			// you should change this object name to something unique
			// will will also need to change this object name in the JS file
			$object = 'my_acf_extension_object';
			
			$data = array('post_id' => $post->ID);
			wp_localize_script($handle, $object, $data);
			
			wp_enqueue_script($handle);
		} // end public function enqueue_script
		
	} // end class my_dynamic_acf_extension
	
?>