<?php
/**
 * The template part for displaying full-width content
 * above the regular content on the home page.
 *
 * @package WPCasa STAGE
 */
?>

<?php if( is_active_sidebar( 'home-bottom' ) && is_front_page() ) : ?>

	<div class="container">
		<hr />
	</div>

	<div class="site-bottom site-section">
		
		<div class="container">
			
			<div class="row">
			
				<div class="12u$">
				
					<?php dynamic_sidebar( 'home-bottom' ); ?>
				
				</div>
			
			</div>
				
		</div>
		
	</div>

<?php endif; ?>