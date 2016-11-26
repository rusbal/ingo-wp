<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and the theme's header section.
 *
 * @package WPCasa Elviria
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

		<?php $header_bg = get_option( 'wpcasa_elviria_logo_bg', get_stylesheet_directory_uri() . '/assets/images/bg-site-header.png' ); ?>
		<header class="site-header site-section" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader"<?php echo $header_bg ? 'style="background-image: url(' . $header_bg . ')"' : ''; ?>>
		
			<div class="container clearfix">
			
				<div class="site-header-title">
				
					<?php $animate = is_front_page() ? '  animated fadeIn' : ''; ?>
				
					<?php if( get_option( 'wpcasa_elviria_logo', get_stylesheet_directory_uri() . '/assets/images/logo.png' ) ) : ?>					
						<div class="site-title site-title-logo<?php echo $animate; ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_option( 'wpcasa_elviria_logo', get_stylesheet_directory_uri() . '/assets/images/logo.png' ); ?>" alt="logo"></a>
						</div>
					<?php else : ?>			
						<h1 class="site-title site-title-text" itemprop="headline">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
					<?php endif; ?>

					<div class="site-description<?php echo $animate; ?>" itemprop="description"><?php bloginfo( 'description' ); ?></div>
				
				</div><!-- .site-header-title -->
		
				<?php wpsight_elviria_menu( 'primary', array( 'align' => 'right', 'menu_class' => 'wpsight-menu' ) ); ?>
		
			</div>
		
		</header>
	
	</div><!-- .site-header-bg -->
	
	<?php wpsight_elviria_menu( 'secondary', array( 'container' => 'div', 'container_class' => 'container' ) ); ?>