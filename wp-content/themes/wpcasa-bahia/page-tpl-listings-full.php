<?php
/**
 * Template Name: Listings Page (full width)
 *
 * This is the template that displays all pages
 * with the listings page-template applied.
 *
 * @package WPCasa Bahia
 */
global $wp_query;

get_header(); ?>

	<div class="site-top site-section site-page-title">
	
		<div class="container">
			<?php the_title( '<h2 class="page-title">', '</h2>' ); ?>
		</div>
	
	</div>

	<div class="site-main site-section">
	
		<div class="container">
	
			<main class="content 12u$" role="main" itemprop="mainContentOfPage">

				<?php while ( have_posts() ) : the_post(); ?>
				
					<?php get_template_part( 'template-parts/content', 'listings' ); ?>
				
				<?php endwhile; // end of the loop. ?>
			
			</main>
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>