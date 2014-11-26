<?php $settings = json_decode( get_option( "simpleshop_settings" ) ); ob_start(); ?>
<style type="text/css" media="screen">
	body {
		background: #eee;
		font-family: Helvetica Neue, Helvetica, Arial, Sans serif;
		margin: 40px 0 60px;
	}
	
	.simpleshop_swatch {
		display: block !important;
	}
	
	.receipt {
		background: #fff;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
		padding: 20px;		
		position: relative;
	}
	
	.container {
		max-width: 450px;
		margin: 0 auto;
	}
	
	.message {
		color: #333;
		text-align: center;
		margin: 0px auto 30px;
		font-size: 15px;
		line-height: 23px;
	}
	
	.message.last_message a {
		color: #777;
	}
	
	.message.last_message {
		margin-top: 30px;
		font-size: 13px;
		line-height: 21px;
	}
	
	.refunded {
		color: red;
		padding: 10px;
		border: 7px solid red;
		font-weight: bold;
		font-size: 32px;
		line-height: 32px;
		position: absolute;
		border-radius: 7px;
		text-transform: uppercase;
		right: 20px;
		top: 50px;
	}
	
	table {
		border-collapse: collapse;
	}

	td, th {
		padding: 7px;
		border-bottom: 1px solid #ddd;
		text-align: left;
	}
	
	tr:last-child td {
		border-bottom: 0 none;
	}
	
	th {
		font-size: 11px;
		line-height: 11px;
		text-transform: uppercase;
		background: #ddd;
	}
	
	td {
		font-size: 13px;
		line-height: 18px;
	}
	
	tr.total_row td, .title {
		font-size: 16px;
		line-height: 22px;
		font-weight: bold;
	}
	
	td ul {
		margin: 0px 0 0 -20px;
	}
	
	.simpleshop_swatch {
		display: inline-block;
	}
</style>
<p class="message container">
	<strong>We've received your order and are processing it now!</strong><br><br>
	Here's your receipt:
</p>

<div class="receipt container">
	<?php if ($cart->refund_token != "") { ?>
		<h1 class="refunded">Refunded</h1>
	<?php } ?>
	<table style="width: 100%; ">
		<tbody>
			<tr>
				<td colspan="2">
					<span class="title"><?php bloginfo( "name" ); ?></span><br><br>
					<?php echo $cart->customer_name; ?><br>
					<?php echo $cart->customer_email; ?><br><br>
					<?php if (!$cart->local) { ?>
						<?php echo SimpleShop::pretty_address( $cart ); ?>
						<br><br>
					<?php } ?>
				</td>
				<td style="vertical-align: top; text-align: right; ">
					<?php echo explode(" ", $cart->created_at)[0]; ?>
				</td>
			</tr>
			<tr>
				<th style="width: 20%; ">Quantity</th>
				<th style="width: 50%; ">Description</th>
				<th style="width: 30%; text-align: right; ">Amount</th>
			</tr>
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
											<?php echo SimpleShop::build_swatch( $value, false, false, 18 ); ?>
										<?php } else { ?>
											<strong><?php echo ucfirst($value); ?></strong>
										<?php } ?>
									</li>
								<?php } ?>
							</ul>
						</td>
						<td style="text-align: right; " class="price">$<?php echo $item->price; ?></td>
					</tr>
				<?php	} ?>
			<?php } ?>
			
		<tr>
			<td colspan="3" style="background: #ddd; height: 4px; padding: 0px; "></td>
		</tr>
		
		<?php if ($cart->instructions != "") { ?>
			<tr>
				<td colspan="3">
					<strong>Special Instructions</strong><br>
					<?php echo $cart->instructions; ?>
				</td>
			</tr>
		<?php } ?>
		
		<?php if ($cart->local) { ?>
			<tr>
				<td colspan="2">
					<label for="local">Pick Up Locally</label>
				</td>
				<td style="text-align: right; ">
					&check;
				</td>
			</tr>
		<?php } ?>

		<tr>
			<td colspan="2">Subtotal</td>
			<td style="text-align: right; " class="subtotal">$<?php echo $cart->subtotal; ?></td>
		</tr>
		<tr>
			<td colspan="2">Shipping</td>
			<td style="text-align: right; " class="shipping">$<?php echo $cart->shipping; ?></td>
		</tr>
		<tr>
			<td colspan="2">Tax</td>
			<td style="text-align: right; " class="tax">$<?php echo $cart->tax; ?></td>
		</tr>
		<tr class="total_row">
			<td colspan="2">Total</td>
			<td style="text-align: right; " class="total">$<?php echo $cart->total; ?></td>
		</tr>
	</table>
</div>

<p class="last_message message container">
	<a href="<?php echo site_url("?simpleshop_receipt_preview={$cart->token}"); ?>">Click here to review this receipt online.</a>
</p>
<?php $return = ob_get_contents(); ob_end_clean(); return $return; ?> 