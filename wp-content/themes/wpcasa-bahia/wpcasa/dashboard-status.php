<fieldset>
	<label><?php _e( 'Status', 'wpsight-dashboard' ); ?></label>
	<div class="field listing-status">
		<?php printf( __( 'Current listing status is <em>%s</em>.', 'wpsight-dashboard' ), wpsight_get_status( $listing->post_status ) ); ?>

		<?php if( 'publish' == $listing->post_status ) : ?>
		<a href="<?php echo esc_url( get_permalink( $listing->ID ) ); ?>"><?php _e( 'View Listing', 'wpsight-dashboard' ); ?></a>
		<?php endif; ?>

	</div>
</fieldset>