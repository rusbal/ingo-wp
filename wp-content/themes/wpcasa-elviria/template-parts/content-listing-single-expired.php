<?php
/**
 * The template for expired listings.
 *
 * @package WPCasa Elviria
 */

get_header(); ?>

	<div class="site-main">
		
		<div class="wrap">
		
			<div class="wpsight-alert wpsight-alert-expired">
				<?php _e( 'This listing has expired.', 'wpcasa-elviria' ); ?>
			</div>
		
		</div><!-- .wrap -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
