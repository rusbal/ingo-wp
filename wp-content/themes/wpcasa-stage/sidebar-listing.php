<?php
/**
 * The sidebar containing a widget area.
 *
 * @package WPCasa STAGE
 */
?>

<aside class="sidebar 4u 12u$(medium)" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	
	<?php if( ! post_password_required() ) : ?>
	
		<?php if( is_active_sidebar( 'sidebar-listing' ) ) : ?>
		
			<?php dynamic_sidebar( 'sidebar-listing' ); ?>
		
		<?php else : ?>
		
			<section class="widget-section">
			
				<div class="widget">
				
					<h3 class="widget-title"><?php _e( 'Listing Single Sidebar', 'wpsight-stage' ); ?></h3>
					
					<p><?php printf( 'Display content here by activating widgets in the "%s" widget area on WP-Admin > Appearance > Widgets.', __( 'Listing Single Sidebar', 'wpsight-stage' ) ); ?></p>
				
				</div>
			
			</section>
		
		<?php endif; ?>
	
	<?php else : ?>
	
		<div class="wpsight-alert wpsight-alert-password">
			<?php _e( 'This listing is password protected.', 'wpsight-stage' ); ?>
		</div>
	
	<?php endif; ?>

</aside>