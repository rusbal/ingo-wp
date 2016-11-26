<?php
/**
 * Feature Box widget
 *
 * @package WPCasa London
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_london_register_widget_feature_box' );
 
function wpsight_london_register_widget_feature_box() {
	register_widget( 'WPSight_London_Feature_Box' );
}

/**
 * Feature box widget class
 */
class WPSight_London_Feature_Box extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_feature_box',
			'description' => _x( 'Display a feature box with icon.', 'listing wigdet', 'wpcasa-london' )
		);

		parent::__construct( 'wpsight_london_feature_box', '&rsaquo; ' . _x( 'Feature Box', 'listing widget', 'wpcasa-london' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'icon_class'	=> 'fa fa-check',
			'description'	=> '',
			'link_text'		=> '',
			'link_url'		=> '',
			'link_blank'	=> false
		);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$instance['link_blank'] = isset( $instance['link_blank'] ) ? (bool) $instance['link_blank'] : false;

		// Echo before_widget
        echo $args['before_widget'];
			
		// Display feature box
		wpsight_london_feature_box( $instance, $args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['icon_class']		= strip_tags( $new_instance['icon_class'] );
	    $instance['description']	= wp_kses_post( $new_instance['description'] );
	    $instance['link_text']		= strip_tags( $new_instance['link_text'] );
	    $instance['link_url']		= strip_tags( $new_instance['link_url'] );
	    $instance['link_blank']		= ! empty( $new_instance['link_blank'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'icon_class'	=> 'fa fa-check',
			'description'	=> '',
			'link_text'		=> '',
			'link_url'		=> '',
			'link_blank'	=> 0
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-london' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'icon_class' ); ?>"><?php _e( 'Icon Class', 'wpcasa-london' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'icon_class' ); ?>" name="<?php echo $this->get_field_name( 'icon_class' ); ?>" type="text" value="<?php echo esc_attr( $instance['icon_class'] ); ?>" /></label><br />
			<span class="description"><?php _e( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-london' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description' ); ?>:
		<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Link Text', 'wpcasa-london' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" type="text" value="<?php echo esc_attr( $instance['link_text'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e( 'Link URL', 'wpcasa-london' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" type="text" value="<?php echo esc_attr( $instance['link_url'] ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'link_blank' ); ?>" name="<?php echo $this->get_field_name( 'link_blank' ); ?>"<?php checked( $instance['link_blank'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'link_blank' ); ?>"><?php _e( 'Open link in new tab or window', 'wpcasa-london' ); ?></label></p><?php

	}

}
