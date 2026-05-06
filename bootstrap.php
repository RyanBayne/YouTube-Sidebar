<?php
/**
 * YouTube Sidebar Compatibility Bootstrap
 * 
 * Provides a clear load order and modern entry point for the plugin's initialization.
 * Preserves both the legacy and modern runtime layers while establishing a stable
 * bootstrap sequence.
 *
 * @package YouTubeSidebar
 */

namespace YouTubeSidebar\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class PluginBootstrap {

    /**
     * Boot the YouTube Sidebar plugin.
     *
     * @param string $plugin_file The main plugin file path.
     */
    public static function boot( $plugin_file ) {
        self::define_constants( $plugin_file );
        self::load_modern_runtime();
        self::load_legacy_runtime();
        self::register_hooks();
    }

    /**
     * Define plugin constants.
     *
     * @param string $plugin_file
     */
    private static function define_constants( $plugin_file ) {
        if ( ! defined( 'YOUTUBESIDEBAR_VERSION' ) ) {
            define( 'YOUTUBESIDEBAR_VERSION', '3.1.0' );
        }
        if ( ! defined( 'YOUTUBESIDEBAR__FILE__' ) ) {
            define( 'YOUTUBESIDEBAR__FILE__', $plugin_file );
        }
        if ( ! defined( 'YOUTUBESIDEBAR_BASENAME' ) ) {
            define( 'YOUTUBESIDEBAR_BASENAME', plugin_basename( $plugin_file ) );
        }
        if ( ! defined( 'YOUTUBESIDEBAR_ABSPATH' ) ) {
            define( 'YOUTUBESIDEBAR_ABSPATH', plugin_dir_path( $plugin_file ) );
        }
        if ( ! defined( 'YOUTUBESIDEBAR_DIR_PATH' ) ) {
            define( 'YOUTUBESIDEBAR_DIR_PATH', YOUTUBESIDEBAR_ABSPATH );
        }
    }

    /**
     * Load the modern runtime layer.
     */
    private static function load_modern_runtime() {
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/ServiceContainer.php';
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/Plugin.php';
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/Services/VideoSourceRepository.php';
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/Services/VideoManagement.php';
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/Services/ConfigurationDiagnostics.php';
        require_once YOUTUBESIDEBAR_DIR_PATH . 'src/Admin/SettingsPage.php';
    }

    /**
     * Load the legacy runtime layer.
     */
    private static function load_legacy_runtime() {
        require_once YOUTUBESIDEBAR_DIR_PATH . 'classes/class-youtubesidebar.php';
    }

    /**
     * Register plugin hooks.
     */
    private static function register_hooks() {
        // Register textdomain
        add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
        
        // Initialize legacy plugin
        add_action( 'plugins_loaded', array( __CLASS__, 'init_legacy' ), 5 );
    }

    /**
     * Load plugin textdomain.
     */
    public static function load_textdomain() {
        load_plugin_textdomain(
            'youtubesidebar',
            false,
            dirname( YOUTUBESIDEBAR_BASENAME ) . '/languages/'
        );
    }

    /**
     * Initialize the legacy YouTube Sidebar class.
     */
    public static function init_legacy() {
        if ( ! class_exists( 'YOUTUBESIDEBAR' ) ) {
            return;
        }

        global $YOUTUBESIDEBAR;
        $YOUTUBESIDEBAR = new \YOUTUBESIDEBAR();
        $YOUTUBESIDEBAR->custom_post_types();
    }
}
