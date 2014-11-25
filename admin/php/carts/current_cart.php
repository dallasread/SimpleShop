<?php
	global $wpdb;
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
	$settings = json_decode( get_option( "simpleshop_settings" ) );
	$cart = false;
	
	if (isset($_COOKIE['simpleshop_cart'])) {
		$cart = $wpdb->get_row(sprintf(
			"SELECT * FROM %s WHERE token = '%s'", 
			SIMPLESHOP_CARTS,
			$_COOKIE['simpleshop_cart']
		));
	}
	
	
	if (!$cart) {
		$cart_attrs = array(
	    "token" => $_COOKIE['simpleshop_cart'],
			"local" => 0,
	    "created_at" => $now,
	    "updated_at" => $now
	  );
		if ( $wpdb->insert( SIMPLESHOP_CARTS, $cart_attrs ) ) {
			$cart = (object) $cart_attrs;
			$cart->id = $wpdb->insert_id;
		}
	}
	
	if ($cart) {
		$cart->items_count = 0;
		$cart->subtotal = 0;
		$cart->tax = 0;
		$cart->shipping = 0;
		
		$cart->items = $wpdb->get_results(sprintf(
			"SELECT * FROM %s WHERE cart_token = '%s'",
			SIMPLESHOP_ITEMS,
			$cart->token
		));
		
		foreach ($cart->items as $key => $item) {
			$cart->items[$key]->variants = json_decode($item->variants);
			$cart->items[$key]->product = get_the_title( $item->product_id );
			$cart->items_count += (integer) $item->quantity;
			
			$price_attrs = clone $item->variants;
			$price_attrs->id = $item->product_id;
			$price_attrs->quantity = $item->quantity;
			$pricing = SimpleShop::price_for_product( $price_attrs );
			
			$cart->items[$key]->price = $pricing["price"];
			$cart->items[$key]->shipping = $pricing["shipping"];
			$cart->subtotal += $pricing["price"];
			$cart->shipping += $pricing["shipping"];
		}

		if ($cart->local) {
			$cart->shipping = 0;
		} else {
			if ($settings->max_shipping != "" && $cart->shipping > (integer) $settings->max_shipping) { $cart->shipping = $settings->max_shipping; }
		}
		
		$cart->shipping = number_format($cart->shipping, 2);
		$cart->subtotal = number_format($cart->subtotal, 2);
		$cart->tax = number_format($cart->tax, 2);
		$cart->total = number_format($cart->subtotal + $cart->tax + $cart->shipping, 2);
	}
	
	return isset($cart) ? $cart : false;
?>