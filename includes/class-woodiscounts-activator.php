<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Woo_Discounts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function activate() {

		global $wpdb;

		$table_name = $wpdb->get_blog_prefix() . 'wdag_discount';
		$meta_table_name = $table_name . '_meta';

		Woo_Discounts_Activator::create_database_tables($table_name, $meta_table_name); 

	}

	public static function create_database_tables($table_name, $meta_table_name){

		global $wpdb;

		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

		$discount_table = "CREATE TABLE IF NOT EXISTS {$table_name} (
			id int(11) unsigned NOT NULL auto_increment,
			discount_id varchar(30) NOT NULL,
			discount_name varchar(512) default '',
			date_from DATE,
            date_to DATE,
            discount_type varchar (11) NOT NULL default '',
			-- prod_description varchar(512) default '',
			-- cat_description varchar(512) default '',
			PRIMARY KEY (id)
		) {$charset_collate};";

		dbDelta( $discount_table );

		$discount_meta_table = "CREATE TABLE IF NOT EXISTS {$meta_table_name} (
			id int(11) unsigned NOT NULL auto_increment,
			discount_id varchar(30) NOT NULL,
			meta_key varchar(256),
			meta_value varchar(256),
			PRIMARY KEY (id)
		) {$charset_collate};";

		dbDelta( $discount_meta_table );

	}

}