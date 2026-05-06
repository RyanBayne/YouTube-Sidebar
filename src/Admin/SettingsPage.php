<?php
/**
 * Settings Page
 * 
 * Provides a modern admin settings page for YouTube Sidebar configuration.
 *
 * @package YouTubeSidebar\Admin
 */

namespace WPSeed\YouTubeSidebar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class SettingsPage {

    /**
     * @var \WPSeed\YouTubeSidebar\Services\ConfigurationDiagnostics
     */
    private $diagnostics;

    public function __construct( \WPSeed\YouTubeSidebar\Services\ConfigurationDiagnostics $diagnostics ) {
        $this->diagnostics = $diagnostics;
    }

    /**
     * Register the admin page and hooks.
     */
    public function register() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Add the admin menu.
     */
    public function add_admin_menu() {
        add_options_page(
            __( 'YouTube Sidebar Settings', 'youtubesidebar' ),
            __( 'YouTube Sidebar', 'youtubesidebar' ),
            'manage_options',
            'youtubesidebar-settings',
            array( $this, 'render_page' )
        );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {
        register_setting( 'youtubesidebar-settings', 'youtubesidebar_api_key' );
        register_setting( 'youtubesidebar-settings', 'youtubesidebar_use_blocks' );
        register_setting( 'youtubesidebar-settings', 'youtubesidebar_layout' );
    }

    /**
     * Render the settings page.
     */
    public function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'youtubesidebar' ) );
        }

        $diagnostic = $this->diagnostics->get_full_diagnostic();
        $api_key = get_option( 'youtubesidebar_api_key', '' );
        $use_blocks = get_option( 'youtubesidebar_use_blocks', true );
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'YouTube Sidebar Settings', 'youtubesidebar' ); ?></h1>
            
            <?php if ( ! empty( $diagnostic['health']['issues'] ) ) : ?>
                <div class="notice notice-warning"><p>
                    <?php esc_html_e( 'There are some configuration issues:', 'youtubesidebar' ); ?>
                    <ul>
                        <?php foreach ( $diagnostic['health']['issues'] as $issue ) : ?>
                            <li><?php echo esc_html( $issue ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </p></div>
            <?php endif; ?>
            
            <h2><?php esc_html_e( 'Configuration', 'youtubesidebar' ); ?></h2>
            
            <form method="post" action="options.php">
                <?php settings_fields( 'youtubesidebar-settings' ); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="youtubesidebar_api_key">
                                <?php esc_html_e( 'YouTube API Key', 'youtubesidebar' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" id="youtubesidebar_api_key" name="youtubesidebar_api_key" 
                                   value="<?php echo esc_attr( substr( $api_key, 0, 10 ) ) . '...' ?>" 
                                   class="regular-text" />
                            <p class="description">
                                <?php esc_html_e( 'Configure your YouTube API key for enhanced video features.', 'youtubesidebar' ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="youtubesidebar_use_blocks">
                                <?php esc_html_e( 'Enable Block Support', 'youtubesidebar' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="checkbox" id="youtubesidebar_use_blocks" name="youtubesidebar_use_blocks" 
                                   value="1" <?php checked( $use_blocks, 1 ); ?> />
                            <label for="youtubesidebar_use_blocks">
                                <?php esc_html_e( 'Allow YouTube Sidebar block in the block editor', 'youtubesidebar' ); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <h2><?php esc_html_e( 'Diagnostics', 'youtubesidebar' ); ?></h2>
            
            <table class="wp-list-table widefat fixed">
                <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Posts with Videos:', 'youtubesidebar' ); ?></td>
                        <td><strong><?php echo absint( $diagnostic['usage']['posts_with_videos'] ); ?></strong></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Total Videos:', 'youtubesidebar' ); ?></td>
                        <td><strong><?php echo absint( $diagnostic['usage']['total_videos'] ); ?></strong></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Plugin Version:', 'youtubesidebar' ); ?></td>
                        <td><code><?php echo esc_html( $diagnostic['version'] ); ?></code></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}
