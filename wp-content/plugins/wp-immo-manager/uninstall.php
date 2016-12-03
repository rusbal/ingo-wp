<?php
require_once('\wpi_classes\WpOptionsClass.php');

//only execute the contents of this file if the plugin is really being uninstalled
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit ();
}
$wpi_options = new \wpi_classes\WpOptionsClass();

/************************************/
//Optionen löschen aus DB//
/***********************************/
global $wpdb;

foreach ($wpi_options->wpi_options as $key => $value) {
    delete_option($key);
}