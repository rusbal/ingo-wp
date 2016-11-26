<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Favorites_Admin class
 */
class WPSight_Favorites_Admin {

	/**
	 *	Constructor
	 */
	public function __construct() {
	
		// Add add-on options to general plugin settings
		add_filter( 'wpsight_options', array( $this, 'favorites_options' ) );
		
		// Add addon license to licenses page
		add_filter( 'wpsight_licenses', array( $this, 'license' ) );
		
		// Add plugin updater
		add_action( 'admin_init', array( $this, 'update' ), 0 );

	}
	
	/**
	 *	favorites_options()
	 *
	 *	Add add-on options to general plugin settings.
	 *
	 *	@param	array	$options	Registered plugin options
	 *	@return	array	$options	Updated plugin options
	 *
	 *	@since 1.0.0
	 */
	public function favorites_options( $options ) {
		
		$options['favorites'] = array(
			
			__( 'Favorites', 'wpcasa-favorites' ),
			array(
				'favorites_page' => array(
					'name' 		=> __( 'Page', 'wpcasa-favorites' ),
					'desc' 		=> __( 'Please select the page that contains the favorites shortcode.', 'wpcasa-favorites' ),
					'id' 		=> 'favorites_page',
					'type' 		=> 'pages'
				),
				'favorites_add' => array(
					'name' 		=> __( 'Label Add', 'wpcasa-favorites' ),
					'desc' 		=> __( 'Please enter the label for the add favorite button.', 'wpcasa-favorites' ),
					'id' 		=> 'favorites_add',
					'type' 		=> 'text',
					'default'	=> _x( 'Save', 'favorites button labels', 'wpcasa-favorites' )
				),
				'favorites_see' => array(
					'name' 		=> __( 'Label See', 'wpcasa-favorites' ),
					'desc' 		=> __( 'Please enter the label for the see favorite button.', 'wpcasa-favorites' ),
					'id' 		=> 'favorites_see',
					'type' 		=> 'text',
					'default'	=> _x( 'Favorites', 'favorites button labels', 'wpcasa-favorites' )
				),
				'favorites_badge' => array(
					'name' 		=> __( 'Badge', 'wpcasa-favorites' ),
					'desc' 		=> __( 'Set the format of the badge that displays the number of favorites.', 'wpcasa-favorites' ),
					'id' 		=> 'favorites_badge',
					'type' 		=> 'text',
					'default'	=> ' <span class="badge">%number%</span>'
				),
				'favorites_expire' => array(
					'name' 		=> __( 'Cookie Expiry', 'wpcasa-favorites' ),
					'desc' 		=> __( 'Set the number of <strong>days</strong> a favorite listing is saved.', 'wpcasa-favorites' ),
					'id' 		=> 'favorites_expire',
					'type' 		=> 'text',
					'default'	=> 30
				)
			)

		);
		
		return $options;
		
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
		
		$licenses['favorites'] = array(
			'name' => WPSIGHT_FAVORITES_NAME,
			'desc' => sprintf( __( 'For premium support and seamless updates for %s please activate your license.', 'wpcasa-favorites' ), WPSIGHT_FAVORITES_NAME ),
			'id'   => wpsight_underscores( WPSIGHT_FAVORITES_DOMAIN )
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
		$key = wpsight_underscores( WPSIGHT_FAVORITES_DOMAIN );
	
		// Setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPSIGHT_SHOP_URL, WPSIGHT_FAVORITES_PLUGIN_DIR . '/wpcasa-favorites.php', array(
				'version' 	=> WPSIGHT_FAVORITES_VERSION,
				'license' 	=> isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false,
				'item_name' => WPSIGHT_FAVORITES_NAME,
				'author' 	=> WPSIGHT_AUTHOR
			)
		);
	
	}
}
