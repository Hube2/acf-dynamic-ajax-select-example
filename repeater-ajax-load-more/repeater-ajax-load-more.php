<?php 
	
	/*
			this is an example of how to create a repeater that will show a specific number of rows
			and add a link (button) to display more rows
			
			this code would generally be added to your functions.php file
			
			Note: This will only work for a single repeater field. You will need to add different functions
			with different names to use this on multiple repeater field
			
			The code in this file should go into your function.php file
			or be included by your functions.php file
			
			Please note that this example uses jQuery to perform the AJAX request
			You must enqueue jQuery using a wp_enqueue_scripts action
	*/
	
	// add action for logged in users
	add_action('wp_ajax_my_repeater_show_more', 'my_repeater_show_more');
	// add action for non logged in users
	add_action('wp_ajax_nopriv_my_repeater_show_more', 'my_repeater_show_more');
	
	function my_repeater_show_more() {
		// validate the nonce
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_repeater_field_nonce')) {
			exit;
		}
		// make sure we have the other values
		if (!isset($_POST['post_id']) || !isset($_POST['offset'])) {
			return;
		}
		$show = 4; // how many more to show
		$start = $_POST['offset'];
		$end = $start+$show;
		$post_id = $_POST['post_id'];
		// use an object buffer to capture the html output
		// alternately you could create a varaible like $html
		// and add the content to this string, but I find
		// object buffers make the code easier to work with
		ob_start();
		if (have_rows('load_more_example_repeater', $post_id)) {
			$total = count(get_field('load_more_example_repeater', $post_id));
			$count = 0;
			while (have_rows('load_more_example_repeater', $post_id)) {
				the_row();
				if ($count < $start) {
					// we have not gotten to where
					// we need to start showing
					// increment count and continue
					$count++;
					continue;
				}
				?><li><?php the_sub_field('sub_field'); ?></li><?php 
				$count++;
				if ($count == $end) {
					// we've shown the number, break out of loop
					break;
				}
			} // end while have rows
		} // end if have rows
		$content = ob_get_clean();
		// check to see if we've shown the last item
		$more = false;
		if ($total > $count) {
			$more = true;
		}
		// output our 3 values as a json encoded array
		echo json_encode(array('content' => $content, 'more' => $more, 'offset' => $end));
		exit;
	} // end function my_repeater_show_more
	
	// this will load the example field group included
	// you only need this when setting up this example
	// it should be removed if you're using your own field group
	add_action('acf/include_fields', 'load_repeater_more_example_group');
	function load_repeater_more_example_group() {
		$file = dirname(__FILE__).'/group_57cae2b099966.json';
		$json = file_get_contents($file);
		$group = json_decode($json, true);
		acf_add_local_field_group($group);
	} // end function load_repeater_more_example_group
	
?>