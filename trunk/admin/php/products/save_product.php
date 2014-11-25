<?php
	if (isset($_POST["price"])) {
		if ($_POST["price"] != "") { update_post_meta( $post_id, "price", number_format(esc_attr($_POST["price"]), 2) ); }
		if ($_POST["shipping"] != "") { update_post_meta( $post_id, "shipping", number_format(esc_attr($_POST["shipping"]), 2) ); }
		update_post_meta( $post_id, "local", isset($_POST["local"]) );
	}
	
	if (isset($_POST["variants"])) {
		$variants = $_POST["variants"];
		foreach ($variants as $key => $variant) {
			$options = explode(",", $variant["options"]);
			$variants[$key]["options"] = $options;
		}
		update_post_meta( $post_id, "variants", json_encode($variants) );
	}
	
	if (isset($_POST["pricing"])) {
		$pricing = $_POST["pricing"];
		foreach ($pricing as $key => $tier) {
			$options = explode(",", $tier["options"]);
			$options_array = array();
		
			foreach ($options as $opt) {
				$opt_split = explode(";", $opt);
				if (count($opt_split) == 2) {
					array_push($options_array, array(
						"attribute" => $opt_split[0],
						"value" => $opt_split[1]
					));
				}
			}
		
			$pricing[$key]["options"] = $options_array;
			if ($pricing[$key]["price"] != "") { $pricing[$key]["price"] = number_format($pricing[$key]["price"], 2); }
			if ($pricing[$key]["shipping"] != "") { $pricing[$key]["shipping"] = number_format($pricing[$key]["shipping"], 2); }
		}
		update_post_meta( $post_id, "pricing", json_encode($pricing) );
	}
?>