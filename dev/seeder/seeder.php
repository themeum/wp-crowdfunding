<?php
/**
 * Loader for the development test-data seeder.
 *
 * Loaded by wp-crowdfunding.php only while WPCF_ENV is 'development'. The whole
 * dev/ folder is also excluded from the release build (see gulpfile.js), and
 * the build sets WPCF_ENV to 'production'.
 *
 * Set define( 'WPCF_DEV_SEEDER', false ); in wp-config.php to hide the page.
 *
 * @package WPCF\Dev
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_admin() || ( defined( 'WPCF_DEV_SEEDER' ) && ! WPCF_DEV_SEEDER ) ) {
	return;
}

require_once __DIR__ . '/class-seeder.php';
require_once __DIR__ . '/class-admin-page.php';
new \WPCF\Dev\Admin_Page();
