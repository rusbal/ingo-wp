<?php
/**
 *	wpsight_sylt_admin_meta_box_css()
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
add_action( 'admin_enqueue_scripts', 'wpsight_sylt_admin_meta_box_css', 20 );

function wpsight_sylt_admin_meta_box_css() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	$screen		= get_current_screen();
	$post_type	= 'page';
	$template	= get_post_meta( get_the_id(), '_wp_page_template', true );

	if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) && 'page-tpl-home.php' == $template )
		wp_enqueue_style( 'wpsight-sylt-meta-boxes', get_template_directory_uri() . '/assets/css/wpsight-meta-boxes' . $suffix . '.css' );

}

/**
 *	wpsight_sylt_meta_boxes_home()
 *
 *	Combine Sylt meta boxes and add
 *	to general WPCasa meta boxes array.
 *
 *	@uses	wpsight_sylt_meta_boxes_home_slider()
 *	@uses	wpsight_sylt_meta_boxes_home_slider()
 *	@uses	wpsight_sylt_meta_boxes_home_cta_1()
 *	@uses	wpsight_sylt_meta_boxes_home_cta_2()
 *	@uses	wpsight_sylt_meta_boxes_home_carousel()
 *	@return	array	$meta_boxes	Array of meta boxes
 *
 *	@since 1.0.0
 */
add_filter( 'wpsight_meta_boxes', 'wpsight_sylt_meta_boxes_home' );

function wpsight_sylt_meta_boxes_home( $meta_boxes ) {
	
	$meta_boxes['home_slider']		= wpsight_sylt_meta_boxes_home_slider();
	$meta_boxes['home_icon_links']	= wpsight_sylt_meta_boxes_home_icon_links();
	$meta_boxes['home_carousel']	= wpsight_sylt_meta_boxes_home_carousel();
	$meta_boxes['home_cta_1']		= wpsight_sylt_meta_boxes_home_cta_1();
	$meta_boxes['home_listings']	= wpsight_sylt_meta_boxes_home_listings();
	$meta_boxes['home_cta_2']		= wpsight_sylt_meta_boxes_home_cta_2();
	
	return $meta_boxes;
	
}

/**
 *	wpsight_sylt_meta_boxes_home_slider()
 *
 *	Set up home slider meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_sylt_checkbox_default()
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_sylt_meta_boxes_home_slider() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_slider_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display slider on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-sylt' ),
			'id'        => '_slider_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-sylt' ),
			'default'	=> 10,
			'priority'  => 20
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-sylt' ) . ' ' . __( 'Offer', 'wpcasa-sylt' ),
			'id'        => '_slider_offer',
			'type'      => 'select',
			'desc'      => '',
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-sylt' ) ), $offers ),
			'priority'  => 30
		),
		'loop' => array(
			'name'      => '',
			'id'        => '_slider_loop',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Loop', 'wpcasa-sylt' ),
			'desc'      => __( 'Loop slider to be infinite', 'wpcasa-sylt' ),
			'default'	=> wpsight_sylt_checkbox_default( true ),
			'priority'  => 50
		),
		'nav' => array(
			'name'      => '',
			'id'        => '_slider_nav',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider nav', 'wpcasa-sylt' ),
			'desc'      => __( 'Show prev/next slider navigation', 'wpcasa-sylt' ),
			'default'	=> wpsight_sylt_checkbox_default( true ),
			'priority'  => 60
		),
		'dots' => array(
			'name'      => '',
			'id'        => '_slider_dots',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider dots', 'wpcasa-sylt' ),
			'desc'      => __( 'Show <em>dots</em> slider navigation', 'wpcasa-sylt' ),
			'priority'  => 70
		),
		'autoplay' => array(
			'name'      => '',
			'id'        => '_slider_autoplay',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Slider autoplay', 'wpcasa-sylt' ),
			'desc'      => __( 'Slide through items automatically', 'wpcasa-sylt' ),
			'priority'  => 80
		),
		'autoplay_time' => array(
			'name'      => 'Autoplay interval',
			'id'        => '_slider_autoplay_time',
			'type'      => 'select',
			'options'	=> array(
				'2000'	=> '2 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'4000'	=> '4 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'6000'	=> '6 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'8000'	=> '8 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'10000'	=> '10 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
			),
			'desc'		=> __( 'Please select the autoplay interval timeout', 'wpcasa-sylt' ),
			'default'	=> 6000,
			'priority'  => 80
		)
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-sylt' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-sylt' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_slider_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 40
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_slider_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_slider',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Slider', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_slider', $meta_box );	
	
}

/**
 *	wpsight_sylt_meta_boxes_home_icon_links()
 *
 *	Set up home icon links meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_sylt_meta_boxes_home_icon_links() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_icon_links_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display icon links on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'_icon_links' => array(
			'name'      	=> __( 'Icon Links', 'wpcasa-sylt' ),
			'id'        	=> '_icon_links',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'link_label' => array(
					'name'		=> __( 'Link Label', 'wpcasa-sylt' ),
					'id'		=> '_icon_link_label',
					'type'		=> 'text',
					'desc'		=> __( 'Add the main link label here', 'wpcasa-sylt' )
				),
				'link_icon' => array(
					'name'		=> __( 'Link Icon', 'wpcasa-sylt' ),
					'id'		=> '_icon_link_icon',
					'type'		=> 'text',
					'desc'		=> __( 'Enter <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a> icon class', 'wpcasa-sylt' ),
					'attributes'	=> array(
						'placeholder'	=> 'fa-check'
					)
				),
				'link_description' => array(
					'name'		=> __( 'Link Description', 'wpcasa-sylt' ),
					'id'		=> '_icon_link_desc',
					'type'		=> 'text',
					'desc'		=> __( 'Add the link description here', 'wpcasa-sylt' )
				),
				'link_url' => array(
					'name'		=> __( 'Link URL', 'wpcasa-sylt' ),
					'id'		=> '_icon_link_url',
					'type'		=> 'text_url',
					'desc'		=> __( 'Add the link URL here', 'wpcasa-sylt' )
				)
			),
			'description' 	=> __( 'Create icon links displayed in a bar', 'wpcasa-sylt' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Icon Link {#}', 'wpcasa-sylt' ),
			    'add_button'    => __( 'Add Another Icon Link', 'wpcasa-sylt' ),
			    'remove_button' => __( 'Remove Icon Link', 'wpcasa-sylt' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'	=> 20
		)
	);
	
	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_icon_links_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_icon_links',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Icon Links', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_icon_links', $meta_box );

}

/**
 *	wpsight_sylt_meta_boxes_home_listings()
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
function wpsight_sylt_meta_boxes_home_listings() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_listings_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display listings on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'queries' => array(
			'name'      	=> __( 'Listings Query', 'wpcasa-sylt' ),
			'id'        	=> '_listings',
			'type'      	=> 'group',
			'group_fields'	=> array(
				'title'	=> array(
					'name'      => __( 'Section Title', 'wpcasa-sylt' ),
					'id'        => '_listings_title',
					'type'      => 'text',
					'desc'      => __( 'Please enter the title', 'wpcasa-sylt' )
				),
				'label' => array(
					'name'      => __( 'Button Label', 'wpcasa-sylt' ),
					'id'        => '_listings_button_label',
					'type'      => 'text',
					'desc'      => __( 'Please enter the button label', 'wpcasa-sylt' ),
					'default'   => __( 'View All', 'wpcasa-sylt' )
				),
				'url' => array(
					'name'      => __( 'Button URL', 'wpcasa-sylt' ),
					'id'        => '_listings_button_url',
					'type'      => 'text_url',
					'desc'      => __( 'Please enter the button URL', 'wpcasa-sylt' )
				),
				'nr' => array(
					'name'      => __( 'Listings', 'wpcasa-sylt' ),
					'id'        => '_listings_nr',
					'type'      => 'text',
					'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-sylt' ),
					'default'	=> 6
				),
				'offer' => array(
					'name'      => __( 'Filter by', 'wpcasa-sylt' ) . ' ' . __( 'Offer', 'wpcasa-sylt' ),
					'id'        => '_listings_offer',
					'type'      => 'select',
					'desc'      => '',
					'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-sylt' ) ), $offers )
				)
			),
			'description' 	=> __( 'Create listing queries for the home page', 'wpcasa-sylt' ),
			'repeatable'  	=> true,
			'options'     	=> array(
			    'group_title'   => __( 'Listings Query {#}', 'wpcasa-sylt' ),
			    'add_button'    => __( 'Add Another Listings Query', 'wpcasa-sylt' ),
			    'remove_button' => __( 'Remove Listings Query', 'wpcasa-sylt' ),
			    'sortable'      => true,
			    'closed'		=> true
			),
			'priority'  => 20
		)
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-sylt' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields['queries']['group_fields'][ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-sylt' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_listings_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select'
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_listings_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_listings',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Listings', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_listings', $meta_box );

}

/**
 *	wpsight_sylt_meta_boxes_home_cta_1()
 *
 *	Set up home call to action #1 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_sylt_meta_boxes_home_cta_1() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_1_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-sylt' ),
			'id'        => '_cta_1_title',
			'type'      => 'text',
			'desc'      => __( 'Please enter the title', 'wpcasa-sylt' ),
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-sylt' ),
			'id'        => '_cta_1_description',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the description', 'wpcasa-sylt' ),
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-sylt' ),
			'id'        => '_cta_1_button_label',
			'type'      => 'text',
			'desc'      => __( 'Please enter the button label', 'wpcasa-sylt' ),
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-sylt' ),
			'id'        => '_cta_1_button_url',
			'type'      => 'text_url',
			'desc'      => __( 'Please enter the URL', 'wpcasa-sylt' ),
			'priority'  => 50
		)
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_cta_1_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_1',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Call to Action #1', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_cta_1', $meta_box );
	
}

/**
 *	wpsight_sylt_meta_boxes_home_carousel()
 *
 *	Set up home carousel meta box.
 *
 *	@uses	wpsight_offers()
 *	@uses	wpsight_post_type()
 *	@uses	get_object_taxonomies()
 *	@uses	get_terms()
 *	@uses	wpsight_sylt_checkbox_default()
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_sylt_meta_boxes_home_carousel() {
	
	// Prepare offers
	$offers = wpsight_offers();
	
	// Prepare taxonomies
	$taxonomies = wpsight_taxonomies();
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_carousel_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display carousel on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'nr' => array(
			'name'      => __( 'Listings', 'wpcasa-sylt' ),
			'id'        => '_carousel_nr',
			'type'      => 'text',
			'desc'      => __( 'Please enter the number of listings to be displayed', 'wpcasa-sylt' ),
			'default'	=> 6,
			'priority'  => 20
		),
		'offer' => array(
			'name'      => __( 'Filter by', 'wpcasa-sylt' ) . ' ' . __( 'Offer', 'wpcasa-sylt' ),
			'id'        => '_carousel_offer',
			'type'      => 'select',
			'desc'      => '',
			'options'	=> array_merge( array( '' => __( 'None', 'wpcasa-sylt' ) ), $offers ),
			'priority'  => 30
		),
		'items' => array(
			'name'      => __( 'Items', 'wpcasa-sylt' ),
			'id'        => '_carousel_items',
			'type'      => 'hidden',
			'desc'      => __( 'Please enter the number of listings to be visible', 'wpcasa-sylt' ),
			'default'	=> 2,
			'priority'  => 40
		),
		'slide_by' => array(
			'name'      => __( 'Slide by', 'wpcasa-sylt' ),
			'id'        => '_carousel_slide_by',
			'type'      => 'hidden',
			'desc'      => __( 'Please enter the number of listings to move in one slide', 'wpcasa-sylt' ),
			'default'	=> 2,
			'priority'  => 50
		),
		'margin' => array(
			'name'      => __( 'Margin', 'wpcasa-sylt' ),
			'id'        => '_carousel_margin',
			'type'      => 'hidden',
			'desc'      => __( 'Please enter the space between two listings in px', 'wpcasa-sylt' ),
			'default'	=> 40,
			'priority'  => 60
		),
		'padding' => array(
			'name'      => __( 'Padding', 'wpcasa-sylt' ),
			'id'        => '_carousel_padding',
			'type'      => 'hidden',
			'desc'      => __( 'Please enter the space one the left and right side of the stage', 'wpcasa-sylt' ),
			'default'	=> 0,
			'priority'  => 70
		),
		'loop' => array(
			'name'      => '',
			'id'        => '_carousel_loop',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Loop', 'wpcasa-sylt' ),
			'desc'      => __( 'Loop slider to be infinite', 'wpcasa-sylt' ),
			'default'	=> wpsight_sylt_checkbox_default( true ),
			'priority'  => 80
		),
		'nav' => array(
			'name'      => '',
			'id'        => '_carousel_nav',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel nav', 'wpcasa-sylt' ),
			'desc'      => __( 'Show prev/next carousel navigation', 'wpcasa-sylt' ),
			'default'	=> wpsight_sylt_checkbox_default( true ),
			'priority'  => 90
		),
		'dots' => array(
			'name'      => '',
			'id'        => '_carousel_dots',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel dots', 'wpcasa-sylt' ),
			'desc'      => __( 'Show <em>dots</em> carousel navigation', 'wpcasa-sylt' ),
			'priority'  => 100
		),
		'autoplay' => array(
			'name'      => '',
			'id'        => '_carousel_autoplay',
			'type'      => 'checkbox',
			'label_cb'	=> __( 'Carousel autoplay', 'wpcasa-sylt' ),
			'desc'      => __( 'Slide through items automatically', 'wpcasa-sylt' ),
			'priority'  => 110
		),
		'autoplay_time' => array(
			'name'      => 'Autoplay interval',
			'id'        => '_carousel_autoplay_time',
			'type'      => 'select',
			'options'	=> array(
				'2000'	=> '2 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'4000'	=> '4 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'6000'	=> '6 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'8000'	=> '8 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
				'10000'	=> '10 ' . _x( 'seconds', 'listing widget', 'wpcasa-sylt' ),
			),
			'desc'		=> __( 'Please select the autoplay interval timeout', 'wpcasa-sylt' ),
			'default'	=> 6000,
			'priority'  => 120
		)
	);
	
	// Add taxonomy filters
	
	foreach( $taxonomies as $taxonomy ) {
		
		$get_terms = get_terms( $taxonomy->name, array( 'hide_empty' => false ) );
		
		$terms = array(
			'' => __( 'None', 'wpcasa-sylt' )
		);
		
		foreach ( $get_terms as $term ) {
			$terms[ $term->slug ] = $term->name;
		}
		
		$fields[ $taxonomy->name ] = array(
			'name'		=> __( 'Filter by', 'wpcasa-sylt' ) . ': ' . $taxonomy->labels->singular_name,
			'desc'		=> '',
			'id'		=> '_carousel_taxonomy_' . $taxonomy->name,
			'options'	=> $terms,
			'type'		=> 'select',
			'priority'  => 40
		);

	}

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_carousel_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_carousel',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Carousel', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_carousel', $meta_box );
	
}

/**
 *	wpsight_sylt_meta_boxes_home_cta_2()
 *
 *	Set up home call to action #2 meta box.
 *
 *	@uses	wpsight_sort_array_by_priority()
 *	@return	array	$meta_box	Array of meta box
 *
 *	@since 1.0.0
 */
function wpsight_sylt_meta_boxes_home_cta_2() {
	
	// Set meta box fields

	$fields = array(
		'display' => array(
			'name'      => '',
			'id'        => '_cta_2_display',
			'type'      => 'checkbox',
			'label_cb'  => __( 'Display', 'wpcasa-sylt' ),
			'desc'      => __( 'Display call to action on the front page', 'wpcasa-sylt' ),
			'priority'  => 10
		),
		'title' => array(
			'name'      => __( 'Title', 'wpcasa-sylt' ),
			'id'        => '_cta_2_title',
			'type'      => 'text',
			'desc'      => __( 'Please enter the title', 'wpcasa-sylt' ),
			'priority'  => 20
		),
		'description' => array(
			'name'      => __( 'Description', 'wpcasa-sylt' ),
			'id'        => '_cta_2_description',
			'type'      => 'textarea_small',
			'desc'      => __( 'Please enter the description', 'wpcasa-sylt' ),
			'priority'  => 30
		),
		'button_label' => array(
			'name'      => __( 'Button Label', 'wpcasa-sylt' ),
			'id'        => '_cta_2_button_label',
			'type'      => 'text',
			'desc'      => __( 'Please enter the button label', 'wpcasa-sylt' ),
			'priority'  => 40
		),
		'button_url' => array(
			'name'      => __( 'Button URL', 'wpcasa-sylt' ),
			'id'        => '_cta_2_button_url',
			'type'      => 'text_url',
			'desc'      => __( 'Please enter the URL', 'wpcasa-sylt' ),
			'priority'  => 50
		)
	);

	// Apply filter and order fields by priority
	$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_sylt_meta_boxes_home_cta_2_fields', $fields ) );

	// Set meta box

	$meta_box = array(
		'id'			=> 'home_cta_2',
		'title'			=> WPSIGHT_SYLT_NAME . ' :: ' . __( 'Home Call to Action #2', 'wpcasa-sylt' ),
		'object_types'	=> array( 'page' ),
		'show_on'		=> array( 'key' => 'page-template', 'value' => 'page-tpl-home.php' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> $fields
	);

	return apply_filters( 'wpsight_sylt_meta_boxes_home_cta_2', $meta_box );	
	
}

/**
 *	wpsight_sylt_checkbox_default()
 *	
 *	Helper function to set check box defaults.
 *	Only return default value if we don't
 *	have a post ID (in the 'post' query variable).
 *
 *	@param	bool	$default On/Off (true/false)
 *	@return	mixed	Returns true or '', the blank default
 */
function wpsight_sylt_checkbox_default( $default ) {
    return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}
