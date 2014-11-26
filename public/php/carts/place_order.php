<?php
	global $wpdb;
	$output = array( "errors" => array() );
	$vars = $_REQUEST;
	$settings = json_decode( get_option( 'simpleshop_settings' ) );
	
	unset($vars["checkout"]);
	unset($vars["complete"]);
		
	$cart = SimpleShop::current_cart();
	$required = array( "customer_name", "customer_email" );
	if (!$cart->local) { $required = array("customer_name", "customer_email", "address", "city", "province", "country", "postal_code", "card_token"); }
	$vars["updated_at"] = date("Y-m-d H:i:s", current_time("timestamp"));
	$wpdb->update( SIMPLESHOP_CARTS, $vars, array( "id" => $cart->id ));
	$cart_vars = (array) SimpleShop::current_cart();
	
	foreach ($required as $key) {
		if ($cart_vars[$key] == "") {
			$name = implode(" ", explode("_", ucfirst($key)) );
			if ($key == "card_token") { $name = "A valid credit card"; }
			array_push( $output["errors"], $name . " is required." );
		}
	}	
	
	if (empty($output["errors"])) {
		require_once __DIR__ . '/../vendor/stripe-php-1.17.3/lib/Stripe.php';
		Stripe::setApiKey( $settings->stripe_secret );

		try {
			$customer = Stripe_Customer::create(array(
			  "description" => $cart_vars["customer_name"],
			  "email" => $cart_vars["customer_email"],
			  "card" => $cart_vars["card_token"]
			));
			
			foreach ($cart->items as $item) {
				$price_attrs = clone $item->variants;
				$price_attrs->id = $item->product_id;
				$price_attrs->quantity = $item->quantity;
				$pricing = SimpleShop::price_for_product( $price_attrs );
				$description = $item->product;
				
				if (!empty($item->variants)) {
					$descriptions = array();
					foreach ($item->variants as $key => $value) {
						array_push( $descriptions, ucfirst($key) . ": " . ucfirst($value) );
					}
					$description .= " (" . implode(", ", $descriptions) . ")";
				}
				
				Stripe_InvoiceItem::create(array(
					"customer" => $customer->id,
					"amount" => (integer) ($pricing["price"] * 100),
					"description" => $description,
					"metadata" => (array) $item->variants,
					"currency" => $settings->currency
				));
			}
			
			if ($cart->total > 0) {
				$invoice = Stripe_Invoice::create(array(
					"customer" => $customer->id
				));
				
				$charge = $invoice->pay();
			}
			
			$headers = array();
			array_push($headers, "Content-type: text/html");
			wp_mail( "$cart_vars[customer_name] <$cart_vars[customer_email]>", "Thanks for your order!", SimpleShop::receipt( (object) $cart_vars ), $headers );
			wp_mail( $settings->email, "You received a new order!", SimpleShop::receipt( (object) $cart_vars ), $headers );
			
			$wpdb->update( SIMPLESHOP_CARTS, array(
				"status" => "processing", 
				"customer_token" => $customer->id,
				"invoice_token" => isset($invoice) ? $invoice->id : "",
				"charge_token" => isset($charge) ? $charge->charge : ""
			), array( "id" => $cart->id ));
		} catch(Exception $e) {
			$wpdb->update( SIMPLESHOP_CARTS, array( "card_token" => NULL ), array( "id" => $cart->id ));
			array_push( $output["errors"], $e->getMessage() );
			return $output;
		}
	}
	
	if (!empty($output["errors"])) {
		return $output;
	}
	
	return $output;
?>