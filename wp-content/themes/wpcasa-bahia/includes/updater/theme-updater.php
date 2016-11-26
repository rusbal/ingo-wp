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
		'item_name'			=> WPSIGHT_BAHIA_NAME, // Name of theme
		'theme_slug'		=> WPSIGHT_BAHIA_DOMAIN, // Theme slug
		'version'			=> WPSIGHT_BAHIA_VERSION, // The current version of this theme
		'author'			=> WPSIGHT_AUTHOR, // The author of this theme
		'download_id'		=> '', // Optional, used for generating a license renewal link
		'renew_url'			=> '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license' 			=> __( 'Theme License', 'wpcasa-bahia' ),
		'enter-key' 				=> __( 'Enter your theme license key.', 'wpcasa-bahia' ),
		'license-key'				=> __( 'License Key', 'wpcasa-bahia' ),
		'license-action'			=> __( 'License Action', 'wpcasa-bahia' ),
		'deactivate-license'		=> __( 'Deactivate License', 'wpcasa-bahia' ),
		'activate-license'			=> __( 'Activate License', 'wpcasa-bahia' ),
		'status-unknown'			=> __( 'License status is unknown.', 'wpcasa-bahia' ),
		'renew'						=> __( 'Renew?', 'wpcasa-bahia' ),
		'unlimited'					=> __( 'unlimited', 'wpcasa-bahia' ),
		'license-key-is-active'		=> __( 'License key is active.', 'wpcasa-bahia' ),
		'expires%s'					=> __( 'Expires %s.', 'wpcasa-bahia' ),
		'%1$s/%2$-sites'			=> __( 'You have %1$s / %2$s sites activated.', 'wpcasa-bahia' ),
		'license-key-expired-%s'	=> __( 'License key expired %s.', 'wpcasa-bahia' ),
		'license-key-expired'		=> __( 'License key has expired.', 'wpcasa-bahia' ),
		'license-keys-do-not-match'	=> __( 'License keys do not match.', 'wpcasa-bahia' ),
		'license-is-inactive'		=> __( 'License is inactive.', 'wpcasa-bahia' ),
		'license-key-is-disabled'	=> __( 'License key is disabled.', 'wpcasa-bahia' ),
		'site-is-inactive'			=> __( 'License key for this site is inactive.', 'wpcasa-bahia' ),
		'license-status-unknown'	=> __( 'License status is unknown.', 'wpcasa-bahia' ),
		'update-notice'				=> __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wpcasa-bahia' ),
		'update-available'			=> __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wpcasa-bahia' )
	)

);