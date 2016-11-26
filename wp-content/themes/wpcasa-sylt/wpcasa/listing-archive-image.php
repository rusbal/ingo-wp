<meta itemprop="image" content="<?php echo esc_attr( wpsight_get_listing_thumbnail_url( get_the_id(), 'wpsight-large' ) ); ?>" />

<div class="wpsight-listing-section wpsight-listing-section-image">
	
	<?php do_action( 'wpsight_listing_archive_image_before' ); ?>
	
	<?php $image_size = isset( $args['slider_full_width'] ) && $args['slider_full_width'] === true ? 'wpsight-slider' : 'post-thumbnail'; ?>

	<div class="wpsight-listing-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php wpsight_listing_thumbnail( get_the_id(), $image_size, array( 'title' => '' ) ); ?>
		</a>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_image_after' ); ?>

</div><!-- .wpsight-listing-section -->