<div class="wrap">
	<h2>
		Orders
	</h2>
	
	<ul class="subsubsub">
		<li class="processing">
			<a href="admin.php?page=simpleshop_orders" class="<?php if (!isset($_GET["status"])) { echo "current"; } ?>">Processing <span class="count">(2)</span></a>
		</li>
		<li class="complete">
			<a href="admin.php?page=simpleshop_orders&amp;status=complete" class="<?php if (isset($_GET["status"]) && $_GET["status"] == "complete") { echo "current"; } ?>">Complete <span class="count">(2)</span></a>
		</li>
	</ul>
	
	
	<table class="wp-list-table widefat fixed pages">
		<thead>
			<tr>
				<th style="width: 20px; "></th>
				<th>Name</th>
				<th>Email</th>
				<th>Address</th>
				<th>Total</th>
				<th>Paid At</th>
			</tr>
		</thead>
		<tbody>
			<?php $odd = false; foreach (SimpleShop::carts() as $cart) { ?>
				<tr class="<?php echo ($odd = !$odd) ? "alternate" : ""; ?>">
					<td><input type="checkbox"></td>
					<td>Dallas Read</td>
					<td>dallas@excitecreative.ca</td>
					<td>61 Westfield Crescent, Cole Harbour, Nova Scotia, B2V 2W1, Canada</td>
					<td>$74.00</td>
					<td>April 5, 1988</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<br class="clear">
</div>
