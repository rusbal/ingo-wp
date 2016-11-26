<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCasa STAGE
 */
global $wp_query;

get_header(); ?>

	<?php get_template_part( 'template-parts/content-general', 'top' ); ?>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row 200%">
	
				<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">

					<?php if ( have_posts() ) : ?>
					
						<header class="page-header">
							<?php
								the_archive_title( '<h2 class="page-title">', '</h2>' );
								the_archive_description( '<div class="taxonomy-description">', '</div>' );
							?>
						</header><!-- .page-header -->
						
						<?php if( wpsight_is_listings_archive() ) : ?>
							<?php wpsight_panel( $wp_query ); ?>
						<?php endif; ?>
					
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
					
							<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_format() );
							?>
					
						<?php endwhile; ?>
					
						<?php wpsight_pagination( $wp_query->max_num_pages ); ?>
					
					<?php else : ?>
					
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					
					<?php endif; ?>
				
				</main>
				
				<?php get_sidebar(); ?>
			
			</div><!-- .content-sidebar-wrap -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
