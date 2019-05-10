<div class="wrap">
    <div class="tutor-addons-list">
        <h3 class="addon-list-heading"><?php _e('Addons List', 'wpcf'); ?></h3>
        <br class="clear">
		<?php
        $addons = apply_filters('wpcf_addons_lists_config', array());

        /* echo "<pre>";
        print_r(maybe_unserialize(get_option('wpcf_addons_config'))); */

		if (is_array($addons) && count($addons)){
			?>
            <div class="wp-list-table widefat plugin-install">
                <div id="the-list">
					<?php
					foreach ( $addons as $basName => $addon ) {
						$addonConfig = get_wpcf_addon_config($basName);
                        $isEnable = (bool) wpcf_avalue_dot('is_enable', $addonConfig);

						$thumbnailURL =  WPCF_DIR_URL.'assets/images/wpcf-plugin.png';
						if (file_exists($addon['path'].'assets/images/thumbnail.png') ){
							$thumbnailURL = $addon['url'].'assets/images/thumbnail.png';
                        } elseif (file_exists($addon['path'].'assets/images/thumbnail.svg')){
							$thumbnailURL = $addon['url'].'assets/images/thumbnail.svg';
						}

						?>
                        <div class="plugin-card plugin-card-akismet">
                            <div class="plugin-card-top">
                                <div class="name column-name">
                                    <h3>
										<?php
										echo $addon['name'];
										echo "<img src='{$thumbnailURL}' class='plugin-icon' alt=''>";
										?>
                                    </h3>
                                </div>
                                <div class="action-links">
                                    <ul class="plugin-action-buttons">
                                        <li>
                                            <label class="btn-switch">
                                                <input type="checkbox" class="wpcf_addons_list_item" value="1" name="<?php echo $basName; ?>" <?php checked(true, $isEnable) ?> />
                                                <div class="btn-slider btn-round"></div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="desc column-description">
                                    <p><?php echo $addon['description']; ?></p>

                                    <p class="authors"><cite>By <a href="https://www.themeum.com" target="_blank">Themeum</a></cite></p>
                                </div>
                            </div>
                        </div>
                    <?php }

                    //PRO ADDONS LIST FOR DISPLAY
                    if( WPCF_TYPE == 'free' ) {
                        $proAddons = array(
                            'email' => array(
                                'name'          => __( 'Email', 'wp-crowdfunding' ),
                                'description'   => __( 'Student will able to download certificate of completed course', 'wp-crowdfunding' ),
                            ),
                            'recaptcha' => array(
                                'name'          => __( 'reCAPTCHA', 'wp-crowdfunding' ),
                                'description'   => __( 'Stay away from all spam comments and unauthorized login attempts by reCAPTCHA', 'wp-crowdfunding' ),
                            ),
                        );

                        foreach ( $proAddons as $basName => $addon ) {
                            $addonConfig = get_wpcf_addon_config($basName);
    
                            $addons_path = trailingslashit(WPCF_DIR_PATH."assets/addons/{$basName}");
                            $addons_url = trailingslashit(WPCF_DIR_URL."assets/addons/{$basName}");
    
                            $thumbnailURL =  WPCF_DIR_URL.'assets/images/wpcf-plugin.png';
    
                            if (file_exists($addons_path.'thumbnail.png') ) {
                                $thumbnailURL = $addons_url.'thumbnail.png';
                            } elseif (file_exists($addons_path.'thumbnail.svg')) {
                                $thumbnailURL = $addons_url.'thumbnail.svg';
                            }
    
                            ?>
                            <div class="plugin-card plugin-card-akismet">
                                <div class="plugin-card-top">
                                    <div class="name column-name">
                                        <h3>
                                            <?php
                                            echo $addon['name'];
                                            echo "<img src='{$thumbnailURL}' class='plugin-icon' alt=''>";
                                            ?>
                                        </h3>
                                    </div>
                                    <div class="action-links">
                                        <ul class="plugin-action-buttons">
                                            <li>
                                                <a href="https://www.themeum.com/product/wp-crowdfunding-plugin/?utm_source=tutor&utm_medium=addons_lists&utm_campaign=wpcf_addons_lists"
                                                   class="addon-buynow-link" target="_blank">Buy Now</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="desc column-description">
                                        <p><?php echo $addon['description']; ?></p>
                                        <p class="authors"><cite>By <a href="https://www.themeum.com/?utm_source=tutor&utm_medium=addons_lists&utm_campaign=wpcf_addons_lists" target="_blank">Themeum</a></cite></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }?>
                </div>
            </div>

            <br class="clear">
			<?php
		}
		?>
    </div>
</div>