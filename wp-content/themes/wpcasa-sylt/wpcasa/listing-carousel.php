<div class="listing-<?php the_ID(); ?>-carousel-wrap listing-carousel-wrap">

	<div id="listing-<?php the_ID(); ?>-carousel" <?php wpsight_listing_class( 'wpsight-listing-carousel' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
			
			<div class="listing-top">
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
			
			</div>
			
			<div class="row">
			
				<?php $sidebar_id = isset( $args['carousel_context']['id'] ) ? $args['carousel_context']['id'] : false; ?>
				
				<?php $class_left = 'sidebar-listing' == $sidebar_id || 'sidebar' == $sidebar_id ? '12$' : '4u 12u$(medium)'; ?>
			
				<div class="wpsight-listing-left <?php echo $class_left; ?>">
				
					<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
				
				</div>
				
				<?php $class_right = 'sidebar-listing' == $sidebar_id || 'sidebar' == $sidebar_id ? '12$' : '8u 12u$(medium)'; ?>
				
				<div class="wpsight-listing-right <?php echo $class_right; ?> equal">
					
					<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
					
					<?php wpsight_get_template( 'listing-archive-description.php' ); ?>
					
					<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
				
				</div>
			
			</div>
			
			<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
			
			<?php do_action( 'wpsight_listing_archive_after', $post ); ?>
		
		</div>
	
	</div><!-- #listing-<?php the_ID(); ?>-carousel -->

</div>