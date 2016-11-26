<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Sylt_Shortcode_Image_Slider {

	/**
	 * __construct()
	 *
	 * @access public
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_image_slider', array( $this, 'shortcode_image_slider' ) );
	}
	
	/**
	 * shortcode_image_slider()
	 *
	 * Image slider shortcode [wpsight_image_slider]
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_search()
	 * @uses wp_kses_allowed_html()
	 *
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
	public function shortcode_image_slider( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'ids'					=> '',
	        'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> false,
			'thumbs_link' 			=> true,
			'slider_items' 			=> 2,
			'slider_slide_by'		=> 2,
			'slider_margin'			=> 20,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_dots'			=> true,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 6000,
			'lightbox_size' 		=> 'wpsight-full',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_share'	   	=> false,
			'lightbox_close'	   	=> true,
			'lightbox_counter'	   	=> true,
			'wrap'					=> 'div'
		);
        
        // Merge shortcodes atts with defaults
        $args = shortcode_atts( $defaults, $atts );
        
		// Thumbnail settings

		$thumbs_ids				= array_map( 'absint', explode( ',', $args['ids'] ) );
		$thumbs_size			= strip_tags( $args['thumbs_size'] );		
		$thumbs_caption 		= $args['thumbs_caption'] == 'true' || $args['thumbs_caption'] === true ? true : false;
		$thumbs_link 			= $args['thumbs_link'] == 'true' || $args['thumbs_link'] === true ? true : false;
		
		// Slider settings
		
		$slider_items 			= absint( $args['slider_items'] );
		$slider_slide_by 		= absint( $args['slider_slide_by'] );
		$slider_margin 			= absint( $args['slider_margin'] );
		$slider_stage_padding 	= absint( $args['slider_stage_padding'] );		
		$slider_loop 			= $args['slider_loop'] == 'true' || $args['slider_loop'] === true ? true : false;
		$slider_nav 			= $args['slider_nav'] == 'true' || $args['slider_nav'] === true ? true : false;
		$slider_dots 			= $args['slider_dots'] == 'true' || $args['slider_dots'] === true ? true : false;
		$slider_autoplay 		= $args['slider_autoplay'] == 'true' || $args['slider_autoplay'] === true ? true : false;
		$slider_autoplay_time 	= absint( $args['slider_autoplay_time'] );
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $args['lightbox_size'] );
		$lightbox_caption 		= $args['lightbox_caption'] == 'true' || $args['lightbox_caption'] === true ? true : false;
		$lightbox_prev_next 	= $args['lightbox_prev_next'] == 'true' || $args['lightbox_prev_next'] === true ? true : false;
		$lightbox_zoom 			= $args['lightbox_zoom'] == 'true' || $args['lightbox_zoom'] === true ? true : false;
		$lightbox_fullscreen 	= $args['lightbox_fullscreen'] == 'true' || $args['lightbox_fullscreen'] === true ? true : false;
		$lightbox_share 		= $args['lightbox_share'] == 'true' || $args['lightbox_share'] === true ? true : false;
		$lightbox_close 		= $args['lightbox_close'] == 'true' || $args['lightbox_close'] === true ? true : false;
		$lightbox_counter 		= $args['lightbox_counter'] == 'true' || $args['lightbox_counter'] === true ? true : false;
		
		// Set up args for gallery

		$slider_args = array(
			'thumbs_size' 	   		=> $thumbs_size,
			'thumbs_caption'    	=> $thumbs_caption,
			'thumbs_link'	   		=> $thumbs_link,			
			'slider_items'			=> $slider_items,
			'slider_slide_by'		=> $slider_slide_by,
			'slider_margin'			=> $slider_margin,
			'slider_stage_padding'	=> $slider_stage_padding,
			'slider_loop'			=> $slider_loop,
			'slider_nav'			=> $slider_nav,
			'slider_dots'			=> $slider_dots,
			'slider_autoplay'		=> $slider_autoplay,
			'slider_autoplay_time'	=> $slider_autoplay_time,			
			'lightbox_size' 		=> $lightbox_size,
			'lightbox_caption' 		=> $lightbox_caption,
			'lightbox_prev_next'   	=> $lightbox_prev_next,
			'lightbox_zoom'		   	=> $lightbox_zoom,
			'lightbox_fullscreen'  	=> $lightbox_fullscreen,
			'lightbox_share'	   	=> $lightbox_share,
			'lightbox_close'	   	=> $lightbox_close,
			'lightbox_counter'	   	=> $lightbox_counter
		);
        
        // Extract args
		extract( $args );
		
		// When no images, don't any produce output		
		if( ! $thumbs_ids )
			return;
		
		ob_start();

        // Echo listing slider
		wpsight_sylt_image_slider( $thumbs_ids, $slider_args );
        
        $output = ob_get_clean();
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-image-slider-sc">%1$s</%2$s>', $output, $wrap );
		
		return apply_filters( 'wpsight_sylt_shortcode_image_slider', $output, $atts );

	}

}

new WPSight_Sylt_Shortcode_Image_Slider();
