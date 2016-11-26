<?php
/**
 * The 404 template file.
 *
 * @package WPCasa Elviria
 */

get_header(); ?>

	<div class="site-top site-section site-page-title">
	
		<div class="container">
			<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'wpcasa-elviria' ); ?></h1>
		</div>
	
	</div>

	<div class="site-main site-section">
	
		<div class="container">
	
			<main class="content 12u$" role="main" itemprop="mainContentOfPage">
					
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			
			</main>
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>