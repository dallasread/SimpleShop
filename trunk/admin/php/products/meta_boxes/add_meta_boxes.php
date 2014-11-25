<?php
	add_meta_box(
		'simpleshop_variant_metabox',
		'Variants',
		array('SimpleShop', 'variant_meta_box'),
		'product',
		'advanced',
		'high'
	);

	add_meta_box(
		'simpleshop_pricing_metabox',
		'Pricing',
		array('SimpleShop', 'pricing_meta_box'),
		'product',
		'advanced',
		'high'
	);
	
	add_meta_box(
		'simpleshop_shortcodes_metabox',
		'Shortcodes',
		array('SimpleShop', 'shortcodes_meta_box'),
		'product',
		'side',
		'low'
	);
?>