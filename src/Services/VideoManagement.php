<?php
/**
 * Video Management Service
 * 
 * Handles video-related operations like adding, updating, and retrieving videos.
 *
 * @package YouTubeSidebar\Services
 */

namespace WPSeed\YouTubeSidebar\Services;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class VideoManagement {

    /**
     * @var VideoSourceRepository
     */
    private $repository;

    public function __construct( VideoSourceRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Add a video to a post.
     *
     * @param int $post_id
     * @param string $video_url_or_id
     * @param array $options Optional video options (layout, autoplay, etc)
     * @return bool
     */
    public function add_video( $post_id, $video_url_or_id, $options = array() ) {
        $post_id = absint( $post_id );
        
        $video_id = $this->repository->extract_video_id( $video_url_or_id );
        if ( ! $video_id ) {
            return false;
        }
        
        $videos = $this->repository->get_post_videos( $post_id );
        
        $video = array(
            'id' => $video_id,
            'url' => "https://youtube.com/watch?v={$video_id}",
            'added' => current_time( 'mysql' ),
        );
        
        if ( ! empty( $options ) ) {
            $video = array_merge( $video, $options );
        }
        
        $videos[] = $video;
        
        return update_post_meta( $post_id, '_youtubesidebar_videos', $videos );
    }

    /**
     * Remove a video from a post.
     *
     * @param int $post_id
     * @param int $video_index
     * @return bool
     */
    public function remove_video( $post_id, $video_index ) {
        $post_id = absint( $post_id );
        $video_index = absint( $video_index );
        
        $videos = $this->repository->get_post_videos( $post_id );
        
        if ( ! isset( $videos[ $video_index ] ) ) {
            return false;
        }
        
        unset( $videos[ $video_index ] );
        $videos = array_values( $videos ); // Re-index
        
        if ( empty( $videos ) ) {
            return delete_post_meta( $post_id, '_youtubesidebar_videos' );
        }
        
        return update_post_meta( $post_id, '_youtubesidebar_videos', $videos );
    }

    /**
     * Update video options.
     *
     * @param int $post_id
     * @param int $video_index
     * @param array $options
     * @return bool
     */
    public function update_video( $post_id, $video_index, $options ) {
        $post_id = absint( $post_id );
        $video_index = absint( $video_index );
        
        $videos = $this->repository->get_post_videos( $post_id );
        
        if ( ! isset( $videos[ $video_index ] ) ) {
            return false;
        }
        
        $videos[ $video_index ] = array_merge( $videos[ $video_index ], $options );
        
        return update_post_meta( $post_id, '_youtubesidebar_videos', $videos );
    }

    /**
     * Get all videos for multiple posts.
     *
     * @param array $post_ids
     * @return array Keyed by post_id
     */
    public function get_bulk_videos( $post_ids ) {
        $result = array();
        
        foreach ( $post_ids as $post_id ) {
            $result[ $post_id ] = $this->repository->get_post_videos( absint( $post_id ) );
        }
        
        return $result;
    }

    /**
     * Count total videos across all posts with videos.
     *
     * @return int
     */
    public function count_total_videos() {
        $posts = $this->repository->get_posts_with_videos( 'post', 9999 );
        
        $total = 0;
        foreach ( $posts as $post ) {
            $videos = $this->repository->get_post_videos( $post->ID );
            $total += count( $videos );
        }
        
        return $total;
    }
}
