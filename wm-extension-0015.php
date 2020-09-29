<?php
/*
 * Plugin Name: Woomelly Extension 015 Add ons 
 * Version: 1.0.1
 * Plugin URI: https://woomelly.com
 * Description: Extension that allows replicating only one of the publications with the same name taking into account the type of publication.
 * Author: Team MakePlugins
 * Author URI: https://woomelly.com
 * Requires at least: 4.0
 * Tested up to: 5.1.1
 * WC requires at least: 3.0.0
 * WC tested up to: 3.5.6
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'wm_before_sync_meli_to_woo_015' ) ) {
	add_action( 'wmfilter_before_sync_meli_to_woo', 'wm_before_sync_meli_to_woo_015', 10, 3 );
	function wm_before_sync_meli_to_woo_015( $status, $data_item, $wc_product_exist ) {
		$wmothers = false;
		$wmothers = wm_get_product_by_name_015( $wc_product_exist->get_name() );
		if ( $wmothers == true && $data_item->listing_type_id == "gold_pro" ) {
			//$wc_product_exist->set_catalog_visibility('hidden');
			//$wc_product_exist->save();
			wp_trash_post( $wc_product_exist->get_id() );
		}
		return true;
	}
}

if ( ! function_exists( 'action_woomelly_import_product_to_meli_woo_015' ) ) {
	add_action( 'action_woomelly_import_product_to_meli', 'action_woomelly_import_product_to_meli_woo_015', 10, 3 );
	function action_woomelly_import_product_to_meli_woo_015( $wm_product, $data_item, $wc_product_exist ) {
		$wmothers = wm_get_product_by_name_015( $wc_product_exist->get_name() );
		if ( $wmothers == true && $data_item->listing_type_id == "gold_pro" ) {
			//$wc_product_exist->set_catalog_visibility('hidden');
			//$wc_product_exist->save();
			wp_trash_post( $wc_product_exist->get_id() );
		}
	}
}

if ( ! function_exists( 'wm_get_product_by_name_015' ) ) {
	function wm_get_product_by_name_015( $name ) {
		global $wpdb;
		$same_product = false;
		$all_product_query = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='product' AND (post_status='publish' OR post_status='private' OR post_status='pending');", OBJECT );
		if ( !empty($all_product_query) ) {
			$b = sanitize_title($name);
			foreach ( $all_product_query as $value ) {
				$a = sanitize_title($value->post_title);
				if ( strcmp($a, $b) == 0 ) {
					$same_product = true;
					break;
				}
			}
		}
		return	$same_product;
	}
} //End wm_get_product_by_name_015()
?>