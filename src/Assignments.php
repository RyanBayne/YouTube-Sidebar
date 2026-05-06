<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class Assignments {
    const META = '_youtubesidebar_source';
    const LEGACY_META = 'youtubesidebar';

    public function get_for_post( $post_id, array $overrides = array() ) {
        $data = get_post_meta( $post_id, self::META, true );
        if ( is_array( $data ) ) {
            return VideoSource::from_array( wp_parse_args( $overrides, $data ) );
        }

        $legacy = get_post_meta( $post_id, self::LEGACY_META );
        $legacy = array_values( array_filter( array_map( 'trim', (array) $legacy ) ) );
        if ( ! empty( $legacy ) ) {
            $input = implode( ',', $legacy );
            return VideoSource::from_input( $input, wp_parse_args( $overrides, array( 'type' => 'video', 'layout' => 'sidebar' ) ) );
        }

        return VideoSource::from_input( '', $overrides );
    }

    public function save_for_post( $post_id, VideoSource $source ) {
        if ( ! $source->has_source() ) {
            delete_post_meta( $post_id, self::META );
            delete_post_meta( $post_id, self::LEGACY_META );
            return;
        }

        update_post_meta( $post_id, self::META, $source->to_array() );

        delete_post_meta( $post_id, self::LEGACY_META );
        foreach ( $source->ids as $id ) {
            add_post_meta( $post_id, self::LEGACY_META, $id );
        }
    }
}
