<div class="listing-<?php the_ID(); ?>-carousel-wrap listing-carousel-wrap">

	<div id="listing-<?php the_ID(); ?>-carousel" <?php wpsight_listing_class( 'wpsight-listing-carousel equal' ); ?> itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php echo esc_attr( $post->post_title ); ?>" />
		
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="clearfix">
	
			<?php do_action( 'wpsight_listing_carousel_before', $post ); ?>
				
			<div class="listing-top">
			
				<?php wpsight_get_template( 'listing-archive-image.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-meta.php' ); ?>
			
			</div>
			
			<div class="listing-bottom">
			
				<?php wpsight_get_template( 'listing-archive-title.php' ); ?>
						
				<?php wpsight_get_template( 'listing-archive-info.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-summary.php' ); ?>
				
				<?php wpsight_get_template( 'listing-archive-compare.php' ); ?>
			
			</div>
			
			<?php do_action( 'wpsight_listing_carousel_after', $post ); ?>
		
		</div>
	
	</div><!-- #listing-<?php the_ID(); ?>-carousel -->

</div>