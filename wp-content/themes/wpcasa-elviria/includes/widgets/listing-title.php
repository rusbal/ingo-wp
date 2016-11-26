<?php
/**
 * Listing Title widget
 *
 * @package WPCasa Elviria
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_elviria_register_widget_listing_title' );
 
function wpsight_elviria_register_widget_listing_title() {
	register_widget( 'WPSight_Elviria_Listing_Title' );
}

/**
 * Listing title widget class
 *
 * @since 1.0.0
 */

class WPSight_Elviria_Listing_Title extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_title',
			'description' => _x( 'Display title and listing actions on single listing pages.', 'listing wigdet', 'wpcasa-elviria' )
		);

		parent::__construct( 'wpsight_elviria_listing_title', '&rsaquo; ' . WPSIGHT_ELVIRIA_NAME . ' ' . _x( 'Listing Title', 'listing widget', 'wpcasa-elviria' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		// get registered actions
		$actions = wpsight_get_listing_actions();
		
		// Loop through actions and check if activated in widget
		
		foreach( $actions as $action => $v ) {
			
			$instance[ 'action_' . $action ] = isset( $instance[ 'action_' . $action ] ) ? $instance[ 'action_' . $action ] : '';
			
			// If action inactive, hide it (set to false)

			if( empty( $instance[ 'action_' . $action ] ) )
				$actions[ $action ] = false;

		}

		// Echo before_widget
        echo $args['before_widget'];
        
        // Echo listing title and actions
		wpsight_listing_title( get_the_id(), $actions );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    // get registered actions
		$actions = wpsight_get_listing_actions();
		
		foreach( $actions as $action => $v )	    
	    	$instance[ 'action_' . $action ] = ! empty( $new_instance[ 'action_' . $action ] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		// get registered actions
		$actions = wpsight_get_listing_actions();
		
		if( is_array( $actions ) ) { ?>
			<p><?php _e( 'Please select the listing actions that you want to be displayed along the title.', 'wpcasa-elviria' ); ?></p><?php			
		} else { ?>		
			<p><?php _e( 'Currently there are no listing actions to be displayed along the title.', 'wpcasa-elviria' ); ?></p><?php		
		}
		
		// Loop through actions and display checkbox
			
		foreach( $actions as $action => $v ) {
			
			$checked = isset( $instance[ 'action_' . $action ] ) ? (bool) $instance[ 'action_' . $action ] : false; ?>

            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'action_' . $action ); ?>" name="<?php echo $this->get_field_name( 'action_' . $action ); ?>"<?php checked( $checked ); ?> />
                <label for="<?php echo $this->get_field_id( 'action_' . $action ); ?>"><?php echo ucfirst( $action ); ?></label>		
            </p><?php

	    }

	}

}