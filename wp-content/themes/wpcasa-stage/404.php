<?php
/**
 * The 404 template file.
 *
 * @package WPCasa STAGE
 */

get_header(); ?>

	<?php get_template_part( 'template-parts/content-general', 'top' ); ?>

	<div class="site-main site-section">
	
		<div class="container">
		
			<div class="content-sidebar-wrap row 200%">
	
				<main class="content 12u" role="main" itemprop="mainContentOfPage">
					
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				
				</main>
			
			</div><!-- .content-sidebar-wrap -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>