<?php
/**
 * Listing Image widget
 *
 * @package WPCasa Bahia
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_bahia_register_widget_listing_image' );
 
function wpsight_bahia_register_widget_listing_image() {
	register_widget( 'WPSight_Bahia_Listing_Image' );
}

/**
 * Listing title widget class
 *
 * @since 1.0.0
 */

class WPSight_Bahia_Listing_Image extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_image',
			'description' => _x( 'Display featured image on single listing pages.', 'listing wigdet', 'wpcasa-bahia' )
		);

		parent::__construct( 'wpsight_bahia_listing_image', '&rsaquo; ' . WPSIGHT_BAHIA_NAME . ' ' . _x( 'Listing Image', 'listing widget', 'wpcasa-bahia' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		if( ! has_post_thumbnail( get_the_id() ) )
			return;
		
		// Set image size depending on widget area (full-width or not)
		
		$image_size = 'large';
		
		if( 'listing-top' == $args['id'] || 'listing-bottom' == $args['id'] )
			$image_size = 'full';
		
		if( 'sidebar-listing' == $args['id'] )
			$image_size = 'post-thumbnail';

		// Echo before_widget
        echo $args['before_widget'];
        
        // Echo listing title and actions ?>
        
        <div class="wpsight-listing-image">
			<?php wpsight_listing_thumbnail( get_the_id(), $image_size ); ?>
		</div><?php
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;

        return $instance;

    }

	public function form( $instance ) { ?>

		<p>
			<?php _e( 'This widget has no settings. It automatically displays the featured image of the current single listing.', 'wpcasa-bahia' ); ?>
		</p><?php

	}

}