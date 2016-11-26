<?php
/**
 * Basic theme setup
 *
 * @package WPCasa STAGE
 */

/**
 * wpsight_stage_setup()
 *
 * Let this theme support translations, post thumbnails
 * and some other features.
 *
 * @uses load_theme_textdomain()
 * @uses add_theme_support()
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'wpsight_stage_setup' );

if ( ! function_exists( 'wpsight_stage_setup' ) ) {

	function wpsight_stage_setup() {
	
		// Make theme available for translation
		load_theme_textdomain( 'wpsight-stage', get_template_directory() . '/languages' );
	
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
	
		// Let WordPress provide the title
		add_theme_support( 'title-tag' );
	
		// Enable support for post thumbnails
		add_theme_support( 'post-thumbnails' );
	
		// Switch core markup for search form, comment form, and comments to valid HTML5
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	
		// Enable support for post formats
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
		
		// Remove core plugin CSS
		add_filter( 'wpsight_css', '__return_false' );
	
	} // end wpsight_stage_setup()

} // end ! function_exists()

/**
 * wpsight_stage_content_width()
 *
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'wpsight_stage_content_width', 0 );

function wpsight_stage_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpsight_stage_content_width', 1280 );
}
