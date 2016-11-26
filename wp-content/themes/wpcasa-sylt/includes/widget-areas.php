<?php
/**
 * Custom widget areas
 *
 * @package WPCasa Sylt
 */

/**
 * Register widget areas
 *
 * @since 1.0
 */
add_action( 'widgets_init', 'wpsight_sylt_register_widget_areas' );

function wpsight_sylt_register_widget_areas() {

	foreach( wpsight_sylt_widget_areas() as $widget_area )
		register_sidebar( $widget_area );

}

/**
 * Create widget areas array
 *
 * @since 1.0
 */
function wpsight_sylt_widget_areas() {

	$widget_areas = array(
		
		'sidebar' => array(
			'name' 			=> __( 'General Sidebar', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display content in the general sidebar widget area.', 'wpcasa-sylt' ),
			'id' 			=> 'sidebar',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 10
		),
		
		'footer' => array(
			'name' 			=> __( 'General Footer', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display content in the footer widget area.', 'wpcasa-sylt' ),
			'id' 			=> 'footer',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s ' . wpsight_sylt_count_widgets( 'footer' ) . '"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 20
		),
		
		'listing-top' => array(
			'name' 			=> __( 'Listing Single Top', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display full-width content above the regular content on single listing pages.', 'wpcasa-sylt' ),
			'id' 			=> 'listing-top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 90
		),
		
		'listing' => array(
			'name' 			=> __( 'Listing Single Content', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display main content on single listing pages.', 'wpcasa-sylt' ),
			'id' 			=> 'listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 100
		),
		
		'sidebar-listing' => array(
			'name' 			=> __( 'Listing Single Sidebar', 'wpcasa-sylt' ),
			'description'	=> __( 'Display content in the sidebar on single listing pages.', 'wpcasa-sylt' ),
			'id' 			=> 'sidebar-listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 110
		),
		
		'listing-bottom' => array(
			'name' 			=> __( 'Listing Single Bottom', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display full-width content below the regular content on single listing pages.', 'wpcasa-sylt' ),
			'id' 			=> 'listing-bottom',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 120
		),
		
		'sidebar-listing-archive' => array(
			'name' 			=> __( 'Listing Archive Sidebar', 'wpcasa-sylt' ),
			'description' 	=> __( 'Display content in the sidebar on listing archive pages.', 'wpcasa-sylt' ),
			'id' 			=> 'sidebar-listing-archive',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 130
		)
	
	);
	
	$widget_areas = apply_filters( 'wpsight_sylt_widget_areas', $widget_areas );
	
	// Sort array by position
	
	if( function_exists( 'wpsight_sort_array_by_priority') )
    	$widget_areas = wpsight_sort_array_by_priority( $widget_areas );
	
	return $widget_areas;

}

/**
 * Count number of widgets in a sidebar
 * Used to add classes to widget areas so widgets can be displayed one, two, three or four per row
 *
 * @see https://gist.github.com/slobodan/6156076
 */
function wpsight_sylt_count_widgets( $sidebar_id ) {
	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );
		if ( $widget_count == 1 ) :
			$widget_classes .= ' 12u$';
		elseif ( 2 == $widget_count ) :
			$widget_classes .= ' 6u 12u$(medium)';
		elseif ( $widget_count >= 3 ) :
			$widget_classes .= ' 4u 12u$(medium)';
		endif; 

		return $widget_classes;
	endif;
}
