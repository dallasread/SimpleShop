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
		'rewrite' => array( 'slug' => 'products' ),
		'capability_type' => 'post',
		'capabilities' => array(
			'read_post' => 'unmanageable_simpleshop',
      'read_private_posts' => 'unmanageable_simpleshop',
      'publish_posts' => 'manage_simpleshop',
      'edit_posts' => 'manage_simpleshop',
      'edit_others_posts' => 'manage_simpleshop',
      'edit_post' => 'manage_simpleshop',
			'edit_published_posts' => 'manage_simpleshop',
			'delete_published_posts' => 'manage_simpleshop',
      'delete_posts' => 'manage_simpleshop',
      'delete_post' => 'manage_simpleshop',
      'delete_others_posts' => 'manage_simpleshop',
		),
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'product', $products );
?>