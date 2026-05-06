<?php
/**
 * YouTube Sidebar Service Container
 * 
 * Central registry for modern YouTube Sidebar services.
 *
 * @package YouTubeSidebar
 */

namespace WPSeed\YouTubeSidebar;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class ServiceContainer {

    private static $instance = null;

    private $services = array();

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function plugin() {
        if ( ! isset( $this->services['plugin'] ) ) {
            $this->services['plugin'] = Plugin::bootstrap();
        }

        return $this->services['plugin'];
    }

    public function video_source_repository() {
        if ( ! isset( $this->services['video_source_repository'] ) ) {
            $this->services['video_source_repository'] = new Services\VideoSourceRepository();
        }

        return $this->services['video_source_repository'];
    }

    public function video_management() {
        if ( ! isset( $this->services['video_management'] ) ) {
            $this->services['video_management'] = new Services\VideoManagement(
                $this->video_source_repository()
            );
        }

        return $this->services['video_management'];
    }

    public function configuration_diagnostics() {
        if ( ! isset( $this->services['configuration_diagnostics'] ) ) {
            $this->services['configuration_diagnostics'] = new Services\ConfigurationDiagnostics(
                $this->video_source_repository()
            );
        }

        return $this->services['configuration_diagnostics'];
    }

    public function settings_page() {
        if ( ! isset( $this->services['settings_page'] ) ) {
            $this->services['settings_page'] = new Admin\SettingsPage(
                $this->configuration_diagnostics()
            );
        }

        return $this->services['settings_page'];
    }
}
