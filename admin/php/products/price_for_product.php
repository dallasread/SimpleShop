<?php
	if (is_object($attrs)) { $attrs = (array) $attrs; }
	$id = is_array($attrs) ? $attrs["id"] : $attrs;
	if (is_array($attrs)) { unset( $attrs["id"] ); }
	$pricing = get_post_meta( $id, "pricing", true );
	$pricing = !empty($pricing) ? json_decode( $pricing, true ) : array();
	
	if (is_array($pricing)) {
		foreach ($pricing as $tier) {
			$attributes_to_check = array();
		
			foreach ($tier["options"] as $option) {
				if (array_key_exists($option["attribute"], $attributes_to_check)) {
					array_push($attributes_to_check[$option["attribute"]]["options"], $option["value"]);
				} else {
					$attributes_to_check[$option["attribute"]]["options"] = array( $option["value"] );
					$attributes_to_check[$option["attribute"]]["found"] = false;
				}
			}
		
			foreach($attributes_to_check as $key => $value) {
				if (array_key_exists($key, $attrs) && in_array($attrs[$key], $value["options"])) {
					unset( $attributes_to_check[$key] );
				}
			}
		
			if (empty($attributes_to_check)) {
				if ($tier["price"] != "" && !isset($price_per_unit)) {
					$price_per_unit = $tier["price"];
				}
			
				if ($tier["shipping"] != "" && !isset($shipping_per_unit)) {
					$shipping_per_unit = $tier["shipping"];
				}
			}
		}
	}
	
	if (!isset($price_per_unit) || $price_per_unit == "") {
		$price_per_unit = get_post_meta( $id, "price", true );
	}
	
	if (!isset($shipping_per_unit) || $shipping_per_unit == "") {
		$shipping_per_unit = get_post_meta( $id, "shipping", true );
	}
	
	$quantity = isset($attrs["quantity"]) ? (integer) $attrs["quantity"] : 1;
	$price = number_format($price_per_unit * $quantity, 2);
	$shipping = number_format($shipping_per_unit * $quantity, 2);

	return array(
		"price_per_unit" => $price_per_unit,
		"shipping_per_unit" => $shipping_per_unit,
		"price" => $price,
		"shipping" => $shipping,
		"clean_price" => str_replace(".00", "", $price),
		"clean_shipping" => str_replace(".00", "", $shipping)
	);
?>