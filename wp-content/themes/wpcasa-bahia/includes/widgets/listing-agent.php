<?php
/**
 * Listing Agent widget
 *
 * @package WPCasa Bahia
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_bahia_register_widget_listing_agent' );
 
function wpsight_bahia_register_widget_listing_agent() {
	register_widget( 'WPSight_Bahia_Listing_Agent' );
}

/**
 * Listing agent widget class
 *
 * @since 1.0.0
 */

class WPSight_Bahia_Listing_Agent extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_agent',
			'description' => _x( 'Display listing agent on single listing pages.', 'listing wigdet', 'wpcasa-bahia' )
		);

		parent::__construct( 'wpsight_bahia_listing_agent', '&rsaquo; ' . WPSIGHT_BAHIA_NAME . ' ' . _x( 'Listing Agent', 'listing widget', 'wpcasa-bahia' ), $widget_ops );

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
		
		wpsight_get_template( 'listing-single-agent.php' );
		
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
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-bahia' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>

		<p>
			<?php _e( 'This widget has no further settings. It displays the listing agent of the current single listing (also see <em>listing-single-agent.php</em> template).', 'wpcasa-bahia' ); ?>
		</p><?php

	}

}