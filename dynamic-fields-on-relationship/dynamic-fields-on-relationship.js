	
	jQuery(document).ready(function($){
		// make sure acf is loaded, it should be, but just in case
		if (typeof acf == 'undefined') { return; }
		
		// extend the acf.ajax object
		// you should probably rename this var
		var myACFextension = acf.ajax.extend({
			events: {
				// this data-key must match the field key for the relationship field that
				// will trigger the change of the other fields, in this case, the relationship field 
				// is a Select2 field and we need to jump through a few extra hoops to set
				// an event and get the value. When ACF updates the value it triggers a change
				// action on a different hidden field than the one that will contain the value
				'change [data-key="field_57d9e8a359b75"] .acf-input input': '_relationship_change',
			},
			
			_relationship_change: function(e) {
				
				// clear existing values from the fields we will update
				
				// clear input field
				// data-key == field key of title field
				$('[data-key="field_57d9e8d659b76"] input').val('');
				
				// clear textarea field
				// date-key == field key of excerpt field
				$('[data-key="field_57d9e8f559b77"] textarea').val('');
				
				// clear image field
				// data-key == field key of image field
				// target the link that will remove the image and trigger a click
				$('[data-key="field_57d9e92159b78"] a[data-name="remove"]').trigger('click');
				
				// maybe I'm slow, but it took several hours to figure out how to get
				// the actual selected value of a relationship field
				// this first line gets a list of the hidden input elements
				// for our example there should be only one element
				var list = e.$el.closest('.acf-field').find('.values .list input[type="hidden"]');
				// set the default value
				var $value = 0;
				if (list.length) {
					// if the list lenght > 0
					// in our case a lenght of 1
					// get the value of the first element
					$value = list[0].value;
				}
				
				// if there is no value, exit
				if (!$value) {
					return;
				}
				
				
				// now we can do our ajax request
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.request) {
					this.request.abort();
				}
				
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
				    data = this.o;
						
				// set the ajax action that's set up in php
				data.action = 'load_relationship_content';
				// set the term id value to be submitted
				data.related_post = $value;
				
				// this gets the post ID that we set when localizing the script
				// you should change this object name to something unique
				// will will also need to change this object name in the PHO file
				// where this script is enqueued
				data.post_id = my_acf_extension_object.post_id
				
				// we need to get information about the image field
				// to send that along with the request
				data.image_size = $('[data-key="field_57d9e92159b78"]').find('.acf-image-uploader').data('preview_size');
				 
				
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'json',
					async: true,
					success: function(json){
						
						if (!json) {
							return;
						}
						
						// put the values into the fields
						if (json['title']) {
							// data-key == field key of title field
							$('[data-key="field_57d9e8d659b76"] input').val(json['title']);
						}
						if (json['excerpt']) {
							// date-key == field key of excerpt field
							$('[data-key="field_57d9e8f559b77"] textarea').val(json['excerpt']);
						}
						if (json['image']) {
							// data-key == field key of image field
							// put the id value into the hidden field
							$('[data-key="field_57d9e92159b78"] input[type="hidden"]').val(json['image']['id']);
							// put the url into the img element
							$('[data-key="field_57d9e92159b78"] img').attr('src', json['image']['url']);
							// set the image field to show 
							$('[data-key="field_57d9e92159b78"]').find('.acf-image-uploader').addClass('has-value');
						}
					},
					error: function(jqXHR, textStatus, error) {
						alert (jqXHR+' : '+textStatus+' : '+error);
					}
				});
			},
		});
		
	});
