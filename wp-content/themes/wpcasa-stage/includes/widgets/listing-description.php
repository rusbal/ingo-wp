<?php
/**
 * Listing Description widget
 *
 * @package WPCasa STAGE
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_stage_register_widget_listing_description' );
 
function wpsight_stage_register_widget_listing_description() {
	register_widget( 'WPSight_STAGE_Listing_Description' );
}

/**
 * Listing title widget class
 *
 * @since 1.0.0
 */

class WPSight_STAGE_Listing_Description extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_description',
			'description' => _x( 'Display listing description on single listing pages.', 'listing wigdet', 'wpsight-stage' )
		);

		parent::__construct( 'wpsight_stage_listing_description', '&rsaquo; ' . WPSIGHT_STAGE_NAME . ' ' . _x( 'Listing Description', 'listing widget', 'wpsight-stage' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing, $widget_args, $widget_instance;
	
		$listing 	 		= wpsight_get_listing( get_the_id() );
		$widget_args 		= $args;
		$widget_instance 	= $instance;
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
		
		wpsight_get_template( 'listing-single-description.php' );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;

    }

	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title']; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>

		<p>
			<?php _e( 'This widget has no further settings. It automatically displays the listing description of the current single listing.', 'wpsight-stage' ); ?>
		</p><?php

	}

}