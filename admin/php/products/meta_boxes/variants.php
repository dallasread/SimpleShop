<div class="variants" data-variants="<?php echo htmlspecialchars( get_post_meta( $post->ID, "variants", true) ); ?>">
	<p>Variants allow you to offer different sizes, colours, and more.</p>
	<table class="variants wp-list-table widefat fixed">
		<thead>
			<tr class="alternate">
				<th style="width: 15%; ">Attribute</th>
				<th style="width: 15%; ">Permalink</th>
				<th style="width: 65%; ">Options</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<script class="template" type="text/html">
				<tr class="variant">
					<td>
						<input type="text" name="variants[][attribute]" class="attribute" placeholder="Colour" >
					</td>
					<td>
						<input type="text" readonly="readonly" name="variants[][permalink]" class="permalink" placeholder="Colour" >
					</td>
					<td>
						<input type="text" name="variants[][options]" class="variants_selectize options" placeholder="red,blue">
						<textarea name="variants[][description]" class="description" placeholder="Description (optional)"></textarea>
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
					<a href="#" class="button add" data-add="variants">Add Variant</a>
				</td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</div>