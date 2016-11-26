<?php
/**
 *	Call to Action
 *	
 *	@package WPCasa Oslo
 */

/**
 *	wpsight_oslo_call_to_action()
 *	
 *	Echo wpsight_oslo_get_call_to_action()
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_oslo_get_call_to_action()
 *	
 *	@since	1.0.0
 */
function wpsight_oslo_call_to_action( $args = array(), $widget_args = array() ) {
	echo wpsight_oslo_get_call_to_action( $args, $widget_args );
}

/**
 *	wpsight_oslo_get_call_to_action()
 *	
 *	Build call to action.
 *	
 *	@param	array	$args
 *	@param	array	$widget_args
 *	@uses	wpsight_get_template()
 *	
 *	@return	mixed
 *	
 *	@since 1.0.0
 */
function wpsight_oslo_get_call_to_action( $args = array(), $widget_args = array() ) {

	// Set some defaults
		
	$defaults = array(
		'title'			=> false,
		'description'	=> __( 'Here you can place your call to action description.', 'wpcasa-oslo' ),
		'link_text'		=> false,
		'link_url'		=> false,
		'link_blank'	=> false,
		'orientation'	=> 'vertical' // can be horizontal
	);
	
	// Merge args with defaults and apply filter
	$args = apply_filters( 'wpsight_oslo_get_call_to_action_args', wp_parse_args( $args, $defaults ), $args );
	
	ob_start();

	wpsight_get_template( 'call-to-action.php', array( 'args' => $args, 'widget_args' => $widget_args ) );
		
	return apply_filters( 'wpsight_oslo_get_call_to_action', ob_get_clean(), $args );

}
