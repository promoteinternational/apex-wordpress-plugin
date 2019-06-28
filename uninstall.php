<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  AlecadddPlugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Clear Database stored data
$posts= get_posts( array( 'post_type' => ['courses'], 'numberposts' => -1 ) );

foreach( $posts as $post ) {
	wp_delete_post( $post->ID, true );
}

// Access the database via SQL
global $wpdb;
$wpdb->query( "DELETE FROM `wp_options` WHERE `option_name`  LIKE 'apex_update_frequency' OR `option_name` LIKE 'apex_currency' OR `option_name` LIKE 'apex_server_name' OR `option_name` LIKE 'apex_private_api_key' OR `option_name` LIKE 'apex_public_api_key' OR `option_name` LIKE 'apex_portal_id'" );
