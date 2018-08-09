	
	/* 
		Updated JS file for ACF >= 5.7.0
		See comments in the original file for things not included here
		The comments here mainly cover the differences between
		ACF < 5.7.0 and ACF >= 5.7.0
	*/
	
	
	/* 
		The first main difference is that we cannot extend
		the acf.ajax object because it no longer exists 
		we have to add our own function on jQuery(document).ready
	*/
	jQuery(document).ready(function($) {
		/*
			The next difference is how we target and detect changes to the field
			Since a repeater can be dynamically added we must target the document
			and include the selector for the checkbox field
		*/
		$(document).on('change', '[data-key="field_5b6c628cb8088"] .acf-input input', function(e) {
			/*
				The final difference is how we get the checkbox that was actually clicked
			*/
			var target = $(e.target);
			/*
				From this point on the logic is identical to the original JS
			*/
			var checked = target.prop('checked');
			if (!checked) {
				return;
			}
			var id = target.prop('id');
			var key = target.closest('.acf-field').attr('data-key');
			var list = $('[data-key="'+key+'"] input').not('[data-key="'+key+'"] input[type="hidden"]').not('.acf-clone [data-key="'+key+'"] input');
			if (list.length == 1) {
				return;
			}
			for (var i=0; i<list.length; i++) {
				var item_id = list[i].getAttribute('id');
				if (id != item_id) {
					list[i].checked = false;
				}
			}
		});
	});
