<?php
/**
 * Header top template with logo and menu.
 *
 * @package WPCasa London
 */
?>

<div class="header-top">
	<div class="container">
		<div class="header-top-inner clearfix">
									
			<div class="site-logo">
			
				<?php
					/**
					 * We check individual logo settings here. If there is an alternative
					 * logo and the option of the individual page is activated, we use it.
					 * If not the regular logo is used.
					 */					 
					$logo = get_theme_mod( 'wpcasa_logo' );

					if( is_singular() ) {
						
						// Check if alternative logo should be used
						$use_alt = get_post_meta( get_the_id(), '_logo_alt', true );
						
						// Get alternative logo
						$logo_alt = get_theme_mod( 'wpcasa_logo_alt' );
						
						if( $use_alt && $logo_alt )
							$logo = $logo_alt;
						
					}
					
					$logo = apply_filters( 'wpsight_london_logo', $logo );
				?>
			
				<?php if( ! empty( $logo ) ) : ?>
					<div class="site-title site-title-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_url( $logo ); ?>" alt="logo"></a>
					</div>
				<?php else : ?>
				
					<?php
						/**
						 * If there is no image logo, we will display the
						 * site title or the text logo.
						 */
						$logo_text = get_theme_mod( 'wpcasa_logo_text' );
						
						$text = $logo_text ? $logo_text : get_bloginfo( 'name' );
					?>
					<h1 class="site-title site-title-text" itemprop="headline">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo strip_tags( $text ); ?></a>
					</h1>
				<?php endif; ?>
				
				<?php $margin_bottom = get_theme_mod( 'tagline_margin' ) ? ' style="padding-bottom: ' . absint( get_theme_mod( 'tagline_margin' ) ) . 'px"' : ''; ?>
				<div class="site-description"<?php echo $margin_bottom; ?>><?php bloginfo( 'description' ); ?></div>

			</div>
			
			<?php wpsight_london_menu(); ?>
			
			<div class="menu-btn menu-btn--right hidden-md hidden-lg">
				<span class="sr-only"><?php _e( 'Toggle navigation', 'wpcasa-london' ); ?></span>
				<i class="fa fa-fw fa-navicon"></i>
			</div>

		</div>
	</div>
</div><!-- .header-top -->
