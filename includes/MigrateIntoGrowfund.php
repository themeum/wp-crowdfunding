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
                .wpcf-admin-migration{
                    padding: 0 !important;
                    border: none !important;
                    margin-bottom: 1rem;
                }

				.wpcf-admin-migration-banner {
					display: grid;
					grid-template-columns: 1fr 1fr;
					overflow: hidden;
					background: #fff;
					height: 112px;
				}
				.wpcf-admin-migration-banner__image {
					width: 100%;
					height: 112px;
					object-fit: cover;
				}

                .wpcf-admin-migration-banner__info_image {
					width: 100%;
					object-fit: cover;
				}

				.wpcf-admin-migration-banner__content {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 8px 24px;
                    background-color: #E0F5CC;
				}

				.wpcf-admin-migration-banner__text {
					display: flex;
					flex-direction: column;
					gap: 0.25rem;
                    width: 380px;
				}

				.wpcf-admin-migration-banner__title {
					font-size: 1.125rem;
					font-weight: 600;
					color: #111827;
					margin: 0;
				}

				.wpcf-admin-migration-banner__subtitle {
					font-size: 0.875rem;
					color: #6b7280;
					margin: 0;
				}

				/* Actions container */
				.wpcf-admin-migration-banner__actions {
					display: inline-flex;
					align-items: center;
					gap: 0.75rem;
					min-width: 190px;
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
                    min-width: 142px;
				}

				.wpcf-admin-migration-banner__button:hover {
					background-color: #2a7449;
					color: #fff;
				}

				.wpcf-admin-migration-banner__button--close {
					width: 2.5rem;
					height: 2.5rem;
					border: 1px solid #e5e7eb;
					background-color: #f9fafb;
					display: flex;
					align-items: center;
					justify-content: center;
					border-radius: 0.375rem;
					transition: background 0.2s ease-in-out;
				}
				.wpcf-admin-migration-banner__button--close:hover {
					background-color: #f3f4f6;
				}

                .wpcf-admin-migration-banner__header_section {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding-top: 40px;
                }

                .wpcf-admin-migration-banner__header_description {
                    font-weight: 600;
                    font-size: 20px;
                    line-height: 38px;
                    color: #1C733099;
                }

                .wpcf-admin-migration-banner__header {
                    color: #1C7330;
                    font-weight: 600;
                    font-size: 32px;
                    line-height: 38px;
                    font-style: italic;
                }

                .wpcf-admin-migration-banner__header_link {
                    padding-top: 16px;
                    font-weight: 600;
                    font-size: 14px;
                    line-height: 20px;
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
                    document.querySelector('.wpcf-admin-migration-banner__button--close')
                        ?.addEventListener('click', () => {
                        const banner = document.getElementById('wpcf_admin_migration_banner');
                        if (banner) {
                            banner.style.transition = 'opacity 0.3s ease';
                            banner.style.opacity = '0';
                            setTimeout(() => (banner.style.display = 'none'), 300);
                        }
                        });


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
                    <img
                        src="<?php echo esc_url( WPCF_DIR_URL . 'assets/images/migration-top-banner.webp' ); ?>"
                        alt="<?php esc_attr_e('Migration Banner', 'wp-crowdfunding') ?>"
                        class="wpcf-admin-migration-banner__image"
                    />

                    <div class="wpcf-admin-migration-banner__content">
                        <div class="wpcf-admin-migration-banner__text">
                            <h3 class="wpcf-admin-migration-banner__title">
                                <?php esc_html_e('WP Crowdfunding is now Growfund', 'wp-crowdfunding'); ?>
                            </h3>
                            <p class="wpcf-admin-migration-banner__subtitle">
                                <?php esc_html_e('Update to enjoy better performance, new capabilities, and the best crowdfunding experience on WordPress.', 'wp-crowdfunding'); ?>
                            </p>
                        </div>

                        <div class="wpcf-admin-migration-banner__actions">
                            <a 
                                href="<?php echo esc_url( $action_url ); ?>" 
                                class="wpcf-admin-migration-banner__button"
                                id="wpcf-migrate-now"
                            >
                                <span class="dashicons dashicons-randomize"></span>
                                <?php esc_html_e('Migrate Now', 'wp-crowdfunding'); ?>
                            </a>
                            <button class="wpcf-admin-migration-banner__button--close">
                                <span class="dashicons dashicons-no-alt"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="wpcf-admin-migration-banner__header_section">
                    <div class="wpcf-admin-migration-banner__header_description"><?php esc_html_e('Here’s why you should', 'wp-crowdfunding') ?></div>
                    <div class="wpcf-admin-migration-banner__header"><?php esc_html_e('Migrate to Growfund', 'wp-crowdfunding') ?></div>
                    <a class="wpcf-admin-migration-banner__header_link" href="https://growfund.com"><?php esc_html_e('Learn more', 'wp-crowdfunding') ?></a>
                </div>
                <img
                    src="<?php echo esc_url( WPCF_DIR_URL . 'assets/images/migration-info-banner.webp' ); ?>"
                    alt="<?php esc_attr_e('Migration Banner', 'wp-crowdfunding') ?>"
                    class="wpcf-admin-migration-banner__info_image"
                />
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

            wp_redirect( $action_url );
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