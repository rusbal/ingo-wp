<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Favorites_Shortcode class
 */
class WPSight_Favorites_Shortcode {

	/**
	 *	Constructor
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_favorites', array( $this, 'shortcode_favorites' ) );
	}
	
	/**
	 *	shortcode_favorites()
	 *	
	 *	Show the listings search form.
	 *	
	 *	@param	array	$atts	Shortcode attributes
	 *	@uses	shortcode_atts()
	 *	@uses	wpsight_favorites()
	 *	@uses	wp_kses_post()
	 *	@return string	$output	Entire shortcode output
	 *	
	 *	@since 1.0.0
	 */
	public function shortcode_favorites( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'nr'			=> '-1',
            'before'		=> '',
            'after'			=> '',
            'wrap'			=> 'div',
			'show_panel'	=> true, // can be false
			'show_paging'	=> false // can be true
        );
        
        // Merge shortcodes atts with defaults
        $args = shortcode_atts( $defaults, $atts, 'wpsight_favorites' );
        
        // Optionally Convert strings true|false to bool
		
		$args['show_panel'] = $args['show_panel'] === true || $args['show_panel'] == 'true' ? true : false;
		$args['show_paging'] = $args['show_paging'] === true || $args['show_paging'] == 'true' ? true : false;
		
		ob_start();

        wpsight_favorites( $args );
        
        $output = sprintf( '%1$s%3$s%2$s', wp_kses_post( $args['before'] ), wp_kses_post( $args['after'] ), ob_get_clean() );
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $args['wrap'] ) && $args['wrap'] != 'false' )
			$output = sprintf( '<%2$s class="wpsight-favorites-sc">%1$s</%2$s>', $output, tag_escape( $args['wrap'] ) );
		
		return apply_filters( 'wpsight_shortcode_favorites', $output, $atts );

	}

}

new WPSight_Favorites_Shortcode();
