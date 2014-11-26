<?php if (isset($attrs["id"])) { ?>
	<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="add_to_cart">
		<input type="hidden" name="action" value="add_to_cart">
		<input type="hidden" name="id" value="<?php echo $attrs["id"]; ?>">
		
		<?php if (isset($is_button)) { ?>
			<input type="hidden" name="quantity" id="quantity" value="<?php if (isset($attrs["quantity"])) { echo $attrs["quantity"]; } else { echo 1; } ?>">
			
			<?php foreach ($attrs as $key => $value) { ?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
			<?php } ?>
		<?php } else { ?>
		
			<?php $variants = json_decode( get_post_meta( $attrs["id"], "variants", true, json_encode(array()) ) ); ?>
			<?php foreach ($variants as $key => $value) { ?>
				<?php $id = "product_{$attrs["id"]}_{$value->permalink}"; ?>
				<?php if (isset($_GET[$value->permalink])) { $attrs[$value->permalink] = $_GET[$value->permalink]; } ?>
			
				<div class="field">
					<label for="<?php echo $id; ?>">
						<?php echo $value->attribute; ?>
						<span class="hint"><?php echo $value->description; ?></span>
					</label>
					
					<?php if (strpos($value->permalink, 'color') !== false || strpos($value->permalink, 'colour') !== false) { ?>
						<?php foreach ($value->options as $option) { ?>
							<?php echo SimpleShop::build_swatch( $option, $attrs[$value->permalink], $id ); ?>
						<?php } ?>
						
						<input type="hidden" name="<?php echo $value->permalink; ?>" id="<?php echo $id; ?>" value="<?php echo $attrs[$value->permalink]; ?>">
						<div class="simpleshop_clear_swatches"></div>
					<?php } else { ?>
						<select name="<?php echo $value->permalink; ?>" id="<?php echo $id; ?>">
							<?php foreach ($value->options as $option) { ?>
								<option value="<?php echo $option; ?>" <?php if (isset($attrs[$value->permalink]) && $attrs[$value->permalink] == $option) { echo "selected='selected'"; } ?>><?php echo $option; ?></option>
							<?php } ?>
						</select>
					<?php } ?>
				</div>
			<?php } ?>
			
			<div class="field">
				<label for="quantity">Quantity</label>
				<?php if (!isset($attrs["quantity"])) { $attrs["quantity"] = 1; } ?>
				<?php if (isset($_GET["quantity"])) { $attrs["quantity"] = $_GET["quantity"]; } ?>
				<input type="text" name="quantity" id="quantity" value="<?php echo $attrs["quantity"]; ?>">
			</div>
		
		<?php } ?>
		
		<button type="submit">
			<span class="is_not_calculating">
				Add To Cart 
				<span class="price_wrapper">
					($<span class="price"><?php echo SimpleShop::price_for_product($attrs)["clean_price"]; ?></span>)
				</span>
			</span>
			<span class="is_calculating">
				Recalculating...
			</span>
		</button>
	</form>
<?php } else { ?>
	<p>No Product ID Supplied.</p>
<?php } ?>