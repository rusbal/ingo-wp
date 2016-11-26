<?php
/**
 * Custom widget areas
 *
 * @package WPCasa STAGE
 */

/**
 * Register widget areas
 *
 * @since 1.0
 */
 
add_action( 'widgets_init', 'wpsight_stage_register_widget_areas' );

function wpsight_stage_register_widget_areas() {

	foreach( wpsight_stage_widget_areas() as $widget_area )
		register_sidebar( $widget_area );

}

/**
 * Create widget areas array
 *
 * @since 1.0
 */
 
function wpsight_stage_widget_areas() {

	$widget_areas = array(
		
		'sidebar' => array(
			'name' 			=> __( 'General Sidebar', 'wpsight-stage' ),
			'description' 	=> __( 'Display content in the general sidebar widget area.', 'wpsight-stage' ),
			'id' 			=> 'sidebar',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 10
		),
	
		'top' => array(
			'name' 			=> __( 'General Top', 'wpsight-stage' ),
			'description' 	=> __( 'Display content at the very top of the site.', 'wpsight-stage' ),
			'id' 			=> 'top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 15
		),
		
		'footer' => array(
			'name' 			=> __( 'General Footer', 'wpsight-stage' ),
			'description' 	=> __( 'Display content in the footer widget area.', 'wpsight-stage' ),
			'id' 			=> 'footer',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 20
		),
		
		'home-top' => array(
			'name' 			=> __( 'Home Page Top', 'wpsight-stage' ),
			'description' 	=> __( 'Display full-width content above the regular content on the home page.', 'wpsight-stage' ),
			'id' 			=> 'home-top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 50
		),
		
		'home' => array(
			'name' 			=> __( 'Home Page Content', 'wpsight-stage' ),
			'description' 	=> __( 'Display main content on the home page next to the sidebar.', 'wpsight-stage' ),
			'id' 			=> 'home',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 60
		),
		
		'sidebar-home' => array(
			'name' 			=> __( 'Home Page Sidebar', 'wpsight-stage' ),
			'description' 	=> __( 'Display sidebar next to the main content on the home page.', 'wpsight-stage' ),
			'id' 			=> 'sidebar-home',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 70
		),
		
		'home-bottom' => array(
			'name' 			=> __( 'Home Page Bottom', 'wpsight-stage' ),
			'description' 	=> __( 'Display full-width content below the regular content on the home page.', 'wpsight-stage' ),
			'id' 			=> 'home-bottom',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 80
		),
		
		'listing-top' => array(
			'name' 			=> __( 'Listing Single Top', 'wpsight-stage' ),
			'description' 	=> __( 'Display full-width content above the regular content on single listing pages.', 'wpsight-stage' ),
			'id' 			=> 'listing-top',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 90
		),
		
		'listing' => array(
			'name' 			=> __( 'Listing Single Content', 'wpsight-stage' ),
			'description' 	=> __( 'Display main content on single listing pages.', 'wpsight-stage' ),
			'id' 			=> 'listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 100
		),
		
		'sidebar-listing' => array(
			'name' 			=> __( 'Listing Single Sidebar', 'wpsight-stage' ),
			'description'	=> __( 'Display content in the sidebar on single listing pages.', 'wpsight-stage' ),
			'id' 			=> 'sidebar-listing',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 110
		),
		
		'listing-bottom' => array(
			'name' 			=> __( 'Listing Single Bottom', 'wpsight-stage' ),
			'description' 	=> __( 'Display full-width content below the regular content on single listing pages.', 'wpsight-stage' ),
			'id' 			=> 'listing-bottom',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'priority'		=> 120
		),
		
		'sidebar-listing-archive' => array(
			'name' 			=> __( 'Listing Archive Sidebar', 'wpsight-stage' ),
			'description' 	=> __( 'Display content in the sidebar on listing archive pages.', 'wpsight-stage' ),
			'id' 			=> 'sidebar-listing-archive',
			'before_widget' => '<section id="section-%1$s" class="widget-section section-%2$s"><div id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</div></section>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'priority'		=> 130
		)
	
	);
	
	$widget_areas = apply_filters( 'wpsight_stage_widget_areas', $widget_areas );
	
	// Sort array by position
	
	if( function_exists( 'wpsight_sort_array_by_priority') )
    	$widget_areas = wpsight_sort_array_by_priority( $widget_areas );
	
	return $widget_areas;

}
