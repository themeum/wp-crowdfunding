<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see wpcf_default_single_campaign_tabs()
 */
$tabs = apply_filters( 'wpcf_default_single_campaign_tabs', array() );

if ( ! empty( $tabs ) ) : ?>
    <div class="wpcf-tabs">
        <div class="wpcf-card">
            <ul class="wpcf-nav wpcf-nav-tabs">
                <?php
                    $i = 0;
                    foreach ( $tabs as $key => $tab ) :
                    $i++;
                    $current = $i === 1 ? ' wpcf-is-active' : '';
                ?>
                    <li>
                        <a href="#" class="wpcf-nav-item<?php echo $current; ?>" role="tab"><?php echo apply_filters( 'wpcf_campaign_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="wpcf-tab-content wpcf-pt-4">
            <?php $j = 0; ?>
            <?php foreach ( $tabs as $key => $tab ) : ?>
                <div class="wpcf-tab-pane<?php echo $j == 0 ? ' wpcf-is-active' : ''; ?>" role="tabpanel">
                    <?php call_user_func( $tab['callback'], $key, $tab ); ?>
                </div>
            <?php $j++; endforeach; ?>
        </div>
    </div>
<?php endif; ?>