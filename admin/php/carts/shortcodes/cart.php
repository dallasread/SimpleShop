<div class="cart">
	<table>
		<thead>
			<tr>
				<th>Quantity</th>
				<th>Description</th>
				<th>Amount</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if (!$cart || count($cart->items) == 0) { ?>
				<tr>
					<td colspan="4">Your cart is empty.</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($cart->items as $item) { ?>
					<tr data-item-id="<?php echo $item->id; ?>">
						<td><input type="text" class="quantity" value="<?php echo $item->quantity; ?>"></td>
						<td>
							<?php echo $item->product; ?>
							<ul>
								<?php foreach ($item->variants as $key => $value) { ?>
									<li><?php echo ucfirst($key); ?>: <strong><?php echo ucfirst($value); ?></strong></li>
								<?php } ?>
							</ul>
						</td>
						<td class="price"><?php echo $item->price; ?></td>
						<td><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=remove_from_cart&id=<?php echo $item->id; ?>">x</a></td>
					</tr>
				<?php	} ?>
			<?php } ?>
		</tbody>
	</table>

	<table>
		<tr>
			<td>Subtotal</td>
			<td class="subtotal"><?php echo $cart->subtotal; ?></td>
		</tr>
		<?php if ($settings->local) { ?>
			<tr>
				<td>
					<label for="local">Pick Up Locally</label>
				</td>
				<td>
					<input type="checkbox" id="local" <?php if ($cart->local) { echo "checked='checked'"; }?>>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td>Shipping</td>
			<td class="shipping"><?php echo $cart->shipping; ?></td>
		</tr>
		<tr>
			<td>Tax</td>
			<td class="tax"><?php echo $cart->tax; ?></td>
		</tr>
		<tr>
			<td>Total</td>
			<td class="total"><?php echo $cart->total; ?></td>
		</tr>
	</table>

	<form action="<?php echo get_permalink($settings->cart_page_id); ?>" method="post">
		<input type="hidden" name="checkout" value="true">
		<button type="submit">
			Checkout ($<span class="clean_total"><?php echo $cart->clean_total; ?></span>)
		</button>
	</form>
</div>