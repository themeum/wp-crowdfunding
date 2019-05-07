<?php
global $post;
$r = get_post_meta($post->ID, 'wpneo_selected_reward', true);
if ( ! empty($r) && is_array($r) ){
    ?>
    <ul class="order_notes">
        <li rel="30" class="note system-note">
            <div class="note_content">
                <?php
                if ( ! empty($r['wpneo_rewards_description'])){
                    echo "<div>{$r['wpneo_rewards_description']}</div>";
                }
                ?>
            </div>

            <?php if ( ! empty($r['wpneo_rewards_pladge_amount'])){ ?>
                <div class="meta">
                    <abbr>
                        <?php echo sprintf('Amount : %s, Delivery : %s', wc_price($r['wpneo_rewards_pladge_amount']), $r['wpneo_rewards_endmonth'].', '.$r['wpneo_rewards_endyear'] ); ?>
                    </abbr>
                </div>
            <?php } ?>

        </li>
    </ul>
<?php } ?>