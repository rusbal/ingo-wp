<?php
/**
 * Theme Customizer
 *
 * @package WPCasa Elviria
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
add_action( 'customize_register', 'wpsight_elviria_customize_register' );

function wpsight_elviria_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @uses wp_enqueue_script()
 * @uses get_template_directory_uri()
 *
 * @since 1.0
 */
add_action( 'customize_preview_init', 'wpsight_elviria_customize_preview_js' );

function wpsight_elviria_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_elviria_customizer', get_template_directory_uri() . '/assets/js/customizer' . $suffix . '.js', array( 'customize-preview' ), WPSIGHT_ELVIRIA_VERSION, true );
}

/**
 * Register custom customizer logo options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_elviria_customize_register_logo' );

function wpsight_elviria_customize_register_logo( $wp_customize ) {
	
	$wp_customize->add_setting(
		'wpcasa_elviria_logo',
		array(
			'default'		=> get_template_directory_uri() . '/assets/images/logo.png',
			'type'			=> 'option'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Upload_Control( 
		$wp_customize, 
		'customize_wpcasa_elviria_logo', 
		array(
			'label'      => __( 'Logo', 'wpcasa-elviria' ),
			'section'    => 'title_tagline',
			'settings'   => 'wpcasa_elviria_logo',
		) ) 
	);
	
	$wp_customize->add_setting(
		'wpcasa_elviria_logo_bg',
		array(
			'default'		=> get_template_directory_uri() . '/assets/images/bg-site-header.png',
			'type'			=> 'option'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Upload_Control( 
		$wp_customize, 
		'customize_wpcasa_elviria_logo_bg', 
		array(
			'label'      => __( 'Logo Background', 'wpcasa-elviria' ),
			'section'    => 'title_tagline',
			'settings'   => 'wpcasa_elviria_logo_bg',
		) ) 
	);

}

/**
 * Register custom customizer color options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_elviria_customize_register_color' );

function wpsight_elviria_customize_register_color( $wp_customize ) {
	
	// Add setting link color
	
	$wp_customize->add_setting(
		'accent_color',
		array(
			'default' 			=> '#502732',
			'type' 				=> 'theme_mod',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'customize_link_color',
			array(
			    'label'    => __( 'Accent Color', 'wpcasa-elviria' ),
			    'section'  => 'colors',
			    'settings' => 'accent_color',
			)
		)
	);

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_elviria_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_elviria_do_theme_mods_css' );

function wpsight_elviria_do_theme_mods_css() {

	$mods 		  = '';
	$accent_color = '';
	
	// Set accent color
	
	$accent_color .= wpsight_elviria_generate_css( 'body, .site-header-bg, .site-header.site-section, .single-listing .wpsight-listing-section-features a.listing-term:hover, .single-listing .section-widget_listing_terms .listing-terms-blocks a.listing-term:hover, .calendar_wrap caption, .single-listing .site-main .section-widget_listing_price, .single-listing .site-main .wpsight-listing-section-info, .single-listing .site-top .section-widget_listing_price, .single-listing .site-bottom .section-widget_listing_price', 'background-color', 'accent_color' );	
	$accent_color .= wpsight_elviria_generate_css( 'a', 'color', 'accent_color', '', '', false, '.9' );	
	$accent_color .= wpsight_elviria_generate_css( 'h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .nav-secondary .wpsight-menu .sub-menu a, .nav-secondary .wpsight-menu .sub-menu a:hover, .wpsight-listing-carousel .entry-title a:hover', 'color', 'accent_color' );	
	$accent_color .= wpsight_elviria_generate_css( 'a:hover,.wpsight-menu .sub-menu a:hover', 'color', 'accent_color', '', '', false, '.75' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before, input[type="submit"], input[type="reset"], input[type="button"], button, .button, input[type="submit"]:active, input[type="reset"]:active, input[type="button"]:active, button:active, .button:active', 'background-color', 'accent_color', '', '', false, '.9' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="submit"]:hover, input[type="reset"]:hover, input[type="button"]:hover, button:hover, .button:hover', 'background-color', 'accent_color', '', '', false, '.85' );	
	$accent_color .= wpsight_elviria_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before', 'border-color', 'accent_color', '', '', false, '.9' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt, input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active', 'box-shadow', 'accent_color', 'inset 0 0 0 2px ', '', false, '.75' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt, input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active', 'color', 'accent_color', '', ' !important', false, '.75' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'box-shadow', 'accent_color', 'inset 0 0 0 2px ', '', false, '.9' );
	$accent_color .= wpsight_elviria_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'color', 'accent_color', '', ' !important', false, '.9' );
	
	if( ! empty( $accent_color ) )
		$mods .= $accent_color;
	
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
 * @uses wpsight_elviria_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_elviria_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_elviria_hex2rgb( $mod );
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
function wpsight_elviria_hex2rgb( $hex ) {

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
