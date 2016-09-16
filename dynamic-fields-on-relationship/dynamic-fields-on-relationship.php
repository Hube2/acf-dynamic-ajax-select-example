<?php 
	
	//echo 'here'; die;
	new my_dynmamic_field_on_relationship();
	
	// you should probably change the name of this class and
	// modify the statement above that instantiates it
	class my_dynmamic_field_on_relationship {
		
		public function __construct() {
			//echo 'here 5'; die;
			// enqueue js extension for acf
			// do this when ACF in enqueuing scripts
			add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
			// ajax action for loading values
			add_action('wp_ajax_load_relationship_content', array($this, 'load_content_from_relationship'));
		} // end public function __construct
		
		public function load_content_from_relationship() {
			// this is the ajax function that gets the related values and returns them
			
			// we can use the acf nonce to verify the request
			if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
				echo json_encode(false);
				exit;
			}
			
			// check for our other required values
			if (!isset($_POST['post_id']) || !isset($_POST['related_post']) || !isset($_POST['image_size'])) {
				echo json_encode(false);
				exit;
			}
			
			// post IDs must be integers
			$post_id = intval($_POST['post_id']);
			$related_post = intval($_POST['related_post']);
			// get the image size to return
			$image_size = $_POST['image_size'];
			
			// you need to replace all field names in all of the following code
			// with the field names of your fields
			
			// first check to see if the selected related post is the same
			// as the current value for the related post
			// if it is return values that have already been saved for this post
			// the 3rd parameter set to false returns unformatted value
			$value = get_field('relationship', $post_id, false);
			// relationship fields always returns an array
			// use the first value of the array
			$value = intval($value[0]);
			
			if ($value == $related_post) {
				// this post already has a related post and it is the same one just selected
				// return values that were previously saved for the fields
				
				// get the image, if any
				$image = false;
				$image_id = intval(get_field('relationship_image', $post_id, false));
				if ($image_id) {
					$image_details = wp_get_attachment_image_src($image_id, $image_size);
					if ($image_details) {
						$image = array(
							'id' => $image_id,
							'url' => $image_details[0]
						);
					}
				}
				
				// put all the values into an array and return it as json
				$array = array(
				  'title' => get_field('relationship_title', $post_id),
					'excerpt' => get_field('relationship_excerpt', $post_id),
					'image' => $image
				);
				echo json_encode($array);
				exit;
			}
			
			// this is a different related post
			// get the post and set it up
			global $post;
			$post = get_post($related_post);
			setup_postdata($post);
			
			// get the excerpt
			$excerpt = trim($post->post_excerpt);
			if (!$excerpt) {
				// the excerpt is not set, create on from the content
				$excerpt = apply_filters('the_excerpt', $post->post_content);
			}
			
			// get the image if any
			$image = false;
			$image_id = intval(get_post_thumbnail_id($post->ID));
			if ($image_id) {
				$image_details = wp_get_attachment_image_src($image_id, $image_size);
				if ($image_details) {
					$image = array(
						'id' => $image_id,
						'url' => $image_details[0]
					);
				}
			}
			
			// put all the values into an array and return it as json
			$array = array(
				'title' => $post->post_title,
				'excerpt' => $excerpt,
				'image' => $image
			);
			echo json_encode($array);
			exit;
			
		} // end public function load_content_from_relationship
		
		public function enqueue_script() {
			//echo 'here'; die;
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
			
			// the handle should be changed to your own unique handle
			$handle = 'my-dynamic-repeater-on-relationship';
			
			// I'm using this method to set the src because
			// I don't know where this file will be located
			// you should alter this to use the correct functions
			// to get the theme, template or plugin path
			// to set the src value to point to the javascript file
			$src = '/'.str_replace(ABSPATH, '', dirname(__FILE__)).'/dynamic-fields-on-relationship.js';
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
		
	} // end class my_dynmamic_field_on_relationship
	
?>