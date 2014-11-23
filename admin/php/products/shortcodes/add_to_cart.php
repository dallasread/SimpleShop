<?php if (isset($attrs["id"])) { ?>
	<form action="<?php echo admin_url('admin-ajax.php'); ?>" class="add_to_cart">
		<input type="hidden" name="action" value="add_to_cart">
		
		<?php foreach ($attrs as $key => $value) { ?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		<?php } ?>
		<button type="submit">Add To Cart <span class="price_wrapper">($<span class="price">0</span>)</span></button>
	</form>
<?php } else { ?>
	<p>No Product ID Supplied.</p>
<?php } ?>