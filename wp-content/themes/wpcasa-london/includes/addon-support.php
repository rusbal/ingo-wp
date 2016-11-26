<?php
/**
 *	Add-On Support
 *	
 *	@package WPCasa London
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'wpsight_london_scripts_addons' );

function wpsight_london_scripts_addons() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	// Enqueue dsIDXpress custom scripts and styles if desired
	
	if( apply_filters( 'wpsight_london_dsidxpress', true ) == true && is_plugin_active( 'dsidxpress/dsidxpress.php' ) ) {

		wp_enqueue_style( 'wpsight-dsidxpress', get_template_directory_uri() . '/assets/css/wpsight-dsidxpress.css', false, WPSIGHT_LONDON_VERSION, 'all'  );
		wp_enqueue_script( 'wpsight-dsidxpress', get_template_directory_uri() . '/assets/js/wpsight-dsidxpress.js', array( 'jquery' ), WPSIGHT_LONDON_VERSION, true );

	}

}

/**
 *	Filter Favorites add-on compare button.
 *	
 *	@param	string	$output
 *	@return string
 *	
 *	@since	1.0.0
 */
add_filter( 'wpsight_favorites_listings_panel_compare', 'wpsight_london_favorites_listings_panel_compare' );

function wpsight_london_favorites_listings_panel_compare( $output ) {	
	return str_replace( 'class="listings-compare"', 'class="listings-compare btn btn-default"', $output );	
}
