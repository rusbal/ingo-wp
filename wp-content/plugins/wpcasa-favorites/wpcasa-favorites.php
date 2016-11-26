<?php
/*
Plugin Name: WPCasa Favorites
Plugin URI: https://wpcasa.com/downloads/wpcasa-favorites
Description: Let users save their favorite listings and compare them on a single page.
Version: 1.0.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.3.1
Text Domain: wpcasa-favorites
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Favorites class
 */
class WPSight_Favorites {

	/**
	 *	Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );
		
		if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
			define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );

		if ( ! defined( 'WPSIGHT_AUTHOR' ) )
			define( 'WPSIGHT_AUTHOR', 'WPSight' );

		define( 'WPSIGHT_FAVORITES_NAME', 'WPCasa Favorites' );
		define( 'WPSIGHT_FAVORITES_DOMAIN', 'wpcasa-favorites' );
		define( 'WPSIGHT_FAVORITES_VERSION', '1.0.1' );
		define( 'WPSIGHT_FAVORITES_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_FAVORITES_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Cookie constants
	
		define( 'WPSIGHT_FAVORITES_COOKIE', WPSIGHT_DOMAIN . '_favorites' );
		define( 'WPSIGHT_FAVORITES_COOKIE_COMPARE', WPSIGHT_DOMAIN . '_favorites_compare' );
		
		// Include functions
		include( 'wpcasa-favorites-functions.php' );
		
		// Include shortcode
		include( 'includes/class-wpsight-favorites-shortcode.php' );
		
		// Include admin part
		
		if ( is_admin() ) {
			include( WPSIGHT_FAVORITES_PLUGIN_DIR . '/includes/admin/class-wpsight-favorites-admin.php' );
			$this->admin = new WPSight_Favorites_Admin();
		}

		// Actions
		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'maybe_update_cookie' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wpsight_listing_archive_before', array( $this, 'listings_archive' ) );		
		add_action( 'wpsight_listings_panel_actions', array( $this, 'listings_panel_compare' ) );
		
		// Filters
		
		add_filter( 'wpsight_get_listing_actions', array( $this, 'listing_actions' ), 10, 2 );

	}

	/**
	 *	init()
	 *	
	 *	Initialize the plugin when WPCasa is loaded
	 *	
	 *	@param	object	$wpsight
	 *	@return	object	$wpsight->favorites
	 *	
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->favorites ) )
			$wpsight->favorites = new self();

		do_action_ref_array( 'wpsight_init_favorites', array( &$wpsight ) );

		return $wpsight->favorites;
	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-favorites', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 *	maybe_update_cookie()
	 *	
	 *	When cookie exists, check if all favorites
	 *	have post_status 'publish'. If not, remove.
	 *	
	 *	@uses 	get_post_status()
	 *	@uses 	array_search()
	 *	@uses 	setcookie()
	 *	@uses	wpsight_get_option
	 *	
	 *	@since 1.0.0
	 */
	public function maybe_update_cookie() {
		
		// Get listing IDs stored in cookie
		$favorites = ( isset( $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) && ! empty( $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) ) ? explode( ',', $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) : false;
			
		if( is_array( $favorites ) ) {
			
			// Loop through saved favorites
				
			foreach( $favorites as $id ) {
				
				// Check if favorites are published
				// If not, remove them from array
				
				if( 'publish' != get_post_status( $id ) && ( $key = array_search( $id, $favorites ) ) !== false )
					unset( $favorites[ $key ] );

			}
			
			// Update favorites cookie
			setcookie( WPSIGHT_FAVORITES_COOKIE, implode( ',', $favorites ), time() + DAY_IN_SECONDS * absint( wpsight_get_option( 'favorites_expire' ) ), COOKIEPATH );
			
		}
		
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue JS scripts and CSS styles.
	 *	Also localize some JS to use PHP constants.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_template_part()
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_localize_script()
	 *	
	 *	@since 1.0.0
	 */
	public function frontend_scripts() {
		
		// Only on front end
		
		if( is_admin() )
			return;
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		// Enqueue CSS styles
		wp_enqueue_style( 'wpsight-favorites', WPSIGHT_FAVORITES_PLUGIN_URL . '/assets/css/wpsight-favorites' . $suffix . '.css' );
		
		// Enqueue scripts
		wp_enqueue_script( 'wpsight-favorites', WPSIGHT_FAVORITES_PLUGIN_URL . '/assets/js/wpsight-favorites' . $suffix . '.js', array( 'jquery' ), WPSIGHT_FAVORITES_VERSION, true );
		
		// Get template part for script
		
		ob_start();		
		wpsight_get_template_part( 'listings', 'no', null, WPSIGHT_FAVORITES_PLUGIN_DIR . '/templates/' );		
		$listings_no = ob_get_clean();
		
		// Get badge format for script
		
		$badge = wpsight_get_option( 'favorites_badge' );		
		$badge = explode( '%number%', $badge );
		
		$badge_before = is_array( $badge ) ? $badge[0] : '';
		$badge_after = is_array( $badge ) ? $badge[1] : '';
		
		// Localize scripts
		
		$data = array(
			'favorites_cookie_path'	=> COOKIEPATH,
			'favorites_cookie'   	=> WPSIGHT_FAVORITES_COOKIE,
			'favorites_expire'	   	=> absint( wpsight_get_option( 'favorites_expire' ) ),
			'favorites_no'			=> $listings_no,
			'favorites_badge_before'=> wp_kses_post( $badge_before ),
			'favorites_badge_after'	=> wp_kses_post( $badge_after ),
			'favorites_page'		=> wpsight_get_option( 'favorites_page' ),
			'compare_cookie'		=> WPSIGHT_FAVORITES_COOKIE_COMPARE
		);
		
		wp_localize_script( 'wpsight-favorites', 'WPSight_Favorites', $data );

	}
	
	/**
	 *	listing_actions()
	 *	
	 *	Add favorites listing action calling
	 *	a callback function in wpsight-favorites-functions.php.
	 *	
	 *	@param	array	$actions	Listings actions of wpsight_get_listing_actions filter
	 *	@param	integer	$post_id	Post ID of the current listing
	 *	@return	array	$actions	Original and custom listing actions
	 *	
	 *	@since 1.0.0
	 */
	public function listing_actions( $actions, $post_id ) {
	
		$actions['favorites'] = array(
			'post_id'	=> $post_id,
			'callback'	=> 'wpsight_listing_actions_favorite',
			'priority'	=> 10
		);
		
		return $actions;
	
	}
	
	/**
	 *	listings_archive()
	 *	
	 *	Add remove button to favorite listings.
	 *	
	 *	@param	object	$listing	WP_Post object of corresponding listing
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since 1.0.0
	 */
	public function listings_archive( $listing ) {
		
		$page_id = wpsight_get_option( 'favorites_page' );
	
		if( isset( $page_id ) && is_page( $page_id ) )		
			printf( '<a href="" data-favorite="%3$s" class="favorites-remove" title="%2$s">%1$s</a>', _x( '&times;', 'favorites page remove', 'wpcasa-favorites' ), _x( 'Remove', 'favorites page remove', 'wpcasa-favorites' ), $listing->ID );

	}
	
	/**
	 *	listings_panel_compare()
	 *	
	 *	Add listings panel compare button
	 *	on favorites page.
	 *	
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since 1.0.0
	 */
	public function listings_panel_compare() {
		
		$page_id = wpsight_get_option( 'favorites_page' );
	
		if( isset( $page_id ) && is_page( $page_id ) )		
			printf( '<div class="listings-panel-action"><a href="" class="listings-compare" title="%1$s">%1$s</a></div>', _x( 'Compare', 'favorites page compare', 'wpcasa-favorites' ) );	
	
	}
	
	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create a default favorites page with
	 *	the [wpsight_favorites] shortcode and
	 *	to create some default options to be
	 *	used by this plugin.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wp_insert_post()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since 1.0.0
	 */
	public static function activation() {
		
		// Create favorites page
		
		$page_data = array(
			'post_title'     => _x( 'Favorites', 'favorites page title', 'wpcasa-favorites' ),
			'post_content'   => '[wpsight_favorites]',
			'post_type'      => 'page',
			'post_status'	 => 'publish',
			'comment_status' => 'closed',
			'ping_status'	 => 'closed'
		);
		
		$page_id = ! wpsight_get_option( 'favorites_page' ) ? wp_insert_post( $page_data ) : false;
		
		// Add some default options
		
		$options = array(
			'favorites_page' 	=> $page_id,
			'favorites_add' 	=> _x( 'Save', 'favorites button labels', 'wpcasa-favorites' ),
			'favorites_see'		=> _x( 'Favorites', 'favorites button labels', 'wpcasa-favorites' ),
			'favorites_expire' 	=> 30,
			'favorites_badge'	=> ' <span class="badge">%number%</span>',
		);
		
		foreach( $options as $option => $value ) {
			
			if( wpsight_get_option( $option ) )
				continue;
			
			wpsight_add_option( $option, $value );

		}
		
	}
	
}

// Activation Hook
register_activation_hook( __FILE__, array( 'WPSight_Favorites', 'activation' ) );

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Favorites', 'init' ) );
