<?php $settings = json_decode( get_option( "simpleshop_settings" ) ); ob_start(); ?>
<style type="text/css" media="screen">
	table {
		border-collapse: collapse;
	}

	td, th {
		border: 1px solid #333;
		padding: 7px;
	}
	
	td ul {
		margin: 7px 0 0 -25px;
	}
	
	.simpleshop_swatch {
		display: inline-block;
	}
</style>
<div class="cart">
	<table style="width: 100%; ">
		<thead>
			<tr>
				<th>Quantity</th>
				<th>Description</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($cart->items)) { ?>
				<?php foreach ($cart->items as $item) { ?>
					<tr data-item-id="<?php echo $item->id; ?>">
						<td><?php echo $item->quantity; ?></td>
						<td>
							<?php echo $item->product; ?>
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
						<td style="text-align: right; " class="price"><?php echo $item->price; ?></td>
					</tr>
				<?php	} ?>
			<?php } ?>
		
		<?php if ($cart->instructions != "") { ?>
			<tr>
				<td colspan="3">
					<strong>Special Instructions</strong><br>
					<?php echo $cart->instructions; ?>
				</td>
			</tr>
		<?php } ?>

		<tr>
			<td colspan="2">Subtotal</td>
			<td style="text-align: right; " class="subtotal"><?php echo $cart->subtotal; ?></td>
		</tr>
		<?php if ($cart->local) { ?>
			<tr>
				<td colspan="2">
					<label for="local">Pick Up Locally</label>
				</td>
				<td style="text-align: right; ">
					Yes
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="2">Shipping</td>
			<td style="text-align: right; " class="shipping"><?php echo $cart->shipping; ?></td>
		</tr>
		<tr>
			<td colspan="2">Tax</td>
			<td style="text-align: right; " class="tax"><?php echo $cart->tax; ?></td>
		</tr>
		<tr>
			<td colspan="2">Total</td>
			<td style="text-align: right; " class="total"><?php echo $cart->total; ?></td>
		</tr>
	</table>
</div>
<?php $return = ob_get_contents(); ob_end_clean(); return $return; ?> 