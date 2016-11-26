<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Expire_Listings_Admin class
 */
class WPSight_Expire_Listings_Admin {

	/**
	 *	Constructor
	 */
	public function __construct() {

		// Add listings and dashboard settings

		add_filter( 'wpsight_options_listings', array( $this, 'expire_options_listings' ) );
		add_filter( 'wpsight_options_dashboard', array( $this, 'expire_options_dashboard' ) );		
		
		// Add expire option with calendar to listing attributes meta box		
		add_filter( 'wpsight_meta_box_listing_attributes_fields', array( $this, 'meta_box_attributes' ) );
		
		// Add expire/relist button to listings table
		add_filter( 'wpsight_admin_actions', array( $this, 'admin_actions' ), 10, 2 );
		
		// Manage bulk actions
		
		add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_actions' ) );
		add_action( 'load-edit.php', array( $this, 'do_bulk_actions' ) );
		
		// Handle expire button and add admin notices
		
		add_action( 'admin_init', array( $this, 'expire_listing' ) );
		add_action( 'admin_notices', array( $this, 'expired_notice' ) );
		
		// Add addon license to licenses page
		add_filter( 'wpsight_licenses', array( $this, 'license' ) );
		
		// Add plugin updater
		add_action( 'admin_init', array( $this, 'update' ), 0 );

	}
	
	/**
	 *	expire_options_listings()
	 *	
	 *	Add listing duration option to
	 *	listing settings.
	 *	
	 *	@param	array	$options	Registered listing options
	 *	@return	array	$options	Updated set of options
	 *	
	 *	@since 1.0.0
	 */
	public function expire_options_listings( $options ) {
		
		$options['listing_duration'] = array( 
			'name'  	=> __( 'Listing Duration', 'wpcasa-expire-listings' ),
			'id'    	=> 'listing_duration',
			'desc'  	=> __( 'Set the number of <strong>days</strong> a listing is live before expiring. Leave blank to never expire.', 'wpcasa-expire-listings' ),
			'type'  	=> 'text',
			'default'	=> 30
		);
		
		return $options;
		
	}
	
	/**
	 *	expire_options_dashboard()
	 *	
	 *	Add listing duration option to
	 *	dashboard settings.
	 *	
	 *	@param	array	$options	Registered dashboard options
	 *	@return	array	$options	Updated set of options
	 *	
	 *	@since 1.0.0
	 */
	public function expire_options_dashboard( $options ) {
		
		$options['dashboard_duration'] = array( 
			'name'  	=> __( 'Listing Duration', 'wpcasa-expire-listings' ),
			'id'    	=> 'dashboard_duration',
			'desc'  	=> __( 'Set the number of <strong>days</strong> a listing submitted through dashboard is live before expiring. Leave blank to never expire.', 'wpcasa-expire-listings' ),
			'type'  	=> 'text',
			'default'	=> 30
		);
		
		return $options;
		
	}
	
	/**
	 *	meta_box_attributes()
	 *	
	 *	Add expire field with calendar
	 *	to listing attributes meta box
	 *	if user can 'edit_published_listings'.
	 *
	 *	@param	array	$fields	Registered fields
	 *	@uses	current_user_can()
	 *	@return	array	$fields	Upated fields array
	 *	
	 *	@since 1.0.0
	 */
	public function meta_box_attributes( $fields ) {
		
		if( current_user_can( 'edit_published_listings' ) ) {
		
			$fields['expires'] = array(
				'name'  	=> __( 'Expires', 'wpcasa-expire-listings' ),
				'id'    	=> '_listing_expires',
				'type'  	=> 'text_date_timestamp',
				'desc'  	=> __( 'Once this listing expires it will no longer be visible on the website.', 'wpcasa-expire-listings' ),
				'dashboard' => false,
				'priority' 	=> 40
			);
		
		}
		
		return $fields;
		
	}
	
	/**
	 *	admin_actions()
	 *	
	 *	Add expire/relist admin action
	 *	to edit listings table in admin.
	 *	
	 *	@param	array	$admin_actions
	 *	@param	object	$_post
	 *	@uses	wpsight_is_listing_expired()
	 *	@uses	add_query_arg()
	 *	@uses	wp_nonce_url()
	 *	@return	array	$admin_actions
	 *	
	 *	@since 1.0.0
	 */
	public function admin_actions( $admin_actions, $_post ) {
		
		if( $_post->post_status !== 'trash' ) {
		
			$admin_actions['expire'] = array(
				'action'   => 'expire',
				'name'     => wpsight_is_listing_expired( $_post->ID ) ? __( 'Relist', 'wpcasa-expire-listings' ) : __( 'Expire', 'wpcasa-expire-listings' ),
				'url'      =>  wp_nonce_url( add_query_arg( 'expire_listing', $_post->ID ), 'expire_listing' ),
				'cap'	   => 'publish_listings',
				'priority' => 12
			);
		
		}
		
		return $admin_actions;
		
	}

	/**
	 *	add_bulk_actions()
	 *	
	 *	Add our custom bulk actions
	 *	to WordPress dropdown.
	 *	
	 *	@uses wpsight_post_type()
	 *	
	 *	@since 1.0.0
	 */
	public function add_bulk_actions() {
		global $post_type, $wp_post_types;

		if ( $post_type == wpsight_post_type() ) {
			?>
			<script type="text/javascript">
		      jQuery(document).ready(function() {

		        jQuery('<option>').val('expire_listings').text('<?php _e( 'Expire', 'wpcasa-expire-listings' ); ?>').appendTo("select[name='action']");
		        jQuery('<option>').val('expire_listings').text('<?php _e( 'Expire', 'wpcasa-expire-listings' ); ?>').appendTo("select[name='action2']");

		        jQuery('<option>').val('relist_listings').text('<?php _e( 'Relist', 'wpcasa-expire-listings' ); ?>').appendTo("select[name='action']");
		        jQuery('<option>').val('relist_listings').text('<?php _e( 'Relist', 'wpcasa-expire-listings' ); ?>').appendTo("select[name='action2']");

		      });
		    </script>
		    <?php
		}

	}

	/**
	 *	do_bulk_actions()
	 *	
	 *	Execute our custom bulk actions
	 *	if selected in WordPress dropdown.
	 *	
	 *	@uses	_get_list_table()
	 *	@uses	$wp_list_table->current_action()
	 *	@uses	check_admin_referer()
	 *	@uses	wp_update_post()
	 *	@uses	admin_url()
	 *	@uses	remove_query_arg()
	 *	@uses	add_query_arg()
	 *	@uses	wp_redirect()
	 *	@uses	wpsight_set_listing_expiry()
	 *	@return null
	 *	
	 *	@since 1.0.0
	 */
	public function do_bulk_actions() {

		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$list_action   = $wp_list_table->current_action();
		
		// Set array of vars to remove
				
		$remove_vars = array(
			'approved_listings',
			'expired_listings'
		);
		
		// Check current action

		switch( $list_action ) {
			
			// Bulk approve listings

			case 'expire_listings' :
				check_admin_referer( 'bulk-posts' );

				// Get post IDs from $_GET
				$post_ids     = array_map( 'absint', array_filter( (array) $_GET['post'] ) );

				$expired = array();
				
				// Check if there are post IDs

				if ( ! empty( $post_ids ) ) {
					
					// Loop through post IDs, if any
					
					foreach( $post_ids as $post_id ) {
						
						// Set ID and post status
						
						$listing_data = array(
							'ID'          => $post_id,
							'post_status' => 'expired'
						);
						
						// Update post status and collect post ID
						
						if ( wp_update_post( $listing_data ) ) {
							
							// Collect post ID
							$expired[] = $post_id;

							// Update expiry date to today
							update_post_meta( $post_id, '_listing_expires', current_time( 'timestamp' ) );
						
						}

					} // endforeach
				
				} // endif ! empty( $post_ids )

				// Add expired listings to URL and redirect to list
				wp_redirect( add_query_arg( 'expired_listings', $expired, remove_query_arg( $remove_vars, admin_url( 'edit.php?post_type=listing' ) ) ) );

				exit;
			break;
			
			case 'relist_listings' :
				check_admin_referer( 'bulk-posts' );

				// Get post IDs from $_GET
				$post_ids = array_map( 'absint', array_filter( (array) $_GET['post'] ) );

				$relisted = array();
				
				// Check if there are post IDs

				if ( ! empty( $post_ids ) ) {
					
					// Loop through post IDs, if any
					
					foreach( $post_ids as $post_id ) {
						
						// Set ID and post status
						
						$listing_data = array(
							'ID'          => $post_id,
							'post_status' => 'publish'
						);
						
						// Update post status and collect post ID
						
						if ( wp_update_post( $listing_data ) ) {
							
							$relisted[] = $post_id;
							wpsight_set_listing_expiry( $post_id, '', true );

						}

					} // endforeach
				
				} // endif ! empty( $post_ids )

				// Add expired listings to URL and redirect to list
				wp_redirect( add_query_arg( 'expired_listings', $relisted, remove_query_arg( $remove_vars, admin_url( 'edit.php?post_type=listing' ) ) ) );

				exit;
			break;

		} // end switch( $list_action )

		return;

	}

	/**
	 *	expire_listing()
	 *	
	 *	Expire a single listing with action button.
	 *	
	 *	@uses	wp_verify_nonce()
	 *	@uses	current_user_can()
	 *	@uses	wpsight_is_listing_expired()
	 *	@uses	wpsight_set_listing_expiry()
	 *	@uses	current_time()
	 *	@uses	update_post_meta()
	 *	@uses	wp_update_post()
	 *	@uses	admin_url()
	 *	@uses	add_query_arg()
	 *	@uses	remove_query_arg()
	 *	@uses	wp_redirect()
	 *	
	 *	@since 1.0.0
	 */
	public function expire_listing() {
		
		// Get listing to approve from $_GET, check nonce and if current user can

		if ( ! empty( $_GET['expire_listing'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'expire_listing' ) && current_user_can( 'edit_post', $_GET['expire_listing'] ) ) {
			
			// Get post ID
			$post_id = absint( $_GET['expire_listing'] );
			
			// Check if listing is currently expired or not
			
			if( wpsight_is_listing_expired( $post_id ) ) {				
				// Set post status
				$status = 'publish';				
				wpsight_set_listing_expiry( $post_id, '', true );				
			} else {				
				// Set post status
				$status = 'expired';				
				// Update expiry date to today
				update_post_meta( $post_id, '_listing_expires', current_time( 'timestamp' ) );				
			}
			
			// Set ID and post status
			
			$listing_data = array(
				'ID'          => $post_id,
				'post_status' => $status
			);
			
			// Update post
			wp_update_post( $listing_data );
			
			// Add expired listing to URL and redirect to list
			wp_redirect( remove_query_arg( 'expire_listing', add_query_arg( 'expired_listings', $post_id, admin_url( 'edit.php?post_type=listing' ) ) ) );

			exit;

		} // endif

	}

	/**
	 *	expired_notice()
	 *	
	 *	Show a notice if we did a bulk action or single expire.
	 *	
	 *	@uses	wpsight_post_type()
	 *	@uses	wpsight_is_listing_expired()
	 *	@uses	get_the_title()
	 *	
	 *	@since 1.0.0
	 */
	public function expired_notice() {
		global $post_type, $pagenow;

		// Check if we have listings to expire and are on the right page

		if ( $pagenow == 'edit.php' && $post_type == wpsight_post_type() && ! empty( $_REQUEST['expired_listings'] ) ) {
			
			// Get listings to expire from $_REQUEST
			$expired_listings = $_REQUEST['expired_listings'];
			
			// Check if we have multiple (array) or a single listing
			
			if ( is_array( $expired_listings ) ) {
				
				// Make sure we have positive integers
				$expired_listings = array_map( 'absint', $expired_listings );
				
				$titles_expired	 = array();
				$titles_relisted = array();
				
				// Loop through listings and collect titles
				
				foreach ( $expired_listings as $listing_id ) {					
					if( wpsight_is_listing_expired( $listing_id ) ) {						
						// Titles of listings marked sticky
						$titles_expired[] = get_the_title( $listing_id );
					} else {					
						// Titles of listings marked sticky					
						$titles_relisted[] = get_the_title( $listing_id );
					} // wpsight_is_listing_sticky()

				} // endforeach
					
				// Display update message with titles of listings marked sticky
				
				if( $titles_expired )
					echo '<div class="updated"><p>' . sprintf( __( '%s have now expired', 'wpcasa-expire-listings' ), '&quot;' . implode( '&quot;, &quot;', $titles_expired ) . '&quot;' ) . '</p></div>';
				
				// Display update message with titles of unmarked listings
				
				if( $titles_relisted )
					echo '<div class="updated"><p>' . sprintf( __( '%s have been relisted', 'wpcasa-expire-listings' ), '&quot;' . implode( '&quot;, &quot;', $titles_relisted ) . '&quot;' ) . '</p></div>';

			} else {
				
				// Display update message with title of single listing

				if( wpsight_is_listing_expired( $expired_listings ) ) {
					echo '<div class="updated"><p>' . sprintf( __( '%s has now expired', 'wpcasa-expire-listings' ), '&quot;' . get_the_title( $expired_listings ) . '&quot;' ) . '</p></div>';
				} else {
					echo '<div class="updated"><p>' . sprintf( __( '%s has been relisted', 'wpcasa-expire-listings' ), '&quot;' . get_the_title( $expired_listings ) . '&quot;' ) . '</p></div>';
				}

			} // endif is_array( $expired_listings )

		} // endif

	}
	
	/**
	 *	license()
	 *	
	 *	Add addon license to licenses page
	 *	
	 *	@return	array	$options_licenses
	 *	
	 *	@since 1.0.0
	 */
	public static function license( $licenses ) {
		
		$licenses['expire_listings'] = array(
			'name' => WPSIGHT_EXPIRE_LISTINGS_NAME,
			'desc' => sprintf( __( 'For premium support and seamless updates for %s please activate your license.', 'wpcasa-expire-listings' ), WPSIGHT_EXPIRE_LISTINGS_NAME ),
			'id'   => wpsight_underscores( WPSIGHT_EXPIRE_LISTINGS_DOMAIN )
		);
		
		return $licenses;
	
	}
	
	/**
	 *	update()
	 *	
	 *	Set up EDD plugin updater.
	 *	
	 *	@uses	class_exists()
	 *	@uses	get_option()
	 *	@uses	wpsight_underscores()
	 *	
	 *	@since 1.0.0
	 */
	function update() {
		
		if( ! class_exists( 'EDD_SL_Plugin_Updater' ) )
			return;

		// Get license option
		$licenses = get_option( 'wpsight_licenses' );		
		$key = wpsight_underscores( WPSIGHT_EXPIRE_LISTINGS_DOMAIN );
	
		// Setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPSIGHT_SHOP_URL, WPSIGHT_EXPIRE_LISTINGS_PLUGIN_DIR . '/wpcasa-expire-listings.php', array(
				'version' 	=> WPSIGHT_EXPIRE_LISTINGS_VERSION,
				'license' 	=> isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false,
				'item_name' => WPSIGHT_EXPIRE_LISTINGS_NAME,
				'author' 	=> WPSIGHT_AUTHOR
			)
		);
	
	}

}
