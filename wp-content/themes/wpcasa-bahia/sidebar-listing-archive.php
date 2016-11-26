<?php
/**
 * The sidebar containing a widget area.
 *
 * @package WPCasa Bahia
 */
?>

<aside class="sidebar 4u 12u$(medium)" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	
	<?php if( ! post_password_required() ) : ?>
	
		<?php if( is_active_sidebar( 'sidebar-listing-archive' ) ) : ?>
		
			<?php dynamic_sidebar( 'sidebar-listing-archive' ); ?>
		
		<?php elseif( is_active_sidebar( 'sidebar' ) ) : ?>
		
			<?php dynamic_sidebar( 'sidebar' ); ?>
		
		<?php else : ?>
		
			<section class="widget-section">
			
				<div class="widget">
				
					<h3 class="widget-title"><?php _e( 'Listing Archive Sidebar', 'wpcasa-bahia' ); ?></h3>
					
					<p><?php printf( 'Display content here by activating widgets in the "%s" widget area on WP-Admin > Appearance > Widgets.', __( 'Listing Archive Sidebar', 'wpcasa-bahia' ) ); ?></p>
				
				</div>
			
			</section>
		
		<?php endif; ?>
	
	<?php else : ?>
	
		<div class="wpsight-alert wpsight-alert-password">
			<?php _e( 'This page is password protected.', 'wpcasa-bahia' ); ?>
		</div>
	
	<?php endif; ?>

</aside>