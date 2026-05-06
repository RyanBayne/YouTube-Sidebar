<?php
/**
 * Video Source Repository
 * 
 * Provides structured access to video sources and assignments.
 *
 * @package YouTubeSidebar\Services
 */

namespace WPSeed\YouTubeSidebar\Services;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class VideoSourceRepository {

    /**
     * Get video assignments for a post.
     *
     * @param int $post_id
     * @return array Video assignments
     */
    public function get_post_videos( $post_id ) {
        $post_id = absint( $post_id );
        
        $videos = get_post_meta( $post_id, '_youtubesidebar_videos', true );
        
        if ( ! is_array( $videos ) ) {
            return array();
        }
        
        return $videos;
    }

    /**
     * Get a single video assignment for a post.
     *
     * @param int $post_id
     * @param int $video_index
     * @return array|null
     */
    public function get_post_video( $post_id, $video_index = 0 ) {
        $videos = $this->get_post_videos( $post_id );
        
        return isset( $videos[ $video_index ] ) ? $videos[ $video_index ] : null;
    }

    /**
     * Get all posts with video assignments.
     *
     * @param string $post_type Default 'post'
     * @param int $limit
     * @return array
     */
    public function get_posts_with_videos( $post_type = 'post', $limit = 100 ) {
        global $wpdb;
        
        $results = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT post_id FROM {$wpdb->postmeta} 
             WHERE meta_key = %s AND meta_value IS NOT NULL",
            '_youtubesidebar_videos'
        ) );
        
        if ( empty( $results ) ) {
            return array();
        }
        
        $args = array(
            'post__in' => $results,
            'post_type' => $post_type,
            'posts_per_page' => $limit,
        );
        
        return get_posts( $args );
    }

    /**
     * Count posts with video assignments.
     *
     * @param string $post_type
     * @return int
     */
    public function count_posts_with_videos( $post_type = 'post' ) {
        $posts = $this->get_posts_with_videos( $post_type, 9999 );
        return count( $posts );
    }

    /**
     * Get video URL from assignment.
     *
     * @param array $video_assignment
     * @return string|null
     */
    public function get_video_url( $video_assignment ) {
        if ( ! is_array( $video_assignment ) ) {
            return null;
        }
        
        return $video_assignment['url'] ?? $video_assignment['id'] ?? null;
    }

    /**
     * Extract video ID from a YouTube URL or ID string.
     *
     * @param string $input YouTube URL or video ID
     * @return string|null Video ID
     */
    public function extract_video_id( $input ) {
        // Already just an ID
        if ( preg_match( '/^[a-zA-Z0-9_-]{11}$/', $input ) ) {
            return $input;
        }
        
        // YouTube URL variants
        $patterns = array(
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        );
        
        foreach ( $patterns as $pattern ) {
            if ( preg_match( $pattern, $input, $matches ) ) {
                return $matches[1];
            }
        }
        
        return null;
    }
}
