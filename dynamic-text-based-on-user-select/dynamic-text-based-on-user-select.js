	
	jQuery(document).ready(function($){
		// make sure acf is loaded, it should be, but just in case
		if (typeof acf == 'undefined') { return; }
		
		// extend the acf.ajax object
		// you should probably rename this var
		var myUserFieldextension = acf.ajax.extend({
			events: {
				// this data-key must match the field key for the user field on the post page where
				// you want to dynamically load additional user information
				'change [data-key="field_57ab9d5905e4f"] input': '_update_user_fields',
				// this entry is to cause the city field to be updated when the page is loaded
				'ready [data-key="field_57ab9d5905e4f"] input': '_update_user_fields',
			},
			
			// this is our function that will perform the
			// ajax request when the state value is changed
			_update_user_fields: function(e){
				
				// get the user selection
				var $value = e.$el.val();
				
				// a lot of the following code is copied directly 
				// from ACF and modified for our purpose
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.update_user_request) {
					this.update_user_request.abort();
				}
				
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
						data = this.o;
						
				// set the ajax action that's set up in php
				data.action = 'load_user_details';
				// set the user id value to be submitted
				data.user_id = $value;
				
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.update_user_request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'json',
					async: true,
					success: function(json){
						// function to populate fields
						// loop through the values returned
						// and insert into fields
						for (i=0; i<json.length; i++) {
							var $key = json[i]['key'];
							var $value = json[i]['value'];
							$('[data-key="'+$key+'"] input').val($value);
						}
					}
				});
			},
		});
		
		// triger the ready action on page load
		$('[data-key="field_57ab9d5905e4f"] select').trigger('ready');
	});
