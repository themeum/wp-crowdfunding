<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'wpneo_registration', 'wpcf_registration_callback' ); //@comparability
add_shortcode( 'wpcf_registration', 'wpcf_registration_callback' );

function wpcf_registration_callback() {

    if ( is_user_logged_in() ) { ?>
        <h3 class="wpneo-center"><?php _e("You are already logged in.","wp-crowdfunding"); ?></h3>
    <?php } else {
      global $reg_errors,$reg_success;
      $nonce = wp_create_nonce( 'wpcf-nonce-registration' );
      ?>
        <div class="wpneo-user-registration-wrap">
            <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" id="wpneo-registration" method="post">
                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>">
                <?php
                $regisration_data = array(
                    array(
                        'id'            => 'fname',
                        'label'         => __( "First Name" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter First Name', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => 'wpneo-first-half',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'lname',
                        'label'         => __( "Last Name" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Last Name', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => 'wpneo-second-half',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'username',
                        'label'         => __( "Username *" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Username', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => 'required',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'password',
                        'label'         => __('Password *', 'wp-crowdfunding'),
                        'type'          => 'password',
                        'placeholder'   => __('Enter Password', 'wp-crowdfunding'),
                        'class'         => 'required',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'email',
                        'label'         => __( "Email *" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Email', 'wp-crowdfunding'),
                        'value'         => '',
                        'warpclass'     => 'wpneo-first-half',
                        'class'         => 'required',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'website',
                        'label'         => __( "Website" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Website', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => 'wpneo-second-half',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'nickname',
                        'label'         => __( "Nickname" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Nickname', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'bio',
                        'label'         => __( "About / Bio" , "wp-crowdfunding" ),
                        'type'          => 'textarea',
                        'placeholder'   => __('Enter About / Bio', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    )
                );

                $regisration_meta = apply_filters('wpcf_registration_fields', $regisration_data );

                foreach( $regisration_meta as $item ){ ?>
                    <div class="wpneo-single <?php echo (isset($item['warpclass'])? $item['warpclass'] : "" ); ?>">
                        <div class="wpneo-name"><?php echo (isset($item['label'])? $item['label'] : "" ); ?></div>
                        <div class="wpneo-fields">
                            <?php
                            switch ($item['type']){
                                case 'text':
                                echo '<input type="text" id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" placeholder="'.$item['placeholder'].'">';
                                    break;
                                case 'password':
                                echo '<input type="password" id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" placeholder="'.$item['placeholder'].'">';
                                    break;
                                case 'textarea':
                                echo '<textarea id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" ></textarea>';
                                    break;
                                case 'submit':
                                echo '<input type="submit" id="'.$item['id'].'"  class="'.$item['class'].'" name="'.$item['id'].'" />';
                                    break;
                                case 'shortcode':
                                echo do_shortcode($item['shortcode']);
                                    break;
                            } ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="wpneo-single wpneo-register">
                    <a href="<?php echo get_home_url(); ?>" class="wpneo-cancel-campaign"><?php _e("Cancel","wp-crowdfunding"); ?></a>
                    <input type="hidden" name="action" value="wpneo_crowdfunding_registration_action" />
                    <input type="hidden" name="current_page" value="<?php echo get_the_permalink(); ?>" />
                    <input type="submit" class="wpneo-submit-campaign" id="user-registration-btn" value="<?php _e('Sign UP', 'wp-crowdfunding'); ?>" name="submits" />
                </div>

            </form>
        </div>
        <?php
    }
    return ob_get_clean();
}
