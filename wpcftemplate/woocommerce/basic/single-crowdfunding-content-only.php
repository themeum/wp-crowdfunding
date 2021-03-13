<?php
defined( 'ABSPATH' ) || exit;

do_action( 'wpcf_before_single_campaign' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div class="wpcf-wrapper">
    <div class="wpcf-container">
        <div class="content-area">
            <div id="content" class="site-content" role="main">
                <div class="wpcf-campaign-details">
					<?php do_action( 'wpcf_before_main_content' ); ?>
                    <div id="campaign-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/ItemList">
						<?php do_action( 'wpcf_before_single_campaign_summary' ); ?>
                        <div class="wpcf-campaign-summary" itemscope itemtype="http://schema.org/DonateAction">
							<?php do_action( 'wpcf_single_campaign_summary' ); ?>
                        </div>
						<?php do_action( 'wpcf_after_single_campaign_summary' ); ?>
                        <meta itemprop="url" content="<?php the_permalink(); ?>" />
                    </div>
					<?php do_action( 'wpcf_after_single_campaign' ); ?>
					<?php do_action( 'wpcf_after_main_content' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>
