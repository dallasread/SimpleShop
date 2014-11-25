<?php
	$cart = SimpleShop::current_cart();
	
	#	subtotal: SimpleShop::price_for_cart($cart)->subtotal
	#	total: SimpleShop::price_for_cart($cart)->total
	#	shipping: SimpleShop::price_for_cart($cart)->shipping
	
	return $cart;
?>