<?php

namespace WPCF;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MigrateIntoGrowfund' ) ) {
    class MigrateIntoGrowfund {
        public function __construct() 
        {
            add_action( 'admin_action_migrate_into_growfund', array( $this, 'migrate_to_growfund' ) );
            add_action( 'admin_action_growfund_onboarding_from_wp_crowdfunding', array( $this, 'growfund_onboarding' ) );
        }

        protected function add_styles() 
        {
          wp_enqueue_style('wpcf-migrate-to-growfund-css', WPCF_DIR_URL . 'assets/css/dist/migrate-to-growfund.css', ['wpcf-crowdfunding-css'], WPCF_VERSION);
        }

        protected function add_scripts()
        {
            wp_enqueue_script('wpcf-migrate-to-growfund-scripts', WPCF_DIR_URL . 'assets/js/dist/migrate-to-growfund.min.js', array('wpcf-jquery-scripts'), WPCF_VERSION, true);
        }

        public function migrate_to_growfund()
        {
            if (!check_admin_referer('wpcf_migrate_nonce')) {
                wp_send_json_error();
            }

            if ($_GET['referer'] !== 'wp-crowdfunding') {
               wp_send_json_error();
            }

            if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

            $growfund_agree_to_migrate_from_crowdfunding = $_POST['growfund_agree_to_migrate_from_crowdfunding'] ?? 0;

            if (0 === (int) $growfund_agree_to_migrate_from_crowdfunding) {
                wp_send_json_error();
            }

            update_option('growfund_agree_to_migrate_from_crowdfunding', $growfund_agree_to_migrate_from_crowdfunding);

            $growfund_file = WP_PLUGIN_DIR.'/growfund/growfund.php';

            if ( ! file_exists( $growfund_file ) ) {
                $this->install_growfund_plugin();
            }

            if ( ! is_plugin_active('growfund/growfund.php') ) {
                $this->activate_growfund_plugin();
            }

            die();
        }

        protected function activate_growfund_plugin()
        {
            activate_plugin( 'growfund/growfund.php' );

            $action_url = wp_nonce_url(
                add_query_arg( 
                    array( 
                        'action' => 'growfund_onboarding_from_wp_crowdfunding', 
                        'referer' => 'wp-crowdfunding' 
                    ), 
                    admin_url() 
                ),
                'wpcf_migrate_nonce'
            );

            wp_redirect( htmlspecialchars_decode( $action_url ) );
            die();
        }

        protected function install_growfund_plugin() {
			
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			if ( ! class_exists( 'Plugin_Upgrader' ) ) {
				include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
			}

            if ( !class_exists('Automatic_Upgrader_Skin')) {
                include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
            }

			$plugin = 'growfund';

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $plugin,
					'fields' => array(
						'short_description' => false,
						'sections'          => false,
						'requires'          => false,
						'rating'            => false,
						'ratings'           => false,
						'downloaded'        => false,
						'last_updated'      => false,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
					),
				)
			);

			if ( is_wp_error( $api ) ) {
				wp_die( esc_html( $api->get_error_message() ) );
			}

			$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin());
			$upgrader->install( $api->download_link );
			
            return;
		}

        public function growfund_onboarding() 
        {
            if (!check_admin_referer('wpcf_migrate_nonce')) {
                wp_send_json_error();
            }

            if ($_GET['referer'] !== 'wp-crowdfunding') {
               wp_send_json_error();
            }

            if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

            $growfund_file = WP_PLUGIN_DIR.'/growfund/growfund.php';

            if ( ! file_exists( $growfund_file ) ) {
                wp_send_json_error();
            }

            if ( ! is_plugin_active('growfund/growfund.php') ) {
                wp_send_json_error();
            }

            do_action( 'growfund_apply_onboarding_from_wp_crowdfunding' );

            wp_redirect( admin_url( 'admin.php?page=growfund&referer=wp-crowdfunding#/migrate-from-crowdfunding' ) );
            die();
        }

        public function render_admin_notice() {
            $this->add_styles();
            $this->add_scripts();
    
            $action_url = wp_nonce_url(
                add_query_arg( 
                    array( 
                        'action' => 'migrate_into_growfund', 
                        'referer' => 'wp-crowdfunding' 
                    ), 
                    admin_url() 
                ),
                'wpcf_migrate_nonce'
            );
    
            ?>
            <div id="wpcf-migration-notice" class="wpcf-admin-migration notice wpcf-migration-notice">
                <div class="wpcf-migration-notice-container">
                    <div class="wpcf-migration-notice-section">
                        <div class="wpcf-migration-notice-header">
                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_g_16405_3128)">
                                <path d="M1.27368 16.7495C1.1069 16.7495 0.955287 16.7079 0.818834 16.6248C0.68238 16.5417 0.576249 16.4317 0.500441 16.2946C0.424634 16.1576 0.383091 16.0096 0.375814 15.8507C0.368536 15.6918 0.410079 15.5365 0.500441 15.3849L8.91509 0.829846C9.00606 0.678231 9.12372 0.564519 9.26805 0.488712C9.41239 0.412904 9.56007 0.375 9.71108 0.375C9.86208 0.375 10.0101 0.412904 10.155 0.488712C10.2999 0.564519 10.4173 0.678231 10.5071 0.829846L18.9217 15.3849C19.0127 15.5365 19.0545 15.6921 19.0472 15.8516C19.04 16.0111 18.9981 16.1588 18.9217 16.2946C18.8453 16.4305 18.7392 16.5405 18.6033 16.6248C18.4675 16.7091 18.3159 16.7507 18.1485 16.7495H1.27368Z" fill="#FF1616"/>
                                </g>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3531 6.05098C10.3522 6.05244 10.3549 6.05029 10.3531 6.05098C10.3619 6.04671 10.3592 6.04776 10.3584 6.0467L10.3531 6.05098C10.3544 6.04957 10.3531 6.05098 10.3531 6.05098ZM8.88341 7.68366C8.88335 7.68495 8.88334 7.68214 8.88341 7.68366V7.68366ZM8.88341 7.68366C8.88631 7.63733 8.88903 7.59109 8.89175 7.54489L8.88341 7.68366ZM10.3531 6.05098C10.3519 6.05231 10.3531 6.05098 10.3531 6.05098V6.05098Z" fill="white"/>
                                <path d="M9.6463 11.0785C9.49954 11.1083 9.33164 11.0777 9.29532 11.0612C9.25057 11.0449 9.2365 11.0339 9.22635 11.0261C9.21092 11.0123 9.20461 11.0048 9.20195 11.0005C9.20109 10.999 9.20317 11.0021 9.20195 11.0005C9.19801 10.9949 9.19211 10.9857 9.18982 10.9821C9.17985 10.9651 9.17257 10.952 9.16643 10.9393C9.1548 10.9156 9.14614 10.8938 9.13953 10.8705L9.13837 10.8651C9.13502 10.8494 9.13207 10.8355 9.13058 10.8217C9.11128 10.6416 9.09104 10.4615 9.07079 10.2814C8.97399 9.42023 8.87716 8.55874 8.88358 7.69511C8.88368 7.69205 8.8835 7.68562 8.88341 7.68366C8.88631 7.63733 8.88903 7.59109 8.89175 7.54489C8.91897 7.08204 8.94592 6.62387 9.15029 6.13602C9.17471 6.08599 9.20214 6.0348 9.24567 5.97866C9.26928 5.94903 9.29386 5.92104 9.33844 5.8839C9.35058 5.87641 9.36028 5.86849 9.37071 5.85996C9.41054 5.82741 9.461 5.78616 9.69865 5.72752C9.83061 5.70019 9.98493 5.69307 10.1268 5.70804C10.2686 5.72304 10.3867 5.75911 10.4554 5.80764C10.5238 5.85612 10.5376 5.91343 10.493 5.96702C10.4653 6.00033 10.4176 6.02633 10.3531 6.05098L10.3479 6.05619C10.3404 6.06337 10.3303 6.07306 10.3218 6.08407C10.2965 6.11631 10.2736 6.1567 10.254 6.19741C10.0648 6.6439 10.0369 7.13532 10.0097 7.61377L10.0061 7.67649C10.006 7.67795 10.0063 7.67502 10.0061 7.67649C10.0059 7.67796 10.0051 7.68384 10.005 7.68531C9.99945 8.32603 9.95748 8.96911 9.91546 9.6128C9.88943 10.0116 9.86339 10.4106 9.84597 10.8095C9.84532 10.8248 9.84337 10.8401 9.84076 10.8554C9.83806 10.875 9.83478 10.8964 9.82989 10.9168C9.82748 10.927 9.82374 10.937 9.82085 10.9429C9.82147 10.9462 9.82255 10.9496 9.82366 10.953C9.82515 10.9576 9.82667 10.9623 9.82709 10.967C9.82621 10.97 9.82656 10.9718 9.82692 10.9737C9.82785 10.9785 9.82882 10.9835 9.80773 11.0109C9.80146 11.0128 9.80185 11.0143 9.80241 11.0163C9.80406 11.0225 9.80719 11.0341 9.6463 11.0785ZM10.3531 6.05098C10.3504 6.04805 10.3702 6.04272 10.3531 6.05098C10.3531 6.05098 10.3544 6.04957 10.3531 6.05098Z" fill="white"/>
                                <circle cx="9.50815" cy="13.0726" r="1.08627" fill="white"/>
                                <defs>
                                <filter id="filter0_g_16405_3128" x="0" y="0" width="19.4229" height="17.125" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                <feTurbulence type="fractalNoise" baseFrequency="0.4166666567325592 0.4166666567325592" numOctaves="3" seed="6721" />
                                <feDisplacementMap in="shape" scale="0.75" xChannelSelector="R" yChannelSelector="G" result="displacedImage" width="100%" height="100%" />
                                <feMerge result="effect1_texture_16405_3128">
                                <feMergeNode in="displacedImage"/>
                                </feMerge>
                                </filter>
                                </defs>
                            </svg>
                            <span class="wpcf-migration-notice-header-text"><?php esc_html_e( 'IMPORTANT NOTICE', 'wp-crowdfunding' ) ?></span>
                        </div>
                        <div class="wpcf-migration-notice-title-wrapper">
                            <div class="wpcf-migration-notice-title">
                                <?php esc_html_e( 'WP Crowdfunding is now Growfund.', 'wp-crowdfunding' ) ?>
                            </div>
                            <div class="wpcf-migration-notice-description">
                                <?php esc_html_e( 'To keep receiving updates and support, please migrate to Growfund.', 'wp-crowdfunding' ) ?>
                            </div>
                        </div>
                        <div class="wpcf-migration-notice-learn-more">
                            <a href="<?php echo esc_url( 'https://growfund.com/docs/migrating-from-wp-crowdfunding-to-growfund/' ) ?>" target="_blank">
                                <?php esc_html_e( 'Learn more', 'wp-crowdfunding' ) ?>
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5 7.16667V11.1667C10.5 11.5203 10.3595 11.8594 10.1095 12.1095C9.85943 12.3595 9.52029 12.5 9.16667 12.5H1.83333C1.47971 12.5 1.14057 12.3595 0.890524 12.1095C0.640476 11.8594 0.5 11.5203 0.5 11.1667V3.83333C0.5 3.47971 0.640476 3.14057 0.890524 2.89052C1.14057 2.64048 1.47971 2.5 1.83333 2.5H5.83333M12.5 4.5V0.5H8.5M12.5 0.5L5.16667 7.83333" stroke="#0055FF" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="wpcf-migration-consent-section">
                        <div class="wpcf-migration-info">
                            <div class="wpcf-migration-info-title">
                                <?php esc_html_e( 'Here’s what will happen when you migrate:', 'wp-crowdfunding' ) ?>
                            </div>
                            <div class="wpcf-migration-info-item">
                                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.22461 0.527344C8.3016 0.577768 8.32278 0.68085 8.27246 0.757812L3.73926 7.69141C3.71263 7.73204 3.66962 7.75942 3.62109 7.76562C3.57295 7.77178 3.52442 7.75629 3.48828 7.72363L0.554688 5.05664C0.486645 4.99478 0.481227 4.88941 0.542969 4.82129C0.604886 4.75318 0.71022 4.74865 0.77832 4.81055L3.13379 6.95117L3.56738 7.3457L3.88867 6.85449L7.99414 0.575195C8.04461 0.49844 8.1477 0.47706 8.22461 0.527344Z" fill="#4D4D4D" stroke="#4D4D4D"/>
                                </svg>
                                <?php esc_html_e( 'Growfund will be automatically installed.', 'wp-crowdfunding' ) ?>
                            </div>
                            <div class="wpcf-migration-info-item">
                                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.22461 0.527344C8.3016 0.577768 8.32278 0.68085 8.27246 0.757812L3.73926 7.69141C3.71263 7.73204 3.66962 7.75942 3.62109 7.76562C3.57295 7.77178 3.52442 7.75629 3.48828 7.72363L0.554688 5.05664C0.486645 4.99478 0.481227 4.88941 0.542969 4.82129C0.604886 4.75318 0.71022 4.74865 0.77832 4.81055L3.13379 6.95117L3.56738 7.3457L3.88867 6.85449L7.99414 0.575195C8.04461 0.49844 8.1477 0.47706 8.22461 0.527344Z" fill="#4D4D4D" stroke="#4D4D4D"/>
                                </svg>
                                <?php esc_html_e( 'WP Crowdfunding will be deactivated.', 'wp-crowdfunding' ) ?>
                            </div>
                            <div class="wpcf-migration-info-item">
                                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.22461 0.527344C8.3016 0.577768 8.32278 0.68085 8.27246 0.757812L3.73926 7.69141C3.71263 7.73204 3.66962 7.75942 3.62109 7.76562C3.57295 7.77178 3.52442 7.75629 3.48828 7.72363L0.554688 5.05664C0.486645 4.99478 0.481227 4.88941 0.542969 4.82129C0.604886 4.75318 0.71022 4.74865 0.77832 4.81055L3.13379 6.95117L3.56738 7.3457L3.88867 6.85449L7.99414 0.575195C8.04461 0.49844 8.1477 0.47706 8.22461 0.527344Z" fill="#4D4D4D" stroke="#4D4D4D"/>
                                </svg>
                                <?php esc_html_e( 'Our support team will assist you throughout your journey.', 'wp-crowdfunding' ) ?>
                            </div>
                        </div>
                        <form class="wpcf-migration-consent-wrapper" method="POST" action="<?php echo esc_url( $action_url ) ?>">
                            <label for="wpcf-migration-consent-checkbox-input" class="wpcf-migration-consent-checkbox-label">
                                <input type="checkbox" id="wpcf-migration-consent-checkbox-input" name="growfund_agree_to_migrate_from_crowdfunding" value="0" />
                                <span class="wpcf-migration-consent-text">
                                    <?php esc_html_e( 'I have backed up my site & agree to the consents above.', 'wp-crowdfunding' ) ?>
                                </span>
                            </label>
                            <div class="wpcf-migration-consent-btn-wrapper">
                                <button id="wpcf-migration-confirm-btn" type="submit" class="wpcf-migration-confirm-btn" disabled>
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.5 11.1667H1.43333C2.3 11.1667 3.1 10.7667 3.63333 10.0333L7.7 4.3C8.16667 3.56667 9.03333 3.16667 9.9 3.16667H13.8333M11.1667 5.83333L13.8333 3.16667L11.1667 0.5M0.5 3.16667H1.76667C2.76667 3.16667 3.7 3.76667 4.16667 4.63333M13.8333 11.1667H9.9C9.03333 11.1667 8.16667 10.7 7.7 9.96667L7.36667 9.43333M11.1667 13.8333L13.8333 11.1667L11.1667 8.5" stroke="#F5F5F5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                    <?php esc_html_e( 'Migrate to Growfund', 'wp-crowdfunding' ) ?>
                                </button>
                                <button id="wpcf-migration-cancel-btn" type="button" class="wpcf-migration-cancel-btn">
                                    <?php esc_html_e( 'Not ready yet', 'wp-crowdfunding' ) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
