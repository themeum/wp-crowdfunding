<?php
defined( 'ABSPATH' ) || exit;
// $reward is set by Dashboard::selected_reward_meta_box_display_callback() from the order (HPOS or posts).
$reward = isset( $reward ) && is_array( $reward ) ? $reward : array();
if ( ! empty($reward) ){
    ?>
    <ul class="order_notes">
        <li class="note system-note">
            <div class="note_content">
                <?php
                if ( ! empty($reward['wpneo_rewards_description']) ){
                    echo '<div>' . esc_html($reward['wpneo_rewards_description']) . '</div>';
                }
                ?>
            </div>
            <?php if( ! empty($reward['wpneo_rewards_pladge_amount']) ) { ?>
                <div class="meta">
                    <abbr>
                        <?php echo sprintf(
                            /* translators: 1: pledge amount, 2: delivery month and year */
                            esc_html__('Amount : %1$s, Delivery : %2$s', 'wp-crowdfunding'),
                            wp_kses_post(wc_price($reward['wpneo_rewards_pladge_amount'])),
                            esc_html($reward['wpneo_rewards_endmonth']) . ', ' . esc_html($reward['wpneo_rewards_endyear'])
                        ); ?>
                    </abbr>
                </div>
            <?php } ?>
        </li>
    </ul>
<?php } ?>