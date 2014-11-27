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
		"max_shipping" => "",
		"email" => "",
		"cart_page_id" => "",
		"currency" => "cad",
		"stripe_secret" => "",
		"stripe_publishable" => ""
	))));
	
?>

<div class="wrap">
	<h2>SimpleShop</h2>
	<?php if (!(isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) { ?>
		<p style="color: maroon; "><strong>To maintain your PCI Compliance, We strongly recommend that you install an SSL certificate before accepting any payments. Contact your hosting provider for more information.</strong></p>
	<?php } ?>
	<hr>
	
	<?php if (isset($saved)) { ?>
		<div class="updated">
			Your SimpleShop settings have been updated!
	  </div>
	<?php } ?>
	
	<form method="post" action="options-general.php?page=simpleshop_settings">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="local">Offer local pickup</label></th>
					<td>
						<input name="local" type="checkbox" id="local" value="1" <?php if ($settings->local) { echo "checked='checked'"; } ?>>
						<p class="description">If the customer selects Local Pickup, there are no shipping costs and their address will not required.</p>
					</td>
				</tr>
				<!-- <tr>
					<th><label for="use_js">Use Javascript</label></th>
					<td>
						<input name="use_js" type="checkbox" id="use_js" value="1" <?php if ($settings->use_js) { echo "checked='checked'"; } ?>>
						<p class="description">With Javascript, customers will see a smooth notification when they add something to their cart. Without Javascript, customers will be redirected to their Cart.</p>
					</td>
				</tr> -->
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
					<th><label for="email">Email Orders</label></th>
					<td>
						<input name="email" type="text" id="email" value="<?php echo $settings->email; ?>" class="regular-text">
						<p class="description">Which email address should we send order notifications to?</p>
					</td>
				</tr>
				<tr>
					<th><label for="currency">Currency</label></th>
					<td>
						<select name="currency" id="currency">
							<?php foreach (array('cad', 'usd') as $currency) { ?>
								<option value="<?php echo $currency; ?>" <?php if ($settings->currency == $currency) { echo "selected='selected'"; } ?>><?php echo strtoupper($currency); ?></option>
							<?php } ?>
						</select>
						<p class="description">Which page is the [cart] shortcode on?</p>
					</td>
				</tr>
				<tr>
					<th><label for="stripe_secret">Stripe Secret Key</label></th>
					<td>
						<input name="stripe_secret" type="text" id="stripe_secret" value="<?php echo $settings->stripe_secret; ?>" class="regular-text">
						<p class="description">You'll find this in the Account Settings section of your Stripe account.</p>
					</td>
				</tr>
				<tr>
					<th><label for="stripe_publishable">Stripe Publishable Key</label></th>
					<td>
						<input name="stripe_publishable" type="text" id="stripe_publishable" value="<?php echo $settings->stripe_publishable; ?>" class="regular-text">
						<p class="description">You'll find this in the Account Settings section of your Stripe account.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>