<?php
/*
	instructions tab
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
?>

<h2><?php esc_html_e( 'Instructions', 'azrcrv-tc' ); ?></h2>

<ol>
	<li><?php esc_html_e( 'On the Settings tab, choose whether the tags you mark below should be included in the cloud (only they will show) or excluded from it (everything else will show).', 'azrcrv-tc' ); ?></li>
	<li><?php esc_html_e( 'Tick the tags you want to include or exclude in the scrollable list.', 'azrcrv-tc' ); ?></li>
	<li><?php esc_html_e( 'Set the smallest and largest text size, and the unit (points, pixels, ems, or percent) they are measured in.', 'azrcrv-tc' ); ?></li>
	<li><?php esc_html_e( 'Choose how many tags to display, how they should be formatted (a flat cloud or a list), and how they should be ordered.', 'azrcrv-tc' ); ?></li>
	<li><?php esc_html_e( 'Save Changes, then add the "Tag Cloud by azurecurve" widget to a widget area from Appearance > Widgets.', 'azrcrv-tc' ); ?></li>
</ol>
