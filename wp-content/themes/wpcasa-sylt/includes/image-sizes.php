<?php
/**
 * WPCasa Sylt custom image sizes
 *
 * @package WPCasa Sylt
 */

/**
 * wpsight_sylt_add_image_sizes()
 *
 * Add theme image sizes.
 *
 * @uses wpsight_sylt_image_sizes()
 * @uses set_post_thumbnail_size()
 * @uses add_image_size()
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'wpsight_sylt_add_image_sizes' );
 
function wpsight_sylt_add_image_sizes() {

	foreach( wpsight_sylt_image_sizes() as $image_size => $v ) {

		if( $image_size == 'post-thumbnail' ) {		
			set_post_thumbnail_size( $v['size']['w'], $v['size']['h'], $v['crop'] );		
		} else {		
			add_image_size( $image_size, $v['size']['w'], $v['size']['h'], $v['crop'] );		
		}

	}

}

/**
 * wpsight_sylt_image_sizes()
 *
 * Define set of custom image sizes.
 *
 * @return array $image_sizes Array of custom image sizes
 *
 * @since 1.0.0
 */

function wpsight_sylt_image_sizes() {
	
	$image_sizes = array(

		'post-thumbnail' => array(
			'size' => array(
				'w' => 400,
				'h' => 250
			),
			'crop'  => true,
			'label' => __( 'small', 'wpcasa-sylt' )
		),
		'wpsight-half' => array(
			'size' => array(
				'w' => 800,
				'h' => 500
			),
			'crop'  => true,
			'label' => __( 'half', 'wpcasa-sylt' )
		),
		'wpsight-large' => array(
			'size' => array(
				'w' => 1000,
				'h' => 625
			),
			'crop'  => true,
			'label' => __( 'large', 'wpcasa-sylt' )
		),
		'wpsight-full' => array(
			'size' => array(
				'w' => 1280,
				'h' => 800
			),
			'crop'  => true,
			'label' => __( 'full', 'wpcasa-sylt' )
		),
		'wpsight-slider' => array(
			'size' => array(
				'w' => 2000,
				'h' => 750
			),
			'crop'  => true,
			'label' => __( 'slider', 'wpcasa-sylt' )
		)

	);	
		
	return apply_filters( 'wpsight_sylt_image_sizes', $image_sizes );
	
}

/**
 * wpsight_sylt_get_image()
 *
 * Get dimensions of a specific image size.
 *
 * @uses wpsight_sylt_get_image()
 * @return array|bool Array of dimensions or false if size does not exist
 *
 * @since 1.0
 */

function wpsight_sylt_get_image( $size = 'large' ) {

	$image_sizes = wpsight_sylt_image_sizes();
	
	if( isset( $image_sizes[ $size ]['size'] ) )
		return $image_sizes[ $size ]['size'];
	
	return false;

}
