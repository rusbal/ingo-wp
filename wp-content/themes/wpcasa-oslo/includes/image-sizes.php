<?php
/**
 * Custom image sizes
 *
 * @package WPCasa Oslo
 */

/**
 * wpsight_oslo_add_image_sizes()
 *
 * Add theme image sizes.
 *
 * @uses wpsight_oslo_image_sizes()
 * @uses set_post_thumbnail_size()
 * @uses add_image_size()
 *
 * @since 1.0.0
 */
add_action( 'after_setup_theme', 'wpsight_oslo_image_sizes_add' );
 
function wpsight_oslo_image_sizes_add() {

	foreach( wpsight_oslo_image_sizes() as $image_size => $v ) {

		if( $image_size == 'post-thumbnail' ) {		
			set_post_thumbnail_size( $v['size']['w'], $v['size']['h'], $v['crop'] );		
		} else {		
			add_image_size( $image_size, $v['size']['w'], $v['size']['h'], $v['crop'] );		
		}

	}

}

/**
 * wpsight_oslo_image_sizes()
 *
 * Define set of custom image sizes.
 *
 * @return array $image_sizes Array of custom image sizes
 *
 * @since 1.0.0
 */
function wpsight_oslo_image_sizes() {
	
	$image_sizes = array(

		'post-thumbnail' => array(
			'size' => array(
				'w' => 340,
				'h' => 255
			),
			'crop'  => true,
			'label' => __( 'small', 'wpcasa-oslo' )
		),
		'wpsight-half' => array(
			'size' => array(
				'w' => 540,
				'h' => 405
			),
			'crop'  => true,
			'label' => __( 'half', 'wpcasa-oslo' )
		),
		'wpsight-large' => array(
			'size' => array(
				'w' => 740,
				'h' => 370
			),
			'crop'  => true,
			'label' => __( 'large', 'wpcasa-oslo' )
		),
		'wpsight-full' => array(
			'size' => array(
				'w' => 1140,
				'h' => 600
			),
			'crop'  => true,
			'label' => __( 'full', 'wpcasa-oslo' )
		),
		'wpsight-slider' => array(
			'size' => array(
				'w' => 2000,
				'h' => 850
			),
			'crop'  => true,
			'label' => __( 'slider', 'wpcasa-oslo' )
		)

	);	
		
	return apply_filters( 'wpsight_oslo_image_sizes', $image_sizes );
	
}

/**
 * wpsight_oslo_get_image()
 *
 * Get dimensions of a specific image size.
 *
 * @uses wpsight_oslo_get_image()
 * @return array|bool Array of dimensions or false if size does not exist
 *
 * @since 1.0
 */

function wpsight_oslo_get_image( $size = 'large' ) {

	$image_sizes = wpsight_oslo_image_sizes();
	
	if( isset( $image_sizes[ $size ]['size'] ) )
		return $image_sizes[ $size ]['size'];
	
	return false;

}
