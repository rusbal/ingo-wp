<?php
/**
 * Header template.
 *
 * @package WPCasa Oslo
 */
global $post, $wpsight_query; ?>

<header class="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
	<div class="site-header-inner">
		
		<div class="header-title">
			<div class="container">			
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
			</div>
		</div><!-- .header-title -->

	</div><!-- .site-header-inner -->
</header><!-- .site-header -->

<?php get_template_part( 'template-parts/header', 'widgets' ); ?>
