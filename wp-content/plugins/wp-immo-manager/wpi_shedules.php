<?php
/**
 * In dieser Datei werden zusätzliche Zeiten zur Synchronisation in WP Filter angelegt
 * und die Funktionen der automatischen Synchronisation definiert.
 */
$pro = get_option('wpi_pro');

// WP-Cron Filter Zeit von 15Min. und 30 Min. setzen

add_filter( 'cron_schedules', 'wpi_cron_schedules' );

// add custom time to cron
function wpi_cron_schedules( $schedules ) {

    $schedules['half_hour'] = array(
        'interval' => 1800, // seconds
        'display'  => __( 'Halbe Stunde' )
    );
    $schedules['fiveteen_minutes'] = array(
        'interval' => 900, // seconds
        'display'  => __( '15 Min.' )
    );

    return $schedules;
}


// Prüfen ob Shedule bereits registriert ist, wenn nicht registrieren.

add_action( 'wp', 'wpi_setup_schedule' );

function wpi_setup_schedule() {
    $shedule_time = get_option('wpi_shedule_time'); // Abrufen der eingestellten Werte
    if ( ! wp_next_scheduled( 'wpi_time_event' ) ) {
        wp_schedule_event( time(), $shedule_time, 'wpi_time_event');
    }
}


// An einem registrierten shedule, Befehle ausführen.
if('true' === $pro) {
    add_action('wpi_time_event', 'wpi_do_this_on_time');
}

function wpi_do_this_on_time() {
   // Hier kommen die Befehle der Synchronisation etc. hin.

    $xml_file_array = wpi_xml_auslesen(); //Funktion definiert in wpi_unzip_functions.php
    $GLOBALS['xml_array'] = wpi_xml_array($xml_file_array); //Funktion definiert in wpi_create_posts.php
    wpi_xml_standard();
}
