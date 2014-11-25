<?php
	global $wpdb;
	
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
	$cart = SimpleShop::current_cart();
	$settings = json_decode( get_option( "simpleshop_settings" ) );
	$output = array( "error" => "Fail." );

	if ($settings->local && $cart) {
		$wpdb->update( SIMPLESHOP_CARTS, array( 
			"local" => $_POST["local"] == "true",
			"updated_at" => $now
		), array( "id" => $cart->id ));
		$output = SimpleShop::current_cart();
	}
	
	die(json_encode($output));
?>