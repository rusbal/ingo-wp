<?php
/**
 * The 404 template file.
 *
 * @package WPCasa London
 */
global $wp_query;

get_header(); ?>

	<?php get_template_part( 'template-parts/header', '404' ); ?>

	<div class="site-main">
	
		<div class="container">
		
			<div class="row gutter-60">

				<main class="content col-md-8 col-md-offset-2" role="main" itemprop="mainContentOfPage">

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				</main><!-- .content -->

			</div><!-- .row -->
		
		</div><!-- .container -->
	
	</div><!-- .site-main -->

<?php get_footer(); ?>