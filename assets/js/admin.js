jQuery( function ( $ ) {
	'use strict';

	var renewals_wrapper = $( '.wcs_number_payments_field' ),
		renewals_field = $( '#wcs_number_payments' ),
		allow_subscriptions_wrapper = $( '.subscription_coupon_allow_subscriptions_wrapper' ),
		allow_subscriptions_field = $( '#subscription_coupon_allow_subscriptions' ),
		first_cycle_only_wrapper = $( '.subscription_coupon_allow_subscriptions_first_cycle_only_wrapper' );


	$( '#discount_type' ).on( 'change', function () {
		update_status();
	} );

	allow_subscriptions_field.change( function () {
		update_status();
	} );

	function update_status() {
		var selectedOption = $( '#discount_type' ).find( ':selected' ).val();
		if ( [ 'fixed_cart', 'fixed_product', 'percent' ].indexOf( selectedOption ) >= 0 ) {
			allow_subscriptions_wrapper.show();
			if ( allow_subscriptions_field.is( ':checked' ) ) {
				first_cycle_only_wrapper.show();
			} else {
				first_cycle_only_wrapper.hide();
			}
		} else {
			allow_subscriptions_field.prop( 'checked', false );
			allow_subscriptions_wrapper.hide();

			first_cycle_only_wrapper.prop( 'checked', false );
			first_cycle_only_wrapper.hide();
		}
	}

	allow_subscriptions_wrapper.insertBefore( renewals_wrapper );
	first_cycle_only_wrapper.insertBefore( renewals_wrapper );
	update_status();
} );
