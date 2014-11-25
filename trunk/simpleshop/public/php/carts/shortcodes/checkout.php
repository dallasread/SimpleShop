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
	
	Name: <input type="text" name="customer_name" value="<?php echo $cart->customer_name; ?>"><br>
	Email: <input type="text" name="customer_email" value="<?php echo $cart->customer_email; ?>"><br>

	<hr>

	Address: <input type="text" name="address" value="<?php echo $cart->address; ?>"><br>
	City: <input type="text" name="city" value="<?php echo $cart->city; ?>"><br>
	Province/State: <input type="text" name="province" value="<?php echo $cart->province; ?>"><br>
	Country: <input type="text" name="country" value="<?php echo $cart->country; ?>"><br>
	Postal/Zip Code: <input type="text" name="postal_code" value="<?php echo $cart->postal_code; ?>"><br>

	<hr>

	<?php if ($cart->card_token != "") { ?>
		<div class="no_card_fields">
			<input type="hidden" id="card_token_is_set" value="true">
			The credit card <strong>************<?php echo $cart->last_four; ?></strong> is already approved.
			<a href="#" class="change_card">Change Card</a>
		</div>
	<?php } ?>
	
	<div class="card_fields" style="<?php if ($cart->card_token != "") { echo "display: none; "; }?>">
		Card Number: <input type="text" data-stripe="number"><br>
		CVC: <input type="text" data-stripe="cvc"><br>
		Expiry Month: <input type="text" data-stripe="exp-month"><br>
		Expiry Year: <input type="text" data-stripe="exp-year">
	</div>

	<hr>

	Special Instructions: <textarea name="instructions"><?php echo $cart->instructions; ?></textarea><br>

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