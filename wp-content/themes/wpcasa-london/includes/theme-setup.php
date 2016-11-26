<?php
/**
 *	Basic theme setup
 *	
 *	@package WPCasa London
 */

/**
 *	wpsight_london_setup()
 *	
 *	Let this theme support translations, post thumbnails
 *	and some other features.
 *	
 *	@uses	load_theme_textdomain()
 *	@uses	add_theme_support()
 *	
 *	@since	1.0.0
 */
add_action( 'after_setup_theme', 'wpsight_london_setup' );

if ( ! function_exists( 'wpsight_london_setup' ) ) {

	function wpsight_london_setup() {
	
		// Make theme available for translation
		load_theme_textdomain( 'wpcasa-london', get_template_directory() . '/languages' );
	
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
	
		// Let WordPress provide the title
		add_theme_support( 'title-tag' );
	
		// Enable support for post thumbnails
		add_theme_support( 'post-thumbnails' );
	
		// Switch core markup for search form, comment form, and comments to valid HTML5
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	
		// Enable support for post formats
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
		
		// Enable support for custom background
		
		$background_args = array(
			'default-color' => '333',
			'default-image' => false
		);
		
		$background_args = apply_filters( 'wpsight_london_custom_background_args', $background_args );
		
		add_theme_support( 'custom-background', $background_args );
		
		// Enable support for custom header images
		
		$header_args = array(			
			'default-image'          => get_template_directory_uri() . '/assets/images/bg-site-header.jpg',
			'random-default'         => false,
			'width'                  => 1500,
			'height'                 => 1000,
			'flex-height'            => false,
			'flex-width'             => false,
			'default-text-color'     => 'ffffff',
			'header-text'            => true,
			'uploads'                => true,
			'wp-head-callback'       => 'wpsight_london_header_style',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
		
		$header_args = apply_filters( 'wpsight_london_custom_header_args', $header_args );
		
		add_theme_support( 'custom-header', $header_args );
		
		// Enable custom logo (since WordPress 4.5)
		
		/**
		 *	We still leave this unactivated as the
		 *	behavior in the theme customizer is still
		 *	strange. Removing a logo would leave an
		 *	empty image tag and not display the text
		 *	logo again as expected.
		 *	
		 *	For now we stick to a custom logo control
		 *	and add this in a future version:
		 *
		 *	add_theme_support( 'custom-logo', array(
		 *		'flex-height'	=> true,
		 *		'flex-width'	=> true,
		 *		'header-text'	=> array( 'site-title-text', 'site-description' ),
		 *	) );
		 */
		
		// Remove core plugin CSS
		add_filter( 'wpsight_css', '__return_false' );
		
		// Remove dashboard add-on CSS
		add_filter( 'wpsight_dashboard_css', '__return_false' );
		
		// Remove pricing table add-on CSS
		add_filter( 'wpsight_pricing_tables_css', '__return_false' );
		
		// Add theme print CSS
		add_action( 'wpsight_head_print', 'wpsight_london_head_print_css' );
	
	} // end wpsight_london_setup()

} // end ! function_exists()

/**
 *	wpsight_london_header_style()
 *	
 *	Callback function for custom-header theme support.
 *	
 *	@uses	get_header_image()
 *	@uses	header_image()
 *	@uses	get_header_textcolor()
 *	@uses	get_theme_support()
 *	
 *	@since	1.0.0
 */
function wpsight_london_header_style() {
	
	$header_image = get_header_image();

	if( ! empty( $header_image ) ) { ?>

	<style type="text/css">
	.site-header {
	    background-image: url(<?php header_image(); ?>);
	}
	</style>
	<?php }

	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	if ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) { ?>

	<style type="text/css" id="wpsight-london-header-text-color">
		.header-tagline #tagline,
		.header-title h1,
		.header-featured-image h1,
		.header-full-width .wpsight-listings-slider-wrap .listing-content .wpsight-listing-title .entry-title,
		.site-wrapper .header-top .wpsight-nav > li > a, .site-wrapper .header-top .wpsight-nav > li > a:hover,
		.site-title-text,
		.site-description {
			color: #<?php echo $text_color; ?>;
		}
	</style>
	<?php }

}

/**
 *	wpsight_london_content_width()
 *	
 *	Set the content width in pixels, based on the theme's design and stylesheet.
 *	Priority 0 to make it available to lower priority callbacks.
 *	
 *	@global	int	$content_width
 *	
 *	@since	1.0.0
 */
add_action( 'after_setup_theme', 'wpsight_london_content_width', 0 );

function wpsight_london_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpsight_london_content_width', 1140 );
}

/**
 *	Set grid classes for search form fields
 *
 *	@param	array	$fields
 *	@return array
 *
 *	@since	1.0.0
 */
add_filter( 'wpsight_get_search_fields', 'wpsight_london_get_search_field_widths' );

function wpsight_london_get_search_field_widths( $fields ) {
	
	// Get listing details
	$details = wpsight_details();
	
	$details_1 = $details['details_1']['id'];
	$details_2 = $details['details_2']['id'];
	
	$fields['keyword']['class'] 		= 'col-xs-12 col-sm-9';
	$fields['submit']['class']			= 'col-xs-12 col-sm-3';
	
	$fields['offer']['class']			= 'col-xs-12 col-sm-2';
	$fields['location']['class']		= 'col-xs-12 col-sm-3';
	$fields['listing-type']['class']	= 'col-xs-12 col-sm-3';
	$fields[ $details_1 ]['class']		= 'col-xs-6 col-sm-2';	
	$fields[ $details_2 ]['class']		= 'col-xs-6 col-sm-2';
		
	return $fields;
	
}

/**
 *	Set grid classes for search form advanced fields
 *
 *	@param	array	$fields
 *	@return array
 *
 *	@since	1.0.0
 */
add_filter( 'wpsight_get_advanced_search_fields', 'wpsight_london_get_advanced_search_fields_widths' );

function wpsight_london_get_advanced_search_fields_widths( $fields ) {
	
	$fields['min']['class'] 	= 'col-xs-6 col-sm-3';
	$fields['max']['class'] 	= 'col-xs-6 col-sm-3';
	$fields['orderby']['class'] = 'col-xs-6 col-sm-3';
	$fields['order']['class'] 	= 'col-xs-6 col-sm-3';
	$fields['feature']['class'] = 'col-xs-12';
		
	return $fields;
	
}

/**
 *	Filter get_search_form
 *
 *	@param	mixed	$form
 *	@return mixed
 *
 *	@since	1.0.0
 */
add_filter( 'get_search_form', 'wpsight_london_search_form' );

function wpsight_london_search_form( $form ) {
	
	ob_start(); ?>

	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="row gutter-10">
			<div class="col-xs-8">
				<label class="sr-only"><span class="screen-reader-text"><?php _ex( 'Search for', 'search form', 'wpcasa-london' ); ?></span></label>
				<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'search form', 'wpcasa-london' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
			</div>
			<div class="col-xs-4">
				<input type="submit" class="search-submit btn btn-primary btn-block" value="<?php echo esc_attr_x( 'Search', 'search form', 'wpcasa-london' ); ?>">
			</div>
		</div>
	</form>
	<?php
	
	return ob_get_clean();

}

/**
 * Filter embed HTML to prevent wpautop.
 * Adds a block wrapper around the embed which wpautop will skip.
 *
 * @see wp-includes/class-wp-embed.php
 * @see wp-includes/formatting.php
 *
 * @param  mixed  $html The shortcode callback function to call.
 * @param  string $url  The attempted embed URL.
 * @param  array  $attr An array of shortcode attributes.
 * @return string       The filterd HTML.
 */
add_filter( 'embed_oembed_html', 'wpsight_london_oembed_handler', 10, 4 );

function wpsight_london_oembed_handler( $html, $url, $attr, $post_ID ) {
    return '<div class="oembed">' . $html . '</div>';
}

/**
 *	Filter wpsight_pagination args
 *
 *	@param	array	$args
 *	@return	array
 *
 *	@since	1.0.0
 */
add_filter( 'wpsight_get_pagination_args', 'wpsight_london_pagination_args' );

function wpsight_london_pagination_args( $args ) {
	
	$args['type'] = 'array';
	
	return $args;
	
}

/**
 *	Filter wpsight_pagination
 *
 *	@param	array	$paginate_links
 *	@return	mixed
 *
 *	@since	1.0.0
 */
add_filter( 'wpsight_get_pagination', 'wpsight_london_pagination' );

function wpsight_london_pagination( $paginate_links ) {
	
	$pagination = '';
	
	if( ! empty( $paginate_links ) && is_array( $paginate_links ) ) {
	
		// Create pagination output
			
		$pagination = '<nav><ul class="pagination">' . "\n";		
		
		foreach( $paginate_links as $page )
			$pagination .= '<li>' . $page . '</li>';
		
		$pagination .= '</nav>' . "\n";
		
		// Place active class on li
		
		$pagination = str_replace( '<li><span class=\'page-numbers current\'>', '<li class="active"><a href="#"><span class=\'page-numbers current\'>', $pagination );
		$pagination = str_replace( '</span>', '</span></a>', $pagination );
		
		// Place disabled class on li		
		$pagination = str_replace( '<li><span class="page-numbers dots">', '<li class="disabled"><a href="#"><span class="page-numbers dots">', $pagination );
	
	}
    
    return $pagination;

}

/**
 *	Template for comments and pingbacks.
 *	
 *	Used as a callback by wp_list_comments() for displaying the comments.
 *	
 *	@since 1.0
 */
if ( ! function_exists( 'wpsight_london_comment' ) ) {

	function wpsight_london_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	
		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'wpcasa-london' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'wpcasa-london' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
	
		<?php else : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-meta">
					<div class="comment-author vcard">
						<?php if ( 0 != $args['avatar_size'] ) echo '<span class="image">' . get_avatar( $comment, $args['avatar_size'] ) . '</span>'; ?>
						<?php printf( __( '%s <span class="says">says:</span>', 'wpcasa-london' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</div><!-- .comment-author -->
	
					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'wpcasa-london' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'wpcasa-london' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->
	
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wpcasa-london' ); ?></p>
					<?php endif; ?>
				</header><!-- .comment-meta -->
	
				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
	
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>
			</div><!-- .comment-body -->
	
		<?php
		endif;	
		
	}
	
	add_filter( 'comment_reply_link', 'wpsight_london_comment_reply_link' );
	
	function wpsight_london_comment_reply_link( $link ) {		
		return str_replace( 'comment-reply-link' , 'comment-reply-link btn btn-sm btn-primary', $link );		
	}

} // endif check for wpsight_london_comment()

/**
 *	Add header display option to body classes
 *	
 *	@param	array	Registered body classes
 *	@uses	get_post_meta()
 *	@return	array
 *	
 *	@since 1.0.0
 */
add_filter( 'body_class', 'wpsight_london_body_class' );

function wpsight_london_body_class( $classes ) {
	
	$classes[] = 'wpsight-london';
	
	$header_display = get_post_meta( get_the_id(), '_header_display', true );
	
	if( $header_display )
		$classes[] = 'header-display-' . $header_display;
	
	if( ! is_active_sidebar( 'sidebar' ) )
		$classes[] = 'sidebar-not-active';
	
	return $classes;
	
}

/**
 *	Add filter to Gravity Forms field markup
 *	to add Bootstrap classes.
 *	
 *	@param	string	$content
 *	@param	array	$field
 *	@uses	str_replace()
 *	@return	string
 *	
 *	@since 1.0.0
 */
add_filter( 'gform_field_content', 'wpsight_london_gform_field_content', 10, 2 );

function wpsight_london_gform_field_content( $content, $field ) {
	
    // Currently only applies to most common field types, but could be expanded.
	
    if( $field['type'] != 'hidden' && $field['type'] != 'list' && $field['type'] != 'checkbox' && $field['type'] != 'fileupload' && $field['type'] != 'date' && $field['type'] != 'html' && $field['type'] != 'address' ) {
	    
	    if( empty( $field['cssClass'] ) && empty( $field['size'] ) ) {
	    	$content = str_replace('<input ', '<input class=\'form-control\' ', $content);
	    } else {		    
        	$content = str_replace( 'class=\'' . $field['size'], 'class=\'form-control ' . $field['size'], $content );
        }
    }
	
    if( $field['type'] == 'name' || $field['type'] == 'address' ) {
        $content = str_replace('<input ', '<input class=\'form-control\' ', $content);
    }
	
    if( $field['type'] == 'textarea' ) {
        $content = str_replace('class=\'textarea', 'class=\'form-control textarea', $content);
    }
	
    if( $field['type'] == 'checkbox' ) {
        $content = str_replace('li class=\'', 'li class=\'checkbox ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }
	
    if( $field['type'] == 'radio' ) {
        $content = str_replace('li class=\'', 'li class=\'radio ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }
    
    if( $field['type'] == 'multiselect' ) {
        $content = str_replace('li class=\'', 'li class=\'radio ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }
	
	return $content;
	
}

/**
 *	Add filter to Gravity Forms submit
 *	button markup to add Bootstrap classes.
 *	
 *	@param	string	$button
 *	@param	array	$form
 *	@return	string
 *	
 *	@since 1.0.0
 */
add_filter( 'gform_submit_button', 'wpsight_london_gform_submit_button', 10, 2 );

function wpsight_london_gform_submit_button( $button, $form ) {	
	return '<input type="submit" class="btn btn-lg btn-primary" id="gform_submit_button_' . $form['id'] . ' value="' . $form['button']['text'] . '" />';
}

/**
 *	wpsight_london_head_print_css()
 *	
 *	Add print styles to print header
 *	using wpsight_head_print action hook.
 *	
 *	@since 1.0.0
 */
function wpsight_london_head_print_css() {
	
	// Script debugging?
	$suffix = SCRIPT_DEBUG ? '' : '.min'; ?>
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/wpsight-print<?php echo $suffix; ?>.css" rel="stylesheet" type="text/css">
<?php
}

add_filter( 'pre_get_posts','wpsight_london_search_filter' );

/**
 *	wpsight_london_search_filter()
 *	
 *	Restrict general WordPress search
 *	to posts and pages.
 *
 *	@param	object	$query
 *
 *	@since 1.0.0
 */
function wpsight_london_search_filter( $query ) {

    if ( $query->is_search && $query->is_main_query() && ! is_admin() )
		$query->set( 'post_type', array( 'post', 'page' ) );

	return $query;

}

/**
 *	wpsight_london_demo_import_remove_content()
 *
 *	When demo content is imported we remove the
 *	default hello world and sample page entries.
 *
 *	@since	1.0.0
 */
add_action( 'wpsight_demo_import_process_content_before', 'wpsight_london_demo_import_remove_content' );

function wpsight_london_demo_import_remove_content() {

	// Delete default post
	wp_delete_post( 1 );
	
	// Delete default page
	wp_delete_post( 2 );
	
	// Delete WPCasa listings page
	
	$page = wpsight_get_option( 'listings_page' );
	
	if( $page ) {
		
		// Delete listings page
		wp_delete_post( $page );
		
		// Add listings page from import content
		wpsight_add_option( 'listings_page', 273 );
		
	}

}

/**
 *	wpsight_london_demo_import_remove_widgets()
 *
 *	When demo content is imported we remove the
 *	WordPress widgets that are activated by default.
 *
 *	@since	1.0.0
 */
add_action( 'wpsight_demo_import_process_widgets_before', 'wpsight_london_demo_import_remove_widgets' );

function wpsight_london_demo_import_remove_widgets() {
			
	if ( ! get_option( 'wpsight_london_cleared_widgets' ) ) {

		update_option( 'sidebars_widgets', array() );
		
		// Set flag to only run this once
		update_option( 'wpsight_london_cleared_widgets', true );

	}

}

/**
 *	wpsight_london_get_call_to_action_url()
 *
 *	Replace demo content contact us URL
 *
 *	@since 1.0.0
 */
add_filter( 'wpsight_london_get_call_to_action_args', 'wpsight_london_get_call_to_action_url' );

function wpsight_london_get_call_to_action_url( $args ) {
	
	if( 'http://import.wpcasa.com/london/contact-us/' == $args['link_url'] )
		$args['link_url'] = home_url( 'contact-us/' );
	
	return $args;
	
}

/**
 *	wpsight_london_listing_agent_image()
 *
 *	Correctly display default demo agent image
 *
 *	@since 1.0.0
 */
add_filter( 'wpsight_listing_agent_image', 'wpsight_london_listing_agent_image', 10, 3 );

function wpsight_london_listing_agent_image( $image, $post, $size ) {
	
	if( '<img src="" width="" height="" alt="Demo Agent" />' == $image )
		$image = '<img src="' . esc_url( $post->_agent_logo ) . '" alt="Demo Agent">';
	
	return $image;
	
}
