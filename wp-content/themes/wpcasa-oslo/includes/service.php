<?php
/**
 *	Service
 *	
 *	@package WPCasa Oslo
 */

/**
 *	wpsight_oslo_service()
 *	
 *	Echo wpsight_oslo_get_service()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_oslo_get_service()
 *	
 *	@since	1.0.0
 */
function wpsight_oslo_service( $args = array(), $widget_args = array() ) {
	echo wpsight_oslo_get_service( $args, $widget_args );
}

/**
 *	wpsight_oslo_get_service()
 *	
 *	Build service section.
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_get_template()
 *	
 *	@return	mixed
 *	
 *	@since 1.0.0
 */
function wpsight_oslo_get_service( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> false,
		'icon_class'	=> false,
		'description'	=> __( 'Here you can place your service description.', 'wpcasa-oslo' ),
		'link_text'		=> false,
		'link_url'		=> false,
		'link_blank'	=> false,
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_oslo_get_service_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'service.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_oslo_get_service', ob_get_clean(), $args );

}
