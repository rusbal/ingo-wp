<?php
/**
 *	wpsight_bahia_admin_meta_box_css()
 *
 *	Enqueue meta box CSS on corresponding admin pages.
 *
 *	@uses	get_current_screen()
 *	@uses	wp_enqueue_style()
 *	@uses	get_template_directory_uri()
 *	@uses	get_post_meta()
 *	@uses	get_the_id()
 *
 *	@since 1.0.0
 */
add_action( 'admin_enqueue_scripts', 'wpsight_bahia_admin_meta_box_css', 20 );

function wpsight_bahia_admin_meta_box_css() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	$screen		= get_current_screen();
	$post_type	= 'page';
	$template	= get_post_meta( get_the_id(), '_wp_page_template', true );

	if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) && 'page-tpl-home.php' == $template )
		wp_enqueue_style( 'wpsight-bahia-meta-boxes', get_template_directory_uri() . '/assets/css/wpsight-meta-boxes' . $suffix . '.css' );

}

/**
 *	wpsight_bahia_meta_boxes_home()
 *
 *	Combine Bahia meta boxes and add
 *	to general WPCasa meta boxes array.
 *
 *	@uses	wpsight_bahia_meta_boxes_home_slider()
 *	@uses	wpsight_bahia_meta_boxes_home_cta_1()
 *	@uses	wpsight_bahia_meta_boxes_home_cta_2()
 *	@uses	wpsight_bahia_meta_boxes_home_carousel()
 *	@return	array	$meta_boxes	Array of meta boxes
 *
 *	@since 1.0.0
 */
add_filter( 'wpsight_meta_boxes', 'wpsight_bahia_meta_boxes_home' );

function wpsight_bahia_meta_boxes_home( $meta_boxes ) {
	
	$meta_boxes['home_icon_links']	= wpsight_bahia_meta_boxes_home_icon_links();
	$meta_boxes['home_carousel']	= wpsight_bahia_meta_boxes_home_carousel();
	$meta_boxes['home_cta_1']		= wpsight_bahia_meta_boxes_home_cta_1();
	$meta_boxes['home_listings']	= wpsight_bahia_meta_boxes_home_listings();
	$meta_boxes['home_cta_2']		= wpsight_bahia_meta_boxes_home_cta_2();
	
	return $meta_boxes;
	
}

/**
 *	wpsight_bahia_meta_boxes_home_icon_links()
 *
 *	Set up home icon links meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_bahia_meta_boxes_home_icon_links() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_icon_links_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-bahia' ),
			'desc'      => __( 'Display icon links on the front page', 'wpcasa-bahia' ),
			'priority'  => 10
		),
		'_icon_links' => array(
			'name'      	=> __( 'Icon Links', 'wpcasa-bahia' ),
			'id'        	=> '_icon_links',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'link_label' => array(
					'name'		=> __( 'Link Label', 'wpcasa-bahia' ),
					'id'		=> '_icon_link_label',
					'type'		=> 'text',
					'desc'		=> __( 'Add the main link label here', 'wpcasa-bahia' )
				),
				'link_icon' => array(
					'name'		=> __( 'Link Icon', 'wpcasa-bahia' ),
					'id'		=> '_icon_link_icon',
					'type'		=> 'text',
					'desc'		=> __( 'Enter <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a> icon class', 'wpcasa-bahia' ),
					'attributes'	=> array(
						'placeholder'	=> 'fa-check'
					)
				),
				'link_description' => array(
					'name'		=> __( 'Link Description', 'wpcasa-bahia' ),
					'id'		=> '_icon_link_desc',
					'type'		=> 'text',
					'desc'		=> __( 'Add the link description here', 'wpcasa-bahia' )
				),
				'link_url' => array(
					'name'		=> __( 'Link URL', 'wpcasa-bahia' ),
					'id'		=> '_icon_link_url',
					'type'		=> 'text_url',
					'desc'		=> __( 'Add the link URL here', 'wpcasa-bahia' )
				)
			),
			'description' 	=> __( 'Create icon links displayed in a bar', 'wpcasa-bahia' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Icon Link {#}', 'wpcasa-bahia' ),
			    'add_button'    => __( 'Add Another Icon Link', 'wpcasa-bahia' ),
			    'remove_button' => __( 'Remove Icon Link', 'wpcasa-bahia' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'	=> 20
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_bahia_meta_boxes_home_icon_links_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_icon_links',
		'title'			=> WPSIGHT_BAHIA_NAME . ' :: ' . __( 'Home Icon Links', 'wpcasa-bahia' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_bahia_meta_boxes_home_icon_links', $meta_box );

}

/**
 *	wpsight_bahia_meta_boxes_home_carousel()
 *
 *	Set up home carousel meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_bahia_checkbox_default()
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_bahia_meta_boxes_home_carousel() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = get_object_taxonomies( wpsight_post_type(), 'objects' );
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_carousel_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-bahia' ),
			'desc'      => __( 'Display carousel on the front page', 'wpcasa-bahia' ),
			'priority'  => 10
		),
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-bahia' ),
			'id'        => '_carousel_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-bahia' ),
			'default'	=> 6,
			'priority'  => 20
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-bahia' ) . ' ' . __( 'Offer', 'wpcasa-bahia' ),
			'id'        => '_carousel_offer',
			'type'      => 'select',
			'desc'      => '',
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-bahia' ) ), $offers ),
			'priority'  => 30
		),
		'items' => array(
			'name'      => __( 'Items', 'wpcasa-bahia' ),
			'id'        => '_carousel_items',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be visible', 'wpcasa-bahia' ),
			'default'	=> 3,
			'priority'  => 40
		),
		'slide_by' => array(
			'name'      => __( 'Slide by', 'wpcasa-bahia' ),
			'id'        => '_carousel_slide_by',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to move in one slide', 'wpcasa-bahia' ),
			'default'	=> 3,
			'priority'  => 50
		),
		'margin' => array(
			'name'      => __( 'Margin', 'wpcasa-bahia' ),
			'id'        => '_carousel_margin',
			'type'      => 'text',
			'desc'      => __( 'Please enter the space between two listings in px', 'wpcasa-bahia' ),
			'default'	=> 40,
			'priority'  => 60
		),
		'padding' => array(
			'name'      => __( 'Padding', 'wpcasa-bahia' ),
			'id'        => '_carousel_padding',
			'type'      => 'text',
			'desc'      => __( 'Please enter the space one the left and right side of the stage', 'wpcasa-bahia' ),
			'default'	=> 0,
			'priority'  => 70
		),
		'loop' => array(
			'name'      => '',
			'id'        => '_carousel_loop',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Loop', 'wpcasa-bahia' ),
			'desc'      => __( 'Loop slider to be infinite', 'wpcasa-bahia' ),
			'default'	=> wpsight_bahia_checkbox_default( true ),
			'priority'  => 80
		),
		'nav' => array(
			'name'      => '',
			'id'        => '_carousel_nav',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel nav', 'wpcasa-bahia' ),
			'desc'      => __( 'Show prev/next carousel navigation', 'wpcasa-bahia' ),
			'default'	=> wpsight_bahia_checkbox_default( true ),
			'priority'  => 90
		),
		'dots' => array(
			'name'      => '',
			'id'        => '_carousel_dots',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel dots', 'wpcasa-bahia' ),
			'desc'      => __( 'Show <em>dots</em> carousel navigation', 'wpcasa-bahia' ),
			'priority'  => 100
		),
		'autoplay' => array(
			'name'      => '',
			'id'        => '_carousel_autoplay',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel autoplay', 'wpcasa-bahia' ),
			'desc'      => __( 'Slide through items automatically', 'wpcasa-bahia' ),
			'priority'  => 110
		),
		'autoplay_time' => array(
			'name'      => 'Autoplay interval',
			'id'        => '_carousel_autoplay_time',
			'type'      => 'select',
			'options'	=> array(
				'2000'	=> '2 ' . _x( 'seconds', 'listing widget', 'wpcasa-bahia' ),
				'4000'	=> '4 ' . _x( 'seconds', 'listing widget', 'wpcasa-bahia' ),
				'6000'	=> '6 ' . _x( 'seconds', 'listing widget', 'wpcasa-bahia' ),
				'8000'	=> '8 ' . _x( 'seconds', 'listing widget', 'wpcasa-bahia' ),
				'10000'	=> '10 ' . _x( 'seconds', 'listing widget', 'wpcasa-bahia' ),
			),
			'desc'		=> __( 'Please select the autoplay interval timeout', 'wpcasa-bahia' ),
			'default'	=> 6000,
			'priority'  => 120
		)
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-bahia' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-bahia' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_carousel_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 40
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_bahia_meta_boxes_home_carousel_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_carousel',
		'title'			=> WPSIGHT_BAHIA_NAME . ' :: ' . __( 'Home Carousel', 'wpcasa-bahia' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_bahia_meta_boxes_home_carousel', $meta_box );
	
}

/**
 *	wpsight_bahia_meta_boxes_home_cta_1()
 *
 *	Set up home call to action #1 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_bahia_meta_boxes_home_cta_1() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_1_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-bahia' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-bahia' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-bahia' ),
			'id'        => '_cta_1_title',
			'type'      => 'text',
			'desc'      => __( 'Please enter the title', 'wpcasa-bahia' ),
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-bahia' ),
			'id'        => '_cta_1_description',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the description', 'wpcasa-bahia' ),
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-bahia' ),
			'id'        => '_cta_1_button_label',
			'type'      => 'text',
			'desc'      => __( 'Please enter the button label', 'wpcasa-bahia' ),
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-bahia' ),
			'id'        => '_cta_1_button_url',
			'type'      => 'text_url',
			'desc'      => __( 'Please enter the URL', 'wpcasa-bahia' ),
			'priority'  => 50
		)
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_bahia_meta_boxes_home_cta_1_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_1',
		'title'			=> WPSIGHT_BAHIA_NAME . ' :: ' . __( 'Home Call to Action #1', 'wpcasa-bahia' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_bahia_meta_boxes_home_cta_1', $meta_box );
	
}

/**
 *	wpsight_bahia_meta_boxes_home_listings()
 *
 *	Set up home listings meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_sort_array_by_priority()
 *
 *	@since 1.0.0
 */
function wpsight_bahia_meta_boxes_home_listings() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = get_object_taxonomies( wpsight_post_type(), 'objects' );
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_listings_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-bahia' ),
			'desc'      => __( 'Display listings on the front page', 'wpcasa-bahia' ),
			'priority'  => 10
		),
		'queries' => array(
			'name'      	=> __( 'Listings Query', 'wpcasa-bahia' ),
			'id'        	=> '_listings',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'title'	=> array(
					'name'      => __( 'Section Title', 'wpcasa-bahia' ),
					'id'        => '_listings_title',
					'type'      => 'text',
					'desc'      => __( 'Please enter the title', 'wpcasa-bahia' )
				),
				'label' => array(
					'name'      => __( 'Button Label', 'wpcasa-bahia' ),
					'id'        => '_listings_button_label',
					'type'      => 'text',
					'desc'      => __( 'Please enter the button label', 'wpcasa-bahia' ),
					'default'   => __( 'View All', 'wpcasa-bahia' )
				),
				'url' => array(
					'name'      => __( 'Button URL', 'wpcasa-bahia' ),
					'id'        => '_listings_button_url',
					'type'      => 'text_url',
					'desc'      => __( 'Please enter the button URL', 'wpcasa-bahia' )
				),
				'nr' => array(
					'name'      => __( 'Listings', 'wpcasa-bahia' ),
					'id'        => '_listings_nr',
					'type'      => 'text',
					'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-bahia' ),
					'default'	=> 6
				),
				'offer' => array(
					'name'      => __( 'Filter by', 'wpcasa-bahia' ) . ' ' . __( 'Offer', 'wpcasa-bahia' ),
					'id'        => '_listings_offer',
					'type'      => 'select',
					'desc'      => '',
					'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-bahia' ) ), $offers )
				)
			),
			'description' 	=> __( 'Create listing queries for the home page', 'wpcasa-bahia' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Listings Query {#}', 'wpcasa-bahia' ),
			    'add_button'    => __( 'Add Another Listings Query', 'wpcasa-bahia' ),
			    'remove_button' => __( 'Remove Listings Query', 'wpcasa-bahia' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'  => 20
		)
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		if( 'language' == $taxonomy->name || 'post_translations' == $taxonomy->name )
			continue;
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-bahia' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields['queries']['group_fields'][ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-bahia' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_taxonomy_filter_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select'
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_bahia_meta_boxes_home_listings_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_listings',
		'title'			=> WPSIGHT_BAHIA_NAME . ' :: ' . __( 'Home Listings', 'wpcasa-bahia' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_bahia_meta_boxes_home_listings', $meta_box );

}

/**
 *	wpsight_bahia_meta_boxes_home_cta_2()
 *
 *	Set up home call to action #2 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_bahia_meta_boxes_home_cta_2() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_2_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-bahia' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-bahia' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-bahia' ),
			'id'        => '_cta_2_title',
			'type'      => 'text',
			'desc'      => __( 'Please enter the title', 'wpcasa-bahia' ),
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-bahia' ),
			'id'        => '_cta_2_description',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the description', 'wpcasa-bahia' ),
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-bahia' ),
			'id'        => '_cta_2_button_label',
			'type'      => 'text',
			'desc'      => __( 'Please enter the button label', 'wpcasa-bahia' ),
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-bahia' ),
			'id'        => '_cta_2_button_url',
			'type'      => 'text_url',
			'desc'      => __( 'Please enter the URL', 'wpcasa-bahia' ),
			'priority'  => 50
		)
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_bahia_meta_boxes_home_cta_2_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_2',
		'title'			=> WPSIGHT_BAHIA_NAME . ' :: ' . __( 'Home Call to Action #2', 'wpcasa-bahia' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_bahia_meta_boxes_home_cta_2', $meta_box );	
	
}

/**
 *	wpsight_bahia_checkbox_default()
 *	
 *	Helper function to set check box defaults.
 *	Only return default value if we don't
 *	have a post ID (in the 'post' query variable).
 *
 *	@param	bool	$default On/Off (true/false)
 *	@return	mixed	Returns true or '', the blank default
 */
function wpsight_bahia_checkbox_default( $default ) {
    return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}
