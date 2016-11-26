/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
 ( function( $ ) {

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	wp.customize( 'deactivate_tagline', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.site-description' ).css( 'display', 'none' );
			} else {
				$( '.site-description' ).css( 'display', 'table-cell' );
			}
		} );
	} );
	
	wp.customize( 'tagline_margin', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).css( 'padding-bottom', to + 'px' );
		} );
	} );
	
	wp.customize( 'wpcasa_logo_text', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-text a' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_logo', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-logo img' ).attr( 'src', to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_bottom_left', function( value ) {
		value.bind( function( to ) {
			$( '.site-bottom-left' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_bottom_right', function( value ) {
		value.bind( function( to ) {
			$( '.site-bottom-right' ).html( to );
		} );
	} );
	
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			$( '.header-tagline #tagline, .header-title h1, .header-featured-image h1, .header-full-width .wpsight-listings-slider-wrap .listing-content .wpsight-listing-title .entry-title, .site-wrapper .header-top .wpsight-nav > li > a, .site-wrapper .header-top .wpsight-nav > li > a:hover, .site-title-text, .site-description' ).css( 'color' , to );
		} );
	} );
	
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'light_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-container' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'dark_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-section-dark, .site-footer, .site-bottom, .site-section .listings-title, .site-section-cta, .wpsight-cta' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'header_overlay_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', hexToRgbA( to ) );
		} );
	} );
	
	wp.customize( 'deactivate_header_overlay', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', 'transparent' );
			} else {
				$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', 'rgba(51,51,51,.5)' );
			}
		} );
	} );
	
} )( jQuery );

function hexToRgbA( hex ){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test( hex )){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',.5)';
    }
    throw new Error('Bad Hex');
}