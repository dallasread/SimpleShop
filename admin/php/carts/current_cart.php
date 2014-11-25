<?php
	global $wpdb;
	$now = date("Y-m-d H:i:s", current_time("timestamp"));
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
		$cart->items = $wpdb->get_results(sprintf(
			"SELECT * FROM %s WHERE cart_token = '%s'",
			SIMPLESHOP_ITEMS,
			$cart->token
		));
		
		foreach ($cart->items as $key => $item) {
			$cart->items[$key]->variants = json_decode($item->variants);
			$cart->items[$key]->product = get_the_title( $item->product_id );
			$cart->items_count += (integer) $item->quantity;
		}
	}
	
	return isset($cart) ? $cart : false;
?>