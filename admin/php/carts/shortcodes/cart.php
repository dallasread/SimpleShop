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
				<tr>
					<td><?php echo $item->quantity; ?></td>
					<td>
						<?php echo $item->product; ?>
						<ul>
							<?php foreach ($item->variants as $key => $value) { ?>
								<li><?php echo ucfirst($key); ?>: <strong><?php echo ucfirst($value); ?></strong></li>
							<?php } ?>
						</ul>
					</td>
					<td><?php
						$item->variants->id = $item->product_id; 
						$item->variants->quantity = $item->quantity; 
						echo SimpleShop::price_for_product( $item->variants )["price"]; ?></td>
					<td><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=remove_from_cart&id=<?php echo $item->id; ?>">x</a></td>
				</tr>
			<?php	} ?>
		<?php } ?>
	</tbody>
</table>

<form action="<?php echo get_permalink($settings->cart_page_id); ?>" method="post">
	<input type="hidden" name="checkout" value="true">
	<button type="submit">
		Checkout
	</button>
</form>