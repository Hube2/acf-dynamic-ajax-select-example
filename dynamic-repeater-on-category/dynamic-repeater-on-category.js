	
	jQuery(document).ready(function($){
		// make sure acf is loaded, it should be, but just in case
		if (typeof acf == 'undefined') { return; }
		
		// extend the acf.ajax object
		// you should probably rename this var
		var myACFextension = acf.ajax.extend({
			events: {
				// this data-key must match the field key taxonomy field
				// we want to trigger the change
				// in this case, the taxonomy field is a Select2 field
				// and we want the value of a hidden field and not the select field
				'change [data-key="field_57c994a6d8ee5"] input[type="hidden"]': '_change_term',
				// this entry is to cause the field to update on page load
				'ready [data-key="field_57c994a6d8ee5"] input[type="hidden"]': '_change_term',
				
				// for this example we also want to hide all of the "add row" buttons
				// because we only want the user to be able to set existing values
				// and not add new ones so we'll add another ready function
				// that will do this maintenance, this is our own special action
				// setup is not a real action, we're creating a new one
				'setup [data-key="field_57c994a6d8ee5"] input[type="hidden"]': '_setup',
			},
			
			// this is our function that will perform the
			// ajax request when the state value is changed
			_change_term: function(e){
				
				// get the value of the taxonomy field
				$value = e.$el.val();
				
				// remove any existing rows of the repeater, except the clone row
				// by triggering each row's remove-row click event
				// the data-key is the field key of the repeater
				$('div[data-key="field_57c994f2d8ee6"] tr a[data-event="remove-row"]').not('div[data-key="field_57c994f2d8ee6"] tr.acf-clone a[data-event="remove-row"]').trigger('click');
				
				
				
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
				data.action = 'load_features_from_term';
				// set the term id value to be submitted
				data.term_id = $value;
				
				// this gets the post ID that we set when localizing the script
				// you should change this object name to something unique
				// will will also need to change this object name in the PHO file
				// where this script is enqueued
				data.post_id = my_acf_extension_object.post_id
				
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
						// add enough rows to the repeater to hold the values
						var len = json.length;
						for (i=0; i<len; i++) {
							// trigger the add-row action for the repeater
							// data-key = field key of the repeater
							$('div[data-key="field_57c994f2d8ee6"] ul.acf-actions a[data-event="add-row"]').trigger('click');
						}
						// we need to get the entire list of fields for both
						// of the fields we want to fill and we need to fill
						// them from the bottom up, the reason for this is
						// that the removal of the existing rows may not
						// actually be completed at this point, so we don't
						// want to put values in fields we're going to delete
						
						// first get all of the repeater rows, except the clone row
						// the first data key is the repeater key
						var feature_list = $('div[data-key="field_57c994f2d8ee6"] tr.acf-row').not('div[data-key="field_57c994f2d8ee6"] tr.acf-clone');
						
						// start at the end
						var json_item = len - 1;
						var start = feature_list.length - 1;
						for (i=start; i>=0; i-=1) {
							// get the ID of this item
							var id = feature_list[i].getAttribute('data-id');
							// populate the featur
							$('div[data-key="field_57c994f2d8ee6"] tr[data-id="'+id+'"] td[data-key="field_57c99507d8ee7"] input').val(json[json_item]['field_57c99507d8ee7']);
							// populate the value
							$('div[data-key="field_57c994f2d8ee6"] tr[data-id="'+id+'"] td[data-key="field_57c99515d8ee8"] input').val(json[json_item]['field_57c99515d8ee8']);
							// decrease json item
							json_item-=1;
						}
						// last thing to do is trigger setup to make sure we keep the add/remvove buttons hidden
						$('[data-key="field_57c994a6d8ee5"] input').trigger('setup');
					}
				});
			}, // end _change_term
			
			// this function will hide the add/remove rows buttons
			_setup: function(e) {
				// hide all add/delete row links
				// this data key is the key for the repeater field
				$('div[data-key="field_57c994f2d8ee6"] a[data-event="remove-row"]').css('display', 'none');
				$('div[data-key="field_57c994f2d8ee6"] a[data-event="add-row"]').css('display', 'none');
			}, // end _setup
		});
		
		// triger the setup action on page load
		$('[data-key="field_57c994a6d8ee5"] input').trigger('setup');
	});