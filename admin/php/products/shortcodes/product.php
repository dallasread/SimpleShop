<?php if (isset($attrs["id"])) { ?>
	<form action="<?php echo admin_url('admin-ajax.php'); ?>" class="add_to_cart">
		<input type="hidden" name="action" value="add_to_cart">
		
		<?php $variants = json_decode( get_post_meta( $attrs["id"], "variants", true, json_encode(array()) ) ); ?>
		
		<?php foreach ($variants as $key => $value) { ?>
			<?php $id = "product_{$attrs["id"]}_{$value->permalink}"; ?>
			
			<div class="field">
				<label for="<?php echo $id; ?>"><?php echo $value->attribute; ?></label>
				<select name="<?php echo $value->permalink; ?>" id="<?php echo $id; ?>">
					<?php foreach ($value->options as $option) { ?>
						<option value="<?php echo $option; ?>" <?php if (isset($attrs[$value->permalink]) && $attrs[$value->permalink] == $option) { echo "selected='selected'"; } ?>><?php echo $option; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>
		
		<button type="submit">Add To Cart <span class="price_wrapper">($<span class="price">0</span>)</span></button>
	</form>
<?php } else { ?>
	<p>No Product ID Supplied.</p>
<?php } ?>