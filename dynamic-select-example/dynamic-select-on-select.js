	
	/*
			updated JS file for use with ACF >= 5.7.0
	*/
	
	
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
		
		/*
				In ACF >= 5.7.0 the acf.ajax object no longer exists so we can't extend it
				Instead we need to attach out event the old fashioned way
				and we need to do it using $(document).on because the element may
				be added dynamically and this is the only way to add events
		*/
		
		$(document).on('change', '[data-key="field_579376f522130"] .acf-input select', function(e) {
			// we are not going to do anyting in this anonymous function
			// the reason is explained below
			// call function, we need to pass the event and jQuery object
			update_cities_on_state_change(e, $);
		});
		$('[data-key="field_579376f522130"] .acf-input select').trigger('ready');
	});
	
	// the actual function is separate from the above change function
	// the reason for this is that "this" has no real meaning in an anonymous 
	// function as each call is a new JS object and we need "this" in order 
	// to be to abort previous AJAX requests
	function update_cities_on_state_change(e, $) {
		if (this.request) {
			// if a recent request has been made abort it
			this.request.abort();
		}
		
		// get the city select field, and remove all exisiting choices
		var city_select = $('[data-key="field_5793770922131"] select');
		city_select.empty();
		
		// get the target of the event and then get the value of that field
		var target = $(e.target);
		var state = target.val();
		
		if (!state) {
			// no state selected
			// don't need to do anything else
			return;
		}
		
		// set and prepare data for ajax
		var data = {
			action: 'load_city_field_choices',
			state: state
		}
		
		// call the acf function that will fill in other values
		// like post_id and the acf nonce
		data = acf.prepareForAjax(data);
		
		// make ajax request
		// instead of going through the acf.ajax object to make requests like in <5.7
		// we need to do a lot of the work ourselves, but other than the method that's called
		// this has not changed much
		this.request = $.ajax({
			url: acf.get('ajaxurl'), // acf stored value
			data: data,
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (!json) {
					return;
				}
				// add the new options to the city field
				for(i=0; i<json.length; i++) {
					var city_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
					city_select.append(city_item);
				}
			}
		});
		
	}
