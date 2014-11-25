<?php
	global $wpdb;
	
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
	$cart = SimpleShop::current_cart();
	$settings = json_decode( get_option( "simpleshop_settings" ) );
	$output = array( "error" => "Fail." );
	
	if ($cart) {
		if ($wpdb->delete( SIMPLESHOP_ITEMS, array( 'ID' => $_REQUEST["id"], "cart_token" => $cart->token ) )) {
			if ($wpdb->update( SIMPLESHOP_CARTS, array( "updated_at" => $now ), array( "id" => $cart->id ))) {
				if (!$settings->use_js) {
					wp_redirect( get_permalink($settings->cart_page_id), 302 );
					exit;
				} else {
					$output = SimpleShop::price_for_cart();
				}
			}
		}
	} else {
		$output = array("error" => "No cart found.");
	}
	
	die(json_encode($output));
?>