<?php
/*
 * Plugin Name: Woomelly Extension 015 Add ons 
 * Version: 1.0.0
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

//$wc_product_exist->set_catalog_visibility('hidden');
//$wc_product_exist->save();

if ( ! function_exists( 'wm_filter_validate_sync_add_product_ext_015' ) ) {
	add_action( 'wm_filter_validate_sync_add_product', 'wm_filter_validate_sync_add_product_ext_015', 10, 3 );
	function wm_filter_validate_sync_add_product_ext_015( $status, $data_item ) {
		$wmothers = false;
		$wmothers = wm_get_product_by_name_ext_015( $data_item->title );
		if ( $wmothers == true ) {
			if ( $data_item->listing_type_id == "gold_pro" ) {
				return false;
			}
		}
		return true;
	}
}

if ( ! function_exists( 'wmfilter_before_sync_meli_to_woo_ext_015' ) ) {
	add_action( 'wmfilter_before_sync_meli_to_woo', 'wmfilter_before_sync_meli_to_woo_ext_015', 10, 3 );
	function wmfilter_before_sync_meli_to_woo_ext_015( $status, $data_item, $wc_product_exist ) {
		$wmothers = false;
		$wmothers = wm_get_product_by_name_ext_015( $data_item->title );
		if ( $wmothers == true ) {
			if ( $data_item->listing_type_id == "gold_pro" ) {
				wp_trash_post( $wc_product_exist->get_id() );
			} else {
				wm_get_product_by_name_ext_015( $data_item->title, true, $wc_product_exist->get_id() );
			}
		}
		return true;
	}
}

if ( ! function_exists( 'wm_get_product_by_name_ext_015' ) ) {
	function wm_get_product_by_name_ext_015( $name, $trash = false, $product_id = 0 ) {
		global $wpdb;
		$same_product = false;
		$all_product_query = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='product' AND (post_status='publish' OR post_status='private' OR post_status='pending');", OBJECT );
		if ( !empty($all_product_query) ) {
			$b = sanitize_title($name);
			foreach ( $all_product_query as $value ) {
				$a = sanitize_title($value->post_title);
				if ( strcmp($a, $b) == 0 ) {
					$same_product = true;
					if ( $trash == true ) {
						if ( $product_id != $value->ID ) {
							wp_trash_post( $value->ID );
						}
					} else {
						break;
					}
				}
			}
		}
		return	$same_product;
	}
}

?>