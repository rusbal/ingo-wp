<?php
/**
 * class WPSight_STAGE_Walker_Category_Checklist
 *
 * We create a walker for wp_terms_checklist()
 * to make sure non-member users can assign
 * terms on the dashboard.
 *
 * @since 1.0.0
 */

// Include template.php to get Walker_Category_Checklist
require_once( ABSPATH . '/wp-admin/includes/template.php' );

class WPSight_STAGE_Walker_Category_Checklist extends Walker_Category_Checklist {
 
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		if ( empty( $args['taxonomy'] ) ) {
			$taxonomy = 'category';
		} else {
			$taxonomy = $args['taxonomy'];
		}

		if ( $taxonomy == 'category' ) {
			$name = 'post_category';
		} else {
			$name = 'tax_input[' . $taxonomy . ']';
		}

		$args['popular_cats'] = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
		$class = in_array( $category->term_id, $args['popular_cats'] ) ? ' class="popular-category"' : '';

		$args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];

		/** This filter is documented in wp-includes/category-template.php */
		if ( ! empty( $args['list_only'] ) ) {
			$aria_cheched = 'false';
			$inner_class = 'category';

			if ( in_array( $category->term_id, $args['selected_cats'] ) ) {
				$inner_class .= ' selected';
				$aria_cheched = 'true';
			}

			$output .= "\n" . '<li' . $class . '>' .
				'<div class="' . $inner_class . '" data-term-id=' . $category->term_id .
				' tabindex="0" role="checkbox" aria-checked="' . $aria_cheched . '">' .
				esc_html( apply_filters( 'the_category', $category->name ) ) . '</div>';
		} else {
			$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
				'<input value="' . $category->term_id . '" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' .
				checked( in_array( $category->term_id, $args['selected_cats'] ), true, false ) . ' /><label class="selectit" for="in-'.$taxonomy.'-' . $category->term_id . '">' .
				esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
		}
	}
 
}

/**
 * wpsight_stage_listing_actions()
 *
 * Filter default WPCasa listing actions
 * output and display container div also
 * if no actions are available (e.g. when
 * listing is not 'publish'). This is
 * necessary because of CSS display:table-cell.
 *
 * @since 1.0.0
 */

add_filter( 'wpsight_listing_actions', 'wpsight_stage_listing_actions' );

function wpsight_stage_listing_actions( $output ) {
	
	if( empty( $output ) )
		$output = '<div class="wpsight-listing-actions"></div>';
	
	return $output;
	
}

/**
 * Remove default add-on CSS.
 *
 * @since 1.0.0
 */

add_action( 'wp_enqueue_scripts', 'wpsight_stage_dequeue_addon_scripts' );

function wpsight_stage_dequeue_addon_scripts() {	
	wp_dequeue_style( 'wpsight-listings-map' );
	wp_dequeue_style( 'wpsight-list-agents' );
}

add_filter( 'wpsight_get_listing_thumbnail', 'wpsight_get_listing_thumbnail_class' );

function wpsight_get_listing_thumbnail_class( $thumb ) {
	return str_replace( 'wpsight-listing-thumbnail', 'wpsight-listing-thumbnail image fit', $thumb );
}

/**
 * Remove date column in dashboard.
 *
 * @since 1.0.0
 */

add_filter( 'wpsight_dashboard_columns', 'wpsight_stage_dashboard_columns' );

function wpsight_stage_dashboard_columns( $columns ) {
	
	// Remove date
	unset( $columns['date'] );
	
	return $columns;
	
}
