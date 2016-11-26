<?php
/**
 * The sidebar containing a widget area.
 *
 * @package WPCasa Sylt
 */
?>

<aside class="sidebar 4u 12u$(medium)" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	
	<?php if( ! post_password_required() ) : ?>
	
		<?php if( is_active_sidebar( 'sidebar' ) ) : ?>
		
			<?php dynamic_sidebar( 'sidebar' ); ?>
		
		<?php else : ?>
		
			<section class="widget-section">
			
				<div class="widget">
				
					<h3 class="widget-title"><?php _e( 'General Sidebar', 'wpcasa-sylt' ); ?></h3>
					
					<p><?php printf( 'Display content here by activating widgets in the "%s" widget area on WP-Admin > Appearance > Widgets.', __( 'General Sidebar', 'wpcasa-sylt' ) ); ?></p>
				
				</div>
			
			</section>
		
		<?php endif; ?>
	
	<?php else : ?>
	
		<div class="wpsight-alert wpsight-alert-password">
			<?php _e( 'This page is password protected.', 'wpcasa-sylt' ); ?>
		</div>
	
	<?php endif; ?>

</aside>