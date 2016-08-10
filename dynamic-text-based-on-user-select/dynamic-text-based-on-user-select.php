<?php 
	
	new myUserFieldextension();
	
	class myUserFieldextension {
		
		public function __construct() {
			// ajax action for loading city choices
			add_action('wp_ajax_load_user_details', array($this, 'ajax_load_user_details'));
			// enqueue js extension for acf
			// do this when ACF in enqueuing scripts
			add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
		} // end public function __construct
		
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
			
			$handle = 'myUserFieldextension';
			
			// I'm using this method to set the src because
			// I don't know where this file will be located
			// you should alter this to use the correct fundtions
			// to set the src value to point to the javascript file
			$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/dynamic-text-based-on-user-select.js';
			// make this script dependent on acf-input
			$depends = array('acf-input');
			
			wp_enqueue_script($handle, $src, $depends);
		} // end public function enqueue_script
		
		public function ajax_load_user_details() {
			// this funtion is called by AJAX to the user information
			// based on user selection
			
			// we can use the acf nonce to verify
			if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
				die();
			}
			$user = 0;
			if (isset($_POST['user_id'])) {
				$user = intval($_POST['user_id']);
			}
			$values = array();
			$user = get_user_by('id', $user);
			if ($user) {
				$values= array(
					// these are the field keys that match were we want to show the values
					array(
						'key' => 'field_57ab9d8305e50',
						'value' => $user->data->user_login
					),
					array(
						'key' => 'field_57ab9de105e54',
						'value' => $user->data->display_name
					),
					array(
						'key' => 'field_57ab9df305e56',
						'value' => $user->data->user_email
					)
				);
			}
			echo json_encode($values);
			exit;
		} // end public function ajax_load_user_details
		
	} // end class myUserFieldextension
	
?>