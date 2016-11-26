<?php
/**
 * Listing Description widget
 *
 * @package WPCasa London
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_london_register_widget_listing_description' );
 
function wpsight_london_register_widget_listing_description() {
	register_widget( 'WPSight_London_Listing_Description' );
}

/**
 * Listing description widget class
 */
class WPSight_London_Listing_Description extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_description',
			'description' => _x( 'Display listing description on single listing pages.', 'listing wigdet', 'wpcasa-london' )
		);

		parent::__construct( 'wpsight_london_listing_description', '&rsaquo; ' . _x( 'Single Listing Description', 'listing widget', 'wpcasa-london' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $listing;
	
		$listing = wpsight_get_listing( get_the_id() );
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		// Echo before_widget
        echo $args['before_widget'];
        
        if( ! is_singular( wpsight_post_type() ) ) {		
			// Display notice when not on single listing page
			wpsight_get_template( 'listing-single-widget-notice.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );		
		} else {        
        	
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			// Display listing description template
			wpsight_get_template( 'listing-single-description.php', array( 'widget_args' => $args, 'widget_instance' => $instance ) );
        	
        }
		
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
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-london' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p><?php

	}

}
