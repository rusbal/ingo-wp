<div class="wpsight-listing-section wpsight-listing-section-title">
	
	<?php do_action( 'wpsight_listing_archive_title_before' ); ?>

	<div class="wpsight-listing-title">
	    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	    <?php wpsight_get_template( 'listing-archive-meta.php' ); ?>
	</div>
	
	<?php do_action( 'wpsight_listing_archive_title_after' ); ?>

</div><!-- .wpsight-listing-section-title -->