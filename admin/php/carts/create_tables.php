<?php
	if (SimpleShop::version != get_option( "simpleshop_version", "0" )) {
		global $wpdb;

		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
	
		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE $wpdb->collate";
		}

		$carts_sql = sprintf("CREATE TABLE %s (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  token VARCHAR(32) NOT NULL,
		  created_at DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  updated_at DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  PRIMARY KEY  (token), UNIQUE (id)
		) $charset_collate;
		", SIMPLESHOP_CARTS);

		$items_sql = sprintf("CREATE TABLE %s (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  cart_token VARCHAR(32) NOT NULL,
			product_id mediumint(9) NOT NULL,
			quantity mediumint(9) NOT NULL,
		  variants text,
		  created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;
		", SIMPLESHOP_ITEMS);

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $carts_sql );
		dbDelta( $items_sql );
	
		update_option( "simpleshop_version", SimpleShop::version );
	}
?>