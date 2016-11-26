<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WPSight_Bahia_Shortcode_Image_Gallery {

	/**
	 * __construct()
	 *
	 * @access public
	 */
	public function __construct() {		
		add_shortcode( 'wpsight_image_gallery', array( $this, 'shortcode_image_gallery' ) );
	}
	
	/**
	 * shortcode_image_gallery()
	 *
	 * Image gallery shortcode [wpsight_image_gallery]
	 *
	 * @param array $atts Shortcode attributes
	 * @uses wpsight_search()
	 * @uses wp_kses_allowed_html()
	 *
	 * @return string $output Entire shortcode output
	 *
	 * @since 1.0.0
	 */
	public function shortcode_image_gallery( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'ids'					=> '',
			'thumbs_columns' 		=> 6, // 12/6 = 2 columns
			'thumbs_columns_small'	=> 12, // 12/12 = 1 column
			'thumbs_gutter'			=> 100, // % of default gutter
			'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> true,
			'thumbs_link' 			=> true,
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
		
        $thumbs_ids				= array_map( 'absint', array_filter( explode( ',', $args['ids'] ) ) );
		$thumbs_columns 		= 12 / absint( $args['thumbs_columns'] );
		$thumbs_columns_small	= 12 / absint( $args['thumbs_columns_small'] );
		$thumbs_gutter 			= absint( $args['thumbs_gutter'] );
		$thumbs_size			= strip_tags( $args['thumbs_size'] );		
		$thumbs_caption 		= $args['thumbs_caption'] == 'true' || $args['thumbs_caption'] === true ? true : false;
		$thumbs_link 			= $args['thumbs_link'] == 'true' || $args['thumbs_link'] === true ? true : false;
		
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

		$gallery_args = array(
			'class_unit' 			=> 'wpsight-gallery-item-u ' . $thumbs_columns . 'u',
			'thumbs_columns' 		=> $thumbs_columns,
			'thumbs_columns_small'	=> $thumbs_columns_small,
			'thumbs_size' 			=> $thumbs_size,
			'thumbs_gutter'			=> $thumbs_gutter,
			'thumbs_caption'		=> $thumbs_caption,
			'thumbs_link'			=> $thumbs_link,
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

        // Echo listing gallery
		wpsight_bahia_image_gallery( $thumbs_ids, $gallery_args );
        
        $output = ob_get_clean();
	
		// Optionally wrap shortcode in HTML tags
		
		if( ! empty( $wrap ) && $wrap != 'false' && in_array( $wrap, array_keys( wp_kses_allowed_html( 'post' ) ) ) )
			$output = sprintf( '<%2$s class="wpsight-image-gallery-sc">%1$s</%2$s>', $output, $wrap );
		
		return apply_filters( 'wpsight_bahia_shortcode_image_gallery', $output, $atts );

	}

}

new WPSight_Bahia_Shortcode_Image_Gallery();
