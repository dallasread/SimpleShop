<?php
	global $wpdb;
	
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
	$cart = SimpleShop::current_cart();
	$settings = json_decode( get_option( "simpleshop_settings" ) );
	$output = array( "error" => "Fail." );

	if ($cart) {
		$wpdb->update( SIMPLESHOP_ITEMS, array(
			"quantity" => $_REQUEST["quantity"]
		), array( "cart_token" => $cart->token, "id" => $_REQUEST["item_id"] ));
				
		$wpdb->update( SIMPLESHOP_CARTS, array(
			"updated_at" => $now
		), array( "id" => $cart->id ));
		
		$output = SimpleShop::current_cart();
		
		foreach ($output->items as $item) {
			if ($item->id == (integer) $_REQUEST["item_id"]) {
				$output->item_price = $item->price;
			}
		}
	}
	
	die(json_encode($output));
?>