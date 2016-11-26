<?php
/**
 * The template part for displaying full-width content
 * above the regular content on the single listing page.
 *
 * @package WPCasa STAGE
 */
?>

<?php if( is_active_sidebar( 'listing-top' ) ) : ?>

	<div class="site-top site-section">
		
		<div class="container">
			
			<div class="row">
			
				<div class="12u$">
				
					<?php dynamic_sidebar( 'listing-top' ); ?>
					
				</div>
			
			</div><!-- .row -->
				
		</div><!-- .container -->
		
	</div><!-- .site-top -->

<?php endif; ?>