<?php
/**
 * Header search template.
 *
 * @package WPCasa London
 */
global $post, $wpsight_query; ?>

<header class="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
	<div class="site-header-inner">
		
		<?php get_template_part( 'template-parts/header', 'top' ); ?>
		
		<div class="header-title">
			<div class="container">
			
				<div class="header-title-inner">
				
					<div class="header-title-title">
						<h1 class="page-title"><?php _ex( 'Search', 'search page title', 'wpcasa-london' ); ?></h1>
						<div class="taxonomy-description"><?php printf( _x( 'Your search results for <em><strong>%s</strong></em>', 'search page title', 'wpcasa-london' ), strip_tags( get_search_query() ) ); ?></div>
					</div>
					
					<div class="header-title-right">				
						<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-primary"><?php _ex( 'Home Page', 'search page title', 'wpcasa-london' ); ?></a>
					</div>
				
				</div>
				
			</div>
		</div><!-- .header-title -->

	</div><!-- .site-header-inner -->
</header><!-- .site-header -->

<?php get_template_part( 'template-parts/header', 'widgets' ); ?>
