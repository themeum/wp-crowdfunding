<?php
defined( 'ABSPATH' ) || exit;

/**
 * Whether the active theme ships a classic PHP template file.
 *
 * Block themes such as Twenty Twenty-Five do not include header.php / footer.php.
 * Calling get_header() / get_footer() then loads wp-includes/theme-compat copies
 * and triggers a "Theme without header.php is deprecated" notice.
 *
 * @param string $template Template filename, e.g. header.php.
 * @return bool
 */
function wpcf_theme_has_php_template( $template ) {
	$template = ltrim( (string) $template, '/' );

	if ( file_exists( get_stylesheet_directory() . '/' . $template ) ) {
		return true;
	}

	return get_template_directory() !== get_stylesheet_directory()
		&& file_exists( get_template_directory() . '/' . $template );
}

/**
 * Output the theme header, including block theme template parts when needed.
 *
 * @param string|null $name Header name passed to get_header() on classic themes.
 */
function wpcf_get_header( $name = null ) {
	if ( wpcf_theme_has_php_template( 'header.php' ) ) {
		get_header( $name );
		return;
	}

	do_action( 'get_header', $name, array() );

	$GLOBALS['wpcf_using_block_theme_canvas'] = true;

	// Render template parts before wp_head() so block styles can be printed in <head>.
	ob_start();
	if ( function_exists( 'block_header_area' ) ) {
		block_header_area();
	}
	$GLOBALS['wpcf_block_header_html'] = ob_get_clean();

	ob_start();
	if ( function_exists( 'block_footer_area' ) ) {
		block_footer_area();
	}
	$GLOBALS['wpcf_block_footer_html'] = ob_get_clean();
	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
	<?php echo $GLOBALS['wpcf_block_header_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php
}

/**
 * Output the theme footer, including block theme template parts when needed.
 *
 * @param string|null $name Footer name passed to get_footer() on classic themes.
 */
function wpcf_get_footer( $name = null ) {
	if ( empty( $GLOBALS['wpcf_using_block_theme_canvas'] ) && wpcf_theme_has_php_template( 'footer.php' ) ) {
		get_footer( $name );
		return;
	}

	do_action( 'get_footer', $name, array() );
	echo isset( $GLOBALS['wpcf_block_footer_html'] ) ? $GLOBALS['wpcf_block_footer_html'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>
<?php wp_footer(); ?>
</body>
</html>
	<?php
}

function wpneo_crowdfunding_get_author_name(){
    return wpcf_function()->get_author_name();
}

function author_name_by_login($author_login){
    return wpcf_function()->author_name_by_login($author_login);
}

function get_wpcf_author_campaigns_url($author_id = 0, $author_nicename = '') {
    wpcf_function()->campaign_url( $author_id, $author_nicename );
}

function wpneo_crowdfunding_get_campaigns_location(){
    return wpcf_function()->campaign_location();
}

function wpneo_crowdfunding_get_total_fund_raised_by_campaign($campaign_id = 0){
    return wpcf_function()->fund_raised($campaign_id);
}

function wpneo_crowdfunding_get_total_goal_by_campaign($campaign_id){
    return wpcf_function()->total_goal($campaign_id);
}

function wpneo_crowdfunding_price($price, $args = array()){
    return wpcf_function()->price( $price, $args = array() );
}

function wpneo_loved_campaign_count($user_id = 0){
    return wpcf_function()->loved_count($user_id);
}
function is_campaign_loved_html($user_id = 0){
    return wpcf_function()->campaign_loved($user_id);
}

function wpneo_crowdfunding_wc_login_form(){
    return wpcf_function()->login_form();
}

function wpneo_crowdfunding_author_all_campaigns($author_id = 0){
    return wpcf_function()->author_campaigns( $author_id );
}

function wpneo_crowdfunding_add_http($url){
    return wpcf_function()->url($url);
}

function wpneo_crowdfunding_embeded_video($url){
    return wpcf_function()->get_embeded_video( $url );
}

function wpneo_crowdfunding_campaign_listing_by_author_url($user_login){
    return wpcf_function()->get_author_url( $user_login );
}

function wpneo_crowdfunding_load_template($template = '404'){
    return wpcf_function()->template($template);
}

function wpneo_crowdfunding_pagination($page_numb, $max_page) {
    return wpcf_function()->get_pagination($page_numb, $max_page);
}

function wpneo_wc_version_check($version = '3.0') {
    return wpcf_function()->wc_version($version = '3.0');
}

function wpneo_crowdfunding_campaign_single_love_this() {
    return wpcf_function()->campaign_single_love_this();
}

function WPNEOCF() {
    return wpcf_function();
}
