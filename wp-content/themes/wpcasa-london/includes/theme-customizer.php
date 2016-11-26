<?php
/**
 * Theme Customizer
 *
 * @package WPCasa London
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @uses wp_enqueue_script()
 * @uses get_template_directory_uri()
 *
 * @since 1.0
 */
add_action( 'customize_preview_init', 'wpsight_london_customize_preview_js' );

function wpsight_london_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_london_customizer', get_template_directory_uri() . '/assets/js/wpsight-customizer' . $suffix . '.js', array( 'jquery','customize-preview' ), false, true );
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
add_action( 'customize_register', 'wpsight_london_customize_register', 11 );

function wpsight_london_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

/**
 * Register customizer logo theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_london_customize_register_logo' );

function wpsight_london_customize_register_logo( $wp_customize ) {
	
	// Set tagline margin
	
	$wp_customize->add_setting( 'tagline_margin', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'absint',
		'default'			=> 0,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	// Add tagline margin control
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'tagline_margin', array(
		'label'				=> __( 'Tagline Margin', 'wpcasa-london' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'Optionally set the bottom margin in <strong>px</strong> to align the tagline with your logo.', 'wpcasa-london' ),
		'settings'			=> 'tagline_margin'
	)));
	
	// Set tagline deactivation option

	$wp_customize->add_setting( 'deactivate_tagline', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'customize_tagline', array(
    	'section'   		=> 'title_tagline',
		'label'     		=> __( 'Hide tagline', 'wpcasa-london' ),
		'type'      		=> 'checkbox',
		'settings'			=> 'deactivate_tagline'
    ));
	
	// Set text logo
	
	$wp_customize->add_setting( 'wpcasa_logo_text', array(
        'capability'		=> 'edit_theme_options',
		// 'sanitize_callback'	=> 'strip_tags',
		'default'			=> get_bloginfo( 'name' ),
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	// Add text logo control
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wpcasa_logo_text', array(
		'label'				=> __( 'Text Logo', 'wpcasa-london' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'It will only be displayed if no regular logo image is provided.', 'wpcasa-london' ),
		'settings'			=> 'wpcasa_logo_text'
	)));
	
	// Set logo image
	
	$wp_customize->add_setting( 'wpcasa_logo', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
	));
	
	// Add logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo', array(
		'label'				=> __( 'Logo', 'wpcasa-london' ),
		'section'			=> 'title_tagline',
		'settings'			=> 'wpcasa_logo'
	)));
	
	// Set alt logo image
	
	$wp_customize->add_setting( 'wpcasa_logo_alt', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
	));
	
	// Add alt logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo_alt', array(
		'label'				=> __( 'Alt Logo', 'wpcasa-london' ),
		'section'			=> 'title_tagline',
		'description'		=> sprintf( __( 'Optionally upload an alternative logo. You can set the logo for individual pages through the <strong>%s</strong> on editor pages.', 'wpcasa-london' ), __( 'Header Settings', 'wpcasa-london' ) ),
		'settings'			=> 'wpcasa_logo_alt'
	)));
	
	// Set print logo image
	
	$wp_customize->add_setting( 'wpcasa_logo_print', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'type'				=> 'theme_mod',
	));
	
	// Add print logo upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'wpcasa_logo_print', array(
		'label'				=> __( 'Print Logo', 'wpcasa-london' ),
		'section'			=> 'title_tagline',
		'description'		=> __( 'Optionally upload a print logo that will be displayed in listing print versions or PDFs.', 'wpcasa-london' ),
		'settings'			=> 'wpcasa_logo_print'
	)));

}

/**
 * Register custom call to action background image
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_london_customize_register_bg_cta' );

function wpsight_london_customize_register_bg_cta( $wp_customize ) {
	
	// Set cta background image image
	
	$wp_customize->add_setting( 'bg_cta', array(
        'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'esc_url',
		'default'			=> get_template_directory_uri() . '/assets/images/bg-cta.png',
		'type'				=> 'theme_mod',
	));
	
	// Add upload control
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'bg_cta', array(
		'label'				=> __( 'Call to Action', 'wpcasa-london' ),
		'section'			=> 'background_image',
		'settings'			=> 'bg_cta'
	)));

}

/**
 * Register customizer bottom section theme_mods
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'wpsight_london_customize_register_bottom' );

function wpsight_london_customize_register_bottom( $wp_customize ) {

	// Add section 'Site Bottom'
	
	$wp_customize->add_section( 'wpsight_london_site_bottom' , array(
	    'title'				=> __( 'Site Bottom', 'wpcasa-london' ),
		'priority'			=> 40
	));
	
	// Set site bottom left
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_left', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> sprintf( __( 'Copyright &copy; <span itemprop="copyrightYear">%s</span> &middot; <a href="%s" rel="home" itemprop="copyrightHolder">%s</a>', 'wpcasa-london' ), date( 'Y' ), esc_url( home_url( '/' ) ), get_bloginfo( 'name' ) ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WPSight_London_Customize_Textarea_Control( $wp_customize, 'site_bottom_left', array(
		'label'				=> __( 'Site Bottom Left', 'wpcasa-london' ),
		'section'			=> 'wpsight_london_site_bottom',
		'settings'			=> 'wpcasa_site_bottom_left'
	)));
	
	// Set site bottom right
	
	$wp_customize->add_setting( 'wpcasa_site_bottom_right', array(
        'capability'		=> 'edit_theme_options',
        'default'			=> __( 'Built on <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>', 'wpcasa-london' ),
		'sanitize_callback' => 'wp_kses_post',
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WPSight_London_Customize_Textarea_Control( $wp_customize, 'site_bottom_right', array(
		'label'				=> __( 'Site Bottom Right', 'wpcasa-london' ),
		'section'			=> 'wpsight_london_site_bottom',
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
add_action( 'customize_register', 'wpsight_london_customize_register_color' );

function wpsight_london_customize_register_color( $wp_customize ) {
	
	// Set light section background color
	
	$wp_customize->add_setting( 'light_background_color', array(
		'default' 			=> '#fff',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_background_color', array(
	    'label'    => __( 'Section Background Color (light)', 'wpcasa-london' ),
	    'section'  => 'colors',
	    'settings' => 'light_background_color',
	)));
	
	// Set dark section background color
	
	$wp_customize->add_setting( 'dark_background_color', array(
		'default' 			=> '#333',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_background_color', array(
	    'label'    => __( 'Section Background Color (dark)', 'wpcasa-london' ),
	    'section'  => 'colors',
	    'settings' => 'dark_background_color',
	)));
	
	// Set light accent color
	
	$wp_customize->add_setting( 'light_accent_color', array(
		'default' 			=> '#d1c9c3',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_accent_color', array(
	    'label'    => __( 'Accent Color (light)', 'wpcasa-london' ),
	    'section'  => 'colors',
	    'settings' => 'light_accent_color',
	)));
	
	// Set dark accent color
	
	$wp_customize->add_setting( 'dark_accent_color', array(
		'default' 			=> '#c1b7af',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_accent_color', array(
	    'label'    => __( 'Accent Color (dark)', 'wpcasa-london' ),
	    'section'  => 'colors',
	    'settings' => 'dark_accent_color',
	)));
	
	// Set light background highlight color
	
	$wp_customize->add_setting( 'light_background_highlight_color', array(
		'default' 			=> '#f7f7f9',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_background_highlight_color', array(
	    'label'    			=> __( 'Light Background Highlight Color', 'wpcasa-london' ),
	    'section'  			=> 'colors',
	    'settings' 			=> 'light_background_highlight_color',
	)));
	
	// Set custom header image overlay color
	
	$wp_customize->add_setting( 'header_overlay_color', array(
		'default' 			=> '#333',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_overlay_color', array(
	    'label'    			=> __( 'Header Overlay Color', 'wpcasa-london' ),
	    'section'  			=> 'colors',
	    'settings' 			=> 'header_overlay_color',
	)));
	
	// Set image overlay deactivation option

	$wp_customize->add_setting( 'deactivate_header_overlay', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'transport'			=> 'postMessage',
	));

	$wp_customize->add_control( 'customize_header_overlay', array(
    	'section'   => 'colors',
		'label'     => __( 'Deactivate header overlay', 'wpcasa-london' ),
		'type'      => 'checkbox',
		'settings'	=> 'deactivate_header_overlay'
    ));

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_london_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_london_do_theme_mods_css' );

function wpsight_london_do_theme_mods_css() {

	$mods 	= '';
	$colors = '';
	
	// Hide tagline
	
	$hide_tagline = get_theme_mod( 'deactivate_tagline' );
	
	if( $hide_tagline )
		$mods .= '.site-description { display: none; }';
	
	// Set CTA background image

	$bg_cta = get_theme_mod( 'bg_cta', get_template_directory_uri() . '/assets/images/bg-cta.png' );
	
	if( $bg_cta )
		$mods .= '.site-section-cta, .wpsight-cta { background-image: url(' . esc_url( $bg_cta ) . '); }';
	
	// Deactivate header overlay

	$header_overlay = get_theme_mod( 'deactivate_header_overlay' );
	
	if( $header_overlay )
		$mods .= '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after { background-color: transparent !important; } .site-header-gallery .wpsight-image-background-slider-item { opacity: 1; }';
	
	// Set header image overlay color	
	$colors .= wpsight_london_generate_css( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after', 'background-color', 'header_overlay_color', '', '', false, '.5' );
	
	// Set light background color
	$colors .= wpsight_london_generate_css( '.site-container', 'background-color', 'light_background_color' );
	
	// Set dark background color
	$colors .= wpsight_london_generate_css( '.site-section-dark, .site-header-wrap, .site-footer, .site-bottom, .site-section .listings-title, .site-section-cta, .wpsight-cta, .wpsight-listings-slider-wrap', 'background-color', 'dark_background_color' );
	
	// Set light accent color
	
	$colors .= wpsight_london_generate_css( '.section-title:after', 'background-color', 'light_accent_color' );
	$colors .= wpsight_london_generate_css( '.wpsight-nav.nav > li > a:hover, .wpsight-nav.nav > li > a:focus, .wpsight-nav.nav .open > a, .wpsight-nav.nav .open > a:hover, .wpsight-nav.nav .open > a:focus, .accent, .site-bottom a, .wpsight-listings-slider-wrap .listing-content .wpsight-listing-price', 'color', 'light_accent_color' );
	
	// Set dark accent color
	
	$colors .= wpsight_london_generate_css( '.post .entry-content:before, .single-listing .wpsight-listing-agent:before, .wpsight-list-agents-sc .wpsight-list-agent:before, .archive.author .wpsight-list-agent:before, .comment-body:after, .bs-callout-primary:before, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before', 'background-color', 'dark_accent_color' );
	
	$colors .= wpsight_london_generate_css( '.single-listing .wpsight-listing-title .actions-print:hover:before, .single-listing .wpsight-listing-title .favorites-add:hover:before, .single-listing .wpsight-listing-title .favorites-see:hover:before', 'color', 'dark_accent_color' );
	
	$colors .= wpsight_london_generate_css( '.wpsight-newsletter-box, .feature-box-info, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before', 'border-color', 'dark_accent_color' );

	$colors .= wpsight_london_generate_css( '.site-section-dark .feature-box-info, .site-footer .feature-box-info, .site-footer .wpsight-newsletter-box', 'border-color', 'dark_accent_color', '', '', false, '.75' );
	
	// Set gradients with light & dark accent color
	
	$light_accent	= get_theme_mod( 'light_accent_color' );
	$dark_accent	= get_theme_mod( 'dark_accent_color' );
	
	if( ( $light_accent && '#d1c9c3' != $light_accent ) || ( $dark_accent && '#c1b7af' != $dark_accent ) ) {
	
		$light_accent_rgb	= wpsight_london_hex2rgb( $light_accent );
		$dark_accent_rgb	= wpsight_london_hex2rgb( $dark_accent );
		
		$colors .= '.site-wrapper .btn-primary, .site-wrapper .btn-primary:active, .site-wrapper .btn-primary.active, .site-wrapper .open > .dropdown-toggle.btn-primary, .tags-links .post-tag-wrap i, .single-listing .wpsight-listing-features .listing-term-wrap i, .feature-box-icon, .cmb2-wrap input.btn:focus, .cmb2-wrap input.btn:active {
			background: rgb(' . $light_accent_rgb . ');
			background: -moz-linear-gradient(top, rgba(' . $light_accent_rgb . ',1) 0%, rgba(' . $dark_accent_rgb . ',1) 100%);
			background: -webkit-linear-gradient(top, rgba(' . $light_accent_rgb . ',1) 0%,rgba(' . $dark_accent_rgb . ',1) 100%);
			background: linear-gradient(to bottom, rgba(' . $light_accent_rgb . ',1) 0%,rgba(' . $dark_accent_rgb . ',1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . $light_accent . '\', endColorstr=\'#' . $dark_accent . '\',GradientType=0 );
		}';
		
		$colors .= '.service-icon:before {
			background: rgb(' . $light_accent_rgb . ');
			background: -moz-linear-gradient(top, rgba(' . $light_accent_rgb . ',1) 0%, rgba(' . $dark_accent_rgb . ',1) 100%);
			background: -webkit-linear-gradient(top, rgba(' . $light_accent_rgb . ',1) 0%,rgba(' . $dark_accent_rgb . ',1) 100%);
			background: linear-gradient(to bottom, rgba(' . $light_accent_rgb . ',1) 0%,rgba(' . $dark_accent_rgb . ',1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . $light_accent . '\', endColorstr=\'#' . $dark_accent . '\',GradientType=0 );
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			display: initial;
			text-shadow: none;
		}';
	
	}
	
	// Set light background highlight color
	
	$colors .= wpsight_london_generate_css( '.site-wrapper .table-striped > tbody > tr:nth-of-type(odd), .header-full-width, .tags-links .post-tag-wrap, .archive .site-page-header, .wpsight-listings-search, .listings-search-reset, .listings-search-advanced-toggle, .wpsight-listings .listing-image-default, .wpsight-listing-compare .listing-details-detail:nth-child(odd), .wpsight-listing-teaser .listing-image-default, .single-listing .wpsight-listing-features .listing-term-wrap, .bs-callout, .feature-box-info, .wpsight-newsletter-box, .wpsight-pricing-table .pricing-plan-price, .wpsight-pricing-table .pricing-plan-action, .wpsight-dashboard-row-image .listing-image-default, .submission-steps.breadcrumb, .cmb2-wrap.form-table, .wpsight-dashboard-form.register-form, .wpsight-dashboard-form.login-form, .wpsight-dashboard-form.change-password-form, .wpsight-dashboard-form.reset-password-form, .wpsight-dashboard-form.payment-form, .wpsight-dashboard-form.package-form', 'background-color', 'light_background_highlight_color' );
	
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
 * @uses wpsight_london_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_london_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_london_hex2rgb( $mod );
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
function wpsight_london_hex2rgb( $hex ) {

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
 * Add WPSight_London_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'wpsight_london_customize_register_class_textarea', 9 );

function wpsight_london_customize_register_class_textarea() {

	class WPSight_London_Customize_Textarea_Control extends WP_Customize_Control {
	
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
