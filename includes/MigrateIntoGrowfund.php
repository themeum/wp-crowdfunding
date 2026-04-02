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
          ?>
            <style type="text/css">
                .wpcf-admin-migration {
                    padding: 0 !important;
                    border: none !important;
                    margin-bottom: 1rem;
                }

				.wpcf-admin-migration-banner {
					display: flex;
					align-items: center;
                    justify-content: center;
                    gap: 2rem;
					background: #fff;
					/* height: 68px; */
                    background-color: #FFE5E4;
                    border: #E98080 solid 1px;
                    padding: 1rem 2rem;
				}

				.wpcf-admin-migration-banner__text {
					display: flex;
					align-items: center;
					gap: 0.25rem;
                    /* width: 380px; */
				}

				.wpcf-admin-migration-banner__title {
					font-size: 1rem;
					font-weight: 600;
					color: #D40000;
                    line-height: 1.25rem;
					margin: 0;
				}

				/* Actions container */
				.wpcf-admin-migration-banner__actions {
					display: inline-flex;
					align-items: center;
					gap: 0.75rem;
					min-width: 12rem;
				}

				.wpcf-admin-migration-banner__button {
					display: inline-flex;
					align-items: center;
					gap: 0.5rem;
					border-radius: 0.375rem;
					font-weight: 500;
					cursor: pointer;
					border: none;
					text-decoration: none;
					background-color: #338c58;
					color: #fff;
					padding: 0.5rem 1rem;
					transition: background 0.2s ease-in-out;
                    min-width: 7rem;
				}

				.wpcf-admin-migration-banner__button:hover {
					background-color: #2a7449;
					color: #fff;
				}


                .wpcf-admin-migration-banner__header_section {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding-top: 2.5rem;
                }

                .wpcf-admin-migration-banner__header_description {
                    font-weight: 600;
                    font-size: 1.25rem;
                    line-height: 2.5rem;
                    color: #1C733099;
                }

                .wpcf-admin-migration-banner__header {
                    color: #1C7330;
                    font-weight: 600;
                    font-size: 2rem;
                    line-height: 2.5rem;
                    font-style: italic;
                }

                .wpcf-admin-migration-banner__header_link {
                    padding-top: 1rem;
                    font-weight: 600;
                    font-size: 0.875rem;
                    line-height: 1.25rem;
                    color: #EA701F;
                }
			</style>
          <?php  
        }

        protected function add_scripts()
        {
            $consent_text = "Migrating to Growfund gives you a better fundraising experience. To ensure a smooth transition, back up your data before proceeding. Use a WordPress backup plugin or your hosting provider's backup tools. Store the backup securely before starting the migration.";
            ?>
            <script type="text/javascript">
				document.addEventListener('DOMContentLoaded', () => {
                    const migrateBtn = document.getElementById('wpcf-migrate-now');

                    migrateBtn?.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('clicked')

                        const confirmed = confirm("<?php echo $consent_text ?>");

                        if (confirmed) {
                            window.location.href = this.href;
                        }

                    });
				});
			</script>
            <?php
        }

        public function admin_notice() {
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
			<div id="wpcf_admin_migration_banner" class="wpcf-admin-migration notice">
                <div class="wpcf-admin-migration-banner">
                    <div class="wpcf-admin-migration-banner__text">
                        <svg width="31" height="28" viewBox="0 0 31 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_g_16057_2426)">
                            <path d="M1.93789 26.6991C1.67104 26.6991 1.42846 26.6327 1.21013 26.4997C0.991808 26.3668 0.821999 26.1907 0.700706 25.9714C0.579414 25.7521 0.512946 25.5153 0.501302 25.2611C0.489658 25.0069 0.556126 24.7585 0.700706 24.5159L14.1642 1.22775C14.3097 0.985169 14.4979 0.803231 14.7289 0.681938C14.9598 0.560646 15.1961 0.5 15.4377 0.5C15.6793 0.5 15.9161 0.560646 16.148 0.681938C16.3799 0.803231 16.5677 0.985169 16.7113 1.22775L30.1747 24.5159C30.3203 24.7585 30.3872 25.0074 30.3756 25.2626C30.364 25.5178 30.297 25.754 30.1747 25.9714C30.0525 26.1887 29.8827 26.3649 29.6653 26.4997C29.448 26.6346 29.2054 26.7011 28.9376 26.6991H1.93789Z" fill="#FF1616"/>
                            </g>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4649 9.58431C16.4635 9.58663 16.4678 9.5832 16.4649 9.58431C16.479 9.57747 16.4747 9.57916 16.4733 9.57746L16.4649 9.58431C16.467 9.58204 16.4649 9.58431 16.4649 9.58431ZM14.1134 12.1966C14.1133 12.1987 14.1132 12.1942 14.1134 12.1966V12.1966ZM14.1134 12.1966C14.118 12.1225 14.1223 12.0485 14.1267 11.9746L14.1134 12.1966ZM16.4649 9.58431C16.4629 9.58642 16.4649 9.58431 16.4649 9.58431V9.58431Z" fill="white"/>
                            <path d="M15.334 17.6284C15.0992 17.676 14.8305 17.627 14.7724 17.6006C14.7008 17.5745 14.6783 17.557 14.6621 17.5444C14.6374 17.5224 14.6273 17.5105 14.623 17.5035C14.6216 17.5012 14.625 17.5061 14.623 17.5035C14.6167 17.4946 14.6073 17.4799 14.6036 17.474C14.5877 17.4469 14.576 17.4259 14.5662 17.4056C14.5476 17.3677 14.5337 17.3328 14.5231 17.2955L14.5213 17.2869C14.5159 17.2617 14.5112 17.2395 14.5088 17.2175C14.4779 16.9293 14.4456 16.6412 14.4132 16.353C14.2583 14.9751 14.1034 13.5967 14.1136 12.2149C14.1138 12.21 14.1135 12.1997 14.1134 12.1966C14.118 12.1225 14.1223 12.0485 14.1267 11.9746C14.1703 11.234 14.2134 10.5009 14.5404 9.72036C14.5794 9.64032 14.6233 9.55842 14.693 9.46859C14.7307 9.42118 14.7701 9.3764 14.8414 9.31698C14.8608 9.305 14.8764 9.29231 14.893 9.27867C14.9568 9.22659 15.0375 9.16059 15.4177 9.06676C15.6289 9.02304 15.8758 9.01165 16.1027 9.0356C16.3296 9.0596 16.5187 9.11732 16.6286 9.19496C16.738 9.27252 16.76 9.36422 16.6888 9.44996C16.6444 9.50326 16.5681 9.54486 16.4649 9.58431L16.4565 9.59264C16.4446 9.60413 16.4284 9.61962 16.4147 9.63725C16.3743 9.68884 16.3377 9.75346 16.3063 9.81859C16.0035 10.533 15.9589 11.3192 15.9154 12.0848L15.9097 12.1851C15.9096 12.1875 15.91 12.1828 15.9097 12.1851C15.9093 12.1875 15.9081 12.1969 15.908 12.1992C15.899 13.2244 15.8319 14.2533 15.7646 15.2832C15.723 15.9213 15.6813 16.5598 15.6535 17.1979C15.6524 17.2224 15.6493 17.2469 15.6451 17.2713C15.6408 17.3028 15.6355 17.337 15.6277 17.3696C15.6239 17.3859 15.6179 17.402 15.6133 17.4114C15.6142 17.4167 15.616 17.422 15.6178 17.4275C15.6201 17.4348 15.6226 17.4423 15.6232 17.45C15.6218 17.4548 15.6224 17.4577 15.623 17.4606C15.6245 17.4683 15.626 17.4764 15.5923 17.5202C15.5822 17.5233 15.5829 17.5256 15.5838 17.5289C15.5864 17.5387 15.5914 17.5572 15.334 17.6284ZM16.4649 9.58431C16.4605 9.57961 16.4922 9.57109 16.4649 9.58431C16.4649 9.58431 16.467 9.58204 16.4649 9.58431Z" fill="white"/>
                            <circle cx="15.113" cy="20.8201" r="1.73804" fill="white"/>
                            <defs>
                            <filter id="filter0_g_16057_2426" x="0" y="0" width="30.877" height="27.1992" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                            <feTurbulence type="fractalNoise" baseFrequency="0.3125 0.3125" numOctaves="3" seed="6721" />
                            <feDisplacementMap in="shape" scale="1" xChannelSelector="R" yChannelSelector="G" result="displacedImage" width="100%" height="100%" />
                            <feMerge result="effect1_texture_16057_2426">
                            <feMergeNode in="displacedImage"/>
                            </feMerge>
                            </filter>
                            </defs>
                        </svg>
                        <h3 class="wpcf-admin-migration-banner__title">
                            <?php esc_html_e('WP Crowdfunding is now Growfund', 'wp-crowdfunding'); ?>
                        </h3>
                    </div>

                    <div class="wpcf-admin-migration-banner__actions">
                        <a 
                            href="<?php echo esc_url( htmlspecialchars_decode($action_url) ); ?>" 
                            class="wpcf-admin-migration-banner__button"
                            id="wpcf-migrate-now"
                        >
                            <span class="dashicons dashicons-randomize"></span>
                            <span><?php esc_html_e('Migrate to Growfund', 'wp-crowdfunding'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
			<?php
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

            update_option('growfund_checked_migration_consent', 1);

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
			
			include ABSPATH . 'wp-admin/includes/plugin-install.php';
			include ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			if ( ! class_exists( 'Plugin_Upgrader' ) ) {
				include ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
			}

            if ( !class_exists('Automatic_Upgrader_Skin')) {
                include ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
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

    }
}
