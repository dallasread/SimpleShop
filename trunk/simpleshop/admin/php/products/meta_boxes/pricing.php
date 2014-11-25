<div class="pricing" data-pricing="<?php echo htmlspecialchars( get_post_meta( $post->ID, "pricing", true, json_encode(array())) ); ?>">
	<table class="form-table">
		<tr>
			<th><label for="price">Price</label></th>
			<td><input id="price" name="price" value="<?php echo get_post_meta( $post->ID, "price", true ); ?>" class="is_decimal"></td>
		</tr>
		<tr>
			<th><label for="shipping">Shipping</label></th>
			<td><input id="shipping" name="shipping" value="<?php echo get_post_meta( $post->ID, "shipping", true ); ?>" class="is_decimal"></td>
		</tr>
	</table>

	<p>
		For more complex pricing or shipping costs, select the variant (or combination of variants) and enter their price. 
		To leave the price or shipping costs at their default, just leave the respective field blank.
	</p>

	<table class="wp-list-table widefat fixed">
		<thead>
			<tr class="alternate">
				<th style="width: 65%; ">Variants</th>
				<th style="width: 15%; " class="center">Price</th>
				<th style="width: 15%; " class="center">Shipping</th>
				<th style="width: 5%; "></th>
			</tr>
		</thead>
		<tbody>
			<script class="template" type="text/html">
				<tr class="tier">
					<td>
						<input type="text" name="pricing[][options]" class="pricing_selectize options" placeholder="red,blue">
					</td>
					<td>
						<input type="text" name="pricing[][price]" class="price is_decimal">
					</td>
					<td>
						<input type="text" name="pricing[][shipping]" class="shipping is_decimal">
					</td>
					<td class="center no_padding">
						<a href="#" class="remove">
							<span class="dashicons dashicons-trash"></span>
						</a>
					</td>
				</tr>
			</script>
		</tbody>
		<tfoot>
			<tr>
				<td>
					<a href="#" class="button add" data-add="pricing">Add Pricing Tier</a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>