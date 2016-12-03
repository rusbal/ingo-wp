<?php

if (get_option('wpi_pro') != 'false') {
    add_shortcode('umkreissuche', 'umkreissuche_func');
}
// Freie Shortcodes
add_shortcode('immobilien', 'immoLoopFunction');

/**
 * Shortcode zur Umkreissuche
 */
function umkreissuche_func($atts)
{
    extract(shortcode_atts(array(
        'anzahl' => '5',
    ), $atts));

    $link = esc_url(home_url('/umkreissuche/') );

    $form = '<form action="' . $link . '" method="get">
  <div class="form-group">
    <input type="hidden" name="sent" value="true">
    <label for="plz">Postleitzahl</label>
    <input type="number" class="form-control" id="plz" name="plz" placeholder="PLZ eingeben" required>
  </div>
  <div class="form-group">
    <label for="distanz">Umkreis in km</label>
    <input type="number" class="form-control" id="distanz" name="distanz" placeholder="km">
  </div>
  <button type="submit" class="btn btn-default">Suchen</button>
</form>';

    return $form;

}

// Shortcode mit Archiv-Loops
function immoLoopFunction($atts)
{
    // Standardparameter übergeben
    extract(shortcode_atts(array(
        'anzahl' => '5',
        'order' => 'ASC',
        'orderby' => 'id',
        'objekttyp' => '',
        'vermarktung' => '',
        'relation' => 'OR',
        'columns' => false
    ), $atts));

    ob_start();
    // CSS einbinden
    wp_register_style('plugin-styles', WPI_PLUGIN_URL . 'styles.css', true, '');
    wp_enqueue_style('plugin-styles');
    // Aus den Optionen
    //if(!empty(get_option('wpi_custom_css'))){
    add_action('wp_enqueue_scripts', 'wpi_add_custom_css');
    //}


    // WP_Query arguments
    global $wp_query;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Argument nur wenn objekttyp oder vermarktung übergeben wird
    if ($objekttyp != '' || $vermarktung != '') {
        $args = array(
            'post_type' => 'wpi_immobilie',
            'post_status' => array('publish'),
            'posts_per_page' => (int)$anzahl,
            'order' => trim($order),
            'orderby' => trim($orderby),
            'page' => $paged,
            'tax_query' => array(
                'relation' => $relation,
                array(
                    'taxonomy' => 'objekttyp',
                    'field' => 'slug',
                    'terms' => $objekttyp,
                ),
                array(
                    'taxonomy' => 'vermarktungsart',
                    'field' => 'slug',
                    'terms' => $vermarktung,
                )
            )
        );
    } else {
        $args = array(
            'post_type' => 'wpi_immobilie',
            'post_status' => array('publish'),
            'posts_per_page' => (int)$anzahl,
            'order' => trim($order),
            'orderby' => trim($orderby),
            'page' => $paged,
        );
    }

    // The Query
    $im_query = new WP_Query($args);
    $pagination_args = array(
        'base' => '%_%',
        'format' => '?paged=%#%',
        'total' => $im_query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'show_all' => true,
        'end_size' => 1,
        'mid_size' => 2,
        'prev_next' => false,
        'prev_text' => __('« Zurück'),
        'next_text' => __('Weiter »'),
        'type' => 'list',
        'add_args' => false,
        'add_fragment' => '',
        'before_page_number' => '',
        'after_page_number' => ''
    );
    // Initialisiere Variable "Content"
    ?>
    <div id="wpi-main" class="site-main row" role="main"><?php

    // The Loop
    if ($im_query->have_posts()) {
        while ($im_query->have_posts()) {
            $im_query->the_post();

            $meta = get_post_meta(get_the_ID());

            if ($columns != false):
                echo view_list_columns($meta);
            else:
                echo view_list_openimmo($meta);
            endif;
        }
        // Pagination einfügen
        $sep = ' &nbsp; ';
        $down = '<button class="btn btn-default navbar-btn">Vorige Seite</button>';
        $up = '<button class="btn btn-default navbar-btn">Nächste Seite</button>';
        ?>

        <div class="clearfix"></div>
        <nav class="text-center nav-pagination">
            <?php echo paginate_links($pagination_args)?>
        </nav>
        </div><!-- Ende #main --><?php

    } else {
        echo view_no_founds();
    }

    // Restore original Post Data
    wp_reset_postdata();

    return ob_get_clean();
}

// TODO Shortcode PDF-Expose

// TODO
