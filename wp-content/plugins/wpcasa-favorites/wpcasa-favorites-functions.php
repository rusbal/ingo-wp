<?php
/**
 *	wpsight_favorites()
 *	
 *	Output formatted listing favorites.
 *	
 *	@param	array	$args	Arguments for wpsight_listings()
 *	@uses	wpsight_get_favorites()
 *	@uses	wpsight_listings()
 *	
 *	@since 1.0.0
 */
function wpsight_favorites( $args = array() ) {
	
	// Parse $args and favorites
	$args = wp_parse_args( $args, wpsight_get_favorites()->query_vars );
	
	// Display favorite listings
	wpsight_listings( $args, WPSIGHT_FAVORITES_PLUGIN_DIR . '/templates/' );

}

/**
 *	wpsight_get_favorites()
 *	
 *	Get favorites listings query based on user cookie.
 *	
 *	@uses	wpsight_get_listings()
 *	@return	object	$favorites	WP_Query object with favorite listings
 *	
 *	@since 1.0.0
 */
function wpsight_get_favorites() {
	
	// Get listing IDs stored in cookie
    $favorites_cookie = ( isset( $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) && ! empty( $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) ) ? explode( ',', $_COOKIE[WPSIGHT_FAVORITES_COOKIE] ) : false;
	
	// If cookie contains data, get query
	$favorites = is_array( $favorites_cookie ) ? wpsight_get_listings( array( 'post__in' => $favorites_cookie ) ) : wpsight_get_listings( array( 'post__in' => array( 0 ) ) );
	
	return apply_filters( 'wpsight_get_favorites', $favorites, $favorites_cookie );
	
}

/**
 *	wpsight_favorites_link()
 *	
 *	Output favorites link (add & see).
 *	
 *	@param	$post_id			Post ID of the corresponding listing
 *	@param	$label_add			Link label for add favorite link
 *	@param	$label_see			Link label for see favorites link
 *	@param	$favorites_page_id 	Page ID of the fvorites page
 *	@uses	wpsight_get_favorites_link()
 *	
 *	@since 1.0.0
 */
function wpsight_favorites_link( $post_id = '', $label_add = '', $label_see = '', $favorites_page_id = '' ) {
	echo wpsight_get_favorites_link( $post_id, $label_add, $label_see, $favorites_page_id );
}

/**
 *	wpsight_get_favorites_link()
 *	
 *	Get favorites link (add & see).
 *	
 *	@param	$post_id 			Post ID of the corresponding listing (defaults to global post ID)
 *	@param	$label_add			Link label for add favorite link (defaults to option 'favorites_add')
 *	@param	$label_see			Link label for see favorites link (defaults to option 'favorites_see')
 *	@param	$favorites_page_id	Page ID of the fvorites page (defaults to option 'favorites_page')
 *	@uses	wpsight_get_option()
 *	
 *	@since 1.0.0
 */
function wpsight_get_favorites_link( $post_id = '', $label_add = '', $label_see = '', $favorites_page_id = '' ) {
	
	// Use global post ID if not defined
    
    if( ! $post_id )
        $post_id = get_the_ID();
    
    // Set label add
    $favorites_add = empty( $label_add ) ? wpsight_get_option( 'favorites_add' ) : $label_add;
    
    // Set label see
    $favorites_see = empty( $label_see ) ? wpsight_get_option( 'favorites_see' ) : $label_see;
    
    // Set permalink
    $favorites_permalink = empty( $favorites_page_id ) ? wpsight_get_option( 'favorites_page' ) : $favorites_page_id;
    
    // Build favorite add link    
    $favorites_link = sprintf( '<a href="" class="favorites-add" data-favorite="%1$s">%2$s</a>', absint( $post_id ), esc_html__( $favorites_add, 'wpcasa-favorites' ) );
    
    // Build favorite see link
    $favorites_link .= sprintf( '<a href="%1$s" class="favorites-see" style="display:none">%2$s</a>', esc_url( get_permalink( absint( $favorites_permalink ) ) ), esc_html__( $favorites_see, 'wpcasa-favorites' ) );
    
    // Apply filter and return
    return apply_filters( 'wpsight_get_favorites_link', $favorites_link, $post_id, $label_add, $label_see, $favorites_page_id );
	
}

/**
 *	Callback for listing title actions favorite button.
 *	
 *	@param	array	$data	Array of data from wpcasa-favorites.php
 *	@uses	wpsight_get_favorites_link()
 *	@return	Favorites link markup
 *	
 *	@since 1.0.0
 */
function wpsight_listing_actions_favorite( $data ) {
	
	if( 'publish' == get_post_status( $data['post_id'] ) )
		return wpsight_get_favorites_link( $data['post_id'] );
	
	return false;

}

/**
 *	Callback for listing title actions compare button.
 *	
 *	@param	array	$data	Array of data from wpcasa-favorites.php
 *	@uses	wpsight_get_favorites_link()
 *	@return Favorites link markup
 *	
 *	@since 1.0.0
 */
function wpsight_panel_actions_compare( $data ) {
	
	$output = false;
	
	$page_id = wpsight_get_option( 'favorites_page' );
	
	if( $page_id && is_page( $page_id ) )
		$output= sprintf( '<a href="" class="favorites-compare" title="%s">%s</a>', _x( 'Compare', 'favorites page compare', 'wpcasa-favorites' ) );
	
	return $output;
	
}
