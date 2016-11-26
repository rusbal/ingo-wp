<?php
/**
 * Listing Slider widget
 *
 * @package WPCasa Sylt
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_sylt_register_widget_listing_image_slider' );
 
function wpsight_sylt_register_widget_listing_image_slider() {
	register_widget( 'WPSight_Sylt_Listing_Image_Slider' );
}

/**
 * Listing slider widget class
 *
 * @since 1.0.0
 */

class WPSight_Sylt_Listing_Image_Slider extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_image_slider',
			'description' => _x( 'Display listing images in thumbnail slider.', 'listing wigdet', 'wpcasa-sylt' )
		);

		parent::__construct( 'wpsight_sylt_listing_image_slider', '&rsaquo; ' . WPSIGHT_SYLT_NAME . ' ' . _x( 'Listing Image Slider', 'listing widget', 'wpcasa-sylt' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$defaults = array(
			'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> false,
			'thumbs_link' 			=> true,
			'slider_items' 			=> 2,
			'slider_slide_by'		=> 2,
			'slider_margin'			=> 20,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_dots'			=> true,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 6000,
			'lightbox_size' 		=> 'wpsight-full',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_share'	   	=> false,
			'lightbox_close'	   	=> true,
			'lightbox_counter'	   	=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// Thumbnail settings

		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
		// Slider settings
		
		$slider_items 			= absint( $instance['slider_items'] );
		$slider_slide_by 		= absint( $instance['slider_slide_by'] );
		$slider_margin 			= absint( $instance['slider_margin'] );
		$slider_stage_padding 	= absint( $instance['slider_stage_padding'] );		
		$slider_loop 			= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_nav 			= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : false;
		$slider_dots 			= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : false;
		$slider_autoplay 		= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 	= absint( $instance['slider_autoplay_time'] );
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $instance['lightbox_size'] );
		$lightbox_caption 		= isset( $instance['lightbox_caption'] ) ? (bool) $instance['lightbox_caption'] : false;
		$lightbox_prev_next 	= isset( $instance['lightbox_prev_next'] ) ? (bool) $instance['lightbox_prev_next'] : false;
		$lightbox_zoom 			= isset( $instance['lightbox_zoom'] ) ? (bool) $instance['lightbox_zoom'] : false;
		$lightbox_fullscreen 	= isset( $instance['lightbox_fullscreen'] ) ? (bool) $instance['lightbox_fullscreen'] : false;
		$lightbox_share 		= isset( $instance['lightbox_share'] ) ? (bool) $instance['lightbox_share'] : false;
		$lightbox_close 		= isset( $instance['lightbox_close'] ) ? (bool) $instance['lightbox_close'] : false;
		$lightbox_counter 		= isset( $instance['lightbox_counter'] ) ? (bool) $instance['lightbox_counter'] : false;
		
		// Set up args for gallery

		$slider_args = array(
			'class_slider'    		=> 'wpsight-image-slider-' . get_the_id() . ' wpsight-image-slider',
			'class_item'	   		=> 'wpsight-image-slider-item',
			'thumbs_size' 	   		=> $thumbs_size,
			'thumbs_caption'    	=> $thumbs_caption,
			'thumbs_link'	   		=> $thumbs_link,			
			'slider_items'			=> $slider_items,
			'slider_slide_by'		=> $slider_slide_by,
			'slider_margin'			=> $slider_margin,
			'slider_stage_padding'	=> $slider_stage_padding,
			'slider_loop'			=> $slider_loop,
			'slider_nav'			=> $slider_nav,
			'slider_dots'			=> $slider_dots,
			'slider_autoplay'		=> $slider_autoplay,
			'slider_autoplay_time'	=> $slider_autoplay_time,			
			'lightbox_size' 		=> $lightbox_size,
			'lightbox_caption' 		=> $lightbox_caption,
			'lightbox_prev_next'   	=> $lightbox_prev_next,
			'lightbox_zoom'		   	=> $lightbox_zoom,
			'lightbox_fullscreen'  	=> $lightbox_fullscreen,
			'lightbox_share'	   	=> $lightbox_share,
			'lightbox_close'	   	=> $lightbox_close,
			'lightbox_counter'	   	=> $lightbox_counter
		);
		
		// When no gallery, don't any produce output
			
		$images = get_post_meta( get_the_id(), '_gallery', true );
		
		if( ! $images )
			return;
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
        
        // Echo listing gallery
		wpsight_sylt_image_slider( get_the_id(), $slider_args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 					= strip_tags( $new_instance['title'] );
	    
	    // Thumbnail settings
	    
	    $instance['thumbs_size'] 			= strip_tags( $new_instance['thumbs_size'] );	    
	    $instance['thumbs_caption'] 		= ! empty( $new_instance['thumbs_caption'] ) ? 1 : 0;
	    $instance['thumbs_link'] 			= ! empty( $new_instance['thumbs_link'] ) ? 1 : 0;
	    
	    // Slider settings
	    
	    $instance['slider_items'] 			= absint( $new_instance['slider_items'] );
	    $instance['slider_slide_by'] 		= absint( $new_instance['slider_slide_by'] );
	    $instance['slider_margin'] 			= absint( $new_instance['slider_margin'] );
	    $instance['slider_stage_padding'] 	= absint( $new_instance['slider_stage_padding'] );
	    $instance['slider_loop'] 			= ! empty( $new_instance['slider_loop'] ) ? 1 : 0;
	    $instance['slider_nav'] 			= ! empty( $new_instance['slider_nav'] ) ? 1 : 0;
	    $instance['slider_dots'] 			= ! empty( $new_instance['slider_dots'] ) ? 1 : 0;
	    $instance['slider_autoplay'] 		= ! empty( $new_instance['slider_autoplay'] ) ? 1 : 0;
	    $instance['slider_autoplay_time'] 	= absint( $new_instance['slider_autoplay_time'] );
	    
	    // Lightbox settings
	    
	    $instance['lightbox_size'] 			= strip_tags( $new_instance['lightbox_size'] );
	    $instance['lightbox_caption'] 		= ! empty( $new_instance['lightbox_caption'] ) ? 1 : 0;
	    $instance['lightbox_prev_next'] 	= ! empty( $new_instance['lightbox_prev_next'] ) ? 1 : 0;
	    $instance['lightbox_zoom'] 			= ! empty( $new_instance['lightbox_zoom'] ) ? 1 : 0;
	    $instance['lightbox_fullscreen'] 	= ! empty( $new_instance['lightbox_fullscreen'] ) ? 1 : 0;
	    $instance['lightbox_share'] 		= ! empty( $new_instance['lightbox_share'] ) ? 1 : 0;
	    $instance['lightbox_close'] 		= ! empty( $new_instance['lightbox_close'] ) ? 1 : 0;
	    $instance['lightbox_counter'] 		= ! empty( $new_instance['lightbox_counter'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title' 				=> '',
			'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> false,
			'thumbs_link' 			=> true,
			'slider_items' 			=> 2,
			'slider_slide_by'		=> 2,
			'slider_margin'			=> 20,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_dots'			=> true,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 6000,
			'lightbox_size' 		=> 'wpsight-full',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_share'	   	=> false,
			'lightbox_close'	   	=> true,
			'lightbox_counter'	   	=> true
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title 				= strip_tags( $instance['title'] );
		
		// Thumbnail settings

		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
		// Slider settings
		
		$slider_items 			= absint( $instance['slider_items'] );
		$slider_slide_by 		= absint( $instance['slider_slide_by'] );
		$slider_margin 			= absint( $instance['slider_margin'] );
		$slider_stage_padding 	= absint( $instance['slider_stage_padding'] );		
		$slider_loop 			= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_nav 			= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : false;
		$slider_dots 			= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : false;
		$slider_autoplay 		= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 	= absint( $instance['slider_autoplay_time'] );
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $instance['lightbox_size'] );
		$lightbox_caption 		= isset( $instance['lightbox_caption'] ) ? (bool) $instance['lightbox_caption'] : false;
		$lightbox_prev_next 	= isset( $instance['lightbox_prev_next'] ) ? (bool) $instance['lightbox_prev_next'] : false;
		$lightbox_zoom 			= isset( $instance['lightbox_zoom'] ) ? (bool) $instance['lightbox_zoom'] : false;
		$lightbox_fullscreen 	= isset( $instance['lightbox_fullscreen'] ) ? (bool) $instance['lightbox_fullscreen'] : false;
		$lightbox_share 		= isset( $instance['lightbox_share'] ) ? (bool) $instance['lightbox_share'] : false;
		$lightbox_close 		= isset( $instance['lightbox_close'] ) ? (bool) $instance['lightbox_close'] : false;
		$lightbox_counter 		= isset( $instance['lightbox_counter'] ) ? (bool) $instance['lightbox_counter'] : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-sylt' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'slider_items' ); ?>"><?php _e( 'Items', 'wpcasa-sylt' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'slider_items' ); ?>" name="<?php echo $this->get_field_name( 'slider_items' ); ?>">
				<option value="1"<?php selected( $slider_items, 1 ); ?>>1 <?php _ex( 'item', 'listing widget', 'wpcasa-sylt' ); ?></option>
				<option value="2"<?php selected( $slider_items, 2 ); ?>>2 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
				<option value="3"<?php selected( $slider_items, 3 ); ?>>3 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
				<option value="4"<?php selected( $slider_items, 4 ); ?>>4 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
				<option value="5"<?php selected( $slider_items, 5 ); ?>>5 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
				<option value="6"<?php selected( $slider_items, 6 ); ?>>6 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of visible slider items', 'wpcasa-sylt' ); ?></span></p>
		
		<div<?php if( $slider_items == 1 ) echo ' style="display:none"'; ?>>
			
			<p><label for="<?php echo $this->get_field_id( 'slider_slide_by' ); ?>"><?php _e( 'Slide by', 'wpcasa-sylt' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'slider_slide_by' ); ?>" name="<?php echo $this->get_field_name( 'slider_slide_by' ); ?>">
					<option value="1"<?php selected( $slider_slide_by, 1 ); ?>>1 <?php _ex( 'item', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="2"<?php selected( $slider_slide_by, 2 ); if( $slider_items < 2 ) echo ' style="display:none"'; ?>>2 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="3"<?php selected( $slider_slide_by, 3 ); if( $slider_items < 3 ) echo ' style="display:none"'; ?>>3 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="4"<?php selected( $slider_slide_by, 4 ); if( $slider_items < 4 ) echo ' style="display:none"'; ?>>4 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="5"<?php selected( $slider_slide_by, 5 ); if( $slider_items < 5 ) echo ' style="display:none"'; ?>>5 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="6"<?php selected( $slider_slide_by, 6 ); if( $slider_items < 6 ) echo ' style="display:none"'; ?>>6 <?php _ex( 'items', 'listing widget', 'wpcasa-sylt' ); ?></option>
				</select><br />
				<span class="description"><?php _e( 'Please select the number items to slide', 'wpcasa-sylt' ); ?></span></p>
		
		</div>
		
		<p><label for="<?php echo $this->get_field_id( 'slider_margin' ); ?>"><?php _e( 'Margin', 'wpcasa-sylt' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'slider_margin' ); ?>" name="<?php echo $this->get_field_name( 'slider_margin' ); ?>">
				<option value="0"<?php selected( $slider_margin, 0 ); ?>>0px</option>
				<option value="10"<?php selected( $slider_margin, 10 ); ?>>10px</option>
				<option value="20"<?php selected( $slider_margin, 20 ); ?>>20px</option>
				<option value="30"<?php selected( $slider_margin, 30 ); ?>>30px</option>
				<option value="40"<?php selected( $slider_margin, 40 ); ?>>40px</option>
				<option value="50"<?php selected( $slider_margin, 50 ); ?>>50px</option>
				<option value="60"<?php selected( $slider_margin, 60 ); ?>>60px</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the space between slider items', 'wpcasa-sylt' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'slider_stage_padding' ); ?>"><?php _e( 'Stage padding', 'wpcasa-sylt' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'slider_stage_padding' ); ?>" name="<?php echo $this->get_field_name( 'slider_stage_padding' ); ?>">
				<option value="0"<?php selected( $slider_stage_padding, 0 ); ?>>0px</option>
				<option value="10"<?php selected( $slider_stage_padding, 10 ); ?>>10px</option>
				<option value="20"<?php selected( $slider_stage_padding, 20 ); ?>>20px</option>
				<option value="30"<?php selected( $slider_stage_padding, 30 ); ?>>30px</option>
				<option value="40"<?php selected( $slider_stage_padding, 40 ); ?>>40px</option>
				<option value="50"<?php selected( $slider_stage_padding, 50 ); ?>>50px</option>
				<option value="60"<?php selected( $slider_stage_padding, 60 ); ?>>60px</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the stage padding (left and right space to see neighbour items)', 'wpcasa-sylt' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_loop' ); ?>" name="<?php echo $this->get_field_name( 'slider_loop' ); ?>"<?php checked( $slider_loop ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_loop' ); ?>"><?php _e( 'Loop slider to be infinite', 'wpcasa-sylt' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_nav' ); ?>" name="<?php echo $this->get_field_name( 'slider_nav' ); ?>"<?php checked( $slider_nav ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_nav' ); ?>"><?php _e( 'Show prev/next slider navigation', 'wpcasa-sylt' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_dots' ); ?>" name="<?php echo $this->get_field_name( 'slider_dots' ); ?>"<?php checked( $slider_dots ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_dots' ); ?>"><?php _e( 'Show <em>dots</em> slider navigation', 'wpcasa-sylt' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'slider_autoplay' ); ?>" name="<?php echo $this->get_field_name( 'slider_autoplay' ); ?>"<?php checked( $slider_autoplay ); ?> />
				<label for="<?php echo $this->get_field_id( 'slider_autoplay' ); ?>"><?php _e( 'Slide through items automatically', 'wpcasa-sylt' ); ?></label></p>
		
		<div<?php if( ! $slider_autoplay ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'slider_autoplay_time' ); ?>"><?php _e( 'Autoplay interval', 'wpcasa-sylt' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'slider_autoplay_time' ); ?>" name="<?php echo $this->get_field_name( 'slider_autoplay_time' ); ?>">
					<option value="2000"<?php selected( $slider_autoplay_time, 2000 ); ?>>2 <?php _ex( 'seconds', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="4000"<?php selected( $slider_autoplay_time, 4000 ); ?>>4 <?php _ex( 'seconds', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="6000"<?php selected( $slider_autoplay_time, 6000 ); ?>>6 <?php _ex( 'seconds', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="8000"<?php selected( $slider_autoplay_time, 8000 ); ?>>8 <?php _ex( 'seconds', 'listing widget', 'wpcasa-sylt' ); ?></option>
					<option value="10000"<?php selected( $slider_autoplay_time, 10000 ); ?>>10 <?php _ex( 'seconds', 'listing widget', 'wpcasa-sylt' ); ?></option>
				</select><br />
				<span class="description"><?php _e( 'Please select the autoplay interval timeout', 'wpcasa-sylt' ); ?></span></p>
		
		</div>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_size' ); ?>"><?php _e( 'Thumbnail Size', 'wpcasa-sylt' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_size' ); ?>">			
				<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
				<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $thumbs_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
				<?php endforeach; ?>
				<option value="full"<?php selected( $thumbs_size, 'full' ); ?>>full (original)</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the thumbnail size', 'wpcasa-sylt' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_caption' ); ?>"<?php checked( $thumbs_caption ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>"><?php _e( 'Display thumbnail captions (if any)', 'wpcasa-sylt' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_link' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_link' ); ?>"<?php checked( $thumbs_link ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_link' ); ?>"><?php _e( 'Link thumbnails to image file (in lightbox if active)', 'wpcasa-sylt' ); ?></label></p>
		
		<div<?php if( ! $thumbs_link || apply_filters( 'wpsight_sylt_photoswipe', true ) != true ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'lightbox_size' ); ?>"><?php _e( 'Lightbox Size', 'wpcasa-sylt' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'lightbox_size' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_size' ); ?>">			
					<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
					<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $lightbox_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
					<?php endforeach; ?>
					<option value="full"<?php selected( $lightbox_size, 'full' ); ?>>full (original)</option>
				</select><br />
				<span class="description"><?php _e( 'Please select the image size for the lightbox', 'wpcasa-sylt' ); ?></span></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_caption' ); ?>"<?php checked( $lightbox_caption ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>"><?php _e( 'Show image captions in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_prev_next' ); ?>"<?php checked( $lightbox_prev_next ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>"><?php _e( 'Show prev/next navigation in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_zoom' ); ?>"<?php checked( $lightbox_zoom ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>"><?php _e( 'Allow zoom option in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_fullscreen' ); ?>"<?php checked( $lightbox_fullscreen ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>"><?php _e( 'Allow fullscreen toggle in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_share' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_share' ); ?>"<?php checked( $lightbox_share ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_share' ); ?>"><?php _e( 'Display sharing options in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_close' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_close' ); ?>"<?php checked( $lightbox_close ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_close' ); ?>"><?php _e( 'Show close button in lightbox', 'wpcasa-sylt' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_counter' ); ?>"<?php checked( $lightbox_counter ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>"><?php _e( 'Show image counter in lightbox', 'wpcasa-sylt' ); ?></label></p>
		
		</div><?php

	}

}