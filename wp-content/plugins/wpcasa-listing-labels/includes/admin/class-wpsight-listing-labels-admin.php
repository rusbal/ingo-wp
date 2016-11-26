<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listing_Labels_Admin class
 */
class WPSight_Listing_Labels_Admin {

	/**
	 *	Constructor
	 */
	public function __construct() {
		
		// Add add-on options to general plugin settings
		add_filter( 'wpsight_options', array( $this, 'labels_options' ) );		
		
		// Add label options to listing attributes meta box		
		add_filter( 'wpsight_meta_box_listing_attributes_fields', array( $this, 'meta_box_attributes' ) );
		
		// Add addon license to licenses page
		add_filter( 'wpsight_licenses', array( $this, 'license' ) );
		
		// Add plugin updater
		add_action( 'admin_init', array( $this, 'update' ), 0 );

	}
	
	/**
	 *	labels_options()
	 *	
	 *	Add addon options to general
	 *	plugin settings page.
	 *	
	 *	@param	array	$options Registered options
	 *	@uses	wpsight_listing_labels()
	 *	@return	array	$options Updated options
	 *	
	 *	@since 1.0.0
	 */
	public function labels_options( $options ) {
		
		$options_labels = array(

			'labels_format' => array(
				'name' 		=> __( 'Label Format', 'wpcasa-listing-labels' ),
				'desc' 		=> __( 'Set the format of the label that marks listing. <code>%label_text%</code> will be replaced with the corresponding label text and <code>%label_class%</code> with the CSS class (see below).', 'wpcasa-listing-labels' ),
				'id' 		=> 'labels_format',
				'type' 		=> 'text',
				'default'	=> '<div class="wpsight-label %label_class%"><span>%label_text%</span></div>'
			),
			'labels_type' => array(
				'name' 		=> __( 'Label Type', 'wpcasa-listing-labels' ),
				'desc' 		=> __( 'Select the type of label listings are highlighted with.', 'wpcasa-listing-labels' ),
				'id' 		=> 'labels_type',
				'type' 		=> 'select',
				'options'	=> array(
					''					 => __( 'None', 'wpcasa-listing-labels' ),
					'thumbnail' 		 => __( 'Thumbnail Overlay', 'wpcasa-listing-labels' ),
					'title_before' 		 => __( 'Before Title', 'wpcasa-listing-labels' ),
					'title_after' 		 => __( 'After Title', 'wpcasa-listing-labels' ),
					'description_before' => __( 'Before Description', 'wpcasa-listing-labels' ),
					'description_after'  => __( 'After Description', 'wpcasa-listing-labels' )
				),
				'default'	=> 'thumbnail'
			)

		);
		
		$i=1;
		
		foreach( wpsight_listing_labels() as $key => $label ) {
		
			$options_labels[$key] = array(
			    'name'  	=> __( 'Label', 'wpcasa-listing-labels' ) . ' #' . $i,
			    'id' 		=> $key,
			    'default'  	=> $label['label'],
			    'desc'		=> sprintf( __( 'Use label CSS class <code>%s</code> to customize.', 'wpcasa-listing-labels' ), sanitize_html_class( 'wpsight-label-' . $key ) ),
			    'type' 		=> 'text'
			);
		
			$i++;
		
		}
		
		$options['labels'] = array(			
			__( 'Labels', 'wpcasa-listing-labels' ),
			apply_filters( 'wpsight_listing_labels_options', $options_labels )
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
	 *	@param	array	$fields	Registered meta box fields
	 *	@uses	current_user_can()
	 *	@uses	wpsight_listing_labels()
	 *	@return	array	$fields	Updated meta box fields
	 *	
	 *	@since 1.0.0
	 */
	public function meta_box_attributes( $fields ) {
		
		if( current_user_can( 'edit_published_listings' ) ) {
			
			// Prepare listing labels
			
			$labels = array();
			
			foreach( wpsight_listing_labels() as $key => $label )
				if( ! empty( $label['label'] ) )
					$labels[ $key ] = $label['label'];
		
			$fields['label'] = array(
				'name'  	=> __( 'Label', 'wpcasa-listing-labels' ),
				'id'    	=> '_listing_label',
				'type'  	=> 'select',
				'options' 	=> array_merge( array( '' => __( 'None', 'wpcasa-listing-labels' ) ), $labels ),
				'desc'  	=> __( 'Optionally select a label for this listing.', 'wpcasa-listing-labels' ),
				'dashboard' => false,
				'priority' 	=> 35
			);
		
		}
		
		return $fields;
		
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
		
		$licenses['listing_labels'] = array(
			'name' => WPSIGHT_LISTING_LABELS_NAME,
			'desc' => sprintf( __( 'For premium support and seamless updates for %s please activate your license.', 'wpcasa-listing-labels' ), WPSIGHT_LISTING_LABELS_NAME ),
			'id'   => wpsight_underscores( WPSIGHT_LISTING_LABELS_DOMAIN )
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
		$key = wpsight_underscores( WPSIGHT_LISTING_LABELS_DOMAIN );
	
		// Setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPSIGHT_SHOP_URL, WPSIGHT_LISTING_LABELS_PLUGIN_DIR . '/wpcasa-listing-labels.php', array(
				'version' 	=> WPSIGHT_LISTING_LABELS_VERSION,
				'license' 	=> isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false,
				'item_name' => WPSIGHT_LISTING_LABELS_NAME,
				'author' 	=> WPSIGHT_AUTHOR
			)
		);
	
	}

}
