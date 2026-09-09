<?php
// Check that code was called from ClassicPress with uninstallation constant declared.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Options to remove.
$options = array(
	'azrcrv-tc',
);

if ( ! is_multisite() ) {

	foreach ( $options as $option ) {
		delete_option( $option );
	}
} else {

	global $wpdb;

	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );

		foreach ( $options as $option ) {
			delete_option( $option );
		}
	}

	switch_to_blog( $original_blog_id );
}
