<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class Settings {
    const OPTION = 'youtubesidebar_display_settings';

    public function all() {
        $settings = get_option( self::OPTION, array() );
        $settings = is_array( $settings ) ? $settings : array();

        return wp_parse_args(
            $settings,
            array(
                'api_key'        => '',
                'privacy'        => true,
                'default_layout' => 'featured',
                'cache_ttl'      => HOUR_IN_SECONDS,
                'autoplay'       => false,
                'mute'           => false,
                'captions'       => false,
                'ratio'          => '16:9',
            )
        );
    }

    public function get( $key, $default = null ) {
        $settings = $this->all();
        return array_key_exists( $key, $settings ) ? $settings[ $key ] : $default;
    }

    public function save( array $settings ) {
        $current = $this->all();
        $settings = wp_parse_args( $settings, $current );

        $settings['privacy'] = ! empty( $settings['privacy'] );
        $settings['default_layout'] = in_array( $settings['default_layout'], Renderer::layouts(), true ) ? $settings['default_layout'] : 'featured';
        $settings['cache_ttl'] = max( 300, absint( $settings['cache_ttl'] ) );

        update_option( self::OPTION, $settings );
    }
}
