<?php
/*
Plugin Name: WPCasa Expire Listings
Plugin URI: https://wpcasa.com/downloads/wpcasa-expire-listings
Description: Expire listings manually or automatically after a number of days that can be defined in the add-on settings.
Version: 1.0.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.3.1
Text Domain: wpcasa-expire-listings
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Expire_Listings class
 */
class WPSight_Expire_Listings {

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

		define( 'WPSIGHT_EXPIRE_LISTINGS_NAME', 'WPCasa Expire Listings' );
		define( 'WPSIGHT_EXPIRE_LISTINGS_DOMAIN', 'wpcasa-expire-listings' );
		define( 'WPSIGHT_EXPIRE_LISTINGS_VERSION', '1.0.0' );
		define( 'WPSIGHT_EXPIRE_LISTINGS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_EXPIRE_LISTINGS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Include functions
		include( 'wpcasa-expire-listings-functions.php' );
		
		// Include admin part
		
		if ( is_admin() ) {
			include( WPSIGHT_EXPIRE_LISTINGS_PLUGIN_DIR . '/includes/admin/class-wpsight-expire-listings-admin.php' );
			$this->admin = new WPSight_Expire_Listings_Admin();
		}

		// Actions
		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		// Expire listings if expiry date has been reached
        add_action( 'wpsight_check_expired_listings', array( $this, 'check_expired_listings' ) );
		
		// Set expiry on different post status transitions
		
		add_action( 'pending_to_publish', array( $this, 'set_listing_expiry' ) );
		add_action( 'preview_to_publish', array( $this, 'set_listing_expiry' ) );
		add_action( 'draft_to_publish', array( $this, 'set_listing_expiry' ) );
		add_action( 'auto-draft_to_publish', array( $this, 'set_listing_expiry' ) );
		add_action( 'expired_to_publish', array( $this, 'set_listing_expiry' ) );
		
		// Filters
		
		add_filter( 'wpsight_statuses', array( $this, 'listing_status_expired' ) );
		add_filter( 'wpsight_get_listing_duration', array( $this, 'listing_duration' ), 10, 2 );
		
		add_filter( 'wpsight_dashboard_columns', array( $this, 'dashboard_columns' ) );
		add_action( 'manage_listing_posts_custom_column', array( $this, 'custom_columns' ), 2 );
		add_filter( 'manage_edit-listing_sortable_columns', array( $this, 'sortable_columns' ) );
		add_filter( 'request', array( $this, 'sort_columns' ) );

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
		
		if ( ! isset( $wpsight->expire_listings ) )
			$wpsight->expire_listings = new self();

		do_action_ref_array( 'wpsight_init_expire_listings', array( &$wpsight ) );

		return $wpsight->expire_listings;
	}
	
	/**
	 *	dashboard_columns()
	 *	
	 *	Add expired column to dashboard
	 *	
	 *	@param	array	$columns
	 *	
	 *	@since 1.0.0
	 */
	public function dashboard_columns( $columns ) {
		
		$columns['expire'] = __( 'Expires', 'wpsight-expire-listings' );
		
		return $columns;
		
	}

	/**
	 *	custom_columns()
	 *	
	 *	Define custom columns for
	 *	manage_listing_posts_custom_column action.
	 *	
	 *	@param	array	$column
	 *	@uses	get_option()
	 *	@uses	wpsight_get_option()
	 *	@uses	date_i18n()
	 *	@uses	current_time()
	 *	@uses	human_time_diff()
	 *	
	 *	@since 1.0.0
	 */
	public function custom_columns( $column ) {
		global $post;
		
		$datef = wpsight_get_option( 'date_format', get_option( 'date_format' ) );

		switch ( $column ) {

			case "listing_expires" :
			
				// Display listing expiry date
			
				if ( $post->_listing_expires ) {
					echo '<span class="listing-expires">' . date_i18n( $datef, $post->_listing_expires ) . '</span>';
					
					if( $post->post_status == 'publish' )
						echo '<span class="listing-expires-left">' . sprintf( __( '%s left', 'wpcasa-expire-listings' ), human_time_diff( current_time( 'timestamp' ), $post->_listing_expires ) ) . '</span>';
					
				} else {
					echo '&ndash;';
				}
				
			break;

		}
		
	}

	/**
	 *	sortable_columns()
	 *	
	 *	Let users sort by listing expiry in admin.
	 *	
	 *	@param	array	$columns
	 *	@uses	wp_parse_args()
	 *	
	 *	@since 1.0.0
	 */
	public function sortable_columns( $columns ) {

		$custom = array(
			'listing_expires' => 'listing_expires'
		);

		return wp_parse_args( $custom, $columns );

	}

	/**
	 * sort_columns()
	 * 
	 * Make sortable colums sort.
	 * 
	 * @param	array	$vars
	 * 
	 * @since 1.0.0
	 */
	public function sort_columns( $vars ) {

		if ( isset( $vars['orderby'] ) ) {
			
			if ( 'listing_expires' === $vars['orderby'] ) {
				
				$vars = array_merge( $vars, array(
					'meta_key' 	=> '_listing_expires',
					'orderby' 	=> 'meta_value'
				) );

			}

		}

		return $vars;

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
		load_plugin_textdomain( 'wpcasa-expire-listings', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 *	check_expired_listings()
	 *	
	 *	Update listings status when expiry date
	 *	has been reached and move old expired
	 *	listings to trash when actived.
	 *	
	 *	@uses	wpsight_set_expired_listings()
	 *	@uses	wpsight_delete_expired_listings()
	 *	
	 *	@see	wpsight-expire-listings-functions.php
	 *	
	 *	@since 1.0.0
	 */
	public function check_expired_listings() {
		
		// Update expired listings status		
		if ( apply_filters( 'wpsight_set_expired_listings', true ) )
			wpsight_set_expired_listings();
		
		// Delete old expired listings if desired
		if ( apply_filters( 'wpsight_delete_expired_listings', true ) )
			wpsight_delete_expired_listings();
		
	}
	
	/**
	 *	set_listing_expiry()
	 *	
	 *	Set listing expiry when post status changes to publish.
	 *	
	 *	@uses	wpsight_set_listing_expiry()
	 *	@see	wpsight-expire-listings-functions.php
	 *	
	 *	@since 1.0.0
	 */
	public function set_listing_expiry( $post ) {
		
		// Set listing expiry when post status changes
		if ( apply_filters( 'wpsight_set_listing_expiry', true ) )
			wpsight_set_listing_expiry( $post );
		
	}

	/**
	 *	listing_status_expired()
	 *	
	 *	Add 'expired' listing status to
	 *	general wpsight_statuses().
	 *	
	 *	@param	array	$statuses	Registered statuses
	 *	@return array	$statuses Updated set of statuses
	 * 	@see 	/wpcasa/functions/wpsight-general.php => wpsight_statuses()
	 *	
	 *	@since 1.0.0
	 */
	public function listing_status_expired( $statuses ) {
		
		$statuses['expired'] = array(
			'name'	 					=> _x( 'Expired', 'listing post status', 'wpcasa-expire-listings' ),
			'label' 					=> _x( 'Expired', 'listing post status', 'wpcasa-expire-listings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'wpcasa-expire-listings' )
		);
		
		return $statuses;
		
	}
	
	/**
	 *	listing_duration()
	 *	
	 *	Filter wpsight_get_listing_duration() for listings
	 *	submitted through dashboard to respect dashboard_duration
	 *	option instead of general listing_duraction option when no
	 *	individual duraction is provided through post meta.
	 *	
	 *	@param	integer	$duration
	 *	@param	object	$post
	 *	@uses	wpsight_is_listing_dashboard_listing()
	 *	@uses	get_post_meta()
	 *	@uses	wpsight_get_option()
	 *	@return	integer	$duration
	 *	@see	wpsight-expire-listings-functions.php => wpsight_get_listing_duration()
	 *	
	 *	@since 1.0.0
	 */
	public function listing_duration( $duration, $post ) {
		
		if( function_exists( 'wpsight_is_listing_dashboard_listing' ) ) {
		
			if( wpsight_is_listing_dashboard_listing( $post->ID ) ) {
				
				// Get duration from the listing if set
				$duration = get_post_meta( $post->ID, '_listing_duration', true );
				
				// If no listing duration, use dashboard settings option			
				if ( ! $duration )
					$duration = absint( wpsight_get_option( 'dashboard_duration' ) );
				
			}
		
		}
		
		return $duration;
		
	}
	
	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create some default options to be
	 *	used by this plugin and set a cron job
	 *	to expire listings.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wp_insert_post()
	 *	@uses	wpsight_add_option()
	 *	@uses	wp_clear_scheduled_hook()
	 *	@uses	wp_schedule_event()
	 *	
	 *	@since 1.0.0
	 */
	public static function activation() {
		
		// Add some default options
		
		$options = array(
			'listing_duration' 	 => 30,
			'dashboard_duration' => 30
		);
		
		foreach( $options as $option => $value ) {
			
			if( wpsight_get_option( $option ) )
				continue;
			
			wpsight_add_option( $option, $value );

		}
		
		// Set up cron for expired listings		
		wp_clear_scheduled_hook( 'wpsight_check_expired_listings' );		
		wp_schedule_event( time(), 'hourly', 'wpsight_check_expired_listings' );
		
	}
	
	/**
	 *	deactivation()
	 *	
	 *	Callback for register_deactivation_hook
	 *	to set post status of expired listings
	 *	to 'pending' to not disappear from admin.
	 *	
	 *	@uses	wpsight_get_expired_listings()
	 *	@uses	wp_update_post()
	 *	
	 *	@since 1.0.0
	 */
	public static function deactivation() {
		
		// Get expired listings
		$post_ids = wpsight_get_expired_listings();
		
		if ( $post_ids ) {
			
			// When expired listings, update status to pending
			
			foreach ( $post_ids as $post_id ) {
				
				// Set up post data
		
				$listing_data = array(
					'ID' 		  => $post_id,
					'post_status' => 'pending'
				);
				
				wp_update_post( $listing_data );
		
			}
		
		}
		
	}
	
}

// Activation Hook
register_activation_hook( __FILE__, array( 'WPSight_Expire_Listings', 'activation' ) );

// Deactivation Hook
register_deactivation_hook( __FILE__, array( 'WPSight_Expire_Listings', 'deactivation' ) );

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Expire_Listings', 'init' ) );
