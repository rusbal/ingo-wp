<?php
/**
 * Listing Terms widget
 *
 * @package WPCasa STAGE
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_stage_register_widget_listing_terms' );
 
function wpsight_stage_register_widget_listing_terms() {
	register_widget( 'WPSight_STAGE_Listing_Terms' );
}

/**
 * Listing terms widget class
 *
 * @since 1.0.0
 */

class WPSight_STAGE_Listing_Terms extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_terms',
			'description' => _x( 'Display listing taxonomy terms on single listing pages.', 'listing wigdet', 'wpsight-stage' )
		);

		parent::__construct( 'wpsight_stage_listing_terms', '&rsaquo; ' . WPSIGHT_STAGE_NAME . ' ' . _x( 'Listing Terms', 'listing widget', 'wpsight-stage' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$defaults = array(
			'title' 		=> '',
			'taxonomy' 		=> 'feature',
			'separator' 	=> '',
			'term_before' 	=> '',
			'term_after' 	=> '',
			'linked'		=> true,
			'blocks'		=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 		 = strip_tags( $instance['title'] );
		$taxonomy 	 = in_array( $instance['taxonomy'], get_object_taxonomies( wpsight_post_type() ) ) ? $instance['taxonomy'] : 'feature';
		$separator	 = strip_tags( $instance['separator'] );
		$term_before = strip_tags( $instance['term_before'] );
		$term_after	 = strip_tags( $instance['term_after'] );
		$linked		 = ! empty( $instance['linked'] ) ? true : false;
		$blocks		 = ! empty( $instance['blocks'] ) ? true : false;
		
		$terms = wpsight_get_listing_terms( $taxonomy, get_the_id() );
		
		if( ! empty( $terms ) ) {

			// Echo before_widget
        	echo $args['before_widget'];
        	
        	if ( $title )
				echo $args['before_title'] . $title . $args['after_title'];
			
			if( $blocks )
				echo '<div class="listing-terms-blocks">';
        	
        	// Echo listing terms
			wpsight_listing_terms( $taxonomy, get_the_id(), $separator, $term_before, $term_after, $linked );
			
			if( $blocks )
				echo '</div>';
			
			// Echo after_widget
			echo $args['after_widget'];
		
		}

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 		 = strip_tags( $new_instance['title'] );
	    $instance['taxonomy'] 	 = in_array( $new_instance['taxonomy'], get_object_taxonomies( wpsight_post_type() ) ) ? $new_instance['taxonomy'] : 'feature';
	    $instance['separator'] 	 = strip_tags( $new_instance['separator'] );
	    $instance['term_before'] = strip_tags( $new_instance['term_before'] );
	    $instance['term_after']  = strip_tags( $new_instance['term_after'] );
	    $instance['linked'] 	 = ! empty( $new_instance['linked'] ) ? 1 : 0;
	    $instance['blocks'] 	 = ! empty( $new_instance['blocks'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 		=> '',
			'taxonomy' 		=> 'feature',
			'separator' 	=> '',
			'term_before' 	=> '',
			'term_after' 	=> '',
			'linked'		=> true,
			'blocks'		=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 		 = strip_tags( $instance['title'] );
		$taxonomy 	 = in_array( $instance['taxonomy'], get_object_taxonomies( wpsight_post_type() ) ) ? $instance['taxonomy'] : 'feature';
		$separator	 = strip_tags( $instance['separator'] );
		$term_before = strip_tags( $instance['term_before'] );
		$term_after	 = strip_tags( $instance['term_after'] );
		$linked		 = ! empty( $instance['linked'] ) ? true : false;
		$blocks		 = ! empty( $instance['blocks'] ) ? true : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e( 'Taxonomy', 'wpsight-stage' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">			
				<?php foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $tax ) : ?>
				<option value="<?php echo strip_tags( $key ); ?>"<?php selected( $taxonomy, $key ); ?>><?php echo strip_tags( $tax->label ); ?></option>
				<?php endforeach; ?>
			</select><br />
			<span class="description"><?php _e( 'Please select the term taxonomy', 'wpsight-stage' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'separator' ); ?>"><?php _e( 'Separator', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'separator' ); ?>" name="<?php echo $this->get_field_name( 'separator' ); ?>" type="text" value="<?php echo esc_attr( $separator ); ?>" /></label><br />
			<span class="description"><?php _e( 'Separator between two terms', 'wpsight-stage' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'term_before' ); ?>"><?php _e( 'Term before', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'term_before' ); ?>" name="<?php echo $this->get_field_name( 'term_before' ); ?>" type="text" value="<?php echo esc_attr( $term_before ); ?>" /></label><br />
			<span class="description"><?php _e( 'Display text before each term', 'wpsight-stage' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'term_after' ); ?>"><?php _e( 'Term after', 'wpsight-stage' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'term_after' ); ?>" name="<?php echo $this->get_field_name( 'term_after' ); ?>" type="text" value="<?php echo esc_attr( $term_after ); ?>" /></label><br />
			<span class="description"><?php _e( 'Display text before each term', 'wpsight-stage' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'linked' ); ?>" name="<?php echo $this->get_field_name( 'linked' ); ?>"<?php checked( $linked ); ?> />
			<label for="<?php echo $this->get_field_id( 'linked' ); ?>"><?php _e( 'Link terms to archive pages', 'wpsight-stage' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'blocks' ); ?>" name="<?php echo $this->get_field_name( 'blocks' ); ?>"<?php checked( $blocks ); ?> />
			<label for="<?php echo $this->get_field_id( 'blocks' ); ?>"><?php _e( 'Display terms as blocks', 'wpsight-stage' ); ?></label></p><?php

	}

}