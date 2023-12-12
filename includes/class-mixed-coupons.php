<?php

class Mixed_Coupons {
	public static function init() {
		add_filter( 'wcs_bypass_coupon_removal', [ __CLASS__, 'enable_subscription_coupon' ], 999, 5 );
		add_action( 'woocommerce_coupon_options', [ __CLASS__, 'add_admin_coupon_fields' ], 10 );
		add_action( 'woocommerce_coupon_options_save', [ __CLASS__, 'save_coupon_fields' ], 10 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_scripts' ] );
	}

	public static function enable_subscription_coupon( $bypass_default_checks, $coupon, $coupon_type, $calculation_type, $cart ) {

		if ( $coupon->get_meta( '_allow_subscriptions' ) !== 'yes' ) {
			return $bypass_default_checks;
		}

		// Bypass this check if a third-party has already opted to bypass default conditions.
		if ( $bypass_default_checks ) {
			return $bypass_default_checks;
		}

		// Special handling for a single payment coupon.
		if ( 'recurring_total' === $calculation_type && $coupon->get_meta( '_apply_to_first_cycle_only' ) === 'yes' && 0 < WC()->cart->get_coupon_discount_amount( $coupon->get_code() ) ) {
			$cart->remove_coupon( $coupon->get_code() );
		}

		return true;
	}

	public static function add_admin_coupon_fields( $id ) {
		$coupon = new WC_Coupon( $id );

		woocommerce_wp_checkbox(
			array(
				'id' => 'subscription_coupon_allow_subscriptions',
				'label' => __('Allow Subscriptions', 'woocommerce-subscriptions'),
				'desc_tip' => true,
				'description' => __('When enabled will allow this coupon to be used in subscriptions.', 'woocommerce-subscriptions'),
				'wrapper_class' => 'subscription_coupon_allow_subscriptions_wrapper',
				'value' => wc_bool_to_string( $coupon->get_meta( '_allow_subscriptions' ) ),
			)
		);

		woocommerce_wp_checkbox(
			array(
				'id' => 'subscription_coupon_first_cycle_only',
				'label' => __('Limit discount to the first payment', 'woocommerce-subscriptions'),
				'desc_tip' => true,
				'description' => __('When enabled will not apply the coupon to renewals.', 'woocommerce-subscriptions'),
				'wrapper_class' => 'subscription_coupon_allow_subscriptions_first_cycle_only_wrapper',
				'value' => wc_bool_to_string( $coupon->get_meta( '_apply_to_first_cycle_only' ) ),
			)
		);
	}

	public static function save_coupon_fields ( $id ) {
		// Check the nonce (again).
		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) {
			return;
		}

		$coupon = new WC_Coupon( $id );
		$coupon->update_meta_data( '_allow_subscriptions', wc_clean( $_POST['subscription_coupon_allow_subscriptions'] ), true );
		$coupon->update_meta_data( '_apply_to_first_cycle_only', wc_clean( $_POST['subscription_coupon_first_cycle_only'] ), true );
		$coupon->save();
	}

	public static function enqueue_admin_scripts() {
		wp_enqueue_script('subscription-coupon-admin', WOO_MIXED_COUPONS_URL . 'assets/js/admin.js', array('jquery'), '1.0', true);
	}
}
