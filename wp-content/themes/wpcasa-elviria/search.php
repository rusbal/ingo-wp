<?php
/**
 * The search template file.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCasa Elviria
 */
get_header(); ?>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row">
	
				<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">
					
					<?php if ( have_posts() ) : ?>
					
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
					
							<?php get_template_part( 'template-parts/content', 'search' ); ?>
					
						<?php endwhile; ?>
					
						<?php the_posts_navigation(); ?>
					
					<?php else : ?>
					
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					
					<?php endif; ?>
				
				</main>
				
				<?php get_sidebar(); ?>
			
			</div><!-- .content-sidebar-wrap -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>