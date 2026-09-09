<?php
/*
	options functions - get/save/sanitize the plugin's single settings array
	(smallest/largest size, unit, number of tags, format, order, and the
	per-tag include/exclude selection).
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\TagCloud;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * The plugin's built-in fallback values. Includes 'tag' => array() so a
 * fresh install (before Settings has ever been saved) never produces an
 * "undefined array key" notice when the widget checks $options['tag'].
 */
function get_builtin_option_defaults() {
	return array(
		'include_exclude' => 'include',
		'tag'              => array(),
		'smallest'         => 8,
		'largest'          => 25,
		'unit'             => 'pt',
		'number'           => 30,
		'format'           => 'flat',
		'orderby'          => 'name',
		'order'            => 'ASC',
	);
}

/**
 * Get the plugin's stored settings, merged over the built-in defaults.
 */
function get_options() {
	$stored = get_option( OPTIONS_OPTION_NAME, array() );

	if ( ! is_array( $stored ) ) {
		$stored = array();
	}

	return wp_parse_args( $stored, get_builtin_option_defaults() );
}

/**
 * Render the admin page (settings/instructions/other-plugins, in tabs).
 */
function display_admin_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-tc' ) );
	}

	echo '<div class="wrap ' . esc_attr( PLUGIN_HYPHEN ) . '-wrap">';
	echo '<h1>';
		echo '<a href="' . esc_url_raw( DEVELOPER_RAW_LINK ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="' . esc_attr( DEVELOPER_NAME ) . '" /></a>';
		echo esc_html( get_admin_page_title() );
	echo '</h1>';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only status flag, not a state-changing action.
	if ( isset( $_GET['azrcrv-tc-message'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$message_key = sanitize_key( wp_unslash( $_GET['azrcrv-tc-message'] ) );
		render_admin_notice( $message_key );
	}

	require_once __DIR__ . '/tabs-output.php';

	echo '</div>';
}

/**
 * Show a dismissible admin notice for a given message key, set via a
 * redirect query arg after the settings are saved.
 */
function render_admin_notice( $message_key ) {

	$messages = array(
		'settings-saved' => array( 'success', __( 'Settings saved.', 'azrcrv-tc' ) ),
		'invalid-nonce'  => array( 'error', __( 'Security check failed - please try again.', 'azrcrv-tc' ) ),
	);

	if ( ! isset( $messages[ $message_key ] ) ) {
		return;
	}

	list( $type, $text ) = $messages[ $message_key ];
	$css_class            = 'success' === $type ? 'notice-success' : 'notice-error';

	echo '<div class="notice ' . esc_attr( $css_class ) . ' is-dismissible"><p>' . esc_html( $text ) . '</p></div>';
}

/**
 * Build a redirect URL back to the admin page with a status message.
 */
function redirect_with_message( $message_key ) {
	$args = array(
		'page'              => PLUGIN_HYPHEN,
		'azrcrv-tc-message' => $message_key,
	);
	wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
	exit;
}

/**
 * Handle the "Save Changes" settings form.
 */
function handle_save_options() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action.', 'azrcrv-tc' ) );
	}

	if ( ! isset( $_POST[ PLUGIN_HYPHEN . '-nonce' ] ) || ! check_admin_referer( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ) ) {
		redirect_with_message( 'invalid-nonce' );
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce already verified above.
	$options = sanitize_options_from_post( wp_unslash( $_POST ) );

	save_options( $options );

	redirect_with_message( 'settings-saved' );
}
add_action( 'admin_post_' . PLUGIN_UNDERSCORE . '_save_options', __NAMESPACE__ . '\\handle_save_options' );


/**
 * Save the plugin's settings.
 */
function save_options( $options ) {
	update_option( OPTIONS_OPTION_NAME, $options );
}

/**
 * Sanitize a submitted settings form ($_POST, already wp_unslash()-ed by the
 * caller) into a settings array ready to store.
 */
function sanitize_options_from_post( $post ) {

	$options = get_options();

	if ( isset( $post['include_exclude'] ) && in_array( $post['include_exclude'], array( 'include', 'exclude' ), true ) ) {
		$options['include_exclude'] = $post['include_exclude'];
	}

	$tags = array();
	if ( isset( $post['tag'] ) && is_array( $post['tag'] ) ) {
		foreach ( $post['tag'] as $term_id => $value ) {
			$tags[ absint( $term_id ) ] = '1';
		}
	}
	$options['tag'] = $tags;

	if ( isset( $post['smallest'] ) && is_numeric( $post['smallest'] ) ) {
		$options['smallest'] = absint( $post['smallest'] );
	}

	if ( isset( $post['largest'] ) && is_numeric( $post['largest'] ) ) {
		$options['largest'] = absint( $post['largest'] );
	}

	if ( isset( $post['unit'] ) && in_array( $post['unit'], array( 'pt', 'px', 'em', 'pc' ), true ) ) {
		$options['unit'] = $post['unit'];
	}

	if ( isset( $post['number'] ) && is_numeric( $post['number'] ) ) {
		$options['number'] = absint( $post['number'] );
	}

	if ( isset( $post['format'] ) && in_array( $post['format'], array( 'flat', 'list' ), true ) ) {
		$options['format'] = $post['format'];
	}

	if ( isset( $post['orderby'] ) && in_array( $post['orderby'], array( 'name', 'count' ), true ) ) {
		$options['orderby'] = $post['orderby'];
	}

	if ( isset( $post['order'] ) && in_array( $post['order'], array( 'ASC', 'DESC', 'RAND' ), true ) ) {
		$options['order'] = $post['order'];
	}

	return $options;
}
