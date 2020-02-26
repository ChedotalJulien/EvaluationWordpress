<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Post Reactions
*
* @package Plugin Simplon
*/


if( ! function_exists( 'pluginsimplon_post_reaction_display' ) ):

    function pluginsimplon_post_reaction_display(){

        $twp_be_settings = get_option( 'twp_be_options_settings' );

        $twp_be_react_1 = get_post_meta( get_the_ID(), 'twp_be_react_1', true );
        if( empty( $twp_be_react_1 ) ){ $twp_be_react_1 = 0; }
        $twp_be_react_2 = get_post_meta( get_the_ID(), 'twp_be_react_2', true );
        if( empty( $twp_be_react_2 ) ){ $twp_be_react_2 = 0; }
        $twp_be_react_3 = get_post_meta( get_the_ID(), 'twp_be_react_3', true );
        if( empty( $twp_be_react_3 ) ){ $twp_be_react_3 = 0; }
        $twp_be_react_4 = get_post_meta( get_the_ID(), 'twp_be_react_4', true );
        if( empty( $twp_be_react_4 ) ){ $twp_be_react_4 = 0; }
        $twp_be_react_5 = get_post_meta( get_the_ID(), 'twp_be_react_5', true );
        if( empty( $twp_be_react_5 ) ){ $twp_be_react_5 = 0; }
        $twp_be_react_6 = get_post_meta( get_the_ID(), 'twp_be_react_6', true );
        if( empty( $twp_be_react_6 ) ){ $twp_be_react_6 = 0; }

        $total_reacts = $twp_be_react_1 + $twp_be_react_2 + $twp_be_react_3 + $twp_be_react_4 + $twp_be_react_5 + $twp_be_react_6;
        $react_1_percent_friendly = 0;
        $react_2_percent_friendly = 0;
        $react_3_percent_friendly = 0;
        $react_4_percent_friendly = 0;
        $react_5_percent_friendly = 0;
        $react_6_percent_friendly = 0;
        if( $total_reacts >= 1 ){
        	$twp_be_react_1_percent = $twp_be_react_1/$total_reacts;
        	$react_1_percent_friendly = number_format( $twp_be_react_1_percent * 100, 0 );
        	$twp_be_react_2_percent = $twp_be_react_2/$total_reacts;
        	$react_2_percent_friendly = number_format( $twp_be_react_2_percent * 100, 0 );
        	$twp_be_react_3_percent = $twp_be_react_3/$total_reacts;
        	$react_3_percent_friendly = number_format( $twp_be_react_3_percent * 100, 0 );
        	$twp_be_react_4_percent = $twp_be_react_4/$total_reacts;
        	$react_4_percent_friendly = number_format( $twp_be_react_4_percent * 100, 0 );
        	$twp_be_react_5_percent = $twp_be_react_5/$total_reacts;
        	$react_5_percent_friendly = number_format( $twp_be_react_5_percent * 100, 0 );
            $twp_be_react_6_percent = $twp_be_react_6/$total_reacts;
            $react_6_percent_friendly = number_format( $twp_be_react_6_percent * 100, 0 );
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-1' ) ){
        	$be_react_1_class = 'reacted';
        }else{
        	$be_react_1_class = 'un-reacted';
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-2' ) ){
        	$be_react_2_class = 'reacted';
        }else{
        	$be_react_2_class = 'un-reacted';
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-3' ) ){
        	$be_react_3_class = 'reacted';
        }else{
        	$be_react_3_class = 'un-reacted';
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-4' ) ){
        	$be_react_4_class = 'reacted';
        }else{
        	$be_react_4_class = 'un-reacted';
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-5' ) ){
        	$be_react_5_class = 'reacted';
        }else{
        	$be_react_5_class = 'un-reacted';
        }
        if( !pluginsimplon_can_act( get_the_ID(),'be-react-6' ) ){
            $be_react_6_class = 'reacted';
        }else{
            $be_react_6_class = 'un-reacted';
        }
        $twp_be_react_percent_count = isset( $twp_be_settings[ 'twp_be_react_percent_count' ] ) ? $twp_be_settings['twp_be_react_percent_count'] : 'percent';
        ?>
        <div class="booster-reactions-block">
            <div class="twp-reactions-icons">
                
                <div class="twp-reacts-wrap">
                    <a react-data="be-react-1" post-id="<?php the_ID(); ?>" class="be-face-icons <?php echo esc_attr( $be_react_1_class ); ?>" href="javascript:void(0)">
                        <img src="<?php echo pluginsimplon_URL . '/assets/icon/happy.svg';?>" alt="<?php esc_html_e('Happy','booster-extension') ?>">
                    </a>
                    <div class="twp-reaction-title">
                        <?php esc_html_e('Legitimate','booster-extension') ?>
                    </div>
                    <div class="twp-count-percent">
                        <?php if( $twp_be_react_percent_count != 'number' ){ ?>
                            <span style="display: none;" class="twp-react-count"><?php echo absint( $twp_be_react_1 ); ?></span>
                        <?php } ?>

                        <?php if( $twp_be_react_percent_count == 'number' ){ ?>
                            <span class="twp-react-count"><?php echo absint( $twp_be_react_1 ); ?></span>
                        <?php }else{ ?>
                        <span class="twp-react-percent"><span><?php echo esc_html( $react_1_percent_friendly ).'</span> %'; ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="twp-reacts-wrap">
                    <a react-data="be-react-4" post-id="<?php the_ID(); ?>" class="be-face-icons <?php echo esc_attr( $be_react_4_class ); ?>" href="javascript:void(0)">
                        <img src="<?php echo pluginsimplon_URL . '/assets/icon/angry.svg';?>" alt="<?php esc_html_e('Angry','booster-extension') ?>">
                    </a>
                    <div class="twp-reaction-title"><?php esc_html_e('Fake News','booster-extension') ?></div>
                    <div class="twp-count-percent">
                        <?php if( $twp_be_react_percent_count != 'number' ){ ?>
                            <span style="display: none;" class="twp-react-count"><?php echo absint( $twp_be_react_4 ); ?></span>
                        <?php } ?>
                        <?php if( $twp_be_react_percent_count == 'number' ){ ?>
                            <span class="twp-react-count"><?php echo absint( $twp_be_react_4 ); ?></span>
                        <?php }else{ ?>
                        <span class="twp-react-percent"><span><?php echo esc_html( $react_4_percent_friendly ).'</span> %'; ?></span>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>

    <?php
    }

endif;

pluginsimplon_post_reaction_display();
