<?php
/**
 * The template for displaying author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCasa Bahia
 */
global $wp_query;

get_header(); ?>
	
	<div class="site-top site-section site-page-title">
	
		<div class="container">
			<?php
				the_archive_title( '<h2 class="page-title">', '</h2>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</div>
	
	</div>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row">
	
				<main class="content 8u 12u$(medium)" role="main" itemprop="mainContentOfPage">

					<?php if ( have_posts() ) : ?>
						
						<?php
							// Display agent info if listing archive
							
							if( wpsight_is_listing_agent_archive() ) {
								
								$args = array(
									'show_image' 	=> true,
									'show_phone' 	=> true,
									'show_links' 	=> true,
									'show_archive' 	=> false
								);
								
								wpsight_get_template( 'list-agent.php', array( 'user' => get_queried_object(), 'args' => $args ) );
								
								wpsight_panel( $wp_query );
								
							}
						?>
					
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
					
						<?php if( wpsight_is_listing_agent_archive() ) : ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php else : ?>					
							<?php wpsight_get_template( 'listings-no.php' ); ?>
						<?php endif; ?>
					
					<?php endif; ?>
				
				</main>
				
				<?php $sidebar = wpsight_is_listing_agent_archive() ? 'listing-archive' : ''; ?>
				<?php get_sidebar( $sidebar ); ?>
			
			</div><!-- .content-sidebar-wrap -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>
