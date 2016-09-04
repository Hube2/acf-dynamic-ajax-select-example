<?php 
	
	/*
			this is an example of how to create a repeater that will show a specific number of rows
			and add a link (button) to display more rows
			
			this code would generally be added to your functions.php file
			
			Note: This will only work for a single repeater field. You will need to add different functions
			with different names to use this on multiple repeater field
	*/
	
	// add action for logged in users
	add_action('wp_ajax_my_repeater_show_more', 'my_repeater_show_more');
	// add action for non logged in users
	add_action('wp_ajax_nopriv_my_repeater_show_more', 'my_repeater_show_more');
	
	function my_repeater_show_more() {
		//echo 'here'; exit;
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_repeater_field_nonce')) {
			exit;
		}
		//echo 'here 2'; exit;
		// make sure we have the other values
		if (!isset($_POST['post_id']) || !isset($_POST['offset'])) {
			return;
		}
		$show = 4; // how many more to show
		$start = $_POST['offset'];
		$end = $start+$show;
		$post_id = $_POST['post_id'];
		ob_start();
		if (have_rows('load_more_example_repeater', $post_id)) {
			$total = count(get_field('load_more_example_repeater', $post_id));
			$count = 0;
			while (have_rows('load_more_example_repeater', $post_id)) {
				the_row();
				if ($count < $start) {
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
		//echo $content; exit;
		$more = false;
		if ($total > $count) {
			$more = true;
		}
		echo json_encode(array('content' => $content, 'more' => $more, 'offset' => $end));
		exit;
	} // end function my_repeater_show_more
	
	// this will load the example field group included
	add_action('acf/include_fields', 'load_repeater_more_example_group');
	function load_repeater_more_example_group() {
		$file = dirname(__FILE__).'/group_57cae2b099966.json';
		$json = file_get_contents($file);
		$group = json_decode($json, true);
		acf_add_local_field_group($group);
	} // end function load_repeater_more_example_group
	
?>