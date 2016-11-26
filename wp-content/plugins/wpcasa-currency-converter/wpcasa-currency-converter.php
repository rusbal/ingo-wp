<?php
/*
Plugin Name: WPCasa Currency Converter
Plugin URI: https://wpcasa.com/downloads/wpcasa-currency-converter
Description: Let users select their preferred currency and display all listing prices accordingly.
Version: 1.0.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.3.1
Text Domain: wpcasa-currency-converter
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Currency_Converter class
 */
class WPSight_Currency_Converter {

	/**
	 * Initializes the plugin by setting localization and filters functions.
	 */
	function __construct() {
		
		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );
		
		if ( ! defined( 'WPSIGHT_SHOP_URL' ) )
			define( 'WPSIGHT_SHOP_URL', 'https://wpcasa.com' );

		if ( ! defined( 'WPSIGHT_AUTHOR' ) )
			define( 'WPSIGHT_AUTHOR', 'WPSight' );
		
		define( 'WPSIGHT_CURRENCY_CONVERTER_NAME', 'WPCasa Currency Converter' );
		define( 'WPSIGHT_CURRENCY_CONVERTER_DOMAIN', 'wpcasa-currency-converter' );
		define( 'WPSIGHT_CURRENCY_CONVERTER_VERSION', '1.0.0' );
		define( 'WPSIGHT_CURRENCY_CONVERTER_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_CURRENCY_CONVERTER_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		// Set textdomain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Register scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_assets' ) );

		// Add shortcode
		add_shortcode( 'wpsight_currency_converter', array( $this, 'currency_converter_shortcode' ) );

		// The AJAX handling function
		add_action( 'wp_ajax_nopriv_convert_currency', array( $this, 'ajax_convert_currency' ) );
		add_action( 'wp_ajax_convert_currency', array( $this, 'ajax_convert_currency' ) );

		// Do the currency conversion via filter.
		add_filter( 'wpsight_convert_currency', array( $this, 'yahoo_convert_currency' ), 10, 2 );
		
		// Add addon license to licenses page
		add_filter( 'wpsight_licenses', array( $this, 'license' ) );
		
		// Add plugin updater
		add_action( 'admin_init', array( $this, 'update' ), 0 );

	}

	/**
	 *	init()
	 *
	 *  Initialize the plugin when WPCasa is loaded
	 *
	 *  @param   WPSight_Framework  $wpsight
	 *  @return  object
	 *
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {

		if ( ! isset( $wpsight->currency_converter ) ) {
			$wpsight->currency_converter = new self( $wpsight );
		}
		do_action_ref_array( 'wpsight_init_currency_converter', array( &$this, &$wpsight ) );

		return $wpsight->currency_converter;
	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-currency-converter', false, WPSIGHT_CURRENCY_CONVERTER_PLUGIN_DIR . '/lang/' );
	}

	/**
	 *	register_plugin_assets()
	 *
	 *	Registers and enqueues plugin-specific scripts.
	 *
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_localize_script()
	 *
	 *	@since 1.0.0
	 */
	public function register_plugin_assets() {

		// Enqueue CSS styles
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'wpcasa-currency', WPSIGHT_CURRENCY_CONVERTER_PLUGIN_URL . "/assets/js/wpcasa-currency$suffix.js", array( 'wp-util' ), WPSIGHT_CURRENCY_CONVERTER_VERSION, true );

		// Parameters to JS
		$params = array(
			'loading_text' => __( 'Converting...', 'wpcasa-currency-converter' )
		);

		wp_localize_script( 'wpcasa-currency', 'wpcasaCurrency', $params );
	}

	/**
	 *	currency_converter_shortcode()
	 *
	 *  Returns the currency converter output
	 *
	 *  @param  array  $atts
	 *	@uses	shortcode_atts()
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_currencies()
	 *  @return string
	 *
	 *	@since 1.0.0
	 */
	public static function currency_converter_shortcode( $atts ) {

		static $counter = 0;

		extract( shortcode_atts( array(
			'class'      => 'currency-select-'. $counter,
			'currencies' => 'eur,usd,gbp,jpy',
			'id'         => 'currency-select-'. $counter,
			'selected'   => wpsight_get_option( 'currency', 'usd' )
		), $atts, 'wpsight_currency_converter' ) );

		$wpsight_currencies = wpsight_currencies();

		// allow displaying all available currencies without writing them all out individually
		if ( 'all' == $currencies ) {
			$currencies = array_filter( array_keys( $wpsight_currencies ) );
		}
		// convert given string option to array
		if ( is_string( $currencies ) ) {
			$currencies = explode( ',', $currencies );
		}

		// Build select output
		$output = '<select class="currency-select '. sanitize_html_class( $class ) .'" id="'. esc_attr( $id ) .'">';

		// Display given currencies
		foreach ( $currencies as $currency ) {
			$currency = strtolower( $currency );
			$selected_attr = selected( $currency, $selected, false );
			$display = substr( $wpsight_currencies[ $currency ], 7 ) . ' (' . strtoupper( $currency ) . ')';
			$output .= '<option value="' . $currency . '" '. $selected_attr . '>' . $display . '</option>';
		}

		$output .= '</select>';

		$counter++;

		return apply_filters( 'wpsight_currency_converter_shortcode', $output, $atts );

	}

	/**
	 *	ajax_convert_currency()
	 *
	 *  Handles the AJAX calls and returns either a json success or error string
	 *
	 *	@uses	absint()
	 *	@uses	sanitize_text_field()
	 *	@uses	wp_send_json_error()
	 *	@uses	is_wp_error()
	 *	@uses	wp_send_json_success()
	 *  @return string
	 *
	 *	@since 1.0.0
	 */
	function ajax_convert_currency() {

		$base_price      = isset( $_POST['base_price'] ) ? absint( $_POST['base_price'] ) : false;
		$base_currency   = isset( $_POST['base_currency'] ) ? sanitize_text_field( $_POST['base_currency'] ) : false;
		$target_currency = isset( $_POST['target_currency'] ) ? sanitize_text_field( $_POST['target_currency'] ) : false;

		if ( empty( $base_price ) || empty( $base_currency ) || empty( $target_currency ) ) {
			$error = new WP_Error( 'data_missing', 'Expected valid base price, base currency and target' );
			wp_send_json_error( $error );
		}

		$base_data   = array( 'base_price' => $base_price, 'base_currency' => $base_currency );
		$target_data = array( 'target_currency' => $target_currency );

		// developers can overwrite the conversion or use their own exchange rate provider
		$conversion_result = apply_filters( 'wpsight_convert_currency', $base_data, $target_data );

		if ( is_wp_error( $conversion_result ) || ! isset( $conversion_result['target_price'] ) ) {
			wp_send_json_error( $conversion_result );
		}

		wp_send_json_success( $conversion_result );
	}

	/**
	 *	yahoo_convert_currency()
	 *
	 *  Use Yahoo to convert a given base price to a target currency
	 *
	 *  @param  array  $base
	 *  @param  array  $target
	 *	@uses	strtoupper()
	 *	@uses	wpsight_get_option()
	 *	@uses	get_transient()
	 *	@uses	wp_remote_get()
	 *	@uses	is_wp_error()
	 *	@uses	wp_remote_retrieve_body()
	 *	@uses	str_getcsv()
	 *	@uses	set_transient()
	 *	@uses	list()
	 *	@uses	number_format()
	 *	@uses	strtoupper()
	 *	@uses	wpsight_get_currency()
	 *  @return array
	 *
	 *	@since 1.0.0
	 */
	function yahoo_convert_currency( $base, $target ) {

		// set the exchange we want to make
		$s = strtoupper( $base['base_currency'] ) . strtoupper( $target['target_currency'] );

		// let users change the money formatting
		$currency_separator = ( 'dot' == wpsight_get_option( 'currency_separator', 'dot' ) ) ? '.' : ',';

		// check if there is already an exchange rate cached
		if ( apply_filters( 'wpsight_currency_converter_disable_cache', false ) || false === ( $currency_data = get_transient( 'wpsight_currency_exchange_'. $s ) ) ) {

			$url = 'https://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $s .'=X';
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				return $response;
			}

			$currency_data = str_getcsv( wp_remote_retrieve_body( $response ) );

			// cache the response so we don't have to make too many requests
			set_transient( 'wpsight_currency_exchange_'. $s, $currency_data, MINUTE_IN_SECONDS * 15 );
		}

		list( $operation, $rate, $date, $time ) = $currency_data;

		$target_price = $base['base_price'] * $rate;

		// return as much information as we have to make debugging easier
		$conversion_result = array(
			'target_price'    => number_format( $target_price, 0, ',', $currency_separator ),
			'target_symbol'   => wpsight_get_currency( strtoupper( $target['target_currency'] ) ),
			'target_currency' => $target['target_currency'],
			'operation'       => $operation,
			'rate'            => $rate,
			'date'            => $date,
			'time'            => $time
		);

		return $conversion_result;
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
		
		$licenses['currency_converter'] = array(
			'name' => WPSIGHT_CURRENCY_CONVERTER_NAME,
			'desc' => sprintf( __( 'For premium support and seamless updates for %s please activate your license.', 'wpcasa-currency-converter' ), WPSIGHT_CURRENCY_CONVERTER_NAME ),
			'id'   => wpsight_underscores( WPSIGHT_CURRENCY_CONVERTER_DOMAIN )
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
		$key = wpsight_underscores( WPSIGHT_CURRENCY_CONVERTER_DOMAIN );
	
		// Setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPSIGHT_SHOP_URL, __FILE__ , array(
				'version' 	=> WPSIGHT_CURRENCY_CONVERTER_VERSION,
				'license' 	=> isset( $licenses[ $key ] ) ? trim( $licenses[ $key ] ) : false,
				'item_name' => WPSIGHT_CURRENCY_CONVERTER_NAME,
				'author' 	=> WPSIGHT_AUTHOR
			)
		);
	
	}

} // end class

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Currency_Converter', 'init' ) );

/**
 *	wpsight_currency_converter()
 *
 *  Returns the currency converter output
 *
 *  @param  array  $args
 *	@uses	WPSight_Currency_Converter::currency_converter_shortcode()
 *  @return string
 *
 *	@since 1.0.0
 */
function wpsight_currency_converter( $args = array() ) {
	return WPSight_Currency_Converter::currency_converter_shortcode( $args );
}
