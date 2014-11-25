<?php
	
	if (isset($_POST["max_shipping"])) {
		unset($_POST["submit"]);
		$_POST["local"] = isset($_POST["local"]);
		$_POST["use_js"] = isset($_POST["use_js"]);
		update_option( "simpleshop_settings", json_encode($_POST) );
		$saved = true;
	}

	$settings = json_decode( get_option( "simpleshop_settings", json_encode( array(
		"local" => 0,
		"use_js" => 0,
		"max_shipping" => 50,
		"cart_page_id" => "",
		"stripe" => "",
	))));
	
?>

<div class="wrap">
	<h2>SimpleShop</h2>
	<hr>
	
	<?php if (isset($saved)) { ?>
		<div class="updated">
			Your SimpleShop settings have been updated!
	  </div>
	<?php } ?>
	
	<form method="post" action="options-general.php?page=simpleshop" novalidate="novalidate">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="local">Offer local pickup</label></th>
					<td>
						<input name="local" type="checkbox" id="local" value="1" <?php if ($settings->local) { echo "checked='checked'"; } ?>>
						<p class="description">If the customer selects Local Pickup, there are no shipping costs and their address will not required.</p>
					</td>
				</tr>
				<tr>
					<th><label for="use_js">Use Javascript</label></th>
					<td>
						<input name="use_js" type="checkbox" id="use_js" value="1" <?php if ($settings->use_js) { echo "checked='checked'"; } ?>>
						<p class="description">With Javascript, customers will see a smooth notification when they add something to their cart. Without Javascript, customers will be redirected to their Cart.</p>
					</td>
				</tr>
				<tr>
					<th><label for="max_shipping">Max Shipping Amount</label></th>
					<td>
						<input name="max_shipping" type="text" id="max_shipping" value="<?php echo $settings->max_shipping; ?>" class="regular-text">
						<p class="description">Use this to cap shipping at a certain amount. Leave blank for no limit.</p>
					</td>
				</tr>
				<tr>
					<th><label for="cart_page_id">Cart Page</label></th>
					<td>
						<select name="cart_page_id" id="cart_page_id">
							<option value="" <?php if ($settings->cart_page_id == "") { echo "selected='selected'"; } ?>>None</option>
							<?php foreach (get_pages() as $page) { ?>
								<option value="<?php echo $page->ID; ?>" <?php if ((integer) $settings->cart_page_id == $page->ID) { echo "selected='selected'"; } ?>><?php echo $page->post_title; ?></option>
							<?php } ?>
						</select>
						<p class="description">Which page is the [cart] shortcode on?</p>
					</td>
				</tr>
				<tr>
					<th><label for="stripe">Stripe API Key</label></th>
					<td>
						<input name="stripe" type="text" id="stripe" value="<?php echo $settings->stripe; ?>" class="regular-text">
						<p class="description">In a few words, explain what this site is about.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>