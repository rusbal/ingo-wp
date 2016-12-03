<?php

/**
 * Plugin name:     WP Immo Manager
 * Plugin uri:      http://wp-immo-manager.de
 * Description:     WP Immo Manager integriert Immobilien aus ihrer Makler-Software in Wordpress
 * Author:          Artur Voll PC Service (media-store.net)
 * Author uri:      http://media-store.net
 * Version:         2.0.8.2
 * Text domain:     wp-immo-manager
 * Domain path:     languages/
 * License:         GPLv2
 *
 * @package         wp-immo-manager
 */

/* check wp */
if ( ! function_exists( 'add_action' ) ) :
	return;
endif;

// Konstanten und Variablen
// Plugin Ordner Name, wird für Konstanten und Übersetzungen bereitgestellt.
$plugin_folder = 'wp-immo-manager';
$version = '2.0.8.2';
/* Plugin Admin Page */
$plugin = plugin_basename(__FILE__);
/* Verzeichnis für Uploads erstellen */
$basedir = wp_upload_dir();
$basedir = $basedir['basedir'];
/* Handle Uploads */
$upload_dir = wp_upload_dir();
$upload_url = $upload_dir['baseurl'] . '/' . $plugin_folder . '/';
$upload_basedir = $upload_dir['basedir'] . '/' . $plugin_folder . '/';
$uploads = $basedir . '/' . $plugin_folder . '/';
$handle = chdir($basedir);

define('WPI_PLUGIN_NAME', $plugin_folder);
define('WPI_PLUGIN_URL', WP_PLUGIN_URL . '/' . $plugin_folder . '/');
define('WPI_PLUGIN_URI', WP_PLUGIN_URL . '/' . $plugin_folder . '/');
define('WPI_PLUGIN_DIR', plugin_dir_path(__file__) . '');
define('WPI_UPLOAD_DIR', $upload_basedir);
define('WPI_UPLOAD_URL', $upload_url);
define('WPI_UPLOADS_FOLDER', $uploads);
define('WPI_TEMP_DIR', WPI_UPLOAD_DIR . 'tmp/');

/* Check ob der Uploads Ordner bereits angelegt wurde, wenn nein wird erstellt. */
if (!is_dir($upload_basedir)) :
	mkdir($upload_basedir);
endif;
if (!is_dir(WPI_TEMP_DIR)) :
	mkdir(WPI_TEMP_DIR);
endif;
if (!is_dir(WPI_UPLOAD_DIR . 'zip-archive/')) :
	mkdir(WPI_UPLOAD_DIR . 'zip-archive/');
endif;

/* Einbinden der Dateien und Funktionen */
require_once(WPI_PLUGIN_DIR . '/wpi_classes/WpOptionsClass.php');
require_once(WPI_PLUGIN_DIR . '/wpi_classes/AdminClass.php');
require_once(WPI_PLUGIN_DIR . '/wpi_classes/SingleViewClass.php');
require_once(WPI_PLUGIN_DIR . '/wpi_classes/ListViewClass.php');
require_once(WPI_PLUGIN_DIR . '/wpi_classes/WpOptionsClass.php');
include(WPI_PLUGIN_DIR . 'wpi_admin.php');
include(WPI_PLUGIN_DIR . 'wpi_admin_page.php');
include(WPI_PLUGIN_DIR . 'wpi_create_posts.php');
include(WPI_PLUGIN_DIR . 'wpi_post_type.php');
include(WPI_PLUGIN_DIR . 'wpi_unzip_functions.php');
include(WPI_PLUGIN_DIR . 'wpi_view_objects.php');
include(WPI_PLUGIN_DIR . 'wpi_shortcodes.php');
include(WPI_PLUGIN_DIR . 'wpi_shedules.php');
include(WPI_PLUGIN_DIR . 'wpi_metaboxes.php');

$pro = get_option('wpi_pro') === 'true' ? true : false;

/* Umkreissuche "ogdbPLZnearby" */
require_once('ogdbPLZnearby/ogdbPLZnearby2.lib.php');

/* Umkreissuche Funktion */
if (!function_exists('immos')) {

	function immos($content)
	{
		if (!isset($_GET['sent'])) :
			return $content;
		else:
			ob_start();

			$plz = trim($_GET['plz']);
			$distanz = trim($_GET['distanz']);

			@$plz_array = ogdbPLZnearby($plz, $distanz, true);

			foreach ($plz_array as $value) {
				//zeigen($value);
				$zip = $value['zip'];
				$city = $value['city'];

				$args = (array(
					'post_type' => 'wpi_immobilie',
					'order' => 'ASC',
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'geodaten',
							'value' => $zip,
							'compare' => 'LIKE',
						),
					),
				));
				$querys[] = new WP_Query($args);
			}

			$querys = array_reverse($querys);

			foreach ($querys as $query) {

				if ($query->have_posts()):

					while ($query->have_posts()) : $query->the_post();

						$meta = get_post_meta(get_the_ID());

						echo '<div id="wpi-main" class="umkreissuche">';
						if (get_option('wpi_list_view_column') === 'column') {
							echo view_list_columns($meta);
						} else {
							echo view_list_openimmo($meta);
						}
						echo '</div';

					endwhile;

				endif;


			}
			wp_reset_postdata();
			return ob_get_clean();

		endif;


		//return $content . ob_get_clean();
	}
}

/* Funktion zum Filtern des Contents für Umkreissuche */
function filter_umkreissuche_content($content) {
	if (!is_page('umkreissuche')) {
		return $content;
	} else {
		echo immos($content);
	}
}

add_filter('the_content', 'filter_umkreissuche_content');

add_filter('widget_text', 'do_shortcode');

/* Link zu Options-Seite aus der Plugin-Seite */
function wpi_add_settings_link($links)
{
	$settings_link = '<a href="admin.php?page=wp-immo-manager/wpi_admin.php">' . __('Einstellungen', WPI_PLUGIN_NAME) . '</a>';
	array_push($links, $settings_link);
	return $links;
}

add_filter("plugin_action_links_$plugin", 'wpi_add_settings_link');

// Funktion zur Bildung Custom Css, falls in den Einstellungen gespeichert
function wpi_add_custom_css()
{
	$custom_css = esc_html(get_option('wpi_custom_css'));
	wp_add_inline_style('plugin-styles', $custom_css);
}

// Archive-Seite Template hinzufügen
function wpi_page_template($page_template)
{
	global $post;
	wp_register_style('plugin-styles', WPI_PLUGIN_URL . 'styles.css', false, '');

	if (get_post_type() == 'wpi_immobilie' && is_archive()) {
		$page_template = WPI_PLUGIN_DIR . '/archive-wpi_immobilie.php';

		wp_enqueue_style('plugin-styles');

		// Aus den Optionen
		//if(!empty(get_option('wpi_custom_css'))){
		add_action('wp_enqueue_scripts', 'wpi_add_custom_css');
		//}

	}
	if (is_tax('objekttyp') || is_tax('vermarktungsart')) {
		if ($tax_file = locate_template(array('archive-wpi_immobilie.php'))) {
			$page_template = $tax_file;
		} else {
			$page_template = WPI_PLUGIN_DIR . '/archive-wpi_immobilie.php';

			wp_enqueue_style('plugin-styles');
			// Aus den Optionen
			//if(!empty(get_option('wpi_custom_css'))){
			add_action('wp_enqueue_scripts', 'wpi_add_custom_css');
			//}

		}
	}
	return $page_template;
}

// Single-Seite Template hinzufügen
function wpi_include_template($template)
{
	global $post;
	wp_register_style('plugin-styles', WPI_PLUGIN_URL . 'styles.css', false, '');

	if (get_post_type() == 'wpi_immobilie' && is_single()) {
		if ($theme_file = locate_template(array('single-wpi_immobilie.php'))) {
			$template = $theme_file;
		} else {
			$template = WPI_PLUGIN_DIR . '/single-wpi_immobilie.php';

			wp_enqueue_style('plugin-styles');
			// Aus den Optionen
			//if(!empty(get_option('wpi_custom_css'))){
			add_action('wp_enqueue_scripts', 'wpi_add_custom_css');
			//}

		}
	}

	return $template;
}


add_filter('archive_template', 'wpi_page_template');
add_filter('single_template', 'wpi_include_template');
//
/**********************************
 **** Uninstall Option ***********
 **********************************/
/*
function wpi_uninstall()
{
     $wpi_options = new \wpi_classes\WpOptionsClass();

    foreach ($wpi_options->wpi_options as $key => $value) {
        delete_option($key);
    }
    error_log('Plugin gelöscht');

}
*/