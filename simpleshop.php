<?php
/*
Plugin Name: SimpleShop
Plugin URI: http://WPSimpleShop.com
Description: SimpleShop is a lightweight e-commerce plugin that integrates seamlessly with Stripe.
Version: 1.0.0
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Text Domain: simpleshop
Requires at least: 3.6
Tested up to: 4.0.1
Stable tag: trunk
License: MIT

Copyright (c) 2014 Dallas Read.
*/

ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(-1);

class SimpleShop {
  public static $simpleshop_instance;
	const version = '1.0.0';
	const debug = true;

  public static function init() {
    if ( is_null( self::$simpleshop_instance ) ) { self::$simpleshop_instance = new SimpleShop(); }
    return self::$simpleshop_instance;
  }

  private function __construct() {
		global $wpdb;
		define('SIMPLESHOP_CARTS', $wpdb->prefix . "simpleshop_carts");
		define('SIMPLESHOP_ITEMS', $wpdb->prefix . "simpleshop_items");
		
		register_activation_hook( __FILE__, array( $this, 'add_roles' ) );
		
		add_action( 'init', array($this, 'init_plugin') );
		add_action( "admin_init", array( $this, "admin_enqueue_scripts") );
		add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts') );
		add_action( 'add_meta_boxes' , array($this, 'add_meta_boxes') );
		add_action( 'save_post', array($this, 'save_product') );
		add_action( 'plugins_loaded', array( $this, 'update_db' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu') );
		
		add_action( "show_user_profile", array( $this, "user_profile_fields" ) );
		add_action( "edit_user_profile", array( $this, "user_profile_fields" ) );
		add_action( "user_new_form", array( $this, "user_profile_fields" ) );
		add_action( "user_register", array( $this, "save_user_profile_fields" ) );
		add_action( "personal_options_update", array( $this, "save_user_profile_fields" ) );
		add_action( "edit_user_profile_update", array( $this, "save_user_profile_fields" ) );
		
		add_action( 'wp_ajax_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_remove_from_cart', array( $this, 'remove_from_cart' ) );
		add_action( 'wp_ajax_nopriv_remove_from_cart', array( $this, 'remove_from_cart' ) );
		add_action( 'wp_ajax_calculate_product_price', array( $this, 'calculate_product_price' ) );
		add_action( 'wp_ajax_nopriv_calculate_product_price', array( $this, 'calculate_product_price' ) );
		add_action( 'wp_ajax_pickup_locally', array( $this, 'pickup_locally' ) );
		add_action( 'wp_ajax_nopriv_pickup_locally', array( $this, 'pickup_locally' ) );
		add_action( 'wp_ajax_change_quantity', array( $this, 'change_quantity' ) );
		add_action( 'wp_ajax_nopriv_change_quantity', array( $this, 'change_quantity' ) );
		add_action( 'wp_ajax_mark_complete', array( $this, 'mark_complete' ) );
		add_action( 'wp_ajax_nopriv_mark_complete', array( $this, 'mark_complete' ) );
		
		add_shortcode( 'add_to_cart', array($this, 'add_to_cart_shortcode') );
		add_shortcode( 'product_variants', array($this, 'product_variants_shortcode') );
		add_shortcode( 'product', array($this, 'product_shortcode') );
		add_shortcode( 'cart', array($this, 'cart_shortcode') );
  }
	
	public static function admin_enqueue_scripts() {
		wp_register_script( "simpleshop_selectize", plugins_url("admin/js/vendor/selectize.min.js", __FILE__) );
		wp_register_script( "simpleshop", plugins_url("admin/js/simpleshop.min.js", __FILE__) );
		wp_enqueue_script( array( "jquery", "wp-color-picker", "simpleshop_selectize", "simpleshop" ) );
		
		wp_register_style( "simpleshop", plugins_url("admin/css/simpleshop.min.css", __FILE__) );
		wp_enqueue_style( array( "simpleshop" ) );
	}
	
	public static function wp_enqueue_scripts() {
		$settings = json_decode( get_option( 'simpleshop_settings' ) );
				
		wp_register_script( "simpleshop", plugins_url("public/js/simpleshop.min.js", __FILE__) );
		wp_enqueue_script( array( "jquery", "simpleshop" ) );
		wp_localize_script( 'simpleshop', 'SimpleShopAjax', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'stripe_publishable' => $settings->stripe_publishable
		));
		
		wp_register_style( "simpleshop", plugins_url("public/css/simpleshop.min.css", __FILE__) );
		wp_enqueue_style( array( "simpleshop" ) );
	}
	
	public static function admin_menu() {
		add_options_page('SimpleShop', 'SimpleShop', 'manage_simpleshop', 'simpleshop_settings', array( 'SimpleShop', 'settings_page' ));
		add_menu_page( 'Orders', 'Orders', 'manage_simpleshop', 'simpleshop_orders', array( 'SimpleShop', 'orders' ), '', 35 );
	}
	
	public static function settings_page() {
		require 'admin/php/settings/index.php';
	}
	
	public static function carts( $status = "processing", $count = false ) {
		global $wpdb;
		
		$fields = "*";
		if ($count) { $fields = "COUNT(*)"; }
		
		$query = sprintf(
			"SELECT $fields FROM %s WHERE status = '%s' ORDER BY updated_at DESC",
			SIMPLESHOP_CARTS,
			$status
		);

		if ($count) {
			$carts = $wpdb->get_var($query);
		} else {
			$carts = $wpdb->get_results($query);
		}
		
		return $carts;
	}
	
	public static function mark_complete() {
		global $wpdb;
		$wpdb->update( SIMPLESHOP_CARTS, array(
			"updated_at" => date("Y-m-d H:i:s", current_time("timestamp")),
			"status" => $_REQUEST["status"]
		), array( "id" => $_REQUEST["cart_id"] ));
		die("{}");
	}
	
	public static function pretty_address( $cart ) {
		return require 'admin/php/templates/pretty_address.php';
	}
	
	public static function add_roles() {
		$role = get_role( 'administrator' );
		$role->add_cap( 'manage_simpleshop' );
	}
	
	public static function save_user_profile_fields( $user_id ) {
    if (!current_user_can("edit_user", $user_id)) { return false; }
    $user = new WP_User( $user_id );
    if (isset($_REQUEST["manage_simpleshop"])) { $user->add_cap( "manage_simpleshop" ); }
    else { $user->remove_cap( "manage_simpleshop" ); }
	}
	
  public static function user_profile_fields( $user ) {
		require 'admin/php/users/edit_profile_fields.php';
	}
	
	public static function update_db() {		
		return require 'admin/php/carts/create_tables.php';
	}
	
	public static function orders() {
		return require 'admin/php/orders/index.php';
	}
	
	public static function pricing_meta_box( $post ) {
		return require 'admin/php/products/meta_boxes/pricing.php';
	}
	
	public static function variant_meta_box( $post ) {
		return require 'admin/php/products/meta_boxes/variants.php';
	}
	
	public static function shortcodes_meta_box( $post ) {
		return require 'admin/php/products/meta_boxes/shortcodes.php';
	}
	
	public static function add_meta_boxes() {
		return require 'admin/php/products/meta_boxes/add_meta_boxes.php';
	}
	
	public static function init_plugin() {
		if (!is_admin()) {
			$cart = SimpleShop::current_cart();

			if (!isset($_COOKIE['simpleshop_cart']) || ($cart && $cart->status != "pending")) {
				$token = md5(uniqid(mt_rand(), true));
				$_COOKIE['simpleshop_cart'] = $token;
				setcookie( 'simpleshop_cart', $token, time() * 2, COOKIEPATH, COOKIE_DOMAIN );
			}
		}
		
		return require 'admin/php/products/create_post_type.php';
	}
	
	public static function save_product( $post_id ) {
		return require 'admin/php/products/save_product.php';
	}
	
	public static function price_for_cart( $cart ) {
		return require 'admin/php/carts/price_for_cart.php';
	}
	
	public static function add_to_cart_shortcode( $attrs ) {
		$is_button = true;
		require 'public/php/products/shortcodes/product.php';
	}
	
	public static function cart_shortcode() {
		$cart = SimpleShop::current_cart();
		$settings = json_decode( get_option( "simpleshop_settings" ) );

		if ($cart->status != "pending") {
			echo "Your order <strong>" . substr(strtoupper($cart->token), -7) . "</strong> is processing.";
		} else {
			if (isset($_REQUEST["checkout"]) && isset($_REQUEST["complete"])) {
				$place_order = SimpleShop::place_order();
			
				if (empty($place_order["errors"])) {
					require 'public/php/carts/shortcodes/complete.php';				
				} else {
					require 'public/php/carts/shortcodes/checkout.php';
				}
			} else if (isset($_REQUEST["checkout"])) {
				require 'public/php/carts/shortcodes/checkout.php';
			} else {
				require 'public/php/carts/shortcodes/cart.php';
			}
		}
	}
	
	public static function place_order() {
		return require 'public/php/carts/place_order.php';
	}
	
	public static function product_variants_shortcode( $attrs ) {
		echo htmlspecialchars( get_post_meta( $attrs["id"], "variants", true) );
	}
	
	public static function product_shortcode( $attrs ) {
		require 'public/php/products/shortcodes/product.php';
	}
	
	public static function add_to_cart() {
		return require 'public/php/carts/add_to_cart.php';
	}
	
	public static function price_for_product( $attrs ) {
		return require 'admin/php/products/price_for_product.php';
	}
	
	public static function current_cart( $cart = false ) {
		return require 'admin/php/carts/current_cart.php';
	}
	
	public static function calculate_product_price() {
		$attrs = $_REQUEST;
		die(json_encode(require 'admin/php/products/price_for_product.php'));
	}
	
	public static function remove_from_cart() {
		return require 'public/php/carts/remove_from_cart.php';
	}
	
	public static function pickup_locally() {
		return require 'public/php/carts/pickup_locally.php';
	}
	
	public static function change_quantity() {
		return require 'public/php/carts/change_quantity.php';
	}
	
	public static function build_swatch( $colours, $selected = false, $id = false, $size = 32 ) {
		require 'public/php/products/build_swatch.php';
	}
	
	public static function receipt( $cart ) {
		return require 'admin/php/templates/receipt.php';
	}
}

SimpleShop::init();

?>
