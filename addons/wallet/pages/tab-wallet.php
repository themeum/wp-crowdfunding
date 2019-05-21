<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>


<table class="form-table">
    <tbody>
    <tr>
        <th colspan="2">
            <h2> <?php _e('Wallet Settings', 'wp-crowdfunding'); ?> </h2>
            <p> <?php _e('Enable Wallet to split campaign earning between campaign owner and you', 'wp-crowdfunding'); ?> </p>
            <hr>
        </th>
    </tr>

    <tr>
        <th><label for="wpneo_enable_wallet"> <?php _e('Enable Wallet', 'wp-crowdfunding'); ?> </label></th>
        <td>
            <input name="wpneo_enable_wallet" id="wpneo_enable_wallet" value="true" type="checkbox" <?php echo get_option('wpneo_enable_wallet') == 'true' ? 'checked' : ''; ?>  > <?php _e('Checkmark this option to enable native wallet. Please note: Stripe Connect will not work if this option is enabled. You can use any other payment gateways to raise money', 'wp-crowdfunding'); ?>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="wpneo_wallet_withdraw_type"><?php _e('Withdraw type', 'wp-crowdfunding'); ?></label></th>
        <td>
            <label> <input name="wpneo_wallet_withdraw_type" value="anytime_canbe_withdraw" type="radio" <?php echo get_option('wpneo_wallet_withdraw_type') == 'anytime_canbe_withdraw' ? 'checked':''; ?>  > <?php _e('Anytime', 'wp-crowdfunding'); ?> </label> <br>

            <label> <input name="wpneo_wallet_withdraw_type" value="after_certain_period" type="radio" <?php echo get_option('wpneo_wallet_withdraw_type') == 'after_certain_period' ? 'checked':''; ?> > <?php _e('After a certain period of time', 'wp-crowdfunding'); ?> </label><br>
            <label> <input name="wpneo_wallet_withdraw_type" value="after_project_complete" type="radio" <?php echo get_option('wpneo_wallet_withdraw_type') == 'after_project_complete' ? 'checked':''; ?>  > <?php _e('After project completion', 'wp-crowdfunding') ?> </label><br>
            <p> <?php _e('This withdraw type determines when a user a can request a withdrawal', 'wp-crowdfunding'); ?> </p>

            <div id="wpneo_wallet_withdraw_period_wrap" style="display: <?php echo get_option('wpneo_wallet_withdraw_type') == 'after_certain_period' ? 'block':'none'; ?>;">
                <hr />

                <?php $wpneo_wallet_withdraw_period = get_option('wpneo_wallet_withdraw_period'); ?>
                <select name="wpneo_wallet_withdraw_period">
                    <?php for ($i=10; $i<=100; $i=$i+10){
                        $selected = ($wpneo_wallet_withdraw_period == $i) ? 'selected' : '';
                        echo "<option value='{$i}' {$selected}  >{$i}%</option>";
                    } ?>
                </select>
                <p> <?php _e('Withdraw will be applicable after this percent campaign raised amount', 'wp-crowdfunding'); ?> </p>
            </div>

        </td>
    </tr>

    <tr>
        <th>
            <label for="wallet_receiver_percent"><?php _e('Receiver percent', 'wp-crowdfunding'); ?></label>
        </th>
        <td>
            <input id="wallet_receiver_percent" value="<?php echo get_option('wallet_receiver_percent'); ?>" name="wallet_receiver_percent" type="number" placeholder="eg. 60%">
            <p><?php _e('Put the percentage amount you want the campaign owner to have and the rest will go to your account', 'wp-crowdfunding'); ?>.</p>

        </td>
    </tr>


    <tr>
        <th>
            <label for="walleet_min_withdraw_amount"><?php _e('Minimum withdraw amount', 'wp-crowdfunding'); ?></label>
        </th>
        <td>
            <input id="walleet_min_withdraw_amount" value="<?php echo get_option('walleet_min_withdraw_amount'); ?>" name="walleet_min_withdraw_amount" type="number" placeholder="">
            <p><?php _e('The user will not be able to make withdraw request unless his balance exceeds this amount', 'wp-crowdfunding'); ?>.</p>
            <input type="hidden" name="wpneo_varify_wallet_settings" value="true" />
        </td>
    </tr>


    <tr>
        <th colspan="2">
            <h2> <?php _e('Deposit Money', 'wp-crowdfunding'); ?> </h2>
            <p> <?php _e('Allow backers / users to deposit their money to donate later from their wallet balance', 'wp-crowdfunding'); ?> </p>
            <hr>
        </th>
    </tr>


    <tr>
        <th>
            <label for="wpneo_enable_wallet_deposit"> <?php _e('Enable Wallet Deposit', 'wp-crowdfunding'); ?>
            </label>
        </th>
        <td>
            <label>
                <input name="wpneo_enable_wallet_deposit" id="wpneo_enable_wallet_deposit" value="true" type="checkbox" <?php echo get_option('wpneo_enable_wallet_deposit') == 'true' ? 'checked' : ''; ?>  > <?php _e('This will allow you to enable money deposit feature to wallet', 'wp-crowdfunding'); ?>
            </label>
        </td>
    </tr>


    <tr>
        <th>
            <label for="wpneo_deposit_product_id"> <?php _e('Wallet Deposit Checkout Product', 'wp-crowdfunding')
                ; ?>
            </label>
        </th>
        <td>
            <label>

                <select name="wpneo_deposit_product_id">
                    <option value=""><?php _e('Select a product', 'wp-crowdfunding'); ?></option>
                    <?php
                    $products = wc_get_products(array('limit' => -1));
                    $current_product_id = get_option('wpneo_deposit_product_id');

                    foreach ($products as $product){
                        $product_id = $product->get_id();
	                    echo '<option value="'.$product_id.'" '.selected($product_id, $current_product_id).' >'
                             .$product->get_title().'</option>';
                    }
                    ?>
                </select>

            </label>
        </td>
    </tr>



    </tbody>
</table>

