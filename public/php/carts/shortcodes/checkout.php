<?php $cart = SimpleShop::current_cart(); ?>

<form action="<?php echo get_permalink($settings->cart_page_id); ?>" method="post" class="checkout">
	<a href="<?php echo get_permalink($settings->cart_page_id); ?>">&larr; Back to Cart</a>
	
	<hr>
	
	<input type="hidden" name="checkout" value="true">
	<input type="hidden" name="complete" value="true">
	
	<?php if (!empty($place_order["errors"])) { ?>
		<ul class="errors">
			<?php foreach ($place_order["errors"] as $error) { ?>
				<li><?php echo $error; ?></li>
			<?php } ?>
		</ul>
	<?php } ?>
	
	<div class="field">
		<label for="customer_name">Name</label>
		<input type="text" name="customer_name" id="customer_name" value="<?php echo $cart->customer_name; ?>">
	</div>
	
	<div class="field">
		<label for="customer_email">Email</label>
		<input type="text" name="customer_email" id="customer_email" value="<?php echo $cart->customer_email; ?>">
	</div>
	
	<?php if (!$cart->local) { ?>

		<hr>
	
		<div class="field">
			<label for="full_address">Shipping Address</label>
			<input type="text" id="full_address" name="full_address" value="<?php echo $cart->full_address; ?>" autocomplete="off">
		</div>

		<div class="field">
			<label for="address">Street Number &amp; Street</label>
			<input type="text" id="address" name="address" value="<?php echo $cart->address; ?>">
		</div>
		<div class="field">
			<label for="city">City</label>
			<input type="text" id="city" name="city" value="<?php echo $cart->city; ?>">
		</div>
		<div class="field">
			<label for="province">Province/State</label>
			<input type="text" id="province" name="province" value="<?php echo $cart->province; ?>">
		</div>
		<div class="field">
			<label for="country">Country</label>
			<input type="text" id="country" name="country" value="<?php echo $cart->country; ?>">
		</div>
		<div class="field">
			<label for="postal_code">Postal/Zip Code</label>
			<input type="text" id="postal_code" name="postal_code" value="<?php echo $cart->postal_code; ?>">
		</div>
	<?php } ?>

	<hr>

	<?php if ($cart->card_token != "") { ?>
		<div class="no_card_fields">
			<div class="field">
				<input type="hidden" id="card_token_is_set" value="true">
				<p>
					The credit card <strong>************<?php echo $cart->last_four; ?></strong> is already approved.
					<a href="#" class="change_card">Change Card</a>
				</p>
			</div>
		</div>
	<?php } ?>
	
	<div class="card_fields" style="<?php if ($cart->card_token != "") { echo "display: none; "; }?>">
		<div class="field">
			<label for="number">Card Number<label>
			<input type="text" id="number" data-stripe="number">
		</div>
		<div class="field">
			<label for="cvc">CVC<label>
			<input type="text" id="cvc" data-stripe="cvc">
		</div>
		<div class="field">
			<label for="exp-month">Expiry Month<label>
			<select id="exp-month" data-stripe="exp-month">
				<?php foreach (array(
						"01 - January",
						"02 - February",
						"03 - March",
						"04 - April",
						"05 - May",
						"06 - June",
						"07 - July",
						"08 - August",
						"09 - September",
						"10 - October",
						"11 - November",
						"12 - December",
					) as $month) { ?>
					<option value="<?php echo explode(" - ", $month)[0]; ?>"><?php echo $month; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="field">
			<label for="exp-year">Expiry Year<label>
			<select id="exp-year" data-stripe="exp-year">
				<?php foreach (array(2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028) as $year) { ?>
					<option><?php echo $year; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<hr>

	<div class="field">
		<label for="instructions">Special Instructions</label>
		<textarea id="instructions" name="instructions"><?php echo $cart->instructions; ?></textarea>
	</div>

	<hr>
	
	<button type="submit">
		<span class="is_not_calculating">
			Place Order
		</span>
		<span class="is_calculating">
			Processing...
		</span>
	</button>
</form>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=true"></script>