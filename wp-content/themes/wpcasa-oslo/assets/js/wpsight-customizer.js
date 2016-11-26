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
				$( '.site-description' ).css( 'display', 'block' );
			}
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
	
	wp.customize( 'wpcasa_site_top_icon_box_1_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-1 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-2 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-3 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_vertical', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.header-top' ).addClass( 'header-top-vertical' );
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-12' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-12' ).addClass( 'col-xs-12' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-6' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-6' ).addClass( 'col-xs-6' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-4' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-4' ).addClass( 'col-xs-4' );
				}
				
			} else {
				$( '.header-top' ).removeClass( 'header-top-vertical' );
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-12' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-12' ).addClass( 'col-sm-12' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-6' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-6' ).addClass( 'col-sm-6' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-4' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-4' ).addClass( 'col-sm-4' );
				}

			}
		} );
	} );
	
	wp.customize( 'wpcasa_site_bottom_right', function( value ) {
		value.bind( function( to ) {
			$( '.site-bottom-right' ).html( to );
		} );
	} );
	
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'main_text_color', function( value ) {
		value.bind( function( to ) {
			$( 'body.wpsight-oslo, .site-title-text, .site-description, .site-wrapper .btn-group .dropdown-menu > li > a, .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .single-listing .wpsight-listing-title .actions-print:before, .single-listing .wpsight-listing-title .favorites-add:before, .single-listing .wpsight-listing-title .favorites-see:before, .social-icons .fa, .site-wrapper .dropdown-toggle.btn-default, .site-wrapper .dropdown-toggle.btn-default:hover' ).css( 'color' , to );
		} );
	} );
	
	wp.customize( 'light_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-container, .site-section-dark .feature-box-info' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'dark_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper .table-striped > tbody > tr:nth-of-type(odd), .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .header-full-width, .site-section-dark, .tags-links .post-tag-wrap, .wpsight-listings-search, .listings-search-reset, .listings-search-advanced-toggle, #map-toggle-main-before .toggle-map, .wpsight-listings .listing-image-default, .wpsight-listing-compare .listing-details-detail:nth-child(odd), .wpsight-listings .wpsight-listing-summary .listing-details-detail, .wpsight-listings-carousel .wpsight-listing-summary .listing-details-detail, .wpsight-listing-teaser .listing-image-default, .single-listing .wpsight-listing-features .listing-term-wrap, .wpsight-pagination .pagination > li > a, .wpsight-pagination .pagination > li > span, .bs-callout, .wpsight-listings-carousel-prev-next .carousel-prev, .wpsight-listings-carousel-prev-next .carousel-next, .feature-box-info, .wpsight-dashboard-row-image .listing-image-default, .submission-steps.breadcrumb, .cmb2-wrap.form-table, .wpsight-dashboard-form.register-form, .wpsight-dashboard-form.login-form, .wpsight-dashboard-form.change-password-form, .wpsight-dashboard-form.reset-password-form, .wpsight-dashboard-form.payment-form, .wpsight-dashboard-form.package-form' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'header_overlay_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', hexToRgbA( to, '.35' ) );
		} );
	} );
	
} )( jQuery );

function hexToRgbA( hex, opacity ){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test( hex )){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+opacity+')';
    }
    throw new Error('Bad Hex');
}