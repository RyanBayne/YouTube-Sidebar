<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class Cache {
    public function get( $key ) {
        return get_transient( $this->key( $key ) );
    }

    public function set( $key, $value, $ttl ) {
        set_transient( $this->key( $key ), $value, max( 300, absint( $ttl ) ) );
    }

    private function key( $key ) {
        return 'yts_' . md5( $key );
    }
}
