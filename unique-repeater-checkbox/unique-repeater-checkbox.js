
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
		
		var unique_repeater_checkbox = acf.ajax.extend({
			/*
					why am I extenting the acf.ajax method/property
					since I'm not using ajax in this example?
					
					It's easier. Since most of the time when I'm going to be
					adding functionality to ACF I'm more than likely going to
					be adding several actions and one of them is more than likely
					going to include some kind of ajax. I'm more than likely going
					to build it all into a single extension for the site and I would
					put all of my events and functions in a that single extension and
					call it something like {$client's name}_acf_extension
			*/
			
			events: {
				// for each field that you want to apply this to add a 'change' event line
				// and copy the field key for the field and paste it in the "data-key" value
				'change [data-key="field_57c4e1b657543"] input': '_update_unique_checkbox',
			},
			
			_update_unique_checkbox: function(e) {
				var $checked = e.$el.prop('checked');
				if (!$checked) {
					// prevent the field from being unchecked and return
					e.$el.prop('checked', true);
					return;
				}
				// the field is checked, get the currently selected item
				var $id = e.$el.prop('id');
				// get the data-key
				var $key = e.$el.closest('.acf-field').attr('data-key');
				
				// get the field from all of the rows in the repeater
				// exclude hidden fields and the ACF clone row
				var $list = $('[data-key="'+$key+'"] input').not('[data-key="'+$key+'"] input[type="hidden"]').not('.acf-clone [data-key="'+$key+'"] input');
				if ($list.length == 1) {
					// if there is only one row then bail
					// nothing needs to be done
					return;
				}
				// uncheck all of the other rows except the currently checked one
				for (i=0; i<$list.length; i++) {
					var $item_id = $list[i].getAttribute('id');
					if ($id != $item_id) {
						// if not the current item then set to false
						$list[i].checked = false;
					}
				}
			},
			
		});
		
	});