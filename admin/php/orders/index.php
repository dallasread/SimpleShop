<div class="wrap">
	<h2>
		Orders
	</h2>
	
	<ul class="subsubsub">
		<li class="processing">
			<a href="admin.php?page=simpleshop_orders" class="<?php if (!isset($_GET["status"])) { echo "current"; } ?>">Processing <span class="count">(<?php echo SimpleShop::carts( "processing", true ); ?>)</span></a>
		</li>
		<li class="complete">
			<a href="admin.php?page=simpleshop_orders&amp;status=complete" class="<?php if (isset($_GET["status"]) && $_GET["status"] == "complete") { echo "current"; } ?>">Complete <span class="count">(<?php echo SimpleShop::carts( "complete", true ); ?>)</span></a>
		</li>
	</ul>
	
	
	<table class="carts wp-list-table widefat fixed pages">
		<thead>
			<tr>
				<th style="width: 7%; ">Done</th>
				<th style="width: 15%; ">Name</th>
				<th style="width: 20%; ">Email</th>
				<th style="width: 22%; ">Address</th>
				<th style="width: 7%; text-align: center; ">Local</th>
				<th style="width: 8%; text-align: right; ">Total</th>				
				<th style="width: 13%; ">Updated At</th>
			</tr>
		</thead>
		<tbody>
			<?php $odd = false; foreach (SimpleShop::carts( isset($_REQUEST["status"]) ? $_REQUEST["status"] : "processing" ) as $cart) { ?>
				<?php $pricing = SimpleShop::price_for_cart( $cart ); ?>
				<tr data-id="<?php echo $cart->id; ?>" class="cart <?php echo ($odd = !$odd) ? "alternate" : ""; ?>">
					<td><input type="checkbox" class="mark_complete" <?php if ($cart->status == "complete") { echo "checked='checked'"; } ?>></td>
					<td><?php echo $cart->customer_name; ?></td>
					<td><?php echo $cart->customer_email; ?></td>
					<td><?php echo SimpleShop::pretty_address( $cart ); ?></td>
					<td style="text-align: center; "><?php if ($cart->local) { echo "&check; "; } ?></td>
					<td style="text-align: right; ">$<?php echo $pricing->total; ?></td>					
					<td><?php echo explode(" ", $cart->updated_at)[0]; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<br class="clear">
</div>
