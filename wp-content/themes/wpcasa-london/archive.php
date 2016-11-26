<?php
/**
 * The archive template file.
 *
 * @package WPCasa London
 */
global $wp_query;

$archive = wpsight_is_listings_archive() ? 'archive-listings' : 'archive';

get_header(); ?>

	<?php get_template_part( 'template-parts/header', $archive ); ?>

	<div class="site-main">
	
		<div class="container">
		
			<div class="row gutter-60">

				<main class="content <?php if( is_active_sidebar( 'sidebar' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>" role="main" itemprop="mainContentOfPage">

					<?php if( is_active_sidebar( 'content-top' ) ) : ?>
					<div class="content-top">				
						<?php dynamic_sidebar( 'content-top' ); ?>	
					</div><!-- .content-top -->
					<?php endif; ?>
					
					<?php if ( have_posts() ) : ?>

						<?php if( ! wpsight_is_listings_archive() ) : ?><div class="row gutter-60"><?php endif; ?>

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
						<?php endwhile; ?>
						
						<?php if( ! wpsight_is_listings_archive() ) : ?></div><?php endif; ?>

						<?php wpsight_pagination( $wp_query->max_num_pages ); ?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif; ?>
			
					<?php if( is_active_sidebar( 'content-bottom' ) ) : ?>
					<div class="content-bottom">
						<?php dynamic_sidebar( 'content-bottom' ); ?>
					</div><!-- .content-bottom -->
					<?php endif; ?>

				</main><!-- .content -->
			
				<?php get_sidebar(); ?>

			</div><!-- .row -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>