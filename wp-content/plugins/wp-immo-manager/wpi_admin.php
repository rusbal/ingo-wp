<?php
use wpi_classes\WpOptionsClass;
use wpi_classes\AdminClass;

// Setup Textdomain
class Wp_language_manager
{

    public function __construct()
    {
        load_plugin_textdomain(WPI_PLUGIN_NAME, FALSE, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
}

$wp_immo_manager = new Wp_language_manager();
$registerOptions = new \wpi_classes\WpOptionsClass();
$admin = new \wpi_classes\AdminClass();

// create custom plugin settings menu
add_action('admin_menu', 'wpi_plugin_menu');

function wpi_plugin_menu()
{

    //create new top-level menu
    add_menu_page('Immo Manger Optionen', 'WP Immo Manager', 'administrator', __FILE__, 'wpi_plugin_options', 'dashicons-admin-generic');

    //call register settings function
    add_action('admin_init', 'register_wpi_options');

}


function register_wpi_options()
{
    global $registerOptions;

    /**************************/
    /* Optionen registrieren */
    /*************************/

    return $registerOptions->wpi_register_settings();

}

// Hook the Dashboard Widget
add_action('wp_dashboard_setup', 'wpi_dashboard_widget');
function wpi_dashboard_widget(){
	global $wp_meta_boxes;

	wp_add_dashboard_widget('wpi_widget', 'Meine Immobilien', '\wpi_classes\AdminClass::wpi_dashboard_text');
}


// Register Bootstrap Styles

function wpi_bootstrap_styles()
{
    // Lokale Bootstrap-Dateien
    $css = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/css/bootstrap.css';
    $script = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js';
    $awesome = 'https://use.fontawesome.com/a043743ff2.js';
    $mainjs = WPI_PLUGIN_URL . 'js/main.js';

    // Laden von jQuery
    wp_enqueue_script('jquery');

    if (wp_script_is('bootstrap', 'enqueued')):
        return;
    else:
        wp_enqueue_style('bootstrap_css', $css);
        //wp_enqueue_style('bootstrap_theme_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css');

        wp_enqueue_script('bootstrap', $script, array('jquery'), '', true);
        wp_enqueue_script('wp_immo_manager_js', $mainjs, array('jquery'), '', true);
        wp_enqueue_script('awesome_fonts', $awesome, array(), '', true);

    endif;
}

// Hook into the 'wp_enqueue_scripts' action
if ('active' === get_option('wpi_bootstrap_styles')) {
    add_action('wp_enqueue_scripts', 'wpi_bootstrap_styles');
}

// Register Bootstrap Styles für Admin-Page
function bootstrap_admin_enqueue($hook)
{
    /*if ( '/admin.php?page=wp_immo_manager%2Fwpi_admin.php' != $hook) {
        return;
    }*/

    //wp_enqueue_style( 'bootstrap_admin_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
    //wp_enqueue_style( 'bootstrap_admin_theme_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css' );

    wp_enqueue_script('bootstrap_admin', WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js', array('jquery'), '', true);
}

add_action('admin_init', 'bootstrap_admin_enqueue');


/*Dashboard Zählung Immobilien bei "Auf einen Blick" */
add_filter('dashboard_glance_items', 'immobilien_auf_einen_blick');

function immobilien_auf_einen_blick($items = array())
{
    $post_types = array('wpi_immobilie');
    foreach ($post_types as $type) {
        if (!post_type_exists($type)) continue;
        $num_posts = wp_count_posts($type);
        if ($num_posts) {
            $published = intval($num_posts->publish);
            $post_type = get_post_type_object($type);
            $text = _n('%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, WPI_PLUGIN_NAME);
            $text = sprintf($text, number_format_i18n($published));
            if (current_user_can($post_type->cap->edit_posts)) {
                $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            } else {
                $output = '<span>' . $text . '</span>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
        }
    }
    return $items;
}

/**
 * Weiterlesen Link für Excerpts
 */
add_filter('excerpt_more', 'wpi_excerpt_more_link');

function wpi_excerpt_more_link($text)
{
    global $post;
    if (get_post_type() == 'wpi_immobilie'):
        return '... <a class="read-more-link" href="' . get_permalink($post->ID) . '">Read more</a>';
    endif;
}

//Funktion zum trimen eines Array_Values
function trim_value(&$value)
{
    $value = trim($value);
}

/*****************************
 **** Validation Function ****
 *****************************/

add_action('wp_ajax_wpi_valid_status', 'wpi_valid_status'); // Site Admin
add_action('wp_ajax_nopriv_wpi_valid_status', 'wpi_valid_status'); // Site Admin


function wpi_valid_status(){
	if (!empty($_POST)){
		if($_POST['status'] == '1' && $_POST['valid'] == 'true' ){
			update_option('wpi_pro', 'true');
			setcookie('wpi_validated', 'true');
			$status =  'reload';
		}
		else {
			update_option('wpi_pro', 'false');
			setcookie('wpi_validated', 'false');
			$status = 'noreload';
		}
	}
	echo $status;

}