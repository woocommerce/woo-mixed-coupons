/* eslint-env jquery */
jQuery( function ( $ ) {
	'use strict';

	const renewalsWrapper = $( '.wcs_number_payments_field' ),
		allowSubscriptionsWrapper = $(
			'.subscription_coupon_allow_subscriptions_wrapper'
		),
		allowSubscriptionsField = $(
			'#subscription_coupon_allow_subscriptions'
		),
		firstCycleOnlyWrapper = $(
			'.subscription_coupon_allow_subscriptions_first_cycle_only_wrapper'
		);

	$( '#discount_type' ).on( 'change', function () {
		updateStatus();
	} );

	allowSubscriptionsField.change( function () {
		updateStatus();
	} );

	function updateStatus() {
		const selectedOption = $( '#discount_type' ).find( ':selected' ).val();
		if (
			[ 'fixed_cart', 'fixed_product', 'percent' ].indexOf(
				selectedOption
			) >= 0
		) {
			allowSubscriptionsWrapper.show();
			if ( allowSubscriptionsField.is( ':checked' ) ) {
				firstCycleOnlyWrapper.show();
			} else {
				firstCycleOnlyWrapper.hide();
			}
		} else {
			allowSubscriptionsField.prop( 'checked', false );
			allowSubscriptionsWrapper.hide();

			firstCycleOnlyWrapper.prop( 'checked', false );
			firstCycleOnlyWrapper.hide();
		}
	}

	allowSubscriptionsWrapper.insertBefore( renewalsWrapper );
	firstCycleOnlyWrapper.insertBefore( renewalsWrapper );
	updateStatus();
} );
