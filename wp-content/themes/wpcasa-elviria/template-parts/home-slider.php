<?php
/**
 * Home slider template
 *
 * @package WPCasa Elviria
 */
$slider_display = get_post_meta( get_the_id(), '_slider_display', true );

if( $slider_display ) : ?>

	<?php
		// Set up slider instance

		$instance = array(
			'nr'					=> get_post_meta( get_the_id(), '_slider_nr', true ),
			'offer_filter'			=> get_post_meta( get_the_id(), '_slider_offer', true ),
			'slider_loop'			=> get_post_meta( get_the_id(), '_slider_loop', true ),
			'slider_nav'			=> get_post_meta( get_the_id(), '_slider_nav', true ),
			'slider_dots'			=> get_post_meta( get_the_id(), '_slider_dots', true ),
			'slider_autoplay'		=> get_post_meta( get_the_id(), '_slider_autoplay', true ),
			'slider_autoplay_time'	=> get_post_meta( get_the_id(), '_slider_autoplay_time', true ),
			'slider_full_width'		=> true
		);
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {						
			$value = get_post_meta( get_the_id(), '_slider_taxonomy_' . $key, true );
			$instance[ 'taxonomy_filter_' . $key ] = $value ? $value : false;
		}
		
		// Process slider instance
		
		$nr 				= absint( $instance['nr'] );
		$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
		$taxonomy_filters	= array();
		
		foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {			
			if( $instance[ 'taxonomy_filter_' . $key ] != false )
				$taxonomy_filters[ $key ] =  strip_tags( $instance[ 'taxonomy_filter_' . $key ] );		
		}
		
		$defaults = array(
			'nr'					=> 10,
			'slider_margin'			=> 0,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_dots'			=> true,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 6000,
			'slider_full_width'		=> false
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// slider settings
		
		$slider_margin 			= absint( $instance['slider_margin'] );
		$slider_stage_padding 	= absint( $instance['slider_stage_padding'] );		
		$slider_loop 			= isset( $instance['slider_loop'] ) ? (bool) $instance['slider_loop'] : false;
		$slider_nav 			= isset( $instance['slider_nav'] ) ? (bool) $instance['slider_nav'] : false;
		$slider_dots 			= isset( $instance['slider_dots'] ) ? (bool) $instance['slider_dots'] : false;
		$slider_autoplay 		= isset( $instance['slider_autoplay'] ) ? (bool) $instance['slider_autoplay'] : false;
		$slider_autoplay_time 	= absint( $instance['slider_autoplay_time'] );
		$slider_full_width 		= isset( $instance['slider_full_width'] ) ? (bool) $instance['slider_full_width'] : false;
		
		// Set up args for slider

		$slider_args = array(
			'class_slider'   		=> 'wpsight-listings-slider-home wpsight-listings-slider',
			'class_item'	   		=> 'wpsight-listings-slider-item',
			'slider_margin'			=> $slider_margin,
			'slider_stage_padding'	=> $slider_stage_padding,
			'slider_loop'			=> $slider_loop,
			'slider_nav'			=> $slider_nav,
			'slider_dots'			=> $slider_dots,
			'slider_autoplay'		=> $slider_autoplay,
			'slider_autoplay_time'	=> $slider_autoplay_time,
			'slider_full_width'		=> $slider_full_width
		);
		
		$listings_args = array(
			'nr'				=> $nr,
			'offer'				=> $offer_filter,
			'meta_query'		=> array(
				array(
					'key'		=> '_thumbnail_id',
					'compare'	=> 'EXISTS'
				)
			)
		);
		
		// Merge taxonomy filters into args and apply filter hook
		$listings_args = apply_filters( 'wpsight_elviria_home_listings_slider_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, 'home' );
		
		// Finally get listings
		$listings = wpsight_get_listings( $listings_args );
		
	?>
	
	<?php if( $listings ) : ?>
	
	<div id="home-slider">	
		<?php wpsight_elviria_listings_slider( $listings, $slider_args ); ?>	
	</div>
	
	<?php endif; ?>

<?php endif; ?>