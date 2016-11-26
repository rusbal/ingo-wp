<?php
/**
 * Template Name: Home
 *
 * This is the home page template.
 *
 * @package WPCasa London
 */
global $post;

get_header(); ?>

	<?php get_template_part( 'template-parts/header', 'home' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'content' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'search' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'carousel' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'cta-1' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'services' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'listings' ); ?>
	
	<?php get_template_part( 'template-parts/home', 'cta-2' ); ?>

<?php get_footer(); ?>