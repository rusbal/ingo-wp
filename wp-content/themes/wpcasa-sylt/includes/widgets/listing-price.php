<?php
/**
 * Listing Price widget
 *
 * @package WPCasa Sylt
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_sylt_register_widget_listing_price' );
 
function wpsight_sylt_register_widget_listing_price() {
	register_widget( 'WPSight_Sylt_Listing_Price' );
}

/**
 * Listing price widget class
 *
 * @since 1.0.0
 */

class WPSight_Sylt_Listing_Price extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_price',
			'description' => _x( 'Display info bar with price and offer (sale, rent etc.) on single listing pages.', 'listing wigdet', 'wpcasa-sylt' )
		);

		parent::__construct( 'wpsight_sylt_listing_price', '&rsaquo; ' . WPSIGHT_SYLT_NAME . ' ' . _x( 'Listing Price', 'listing widget', 'wpcasa-sylt' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$price_before = strip_tags( $instance['price_before'], '<span><b><strong><i><em><small>' );
		$price_after  = strip_tags( $instance['price_after'], '<span><b><strong><i><em><small>' );
		
		$price_offer = ! empty( $instance['price_offer'] ) ? '1' : '0';

		// Echo before_widget
        echo $args['before_widget'];
        
        // Get listing offer key
        $listing_offer = wpsight_get_listing_offer( get_the_id(), false );
        
        // Echo listing info bar ?>
        
        <div class="wpsight-listing-info clearfix">
		    <div class="alignleft">
		        <?php wpsight_listing_price( get_the_id(), $price_before, $price_after ); ?>
		    </div>
		    <div class="alignright">
		    	<div class="wpsight-listing-id">
					<?php wpsight_listing_id( get_the_id() ); ?>
				</div>
				<?php if( $price_offer ) : ?>	
		    	<div class="wpsight-listing-status">
		    		<span class="badge badge-<?php echo esc_attr( $listing_offer ); ?>" style="background-color:<?php echo esc_attr( wpsight_get_offer_color( $listing_offer ) ); ?>"><?php wpsight_listing_offer(); ?></span>
		        </div>
				<?php endif; ?>
		    </div>
		</div><?php
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['price_before'] = strip_tags( $new_instance['price_before'], '<span><b><strong><i><em><small>' );
	    $instance['price_after']  = strip_tags( $new_instance['price_after'], '<span><b><strong><i><em><small>' );
	    
	    $instance['price_offer'] = ! empty( $new_instance['price_offer'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'price_before' => '', 'price_after' => '' ) );
		
		$price_before = $instance['price_before'];
		$price_after = $instance['price_after'];
		
		$price_offer = isset( $instance['price_offer'] ) ? (bool) $instance['price_offer'] : false; ?>
		
		<p><?php _e( 'Enter some text to be displayed before or after the price (allowed tags: <code>span</code>, <code>b</code>, <code>strong</code>, <code>i</code>, <code>em</code>, <code>small</code>).', 'wpcasa-sylt' ); ?></p>
		
		<p><label for="<?php echo $this->get_field_id( 'price_before' ); ?>"><?php _e( 'Display text before the price', 'wpcasa-sylt' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'price_before' ); ?>" name="<?php echo $this->get_field_name( 'price_before' ); ?>" type="text" value="<?php echo esc_attr( $price_before ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'price_after' ); ?>"><?php _e( 'Display text after the price', 'wpcasa-sylt' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'price_after' ); ?>" name="<?php echo $this->get_field_name( 'price_after' ); ?>" type="text" value="<?php echo esc_attr( $price_after ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'price_offer' ); ?>" name="<?php echo $this->get_field_name( 'price_offer' ); ?>"<?php checked( $price_offer ); ?> />
			<label for="<?php echo $this->get_field_id( 'price_offer' ); ?>"><?php _e( 'Display listing offer badge', 'wpcasa-sylt' ); ?></label></p><?php

	}

}