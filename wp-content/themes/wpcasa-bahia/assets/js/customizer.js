/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-text a' ).html( to ).text();
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	wp.customize( 'wpcasa_bahia_logo', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-logo img' ).attr( 'src', to );
		} );
	} );
	
	wp.customize( 'wpcasa_bahia_logo_bg', function( value ) {
		value.bind( function( to ) {
			$( '.site-header.site-section' ).css('background-image', to );
		} );
	} );
	
} )( jQuery );