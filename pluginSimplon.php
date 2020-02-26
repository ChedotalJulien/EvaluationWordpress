<?php
/*
* Plugin Name: Plugin Simplon
* Version: 1.0
* Plugin URI: 
* Description: plug-in with Form and Social button
* Author: Chedotal Julien
* Author URI: 
* @package Plugin Simplon
*/
// Exit if accessed directly.

if (!defined('ABSPATH')) {
    exit;
}

define('pluginsimplon_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
define( 'pluginsimplon_URL', plugin_dir_url( __FILE__ ) );

if (!class_exists('pluginsimplon_Class')) {

    class pluginsimplon_Class{

        function __construct(){

            if( isset( $_GET['page'] ) ){

                $current_page = $_GET['page'];
                add_action('in_admin_header', function () {

                  if ( !$current_page = 'pluginsimplon' ) return;

                  remove_all_actions('admin_notices');
                  remove_all_actions('all_admin_notices');

                }, 1000);
            }

            add_action('init', array($this, 'pluginsimplon_plugin_text_domain'));
            register_activation_hook(__FILE__, array($this, 'twp_activation_default_value'));
            add_action('admin_enqueue_scripts', array($this, 'pluginsimplon_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'pluginsimplon_frontend_scripts'));
            add_action('admin_menu', array($this, 'pluginsimplon_backend_menu'));
            add_action('admin_post_pluginsimplon_settings_options', array($this, 'pluginsimplon_settings_options'));
            add_filter('user_contactmethods', array($this, 'pluginsimplon_user_fields'));
            add_filter('body_class', array($this, 'pluginsimplon_body_class'));
            add_filter('the_content', array($this, 'pluginsimplon_frontend_the_content'));
            add_action('pluginsimplon_like_dislike', array($this, 'pluginsimplon_frontend_post_like_dislike'));
            add_shortcode('pluginsimplon-like-dislike', array($this, 'pluginsimplon_frontend_post_like_dislike_shortcode'));
            add_action('pluginsimplon_social_icons', array($this, 'pluginsimplon_frontend_social_share_action'));
            add_shortcode('pluginsimplon-ss', array($this, 'pluginsimplon_be_social_share_shortcode'));
            add_action('pluginsimplon_author_box', array($this, 'pluginsimplon_frontend_author_box'));
            add_shortcode('pluginsimplon-ab', array($this, 'pluginsimplon_frontend_author_box_shortcode'));
            add_action('pluginsimplon_read_time', array($this, 'pluginsimplon_frontend_read_time'));
            add_shortcode('pluginsimplon-read-time', array($this, 'pluginsimplon_frontend_read_time_shortcode'));
            add_action('pluginsimplon_reaction', array($this, 'pluginsimplon_frontend_reaction'));
            add_shortcode('pluginsimplon-reaction', array($this, 'pluginsimplon_frontend_reaction_shortcode'));
            include_once 'inc/backend/widget-base-class.php';
            include_once 'inc/backend/twp-be-author-widget.php';
            include_once 'inc/backend/twp-be-functions.php';
            include_once 'inc/backend/twp-be-like-dislike.php';
            include_once 'inc/backend/twp-be-post-reactions.php';
            include_once 'inc/backend/like-count-metabox.php';

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_visit_tracking = isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) : '';
                
            if( $twp_be_enable_post_visit_tracking ){
                include_once 'inc/backend/twp-be-views-count.php';
                add_shortcode( 'pluginsimplon-visit-count', 'pluginsimplon_get_post_view' );
                add_action('pluginsimplon_post_view_action', array($this, 'pluginsimplon_post_view'));
            }
        }

        function pluginsimplon_post_view(){

            echo do_shortcode('[pluginsimplon-visit-count]');

        }
        // Body Class
        function pluginsimplon_body_class($classes){

            $classes[] = 'pluginsimplon';
            return $classes;

        }

        // loads plugin text domain.
        function pluginsimplon_plugin_text_domain(){

            load_plugin_textdomain('pluginsimplon', false, pluginsimplon_LANG_DIR);

        }

        // Admin Script
        function pluginsimplon_admin_scripts(){

            wp_enqueue_script('pluginsimplon-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), '', true);
            wp_enqueue_style('pluginsimplon-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css');

        }

        // Frontend Script
        function pluginsimplon_frontend_scripts(){

            wp_enqueue_script('pluginsimplon-frontend-script', plugin_dir_url(__FILE__) . 'assets/js/frontend.js', array('jquery'), '', true);
            wp_enqueue_style('pluginsimplon-admin-social-icons', plugin_dir_url(__FILE__) . 'assets/css/social-icons.min.css');
            wp_enqueue_style('pluginsimplon-admin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            $ajax_nonce = wp_create_nonce('be_ajax_nonce');
            wp_localize_script(
                'pluginsimplon-frontend-script',
                'pluginsimplon_frontend_script',
                array(
                    'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                    'ajax_nonce' => $ajax_nonce,
                )
            );

        }

        // Add Backend Menu
        function pluginsimplon_backend_menu(){

            add_menu_page('Booster Extension', 'Booster Extension', 'manage_options', 'pluginsimplon', array($this, 'pluginsimplon_main_page'), 'dashicons-pluginsimplon');

        }

        // Settings Form
        function pluginsimplon_main_page(){

            include('inc/backend/main-page.php');

        }

        // Saving Form Values
        function pluginsimplon_settings_options(){

            if (isset($_POST['twp_options_nonce'], $_POST['twp_form_submit']) && wp_verify_nonce($_POST['twp_options_nonce'], 'twp_options_nonce') && current_user_can('manage_options')) {
                include('inc/backend/save-settings.php');
            } else {
                die('No script kiddies please!');
            }

        }

        // Add user social meta
        function pluginsimplon_user_fields($contact_methods){

            $contact_methods['twp_user_metabox_facebook'] = __('Facebook', 'pluginsimplon');
            $contact_methods['twp_user_metabox_twitter'] = __('Twitter', 'pluginsimplon');
            $contact_methods['twp_user_metabox_instagram'] = __('Instagram', 'pluginsimplon');
            $contact_methods['twp_user_metabox_pinterest'] = __('Pinterest', 'pluginsimplon');
            $contact_methods['twp_user_metabox_linkedin'] = __('LinkedIn', 'pluginsimplon');
            $contact_methods['twp_user_metabox_youtube'] = __('Youtube', 'pluginsimplon');
            $contact_methods['twp_user_metabox_vimeo'] = __('Vimeo', 'pluginsimplon');
            $contact_methods['twp_user_metabox_whatsapp'] = __('Whatsapp', 'pluginsimplon');
            $contact_methods['twp_user_metabox_github'] = __('Github', 'pluginsimplon');
            $contact_methods['twp_user_metabox_wordpress'] = __('WordPress', 'pluginsimplon');
            $contact_methods['twp_user_metabox_foursquare'] = __('FourSquare', 'pluginsimplon');
            $contact_methods['twp_user_metabox_vk'] = __('VK', 'pluginsimplon');
            $contact_methods['twp_user_metabox_twitch'] = __('Twitch', 'pluginsimplon');
            $contact_methods['twp_user_metabox_tumblr'] = __('Tumblr', 'pluginsimplon');
            $contact_methods['twp_user_metabox_snapchat'] = __('Snapchat', 'pluginsimplon');
            $contact_methods['twp_user_metabox_skype'] = __('Skype', 'pluginsimplon');
            $contact_methods['twp_user_metabox_reddit'] = __('Reddit', 'pluginsimplon');
            $contact_methods['twp_user_metabox_stackoverflow'] = __('Stack Overflow', 'pluginsimplon');
            $contact_methods['twp_user_metabox_xing'] = __('Xing', 'pluginsimplon');
            $contact_methods['twp_user_metabox_delicious'] = __('Delicious', 'pluginsimplon');
            $contact_methods['twp_user_metabox_soundcloud'] = __('SoundCloud', 'pluginsimplon');
            $contact_methods['twp_user_metabox_behance'] = __('Behance', 'pluginsimplon');
            $contact_methods['twp_user_metabox_steam'] = __('Steam', 'pluginsimplon');
            $contact_methods['twp_user_metabox_dribbble'] = __('Dribbble', 'pluginsimplon');
            $contact_methods['twp_user_metabox_blogger'] = __('Blogger', 'pluginsimplon');
            $contact_methods['twp_user_metabox_flickr'] = __('Flickr', 'pluginsimplon');
            $contact_methods['twp_user_metabox_spotify'] = __('spotify', 'pluginsimplon');
            $contact_methods['twp_user_metabox_rss'] = __('RSS', 'pluginsimplon');
            return $contact_methods;
        }

        // Update options on theme activate
        function twp_activation_default_value(){

            if (empty(get_option('twp_be_options_settings'))) {
                include('inc/backend/activation.php');
            }
        }

        // The Contetn Filter
        function pluginsimplon_frontend_the_content($content){

            if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) )
                return $content;
            if ( 'post' == get_post_type() ) {

                $beforecontent = '';
                $after_content = '';
                $all_content = '';

                
                $beforecontent .= "<div class='be-read-views'>";
                $disable_readtime = apply_filters( 'pluginsimplon_filter_readtime_ed', 1 );
                if( $disable_readtime ){
                    $beforecontent .= do_shortcode('[pluginsimplon-read-time]');
                }

                $twp_be_settings = get_option('twp_be_options_settings');
                $twp_be_enable_post_visit_tracking = isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) : '';
                $social_share_ed_before_content = isset( $twp_be_settings[ 'social_share_ed_before_content' ] ) ? esc_html( $twp_be_settings[ 'social_share_ed_before_content' ] ) : '';

                if( $twp_be_enable_post_visit_tracking && is_singular('post') ){

                    $beforecontent .= pluginsimplon_set_post_view();

                    $disable_views = apply_filters( 'pluginsimplon_filter_views_ed', 1 );
                    if( $disable_views ){
                        $beforecontent .= do_shortcode('[pluginsimplon-visit-count]');
                    }
                }
                
                $beforecontent .= "</div>";

                $disable_like = apply_filters( 'pluginsimplon_filter_like_ed', 1 );

                if( $disable_like ){
                    $after_content .= do_shortcode('[pluginsimplon-like-dislike]');
                }

                $disable_ss = apply_filters( 'pluginsimplon_filter_ss_ed', 1 );
                if( $disable_ss ){
                    if( $social_share_ed_before_content ){
                        $beforecontent .= do_shortcode('[pluginsimplon-ss]');
                    }else{
                        $after_content .= do_shortcode('[pluginsimplon-ss]');
                    }
                }

                $disable_ab = apply_filters( 'pluginsimplon_filter_ab_ed', 1 );
                if( $disable_ab ){
                    $after_content .= do_shortcode('[pluginsimplon-ab]');
                }

                $disable_reaction = apply_filters( 'pluginsimplon_filter_reaction_ed', 1 );
                if( $disable_reaction ){
                    $after_content .= do_shortcode('[pluginsimplon-reaction]');
                }

                $all_content .= $beforecontent . $content . $after_content;
                return $all_content;

            } else {
                return $content;
            }
        }

        // Social Share
        function twp_frontend_social_share($args){

            $twp_be_settings = get_option('twp_be_options_settings');
            if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                $page_checked = true;
            } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                $page_checked = true;
            } else {
                $page_checked = false;
            }
            if ($page_checked) {
                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
            }

        }

        // Social Share
        function pluginsimplon_frontend_social_share_action($args){

            $status = '';
            $layout = '';
            if ($args) {
                if (isset($args['layout'])) {
                    $_POST["layout"] = esc_html($args['layout']);
                    $layout = esc_html($args['layout']);
                }
                if (isset($args['status'])) {
                    $_POST["status"] = esc_html($args['status']);
                    $status = esc_html($args['status']);
                }
            }
            if (empty($status)) {
                $twp_be_settings = get_option('twp_be_options_settings');
                if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                    $status = 'enable';
                } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                    $status = 'enable';
                } else {
                    $status = false;
                }
            }
            if ($status == 'enable') {
                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
                $_POST["status"] = '';
            }
        }

        // Social Share Shortcode
        function pluginsimplon_be_social_share_shortcode($args){

            ob_start();
            $status = '';
            $layout = '';
            if( $args ) {

                $_POST["layout"] = isset( $args[ 'layout' ] ) ? esc_html( $args[ 'layout' ] ) : '';
                $_POST["status"] = isset( $args[ 'status' ] ) ? esc_html( $args[ 'status' ] ) : '';
                $status = isset( $args[ 'status' ] ) ? esc_html( $args[ 'status' ] ) : '';

            }
            if (empty($status)) {
                $twp_be_settings = get_option('twp_be_options_settings');
                if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                    $status = 'enable';
                } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                    $status = 'enable';
                } else {
                    $status = false;
                }
            }
            if ($status == 'enable') {

                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
                $_POST["status"] = '';

            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;

        }

        // Post Author Box
        function pluginsimplon_frontend_author_box(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_show_author_box = esc_html($twp_be_settings['twp_be_show_author_box']);
            if ( is_single() || is_archive()){
                include('inc/frontend/author-box.php');
            }
            

        }

        // Author Box Shortcode.
        function pluginsimplon_frontend_author_box_shortcode($userid){

            ob_start();
            $html = '';
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_show_author_box = esc_html($twp_be_settings['twp_be_show_author_box']);

            if ( ( is_single() || is_archive() ) && $twp_be_show_author_box) {
                if (isset($userid['userid'])) {
                    $userid = $userid['userid'];
                    $_POST["userid"] = absint($userid);
                }
                include('inc/frontend/author-box-shortcode.php');
            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;

        }

        // Post Like Dislike.
        function pluginsimplon_frontend_post_like_dislike($allenable){

            if ($allenable) {
                if ( isset( $allenable ) && $allenable == 'allenable' ) {
                    $_POST['allenable'] = esc_html('allenable');
                }
            }
            include('inc/frontend/like-dislike.php');
        }

        // Post Like Dislike.
        function pluginsimplon_frontend_post_like_dislike_shortcode(){

            ob_start();
            include('inc/frontend/like-dislike.php');
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        // Read Time Calculate
        function pluginsimplon_frontend_read_time(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_read_time = esc_html($twp_be_settings['twp_be_enable_read_time']);
            if ( ( is_archive() || is_single() ) && $twp_be_enable_read_time) {
                include('inc/frontend/read-time.php');
            }

        }

        // Read Time Calculate
        function pluginsimplon_frontend_read_time_shortcode(){

            ob_start();
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_read_time = esc_html($twp_be_settings['twp_be_enable_read_time']);
            if ( ( is_archive() || is_single() ) && $twp_be_enable_read_time) {
                include('inc/frontend/read-time.php');
            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        // Read Time Calculate
        function pluginsimplon_frontend_reaction(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_reaction = isset($twp_be_settings['twp_be_enable_post_reaction']) ? $twp_be_settings['twp_be_enable_post_reaction'] : '';
            if ($twp_be_enable_post_reaction) {
                include('inc/frontend/post-reactions.php');
            }
        }

        // Read Time Calculate
        function pluginsimplon_frontend_reaction_shortcode(){

            ob_start();
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_reaction = isset($twp_be_settings['twp_be_enable_post_reaction']) ? $twp_be_settings['twp_be_enable_post_reaction'] : '';
            if ($twp_be_enable_post_reaction) {
                include('inc/frontend/post-reactions.php');
            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

    }

    $GLOBALS['be_global'] = new pluginsimplon_Class();

}
