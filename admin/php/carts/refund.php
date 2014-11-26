<?php
	global $wpdb;
	$cart = SimpleShop::current_cart( (object) array(
		"token" => $_REQUEST["cart_token"]
	));
	
	if ($cart->refund_token == "") {
		$settings = json_decode( get_option( 'simpleshop_settings' ) );
		require_once __DIR__ . '/../../../public/php/vendor/stripe-php-1.17.3/lib/Stripe.php';
		Stripe::setApiKey( $settings->stripe_secret );
		
		$ch = Stripe_Charge::retrieve( $cart->charge_token );
		if (method_exists($ch, "refund")) {
			$refund = $ch->refund();
		} else {
			$refund = $ch->refunds->create();
		}
		
		if ($refund) {
			$wpdb->update( SIMPLESHOP_CARTS, array(
				"refund_token" => $refund->id,
				"updated_at" => date("Y-m-d H:i:s", current_time("timestamp"))
			), array( "id" => $cart->id ));
		}
		
		$cart = SimpleShop::current_cart( (object) array(
			"token" => $cart->token
		));
	}
	
	die(json_encode($cart));
?>