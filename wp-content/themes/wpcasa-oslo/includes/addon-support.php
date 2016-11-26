<?php
/**
 *	Add-On Support
 *	
 *	@package WPCasa Oslo
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'wpsight_oslo_scripts_addons' );

function wpsight_oslo_scripts_addons() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	// Enqueue dsIDXpress custom scripts and styles if desired
	
	if( apply_filters( 'wpsight_oslo_dsidxpress', true ) == true && is_plugin_active( 'dsidxpress/dsidxpress.php' ) ) {

		wp_enqueue_style( 'wpsight-dsidxpress', get_template_directory_uri() . '/assets/css/wpsight-dsidxpress.css', false, WPSIGHT_OSLO_VERSION, 'all'  );
		wp_enqueue_script( 'wpsight-dsidxpress', get_template_directory_uri() . '/assets/js/wpsight-dsidxpress.js', array( 'jquery' ), WPSIGHT_OSLO_VERSION, true );

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
add_filter( 'wpsight_favorites_listings_panel_compare', 'wpsight_oslo_favorites_listings_panel_compare' );

function wpsight_oslo_favorites_listings_panel_compare( $output ) {	
	return str_replace( 'class="listings-compare"', 'class="listings-compare btn btn-default"', $output );	
}

/**
 *	Add listings map before site main
 *	
 *	@param	string	$context
 *	@param	object	$query
 *	
 *	@since	1.0.0
 */
add_action( 'wpsight_oslo_site_main_before', 'wpsight_oslo_site_main_before_map', 10, 2 );

function wpsight_oslo_site_main_before_map( $context, $query ) {
	
	// Check if we are on general listings page
	
	$listings_page = false;
	
	if( get_the_id() == wpsight_get_option( 'listings_page' ) )
		$listings_page = true;
	
	// Check if we are on favorite listings page
	
	$favorites_page = false;
	
	if( function_exists( 'wpsight_get_favorites' ) ) {
		
		if( get_the_id() == wpsight_get_option( 'favorites_page' ) ) {			
			$favorites_page = true;
			$query = wpsight_get_favorites();
		}
	
	}
	
	// Check if we need to display map	
	if( ! wpsight_is_listings_archive() && 'listings-query' != $context && ! $listings_page && ! $favorites_page )
		return;
	
	if( isset( $query->post_count ) && $query->post_count >= 1 && wpsight_get_option( 'listings_map_main_before' ) ) {
		
		$args = array(
			'map_id'		=> 'main-before',
			'toggle'		=> true,
			'toggle_button'	=> false,
		);
		
		echo '<div id="listings-map-main-before">' . wpsight_get_listings_map( $args, $query->query_vars ) . '</div>';
		
		wp_reset_query();
	
	}
	
}

/**
 *	Add listings map toggle link before site main
 *	
 *	@param	string	$context
 *	@param	object	$query
 *	
 *	@since	1.0.0
 */
add_action( 'wpsight_oslo_site_main_before', 'wpsight_oslo_site_main_before_map_toggle', 10, 2 );

function wpsight_oslo_site_main_before_map_toggle( $context, $query ) {
	
	// Check if we are on general listings page
	
	$listings_page = false;
	
	if( get_the_id() == wpsight_get_option( 'listings_page' ) )
		$listings_page = true;
	
	// Check if we are on favorite listings page
	
	$favorites_page = false;
	
	if( function_exists( 'wpsight_get_favorites' ) ) {
		
		if( get_the_id() == wpsight_get_option( 'favorites_page' ) ) {			
			$favorites_page = true;
			$query = wpsight_get_favorites();
		}
	
	}
	
	// Check if we need to display map link
	if( ! wpsight_is_listings_archive() && 'listings-query' != $context && ! $listings_page && ! $favorites_page )
		return;
	
	if( isset( $query->post_count ) && $query->post_count >= 1 && wpsight_get_option( 'listings_map_main_before' ) ) {
		
		printf( '<div class="container"><div id="map-toggle-main-before"><a href="#" class="toggle-map">%1$s</a></div></div>', wpsight_get_option( 'listings_map_main_before_link', true ) );
		
		wp_reset_query();
	
	}
	
}

/**
 *	Add new listings map toggle options
 *	
 *	@param	array	$options
 *	@return	array
 *	
 *	@since	1.0.0
 */
add_filter( 'wpsight_listings_map_options', 'wpsight_listings_map_options_main_before' );

function wpsight_listings_map_options_main_before( $options ) {
	
	// Unset some options
	
	unset( $options['listings_map_panel'] );
	unset( $options['listings_map_panel_link'] );
	
	if( wpsight_get_option( 'listings_map_panel' ) )
		wpsight_delete_option( 'listings_map_panel' );
	
	if( wpsight_get_option( 'listings_map_panel_link' ) )
		wpsight_delete_option( 'listings_map_panel_link' );
	
	// Set new options
	
	$new = array(
		'listings_map_main_before' => array(
			'name'     	=> __( 'Map Toggle', 'wpcasa-listings-map' ),
			'cb_label' 	=> __( 'Show toggle link under header', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Will show a link right under the header (before main content) and the map below when clicked.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_main_before',
			'type'     	=> 'checkbox',
		),
		'listings_map_main_before_link' => array(
			'name'     	=> __( 'Link Text', 'wpcasa-listings-map' ),
			'desc'     	=> __( 'Please enter the text for the toggle link.', 'wpcasa-listings-map' ),
			'id'       	=> 'listings_map_main_before_link',
			'type'     	=> 'text',
			'default'	=> __( 'Toggle Map', 'wpcasa-listings-map' ),
		),
	);
	
	return wpsight_oslo_array_insert_after( $options, 'listings_map_page', $new );
	
}

/**
 *	wpsight_oslo_array_insert_after()
 *
 *	Insert a value or key/value pair after a specific key in an array. If key doesn't exist, value is appended
 *	to the end of the array.
 *	
 *	@param	array	$array
 *	@param	string	$key
 *	@param	array	$new	
 *	@return	array
 *
 *	@see	https://gist.github.com/wpscholar/0deadce1bbfa4adb4e4c
 */
function wpsight_oslo_array_insert_after( array $array, $key, array $new ) {
	$keys = array_keys( $array );
	$index = array_search( $key, $keys );
	$pos = false === $index ? count( $array ) : $index + 1;

	return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

/**
 *	Use different listings map marker icons
 *	for sales and rentals.
 *
 *	@param	array	$icon		Icon object array
 *	@param	integer	$post_id	Post ID
 *	@uses	wpsight_get_listing_offer()
 *	@uses	get_template_directory_uri()
 *	@return	array
 *
 *	@since	1.0.0
 */
add_filter( 'wpsight_listings_map_icon', 'wpsight_oslo_listings_map_icon_sale_rent', 10, 2 );

function wpsight_oslo_listings_map_icon_sale_rent( $icon, $post_id ) {

	$offer = wpsight_get_listing_offer( $post_id, false );

	$icon_url = get_template_directory_uri() . '/assets/images/map-marker-sale.png';

	if( 'rent' == $offer )
		$icon_url = get_template_directory_uri() . '/assets/images/map-marker-rent.png';

	$icon = array(
		'url'        => $icon_url,
		'size'       => array( 54, 100 ),
		'origin'     => array( 0, 0 ),
		'anchor'     => array( 14, 50 ),
		'scaledSize' => array( 27, 50 )
	);

	return $icon;

}

/**
 *	Set smaller cluster grid on listings map.
 *
 *	@param	array	$options
 *	@return	array
 *
 *	@since	1.0.0
 */
// Deactivated due to https://github.com/wpsight/wpcasa-listings-map/issues/6
// add_filter( 'wpsight_listings_map_options', 'wpsight_oslo_listings_map_options_cluster' );

function wpsight_oslo_listings_map_options_cluster( $options ) {
	
	$options['cluster']['gridSize'] = 40;
	
	return $options;
	
}

/**
 *	wpsight_oslo_pdf_css()
 *	
 *	Add print styles to print header
 *	using wpsight_head_pdf action hook.
 *	
 *	@since	1.0.0
 */
add_action( 'wpsight_head_pdf', 'wpsight_oslo_pdf_css' );

function wpsight_oslo_pdf_css() { ?>
<link href="<?php echo get_template_directory_uri(); ?>/assets/css/wpsight-listing-pdf<?php echo SCRIPT_DEBUG ? '' : '.min'; ?>.css" rel="stylesheet" type="text/css">
<?php
}

/**
 * Register some custom strings for Polylang
 */
add_action( 'admin_init', 'wpsight_oslo_polylang_register' );

function wpsight_oslo_polylang_register() {
	
	if( ! function_exists( 'pll_register_string' ) )
		return;
	
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_1_title', __( 'Get in touch', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_1_text_1', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_1_text_2', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );

	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_2_title', __( 'Office hours', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_2_text_1', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_2_text_2', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );

	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_3_title', __( 'Our location', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_3_text_1', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Top', get_theme_mod( 'wpcasa_site_top_icon_box_3_text_2', __( 'Put some text here', 'wpcasa-oslo' ) ), WPSIGHT_OSLO_NAME );
	
	$bottom_left = sprintf( __( 'Copyright &copy; <span itemprop="copyrightYear">%s</span> &middot; <a href="%s" rel="home" itemprop="copyrightHolder">%s</a>', 'wpcasa-oslo' ), date( 'Y' ), esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) );
	
	$bottom_right = __( 'Built on <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>', 'wpcasa-oslo' );
	
	pll_register_string( 'Site Bottom', get_theme_mod( 'wpcasa_site_bottom_left', $bottom_left ), WPSIGHT_OSLO_NAME );
	pll_register_string( 'Site Bottom', get_theme_mod( 'wpcasa_site_bottom_right', $bottom_right ), WPSIGHT_OSLO_NAME );
	
}

/**
 * Filter theme mods to be translated by Polylang
 */
add_filter( 'theme_mod_wpcasa_site_top_icon_box_1_title', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_1_text_1', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_1_text_2', 'wpsight_oslo_mod_translate' );

add_filter( 'theme_mod_wpcasa_site_top_icon_box_2_title', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_2_text_1', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_2_text_2', 'wpsight_oslo_mod_translate' );

add_filter( 'theme_mod_wpcasa_site_top_icon_box_3_title', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_3_text_1', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_top_icon_box_3_text_2', 'wpsight_oslo_mod_translate' );

add_filter( 'theme_mod_wpcasa_site_bottom_left', 'wpsight_oslo_mod_translate' );
add_filter( 'theme_mod_wpcasa_site_bottom_right', 'wpsight_oslo_mod_translate' );

function wpsight_oslo_mod_translate( $mod ) {
	
	if( ! function_exists( 'pll__' ) )
		return $mod;
	
	return pll__( $mod );
	
}
