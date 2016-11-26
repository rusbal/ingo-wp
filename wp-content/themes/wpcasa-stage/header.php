<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and the theme's header section.
 *
 * @package WPCasa STAGE
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="site-header-bg">

		<header class="site-header site-section" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
		
			<div class="container clearfix">
			
				<div class="site-header-title">
				
					<h1 class="site-title" itemprop="headline"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<div class="site-description" itemprop="description"><?php bloginfo( 'description' ); ?></div>
				
				</div><!-- .site-header-title -->
		
				<?php wpsight_stage_menu( 'primary', array( 'align' => 'right', 'menu_class' => 'wpsight-menu wpsight-menu-light' ) ); ?>
		
			</div>
		
		</header>
		
		<?php if( is_front_page() ) : ?>
		
		<section id="banner">
			
			<div class="container">	
				<?php wpsight_search(); ?>
			</div>
		
		</section>
		
		<div class="container">
			<hr />
		</div>
		
		<?php endif; ?>
	
	</div><!-- .site-header-bg -->
	
	<?php wpsight_stage_menu( 'secondary', array( 'container' => 'div', 'container_class' => 'container' ) ); ?>