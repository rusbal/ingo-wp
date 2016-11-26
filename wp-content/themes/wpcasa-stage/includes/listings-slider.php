<?php
/**
 * WPCasa STAGE listings slider
 *
 * @package WPCasa STAGE
 */

/**
 * wpsight_stage_listings_slider()
 *
 * Echo wpsight_stage_get_listings_slider()
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional slider arguments
 *
 * @uses wpsight_stage_get_listings_slider()
 *
 * @since 1.0.0
 */
 
function wpsight_stage_listings_slider( $listings = array(), $args = array() ) {
	echo wpsight_stage_get_listings_slider( $listings, $args );
}

/**
 * wpsight_stage_get_listings_slider()
 *
 * Create listings slider.
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional slider arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */

function wpsight_stage_get_listings_slider( $listings = array(), $args = array() ) {
	global $post;
	
	// If we have a query object or ID array
	
	if( is_array( $listings ) ) {		
		$listings = array_map( 'absint', $listings );		
	} elseif( is_object( $listings ) ) {		
		$listings = wp_list_pluck( $listings->posts, 'ID' );		
	}
		
	// Set gallery ID
	$slider_id = 'wpsight-listings-slider-' . implode( '-', $listings );
	
	// If there are listings, create the slider
	
	if( $listings ) {
		
		// Set some defaults
		
		$defaults = array(
			'class_slider'   		=> $slider_id . ' wpsight-listings-slider',
			'class_item'	   		=> 'wpsight-listings-slider-item',
			'slider_items'			=> 1,
			'slider_slide_by'		=> 1,
			'slider_margin'			=> 0,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_nav_text'		=> '["&lsaquo;","&rsaquo;"]',
			'slider_dots'			=> true,
			'slider_dots_each'		=> false,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 5000
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_stage_get_listings_slider_args', wp_parse_args( $args, $defaults ), $listings, $args );
		
		// Sanitize slider class
		
		$class_slider = ! is_array( $args['class_slider'] ) ? explode( ' ', $args['class_slider'] ) : $args['class_slider'];		
		$class_slider = array_map( 'sanitize_html_class', $class_slider );
		$class_slider = implode( ' ', $class_slider );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Set counter
		$i = 0;
		
		ob_start(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
    
			    $('.wpsight-listings-slider').owlCarousel({
					responsiveClass:	true,
				    responsive:{
    				    0:{
    				        dots: 		false
    				    },
    				    981:{
							dots:		<?php echo $args['slider_dots'] == true ? 'true' : 'false'; ?>
    				    }
    				},
				    items: 				<?php echo absint( $args['slider_items'] ); ?>,
					slideBy:			<?php echo absint( $args['slider_slide_by'] ); ?>,
					margin: 			<?php echo absint( $args['slider_margin'] ); ?>,
				    stagePadding: 		<?php echo absint( $args['slider_stage_padding'] ); ?>,
				    loop: 				<?php echo $args['slider_loop'] == true ? 'true' : 'false'; ?>,
					nav: 				<?php echo $args['slider_nav'] == true ? 'true' : 'false'; ?>,
					navText:			<?php echo strip_tags( $args['slider_nav_text'] ); ?>,
					dotsEach:			<?php echo $args['slider_dots_each'] == true ? 'true' : 'false'; ?>,
					autoplay:			<?php echo $args['slider_autoplay'] == true ? 'true' : 'false'; ?>,
					autoplayTimeout:	<?php echo absint( $args['slider_autoplay_time'] ); ?>,
					autoplayHoverPause: true,
					navContainer:		'.wpsight-listings-slider-arrows.clearfix',
					dotsContainer: 		'.wpsight-listings-slider-dots',
					autoHeight:			false
			    });
			    
			});
		</script>
		
		<div class="<?php echo $class_slider; ?>">
		
			<?php foreach( $listings as $listing ) : $post = get_post( $listing ); setup_postdata( $post ); ?>
			
				<?php wpsight_get_template( 'listing-slider.php', array( 'post' => $post, 'counter' => $i ) ); ?>
				
				<?php $i++; ?>
						
			<?php endforeach; wp_reset_postdata(); ?>
		
		</div>
		
		<?php if( $args['slider_nav'] || $args['slider_dots'] ) : ?>
		<div class="wpsight-listings-slider-nav clearfix">
			<?php if( $args['slider_nav'] ) : ?>
			<div class="wpsight-listings-slider-arrows clearfix"></div>
			<?php endif; ?>
			<?php if( $args['slider_dots'] ) : ?>
			<div class="wpsight-listings-slider-dots"></div>
			<?php endif; ?>
		</div>
		<?php endif;
	
	}
	
}
