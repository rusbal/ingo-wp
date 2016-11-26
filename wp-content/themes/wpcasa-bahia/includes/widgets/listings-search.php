<?php
/**
 * Listings Search widget
 *
 * @package WPCasa Bahia
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_bahia_register_widget_listings_search' );
 
function wpsight_bahia_register_widget_listings_search() {
	register_widget( 'WPSight_Bahia_Listings_Search' );
}

/**
 * Listings search widget class
 *
 * @since 1.0.0
 */

class WPSight_Bahia_Listings_Search extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listings_search',
			'description' => _x( 'Display listings search form.', 'listing wigdet', 'wpcasa-bahia' )
		);

		parent::__construct( 'wpsight_bahia_listings_search', '&rsaquo; ' . WPSIGHT_BAHIA_NAME . ' ' . _x( 'Listings Search', 'listing widget', 'wpcasa-bahia' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		global $widget_args, $widget_instance;
	
		$widget_args 		= $args;
		$widget_instance 	= $instance;
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$search_args = isset( $args['id'] ) && ( $args['id'] == 'sidebar-listing-archive' || $args['id'] == 'sidebar' || $args['id'] == 'footer' ) ? array( 'advanced' => '<span class="listings-search-advanced-toggle">' . __( 'Advanced', 'wpcasa-bahia' ) . '</span>', 'reset' => '<span class="listings-search-reset">' . __( 'Reset', 'wpcasa-bahia' ) . '</span>' ) : '';

		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
		
		wpsight_search( $search_args );
		
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
			<?php _e( 'This widget has no further settings. It displays the listings search form.', 'wpcasa-bahia' ); ?>
		</p><?php

	}

}