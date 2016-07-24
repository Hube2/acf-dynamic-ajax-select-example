	
	jQuery(document).ready(function($){
		// make sure acf is loaded, it should be, but just in case
		if (typeof acf == 'undefined') { return; }
		
		// extend the acf.ajax object
		// you should probably rename this var
		var myACFextension = acf.ajax.extend({
			events: {
				// this data-key must match the field key for the state field on the post page where
				// you want to dynamically load the cities when the state is changed
				'change [data-key="field_579376f522130"] select': '_state_change',
				// this entry is to cause the city field to be updated when the page is loaded
				'ready [data-key="field_579376f522130"] select': '_state_change',
			},
			
			// this is our function that will perform the
			// ajax request when the state value is changed
			_state_change: function(e){
				
				// clear the city field options
				// the data-key is the field key of the city field on post
				var $select = $('[data-key="field_5793770922131"] select');
				$select.empty();
				
				// get the state selection
				var $value = e.$el.val();
				
				// a lot of the following code is copied directly 
				// from ACF and modified for our purpose
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.state_request) {
					this.state_request.abort();
				}
				
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
						data = this.o;
						
				// set the ajax action that's set up in php
				data.action = 'load_city_field_choices';
				// set the state value to be submitted
				data.state = $value;
				
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.state_request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'json',
					async: true,
					success: function(json){
						// function to update the city field choices
						
						// get the city field
						// the city field key that we want to update
						var $select = $('[data-key="field_5793770922131"] select');
						
						// add options to the city field
						for (i=0; i<json.length; i++) {
							var $value = json[i]['value'];
							var $item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
							$select.append($item);
						}
					}
				});
			},
		});
		
		// triger the ready action on page load
		$('[data-key="field_579376f522130"] select').trigger('ready');
	});