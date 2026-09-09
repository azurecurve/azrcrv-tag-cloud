<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name:		Tag Cloud
 * Description:		Displays a tag cloud with easy control of settings and exclusion of tags from the cloud.
 * Version:			2.0.0
 * Requires CP:		1.0
 * Requires PHP:	8.2
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		azrcrv-tc
 * Domain Path:		/assets/languages
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
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
 * Define constants.
 */
const DEVELOPER_SHORTNAME = 'azurecurve';
const DEVELOPER_NAME      = DEVELOPER_SHORTNAME . ' | Development';
const DEVELOPER_RAW_LINK  = 'https://development.azurecurve.co.uk/classicpress-plugins/';
const DEVELOPER_LINK      = '<a href="' . DEVELOPER_RAW_LINK . '">' . DEVELOPER_NAME . '</a>';

const PLUGIN_NAME       = 'Tag Cloud';
const PLUGIN_SHORT_SLUG = 'tag-cloud';
const PLUGIN_SLUG       = 'azrcrv-' . PLUGIN_SHORT_SLUG;
const PLUGIN_HYPHEN     = 'azrcrv-tc';
const PLUGIN_UNDERSCORE = 'azrcrv_tc';
const PLUGIN_FILE       = __FILE__;

// Option name under which the plugin's settings array is stored. Deliberately
// left as the plain 'azrcrv-tc' key used by earlier versions, rather than the
// PLUGIN_UNDERSCORE . '_options' pattern used elsewhere, so upgrading sites
// keep their existing settings without a data-migration step.
const OPTIONS_OPTION_NAME = 'azrcrv-tc';

/**
 * Load the remote update client, which integrates with azurecurve's own
 * Update Manager server so this plugin updates the same way as the rest of
 * the azurecurve plugin family.
 */
require_once dirname( PLUGIN_FILE ) . '/libraries/updateclient/UpdateClient.class.php';

/**
 * Load settings (options) functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-options.php';

/**
 * Load admin menu functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-menu.php';

/**
 * Load custom plugin icon/banner path functions, used by Update Manager.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-plugin-images.php';

/**
 * Load the shared azurecurve cross-plugin menu: populates this plugin's
 * entry into the shared directory of azurecurve plugins, and (if not
 * already added by another azurecurve plugin on this site) registers the
 * shared top-level "azurecurve" admin menu page that lists them all.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-populate.php';
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-display.php';

/**
 * Load admin script/style enqueue functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-scripts.php';

/**
 * Load language functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-language.php';

/**
 * Load the widget.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-widget.php';

/**
 * Load setup of hooks, actions and filters. This is required last, since it
 * references functions declared in the files above.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/setup.php';
