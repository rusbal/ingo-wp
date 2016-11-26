<?php
/**
 * WPCasa STAGE listings carousel
 *
 * @package WPCasa STAGE
 */

/**
 * wpsight_stage_listings_carousel()
 *
 * Echo wpsight_stage_get_listings_carousel()
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional carousel arguments
 *
 * @uses wpsight_stage_get_listings_carousel()
 *
 * @since 1.0.0
 */
 
function wpsight_stage_listings_carousel( $listings = array(), $args = array() ) {
	echo wpsight_stage_get_listings_carousel( $listings, $args );
}

/**
 * wpsight_stage_get_listings_carousel()
 *
 * Create listings carousel.
 *
 * @param object|array $listings Query object or post IDs array
 * @param array $args Optional carousel arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */

function wpsight_stage_get_listings_carousel( $listings = array(), $args = array() ) {
	global $post;
	
	// If we have a query object or ID array
	
	if( is_array( $listings ) ) {		
		$listings = array_map( 'absint', $listings );		
	} elseif( is_object( $listings ) ) {		
		$listings = wp_list_pluck( $listings->posts, 'ID' );		
	}
		
	// Set gallery ID
	$carousel_id = 'wpsight-listings-carousel-' . implode( '-', $listings );
	
	// If there are listings, create the carousel
	
	if( $listings ) {
		
		// Set some defaults
		
		$defaults = array(
			'class_carousel'   			=> $carousel_id . ' wpsight-listings-carousel',
			'class_item'	   			=> 'wpsight-listings-carousel-item',
			'carousel_items'			=> 4,
			'carousel_slide_by'			=> 2,
			'carousel_margin'			=> 30,
			'carousel_stage_padding'	=> 0,
			'carousel_loop'				=> true,
			'carousel_nav'				=> true,
			'carousel_nav_text'			=> '["&lsaquo;","&rsaquo;"]',
			'carousel_dots'				=> true,
			'carousel_dots_each'		=> false,
			'carousel_autoplay'			=> false,
			'carousel_autoplay_time'	=> 5000
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_stage_get_listings_carousel_args', wp_parse_args( $args, $defaults ), $listings, $args );
		
		// Sanitize gallery class
		
		$class_carousel = ! is_array( $args['class_carousel'] ) ? explode( ' ', $args['class_carousel'] ) : $args['class_carousel'];		
		$class_carousel = array_map( 'sanitize_html_class', $class_carousel );
		$class_carousel = implode( ' ', $class_carousel );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Set counter
		$i = 0;
		
		ob_start(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
    
			    $('.wpsight-listings-carousel').owlCarousel({
					responsiveClass:	true,
				    responsive:{
    				    0:{
    				        items:			1,
    				        slideBy:		1,
    				        dots: 			false,
    				        stagePadding: 	0
    				    },
    				    981:{
    				        items: 			<?php echo absint( $args['carousel_items'] ); ?>,
							slideBy:		<?php echo absint( $args['carousel_slide_by'] ); ?>
    				    }
    				},
					margin: 			<?php echo absint( $args['carousel_margin'] ); ?>,
				    stagePadding: 		<?php echo absint( $args['carousel_stage_padding'] ); ?>,
				    loop: 				<?php echo $args['carousel_loop'] == true ? 'true' : 'false'; ?>,
					nav: 				<?php echo $args['carousel_nav'] == true ? 'true' : 'false'; ?>,
					navText:			<?php echo strip_tags( $args['carousel_nav_text'] ); ?>,
					dots:				<?php echo $args['carousel_dots'] == true ? 'true' : 'false'; ?>,
					dotsEach:			<?php echo $args['carousel_dots_each'] == true ? 'true' : 'false'; ?>,
					autoplay:			<?php echo $args['carousel_autoplay'] == true ? 'true' : 'false'; ?>,
					autoplayTimeout:	<?php echo absint( $args['carousel_autoplay_time'] ); ?>,
					autoplayHoverPause: true,
					navContainer:		'.wpsight-listings-carousel-arrows.clearfix',
					dotsContainer: 		'.wpsight-listings-carousel-dots',
					autoHeight:			false
			    });
			    
			});
		</script>
		
		<div class="<?php echo $class_carousel; ?>">
		
			<?php foreach( $listings as $listing ) : $post = get_post( $listing ); setup_postdata( $post ); ?>
			
				<?php wpsight_get_template( 'listing-carousel.php', array( 'post' => $post, 'counter' => $i ) ); ?>
				
				<?php $i++; ?>
						
			<?php endforeach; wp_reset_postdata(); ?>
		
		</div>
		
		<?php if( $args['carousel_nav'] || $args['carousel_dots'] ) : ?>
		<div class="wpsight-listings-carousel-nav clearfix">
			<?php if( $args['carousel_nav'] ) : ?>
			<div class="wpsight-listings-carousel-arrows clearfix"></div>
			<?php endif; ?>
			<?php if( $args['carousel_dots'] ) : ?>
			<div class="wpsight-listings-carousel-dots"></div>
			<?php endif; ?>
		</div>
		<?php endif;
	
	}
	
}
