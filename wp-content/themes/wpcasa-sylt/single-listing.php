<?php
/**
 * The template for single listings.
 *
 * @package WPCasa Sylt
 */

get_header(); ?>

	<?php if( ! wpsight_is_listing_expired() || wpsight_user_can_edit_listing( get_the_id() ) ) : ?>

		<?php get_template_part( 'template-parts/content-listing', 'single-top' ); ?>
		
		<div class="site-main site-section">
	
			<div class="container">
			
				<div class="content-sidebar-wrap row 150%">
			
					<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">
		
						<?php while ( have_posts() ) : the_post(); ?>
						
							<?php get_template_part( 'template-parts/content-listing', 'single' ); ?>
						
							<?php // the_post_navigation(); ?>
						
						<?php endwhile; // end of the loop. ?>
					
					</main>
					
					<?php get_sidebar( 'listing' ); ?>
				
				</div><!-- .content-sidebar-wrap -->
			
			</div><!-- .container -->
		
		</div><!-- .site-main -->
		
		<?php get_template_part( 'template-parts/content-listing', 'single-bottom' ); ?>
	
	<?php else: ?>
	
		<?php get_template_part( 'template-parts/content-listing', 'single-expired' ); ?>
	
	<?php endif; ?>

<?php get_footer(); ?>
