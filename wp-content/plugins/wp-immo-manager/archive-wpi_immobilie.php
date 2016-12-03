<?php
/**
 * Template zur Anzeige der Immobilien als Single
 *
 */

$uploadsUrl = get_option('wpi_upload_url');

?>

<?php get_header(); ?>


    <!-- Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->


    <div id="wpi-primary" class="container-fluid">
        <div class="row">
            <div class="search-div">
                <?php echo view_searchfield_wpmi(); ?>
            </div>
            <div class="clearfix"></div>

            <hr>

            <?php
            /**
             * Anzeige einer Sidebar wenn in den Einstellungen aktiviert
             */
            if (get_option('wpi_list_sidebar') == 'true'):
                $content_row = 'col-xs-12 col-md-9';
                $sidebar = get_option('wpi_list_sidebar_name');
                $sidebar = substr(strstr($sidebar, '-'), 1);
                // zeige sidebar
                echo '<div class="aside col-md-3">';
                get_sidebar($sidebar);
                echo '</div>';
            else:
                $content_row = 'col-xs-12 col-md-12';
            endif;
            ?>

            <!-- Ende Aside -->

            <div id="wpi-main" class="content site-main <?php echo $content_row; ?>" role="main"><?php

                global $wp_query;

                $big = 999999999; // need an unlikely integer

                $pagination = paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $wp_query->max_num_pages,
                    'type' => 'list',
                ));

                // LOOP
                if ($wp_query->have_posts()):

                    while ($wp_query->have_posts()):

                        $wp_query->the_post();

                        $meta = get_post_meta(get_the_ID());

                        if (get_option('wpi_list_excerpt') === 'true'):
                            echo view_list_excerpt($meta);
                        else :
                            if (get_option('wpi_list_view_column') === 'column') {
                                echo view_list_columns($meta);
                            } else {
                                echo view_list_openimmo($meta);
                            }

                        endif;
                    endwhile; // end of the loop.

                endif; // End of IF-Loop

                wp_reset_postdata();
                ?>

            </div>
            <!-- #main -->

            <div class="clearfix"></div>

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="text-center">
                            <?php echo $pagination; ?>
                    </div>
                </div>
            </nav>

            <div class="clearfix"></div>

            <hr>

            <div class="search-div">
                <?php echo view_searchfield_wpmi(); ?>
            </div>
        </div>
        <!-- row -->

    </div><!-- #primary -->

    <!-- Ende Template von WP Immo Manager created by Media-Store.net - http://media-store.net -->

<?php get_footer(); ?>