<?php if ($cart->status != "pending") { ?>
	Your cart has gone stale. Please reload the page.
<?php } else { ?>
	<div class="cart">
		<table>
			<thead>
				<tr>
					<th class="for_quantity quantity_header">Quantity</th>
					<th class="for_description description_header">Description</th>
					<th class="for_amount amount_header">Amount</th>
					<th class="for_delete delete_header"></th>
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
							<td class="for_quantity"><input type="text" class="quantity" value="<?php echo $item->quantity; ?>"></td>
							<td class="for_description">
								<span class="product_name"><?php echo $item->product; ?></span>
								<ul>
									<?php foreach ($item->variants as $key => $value) { ?>
										<li>
											<?php echo ucfirst($key); ?>: 
											<?php if (strpos($key, 'color') !== false || strpos($key, 'colour') !== false) { ?>
												<?php echo SimpleShop::build_swatch( $value ); ?>
											<?php } else { ?>
												<strong><?php echo ucfirst($value); ?></strong>
											<?php } ?>
										</li>
									<?php } ?>
								</ul>
							</td>
							<td class="for_amount price">$<?php echo $item->price; ?></td>
							<td class="for_delete"><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=remove_from_cart&id=<?php echo $item->id; ?>" class="delete">&times;</a></td>
						</tr>
					<?php	} ?>
				<?php } ?>
			</tbody>
		</table>

		<table class="totals">
			<?php if ($settings->local) { ?>
				<tr>
					<td class="total_label">
						<label for="local">Pick Up Locally</label>
					</td>
					<td class="total_field">
						<input type="checkbox" id="local" <?php if ($cart->local) { echo "checked='checked'"; }?>>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td class="total_label">Subtotal</td>
				<td class="total_field subtotal">$<?php echo $cart->subtotal; ?></td>
			</tr>
			<tr>
				<td class="total_label">Shipping</td>
				<td class="total_field shipping">$<?php echo $cart->shipping; ?></td>
			</tr>
			<tr>
				<td class="total_label">Tax</td>
				<td class="total_field tax">$<?php echo $cart->tax; ?></td>
			</tr>
			<tr>
				<td class="total_label">Total</td>
				<td class="total_field total">$<?php echo $cart->total; ?></td>
			</tr>
		</table>

		<form action="<?php echo get_permalink($settings->cart_page_id); ?>" method="post">
			<input type="hidden" name="checkout" value="true">
			<button type="submit">
				<span class="is_not_calculating">
					Checkout 
					<span class="clean_total_wrapper">($<span class="clean_total"><?php echo $cart->clean_total; ?></span>)</span>
				</span>
				<span class="is_calculating">
					Recalculating...
				</span>
			</button>
		</form>
	</div>
<?php } ?>