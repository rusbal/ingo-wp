<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and the theme's header section.
 *
 * @package WPCasa Bahia
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
			
	<?php
		// Check if info text should be displayed
		$display_top_info = get_option( 'wpsight_bahia_header_top_info_display' );
		
		// Check if top menu is acitve
		$display_top_menu = has_nav_menu( 'top' );
	?>
	
	<?php if( $display_top_info || $display_top_menu ) : ?>
	
	<div class="site-header-top">
	
		<div class="container">
		
			<div class="row">
			
				<?php if( $display_top_info ) : ?>
				<div class="<?php echo $display_top_info && $display_top_menu ? '4u 12u(medium)' : '12u'; ?>">		
					<div class="site-header-top-info">
						<?php echo get_option( 'wpsight_bahia_header_top_info', '<i class="icon fa-mobile-phone"></i> Call Us Today: 1-234-567-8910' ); ?>
					</div>			
				</div>
				<?php endif; ?>
				
				<?php if( $display_top_menu ) : ?>				
				<div class="<?php echo $display_top_info && $display_top_menu ? '8u 12u(medium)' : '12u'; ?>">	
					<?php wpsight_bahia_menu( 'top', array( 'container' => 'div', 'align' => 'right' ) ); ?>				
				</div>				
				<?php endif; ?>
			
			</div>
		
		</div>

	</div><!-- .site-header-top -->
	
	<?php endif; ?>

	<?php $header_bg = get_option( 'wpsight_bahia_header_background', get_stylesheet_directory_uri() . '/assets/images/bg-site-header-bg.jpg' ); ?>
	<div class="site-header-bg"<?php echo $header_bg ? 'style="background-image: url(' . $header_bg . ')"' : ''; ?>>
		<div class="site-header-bg-inner">

			<header class="site-header site-section" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
			
				<div class="container clearfix">
				
					<div class="site-header-title">
					
						<?php $animate = is_front_page() ? ' animated fadeIn' : ''; ?>
					
						<?php if( get_option( 'wpcasa_bahia_logo', get_stylesheet_directory_uri() . '/assets/images/logo.png' ) ) : ?>					
							<div class="site-title site-title-logo<?php echo $animate; ?>">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_option( 'wpcasa_bahia_logo', get_stylesheet_directory_uri() . '/assets/images/logo.png' ); ?>" alt="logo"></a>
							</div>
						<?php else : ?>			
							<h1 class="site-title site-title-text" itemprop="headline">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							</h1>
						<?php endif; ?>
			
						<div class="site-description<?php echo $animate; ?>" itemprop="description"><?php bloginfo( 'description' ); ?></div>
					
					</div><!-- .site-header-title -->
			
					<?php wpsight_bahia_menu( 'primary', array( 'align' => 'right', 'menu_class' => 'wpsight-menu' ) ); ?>
			
				</div>
			
			</header>
			
			<?php get_template_part( 'template-parts/header', 'tagline' ); ?>
			
			<?php get_template_part( 'template-parts/header', 'search' ); ?>
	
		</div><!-- .site-header-bg-inner -->
	</div><!-- .site-header-bg -->