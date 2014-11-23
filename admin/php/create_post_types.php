<?php
	$products = array(
		'labels' => array(
			'name' => 'Products',
			'singular_name' => 'Product',
			'menu_name' => 'Products',
			'name_admin_bar' => 'Product',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Product',
			'new_item' => 'New Product',
			'edit_item' => 'Edit Product',
			'view_item' => 'View Product',
			'all_items' => 'All Products',
			'search_items' => 'Search Products',
			'parent_item_colon' => 'Parent Products:',
			'not_found' => 'No products found.',
			'not_found_in_trash' => 'No products found in Trash.'
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'product' ),
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'thumbnail', 'excerpt', 'comments' ) //'editor',
	);

	register_post_type( 'product', $products );
?>