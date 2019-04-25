<table class="widefat striped our-products">
    <tr>
        <td> <?php _e( 'By','wp-crowdfunding' ); ?> : </td>
        <td>
            <span class="label-default">
            <?php
            $user = get_userdata($post->post_author);
            echo $user->display_name;
            ?>
            </span>
        </td>
    </tr>
    <tr>
        <td><?php _e('Start Date', 'wp-crowdfunding'); ?></td>
        <td><span class="label-primary"><?php echo get_post_meta($post->ID, '_nf_duration_start', true);  ?></span></td>
    </tr>
    <tr>
        <td><?php _e('End Date', 'wp-crowdfunding'); ?></td>
        <td><span class="label-success"><?php echo get_post_meta($post->ID, '_nf_duration_end', true);  ?></span></td>
    </tr>
    <tr>
        <td><?php _e('Goal', 'wp-crowdfunding'); ?></td>
        <td><span class="label-info"><?php echo wc_price(get_post_meta($post->ID, '_nf_funding_goal', true)); ?></span></td>
    </tr>
    <tr>
        <td><?php _e('Raised', 'wp-crowdfunding'); ?></td>
        <td>
            <span class="label-warning">
            <?php
            $raised_total = wpneo_crowdfunding_get_total_fund_raised_by_campaign();
            echo $raised_total ? wc_price($raised_total) : wc_price(0);
            ?>
            </span>
        </td>
    </tr>
    <tr>
        <td><?php _e('Raised Percent', 'wp-crowdfunding'); ?></td>
        <td><span class="label-danger"><?php echo WPNEOCF()->getFundRaisedPercentFormat(); ?></span></td>
    </tr>
</table>