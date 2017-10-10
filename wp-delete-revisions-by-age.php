<?php
/*
Plugin Name: WP Delete Revisions By age
Plugin URI: http://608.software
Description: Deletes old revisions based on age/post date
Version: 1.0
Author: Jeff Hegeman

You can set the constant 'WP_POST_REVISIONS_MAX_AGE' in your config file to the value in months the max age for revisions, 24 by default.

*/

add_action( 'save_post', 'delete_old_revisions' );
function delete_old_revisions() {
    global $post;
    $max_age = defined('WP_POST_REVISIONS_MAX_AGE')? WP_POST_REVISIONS_MAX_AGE : 24;//in months
    $min_post_date = date("Y-m-d", strtotime("-$max_age months", time()));
    // if this post type supports revisions ..
    if ( post_type_supports( $post->post_type, 'revisions' )) {
        // get revisions for this post
        $revisions = wp_get_post_revisions($post->ID);
        foreach ($revisions as $revision) {
            // .. if it is older than the max age ..
            if($revision->post_date < $min_post_date){
                // delete the revision
                $delete = wp_delete_post_revision($revision->ID);
                // check for errors
                if ( is_wp_error($delete) ) {
                    // log it or something
                }
            }
        }
    }
}
