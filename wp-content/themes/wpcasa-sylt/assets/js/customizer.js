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
	
	wp.customize( 'wpcasa_logo', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-logo img' ).attr( 'src', to );
		} );
	} );
	
} )( jQuery );