<div class="wrap">
    <h1><?php _e('Add-ons', 'wp-crowdfunding'); ?></h1>

    <?php $addons = apply_filters('wpcf_addons_lists_config', array()); ?>

    <?php if (is_array($addons) && count($addons)) : ?>
        <div class="wpcf-addons">
            <?php foreach ( $addons as $basName => $addon ) :
                $addonConfig = wpcf_function()->get_addon_config($basName);
                $isEnable = (bool)wpcf_function()->avalue_dot('is_enable', $addonConfig);

                $thumbnailURL =  WPCF_DIR_URL.'assets/images/wpcf-plugin.png';
                if (file_exists($addon['path'].'assets/images/thumbnail.png') ) {
                    $thumbnailURL = $addon['url'].'assets/images/thumbnail.png';
                } elseif (file_exists($addon['path'].'assets/images/thumbnail.svg')) {
                    $thumbnailURL = $addon['url'].'assets/images/thumbnail.svg';
                }
            ?>
                <div class="wpcf-addon">
                    <div class="wpcf-addon-inner">
                        <div class="wpcf-card">
                            <div class="wpcf-card-body">
                                <div>
                                    <img src="<?php echo $thumbnailURL; ?>" class="wpcf-addon-thumbnail" alt="<?php echo $addon['name']; ?>" loading="lazy">
                                </div>
                                <h3 class="wpcf-addon-title"><?php echo $addon['name']; ?></h3>
                                <div class="wpcf-addon-description">
                                    <?php echo $addon['description']; ?>
                                </div>
                            </div>

                            <div class="wpcf-card-footer">
                                <div class="wpcf-checkbox-switcher">
                                    <input type="checkbox" class="wpcf_addons_list_item" value="1" name="<?php echo $basName; ?>" <?php checked(true, $isEnable) ?>>
                                    <label><?php _e( 'Activate', 'wp-crowdfunding' ); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php //Pro Addons ?>
            <?php if( wpcf_function()->is_free() || (!function_exists('WC') && !wpcf_function()->is_free()) ) : ?>
                <?php
                    $proAddons = array(
                        '2checkout' => array(
                            'name'          => __( '2Checkout', 'wp-crowdfunding' ),
                            'description'   => __( 'Offer 2Checkout.com payment gateway option for all transactions.', 'wp-crowdfunding'),
                        ),
                        'authorizenet' => array(
                            'name'          => __( 'Authorize.Net', 'wp-crowdfunding' ),
                            'description'   => __( 'Provide Authorize.net payment gateway option for users.', 'wp-crowdfunding' ),
                        ),
                        'email' => array(
                            'name'          => __( 'Email', 'wp-crowdfunding' ),
                            'description'   => __( 'Connect with users through customizable email templates using Email addon.', 'wp-crowdfunding' ),
                        ),
                        'recaptcha' => array(
                            'name'          => __( 'reCAPTCHA', 'wp-crowdfunding' ),
                            'description'   => __( 'Secure your site from bots and other identity threats with reCAPTCHA.', 'wp-crowdfunding' ),
                        ),
                        'reports' => array(
                            'name'          => __( 'Reports', 'wp-crowdfunding' ),
                            'description'   => __( 'Get detailed analytics & stats using advanced filters with powerful reports.', 'wp-crowdfunding' ),
                        ),
                        'stripe-connect' => array(
                            'name'          => __( 'Stripe connect', 'wp-crowdfunding' ),
                            'description'   => __( 'Enable Stripe Connect payment gateways to boost donations of your campaigns.', 'wp-crowdfunding' ),
                        ),
                        'wallet' => array(
                            'name'          => __( 'Wallet', 'wp-crowdfunding' ),
                            'description'   => __( 'Support native payment system for all donations using the native wallet addon.', 'wp-crowdfunding' ),
                        )
                    );
                ?>

                <?php foreach ( $proAddons as $basName => $addon ) : ?>
                    <?php
                        $addonConfig = wpcf_function()->get_addon_config($basName);
    
                        $addons_path = trailingslashit(WPCF_DIR_PATH."assets/addons/{$basName}");
                        $addons_url = trailingslashit(WPCF_DIR_URL."assets/addons/{$basName}");

                        $thumbnailURL =  WPCF_DIR_URL.'assets/images/wpcf-plugin.png';

                        if (file_exists($addons_path.'thumbnail.png') ) {
                            $thumbnailURL = $addons_url.'thumbnail.png';
                        } elseif (file_exists($addons_path.'thumbnail.svg')) {
                            $thumbnailURL = $addons_url.'thumbnail.svg';
                        }
                    ?>

                    <div class="wpcf-addon">
                        <div class="wpcf-addon-inner">
                            <div class="wpcf-card">    
                                <div class="wpcf-card-body">
                                    <div>
                                        <img src="<?php echo $thumbnailURL; ?>" class="wpcf-addon-thumbnail" alt="<?php echo $addon['name']; ?>" loading="lazy">
                                    </div>
                                    <h3 class="wpcf-addon-title"><?php echo $addon['name']; ?></h3>
                                    <div class="wpcf-addon-description">
                                        <?php echo $addon['description']; ?>
                                    </div>
                                </div>

                                <div class="wpcf-card-footer wpcf-card-footer-warning">
                                    <?php if( wpcf_function()->is_free() ) : ?>
                                        <div><?php _e('Available in the Pro Version', 'wp-crowdfunding'); ?></div>
                                        <a class="wpcf-stretched-link" href="https://www.themeum.com/product/wp-crowdfunding-plugin/?utm_source=crowdfunding_plugin" target="_blank"><span class="wpcf-sr-only"><?php _e('Buy Now','wp-crowdfunding'); ?></span></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>