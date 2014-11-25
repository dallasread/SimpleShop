<?php
	global $wpdb;
	
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
	$cart = SimpleShop::current_cart();
	$settings = json_decode( get_option( "simpleshop_settings" ) );
	$output = array( "error" => "Fail." );
	
	if ($cart) {
		if ($cart->status == "pending") {
			$quantity = $_REQUEST["quantity"];
			$product = get_post( $_REQUEST["id"] );
		
			if ($product) {
				unset($_REQUEST["id"]);
				unset($_REQUEST["quantity"]);
				unset($_REQUEST["action"]);
		
				$item_attrs = array(
			    "cart_token" => $cart->token,
			    "product_id" => $product->ID,
					"quantity" => $quantity,
					"variants" => json_encode($_REQUEST),
			    "created_at" => $now
			  );
		
				if ($wpdb->insert( SIMPLESHOP_ITEMS, $item_attrs )) {
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
				$output = array("error" => "No product found.");
			}
		} else {
			$output = array("error" => "Cart already in process.");
		}
	} else {
		$output = array("error" => "No cart found.");
	}
	
	die(json_encode($output));
?>