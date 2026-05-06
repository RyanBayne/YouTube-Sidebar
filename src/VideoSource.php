<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class VideoSource {
    public $type = 'video';
    public $id = '';
    public $ids = array();
    public $input = '';
    public $layout = 'featured';
    public $title = '';
    public $options = array();

    public static function types() {
        return array( 'video', 'playlist', 'channel', 'live' );
    }

    public function __construct( array $data ) {
        foreach ( array( 'type', 'id', 'ids', 'input', 'layout', 'title', 'options' ) as $key ) {
            if ( array_key_exists( $key, $data ) ) {
                $this->$key = $data[ $key ];
            }
        }

        $this->type = in_array( $this->type, self::types(), true ) ? $this->type : 'video';
        $this->layout = in_array( $this->layout, Renderer::layouts(), true ) ? $this->layout : 'featured';
        $this->ids = array_values( array_filter( array_map( 'sanitize_text_field', (array) $this->ids ) ) );
        $this->id = sanitize_text_field( $this->id );
    }

    public static function from_input( $input, array $args = array() ) {
        $input = trim( (string) $input );
        $type = ! empty( $args['type'] ) ? sanitize_key( $args['type'] ) : '';
        $detected = self::detect( $input, $type );

        $options = array(
            'privacy'  => isset( $args['privacy'] ) ? self::to_bool( $args['privacy'] ) : null,
            'autoplay' => isset( $args['autoplay'] ) ? self::to_bool( $args['autoplay'] ) : null,
            'mute'     => isset( $args['mute'] ) ? self::to_bool( $args['mute'] ) : null,
            'captions' => isset( $args['captions'] ) ? self::to_bool( $args['captions'] ) : null,
            'ratio'    => ! empty( $args['ratio'] ) ? sanitize_text_field( $args['ratio'] ) : '',
        );

        return new self(
            array(
                'type'    => $detected['type'],
                'id'      => $detected['id'],
                'ids'     => $detected['ids'],
                'input'   => $input,
                'layout'  => ! empty( $args['layout'] ) ? sanitize_key( $args['layout'] ) : 'featured',
                'title'   => ! empty( $args['title'] ) ? sanitize_text_field( $args['title'] ) : '',
                'options' => array_filter( $options, array( __CLASS__, 'not_null' ) ),
            )
        );
    }

    public static function from_array( array $data ) {
        return new self( $data );
    }

    public function to_array() {
        return array(
            'type'    => $this->type,
            'id'      => $this->id,
            'ids'     => $this->ids,
            'input'   => $this->input,
            'layout'  => $this->layout,
            'title'   => $this->title,
            'options' => $this->options,
        );
    }

    public function get_input() {
        return $this->input ? $this->input : $this->id;
    }

    public function has_source() {
        return '' !== $this->id || ! empty( $this->ids );
    }

    private static function detect( $input, $forced_type = '' ) {
        $type = in_array( $forced_type, self::types(), true ) ? $forced_type : 'video';
        $id = '';
        $ids = array();

        if ( '' === $input ) {
            return array( 'type' => $type, 'id' => '', 'ids' => array() );
        }

        if ( false !== strpos( $input, ',' ) ) {
            $ids = array_filter( array_map( 'trim', explode( ',', $input ) ) );
            return array( 'type' => $type, 'id' => reset( $ids ), 'ids' => $ids );
        }

        $parts = wp_parse_url( $input );
        if ( ! empty( $parts['host'] ) ) {
            $host = strtolower( $parts['host'] );
            $path = isset( $parts['path'] ) ? trim( $parts['path'], '/' ) : '';
            $query = array();
            if ( ! empty( $parts['query'] ) ) {
                parse_str( $parts['query'], $query );
            }

            if ( false !== strpos( $host, 'youtu.be' ) && $path ) {
                $id = strtok( $path, '/' );
                $type = 'video';
            } elseif ( ! empty( $query['v'] ) ) {
                $id = $query['v'];
                $type = 'video';
            } elseif ( ! empty( $query['list'] ) ) {
                $id = $query['list'];
                $type = 'playlist';
            } elseif ( preg_match( '#(?:embed|shorts|live)/([^/?]+)#', $path, $matches ) ) {
                $id = $matches[1];
                $type = 'live' === strtok( $path, '/' ) ? 'live' : 'video';
            } elseif ( preg_match( '#(?:channel|c|user|@)(?:/)?([^/?]+)#', $path, $matches ) ) {
                $id = $matches[1];
                $type = 'channel';
            }
        }

        if ( '' === $id ) {
            $id = preg_replace( '/[^A-Za-z0-9_\-@]/', '', $input );
        }

        return array( 'type' => $type, 'id' => $id, 'ids' => $id ? array( $id ) : array() );
    }

    private static function to_bool( $value ) {
        if ( is_bool( $value ) ) {
            return $value;
        }

        return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on', 'enable', 'enabled' ), true );
    }

    public static function not_null( $value ) {
        return null !== $value && '' !== $value;
    }
}
