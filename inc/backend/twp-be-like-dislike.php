<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Like Dislike
 *
 * @package Plugin Simplon
**/

add_action('wp_ajax_pluginsimplon_like_dislike', 'pluginsimplon_like_dislike_callback');
add_action('wp_ajax_nopriv_pluginsimplon_like_dislike', 'pluginsimplon_like_dislike_callback');

if( ! function_exists( 'pluginsimplon_like_dislike_callback' ) ) :

    // Recommendec Post Ajax Call Function.
    function pluginsimplon_like_dislike_callback() {
        
        if ( isset($_POST[ '_wpnonce' ]) && wp_verify_nonce($_POST[ '_wpnonce' ], 'be_ajax_nonce') ) {
            $post_ID = $_POST['postID'];
            $action_type = $_POST['LikeDislike'];
            $likes = ( $like_count = get_post_meta( $post_ID, 'twp_be_like_count', true ) ) ? $like_count : 0;
            $like_ip_list = ( $like_ips = get_post_meta( $post_ID, 'twp_be_ip_address_lists_like', true ) ) ? $like_ips : array();
            
            $dislike_likes = ( $dislike_count = get_post_meta( $post_ID, 'twp_be_dislike_like_count', true ) ) ? $dislike_count : 0;
            $dis_like_ip_list = ( $dis_like_ips = get_post_meta( $post_ID, 'twp_be_ip_address_lists_dislike', true ) ) ? $dis_like_ips : array();
            
            if( $action_type == 'twp-post-dislike' ){
                if( pluginsimplon_can_act( $post_ID,$action_type ) ){
                    $dislike_likes  = $dislike_likes + 1;
                    $dis_like_ip_list[] = pluginsimplon_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_dislike_like_count', $dislike_likes );
                    update_post_meta( $post_ID, 'twp_be_ip_address_lists_dislike', $dis_like_ip_list );
                    if( !pluginsimplon_can_act( $post_ID,'twp-post-like' ) ){
                        if( $like_ips ){
                            $likes = $likes - 1;
                            $current_ip = pluginsimplon_get_ip_address();
                            $like_ip_list = booster_remove_element($like_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_like_count', $likes );
                            update_post_meta( $post_ID, 'twp_be_ip_address_lists_like', $like_ip_list );
                        }
                    }
                }else{
                    if( $dis_like_ips ){
                        $dislike_likes = $dislike_likes - 1;
                        $current_ip = pluginsimplon_get_ip_address();
                        $dis_like_ip_list = booster_remove_element( $dis_like_ips, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_dislike_like_count', $dislike_likes );
                        update_post_meta( $post_ID, 'twp_be_ip_address_lists_dislike', $dis_like_ip_list );
                    }
                }
            }else{
                if( pluginsimplon_can_act( $post_ID,$action_type ) ){
                    $likes = $likes + 1;
                    $like_ip_list[] = pluginsimplon_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_like_count', $likes );
                    update_post_meta( $post_ID, 'twp_be_ip_address_lists_like', $like_ip_list );
                    if( !pluginsimplon_can_act( $post_ID,'twp-post-dislike' ) ){
                        
                        if( $dis_like_ips ){ 
                            $dislike_likes = $dislike_likes - 1;
                            $current_ip = pluginsimplon_get_ip_address();
                            $dis_like_ip_list = booster_remove_element( $dis_like_ips, $current_ip );
                            update_post_meta( $post_ID, 'twp_be_dislike_like_count', $dislike_likes );
                            update_post_meta( $post_ID, 'twp_be_ip_address_lists_dislike', $dis_like_ip_list );
                        }
                    }
                }else{
                    if( $like_ips ){ 
                        $likes     = $likes - 1;
                        $current_ip = pluginsimplon_get_ip_address();
                        $like_ip_list = booster_remove_element($like_ips, $current_ip);
                        update_post_meta( $post_ID, 'twp_be_like_count', $likes );
                        update_post_meta( $post_ID, 'twp_be_ip_address_lists_like', $like_ip_list );
                    }
                }
            }
        }
    }
endif;