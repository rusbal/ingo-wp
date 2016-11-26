<div class="wpsight-listing-section wpsight-listing-section-meta wpsight-listing-section-compare-fade">
	
	<?php do_action( 'wpsight_listing_archive_meta_before' ); ?>

	<div class="wpsight-listing-meta clearfix">
	
		
		<?php wpsight_listing_terms( 'location', get_the_id(), ' &rsaquo; ' ); ?>
		
		<?php if( wpsight_get_listing_terms( 'location' ) && wpsight_get_listing_terms( 'listing-type' ) )	: ?>/<?php endif; ?>
		
		<?php wpsight_listing_terms( 'listing-type', get_the_id(), ', ' ); ?>		
	
	</div>
	
	<?php do_action( 'wpsight_listing_archive_meta_after' ); ?>

</div><!-- .wpsight-listing-section -->