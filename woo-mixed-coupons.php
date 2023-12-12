<?php
/**
 * Plugin Name: Woo Mixed Coupons
 * Description: Enable a coupon to be used both in subscriptions and in one time purchases.
 * Author: WooCommerce
 * Author URI: https://woo.com/
 * Version: 1.0.0
 */

define( 'WOO_MIXED_COUPONS_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/includes/class-mixed-coupons.php';

add_action( 'init', 'Mixed_Coupons::init' );
