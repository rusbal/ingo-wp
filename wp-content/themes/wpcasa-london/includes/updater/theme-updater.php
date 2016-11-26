<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package WPCasa Theme
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url'	=> WPSIGHT_SHOP_URL, // Site where EDD is hosted
		'item_name'			=> WPSIGHT_LONDON_NAME, // Name of theme
		'theme_slug'		=> WPSIGHT_LONDON_DOMAIN, // Theme slug
		'version'			=> WPSIGHT_LONDON_VERSION, // The current version of this theme
		'author'			=> WPSIGHT_AUTHOR, // The author of this theme
		'download_id'		=> '', // Optional, used for generating a license renewal link
		'renew_url'			=> '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license' 			=> __( 'Theme License', 'wpcasa-london' ),
		'enter-key' 				=> __( 'Enter your theme license key.', 'wpcasa-london' ),
		'license-key'				=> __( 'License Key', 'wpcasa-london' ),
		'license-action'			=> __( 'License Action', 'wpcasa-london' ),
		'deactivate-license'		=> __( 'Deactivate License', 'wpcasa-london' ),
		'activate-license'			=> __( 'Activate License', 'wpcasa-london' ),
		'status-unknown'			=> __( 'License status is unknown.', 'wpcasa-london' ),
		'renew'						=> __( 'Renew?', 'wpcasa-london' ),
		'unlimited'					=> __( 'unlimited', 'wpcasa-london' ),
		'license-key-is-active'		=> __( 'License key is active.', 'wpcasa-london' ),
		'expires%s'					=> __( 'Expires %s.', 'wpcasa-london' ),
		'%1$s/%2$-sites'			=> __( 'You have %1$s / %2$s sites activated.', 'wpcasa-london' ),
		'license-key-expired-%s'	=> __( 'License key expired %s.', 'wpcasa-london' ),
		'license-key-expired'		=> __( 'License key has expired.', 'wpcasa-london' ),
		'license-keys-do-not-match'	=> __( 'License keys do not match.', 'wpcasa-london' ),
		'license-is-inactive'		=> __( 'License is inactive.', 'wpcasa-london' ),
		'license-key-is-disabled'	=> __( 'License key is disabled.', 'wpcasa-london' ),
		'site-is-inactive'			=> __( 'License key for this site is inactive.', 'wpcasa-london' ),
		'license-status-unknown'	=> __( 'License status is unknown.', 'wpcasa-london' ),
		'update-notice'				=> __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wpcasa-london' ),
		'update-available'			=> __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wpcasa-london' )
	)

);