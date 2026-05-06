<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class YouTubeApi {
    private $settings;
    private $cache;

    public function __construct( Settings $settings, Cache $cache ) {
        $this->settings = $settings;
        $this->cache = $cache;
    }

    public function metadata( VideoSource $source ) {
        if ( ! $source->has_source() ) {
            return array();
        }

        $api_key = $this->settings->get( 'api_key', '' );
        if ( '' === $api_key ) {
            return array();
        }

        $cache_key = 'metadata:' . $source->type . ':' . implode( ',', $source->ids );
        $cached = $this->cache->get( $cache_key );
        if ( false !== $cached ) {
            return is_array( $cached ) ? $cached : array();
        }

        $endpoint = $this->endpoint_for( $source, $api_key );
        if ( ! $endpoint ) {
            return array();
        }

        $response = wp_remote_get( $endpoint, array( 'timeout' => 8 ) );
        if ( is_wp_error( $response ) ) {
            return array();
        }

        $code = wp_remote_retrieve_response_code( $response );
        if ( 200 !== (int) $code ) {
            return array();
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        $items = is_array( $body ) && isset( $body['items'] ) && is_array( $body['items'] ) ? $body['items'] : array();
        $metadata = $this->normalize_items( $items );
        $this->cache->set( $cache_key, $metadata, $this->settings->get( 'cache_ttl', HOUR_IN_SECONDS ) );

        return $metadata;
    }

    private function endpoint_for( VideoSource $source, $api_key ) {
        $base = 'https://www.googleapis.com/youtube/v3/';

        if ( 'playlist' === $source->type ) {
            return add_query_arg(
                array(
                    'part'       => 'snippet,contentDetails',
                    'playlistId' => $source->id,
                    'maxResults' => 12,
                    'key'        => $api_key,
                ),
                $base . 'playlistItems'
            );
        }

        if ( 'channel' === $source->type ) {
            $arg = 0 === strpos( $source->id, 'UC' ) ? 'id' : 'forUsername';
            return add_query_arg(
                array(
                    'part' => 'snippet,contentDetails',
                    $arg   => ltrim( $source->id, '@' ),
                    'key'  => $api_key,
                ),
                $base . 'channels'
            );
        }

        return add_query_arg(
            array(
                'part' => 'snippet,contentDetails,liveStreamingDetails',
                'id'   => implode( ',', $source->ids ),
                'key'  => $api_key,
            ),
            $base . 'videos'
        );
    }

    private function normalize_items( array $items ) {
        $normalized = array();

        foreach ( $items as $item ) {
            $snippet = isset( $item['snippet'] ) && is_array( $item['snippet'] ) ? $item['snippet'] : array();
            $video_id = '';

            if ( isset( $item['id'] ) && is_string( $item['id'] ) ) {
                $video_id = $item['id'];
            }

            if ( isset( $snippet['resourceId']['videoId'] ) ) {
                $video_id = $snippet['resourceId']['videoId'];
            }

            $normalized[] = array(
                'id'          => $video_id,
                'title'       => isset( $snippet['title'] ) ? $snippet['title'] : '',
                'description' => isset( $snippet['description'] ) ? $snippet['description'] : '',
                'thumbnail'   => $this->thumbnail_from_snippet( $snippet ),
                'live'        => isset( $item['liveStreamingDetails'] ),
            );
        }

        return $normalized;
    }

    private function thumbnail_from_snippet( array $snippet ) {
        if ( empty( $snippet['thumbnails'] ) || ! is_array( $snippet['thumbnails'] ) ) {
            return '';
        }

        foreach ( array( 'maxres', 'standard', 'high', 'medium', 'default' ) as $size ) {
            if ( ! empty( $snippet['thumbnails'][ $size ]['url'] ) ) {
                return $snippet['thumbnails'][ $size ]['url'];
            }
        }

        return '';
    }
}
