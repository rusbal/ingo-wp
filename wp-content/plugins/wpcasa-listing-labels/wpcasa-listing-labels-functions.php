<?php
/**
 *	wpsight_listing_labels()
 *	
 *	Function that defines the array
 *	of listing labels (new, reduced etc.)
 *	
 *	@uses	wpsight_sort_array_by_position()
 *	@return	array	Default listing labels
 *	
 *	@since 1.0.0
 */
function wpsight_listing_labels() {

	// Set listing labels

	$labels = array(
    	
    	'labels_1' => array(
    		'label'		=> __( 'New', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-1',
    		'position'	=> 10
    	),
    	'labels_2' => array(
    		'label'		=> __( 'Reduced', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-2',
    		'position'	=> 20
    	),
    	'labels_3' => array(
    		'label'		=> __( 'Reserved', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-3',
    		'position'	=> 30
    	),
    	'labels_4' => array(
    		'label'		=> __( 'Featured', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-4',
    		'position'	=> 40
    	),
    	'labels_5' => array(
    		'label'		=> __( 'Off Market', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-5',
    		'position'	=> 50
    	),
    	'labels_6' => array(
    		'label'		=> __( 'Investment', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-6',
    		'position'	=> 60
    	),
    	'labels_7' => array(
    		'label'		=> __( 'On Show', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-7',
    		'position'	=> 70
    	),
    	'labels_8' => array(
    		'label'		=> __( 'Exclusive', 'wpsight' ),
    		'css_class'	=> 'wpsight-label-8',
    		'position'	=> 80
    	)
    	
    );
    
    // Apply filter to array    
    $labels = apply_filters( 'wpsight_listing_labels', $labels );
    
    // Sort array by position        
    $labels = wpsight_sort_array_by_position( $labels );
	
	// Return array    
    return $labels;

}

/**
 *	wpsight_get_label()
 *	
 *	Get a specific label defined
 *	in wpsight_listing_labels().
 *	
 *	@param	string	$label		Key of the corresponding label
 *	@param	string	$element	Element to return (label, css_class or key)
 *	@uses 	wpsight_listing_labels()
 *	@return string|array 		String or array if key is requested
 *	
 *	@since 1.0.0
 */
function wpsight_get_label( $label = '', $element = 'label' ) {
	
	if( empty( $label ) )
		return;
	
	$labels = wpsight_listing_labels();
	
	if( isset( $labels[ $label ] ) ) {		
		if( $element == 'label' || $element == 'css_class' )
			return $labels[ $label ][ $element ];		
		if( $element == 'key' )
			return $labels[ $label ];		
	}
	
	return false;

}

/**
 *	wpsight_get_listing_label()
 *	
 *	Get specific listing label set
 *	in post meta.
 *	
 *	@param	integer	$post_id	Post ID
 *	@uses	get_post_meta()
 *	@return string	Value of the _listing_label post meta custom field
 *	
 *	@since 1.0.0
 */
function wpsight_get_listing_label( $post_id = '' ) {
	
	// Use global post ID if not defined

	if ( ! $post_id )
		$post_id = get_the_ID();

	// Get listing label
	$listing_label = get_post_meta( $post_id, '_listing_label', true );
	
	return apply_filters( 'wpsight_get_listing_label', $listing_label, $post_id ); 

}

/**
 *	wpsight_has_listing_label()
 *	
 *	Check if a specific listing has a label
 *	(custom field '_listing_label').
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing (defaults to current post)
 *	@uses	get_post_meta()
 *	@return bool	$result		True if _listing_label has value, else false
 *	
 *	@since 1.0.0
 */
function wpsight_has_listing_label( $post_id = '' ) {

	// Set default post ID

	if ( ! $post_id )
		$post_id = get_the_ID();

	// Get custom post meta and set result
	$result = get_post_meta( $post_id, '_listing_label', true ) ? true : false;

	return apply_filters( 'wpsight_has_listing_label', $result, $post_id );

}

/**
 *	wpsight_has_listing_label_is_featured()
 *	
 *	Check if a specific listing has a label
 *	(custom field '_listing_label').
 *	
 *	@param	integer	$post_id	Post ID of the corresponding listing (defaults to current post)
 *	@uses	wpsight_has_listing_label()
 *	@uses	wpsight_is_listing_featured()
 *	@uses	wpsight_get_option()
 *	@return bool	$result		True if _listing_label has value, else false
 *	
 *	@since 1.0.0
 */
function wpsight_has_listing_label_is_featured( $post_id = '' ) {

	// Set default post ID

	if ( ! $post_id )
		$post_id = get_the_ID();

	// Get custom post meta and set result
	$result = ( wpsight_has_listing_label( $post_id ) && wpsight_is_listing_featured( $post_id ) && ( wpsight_get_option( 'labels_type' ) == wpsight_get_option( 'featured_type' ) ) ) ? true : false;

	return apply_filters( 'wpsight_has_listing_label_is_featured', $result, $post_id );

}
