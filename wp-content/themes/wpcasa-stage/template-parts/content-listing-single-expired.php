<?php
/**
 * The template for expired listings.
 *
 * @package WPCasa STAGE
 */

get_header(); ?>

	<div class="site-main">
		
		<div class="wrap">
		
			<div class="wpsight-alert wpsight-alert-expired">
				<?php _e( 'This listing has expired.', 'wpsight-stage' ); ?>
			</div>
		
		</div><!-- .wrap -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
