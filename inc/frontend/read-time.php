<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Read Time
*
* @package Plugin Simplon
*/

if( ! function_exists( 'pluginsimplon_read_time_display' ) ):

    function pluginsimplon_read_time_display(){

        if( is_single() || is_archive() || is_front_page() ){

            $twp_be_settings = get_option( 'twp_be_options_settings' );
            $twp_be_enable_read_time = isset( $twp_be_settings[ 'twp_be_enable_read_time' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_read_time' ] ) : '';
            $word_per_minute = isset( $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] ) ? absint( $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] ) : '';
            $twp_be_readtime_label = isset( $twp_be_settings[ 'twp_be_readtime_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_readtime_label' ] ) : esc_html__('Read Time','booster-extension');
            $twp_be_enable_second = isset( $twp_be_settings[ 'twp_be_enable_second' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_second' ] ) : 1;
            $twp_be_minute_label = isset( $twp_be_settings[ 'twp_be_minute_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_minute_label' ] ) : esc_html__('Minute','booster-extension');
            $twp_be_second_label = isset( $twp_be_settings[ 'twp_be_second_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_second_label' ] ) : esc_html__('Second','booster-extension');

            if( $twp_be_enable_read_time ){ ?>

                <div class="twp-read-time">
                	<?php
                    $words = count( preg_split('~[^\p{L}\p{N}\']+~u',strip_tags( get_the_content() ) ) );
                    $minutes = floor( $words / $word_per_minute );
                    $seconds = floor( $words % $word_per_minute / ( $word_per_minute / 60 ) );
                    if ( 1 <= $minutes ) {

                        $estimated_time = $minutes.' '.$twp_be_minute_label;

                        if( $twp_be_enable_second ){
                            $estimated_time .= ', ' . $seconds.' '.$twp_be_second_label;
                        }
                    } else {
                        $estimated_time = $seconds.' '.$twp_be_second_label;
                    }
                    echo '<span>'.$twp_be_readtime_label.'</span>';
                    echo esc_html( $estimated_time );
                    ?>
                </div>

            <?php 
            }

        }
    }

endif;

pluginsimplon_read_time_display(); ?>