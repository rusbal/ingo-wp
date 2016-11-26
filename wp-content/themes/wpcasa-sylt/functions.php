<?php
/**
 * WPCasa Sylt theme functions and definitions
 *
 * @package WPCasa Sylt
 */

/**
 * Define WPCasa theme constants
 *
 * @since 1.0
 */
add_action( 'after_setup_theme', 'wpsight_sylt_constants' );

function wpsight_sylt_constants() {

	// General theme constants
	
	define( 'WPSIGHT_SYLT_NAME', 'WPCasa Sylt' );
	define( 'WPSIGHT_SYLT_DOMAIN', 'wpcasa-sylt' );
	define( 'WPSIGHT_SYLT_VERSION', '1.0.2' );

	if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
		define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );
	
	if ( ! defined( 'WPSIGHT_AUTHOR' ) )
		define( 'WPSIGHT_AUTHOR', 'WPSight' );

}

/**
 * Start localization
 *
 * @since 1.0
 */ 
add_action( 'after_setup_theme', 'wpsight_sylt_load_textdomain' );

function wpsight_sylt_load_textdomain() {
	load_theme_textdomain( 'wpcasa-sylt', get_template_directory() . '/languages' );
}

/**
 * Handle basic theme setup
 */
require_once get_template_directory() . '/includes/theme-setup.php';

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'wpsight_sylt_scripts' );

function wpsight_sylt_scripts() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	// Skel CSS framework	
	wp_enqueue_style( 'skel-main', get_template_directory_uri() . '/assets/css/main' . $suffix . '.css', false, '3.0.0', 'all' );
	wp_enqueue_style( 'skel-grid', get_template_directory_uri() . '/assets/css/grid' . $suffix . '.css', false, '3.0.0', 'all' );
	
	// Enqueue theme CSS
	wp_enqueue_style( 'wpsight-sylt', get_stylesheet_uri() );
	
	// Enqueue responsive menu JS
	wp_enqueue_script( 'responsive-menu', get_template_directory_uri() . '/assets/js/responsive-menu' . $suffix . '.js', array(), WPSIGHT_SYLT_VERSION, false );
	
	// Enqueue Photoswipe if desired
	
	if( apply_filters( 'wpsight_sylt_photoswipe', true ) == true ) {
		
		wp_enqueue_style( 'photoswipe', get_template_directory_uri() . '/vendor/dimsemenov/PhotoSwipe/photoswipe.css', false, '4.1.0', 'all'  );
		wp_enqueue_script( 'photoswipe', get_template_directory_uri() . '/vendor/dimsemenov/PhotoSwipe/photoswipe.min.js', false, '4.1.0', true );
		wp_enqueue_style( 'photoswipe-ui', get_template_directory_uri() . '/vendor/dimsemenov/PhotoSwipe/default-skin/default-skin.css', false, '4.1.0', 'all'  );
		wp_enqueue_script( 'photoswipe-ui', get_template_directory_uri() . '/vendor/dimsemenov/PhotoSwipe/photoswipe-ui-default.min.js', false, '4.1.0', true );
		
	}
	
	// Enqueue Owl Carousel if desired
	
	if( apply_filters( 'wpsight_sylt_owlcarousel', true ) == true ) {
		
		wp_enqueue_style( 'owlcarousel', get_template_directory_uri() . '/vendor/smashingboxes/owlcarousel/assets/owl.carousel.css', false, '2.0.0-beta', 'all' );		
		wp_enqueue_script( 'owlcarousel', get_template_directory_uri() . '/vendor/smashingboxes/owlcarousel/owl.carousel.min.js', array( 'jquery' ), '2.0.0-beta', true );
		
	}
	
	// Enqueue TipTip if desired
	
	if( apply_filters( 'wpsight_sylt_tiptip', true ) == true ) {		
		wp_enqueue_script( 'tiptip', get_template_directory_uri() . '/assets/js/tiptip' . $suffix . '.js', array( 'jquery', 'jquery-tiptip' ), WPSIGHT_SYLT_VERSION, false );		
	}
	
	// Enqueue Match Height if desired
	
	if( apply_filters( 'wpsight_sylt_equal_height', true ) == true ) {
		wp_enqueue_script( 'jquery-match-height', get_template_directory_uri() . '/assets/js/jquery.matchHeight' . $suffix . '.js', array( 'jquery' ), WPSIGHT_SYLT_VERSION, false );		
		wp_enqueue_script( 'match-height', get_template_directory_uri() . '/assets/js/match-height' . $suffix . '.js', array( 'jquery', 'jquery-match-height' ), WPSIGHT_SYLT_VERSION, false );		
	}
	
	// Enqueue Google Fonts if desired
	
	if( apply_filters( 'wpsight_sylt_google_fonts', true ) == true ) {
		
		$query_args = array(
			'family' => 'Lato:400,300,700',
			'subset' => 'latin'
		);

		wp_enqueue_style( 'google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
	
	}
	
	// Load Dashicons on the front end
	wp_enqueue_style( 'dashicons' );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}

/**
 * Set grid classes for search form fields
 */
add_filter( 'wpsight_get_search_fields', 'wpsight_sylt_get_search_field_widths' );

function wpsight_sylt_get_search_field_widths( $fields ) {
	
	// Get listing details
	$details = wpsight_details();
	
	$details_1 = $details['details_1']['id'];
	$details_2 = $details['details_2']['id'];
	
	$fields['keyword']['class'] = '9u 12u$(medium)';
	$fields['submit']['class'] = '3u 12u$(medium)';
	
	$fields['offer']['class'] = '2u 12u$(medium)';
	$fields['location']['class'] = '3u 12u$(medium)';
	$fields['listing-type']['class'] = '3u 12u$(medium)';
	$fields[ $details_1 ]['class'] = '2u 6u(medium)';	
	$fields[ $details_2 ]['class'] = '2u 6u$(medium)';
		
	return $fields;
	
}

/**
 * Set grid classes for search form advanced fields
 */
add_filter( 'wpsight_get_advanced_search_fields', 'wpsight_sylt_get_advanced_search_fields_widths' );

function wpsight_sylt_get_advanced_search_fields_widths( $fields ) {
	
	$fields['min']['class'] = '3u 6u(medium)';
	$fields['max']['class'] = '3u 6u$(medium)';
	$fields['orderby']['class'] = '3u 6u(medium)';
	$fields['order']['class'] = '3u 6u$(medium)';
		
	return $fields;
	
}

/**
 * Setup custzomizer options
 */
require get_template_directory() . '/includes/theme-customizer.php';

/**
 * Setup custom image sizes.
 */
require get_template_directory() . '/includes/image-sizes.php';

/**
 * Implement custom widget areas.
 */
require get_template_directory() . '/includes/widget-areas.php';

/**
 * Implement custom widgets.
 */
require get_template_directory() . '/includes/widgets.php';

/**
 * Implement custom meta boxes.
 */
require get_template_directory() . '/includes/meta-boxes.php';

/**
 * Setup custom image gallery.
 */
require get_template_directory() . '/includes/image-gallery.php';

/**
 * Setup custom image slider.
 */
require get_template_directory() . '/includes/image-slider.php';

/**
 * Setup custom listings carousel.
 */
require get_template_directory() . '/includes/listings-carousel.php';

/**
 * Setup custom listings slider.
 */
require get_template_directory() . '/includes/listings-slider.php';

/**
 * Implement custom menus.
 */
require get_template_directory() . '/includes/custom-menus.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Implement custom shortcodes.
 */
require get_template_directory() . '/includes/shortcodes.php';

/**
 * Custom add-on functions for this theme.
 */
require get_template_directory() . '/includes/addon-support.php';

/**
 * Load theme updater functions.
 */
add_action( 'after_setup_theme', 'wpsight_sylt_theme_updater' );

function wpsight_sylt_theme_updater() {
	require( get_template_directory() . '/includes/updater/theme-updater.php' );
}
