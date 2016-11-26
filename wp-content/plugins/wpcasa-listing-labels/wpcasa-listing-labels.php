<?php
/*
Plugin Name: WPCasa Listing Labels
Plugin URI: https://wpcasa.com/downloads/wpcasa-listing-labels
Description: Feature specific listings by visually highlighting them with a label.
Version: 1.0.1
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.4
Text Domain: wpcasa-listing-labels
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listing_Labels class
 */
class WPSight_Listing_Labels {

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

		define( 'WPSIGHT_LISTING_LABELS_NAME', 'WPCasa Listing Labels' );
		define( 'WPSIGHT_LISTING_LABELS_DOMAIN', 'wpcasa-listing-labels' );
		define( 'WPSIGHT_LISTING_LABELS_VERSION', '1.0.1' );
		define( 'WPSIGHT_LISTING_LABELS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_LISTING_LABELS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Include admin part
		
		if ( is_admin() ) {
			include( WPSIGHT_LISTING_LABELS_PLUGIN_DIR . '/includes/admin/class-wpsight-listing-labels-admin.php' );
			$this->admin = new WPSight_Listing_Labels_Admin();
		}

		// Actions
		
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wpsight_listing_archive_description_before', array( $this, 'listing_labels_description_before' ) );
		add_action( 'wpsight_listing_single_description_before', array( $this, 'listing_labels_description_before' ) );
		add_action( 'wpsight_listing_archive_description_after', array( $this, 'listing_labels_description_after' ) );
		add_action( 'wpsight_listing_single_description_after', array( $this, 'listing_labels_description_after' ) );
		
		// Filters
		
		add_filter( 'wpsight_listing_thumbnail_overlay', array( $this, 'listing_labels_thumbnail' ), 10, 2 );
		add_filter( 'the_title', array( $this, 'listing_labels_title_before' ), 10, 2 );
		add_filter( 'the_title', array( $this, 'listing_labels_title_after' ), 10, 2 );

		add_filter( 'wpsight_listing_labels', array( $this, 'check_listing_labels' ), 20 );

	}

	/**
	 *	init()
	 *	
	 *	Initialize the plugin when WPCasa is loaded
	 *	
	 *	@param	object	$wpsight
	 *	@return	object	$wpsight->listing_labels
	 *	
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->listing_labels ) )
			$wpsight->listing_labels = new self();

		do_action_ref_array( 'wpsight_init_listing_labels', array( &$wpsight ) );

		return $wpsight->listing_labels;

	}
	
	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue JS scripts and CSS styles.
	 *	
	 *	@uses	wp_enqueue_style()
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
		wp_enqueue_style( 'wpsight-listing-labels', WPSIGHT_LISTING_LABELS_PLUGIN_URL . '/assets/css/wpsight-listing-labels' . $suffix . '.css' );

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
		load_plugin_textdomain( 'wpcasa-listing-labels', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 *	label_format()
	 *	
	 *	Get label format from options
	 *	and replace placeholder with label text.
	 *	
	 *	@param	string	$label_key		Key of the corresponding label
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_label()
	 *	@return string	$label_badge	Output of the listing label
	 *	
	 *	@since 1.0.0
	 */
	public function listing_label( $label_key ) {
	
		// Get label format
		$labels_format = wpsight_get_option( 'labels_format' );
		
		// Replace placeholders

		$label = str_replace( '%label_text%' , wpsight_get_label( $label_key ), $labels_format );
		$label = str_replace( '%label_class%' , wpsight_get_label( $label_key, 'css_class' ), $label );
		
		return apply_filters( 'wpsight_listing_labels_format', wp_kses_post( $label ), $labels_format, $label_key );
	
	}
	
	/**
	 *	listing_labels_thumbnail()
	 *	
	 *	Display listing label in listing thumbnail overlay.
	 *	This function is fired by the wpsight_listing_thumbnail_overlay
	 *	filter hook.
	 *	
	 *	@param	mixed	$overlay	Incoming overlay output
	 *	@param	integer	$post_id	Post ID of the current listing
	 *	@uses	wpsight_has_listing_label()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_listing_label()
	 *	@uses	$this->listing_label()
	 *	@return mixed	$overlay	Filtered overlay output
	 *	
	 *	@since 1.0.0
	 */
	public function listing_labels_thumbnail( $overlay, $post_id ) {
		
		$label = wpsight_get_option( wpsight_get_listing_label( $post_id ) );

		if( wpsight_has_listing_label( $post_id ) && wpsight_get_option( 'labels_type' ) == 'thumbnail' && ! is_admin() && ! empty( $label ) )
			$overlay .= $this->listing_label( wpsight_get_listing_label( $post_id ) );
		
		return $overlay;
	
	}
	
	/**
	 *	listing_labels_title_before()
	 *	
	 *	Display listing label before listing title.
	 *	This function is fired by the the_title filter hook.
	 *	
	 *	@param	string	$title		Incoming title from the filter
	 *	@param	integer	$post_id	Post ID of the current listing
	 *	@uses 	wpsight_has_listing_label()
	 *	@uses 	wpsight_get_option()
	 *	@uses 	wpsight_get_listing_label()
	 *	@uses 	$this->listing_label()
	 *	@return string	$title		Filtered title
	 *	
	 *	@since 1.0.0
	 */
	public function listing_labels_title_before( $title, $post_id ) {
		
		$label = wpsight_get_option( wpsight_get_listing_label( $post_id ) );

		if( wpsight_has_listing_label( $post_id ) && wpsight_get_option( 'labels_type' ) == 'title_before' && ! is_admin() && ! empty( $label ) )
			$title = '<div class="wpsight-label-title-before">' . $this->listing_label( wpsight_get_listing_label( $post_id ) ) . '</div>' . $title;
		
		return $title;
	
	}
	
	/**
	 *	listing_labels_title_after()
	 *	
	 *	Display listing label after listing title.
	 *	This function is fired by the the_title filter hook.
	 *	
	 *	@param	string	$title		Incoming title from the filter
	 *	@param	integer	$post_id	Post ID of the current listing
	 *	@uses	wpsight_has_listing_label()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_listing_label()
	 *	@uses	$this->listing_label()
	 *	@return string	$title		Filtered title
	 *	
	 *	@since 1.0.0
	 */
	public function listing_labels_title_after( $title, $post_id ) {
		
		$label = wpsight_get_option( wpsight_get_listing_label( $post_id ) );

		if( wpsight_has_listing_label( $post_id ) && wpsight_get_option( 'labels_type' ) == 'title_after' && ! is_admin() && ! empty( $label ) )
			$title = $title . '<div class="wpsight-label-title-after">' . $this->listing_label( wpsight_get_listing_label( $post_id ) ) . '</div>';
		
		return $title;
	
	}
	
	/**
	 *	listing_labels_description_before()
	 *	
	 *	Display listing label before listing description.
	 *	This function is fired by the following action hooks:
	 *	
	 *	 - wpsight_listing_archive_description_before
	 *	 - wpsight_listing_single_description_before
	 *	
	 *	@param	integer	$post_id	Post ID of the listing to check
	 *	@uses	wpsight_has_listing_label()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_listing_label()
	 *	@uses	$this->listing_label()
	 *	
	 *	@since 1.0.0
	 */
	public function listing_labels_description_before( $post_id ) {
		
		// Use global post ID if not defined
    
		if( ! $post_id )
        	$post_id = get_the_id();
        
        $label = wpsight_get_option( wpsight_get_listing_label( $post_id ) );

		if( wpsight_has_listing_label( $post_id ) && wpsight_get_option( 'labels_type' ) == 'description_before' && ! is_admin() && ! empty( $label ) )
			echo $this->listing_label( wpsight_get_listing_label( $post_id ) );
	
	}
	
	/**
	 *	listing_labels_description_after()
	 *	
	 *	Display listing label after listing description.
	 *	This function is fired by the following action hooks:
	 *	
	 *	 - wpsight_listing_archive_description_after
	 *	 - wpsight_listing_single_description_after
	 *	
	 *	@param	integer	$post_id	Post ID of the listing to check
	 *	@uses	wpsight_has_listing_label()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_listing_label()
	 *	@uses	$this->listing_label()
	 *	
	 *	@since 1.0.0
	 */
	public function listing_labels_description_after( $post_id ) {
		
		// Use global post ID if not defined
    
		if( ! $post_id )
        	$post_id = get_the_id();
        
        $label = wpsight_get_option( wpsight_get_listing_label( $post_id ) );

		if( wpsight_has_listing_label( $post_id ) && wpsight_get_option( 'labels_type' ) == 'description_after' && ! is_admin() && ! empty( $label ) )
			echo $this->listing_label( wpsight_get_listing_label( $post_id ) );
	
	}

	/**
	 *	check_listing_labels()
	 *	
	 *	Filter listing labels and update if
	 *	label has been set on options page.
	 *	
	 *	@param	array	$labels	Incoming listing labels
	 *	@uses	wpsight_get_option()
	 *	@return	array	$labels	Updated listing labels
	 *	
	 *	@since 1.0.0
	 */
	
	public function check_listing_labels( $labels ) {
	
		// Just return originals on reset
	
		if( isset( $_POST['reset'] ) )
			return $labels;
			
		// Loop through details and check against database
	
		foreach( $labels as $label => $value )
			$labels[ $label ]['label'] = wpsight_get_option( $label ) === false ? $labels[ $label ]['label'] : wpsight_get_option( $label );
		
		return $labels;
	
	}
	
	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create some default options to be
	 *	used by this plugin.
	 *	
	 *	@uses	wpsight_listing_labels()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since 1.0.0
	 */	
	public static function activation() {
		
		// Add some default options
		
		$options = array(
			'labels_format' => '<div class="wpsight-label %label_class%"><span>%label_text%</span></div>',
			'labels_type'  	=> 'thumbnail'
		);
		
		foreach( wpsight_listing_labels() as $key => $label )
			$options[$key] = $label['label'];
		
		foreach( $options as $option => $value ) {
			
			if( wpsight_get_option( $option ) )
				continue;
			
			wpsight_add_option( $option, $value );

		}
		
	}
	
}

// Include functions
include( 'wpcasa-listing-labels-functions.php' );

// Activation Hook
register_activation_hook( __FILE__, array( 'WPSight_Listing_Labels', 'activation' ) );

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Listing_Labels', 'init' ) );
