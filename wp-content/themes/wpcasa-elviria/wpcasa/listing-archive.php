<?php $layout = is_page_template( 'page-tpl-home-slider.php' ) || is_page_template( 'page-tpl-home-gallery.php' ) || is_page_template( 'page-tpl-listings-full.php' ) ? '4u 6u(medium) 12u$(small)' : '6u 12u$(medium)'; ?>

<div class="listing-wrap <?php echo $layout; ?>">

	<div id="listing-<?php the_ID(); ?>" <?php wpsight_listing_class( 'wpsight-listing-archive equal' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
			
			<div class="listing-top">
			
				<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-meta.php' ); ?>
			
			</div>
			
			<div class="listing-bottom">
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
						
				<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
				
				<?php // wpsight_get_template( 'listing-archive-description.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
			
			</div>
			
			<?php do_action( 'wpsight_listing_archive_after', $post ); ?>
		
		</div>
	
	</div><!-- #listing-<?php the_ID(); ?> -->

</div>