<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name:		Tag Cloud
 * Description:		Displays a tag cloud with easy control of settings and exclusion of tags from the cloud.
 * Version:			1.2.6
 * Requires CP:		1.0
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		tag-cloud
 * Domain Path:		/languages
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_tc');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_tc_create_admin_menu');
add_action('admin_post_azrcrv_tc_save_options', 'azrcrv_tc_save_options');
add_action('network_admin_menu', 'azrcrv_tc_create_network_admin_menu');
add_action('network_admin_edit_azrcrv_tc_save_network_options', 'azrcrv_tc_save_network_options');
add_action('widgets_init', 'azrcrv_tc_create_widget');
add_action('plugins_loaded', 'azrcrv_tc_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_tc_add_plugin_action_link', 10, 2);
add_filter('codepotent_update_manager_image_path', 'azrcrv_tc_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_tc_custom_image_url');

// add shortcodes
add_shortcode('shortcode', 'shortcode_function');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('tag-cloud', false, $plugin_rel_path);
}

/**
 * Custom plugin image path.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tc_custom_image_path($path){
    if (strpos($path, 'azrcrv-tag-cloud') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tc_custom_image_url($url){
    if (strpos($url, 'azrcrv-tag-cloud') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Get options including defaults.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tc_get_option($option_name){
 
	$defaults = array(
						'include_exclude' => 10,
						'smallest' => 8,
						'largest' => 25,
						'unit' => 'pt',
						'number' => 30,
						'use_network_settings' => 0,
						'format' => 'flat',
						'orderby' => 'name',
						'order' => 'ASC',
					);

	$options = get_option($option_name, $defaults);

	$options = wp_parse_args($options, $defaults);

	return $options;

}

/**
 * Add Tag Cloud action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-tc').'"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'tag-cloud').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Tag Cloud Settings", "tag-cloud")
						,esc_html__("Tag Cloud", "tag-cloud")
						,'manage_options'
						,'azrcrv-tc'
						,'azrcrv_tc_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'tag-cloud'));
    }
	
	// Retrieve plugin configuration options from database
	$options = azrcrv_tc_get_option('azrcrv-tc');
	?>
	<div id="azrcrv-tc-general" class="wrap">
		<fieldset>
			<h1>
				<?php
					echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
					esc_html_e(get_admin_page_title());
				?>
			</h1>
			<?php if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Settings have been saved.', 'tag-cloud'); ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_tc_save_options" />
				<input name="page_options" type="hidden" value="smallest, largest, number" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-tc', 'azrcrv-tc-nonce'); ?>
				
				<table class="form-table">
				
				<tr><th scope="row"><label for="include_exclude"><?php esc_html_e('Include/Exclude Tags?', 'tag-cloud'); ?></label></th><td>
					<select name="include_exclude">
						<option value="include" <?php if($options['include_exclude'] == 'include'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Include', 'tag-cloud'); ?></option>
						<option value="exclude" <?php if($options['include_exclude'] == 'exclude'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Exclude', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Flag whether marked tags should be included or excluded from the tag cloud', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Tags to include/exclude', 'tag-cloud'); ?></th><td>
					<div class='azrcrv-tc-scrollbox'>
						<?php
							global $wpdb;
							$query = "SELECT t.term_id AS `term_id`, t.name AS `name` FROM $wpdb->term_taxonomy tt INNER JOIN $wpdb->terms t On t.term_id = tt.term_id WHERE tt.taxonomy = 'post_tag' ORDER BY t.name";
							$_query_result = $wpdb->get_results($query);
							foreach($_query_result as $data){
								if (isset($options['tag'][$data->term_id])){
									$selected_tag = checked('1', $options['tag'][$data->term_id], false);
								}else{
									$selected_tag = '';
								}
								?>
								<label for="<?php echo $data->term_id; ?>"><input name="tag[<?php echo $data->term_id; ?>]" type="checkbox" id="tag" value="1" <?php echo $selected_tag; ?> /><?php echo esc_html($data->name); ?></label><br />
								<?php
							}
							unset($_query_result);
						?>
					</div>
					<p class="description"><?php esc_html_e('Mark the tags you want to include/exclude from the tag cloud', 'tag-cloud'); ?></p>
				</td></tr>
				
				<?php if (function_exists('is_multisite') && is_multisite()){ ?>
					<tr><th scope="row"><?php esc_html("Use Network Settings", "tag-cloud"); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Use Network Settings', 'tag-cloud'); ?></span></legend>
						<label for="use_network_settings"><input name="use_network_settings" type="checkbox" id="use_network_settings" value="1" <?php checked('1', $options['use_network_settings']); ?> /><?php esc_html_e('Use Network Settings? The settings below will be ignored', 'tag-cloud'); ?></label>
						</fieldset>
					</td></tr>
				<?php } ?>
				
				<tr><th scope="row"><label for="smallest"><?php esc_html_e('Smallest Size', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="smallest" value="<?php echo esc_html(stripslashes($options['smallest'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The text size of the tag with the lowest count value', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="largest"><?php esc_html_e('Largest Size', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="largest" value="<?php echo esc_html(stripslashes($options['largest'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The text size of the tag with the highest count value', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="unit"><?php esc_html_e('Unit', 'tag-cloud'); ?></label></th><td>
					<select name="unit">
						<option value="pt" <?php if($options['unit'] == 'pt'){ echo 'selected="selected"'; } ?>>pt</option>
						<option value="px" <?php if($options['unit'] == 'px'){ echo 'selected="selected"'; } ?>>px</option>
						<option value="em" <?php if($options['unit'] == 'em'){ echo 'selected="selected"'; } ?>>em</option>
						<option value="pc" <?php if($options['unit'] == 'pc'){ echo 'selected="selected"'; } ?>>%</option>
					</select>
					<p class="description"><?php esc_html_e('Unit of measure as pertains to the smallest and largest values', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="number"><?php esc_html_e('Number Of Tags', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="number" value="<?php echo esc_html(stripslashes($options['number'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The number of actual tags to display in the cloud', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="format"><?php esc_html_e('Format', 'tag-cloud'); ?></label></th><td>
					<select name="format">
						<option value="flat" <?php if($options['format'] == 'flat'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Flat', 'tag-cloud'); ?></option>
						<option value="list" <?php if($options['format'] == 'list'){ echo ' selected="selected"'; } ?>><?php esc_html_e('List', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Format of the cloud display', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="orderby"><?php esc_html_e('Order By', 'tag-cloud'); ?></label></th><td>
					<select name="orderby">
						<option value="name" <?php if($options['orderby'] == 'name'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Name', 'tag-cloud'); ?></option>
						<option value="count" <?php if($options['orderby'] == 'count'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Count', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Order of the tags', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="order"><?php esc_html_e('Order', 'tag-cloud'); ?></label></th><td>
					<select name="order">
						<option value="ASC" <?php if($options['order'] == 'ASC'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Ascending', 'tag-cloud'); ?></option>
						<option value="DESC" <?php if($options['order'] == 'DESC'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Descending', 'tag-cloud'); ?></option>
						<option value="RAND" <?php if($options['order'] == 'RAND'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Random', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Sort order', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="widget"><?php esc_html_e('Plugin Widget', 'tag-cloud'); ?></label></th><td>
					<p class="description"><?php sprintf(esc_html_e('Once you\'ve saved the settings, make sure you place the %s widget in a widget area', 'tag-cloud'), 'azurecurve Tag Cloud'); ?></p>
				</td></tr>
				
				</table>
				<input type="submit" value="Save Changes" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'tag-cloud'));
	}
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-tc', 'azrcrv-tc-nonce')){
		// Retrieve original plugin options array
		$options = get_option('azrcrv-tc');
		
		$option_name = 'include_exclude';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'tag';
		$newoptions = array();
		if (isset($_POST[$option_name])){
			//$options[$option_name] = sanitize_text_field($_POST[$option_name]);
			foreach ($_POST[$option_name] as $key => $val ) {
				$newoptions[$key] = sanitize_text_field($val);
			}
		}
		$options[$option_name] = $newoptions;
		
		$option_name = 'use_network_settings';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'smallest';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'largest';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'unit';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'number';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'format';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'orderby';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'order';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		// Store updated options array to database
		update_option('azrcrv-tc', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-tc&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Add to Network menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_create_network_admin_menu(){
	if (function_exists('is_multisite') && is_multisite()){
		add_submenu_page(
						'settings.php'
						,esc_html__("Tag Cloud Settings", "tag-cloud")
						,esc_html__("Tag Cloud", "tag-cloud")
						,'manage_network_options'
						,'azrcrv-tc'
						,'azrcrv_tc_network_settings'
						);
	}
}

/**
 * Display network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_network_settings(){
	if(!current_user_can('manage_network_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'tag-cloud'));
	}
	
	// Retrieve plugin configuration options from database
	$options = get_site_option('azrcrv-tc');

	?>
	<div id="azrcrv-tc-general" class="wrap">
		<fieldset>
			<h1>
				<?php
					echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
					esc_html_e(get_admin_page_title());
				?>
			</h1>
			<form method="post" action="edit.php?action=azrcrv_tc_save_network_options">
				<input type="hidden" name="action" value="azrcrv_tc_save_network_options" />
				<input name="page_options" type="hidden" value="smallest, largest, number" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-tc', 'azrcrv-tc-nonce'); ?>
				<table class="form-table">
				
				<tr><th scope="row"><label for="smallest"><?php esc_html_e('Smallest Size', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="smallest" value="<?php echo esc_html(stripslashes($options['smallest'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The text size of the tag with the lowest count value', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="largest"><?php esc_html_e('Largest Size', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="largest" value="<?php echo esc_html(stripslashes($options['largest'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The text size of the tag with the highest count value', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="unit"><?php esc_html_e('Unit', 'tag-cloud'); ?></label></th><td>
					<select name="unit">
						<option value="pt" <?php if($options['unit'] == 'pt'){ echo 'selected="selected"'; } ?>>pt</option>
						<option value="px" <?php if($options['unit'] == 'px'){ echo 'selected="selected"'; } ?>>px</option>
						<option value="em" <?php if($options['unit'] == 'em'){ echo 'selected="selected"'; } ?>>em</option>
						<option value="pc" <?php if($options['unit'] == 'pc'){ echo 'selected="selected"'; } ?>>%</option>
					</select>
					<p class="description"><?php esc_html_e('Unit of measure as pertains to the smallest and largest values', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="number"><?php esc_html_e('Number Of Tags', 'tag-cloud'); ?></label></th><td>
					<input type="text" name="number" value="<?php echo esc_html(stripslashes($options['number'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('The number of actual tags to display in the cloud', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="format"><?php esc_html_e('Format', 'tag-cloud'); ?></label></th><td>
					<select name="format">
						<option value="flat" <?php if($options['format'] == 'flat'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Flat', 'tag-cloud'); ?></option>
						<option value="list" <?php if($options['format'] == 'list'){ echo ' selected="selected"'; } ?>><?php esc_html_e('List', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Format of the cloud display', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="orderby"><?php esc_html_e('Order By', 'tag-cloud'); ?></label></th><td>
					<select name="orderby">
						<option value="name" <?php if($options['orderby'] == 'name'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Name', 'tag-cloud'); ?></option>
						<option value="count" <?php if($options['orderby'] == 'count'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Count', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Order of the tags', 'tag-cloud'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="order"><?php esc_html_e('Order', 'tag-cloud'); ?></label></th><td>
					<select name="order">
						<option value="ASC" <?php if($options['order'] == 'ASC'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Ascending', 'tag-cloud'); ?></option>
						<option value="DESC" <?php if($options['order'] == 'DESC'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Descending', 'tag-cloud'); ?></option>
						<option value="RAND" <?php if($options['order'] == 'RAND'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Random', 'tag-cloud'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Sort order', 'tag-cloud'); ?></p>
				</td></tr>
				
				</table>
				<input type="submit" value="Submit" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_save_network_options(){     
	if(!current_user_can('manage_network_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'tag-cloud'));
	}
	
	if (! empty($_POST) && check_admin_referer('azrcrv-tc', 'azrcrv-tc-nonce')){
		// Retrieve original plugin options array
		$options = get_site_option('azrcrv-tc');
		
		$option_name = 'smallest';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'largest';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'unit';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'number';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'format';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'orderby';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'order';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		update_site_option('azrcrv-tc', $options);

		wp_redirect(network_admin_url('settings.php?page=azurecurve-tag-cloud'));
		exit;
	}
}

/**
 * Register widget.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tc_create_widget(){
	register_widget('azurecurve_tag_cloud');
}


/**
 * Create widget class.
 *
 * @since 1.0.0
 *
 */
class azurecurve_tag_cloud extends WP_Widget {
	
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	*/
	function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		
		// Widget creation function
		parent::__construct('azurecurve_tag_cloud',
							 'Tag Cloud by azurecurve',
							 array('description' =>
									esc_html__('A customizable cloud of your most used tags.', 'azrcrv-tc')));
	}

	/**
	 * Enqueue styles.
	 *
	 * @since 1.0.0
	 * 
	 * @access public
	 * @return void
	 */
	public function enqueue(){
		// Enqueue Styles
		wp_enqueue_style('azrcrv-tc', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	}

	/**
	 * Display widget.
	 *
	 * @since 1.0.0
	 *
	*/
	function form($instance){
		// Retrieve previous values from instance
		// or set default values if not present
		$widget_title = (!empty($instance['azc_tc_title']) ? 
							esc_attr($instance['azc_tc_title']) :
							'Tag Cloud');
		?>

		<!-- Display field to specify title  -->
		<p>
			<label for="<?php echo 
						$this->get_field_id('azrcrv_tc_title'); ?>">
			<?php echo 'Widget Title:'; ?>			
			<input type="text" 
					id="<?php echo $this->get_field_id('azrcrv_tc_title'); ?>"
					name="<?php echo $this->get_field_name('azrcrv_tc_title'); ?>"
					value="<?php echo $widget_title; ?>" />			
			</label>
		</p> 

		<?php
	}

	/**
	 * Validate user input.
	 *
	 * @since 1.0.0
	 *
	*/
	function update($new_instance, $old_instance){
		$instance = $old_instance;

		$instance['azrcrv_tc_title'] = strip_tags($new_instance['azrcrv_tc_title']);

		return $instance;
	}

	/**
	 * Display widget on front end.
	 *
	 * @since 1.0.0
	 *
	*/
	function widget ($args, $instance){
		// Extract members of args array as individual variables
		extract($args);

		// Display widget title
		echo $before_widget;
		echo $before_title;
		$widget_title = (!empty($instance['azc_tc_title']) ? 
					esc_attr($instance['azc_tc_title']) :
					'Tag Cloud');
		echo apply_filters('widget_title', $widget_title);
		echo $after_title; 
		
		// Display title
		$options = azrcrv_tc_get_option('azrcrv-tc');
		$siteoptions = $options;
		if ($options['use_network_settings'] == 1){
			$options = get_site_option('azrcrv-tc');
		}
		// Define arguements
		$args = array(
					'smallest'                  => $options['smallest'],
					'largest'                   => $options['largest'],
					'unit'                      => $options['unit'],
					'number'                    => $options['number'],
					'format'                    => $options['format'],
					'orderby'                   => strtolower($options['orderby']),
					'order'                     => strtoupper($options['order'])
				//	'include'					=> ($options['include_exclude'] == 'include' ? $options['tag'] : null)
				//	'exclude'					=> ($options['include_exclude'] == 'exclude' ? $options['tag'] : null)
				);
				
		if ($options['unit'] == 'pc'){ $args['unit'] = '%'; }else{ $args['unit'] = $options['unit']; }
		$tags = '';
		if (is_array($siteoptions['tag'])){
			foreach ($siteoptions['tag'] as $key => $value){
				$tags .= $key.',';
			}
			
			if ($siteoptions['include_exclude'] == 'include'){
				$args['include'] = $tags;
			}else{
				$args['exclude'] = $tags;
			}
		}
		// outside if statement to display when set to exclude but nothing excluded
		wp_tag_cloud($args);
		
		echo $after_widget;
	}
}

?>