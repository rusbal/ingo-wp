<div id="listing-<?php the_ID(); ?>" <?php wpsight_listing_class( 'wpsight-listing-archive' ); ?> itemscope itemtype="http://schema.org/Product">

	<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
	
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">

		<?php do_action( 'wpsight_listing_archive_before', $post ); ?>
		
		<div class="row">
		
			<div class="wpsight-listing-left 4u 12u$(medium)">
			
				<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
			
			</div>
			
			<div class="wpsight-listing-right 8u 12u$(medium)">
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-description.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-meta.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
			
			</div>
		
		</div>
		
		<?php do_action( 'wpsight_listing_archive_after', $post ); ?>
	
	</div>

</div><!-- #listing-<?php the_ID(); ?> -->