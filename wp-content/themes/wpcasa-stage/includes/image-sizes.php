<?php
/**
 * WPCasa STAGE custom image sizes
 *
 * @package WPCasa STAGE
 */

/**
 * wpsight_stage_add_image_sizes()
 *
 * Add theme image sizes.
 *
 * @uses wpsight_stage_image_sizes()
 * @uses set_post_thumbnail_size()
 * @uses add_image_size()
 *
 * @since 1.0.0
 */
 
add_action( 'after_setup_theme', 'wpsight_stage_add_image_sizes' );
 
function wpsight_stage_add_image_sizes() {

	foreach( wpsight_stage_image_sizes() as $image_size => $v ) {

		if( $image_size == 'post-thumbnail' ) {		
			set_post_thumbnail_size( $v['size']['w'], $v['size']['h'], $v['crop'] );		
		} else {		
			add_image_size( $image_size, $v['size']['w'], $v['size']['h'], $v['crop'] );		
		}

	}

}

/**
 * wpsight_stage_image_sizes()
 *
 * Define set of custom image sizes.
 *
 * @return array $image_sizes Array of custom image sizes
 *
 * @since 1.0.0
 */

function wpsight_stage_image_sizes() {
	
	$image_sizes = array(

		'post-thumbnail' => array(
			'size' => array(
				'w' => 400,
				'h' => 300
			),
			'crop'  => true,
			'label' => __( 'small', 'wpsight-stage' )
		),
		'wpsight-half' => array(
			'size' => array(
				'w' => 800,
				'h' => 600
			),
			'crop'  => true,
			'label' => __( 'half', 'wpsight-stage' )
		),
		'wpsight-large' => array(
			'size' => array(
				'w' => 1000,
				'h' => 750
			),
			'crop'  => true,
			'label' => __( 'large', 'wpsight-stage' )
		),
		'wpsight-full' => array(
			'size' => array(
				'w' => 1280,
				'h' => 960
			),
			'crop'  => true,
			'label' => __( 'full', 'wpsight-stage' )
		)

	);	
		
	return apply_filters( 'wpsight_stage_image_sizes', $image_sizes );
	
}

/**
 * wpsight_stage_get_image()
 *
 * Get dimensions of a specific image size.
 *
 * @uses wpsight_stage_get_image()
 * @return array|bool Array of dimensions or false if size does not exist
 *
 * @since 1.0
 */

function wpsight_stage_get_image( $size = 'large' ) {

	$image_sizes = wpsight_stage_image_sizes();
	
	if( isset( $image_sizes[ $size ]['size'] ) )
		return $image_sizes[ $size ]['size'];
	
	return false;

}
