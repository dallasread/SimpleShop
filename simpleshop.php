<?php
/*

Plugin Name: SimpleShop
Plugin URI: http://SimpleShop.guru
Description: SimpleShop is a suite of high-converting tools that help you to engage your visitors, personalize customer connections, and boost your profits.
Version: 1.0.0
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Text Domain: simpleshop
Requires at least: 3.6
Tested up to: 4.0
Stable tag: trunk
License: MIT

Copyright (c) 2014 Dallas Read.
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

class SimpleShop {
  public static $simpleshop_instance;
	const version = '0.0.1';
	const debug = true;

  public static function init() {
    if ( is_null( self::$simpleshop_instance ) ) { self::$simpleshop_instance = new SimpleShop(); }
    return self::$simpleshop_instance;
  }

  private function __construct() {
		add_action( 'init', array($this, 'create_post_types') );
		add_action( "admin_init", array( $this, "admin_enqueue_scripts") );
		add_action( 'add_meta_boxes' , array($this, 'add_meta_boxes') );
		add_action( 'save_post', array($this, 'save_product') );
		
		add_action( 'wp_ajax_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_add_to_cart', array( $this, 'add_to_cart' ) );
		
		add_shortcode( 'add_to_cart', array($this, 'add_to_cart_shortcode') );
		add_shortcode( 'product_variants', array($this, 'product_variants_shortcode') );
		add_shortcode( 'product', array($this, 'product_shortcode') );
  }
	
	public static function admin_enqueue_scripts() {
		wp_register_script( "simpleshop_selectize", plugins_url("admin/js/vendor/selectize.min.js", __FILE__) );
		wp_register_script( "simpleshop", plugins_url("admin/js/simpleshop.min.js", __FILE__) );
		wp_enqueue_script( array( "jquery", "wp-color-picker", "simpleshop_selectize", "simpleshop" ) );
		
		wp_register_style( "simpleshop", plugins_url("admin/css/simpleshop.min.css", __FILE__) );
		wp_enqueue_style( array( "simpleshop" ) );
	}
	
	public static function pricing_meta_box( $post ) {
		require 'admin/php/products/meta_boxes/pricing.php';
	}
	
	public static function variant_meta_box( $post ) {
		require 'admin/php/products/meta_boxes/variants.php';
	}
	
	public static function shortcodes_meta_box( $post ) {
		require 'admin/php/products/meta_boxes/shortcodes.php';
	}
	
	public static function add_meta_boxes() {
		require 'admin/php/products/meta_boxes/add_meta_boxes.php';
	}
	
	public static function create_post_types() {
		require 'admin/php/create_post_types.php';
	}
	
	public static function save_product( $post_id ) {
		require 'admin/php/products/save_product.php';
	}
	
	public static function add_to_cart_shortcode( $attrs ) {
		require 'admin/php/products/shortcodes/add_to_cart.php';
	}
	
	public static function product_variants_shortcode( $attrs ) {
		echo htmlspecialchars( get_post_meta( $attrs["id"], "variants", true) );
	}
	
	public static function product_shortcode( $attrs ) {
		require 'admin/php/products/shortcodes/product.php';
	}
	
	public static function add_to_cart() {
		require 'admin/php/products/add_to_cart.php';
	}
}

SimpleShop::init();

?>
