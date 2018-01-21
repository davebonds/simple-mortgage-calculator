(function( $ ) {
	'use strict';

	$(document).on('click','.simple-mortgage-calculator button', function(e) {
    	e.preventDefault();
		// Show loader.
		$('.simple-mortgage-calculator .loader').show();
		$('.monthly-payment input').val('');

		// Get vars
		var principal = $('input#principal').val();
		var down_payment = $('input#down_payment').val();
		var term      = $('input#term').val();
		var apr       = $('input#apr').val();

		var data = {
			action: 'smc_ajax_callback',
			principal: principal,
			down_payment: down_payment,
			term: term,
			apr: apr
		}

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: SMC_Ajax.ajax_url,
			data: data,
			success: function(response) {
				// if ( isNaN(response) ) {
				// 	return false;
				// } else {
					// Hide loader
					$('.simple-mortgage-calculator .loader').hide();
					// Output response
					$('.monthly-payment input').val( response );
				// }
			}
		});
		
	});

	$(document).on('change', 'input#principal, input#down_payment, input#term, input#apr', function() {
    	// Show loader.
		$('.simple-mortgage-calculator .loader').show();
		$('.monthly-payment input').val('');

    	// Get vars
		var principal    = $('input#principal').val();
		var down_payment = $('input#down_payment').val();
		var term         = $('input#term').val();
		var apr          = $('input#apr').val();

		var data = {
			action: 'smc_ajax_callback',
			principal: principal,
			down_payment: down_payment,
			term: term,
			apr: apr
		}

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: SMC_Ajax.ajax_url,
			data: data,
			success: function(response) {
				// if ( isNaN(response) ) {
				// 	return false;
				// } else {
					// Hide loader
					$('.simple-mortgage-calculator .loader').hide();
					// Output response
					$('.monthly-payment input').val( response );
				// }
			}
		});
		
	});

})( jQuery );
