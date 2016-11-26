<?php
/**
 * Theme Customizer
 *
 * @package WPCasa Bahia
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
add_action( 'customize_register', 'wpsight_bahia_customize_register' );

function wpsight_bahia_customize_register( $wp_customize ) {

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
add_action( 'customize_preview_init', 'wpsight_bahia_customize_preview_js' );

function wpsight_bahia_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_bahia_customizer', get_template_directory_uri() . '/assets/js/customizer' . $suffix . '.js', array( 'customize-preview' ), WPSIGHT_BAHIA_VERSION, true );
}

/**
 * Register custom customizer logo options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_bahia_customize_register_logo' );

function wpsight_bahia_customize_register_logo( $wp_customize ) {
	
	// Set logo image
	
	$wp_customize->add_setting( 'wpcasa_bahia_logo', array(
        'capability'	=> 'edit_theme_options',
		'default'		=> get_template_directory_uri() . '/assets/images/logo.png',
		'type'			=> 'option'
	));
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'customize_wpcasa_bahia_logo', array(
		'label'      => __( 'Logo', 'wpcasa-bahia' ),
		'section'    => 'title_tagline',
		'settings'   => 'wpcasa_bahia_logo',
	)));

}

/**
 * Register custom subheader options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_bahia_customize_register_subheader' );

function wpsight_bahia_customize_register_subheader( $wp_customize ) {

	// Add section 'Header'
	
	$wp_customize->add_section( 'wpsight_bahia_subheader' , array(
	    'title'      => __( 'Header', 'uptown' ),
		'priority'   => 30
	));
	
	// Set top bar text
	
	$wp_customize->add_setting( 'wpsight_bahia_header_top_info', array(
        'capability'	=> 'edit_theme_options',
		'default' 			=> __( '<i class="icon fa-mobile-phone"></i> Call Us Today: 1-234-567-8910', 'wpcasa-bahia' ),
		'type' 				=> 'option',
		'sanitize_callback' => 'wp_kses_post'
	));
	
	$wp_customize->add_control( new WPSight_Bahia_Customize_Textarea_Control( $wp_customize, 'customize_header_top_info', array(
		'label'    => __( 'Header Top Info', 'wpcasa-bahia' ),
		'section'  => 'wpsight_bahia_subheader',
		'settings' => 'wpsight_bahia_header_top_info'
	)));
	
	// Set header top info display
	
	$wp_customize->add_setting( 'wpsight_bahia_header_top_info_display', array(
        'capability'	=> 'edit_theme_options',
        'type'			=> 'option',
        'default'		=> '1'
    ));
 
    $wp_customize->add_control( 'header_top_info_display', array(
        'settings'	=> 'wpsight_bahia_header_top_info_display',
        'label'		=> __( 'Display top info', 'wpcasa-bahia' ),
        'section'	=> 'wpsight_bahia_subheader',
        'type'		=> 'checkbox'
    ));
	
	// Set header background
	
	$wp_customize->add_setting( 'wpsight_bahia_header_background', array(
        'capability'		=> 'edit_theme_options',
		'default'			=> get_template_directory_uri() . '/assets/images/bg-site-header-bg.jpg',
		'type'				=> 'option',
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'header_background', array(
		'label'      => __( 'Header Background', 'wpcasa-bahia' ),
		'section'    => 'wpsight_bahia_subheader',
		'settings'   => 'wpsight_bahia_header_background'
	)));
	
	// Set header tagline
	
	$wp_customize->add_setting( 'wpsight_bahia_header_tagline', array(
        'capability'		=> 'edit_theme_options',
		'default' 			=> '<span>Let us help you find <em>your dream</em> home</span>',
		'type' 				=> 'option',
		'sanitize_callback' => 'wp_kses_post'
	));
	
	$wp_customize->add_control( new WPSight_Bahia_Customize_Textarea_Control( $wp_customize, 'customize_header_tagline', array(
		'label'    => __( 'Header Tagline', 'wpcasa-bahia' ),
		'section'  => 'wpsight_bahia_subheader',
		'settings' => 'wpsight_bahia_header_tagline'
	)));
	
	// Set header tagline display
	
	$wp_customize->add_setting( 'wpsight_bahia_header_tagline_display', array(
        'capability'	=> 'edit_theme_options',
        'type'			=> 'option',
        'default'		=> 'home'
    ));
 
    $wp_customize->add_control( 'header_tagline_display', array(
        'settings'	=> 'wpsight_bahia_header_tagline_display',
        'label'		=> false,
        'section'	=> 'wpsight_bahia_subheader',
        'type'		=> 'radio',
        'choices'	=> array(
            'all'	=> __( 'Display on all pages', 'wpcasa-bahia' ),
            'home'	=> __( 'Display only on home page', 'wpcasa-bahia' ),
            'no'	=> __( 'Do not display at all', 'wpcasa-bahia' )
        )
    ));
	
	// Set header search display
	
	$wp_customize->add_setting( 'wpsight_bahia_header_search_display', array(
        'capability'	=> 'edit_theme_options',
        'type'			=> 'option',
        'default'		=> 'all'
    ));
 
    $wp_customize->add_control( 'header_search_display', array(
        'settings'	=> 'wpsight_bahia_header_search_display',
        'label'		=> __( 'Header Search' ),
        'section'	=> 'wpsight_bahia_subheader',
        'type'		=> 'radio',
        'choices'	=> array(
            'all'	=> __( 'Display on all pages', 'wpcasa-bahia' ),
            'home'	=> __( 'Display only on home page', 'wpcasa-bahia' ),
            'no'	=> __( 'Do not display at all', 'wpcasa-bahia' )
        )
    ));

}

/**
 * Register custom customizer color options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_bahia_customize_register_color' );

function wpsight_bahia_customize_register_color( $wp_customize ) {
	
	// Set custom primary color
	
	$wp_customize->add_setting( 'primary_color', array(
		'default' 			=> '#3d4754',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_primary_color', array(
		'label'    => __( 'Primary Color', 'wpcasa-bahia' ),
		'section'  => 'colors',
		'settings' => 'primary_color',
	)));
	
	// Set custom secondary color
	
	$wp_customize->add_setting( 'secondary_color', array(
		'default' 			=> '#81cfe0',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_secondary_color', array(
	    'label'    => __( 'Secondary Color', 'wpcasa-bahia' ),
	    'section'  => 'colors',
	    'settings' => 'secondary_color',
	)));
	
	// Set custom accent color
	
	$wp_customize->add_setting( 'accent_color', array(
		'default' 			=> '#ffed00',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_accent_color', array(
	    'label'    => __( 'Accent Color', 'wpcasa-bahia' ),
	    'section'  => 'colors',
	    'settings' => 'accent_color',
	)));

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_bahia_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_bahia_do_theme_mods_css' );

function wpsight_bahia_do_theme_mods_css() {

	$mods 		  = '';
	$colors = '';
	
	// Set primary color
	
	$colors .= wpsight_bahia_generate_css( 'body, .wpsight-menu .sub-menu, .site-top, .site-bottom, .site-cta-special, .wpsight-favorites-sc .favorites-remove:hover, .single-listing .site-main .section-widget_listing_price, .single-listing .site-main .wpsight-listing-section-info, .single-listing .site-top .section-widget_listing_price, .single-listing .site-bottom .section-widget_listing_price', 'background-color', 'primary_color' );
	
	$colors .= wpsight_bahia_generate_css( '.nav-primary .wpsight-menu .sub-menu:before', 'border-bottom-color', 'primary_color' );
	
	$colors .= wpsight_bahia_generate_css( 'a:hover', 'color', 'primary_color', '', '', false, '.6' );
	
	$colors .= wpsight_bahia_generate_css( '#tagline span, #home-search .wpsight-listings-search, .listings-search-reset, .listings-search-advanced-toggle, .wpsight-favorites-sc .favorites-remove', 'background-color', 'primary_color', '', '', false, '.6' );
	
	$colors .= '@media screen and (max-width: 980px) { ' . wpsight_bahia_generate_css( '.nav-primary, .nav-primary .wpsight-menu.responsive-menu .sub-menu', 'background-color', 'primary_color', '', '', false, '.6' ) . ' }';

	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].special, input[type="reset"].special, input[type="button"].special, button.special, .button.special', 'background-color', 'primary_color', '', '', false, '.9' );
	
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].special, input[type="reset"].special, input[type="button"].special, button.special, .button.special, input[type="submit"].special:active, input[type="reset"].special:active, input[type="button"].special:active, button.special:active, .button.special:active', 'background-color', 'primary_color', '', '', false, '.9' );
	
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].special:hover, input[type="reset"].special:hover, input[type="button"].special:hover, button.special:hover, .button.special:hover', 'background-color', 'primary_color', '', '', false, '.85' );
	
	// Set secondary color
	
	$colors .= wpsight_bahia_generate_css( 'h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .wpsight-listing-carousel .entry-title a:hover', 'color', 'secondary_color' );
	
	$colors .= wpsight_bahia_generate_css( '.single-listing .wpsight-listing-section-features a.listing-term:hover, .single-listing .section-widget_listing_terms .listing-terms-blocks a.listing-term:hover, .calendar_wrap caption', 'background-color', 'secondary_color' );
	
	$colors .= wpsight_bahia_generate_css( '.site-header-bg-inner', 'background-color', 'secondary_color', '', '', false, '.6' );
	
	$colors .= wpsight_bahia_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before, input[type="submit"], input[type="reset"], input[type="button"], button, .button, input[type="submit"]:active, input[type="reset"]:active, input[type="button"]:active, button:active, .button:active', 'background-color', 'secondary_color', '', '', false, '.9' );
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"]:hover, input[type="reset"]:hover, input[type="button"]:hover, button:hover, .button:hover', 'background-color', 'secondary_color', '', '', false, '.85' );	
	$colors .= wpsight_bahia_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before', 'border-color', 'secondary_color', '', '', false, '.9' );
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt, input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active', 'box-shadow', 'secondary_color', 'inset 0 0 0 2px ', '', false, '.75' );
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt, input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active', 'color', 'secondary_color', '', ' !important', false, '.75' );
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'box-shadow', 'secondary_color', 'inset 0 0 0 2px ', '', false, '.9' );
	$colors .= wpsight_bahia_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'color', 'secondary_color', '', ' !important', false, '.9' );
	
	// Set accent color
	
	$colors .= wpsight_bahia_generate_css( '.accent, .site-header-top-info .icon:before, #tagline em', 'color', 'accent_color' );
	
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
 * @uses wpsight_bahia_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_bahia_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_bahia_hex2rgb( $mod );
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
function wpsight_bahia_hex2rgb( $hex ) {

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
 * Add WPSight_Bahia_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'wpsight_bahia_customize_register_class_textarea', 9 );

function wpsight_bahia_customize_register_class_textarea() {

	class WPSight_Bahia_Customize_Textarea_Control extends WP_Customize_Control {
	
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
