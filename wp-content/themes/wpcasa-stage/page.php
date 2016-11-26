<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPCasa STAGE
 */

get_header(); ?>

	<?php get_template_part( 'template-parts/content-general', 'top' ); ?>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row">
	
				<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">

					<?php while ( have_posts() ) : the_post(); ?>
					
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
					
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