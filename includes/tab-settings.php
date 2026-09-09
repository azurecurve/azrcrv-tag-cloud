<?php
/*
	settings tab
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

$options = get_options();

global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- no dedicated API for "all post_tag terms with id+name"; low-frequency admin-only query.
$tags = $wpdb->get_results( "SELECT t.term_id AS term_id, t.name AS name FROM {$wpdb->term_taxonomy} tt INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'post_tag' ORDER BY t.name" );
?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

	<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_options" />
	<?php wp_nonce_field( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ); ?>

	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><label for="include_exclude"><?php esc_html_e( 'Include/Exclude Tags?', 'azrcrv-tc' ); ?></label></th>
				<td>
					<select name="include_exclude" id="include_exclude">
						<option value="include" <?php selected( $options['include_exclude'], 'include' ); ?>><?php esc_html_e( 'Include', 'azrcrv-tc' ); ?></option>
						<option value="exclude" <?php selected( $options['include_exclude'], 'exclude' ); ?>><?php esc_html_e( 'Exclude', 'azrcrv-tc' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Flag whether marked tags should be included or excluded from the tag cloud.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Tags to Include/Exclude', 'azrcrv-tc' ); ?></th>
				<td>
					<div class="azrcrv-tc-scrollbox">
						<?php foreach ( $tags as $tag ) : ?>
							<?php $checkbox_id = 'tag-' . absint( $tag->term_id ); ?>
							<label for="<?php echo esc_attr( $checkbox_id ); ?>">
								<input
									name="tag[<?php echo esc_attr( $tag->term_id ); ?>]"
									type="checkbox"
									id="<?php echo esc_attr( $checkbox_id ); ?>"
									value="1"
									<?php checked( isset( $options['tag'][ $tag->term_id ] ) ); ?>
								/>
								<?php echo esc_html( $tag->name ); ?>
							</label>
							<br />
						<?php endforeach; ?>
					</div>
					<p class="description"><?php esc_html_e( 'Mark the tags you want to include in, or exclude from, the tag cloud.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="smallest"><?php esc_html_e( 'Smallest Size', 'azrcrv-tc' ); ?></label></th>
				<td>
					<input type="number" id="smallest" name="smallest" value="<?php echo esc_attr( $options['smallest'] ); ?>" class="small-text" />
					<p class="description"><?php esc_html_e( 'The text size of the tag with the lowest count value.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="largest"><?php esc_html_e( 'Largest Size', 'azrcrv-tc' ); ?></label></th>
				<td>
					<input type="number" id="largest" name="largest" value="<?php echo esc_attr( $options['largest'] ); ?>" class="small-text" />
					<p class="description"><?php esc_html_e( 'The text size of the tag with the highest count value.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="unit"><?php esc_html_e( 'Unit', 'azrcrv-tc' ); ?></label></th>
				<td>
					<select name="unit" id="unit">
						<option value="pt" <?php selected( $options['unit'], 'pt' ); ?>>pt</option>
						<option value="px" <?php selected( $options['unit'], 'px' ); ?>>px</option>
						<option value="em" <?php selected( $options['unit'], 'em' ); ?>>em</option>
						<option value="pc" <?php selected( $options['unit'], 'pc' ); ?>>%</option>
					</select>
					<p class="description"><?php esc_html_e( 'Unit of measure for the smallest and largest sizes above.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="number"><?php esc_html_e( 'Number of Tags', 'azrcrv-tc' ); ?></label></th>
				<td>
					<input type="number" id="number" name="number" value="<?php echo esc_attr( $options['number'] ); ?>" class="small-text" />
					<p class="description"><?php esc_html_e( 'The number of tags to display in the cloud.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="format"><?php esc_html_e( 'Format', 'azrcrv-tc' ); ?></label></th>
				<td>
					<select name="format" id="format">
						<option value="flat" <?php selected( $options['format'], 'flat' ); ?>><?php esc_html_e( 'Flat', 'azrcrv-tc' ); ?></option>
						<option value="list" <?php selected( $options['format'], 'list' ); ?>><?php esc_html_e( 'List', 'azrcrv-tc' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Format of the cloud display.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="orderby"><?php esc_html_e( 'Order By', 'azrcrv-tc' ); ?></label></th>
				<td>
					<select name="orderby" id="orderby">
						<option value="name" <?php selected( $options['orderby'], 'name' ); ?>><?php esc_html_e( 'Name', 'azrcrv-tc' ); ?></option>
						<option value="count" <?php selected( $options['orderby'], 'count' ); ?>><?php esc_html_e( 'Count', 'azrcrv-tc' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Order of the tags.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="order"><?php esc_html_e( 'Order', 'azrcrv-tc' ); ?></label></th>
				<td>
					<select name="order" id="order">
						<option value="ASC" <?php selected( $options['order'], 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'azrcrv-tc' ); ?></option>
						<option value="DESC" <?php selected( $options['order'], 'DESC' ); ?>><?php esc_html_e( 'Descending', 'azrcrv-tc' ); ?></option>
						<option value="RAND" <?php selected( $options['order'], 'RAND' ); ?>><?php esc_html_e( 'Random', 'azrcrv-tc' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Sort order.', 'azrcrv-tc' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Plugin Widget', 'azrcrv-tc' ); ?></th>
				<td>
					<p class="description">
						<?php
						printf(
							/* translators: %s: widget name */
							esc_html__( "Once you've saved the settings, make sure you place the %s widget in a widget area.", 'azrcrv-tc' ),
							'<strong>' . esc_html__( 'Tag Cloud by azurecurve', 'azrcrv-tc' ) . '</strong>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, already-escaped inline markup.
						);
						?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Changes', 'azrcrv-tc' ) ); ?>
</form>
