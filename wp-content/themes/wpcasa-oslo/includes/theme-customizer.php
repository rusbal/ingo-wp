<?php
/**
 * Theme Customizer
 *
 * @package WPCasa Oslo
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @uses wp_enqueue_script()
 * @uses get_template_directory_uri()
 *
 * @since 1.0
 */
add_action( 'customize_preview_init', 'wpsight_oslo_customize_preview_js' );

function wpsight_oslo_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_oslo_customizer', get_template_directory_uri() . '/assets/js/wpsight-customizer' . $suffix . '.js', array( 'jquery','customize-preview' ), '1112345896', true );
}

/**
 * Add postMessage support for site description
 * and background color for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
add_action( 'customize_register', 'wpsight_oslo_customize_register', 11 );

function wpsight_oslo_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	
	$wp_customize->remove_control( 'display_header_text' );
}

/**
 * Register customizer logo theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_oslo_customize_register_logo' );

function wpsight_oslo_customize_register_logo( $wp_customize ) {
	
	// Set tagline deactivation option

	$wp_customize->add_setting( 'deactivate_tagline', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'customize_tagline', array(
    	'section'   		=> 'title_tagline',
		'label'     		=> __( 'Hide tagline', 'wpcasa-oslo' ),
		'type'      		=> 'checkbox',
		'settings'			=> 'deactivate_tagline'
    ));
	
	// Set text logo
	
	$wp_customize->add_setting( 'wpcasa_logo_text', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_kses_post',
		'default'			=> get_bloginfo( 'name' ),
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	// Add text logo control
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_logo_text', array(
		'label'				=> __( 'Text Logo', 'wpcasa-oslo' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'It will only be displayed if no regular logo image is provided.', 'wpcasa-oslo' ),
		'settings'			=> 'wpcasa_logo_text'
	)));
	
	// Set logo image
	
	$wp_customize->add_setting( 'wpcasa_logo', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
		'default'			=> get_template_directory_uri() . '/assets/images/logo.png',
	));
	
	// Add logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo', array(
		'label'				=> __( 'Logo', 'wpcasa-oslo' ),
		'section'			=> 'title_tagline',
		'settings'			=> 'wpcasa_logo'
	)));
	
	// Set print logo image
	
	$wp_customize->add_setting( 'wpcasa_logo_print', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
	));
	
	// Add print logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo_print', array(
		'label'				=> __( 'Print Logo', 'wpcasa-oslo' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'Optionally upload a print logo that will be displayed in listing print versions or PDFs.', 'wpcasa-oslo' ),
		'settings'			=> 'wpcasa_logo_print'
	)));

}

/**
 * Register customizer top section theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_oslo_customize_register_top' );

function wpsight_oslo_customize_register_top( $wp_customize ) {

	// Add section 'Site Top'
	
	$wp_customize->add_section( 'wpsight_oslo_site_top' , array(
	    'title'				=> __( 'Site Top', 'wpcasa-oslo' ),
		'priority'			=> 40
	));
	
	// Set site top icon box #1
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Get in touch', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-oslo' ), '1' ),
		'description'		=> __( 'Title', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-phone fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-oslo' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-oslo' ) . ')</small>',
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_1_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_1_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_1_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_1_display', array(
    	'section'   => 'wpsight_oslo_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-oslo' ), '1' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_1_display'
    ));
	
	// Set site top icon box #2
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Office hours', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-oslo' ), '2' ),
		'description'		=> __( 'Title', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-clock-o fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-oslo' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-oslo' ) . ')</small>',
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_2_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_2_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_2_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_2_display', array(
    	'section'   => 'wpsight_oslo_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-oslo' ), '2' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_2_display'
    ));
	
	// Set site top icon box #3
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_title', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Our location', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_title', array(
		'label'				=> sprintf( __( 'Icon Box #%s', 'wpcasa-oslo' ), '3' ),
		'description'		=> __( 'Title', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_title'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_icon', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> 'fa fa-map-marker fa-fw',
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_icon', array(
		'label'				=> false,
		'description'		=> __( 'Icon Class', 'wpcasa-oslo' ) . ' <small>(' . __( 'For example <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>', 'wpcasa-oslo' ) . ')</small>',
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_icon'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_text_1', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_text_1', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_text_1'
	)));
	
	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_text_2', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Put some text here', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_site_top_icon_box_3_text_2', array(
		'label'				=> false,
		'description'		=> __( 'Text Line', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_top',
		'settings'			=> 'wpcasa_site_top_icon_box_3_text_2'
	)));

	$wp_customize->add_setting( 'wpcasa_site_top_icon_box_3_display', array(
		'default'			=> true,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'wpcasa_site_top_icon_box_3_display', array(
    	'section'   => 'wpsight_oslo_site_top',
		'label'     => sprintf( __( 'Display Box #%s', 'wpcasa-oslo' ), '3' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_icon_box_3_display'
    ));

	$wp_customize->add_setting( 'wpcasa_site_top_vertical', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'wpcasa_site_top_vertical', array(
    	'section'   => 'wpsight_oslo_site_top',
		'label'     => __( 'Show logo and icons vertically on small screens', 'wpcasa-oslo' ),
		'type'      => 'checkbox',
		'settings'	=> 'wpcasa_site_top_vertical'
    ));

}

/**
 * Register customizer bottom section theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_oslo_customize_register_bottom' );

function wpsight_oslo_customize_register_bottom( $wp_customize ) {

	// Add section 'Site Bottom'
	
	$wp_customize->add_section( 'wpsight_oslo_site_bottom' , array(
	    'title'				=> __( 'Site Bottom', 'wpcasa-oslo' ),
		'priority'			=> 40
	));
	
	// Set site bottom left
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_left', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> sprintf( __( 'Copyright &copy; <span itemprop="copyrightYear">%s</span> &middot; <a href="%s" rel="home" itemprop="copyrightHolder">%s</a>', 'wpcasa-oslo' ), date( 'Y' ), esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new wpsight_oslo_Customize_Textarea_Control( $wp_customize, 'site_bottom_left', array(
		'label'				=> __( 'Site Bottom Left', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_bottom',
		'settings'			=> 'wpcasa_site_bottom_left'
	)));
	
	// Set site bottom right
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_right', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Built on <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>', 'wpcasa-oslo' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new wpsight_oslo_Customize_Textarea_Control( $wp_customize, 'site_bottom_right', array(
		'label'				=> __( 'Site Bottom Right', 'wpcasa-oslo' ),
		'section'			=> 'wpsight_oslo_site_bottom',
		'settings'			=> 'wpcasa_site_bottom_right'
	)));

}

/**
 * Register custom customizer color options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_oslo_customize_register_color' );

function wpsight_oslo_customize_register_color( $wp_customize ) {
	
	// Set light section background color
	
	$wp_customize->add_setting( 'main_text_color', array(
		'default' 			=> '#43454a',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_text_color', array(
	    'label'    => __( 'Text Color', 'wpcasa-oslo' ),
	    'section'  => 'colors',
	    'settings' => 'main_text_color',
	)));
	
	// Set light section background color
	
	$wp_customize->add_setting( 'light_background_color', array(
		'default' 			=> '#ffffff',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_background_color', array(
	    'label'    => __( 'Section Background Color (light)', 'wpcasa-oslo' ),
	    'section'  => 'colors',
	    'settings' => 'light_background_color',
	)));
	
	// Set dark section background color
	
	$wp_customize->add_setting( 'dark_background_color', array(
		'default' 			=> '#f0f0f3',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_background_color', array(
	    'label'    => __( 'Section Background Color (dark)', 'wpcasa-oslo' ),
	    'section'  => 'colors',
	    'settings' => 'dark_background_color',
	)));
	
	// Set light accent color
	
	$wp_customize->add_setting( 'light_accent_color', array(
		'default' 			=> '#dbd063',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_accent_color', array(
	    'label'    => __( 'Accent Color (light)', 'wpcasa-oslo' ),
	    'section'  => 'colors',
	    'settings' => 'light_accent_color',
	)));
	
	// Set dark accent color
	
	$wp_customize->add_setting( 'dark_accent_color', array(
		'default' 			=> '#43454a',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_accent_color', array(
	    'label'    => __( 'Accent Color (dark)', 'wpcasa-oslo' ),
	    'section'  => 'colors',
	    'settings' => 'dark_accent_color',
	)));
	
	// Set custom header image overlay color
	
	$wp_customize->add_setting( 'header_overlay_color', array(
		'default' 			=> '#43454a',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_overlay_color', array(
	    'label'    			=> __( 'Header Overlay Color', 'wpcasa-oslo' ),
	    'section'  			=> 'colors',
	    'settings' 			=> 'header_overlay_color',
	)));
	
	// Set image overlay deactivation option

	$wp_customize->add_setting( 'deactivate_header_overlay', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
	));

	$wp_customize->add_control( 'customize_header_overlay', array(
    	'section'   => 'colors',
		'label'     => __( 'Deactivate header overlay', 'wpcasa-oslo' ),
		'type'      => 'checkbox',
		'settings'	=> 'deactivate_header_overlay'
    ));

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_oslo_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_oslo_do_theme_mods_css' );

function wpsight_oslo_do_theme_mods_css() {

	$mods 	= '';
	$colors = '';
	
	// Hide tagline
	
	$hide_tagline = get_theme_mod( 'deactivate_tagline' );
	
	if( $hide_tagline )
		$mods .= '.site-description { display: none; }';
	
	// Deactivate header overlay

	$header_overlay = get_theme_mod( 'deactivate_header_overlay' );
	
	if( $header_overlay )
		$mods .= '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after { background-color: transparent !important; } .site-header-gallery .wpsight-image-background-slider-item { opacity: 1; }';
	
	// Set text color	
	$colors .= wpsight_oslo_generate_css( 'body.wpsight-oslo, .site-title-text, .site-description, .site-wrapper .btn-group .dropdown-menu > li > a, .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .single-listing .wpsight-listing-title .actions-print:before, .single-listing .wpsight-listing-title .favorites-add:before, .single-listing .wpsight-listing-title .favorites-see:before, .social-icons .fa, .site-wrapper .dropdown-toggle.btn-default, .site-wrapper .dropdown-toggle.btn-default:hover', 'color', 'main_text_color' );
	
	// Set header image overlay color	
	$colors .= wpsight_oslo_generate_css( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after', 'background-color', 'header_overlay_color', '', '', false, '.35' );
	
	$colors .= wpsight_oslo_generate_css( '.site-header-gallery .site-header-inner, .wpsight-image-background-slider-item', 'background-color', 'header_overlay_color' );
	
	// Set light background color
	$colors .= wpsight_oslo_generate_css( '.site-container, .site-section-dark .feature-box-info', 'background-color', 'light_background_color' );
	
	// Set dark background color
	$colors .= wpsight_oslo_generate_css( '.site-wrapper .table-striped > tbody > tr:nth-of-type(odd), .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .header-full-width, .site-section-dark, .tags-links .post-tag-wrap, .wpsight-listings-search, .listings-search-reset, .listings-search-advanced-toggle, #map-toggle-main-before .toggle-map, .wpsight-listings .listing-image-default, .wpsight-listing-compare .listing-details-detail:nth-child(odd), .wpsight-listings .wpsight-listing-summary .listing-details-detail, .wpsight-listings-carousel .wpsight-listing-summary .listing-details-detail, .wpsight-listing-teaser .listing-image-default, .single-listing .wpsight-listing-features .listing-term-wrap, .wpsight-pagination .pagination > li > a, .wpsight-pagination .pagination > li > span, .bs-callout, .wpsight-listings-carousel-prev-next .carousel-prev, .wpsight-listings-carousel-prev-next .carousel-next, .feature-box-info, .wpsight-dashboard-row-image .listing-image-default, .submission-steps.breadcrumb, .cmb2-wrap.form-table, .wpsight-dashboard-form.register-form, .wpsight-dashboard-form.login-form, .wpsight-dashboard-form.change-password-form, .wpsight-dashboard-form.reset-password-form, .wpsight-dashboard-form.payment-form, .wpsight-dashboard-form.package-form', 'background-color', 'dark_background_color' );
	
	// Set light accent color
	
	$colors .= wpsight_oslo_generate_css( '.site-top .accent, .site-sub-bottom .accent, #home-search, .tags-links .post-tag-wrap i, .post .entry-content:before, .search .page .entry-content:before, .single-listing .wpsight-listing-features .listing-term-wrap i, .single-listing .wpsight-listing-agent:before, .wpsight-list-agents-sc .wpsight-list-agent:before, .archive.author .wpsight-list-agent:before, .comment-body:after, .bs-callout-primary:before, .feature-box-icon, .section-title:after, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before, .cmb2-wrap input.btn:focus, .cmb2-wrap input.btn:active', 'background-color', 'light_accent_color' );
	
	$colors .= wpsight_oslo_generate_css( '.site-wrapper .label-primary, .site-wrapper .btn-primary, .site-wrapper .btn-primary:active, .site-wrapper .btn-primary.active, .site-wrapper .open > .dropdown-toggle.btn-primary, #home-search .listings-search-reset, #home-search .listings-search-advanced-toggle', 'background-color', 'light_accent_color', '', ' !important' );
	
	$colors .= wpsight_oslo_generate_css( '.wpsight-nav.nav > li > a:hover, .wpsight-nav.nav > li > a:focus, .wpsight-nav.nav .open > a, .wpsight-nav.nav .open > a:hover, .wpsight-nav.nav .open > a:focus, .site-top .menu a:hover, .site-sub-bottom .menu a:hover, .site-wrapper a, .site-wrapper a:focus, .site-wrapper a:hover, .accent, .post .entry-title a:hover, .page .entry-title a:hover, .wpsight-listings .wpsight-listing-title .entry-title a:hover, .wpsight-listings-carousel .wpsight-listing-title .entry-title a:hover, .wpsight-listings .wpsight-listing-summary .listing-details-detail:before, .wpsight-listings-carousel .wpsight-listing-summary .listing-details-detail:before, .single-listing .wpsight-listing-title .actions-print:hover:before, .single-listing .wpsight-listing-title .favorites-add:hover:before, .single-listing .wpsight-listing-title .favorites-see:hover:before, .site-bottom a, .wpsight-listings-slider-wrap .listing-content .listing-content-location-type, .wpsight-listings-slider-wrap .listing-content .listing-content-location-type a, .wpsight-icon-box .fa, .wpsight-infobox .wpsight-listing-summary .listing-details-detail:before', 'color', 'light_accent_color' );
	
	$colors .= wpsight_oslo_generate_css( '.site-top, .feature-box-info, .wpsight-newsletter-box, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before', 'border-color', 'light_accent_color' );
	
	// Set dark accent color
	
	$colors .= wpsight_oslo_generate_css( '.header-menu, .wpsight-nav .dropdown-menu, .site-header-wrap, .wpsight-listings .wpsight-listing-info, .wpsight-listings-carousel .wpsight-listing-info, .wpsight-pagination .pagination > .active > a, .wpsight-pagination .pagination > .active > span, .wpsight-pagination .pagination > .active > a:hover, .wpsight-pagination .pagination > .active > span:hover, .wpsight-pagination .pagination > .active > a:focus, .wpsight-pagination .pagination > .active > span:focus, .site-footer, .site-bottom, .wpsight-image-slider-item, .wpsight-listings-slider-wrap, .wpsight-listings-slider-wrap .listing-content .wpsight-listing-price, .site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before, .site-section-cta, .wpsight-cta, .wpsight-oslo .lg-backdrop, .pushy, .wpsight-infobox .wpsight-listing-summary', 'background-color', 'dark_accent_color' );
	
	$colors .= wpsight_oslo_generate_css( '.icon-box-text, .header-main, .site-main-top, .site-main-bottom, .content-title, .site-section .listings-title-info, .post .entry-content, .search .page .entry-content, .post .entry-meta, .search .page .entry-meta, .widget-title, .listings-panel, .listings-panel-action .listings-compare, .listings-panel-action .listings-compare:hover, .listings-panel-action .listings-compare:active, .listings-panel-action .listings-compare:focus, .site-wrapper .listings-sort .btn-default, .wpsight-listings .listing-content, .wpsight-listings-carousel .listing-content, .single-listing .wpsight-listing-info, .single-listing .section-widget_listing_title + .section-widget_listing_price + .section-widget_listing_details, .single-listing .wpsight-listing-details .listing-details-detail, .single-listing .wpsight-listing-agent, .wpsight-list-agents-sc .wpsight-list-agent, .archive.author .wpsight-list-agent, .single-listing .wpsight-listing-agent-name, .wpsight-list-agents-sc .wpsight-list-agent-name, .archive.author .wpsight-list-agent-name, .single-listing .wpsight-listing-agent-links a, .wpsight-list-agents-sc .wpsight-list-agent-links a, .archive.author .wpsight-list-agent-links a, .comment-body, .wpsight-dashboard-row-actions a, .site-wrapper input.form-control, .site-wrapper textarea.form-control, .wpsight-icon-box, .site-wrapper .btn-default, .site-wrapper .btn-default:active:hover, .site-wrapper .btn-default.active:hover, .site-wrapper .open > .dropdown-toggle.btn-default:hover, .site-wrapper .bootstrap-select .btn-default:active:focus, .site-wrapper .bootstrap-select .btn-default.active:focus, .site-wrapper .open > .dropdown-toggle.btn-default:focus, .site-wrapper .bootstrap-select .btn-default:active.focus, .site-wrapper .bootstrap-select .btn-default.active.focus, .site-wrapper .open > .dropdown-toggle.btn-default.focus, .site-wrapper .bootstrap-select .btn-default:hover, .site-wrapper .bootstrap-select .btn-default:focus, .site-wrapper .bootstrap-select .btn-default.focus, .site-wrapper .bootstrap-select .btn-default:active, .site-wrapper .bootstrap-select .btn-default.active, .site-wrapper .bootstrap-select .btn:active, .site-wrapper .bootstrap-select .btn.active, .site-wrapper .open > .dropdown-toggle.btn-default', 'border-color', 'dark_accent_color', '', '', false, '.1' );
	
	$colors .= wpsight_oslo_generate_css( '.wpsight-nav .dropdown-menu:after', 'border-bottom-color', 'dark_accent_color' );
	
	$colors .= wpsight_oslo_generate_css( '.site-wrapper .btn-default:not(.dropdown-toggle), .site-wrapper .btn-default:not(.dropdown-toggle):hover, .site-wrapper .btn-default:not(.dropdown-toggle):active, .site-wrapper .btn-default:not(.dropdown-toggle).active', 'background-color', 'dark_accent_color', '', ' !important' );
	
	$colors .= wpsight_oslo_generate_css( '.site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before', 'border-color', 'dark_accent_color' );
	
	$colors .= wpsight_oslo_generate_css( '.wpsight-listings-carousel-wrap .lSSlideOuter .lSPager.lSpg > li a', 'background-color', 'dark_accent_color', '', '', false, '.25' );
	
	$colors .= wpsight_oslo_generate_css( '.wpsight-listings-carousel-wrap .lSSlideOuter .lSPager.lSpg > li:hover a, .wpsight-listings-carousel-wrap .lSSlideOuter .lSPager.lSpg > li.active a', 'background-color', 'dark_accent_color', '', '', false, '.75' );
	
	$colors .= wpsight_oslo_generate_css( '.listings-panel-action .listings-compare.open, .listings-panel-action .listings-compare:hover', 'border-color', 'dark_accent_color', '', ' !important', false, '.25' );
	
	if( ! empty( $colors ) )
		$mods .= $colors;
	
	if( ! empty( $mods ) ) {	
	
		$css  = '<style type="text/css" media="screen">';
		$css .= $mods;
		$css .= '</style>' . "\n";
		
		echo $css;
		
	}

}

/**
 * Helper function to display
 * theme_mods CSS
 *
 * @param string $selector CSS selector
 * @param string $style CSS style
 * @param string $mod_name Key of the modification
 * @param string $prefix Prefix for CSS style
 * @param string $postfix Postfix for CSS style
 * @param bool $echo Return or echo
 * @param bool|string Opacity for rgba() colors
 * @uses wpsight_oslo_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_oslo_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_oslo_hex2rgb( $mod );
			$mod = 'rgba(' . $rgb . ',' . $opacity . ')';
		}
	
	   $output = "\n\t" . sprintf( '%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix ) . "\n";
	   
	   if ( $echo )
	      echo $output;
	}
	
	return $output;

}

/**
 * Helper function to convert
 * hex color in RGBA
 *
 * @param string $hex Hex color code
 * @uses str_replace()
 * @uses strlen()
 * @uses hexdec()
 * @uses substr()
 * @return string RGB color code
 *
 * @since 1.0.1
 */
function wpsight_oslo_hex2rgb( $hex ) {

	$hex = str_replace( '#', '', $hex );
	
	if( strlen( $hex ) == 3 ) {
	
	   $r = hexdec( substr( $hex,0,1 ) . substr( $hex,0,1 ) );
	   $g = hexdec( substr( $hex,1,1 ) . substr( $hex,1,1 ) );
	   $b = hexdec( substr( $hex,2,1 ) . substr( $hex,2,1 ) );
	   
	} else {
	
	   $r = hexdec( substr( $hex,0,2 ) );
	   $g = hexdec( substr( $hex,2,2 ) );
	   $b = hexdec( substr( $hex,4,2 ) );

	}
	
	$rgb = array( $r, $g, $b );
	
	return implode( ',', $rgb );
}

/**
 * Add wpsight_oslo_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'wpsight_oslo_customize_register_class_textarea', 9 );

function wpsight_oslo_customize_register_class_textarea() {

	class WPSight_Oslo_Customize_Textarea_Control extends WP_Customize_Control {
	
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="6" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

}
