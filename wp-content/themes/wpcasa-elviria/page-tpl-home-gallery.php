<?php
/**
 * Template Name: Home Page (gallery)
 *
 * This is the gallery home page template.
 *
 * @package WPCasa Elviria
 */
get_header(); ?>

	<?php get_template_part( 'template-parts/home', 'gallery' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'icon-links' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'search' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'cta-1' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'listings' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'cta-2' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'carousel' ); ?>

<?php get_footer(); ?>