<?php 
	
	/*
		The code in this file is an example off the code that you would use in your template to
		show the first X number of rows of a repeater
		
		I'm sure there are more elegant ways to do the JavaScript, but what's here will work
	*/
	
	if (have_rows('load_more_example_repeater')) {
		// set the id of the element to something unique
		// this id will be needed by JS to append more content
		$total = count(get_field('load_more_example_repeater'));
		?>
			<ul id="my-repeater-list-id">
				<?php 
					$number = 4; // the number of rows to show
					$count = 0; // a counter
					while (have_rows('load_more_example_repeater')) {
						the_row();
						?>
							<li><?php the_sub_field('sub_field'); ?></li>
						<?php 
						$count++;
						if ($count == $number) {
							// we've shown the number, break out of loop
							break;
						}
					} // end while have rows
				?>
			</ul>
			<!-- 
				add a link to call the JS function to show more
				you will need to format this link using
				CSS if you want it to look like a button
				this button needs to be outside the container holding the
				items in the repeater field
			-->
			<a id="my-repeater-show-more-link" href="javascript: my_repeater_show_more();"<?php 
				if ($total < $count) {
					?> style="display: none;"<?php 
				}
				?>>Show More</a>
			<!-- 
				The JS that will do the AJAX request
			-->
			<script type="text/javascript">
				var my_repeater_field_post_id = <?php echo $post->ID; ?>;
				var my_repeater_field_offset = <?php echo $number; ?>;
				var my_repeater_field_nonce = '<?php echo wp_create_nonce('my_repeater_field_nonce'); ?>';
				var my_repeater_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
				var my_repeater_more = true;
				
				function my_repeater_show_more() {
					
					// make ajax request
					jQuery.post(
						my_repeater_ajax_url, {
							// this is the AJAX action we set up in PHP
							'action': 'my_repeater_show_more',
							'post_id': my_repeater_field_post_id,
							'offset': my_repeater_field_offset,
							'nonce': my_repeater_field_nonce
						},
						function (json) {
							// add content to container
							// this ID must match the containter 
							// you want to append content to
							jQuery('#my-repeater-list-id').append(json['content']);
							// update offset
							my_repeater_field_offset = json['offset'];
							// see if there is more, if not then hide the more link
							if (!json['more']) {
								// this ID must match the id of the show more link
								jQuery('#my-repeater-show-more-link').css('display', 'none');
							}
						},
						'json'
					);
				}
				
			</script>
		<?php 		
	} // end if have_rows
	
?>