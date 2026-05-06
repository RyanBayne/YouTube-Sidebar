<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class Plugin {
    const VERSION = '3.1.0';

    private $settings;
    private $assignments;
    private $renderer;
    private $api;

    public static function bootstrap() {
        $plugin = new self();
        $plugin->register();
        return $plugin;
    }

    public function __construct() {
        $this->settings = new Settings();
        $this->api = new YouTubeApi( $this->settings, new Cache() );
        $this->assignments = new Assignments();
        $this->renderer = new Renderer( $this->settings, $this->api );
    }

    public function register() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
        add_action( 'init', array( $this, 'register_shortcode' ) );
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'widgets_init', array( $this, 'register_widget' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_assignment_box' ) );
        add_action( 'save_post', array( $this, 'save_assignment' ), 10, 2 );
        add_action( 'admin_menu', array( $this, 'register_settings_page' ), 30 );
        add_action( 'admin_init', array( $this, 'save_settings' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
    }

    public function enqueue_public_assets() {
        wp_enqueue_style(
            'youtubesidebar-public',
            plugins_url( 'css/public.css', YOUTUBESIDEBAR__FILE__ ),
            array(),
            self::VERSION
        );
    }

    public function register_shortcode() {
        add_shortcode( 'youtube_sidebar', array( $this, 'render_shortcode' ) );
        add_shortcode( 'youtubesidebar', array( $this, 'render_shortcode' ) );
    }

    public function render_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'id'       => '',
                'url'      => '',
                'type'     => '',
                'layout'   => '',
                'post_id'  => 0,
                'privacy'  => '',
                'autoplay' => '',
                'mute'     => '',
                'captions' => '',
                'ratio'    => '',
                'title'    => '',
            ),
            $atts,
            'youtube_sidebar'
        );

        $post_id = absint( $atts['post_id'] );
        $source = $this->source_from_context( $atts, $post_id );
        return $this->renderer->render( $source );
    }

    public function register_block() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        wp_register_script(
            'youtubesidebar-block',
            plugins_url( 'js/block.js', YOUTUBESIDEBAR__FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor' ),
            self::VERSION,
            true
        );

        register_block_type(
            'youtubesidebar/display',
            array(
                'api_version'     => 2,
                'editor_script'   => 'youtubesidebar-block',
                'render_callback' => array( $this, 'render_block' ),
                'attributes'      => array(
                    'id'       => array( 'type' => 'string', 'default' => '' ),
                    'url'      => array( 'type' => 'string', 'default' => '' ),
                    'type'     => array( 'type' => 'string', 'default' => 'video' ),
                    'layout'   => array( 'type' => 'string', 'default' => 'featured' ),
                    'privacy'  => array( 'type' => 'boolean', 'default' => true ),
                    'autoplay' => array( 'type' => 'boolean', 'default' => false ),
                    'mute'     => array( 'type' => 'boolean', 'default' => false ),
                    'captions' => array( 'type' => 'boolean', 'default' => false ),
                    'ratio'    => array( 'type' => 'string', 'default' => '16:9' ),
                    'title'    => array( 'type' => 'string', 'default' => '' ),
                ),
            )
        );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'youtubesidebar-block',
            plugins_url( 'js/block.js', YOUTUBESIDEBAR__FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor' ),
            self::VERSION,
            true
        );
    }

    public function render_block( $attributes ) {
        $source = $this->source_from_context( $attributes, 0 );
        return $this->renderer->render( $source );
    }

    public function register_widget() {
        register_widget( __NAMESPACE__ . '\\LegacyWidget' );
    }

    public function register_assignment_box() {
        foreach ( array( 'post', 'page' ) as $screen ) {
            add_meta_box(
                'youtubesidebar-video-assignment',
                __( 'YouTube Display', 'youtubesidebar' ),
                array( $this, 'render_assignment_box' ),
                $screen,
                'side',
                'default'
            );
        }
    }

    public function render_assignment_box( $post ) {
        $source = $this->assignments->get_for_post( $post->ID );
        wp_nonce_field( 'youtubesidebar_save_assignment', 'youtubesidebar_assignment_nonce' );
        ?>
        <p>
            <label for="youtubesidebar_source_url"><?php esc_html_e( 'Video, playlist, channel, or live URL/ID', 'youtubesidebar' ); ?></label>
            <input class="widefat" type="text" id="youtubesidebar_source_url" name="youtubesidebar_source_url" value="<?php echo esc_attr( $source->get_input() ); ?>" />
        </p>
        <p>
            <label for="youtubesidebar_source_type"><?php esc_html_e( 'Source type', 'youtubesidebar' ); ?></label>
            <select class="widefat" id="youtubesidebar_source_type" name="youtubesidebar_source_type">
                <?php foreach ( VideoSource::types() as $type ) : ?>
                    <option value="<?php echo esc_attr( $type ); ?>" <?php selected( $source->type, $type ); ?>><?php echo esc_html( ucfirst( $type ) ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="youtubesidebar_source_layout"><?php esc_html_e( 'Layout', 'youtubesidebar' ); ?></label>
            <select class="widefat" id="youtubesidebar_source_layout" name="youtubesidebar_source_layout">
                <?php foreach ( Renderer::layouts() as $layout ) : ?>
                    <option value="<?php echo esc_attr( $layout ); ?>" <?php selected( $source->layout, $layout ); ?>><?php echo esc_html( ucwords( str_replace( '-', ' ', $layout ) ) ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function save_assignment( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['youtubesidebar_assignment_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['youtubesidebar_assignment_nonce'] ) ), 'youtubesidebar_save_assignment' ) ) {
            return;
        }

        if ( ! $post || ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $raw = isset( $_POST['youtubesidebar_source_url'] ) ? sanitize_text_field( wp_unslash( $_POST['youtubesidebar_source_url'] ) ) : '';
        $type = isset( $_POST['youtubesidebar_source_type'] ) ? sanitize_key( wp_unslash( $_POST['youtubesidebar_source_type'] ) ) : 'video';
        $layout = isset( $_POST['youtubesidebar_source_layout'] ) ? sanitize_key( wp_unslash( $_POST['youtubesidebar_source_layout'] ) ) : 'featured';

        $source = VideoSource::from_input( $raw, array( 'type' => $type, 'layout' => $layout ) );
        $this->assignments->save_for_post( $post_id, $source );
    }

    public function register_settings_page() {
        add_options_page(
            __( 'YouTube Display', 'youtubesidebar' ),
            __( 'YouTube Display', 'youtubesidebar' ),
            'manage_options',
            'youtubesidebar-display-settings',
            array( $this, 'render_settings_page' )
        );
    }

    public function render_settings_page() {
        $settings = $this->settings->all();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'YouTube Display Settings', 'youtubesidebar' ); ?></h1>
            <?php settings_errors( 'youtubesidebar' ); ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'youtubesidebar_save_settings', 'youtubesidebar_settings_nonce' ); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="youtubesidebar_api_key"><?php esc_html_e( 'YouTube API key', 'youtubesidebar' ); ?></label></th>
                        <td><input class="regular-text" type="password" id="youtubesidebar_api_key" name="youtubesidebar_api_key" value="<?php echo esc_attr( $settings['api_key'] ); ?>" autocomplete="off" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Privacy-enhanced embeds', 'youtubesidebar' ); ?></th>
                        <td><label><input type="checkbox" name="youtubesidebar_privacy" value="1" <?php checked( $settings['privacy'] ); ?> /> <?php esc_html_e( 'Use youtube-nocookie.com by default', 'youtubesidebar' ); ?></label></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="youtubesidebar_default_layout"><?php esc_html_e( 'Default layout', 'youtubesidebar' ); ?></label></th>
                        <td>
                            <select id="youtubesidebar_default_layout" name="youtubesidebar_default_layout">
                                <?php foreach ( Renderer::layouts() as $layout ) : ?>
                                    <option value="<?php echo esc_attr( $layout ); ?>" <?php selected( $settings['default_layout'], $layout ); ?>><?php echo esc_html( ucwords( str_replace( '-', ' ', $layout ) ) ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="youtubesidebar_cache_ttl"><?php esc_html_e( 'Metadata cache TTL', 'youtubesidebar' ); ?></label></th>
                        <td><input class="small-text" type="number" min="300" id="youtubesidebar_cache_ttl" name="youtubesidebar_cache_ttl" value="<?php echo esc_attr( $settings['cache_ttl'] ); ?>" /> <?php esc_html_e( 'seconds', 'youtubesidebar' ); ?></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function save_settings() {
        if ( ! isset( $_POST['youtubesidebar_settings_nonce'] ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        check_admin_referer( 'youtubesidebar_save_settings', 'youtubesidebar_settings_nonce' );

        $this->settings->save(
            array(
                'api_key'        => isset( $_POST['youtubesidebar_api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['youtubesidebar_api_key'] ) ) : '',
                'privacy'        => isset( $_POST['youtubesidebar_privacy'] ),
                'default_layout' => isset( $_POST['youtubesidebar_default_layout'] ) ? sanitize_key( wp_unslash( $_POST['youtubesidebar_default_layout'] ) ) : 'featured',
                'cache_ttl'      => isset( $_POST['youtubesidebar_cache_ttl'] ) ? absint( $_POST['youtubesidebar_cache_ttl'] ) : HOUR_IN_SECONDS,
            )
        );

        add_settings_error( 'youtubesidebar', 'settings_saved', __( 'Settings saved.', 'youtubesidebar' ), 'updated' );
    }

    public function source_from_context( $args, $post_id = 0 ) {
        if ( ! empty( $args['id'] ) || ! empty( $args['url'] ) ) {
            $input = ! empty( $args['url'] ) ? $args['url'] : $args['id'];
            return VideoSource::from_input( $input, $args );
        }

        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        if ( $post_id ) {
            return $this->assignments->get_for_post( $post_id, $args );
        }

        return new VideoSource( array() );
    }

    public function render( $args = array() ) {
        return $this->renderer->render( $this->source_from_context( $args, isset( $args['post_id'] ) ? absint( $args['post_id'] ) : 0 ) );
    }
}
