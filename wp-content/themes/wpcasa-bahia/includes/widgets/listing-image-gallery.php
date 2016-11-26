<?php
/**
 * Listing Gallery widget
 *
 * @package WPCasa Bahia
 */

/**
 * Register widget
 */
 
add_action( 'widgets_init', 'wpsight_bahia_register_widget_listing_image_gallery' );
 
function wpsight_bahia_register_widget_listing_image_gallery() {
	register_widget( 'WPSight_Bahia_Listing_Image_Gallery' );
}

/**
 * Listing gallery widget class
 *
 * @since 1.0.0
 */

class WPSight_Bahia_Listing_Image_Gallery extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_listing_image_gallery',
			'description' => _x( 'Display listing images in thumbnail gallery.', 'listing wigdet', 'wpcasa-bahia' )
		);

		parent::__construct( 'wpsight_bahia_listing_image_gallery', '&rsaquo; ' . WPSIGHT_BAHIA_NAME . ' ' . _x( 'Listing Image Gallery', 'listing widget', 'wpcasa-bahia' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$defaults = array(
			'thumbs_columns' 		=> 6, // 12/6 = 2 columns
			'thumbs_columns_small'	=> 12, // 12/12 = 1 column
			'thumbs_gutter'			=> 100, // % of default gutter
			'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> true,
			'thumbs_link' 			=> true,
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
		
		$thumbs_columns 		= absint( $instance['thumbs_columns'] );
		$thumbs_columns_small	= absint( $instance['thumbs_columns_small'] );
		$thumbs_gutter 			= absint( $instance['thumbs_gutter'] );
		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
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

		$gallery_args = array(
			'class_gallery' 		=> 'wpsight-gallery-' . get_the_id() . ' wpsight-gallery',
			'class_unit' 			=> 'wpsight-gallery-item-u ' . $thumbs_columns . 'u',
			'thumbs_columns' 		=> $thumbs_columns,
			'thumbs_columns_small'	=> $thumbs_columns_small,
			'thumbs_size' 			=> $thumbs_size,
			'thumbs_gutter'			=> $thumbs_gutter,
			'thumbs_caption'		=> $thumbs_caption,
			'thumbs_link'			=> $thumbs_link,
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
			
		$images = get_post_meta( get_the_id(), '_gallery' );
		
		if( ! $images )
			return;
		
		// Echo before_widget
        echo $args['before_widget'];
        
        if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
        
        // Echo listing gallery
		wpsight_bahia_image_gallery( get_the_id(), $gallery_args );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title'] 					= strip_tags( $new_instance['title'] );
	    
	    // Thumbnail settings
	    
	    $instance['thumbs_columns'] 		= absint( $new_instance['thumbs_columns'] );
	    $instance['thumbs_columns_small']	= absint( $new_instance['thumbs_columns_small'] );
	    $instance['thumbs_gutter'] 			= absint( $new_instance['thumbs_gutter'] );
	    $instance['thumbs_size'] 			= strip_tags( $new_instance['thumbs_size'] );	    
	    $instance['thumbs_caption'] 		= ! empty( $new_instance['thumbs_caption'] ) ? 1 : 0;
	    $instance['thumbs_link'] 			= ! empty( $new_instance['thumbs_link'] ) ? 1 : 0;
	    
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
			'thumbs_columns' 		=> 6, // 12/6 = 2 columns
			'thumbs_columns_small'	=> 12, // 12/12 = 1 column
			'thumbs_gutter'			=> 100, // % of default gutter
			'thumbs_size' 			=> 'wpsight-half',
			'thumbs_caption' 		=> true,
			'thumbs_link' 			=> true,
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
		
		$title = strip_tags( $instance['title'] );
		
		// Thumbnail settings
		
		$thumbs_columns 		= absint( $instance['thumbs_columns'] );
		$thumbs_columns_small	= absint( $instance['thumbs_columns_small'] );
		$thumbs_gutter 			= absint( $instance['thumbs_gutter'] );
		$thumbs_size			= strip_tags( $instance['thumbs_size'] );		
		$thumbs_caption 		= isset( $instance['thumbs_caption'] ) ? (bool) $instance['thumbs_caption'] : false;
		$thumbs_link 			= isset( $instance['thumbs_link'] ) ? (bool) $instance['thumbs_link'] : false;
		
		// Lightbox settings
		
		$lightbox_size			= strip_tags( $instance['lightbox_size'] );
		$lightbox_caption 		= isset( $instance['lightbox_caption'] ) ? (bool) $instance['lightbox_caption'] : false;
		$lightbox_prev_next 	= isset( $instance['lightbox_prev_next'] ) ? (bool) $instance['lightbox_prev_next'] : false;
		$lightbox_zoom 			= isset( $instance['lightbox_zoom'] ) ? (bool) $instance['lightbox_zoom'] : false;
		$lightbox_fullscreen 	= isset( $instance['lightbox_fullscreen'] ) ? (bool) $instance['lightbox_fullscreen'] : false;
		$lightbox_share 		= isset( $instance['lightbox_share'] ) ? (bool) $instance['lightbox_share'] : false;
		$lightbox_close 		= isset( $instance['lightbox_close'] ) ? (bool) $instance['lightbox_close'] : false;
		$lightbox_counter 		= isset( $instance['lightbox_counter'] ) ? (bool) $instance['lightbox_counter'] : false; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-bahia' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_columns' ); ?>"><?php _e( 'Columns', 'wpcasa-bahia' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_columns' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_columns' ); ?>">
				<option value="12"<?php selected( $thumbs_columns, 12 ); ?>>1 <?php _ex( 'column', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="6"<?php selected( $thumbs_columns, 6 ); ?>>2 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="4"<?php selected( $thumbs_columns, 4 ); ?>>3 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="3"<?php selected( $thumbs_columns, 3 ); ?>>4 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="2"<?php selected( $thumbs_columns, 2 ); ?>>6 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="1"<?php selected( $thumbs_columns, 1 ); ?>>12 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of columns', 'wpcasa-bahia' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_columns_small' ); ?>"><?php _e( 'Columns', 'wpcasa-bahia' ); ?> (<?php _ex( 'on small screens', 'listing widget', 'wpcasa-bahia' ); ?>):</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_columns_small' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_columns_small' ); ?>">
				<option value="12"<?php selected( $thumbs_columns_small, 12 ); ?>>1 <?php _ex( 'column', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="6"<?php selected( $thumbs_columns_small, 6 ); ?>>2 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="4"<?php selected( $thumbs_columns_small, 4 ); ?>>3 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="3"<?php selected( $thumbs_columns_small, 3 ); ?>>4 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="2"<?php selected( $thumbs_columns_small, 2 ); ?>>6 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
				<option value="1"<?php selected( $thumbs_columns_small, 1 ); ?>>12 <?php _ex( 'columns', 'listing widget', 'wpcasa-bahia' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the number of columns', 'wpcasa-bahia' ); ?> (<?php _ex( 'on small screens', 'listing widget', 'wpcasa-bahia' ); ?>)</span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_gutter' ); ?>"><?php _e( 'Gutter', 'wpcasa-bahia' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_gutter' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_gutter' ); ?>">
				<option value="0"<?php selected( $thumbs_gutter, 0 ); ?>>0%</option>
				<option value="25"<?php selected( $thumbs_gutter, 25 ); ?>>25%</option>
				<option value="50"<?php selected( $thumbs_gutter, 50 ); ?>>50%</option>
				<option value="100"<?php selected( $thumbs_gutter, 100 ); ?>>100% (<?php _ex( 'default', 'listing widget', 'wpcasa-bahia' ); ?>)</option>
				<option value="150"<?php selected( $thumbs_gutter, 150 ); ?>>150%</option>
				<option value="200"<?php selected( $thumbs_gutter, 200 ); ?>>200%</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the gutter width (space between thumbs)', 'wpcasa-bahia' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'thumbs_size' ); ?>"><?php _e( 'Thumbnail Size', 'wpcasa-bahia' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'thumbs_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_size' ); ?>">			
				<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
				<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $thumbs_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
				<?php endforeach; ?>
				<option value="full"<?php selected( $thumbs_size, 'full' ); ?>>full (original)</option>
			</select><br />
			<span class="description"><?php _e( 'Please select the thumbnail size', 'wpcasa-bahia' ); ?></span></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_caption' ); ?>"<?php checked( $thumbs_caption ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_caption' ); ?>"><?php _e( 'Display thumbnail captions (if any)', 'wpcasa-bahia' ); ?></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbs_link' ); ?>" name="<?php echo $this->get_field_name( 'thumbs_link' ); ?>"<?php checked( $thumbs_link ); ?> />
			<label for="<?php echo $this->get_field_id( 'thumbs_link' ); ?>"><?php _e( 'Link thumbnails to image file (in lightbox if active)', 'wpcasa-bahia' ); ?></label></p>
		
		<div<?php if( ! $thumbs_link || apply_filters( 'wpsight_bahia_photoswipe', true ) != true ) echo ' style="display:none"'; ?>>
		
			<p><label for="<?php echo $this->get_field_id( 'lightbox_size' ); ?>"><?php _e( 'Lightbox Size', 'wpcasa-bahia' ); ?>:</label><br />
				<select class="widefat" id="<?php echo $this->get_field_id( 'lightbox_size' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_size' ); ?>">			
					<?php foreach( get_intermediate_image_sizes() as $size ) : ?>
					<option value="<?php echo strip_tags( $size ); ?>"<?php selected( $lightbox_size, $size ); ?>><?php echo strip_tags( $size ); ?></option>
					<?php endforeach; ?>
					<option value="full"<?php selected( $lightbox_size, 'full' ); ?>>full (original)</option>
				</select><br />
				<span class="description"><?php _e( 'Please select the image size for the lightbox', 'wpcasa-bahia' ); ?></span></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_caption' ); ?>"<?php checked( $lightbox_caption ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_caption' ); ?>"><?php _e( 'Show image captions in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_prev_next' ); ?>"<?php checked( $lightbox_prev_next ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_prev_next' ); ?>"><?php _e( 'Show prev/next navigation in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_zoom' ); ?>"<?php checked( $lightbox_zoom ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_zoom' ); ?>"><?php _e( 'Allow zoom option in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_fullscreen' ); ?>"<?php checked( $lightbox_fullscreen ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_fullscreen' ); ?>"><?php _e( 'Allow fullscreen toggle in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_share' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_share' ); ?>"<?php checked( $lightbox_share ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_share' ); ?>"><?php _e( 'Display sharing options in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_close' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_close' ); ?>"<?php checked( $lightbox_close ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_close' ); ?>"><?php _e( 'Show close button in lightbox', 'wpcasa-bahia' ); ?></label></p>
				
				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>" name="<?php echo $this->get_field_name( 'lightbox_counter' ); ?>"<?php checked( $lightbox_counter ); ?> />
				<label for="<?php echo $this->get_field_id( 'lightbox_counter' ); ?>"><?php _e( 'Show image counter in lightbox', 'wpcasa-bahia' ); ?></label></p>
		
		</div><?php

	}

}