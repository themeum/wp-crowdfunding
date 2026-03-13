<?php

namespace WPCF;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MigrateIntoGrowfund' ) ) {
    class MigrateIntoGrowfund {
        protected function add_styles() 
        {
          ?>
            <style type="text/css">
				.wpcf-admin-migration-banner {
					display: grid;
					grid-template-columns: 1fr 1fr;
					height: 7rem;
					overflow: hidden;
					margin-bottom: 1rem;
					background: #fff;
					height: 112px;
					padding: 0 !important;
                    border: none !important;
				}

				.wpcf-admin-migration-banner__image {
					width: 100%;
					height: 112px;
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
			</style>
          <?php  
        }

        protected function add_scripts()
        {
            ?>
            <script type="text/javascript">
				document.addEventListener('DOMContentLoaded', () => {
				document
					.querySelector('.wpcf-admin-migration-banner__button--close')
					?.addEventListener('click', () => {
					const banner = document.getElementById('wpcf_admin_migration_banner');
					if (banner) {
						banner.style.transition = 'opacity 0.3s ease';
						banner.style.opacity = '0';
						setTimeout(() => (banner.style.display = 'none'), 300);
					}
					});
				});
			</script>
            <?php
        }

        public function admin_notice() {
            $this->add_styles();
            $this->add_scripts();
			?>
			<div id="wpcf_admin_migration_banner" class="wpcf-admin-migration-banner notice">
                <img
                    src="<?php echo esc_url( WPCF_DIR_URL . 'assets/images/migration-top-banner.webp' ); ?>"
                    alt="Migration Banner"
                    class="wpcf-admin-migration-banner__image"
                />

                <div class="wpcf-admin-migration-banner__content">
                    <div class="wpcf-admin-migration-banner__text">
                        <h3 class="wpcf-admin-migration-banner__title">
                            <?php esc_html_e('WP Crowdfunding is now Growfund', 'growfund'); ?>
                        </h3>
                        <p class="wpcf-admin-migration-banner__subtitle">
                            <?php esc_html_e('Update to enjoy better performance, new capabilities, and the best crowdfunding experience on WordPress.', 'growfund'); ?>
                        </p>
                    </div>

                    <div class="wpcf-admin-migration-banner__actions">
                        <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'migrate_into_growfund' ), admin_url() ) ); ?>" class="wpcf-admin-migration-banner__button">
                            <span class="dashicons dashicons-randomize"></span>
                            <?php esc_html_e('Migrate Now', 'growfund'); ?>
                        </a>
                        <button class="wpcf-admin-migration-banner__button--close">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                </div>
            </div>
			<?php
		}
    }
}