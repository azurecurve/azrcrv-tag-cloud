<?php
/*
	widget - registers and renders the "Tag Cloud by azurecurve" widget.
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
 * Register widget.
 */
function create_widget() {
	register_widget( __NAMESPACE__ . '\\Tag_Cloud_Widget' );
}

/**
 * Widget class.
 */
class Tag_Cloud_Widget extends \WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		parent::__construct(
			'azurecurve_tag_cloud',
			'Tag Cloud by azurecurve',
			array( 'description' => esc_html__( 'A customizable cloud of your most used tags.', 'azrcrv-tc' ) )
		);
	}

	/**
	 * Enqueue front-end widget styles.
	 */
	public function enqueue() {
		wp_enqueue_style( PLUGIN_HYPHEN . '-widget', plugins_url( 'assets/css/widget.css', PLUGIN_FILE ), array(), '2.0.0' );
	}

	/**
	 * Widget settings form (Appearance > Widgets).
	 */
	public function form( $instance ) {
		$widget_title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Tag Cloud', 'azrcrv-tc' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Widget Title:', 'azrcrv-tc' ); ?>
				<input
					type="text"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					value="<?php echo esc_attr( $widget_title ); ?>"
					class="widefat"
				/>
			</label>
		</p>
		<?php
	}

	/**
	 * Validate/sanitize submitted widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( wp_strip_all_tags( $new_instance['title'] ) );

		return $instance;
	}

	/**
	 * Render the widget on the front end.
	 */
	public function widget( $args, $instance ) {

		$widget_title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Tag Cloud', 'azrcrv-tc' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-provided wrapper markup.
		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $widget_title ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-provided wrapper markup.

		$options = get_options();

		$cloud_args = array(
			'smallest' => $options['smallest'],
			'largest'  => $options['largest'],
			'unit'     => 'pc' === $options['unit'] ? '%' : $options['unit'],
			'number'   => $options['number'],
			'format'   => $options['format'],
			'orderby'  => strtolower( $options['orderby'] ),
			'order'    => strtoupper( $options['order'] ),
		);

		if ( is_array( $options['tag'] ) && ! empty( $options['tag'] ) ) {
			$term_ids = implode( ',', array_map( 'absint', array_keys( $options['tag'] ) ) );

			if ( 'exclude' === $options['include_exclude'] ) {
				$cloud_args['exclude'] = $term_ids;
			} else {
				$cloud_args['include'] = $term_ids;
			}
		}

		wp_tag_cloud( $cloud_args );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-provided wrapper markup.
	}
}
