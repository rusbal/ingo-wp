<?php
/**
 * The template for displaying all single posts.
 *
 * @package WPCasa STAGE
 */

get_header(); ?>

	<?php get_template_part( 'template-parts/content-general', 'top' ); ?>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row 200%">
	
				<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">

					<?php while ( have_posts() ) : the_post(); ?>
					
						<?php get_template_part( 'template-parts/content', 'single' ); ?>
					
						<?php wpsight_stage_post_navigation(); ?>
					
						<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						?>
					
					<?php endwhile; // end of the loop. ?>
				
				</main>
				
				<?php get_sidebar(); ?>
			
			</div><!-- .content-sidebar-wrap.row -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
