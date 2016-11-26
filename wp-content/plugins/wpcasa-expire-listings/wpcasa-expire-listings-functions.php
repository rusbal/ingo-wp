<?php
/**
 * wpsight_set_listing_expiry()
 *
 * Set an expiry date (custom field '_listing_expires')
 * for a specific listing.
 *
 * @param int|object $post Post ID or object (defaults to null => current post)
 * @param int $days Number of days for the listing duration
 * @param bool $reset If true resets expiry even if already exists
 * @uses get_post()
 * @uses get_post_meta()
 * @uses wpsight_get_option()
 * @uses strtotime()
 * @uses current_time()
 * @uses update_post_meta()
 *
 * This function is attached to different
 * post status transition hooks.
 *
 * @see /includes/class-wpsight-post-types.php
 * @see https://codex.wordpress.org/Post_Status_Transitions
 *
 * @since 1.0.0
 */

function wpsight_set_listing_expiry( $post = null, $days = '', $reset = false ) {
	global $pagenow;
	
	// Get post
	$post = get_post( $post );
	
	// If reset, remove date

	if( $reset === true )
		update_post_meta( $post->ID, '_listing_expires', '' );

	// See if expiry date already exists
	$expires = get_post_meta( $post->ID, '_listing_expires', true );

	// Stop if expiry already set and no reset
	
	if ( ! empty( $expires ) )
		return;

	// Get duration from the listing if set
	$duration = ! empty( $days ) ? absint( $days ) : wpsight_get_listing_duration( $post->ID );
	
	// If we have a duration, set an expiry date

	if ( $duration ) {
		
		// Get expiry date
		$expires = strtotime( "+{$duration} days", current_time( 'timestamp' ) );
		
		// Format date on listing editor page
		
		if( $pagenow == 'post.php' )
			$expires = date( 'Y-m-d', $expires );

		// Set post meta
		update_post_meta( $post->ID, '_listing_expires', $expires );

		// In case we are saving a post, ensure post data is updated so the field is not overridden

		if ( isset( $_POST['_listing_expires'] ) )
			$_POST['_listing_expires'] = $expires;

	} else {
		
		// If no duraction, leave expiry date emtpy
		update_post_meta( $post->ID, '_listing_expires', '' );

	}

}

/**
 * wpsight_get_listing_duration()
 *
 * Get the number of days a listing is supposed to
 * be active. Duration can be set by WPSight option
 * 'listing_duration' or individually by post meta
 * '_listing_duration'.
 *
 * @param integer|object Post ID or object
 * @return interger $duration Number of days or 0 (zero) to never expire
 *
 * @since 1.0.0
 */

function wpsight_get_listing_duration( $post = null ) {
	
	// Get post
	$post = get_post( $post );
	
	// Get duration from the listing if set
	$duration = get_post_meta( $post->ID, '_listing_duration', true );
	
	// If no listing duration, check global settings option
	
	if ( ! $duration )
		$duration = wpsight_get_option( 'listing_duration' );
	
	return apply_filters( 'wpsight_get_listing_duration', absint( $duration ), $post );
	
}

/**
 * wpsight_get_expired_listings()
 *
 * Get post IDs of listings where post meta _listing_expires
 * has a value and a date that is post current date.
 *
 * @uses wpsight_get_listings()
 * @return array|bool Array of post IDs or false if no result
 *
 * @since 1.0.0
 */

function wpsight_get_expired_listings() {
	
	$args = array(
		'post_status' 	 => 'expired',
		'orderby' 		 => 'meta_value_num',
		'meta_key' 	 	 => '_listing_expires',
		'posts_per_page' => -1
	);
	
	$args = apply_filters( 'wpsight_get_expired_listings_args', $args );
	
	$listings = wpsight_get_listings( $args );
	
	$result = false;
	
	if( ! empty( $listings->posts ) )
		$result = wp_list_pluck( $listings->posts, 'ID' );
	
	return apply_filters( 'wpsight_get_expired_listings', $result, $args, $listings );
	
}

/**
 * wpsight_get_published_expired_listings()
 *
 * Get post IDs of listings where post meta _listing_expires
 * has a value and a date that is post current date and
 * the post status is still set to publish.
 *
 * @uses $wpdb->prepare()
 * @uses $wpdb->get_col()
 * @uses current_time()
 *
 * @return array|bool Array of post IDs or false if no result
 *
 * @since 1.0.0
 */

function wpsight_get_published_expired_listings() {	
	global $wpdb;

	// Get all post IDs with 
	
	$post_ids = $wpdb->get_col( $wpdb->prepare( "
		SELECT postmeta.post_id FROM {$wpdb->postmeta} as postmeta
		LEFT JOIN {$wpdb->posts} as posts ON postmeta.post_id = posts.ID
		WHERE postmeta.meta_key = '_listing_expires'
		AND postmeta.meta_value > 0
		AND postmeta.meta_value < %s
		AND posts.post_status = 'publish'
		AND posts.post_type = 'listing'
	", current_time( 'timestamp' ) ) );
	
	// Set result if IDs, else false
	$result = $post_ids ? $post_ids : false;
	
	return apply_filters( 'wpsight_get_published_expired_listings', $result, $post_ids );
	
}

/**
 * wpsight_set_expired_listings()
 *
 * Set listing post_status with expiry
 * past current date to 'expired'.
 *
 * @uses wpsight_get_expired_listings()
 * @uses wp_update_post()
 *
 * @return array|bool Array of post IDs, false if no expired listings
 *
 * ##### FUNCTION CALLED BY CRON ####
 * @see /includes/class-wpsight-post-types.php
 *
 * @since 1.0.0
 */

function wpsight_set_expired_listings() {
	
	// Get listings with expiry past current date
	$post_ids = wpsight_get_published_expired_listings();
	
	if ( $post_ids ) {
		
		// When expired listings, upate status
		
		foreach ( $post_ids as $post_id ) {
			
			// Set up post data

			$listing_data = array(
				'ID' 		  => $post_id,
				'post_status' => 'expired'
			);
			
			wp_update_post( $listing_data );

		}
		
		return $post_ids;

	}
	
	return false;
	
}

/**
 * wpsight_delete_expired_listings()
 *
 * Move expired listings to trash if number of days
 * have passed after last modification.
 *
 * @param int $days Number of days after that listings are trashed
 * @uses $wpdb->prepare()
 * @uses $wpdb->get_col()
 * @uses current_time()
 * @uses strtotime()
 * @uses date()
 * @uses wp_trash_post()
 *
 * @return array|bool Array of post IDs, false if no listings trashed
 *
 * ##### FUNCTION CALLED BY CRON ####
 * @see /includes/class-wpsight-post-types.php
 *
 * @since 1.0.0
 */

function wpsight_delete_expired_listings( $days = '' ) {
	global $wpdb;
	
	// Set number of days
	$max = ! empty( $days ) ? $days : apply_filters( 'wpsight_delete_expired_listings_days', 30 );
	
	// Make sure $max is positive integer
	$max = absint( $max );
	
	// Get old expired listings

	$post_ids = $wpdb->get_col( $wpdb->prepare( "
		SELECT posts.ID FROM {$wpdb->posts} as posts
		WHERE posts.post_type = 'listing'
		AND posts.post_modified < %s
		AND posts.post_status = 'expired'
	", date( 'Y-m-d', strtotime( '-' . $max . ' days', current_time( 'timestamp' ) ) ) ) );
	
	if ( $post_ids ) {
		
		// If any, move to trash

		foreach ( $post_ids as $post_id )
			wp_trash_post( $post_id );
		
		return $post_ids;

	}
	
	return false;
	
}
