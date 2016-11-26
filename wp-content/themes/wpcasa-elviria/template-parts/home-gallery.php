<?php
/**
 * Home gallery template
 *
 * @package WPCasa Elviria
 */

add_action( 'wpsight_elviria_image_slider_overlay', 'wpsight_elviria_gallery_slide_overlay' );

function wpsight_elviria_gallery_slide_overlay( $attachment ) {
	
	
	
}

function wpsight_elviria_gallery_overlay( $overlay, $attachment_id ) {
	
}


$gallery_display = get_post_meta( get_the_id(), '_gallery_display', true );

if( $gallery_display ) : ?>

	<?php
		
		// Get gallery slides		
		$gallery_slides = get_post_meta( get_the_id(), '_gallery_slides', true );
		
		$images = array();
		$overlay = array();
		
		foreach( $gallery_slides as $key => $slide ) {
			
			$image_id = $slide['_gallery_slide_image_id'];
			
			if( isset( $image_id ) && ! empty( $image_id ) ) {
				
				$images[ $key ] = $image_id;
				
				// Create slide overlay
				
				$slide_title = ( isset( $slide['_gallery_slide_title'] ) && ! empty( $slide['_gallery_slide_title'] ) ) ? $slide['_gallery_slide_title'] : false;
				
				$slide_description = ( isset( $slide['_gallery_slide_description'] ) && ! empty( $slide['_gallery_slide_description'] ) ) ? $slide['_gallery_slide_description'] : false;
				
				$slide_url = ( isset( $slide['_gallery_slide_url'] ) && ! empty( $slide['_gallery_slide_url'] ) ) ? esc_url( $slide['_gallery_slide_url'] ) : false;
				
				$slide_label = ( isset( $slide['_gallery_slide_label'] ) && ! empty( $slide['_gallery_slide_label'] ) ) ? $slide['_gallery_slide_label'] : false;
				
				$overlay[ $image_id ] = '';
				
				if( $slide_title )
					$overlay[ $image_id ] .= '<h3 class="slide-title">' . $slide_title . '</h3>';
				
				if( $slide_description )
					$overlay[ $image_id ] .= '<p class="slide-description">' . nl2br( $slide['_gallery_slide_description'] ) . '</p>';
				
				if( $slide_url && $slide_label )
					$overlay[ $image_id ] .= '<p class="slide-button"><a href="' . $slide_url . '" class="button">' . $slide_label . '</a></p>';
				
			}
			
		}
		
		// Set slider args
		
		$gallery_args = array(
			'class_slider'    		=> 'wpsight-image-slider wpsight-image-slider-home',
			'class_item'	   		=> 'wpsight-image-slider-item image fit',
			'thumbs_size' 	   		=> 'wpsight-slider',
			'thumbs_caption'    	=> false,
			'thumbs_link'	   		=> false,			
			'slider_items'			=> 1,
			'slider_slide_by'		=> 1,
			'slider_margin'			=> 0,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_nav_text'		=> '["&lsaquo;","&rsaquo;"]',
			'slider_dots'			=> true,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 6000,
			'slider_overlay'		=> $overlay
		);
		
	?>
	
	<?php if( $images ) : ?>
	
		<div id="home-gallery">
			<?php wpsight_elviria_image_slider( $images, $gallery_args ); ?>
		</div>
	
	<?php endif; ?>

<?php endif; ?>