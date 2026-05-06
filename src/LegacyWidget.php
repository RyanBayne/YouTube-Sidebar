<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class LegacyWidget extends \WP_Widget {
    public function __construct() {
        parent::__construct(
            'youtubesidebar_display',
            __( 'YouTube Display', 'youtubesidebar' ),
            array( 'description' => __( 'Displays assigned YouTube videos with modern responsive embeds.', 'youtubesidebar' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( ! is_singular() ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Videos', 'youtubesidebar' );
        $plugin = youtubesidebar_modern_plugin();

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }
        echo $plugin->render( array( 'post_id' => get_the_ID(), 'layout' => 'sidebar' ) );
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Videos', 'youtubesidebar' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'youtubesidebar' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return array(
            'title' => isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '',
        );
    }
}
