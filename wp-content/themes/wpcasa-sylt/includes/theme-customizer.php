<?php
/**
 * Theme Customizer
 *
 * @package WPCasa Sylt
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
add_action( 'customize_register', 'wpsight_sylt_customize_register' );

function wpsight_sylt_customize_register( $wp_customize ) {

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
add_action( 'customize_preview_init', 'wpsight_sylt_customize_preview_js' );

function wpsight_sylt_customize_preview_js() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wpsight_sylt_customizer', get_template_directory_uri() . '/assets/js/customizer' . $suffix . '.js', array( 'customize-preview' ), WPSIGHT_SYLT_VERSION, true );
}

/**
 * Register custom customizer logo options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */
add_action( 'customize_register', 'wpsight_sylt_customize_register_logo' );

function wpsight_sylt_customize_register_logo( $wp_customize ) {
	
	// Set logo image
	
	$wp_customize->add_setting( 'wpcasa_logo', array(
        'capability'	=> 'edit_theme_options',
		'default'		=> get_template_directory_uri() . '/assets/images/logo.png',
		'type'			=> 'option'
	));
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'customize_wpcasa_sylt_logo', array(
		'label'      => __( 'Logo', 'wpcasa-sylt' ),
		'section'    => 'title_tagline',
		'settings'   => 'wpcasa_logo'
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

add_action( 'customize_register', 'wpsight_sylt_customize_register_subheader' );

function wpsight_sylt_customize_register_subheader( $wp_customize ) {

	// Add section 'Header'
	
	$wp_customize->add_section( 'wpsight_sylt_subheader' , array(
	    'title'      => __( 'Header', 'wpcasa-sylt' ),
		'priority'   => 30
	));
	
	// Set header description display
	
	$wp_customize->add_setting( 'wpcasa_header_description_display', array(
        'capability'	=> 'edit_theme_options',
        'type'			=> 'option',
    ));
 
    $wp_customize->add_control( 'header_description_display', array(
        'label'		=> __( 'Display logo description', 'wpcasa-sylt' ),
        'section'	=> 'wpsight_sylt_subheader',
        'type'		=> 'checkbox',
        'settings'	=> 'wpcasa_header_description_display'
    ));
	
	// Set top bar text
	
	$wp_customize->add_setting( 'wpcasa_header_top_info', array(
        'capability'	=> 'edit_theme_options',
		'default' 			=> __( '<i class="icon fa-mobile-phone"></i> Call Us Today: 1-234-567-8910', 'wpcasa-sylt' ),
		'type' 				=> 'option',
		'sanitize_callback' => 'wp_kses_post'
	));
	
	$wp_customize->add_control( new wpsight_sylt_Customize_Textarea_Control( $wp_customize, 'customize_header_top_info', array(
		'label'    => __( 'Header Top Info', 'wpcasa-sylt' ),
		'section'  => 'wpsight_sylt_subheader',
		'settings' => 'wpcasa_header_top_info'
	)));
	
	// Set header top info display
	
	$wp_customize->add_setting( 'wpcasa_header_top_info_display', array(
        'capability'	=> 'edit_theme_options',
        'type'			=> 'option',
    ));
 
    $wp_customize->add_control( 'header_top_info_display', array(
        'label'		=> __( 'Display top info', 'wpcasa-sylt' ),
        'section'	=> 'wpsight_sylt_subheader',
        'type'		=> 'checkbox',
        'settings'	=> 'wpcasa_header_top_info_display'
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
add_action( 'customize_register', 'wpsight_sylt_customize_register_color' );

function wpsight_sylt_customize_register_color( $wp_customize ) {
	
	// Set custom accent color
	
	$wp_customize->add_setting( 'accent_color', array(
		'default' 			=> '#a4ce59',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_accent_color', array(
	    'label'    => __( 'Accent Color', 'wpcasa-sylt' ),
	    'section'  => 'colors',
	    'settings' => 'accent_color',
	)));
	
	// Set custom secondary color
	
	$wp_customize->add_setting( 'secondary_color_dark', array(
		'default' 			=> '#3c3b3b',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_secondary_color_dark', array(
	    'label'    => __( 'Secondary Color', 'wpcasa-sylt' ) . ' ' . __( '(dark)', 'wpcasa-sylt' ),
	    'section'  => 'colors',
	    'settings' => 'secondary_color_dark',
	)));
	
	// Set custom secondary color
	
	$wp_customize->add_setting( 'secondary_color_light', array(
		'default' 			=> '#f1f5f9',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_secondary_color_light', array(
	    'label'    => __( 'Secondary Color', 'wpcasa-sylt' ) . ' ' . __( '(light)', 'wpcasa-sylt' ),
	    'section'  => 'colors',
	    'settings' => 'secondary_color_light',
	)));
	
	// Set custom secondary color
	
	$wp_customize->add_setting( 'border_color', array(
		'default' 			=> '#dfe2e6',
		'type' 				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'customize_border_color', array(
	    'label'    => __( 'Border Color', 'wpcasa-sylt' ),
	    'section'  => 'colors',
	    'settings' => 'border_color',
	)));

}

/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses wpsight_sylt_generate_css()
 *
 * @since 1.0
 */
add_action( 'wp_head', 'wpsight_sylt_do_theme_mods_css' );

function wpsight_sylt_do_theme_mods_css() {

	$mods 	= '';
	$colors = '';
	
	// Set accent color
	
	$colors .= wpsight_sylt_generate_css( 'h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .accent, .site-header-top-info .icon:before, .wpsight-menu a:hover, .wpsight-menu .sub-menu a:hover, a.feature:hover .icon, .single-listing .wpsight-listing-section-features a.listing-term:hover, .single-listing .section-widget_listing_terms .listing-terms-blocks a.listing-term:hover', 'color', 'accent_color' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before, input[type="submit"],
input[type="reset"], input[type="button"], button, .button, input[type="submit"]:active, input[type="reset"]:active, input[type="button"]:active, button:active, .button:active, .calendar_wrap caption', 'background-color', 'accent_color' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="checkbox"]:checked + label:before, input[type="radio"]:checked + label:before', 'border-color', 'accent_color' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"]:hover, input[type="reset"]:hover, input[type="button"]:hover, button:hover, .button:hover', 'background-color', 'accent_color', '', '', false, '.85' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt', 'box-shadow', 'accent_color', 'inset 0 0 0 2px ', '', false, '.85' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'box-shadow', 'accent_color', 'inset 0 0 0 2px ' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active', 'box-shadow', 'accent_color', 'inset 0 0 0 2px ', '', false, '.75' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"].alt:active, input[type="reset"].alt:active, input[type="button"].alt:active, button.alt:active, .button.alt:active, input[type="submit"].alt, input[type="reset"].alt, input[type="button"].alt, button.alt, .button.alt', 'color', 'accent_color', '', ' !important', false, '.75' );
	
	$colors .= wpsight_sylt_generate_css( 'input[type="submit"].alt:hover, input[type="reset"].alt:hover, input[type="button"].alt:hover, button.alt:hover, .button.alt:hover', 'color', 'accent_color', '', ' !important' );
	
	// Set secondary (light) color
	
	$colors .= wpsight_sylt_generate_css( '.responsive-menu-icon::before, .site-page-title.site-section, .site-top, .site-bottom, .site-cta, #home-search, #home-slider .wpsight-listing-slider .listing-slider-overlay .wpsight-listing-summary, .wpsight-listings .listing-top, .wpsight-listings .wpsight-listing-summary, .wpsight-favorites-sc .favorites-remove, .wpsight-listing-compare .listing-details-detail:nth-child(even), .single-listing .site-main .section-widget_listing_price, .single-listing .site-main .content .wpsight-listing-section-info, .single-listing .site-top .section-widget_listing_price, .single-listing .site-bottom .section-widget_listing_price, .single-listing .wpsight-listing-section-features .listing-term, .single-listing .section-widget_listing_terms .listing-terms-blocks .listing-term, .single-listing .wpsight-listing-agent, .wpsight-list-agents-sc .wpsight-list-agent, .archive.author .wpsight-list-agent, .single-listing .site-top .wpsight-listing-agent-links a, .single-listing .site-bottom .wpsight-listing-agent-links a, .wpsight-image-slider-arrows [class*=\'owl-\'], .wpsight-image-slider-dots .owl-dot span, .wpsight-listings-carousel-arrows [class*=\'owl-\'], .wpsight-listings-carousel-dots .owl-dot span, .wpsight-listings-carousel .listing-top, .wpsight-listings-carousel .wpsight-listing-summary, .wpsight-listings-slider-arrows [class*=\'owl-\'], .wpsight-listings-slider-dots .owl-dot span, .wpsight-pagination a.page-numbers, .wpsight-pagination span.page-numbers, .wpsight-alert, .post .tags-links a, .comment-body, table tbody tr:nth-child(2n+1), input[type="text"], input[type="password"], input[type="search"], input[type="email"], input[type="tel"], input[type="url"], select, textarea, input[type="checkbox"] + label:before, input[type="radio"] + label:before, .posts-navigation .nav-previous, .posts-navigation .nav-next, .content .listings-search-reset, .content .listings-search-advanced-toggle, .sidebar .listings-search-reset, .sidebar .listings-search-advanced-toggle', 'background-color', 'secondary_color_light' );
	
	$colors .= wpsight_sylt_generate_css( '.wpsight-dashboard-sc div.mce-toolbar-grp, .wpsight-submit-sc div.mce-toolbar-grp', 'background-color', 'secondary_color_light', '', ' !important' );
	
	$colors .= wpsight_sylt_generate_css( '.wpsight-favorites-sc .favorites-remove:hover', 'background-color', 'secondary_color_light', '', '', false, '.5' );
	
	$colors .= wpsight_sylt_generate_css( '.wpsight-menu .sub-menu', 'background-color', 'secondary_color_light', '', '', false, '.95' );
	
	$colors .= '@media screen and (max-width: 980px) { ' . wpsight_sylt_generate_css( '.wpsight-menu.responsive-menu .sub-menu', 'background-color', 'secondary_color_light', '', '', false, '.5' ) . ' }';
	
	$colors .= '@media screen and (min-width: 981px) { ' . wpsight_sylt_generate_css( '.nav-primary .wpsight-menu .sub-menu:before', 'border-bottom-color', 'secondary_color_light' ) . ' }';
	
	// Set seconday (dark) color
	
	$colors .= wpsight_sylt_generate_css( 'a, h1, h2, h3, h4, h5, h6, body, input, select, textarea', 'color', 'secondary_color_dark' );
	
	$colors .= wpsight_sylt_generate_css( 'a:hover', 'color', 'secondary_color_dark', '', '', false, '.75' );
	
	$colors .= wpsight_sylt_generate_css( '#home-search .listings-search-reset, #home-search .listings-search-advanced-toggle', 'background-color', 'secondary_color_dark', '', '', false, '.05' );
	
	$colors .= wpsight_sylt_generate_css( '#home-icons, input[type="submit"].special, input[type="reset"].special, input[type="button"].special, button.special, .button.special, input[type="submit"].special:active, input[type="reset"].special:active, input[type="button"].special:active, button.special:active, .button.special:active, .site-footer-bg', 'background-color', 'secondary_color_dark' );
	
	$colors .= '@media screen and (max-width: 980px) { ' . wpsight_sylt_generate_css( '#home-slider .wpsight-listings-slider-arrows, #home-gallery .wpsight-image-slider-arrows', 'background-color', 'secondary_color_dark' ) . ' }';
	
	// Set border color
	
	$colors .= wpsight_sylt_generate_css( '.site-header-top, .wpsight-menu .sub-menu, .nav-top .wpsight-menu a, .nav-top .wpsight-menu > .first-menu-item > a, .nav-top .wpsight-menu > .first-menu-item > a, .nav-top .responsive-menu-icon::before, .nav-primary .responsive-menu-icon::before, .nav-secondary, .site-page-title.site-section, .site-top, .site-bottom, .site-cta, #home-search, .wpsight-listings-search, .listings-panel-wrap, .wpsight-listing-archive, .wpsight-listing-summary .listing-details-detail, .wpsight-favorites-sc .favorites-remove, .wpsight-listing-teaser, .single-listing .site-main .section-widget_listing_price, .single-listing .site-main .content .wpsight-listing-section-info, .single-listing .site-top .section-widget_listing_price, .single-listing .site-bottom .section-widget_listing_price, .single-listing .wpsight-listing-details .listing-details-detail, .single-listing .wpsight-listing-details .listing-details-detail, .single-listing .wpsight-listing-section-features .listing-term, .single-listing .section-widget_listing_terms .listing-terms-blocks .listing-term, .single-listing .wpsight-listing-agent, .wpsight-list-agents-sc .wpsight-list-agent, .archive.author .wpsight-list-agent, .single-listing .wpsight-listing-agent-links a, .wpsight-list-agents-sc .wpsight-list-agent-links a, .archive.author .wpsight-list-agent-links a, .wpsight-listing-carousel, .wpsight-listings-carousel .listing-top, .wpsight-listings-carousel .wpsight-listing-summary, #home-slider .wpsight-listing-slider .listing-slider-overlay .wpsight-listing-summary, .wpsight-pagination a.page-numbers, .wpsight-pagination span.page-numbers, .post .entry-meta, .post .entry-footer, .post-navigation, .entry-content .page-links, .page .comments-area, table tbody tr, input[type="text"], input[type="password"], input[type="search"], input[type="email"], input[type="tel"], input[type="url"], select, textarea, .posts-navigation .nav-previous, .posts-navigation .nav-next, table thead, table tfoot, .calendar_wrap', 'border-color', 'border_color' );
	
	$colors .= wpsight_sylt_generate_css( '.wpsight-listings .listing-top:after, .wpsight-listings .wpsight-listing-summary:before', 'background-color', 'border_color' );
	
	$colors .= wpsight_sylt_generate_css( '.select-wrapper:before', 'color', 'border_color' );
	
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
 * @uses wpsight_sylt_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
function wpsight_sylt_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = wpsight_sylt_hex2rgb( $mod );
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
function wpsight_sylt_hex2rgb( $hex ) {

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
 * Add wpsight_sylt_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'wpsight_sylt_customize_register_class_textarea', 9 );

function wpsight_sylt_customize_register_class_textarea() {

	class WPSight_Sylt_Customize_Textarea_Control extends WP_Customize_Control {
	
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
