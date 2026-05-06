<?php
namespace WPSeed\YouTubeSidebar;

defined( 'ABSPATH' ) || exit;

final class Renderer {
    private $settings;
    private $api;

    public function __construct( Settings $settings, YouTubeApi $api ) {
        $this->settings = $settings;
        $this->api = $api;
    }

    public static function layouts() {
        return array( 'sidebar', 'featured', 'gallery', 'carousel', 'accordion', 'playlist-strip', 'live-now' );
    }

    public function render( VideoSource $source ) {
        if ( ! $source->has_source() ) {
            return '<div class="youtubesidebar youtubesidebar-empty"></div>';
        }

        $layout = $source->layout ? $source->layout : $this->settings->get( 'default_layout', 'featured' );

        if ( in_array( $layout, array( 'gallery', 'carousel', 'accordion', 'playlist-strip' ), true ) && count( $source->ids ) > 1 ) {
            return $this->render_collection( $source, $layout );
        }

        if ( 'live-now' === $layout || 'live' === $source->type ) {
            return $this->wrap( $this->embed( $source ), $source, 'live-now' );
        }

        if ( 'gallery' === $layout && 'playlist' === $source->type ) {
            $items = $this->api->metadata( $source );
            if ( ! empty( $items ) ) {
                return $this->render_metadata_grid( $items, $source, $layout );
            }
        }

        return $this->wrap( $this->embed( $source ), $source, $layout );
    }

    public function render_legacy_video( $video_id, array $args = array() ) {
        $source = VideoSource::from_input( $video_id, wp_parse_args( $args, array( 'type' => 'video', 'layout' => 'sidebar' ) ) );
        return $this->render( $source );
    }

    private function render_collection( VideoSource $source, $layout ) {
        $html = '';
        foreach ( $source->ids as $id ) {
            $item = VideoSource::from_input( $id, array( 'type' => 'video', 'layout' => 'sidebar' ) );
            $html .= '<div class="youtubesidebar-item">' . $this->embed( $item ) . '</div>';
        }

        return $this->wrap( $html, $source, $layout );
    }

    private function render_metadata_grid( array $items, VideoSource $source, $layout ) {
        $html = '';
        foreach ( $items as $item ) {
            if ( empty( $item['id'] ) ) {
                continue;
            }

            $item_source = VideoSource::from_input( $item['id'], array( 'type' => 'video', 'layout' => 'sidebar' ) );
            $thumb = $item['thumbnail'] ? '<img src="' . esc_url( $item['thumbnail'] ) . '" alt="" loading="lazy" />' : $this->embed( $item_source );
            $html .= '<a class="youtubesidebar-card" href="' . esc_url( 'https://www.youtube.com/watch?v=' . $item['id'] ) . '">';
            $html .= '<span class="youtubesidebar-thumb">' . $thumb . '</span>';
            $html .= '<span class="youtubesidebar-card-title">' . esc_html( $item['title'] ) . '</span>';
            $html .= '</a>';
        }

        if ( '' === $html ) {
            $html = $this->embed( $source );
        }

        return $this->wrap( $html, $source, $layout );
    }

    private function embed( VideoSource $source ) {
        $src = $this->embed_url( $source );
        if ( ! $src ) {
            return '';
        }

        $title = $source->title ? $source->title : __( 'YouTube video player', 'youtubesidebar' );
        $ratio = $this->option( $source, 'ratio', $this->settings->get( 'ratio', '16:9' ) );
        $padding = $this->ratio_padding( $ratio );

        return sprintf(
            '<div class="youtubesidebar-frame" style="--yts-ratio:%s"><iframe src="%s" title="%s" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>',
            esc_attr( $padding ),
            esc_url( $src ),
            esc_attr( $title )
        );
    }

    private function embed_url( VideoSource $source ) {
        $host = $this->option( $source, 'privacy', $this->settings->get( 'privacy', true ) ) ? 'https://www.youtube-nocookie.com' : 'https://www.youtube.com';
        $query = array(
            'rel' => 0,
        );

        if ( $this->option( $source, 'autoplay', false ) ) {
            $query['autoplay'] = 1;
        }

        if ( $this->option( $source, 'mute', false ) ) {
            $query['mute'] = 1;
        }

        if ( $this->option( $source, 'captions', false ) ) {
            $query['cc_load_policy'] = 1;
        }

        if ( 'playlist' === $source->type ) {
            $path = '/embed/videoseries';
            $query['list'] = $source->id;
        } elseif ( 'channel' === $source->type ) {
            $path = '/embed/videoseries';
            $query['list'] = 0 === strpos( $source->id, 'UU' ) ? $source->id : 'UU' . preg_replace( '/^UC/', '', $source->id );
        } elseif ( 'live' === $source->type && 0 === strpos( $source->id, 'UC' ) ) {
            $path = '/embed/live_stream';
            $query['channel'] = $source->id;
        } else {
            $path = '/embed/' . rawurlencode( $source->id );
        }

        return add_query_arg( $query, $host . $path );
    }

    private function wrap( $html, VideoSource $source, $layout ) {
        $classes = array(
            'youtubesidebar',
            'youtubesidebar-' . sanitize_html_class( $layout ),
            'youtubesidebar-source-' . sanitize_html_class( $source->type ),
        );

        if ( 'live' === $source->type || 'live-now' === $layout ) {
            $classes[] = 'is-live-capable';
        }

        return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $html . '</div>';
    }

    private function option( VideoSource $source, $key, $default ) {
        return array_key_exists( $key, $source->options ) ? $source->options[ $key ] : $default;
    }

    private function ratio_padding( $ratio ) {
        if ( preg_match( '/^([0-9]+):([0-9]+)$/', $ratio, $matches ) && (int) $matches[1] > 0 ) {
            return ( (int) $matches[2] / (int) $matches[1] * 100 ) . '%';
        }

        return '56.25%';
    }
}
