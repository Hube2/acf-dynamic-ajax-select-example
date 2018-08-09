<?php 
	
	// enqueue our JS when ACF envques scripts
	add_action('acf/input/admin_enqueue_scripts', 'unique_repeater_checkbox_enqueue_script');
	
	function unique_repeater_checkbox_enqueue_script() {
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
			
		// your should change this to check for whatever
		// admin page(s) you want the script to load on
		global $post;
		if (!$post ||
				!isset($post->ID) || 
				get_post_type($post->ID) != 'post') {
			return;
		}
			
		$handle = 'acf-unique-repeater-checkbox';
		
		// I'm using this method to set the src because
		// I don't know where this file will be located
		// you should alter this to use the correct fundtions
		// to set the src value to point to the javascript file
		$version = acf_get_setting('version');
		if (version_compare($acf_version, '5.7.0', '<')) {
			$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/unique-repeater-checkbox.js';
		} else {
			$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/unique-repeater-checkbox-acf57.js';
		}
		// make this script dependent on acf-input
		$depends = array('acf-input');
		
		wp_enqueue_script($handle, $src, $depends);
		
	} // end function unique_repeater_checkbox_enqueue_script
	
?>
