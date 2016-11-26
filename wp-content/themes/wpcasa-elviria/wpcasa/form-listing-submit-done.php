<?php
global $wp_post_types;
$post_type = wpsight_post_type();

switch ( $listing->post_status ) :

	case 'publish' :
		printf( __( '%s published successfully. <a href="%s" class="button alt small">View Listing</a>', 'wpcasa-elviria' ), $wp_post_types[$post_type]->labels->singular_name, get_permalink( $listing->ID ) );
	break;

	case 'pending' :
		printf( __( '%s submitted successfully. It will be visible once approved.', 'wpcasa-elviria' ), $wp_post_types[$post_type]->labels->singular_name, get_permalink( $listing->ID ) );
	break;

	default :
		do_action( 'wpsight_listing_submitted_' . str_replace( '-', '_', sanitize_title( $listing->post_status ) ), $listing );
	break;

endswitch;