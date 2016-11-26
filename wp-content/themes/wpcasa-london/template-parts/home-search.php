<?php
/**
 * Home search template
 *
 * @package WPCasa London
 */
$display = get_post_meta( get_the_id(), '_search_display', true );

if( $display ) : ?>

<div id="home-search" class="site-section site-section-dark home-section">
	
	<div class="container">

		<?php
			$args = array();
			
			// Get orientation	
			$orientation = get_post_meta( get_the_id(), '_search_orientation', true );
			
			// Add to arguments
			if( $orientation && in_array( $orientation, array( 'horizontal', 'vertical' ) ) )
				$args['orientation'] = $orientation;
		?>
		
		<?php wpsight_search( $args ); ?>
	
	</div><!-- .container -->

</div><!-- #home-search -->

<?php endif; ?>