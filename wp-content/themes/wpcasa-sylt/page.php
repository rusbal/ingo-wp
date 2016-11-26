<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPCasa Sylt
 */

get_header(); ?>

	<div class="site-top site-section site-page-title">
	
		<div class="container">
			<?php the_title( '<h2 class="page-title">', '</h2>' ); ?>
		</div>
	
	</div>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row 150%">
	
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