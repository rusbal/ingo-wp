<?php
/**
 * The template part for displaying full-width content
 * above the regular content on the home page.
 *
 * @package WPCasa STAGE
 */
?>

<?php if( is_active_sidebar( 'home-top' ) && is_front_page() ) : ?>

	<div class="site-top site-section">
		
		<div class="container">
			
			<div class="row">
			
				<div class="12u$">
				
					<?php dynamic_sidebar( 'home-top' ); ?>
				
				</div>
			
			</div><!-- .row -->
				
		</div><!-- .container -->
		
	</div><!-- .site-top -->
	
	<div class="container">
		<hr />
	</div>

<?php endif; ?>