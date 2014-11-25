<p>
	<strong class="bigger">[product id=<?php echo $post->ID; ?> size=3]</strong>
	Display a form with all the product's variants and an Add to Cart button. Default variants can be set (eg. size=3).
</p>

<p>
	<strong class="bigger">[add_to_cart id=<?php echo $post->ID; ?> size=3]</strong>
	Displays an add to cart button. Any combination of variants can be supplied (eg. size=3).
</p>

<p>
	<strong class="bigger">[product_variants id=<?php echo $post->ID; ?>]</strong>
	Add a product's variants as an JSON string (ideal for injecting into a JS variable).
</p>