<div class="wpsight-listing-section wpsight-listing-section-description">
	
	<?php do_action( 'wpsight_listing_archive_description_before' ); ?>
	
	<?php if( wpsight_is_listing_not_available() ) : ?>
		<div class="wpsight-alert wpsight-alert-small wpsight-alert-not-available">
			<?php _e( 'This property is currently not available.', 'wpsight' ); ?>
		</div>
	<?php endif; ?>

	<div class="wpsight-listing-description" itemprop="description">
		<?php wpsight_stage_the_excerpt( get_the_ID(), true, apply_filters( 'wpsight_stage_excerpt_length', 25 ) ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_description_after' ); ?>

</div><!-- .wpsight-listing-section -->