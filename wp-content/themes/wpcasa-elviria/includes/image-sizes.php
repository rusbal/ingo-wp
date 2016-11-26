<?php
/**
 * WPCasa Elviria custom image sizes
 *
 * @package WPCasa Elviria
 */

/**
 * wpsight_elviria_add_image_sizes()
 *
 * Add theme image sizes.
 *
 * @uses wpsight_elviria_image_sizes()
 * @uses set_post_thumbnail_size()
 * @uses add_image_size()
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'wpsight_elviria_add_image_sizes' );
 
function wpsight_elviria_add_image_sizes() {

	foreach( wpsight_elviria_image_sizes() as $image_size => $v ) {

		if( $image_size == 'post-thumbnail' ) {		
			set_post_thumbnail_size( $v['size']['w'], $v['size']['h'], $v['crop'] );		
		} else {		
			add_image_size( $image_size, $v['size']['w'], $v['size']['h'], $v['crop'] );		
		}

	}

}

/**
 * wpsight_elviria_image_sizes()
 *
 * Define set of custom image sizes.
 *
 * @return array $image_sizes Array of custom image sizes
 *
 * @since 1.0.0
 */

function wpsight_elviria_image_sizes() {
	
	$image_sizes = array(

		'post-thumbnail' => array(
			'size' => array(
				'w' => 400,
				'h' => 250
			),
			'crop'  => true,
			'label' => __( 'small', 'wpcasa-elviria' )
		),
		'wpsight-half' => array(
			'size' => array(
				'w' => 800,
				'h' => 500
			),
			'crop'  => true,
			'label' => __( 'half', 'wpcasa-elviria' )
		),
		'wpsight-large' => array(
			'size' => array(
				'w' => 1000,
				'h' => 625
			),
			'crop'  => true,
			'label' => __( 'large', 'wpcasa-elviria' )
		),
		'wpsight-full' => array(
			'size' => array(
				'w' => 1280,
				'h' => 800
			),
			'crop'  => true,
			'label' => __( 'full', 'wpcasa-elviria' )
		),
		'wpsight-slider' => array(
			'size' => array(
				'w' => 2000,
				'h' => 1050
			),
			'crop'  => true,
			'label' => __( 'slider', 'wpcasa-elviria' )
		)

	);	
		
	return apply_filters( 'wpsight_elviria_image_sizes', $image_sizes );
	
}

/**
 * wpsight_elviria_get_image()
 *
 * Get dimensions of a specific image size.
 *
 * @uses wpsight_elviria_get_image()
 * @return array|bool Array of dimensions or false if size does not exist
 *
 * @since 1.0
 */

function wpsight_elviria_get_image( $size = 'large' ) {

	$image_sizes = wpsight_elviria_image_sizes();
	
	if( isset( $image_sizes[ $size ]['size'] ) )
		return $image_sizes[ $size ]['size'];
	
	return false;

}
