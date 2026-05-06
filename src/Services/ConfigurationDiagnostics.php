<?php
/**
 * Configuration Diagnostics Service
 * 
 * Provides insight into the plugin's configuration, feature enablement, and health.
 *
 * @package YouTubeSidebar\Services
 */

namespace WPSeed\YouTubeSidebar\Services;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class ConfigurationDiagnostics {

    /**
     * @var VideoSourceRepository
     */
    private $repository;

    public function __construct( VideoSourceRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Get plugin health summary.
     *
     * @return array
     */
    public function get_health_report() {
        $issues = array();
        
        // Check if any posts have videos
        $posts_with_videos = $this->repository->count_posts_with_videos();
        
        if ( $posts_with_videos === 0 ) {
            $issues[] = 'No videos have been assigned to any posts yet';
        }
        
        // Check YouTube API configuration
        $api_key = get_option( 'youtubesidebar_api_key', '' );
        if ( empty( $api_key ) ) {
            $issues[] = 'YouTube API key not configured';
        }
        
        // Check for legacy widget references
        if ( is_active_widget( false, false, 'youtubesidebar_widget', true ) ) {
            // Widget is active, good
        }
        
        // Check if block is available
        $use_blocks = get_option( 'youtubesidebar_use_blocks', true );
        
        return array(
            'status' => empty( $issues ) ? 'healthy' : 'warning',
            'posts_with_videos' => $posts_with_videos,
            'api_configured' => ! empty( $api_key ),
            'issues' => $issues,
            'features' => array(
                'blocks_enabled' => $use_blocks,
                'widget_available' => true,
                'shortcodes_available' => true,
            ),
        );
    }

    /**
     * Get feature enablement status.
     *
     * @return array
     */
    public function get_feature_status() {
        return array(
            'blocks' => array(
                'enabled' => function_exists( 'register_block_type' ),
                'active' => get_option( 'youtubesidebar_use_blocks', true ),
            ),
            'widgets' => array(
                'enabled' => true,
                'active' => is_active_widget( false, false, 'youtubesidebar_widget', true ),
            ),
            'shortcodes' => array(
                'enabled' => true,
                'active' => true,
            ),
            'api' => array(
                'enabled' => ! empty( get_option( 'youtubesidebar_api_key', '' ) ),
                'configured' => true,
            ),
        );
    }

    /**
     * Get usage statistics.
     *
     * @return array
     */
    public function get_usage_stats() {
        $posts_with_videos = $this->repository->get_posts_with_videos( 'post', 9999 );
        
        $video_count = 0;
        $post_types_used = array();
        
        foreach ( $posts_with_videos as $post ) {
            $videos = $this->repository->get_post_videos( $post->ID );
            $video_count += count( $videos );
            $post_types_used[ $post->post_type ] = true;
        }
        
        return array(
            'posts_with_videos' => count( $posts_with_videos ),
            'total_videos' => $video_count,
            'post_types_used' => array_keys( $post_types_used ),
            'average_videos_per_post' => count( $posts_with_videos ) > 0 
                ? $video_count / count( $posts_with_videos ) 
                : 0,
        );
    }

    /**
     * Validate plugin configuration.
     *
     * @return array Validation report
     */
    public function validate_configuration() {
        $errors = array();
        $warnings = array();
        
        // Check API key
        $api_key = get_option( 'youtubesidebar_api_key', '' );
        if ( empty( $api_key ) ) {
            $warnings[] = 'YouTube API key is not configured. YouTube features will be limited.';
        }
        
        // Check database
        global $wpdb;
        $table_exists = $wpdb->get_var( 
            "SHOW TABLES LIKE '{$wpdb->postmeta}'" 
        );
        
        if ( ! $table_exists ) {
            $errors[] = 'WordPress postmeta table not found';
        }
        
        // Check file permissions
        if ( ! is_dir( YOUTUBESIDEBAR_ABSPATH . 'css' ) ) {
            $warnings[] = 'CSS directory missing';
        }
        
        if ( ! is_dir( YOUTUBESIDEBAR_ABSPATH . 'js' ) ) {
            $warnings[] = 'JS directory missing';
        }
        
        return array(
            'valid' => empty( $errors ),
            'errors' => $errors,
            'warnings' => $warnings,
        );
    }

    /**
     * Get a complete diagnostic report.
     *
     * @return array
     */
    public function get_full_diagnostic() {
        return array(
            'health' => $this->get_health_report(),
            'features' => $this->get_feature_status(),
            'usage' => $this->get_usage_stats(),
            'validation' => $this->validate_configuration(),
            'version' => defined( 'YOUTUBESIDEBAR_VERSION' ) ? YOUTUBESIDEBAR_VERSION : 'unknown',
        );
    }
}
