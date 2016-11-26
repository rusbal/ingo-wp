<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WPCasa Bahia
 */

if ( ! function_exists( 'wpsight_bahia_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function wpsight_bahia_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links clearfix">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '&larr; %title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title &rarr;' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0
 */

if ( ! function_exists( 'wpsight_bahia_posted_on' ) ) {

	function wpsight_bahia_posted_on() {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
			$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
	
		printf( __( '<span class="posted-on">%1$s</span><span class="byline">%2$s</span>', 'wpcasa-bahia' ),
			sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				$time_string
			),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);

		$categories_list = get_the_category_list( ', ' );
		if ( $categories_list && wpsight_bahia_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html( '%s' ) . '</span>', $categories_list );
		}

	}

} // endif wpsight_bahia_posted_on

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since 1.0
 */

if ( ! function_exists( 'wpsight_bahia_comment' ) ) {

	function wpsight_bahia_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	
		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'wpcasa-bahia' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'wpcasa-bahia' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
	
		<?php else : ?>
	
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-meta">
					<div class="comment-author vcard">
						<?php if ( 0 != $args['avatar_size'] ) echo '<span class="image">' . get_avatar( $comment, $args['avatar_size'] ) . '</span>'; ?>
						<?php printf( __( '%s <span class="says">says:</span>', 'wpcasa-bahia' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</div><!-- .comment-author -->
	
					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'wpcasa-bahia' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'wpcasa-bahia' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->
	
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wpcasa-bahia' ); ?></p>
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
			</article><!-- .comment-body -->
	
		<?php
		endif;	
		
	}
	
	add_filter( 'comment_reply_link', 'wpsight_bahia_comment_reply_link' );
	
	function wpsight_bahia_comment_reply_link( $link ) {		
		return str_replace( 'comment-reply-link' , 'comment-reply-link button small', $link );		
	}

} // endif check for wpsight_bahia_comment()

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'wpcasa-bahia' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'wpcasa-bahia' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'wpcasa-bahia' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'wpcasa-bahia' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'wpcasa-bahia' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'wpcasa-bahia' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'wpcasa-bahia' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'wpcasa-bahia' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'wpcasa-bahia' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'wpcasa-bahia' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'wpcasa-bahia' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'wpcasa-bahia' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'wpcasa-bahia' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'wpcasa-bahia' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . do_shortcode( $description ) . $after;  // WPCS: XSS OK
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function wpsight_bahia_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'wpsight_bahia_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'wpsight_bahia_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wpsight_bahia_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wpsight_bahia_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in wpsight_bahia_categorized_blog.
 */
function wpsight_bahia_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'wpsight_bahia_categories' );
}
add_action( 'edit_category', 'wpsight_bahia_category_transient_flusher' );
add_action( 'save_post',     'wpsight_bahia_category_transient_flusher' );

/**
 * Custom excerpt and more link
 *
 * @uses get_post_type()
 * @uses get_the_ID()
 * @uses __()
 * @uses get_permalink()
 * @uses apply_filters()
 * @return string $excerpt_more Read more link
 *
 * @since 1.0
 */
 
add_filter( 'excerpt_more', 'wpsight_bahia_excerpt_more' );

function wpsight_bahia_excerpt_more() {

	$more_text = get_post_type( get_the_ID() ) == 'post' ? __( 'Read more', 'wpcasa-bahia' ) : __( 'More info', 'wpcasa-bahia' );

	$excerpt_more = '<span class="moretag"><a class="button small" href="'. get_permalink( get_the_ID() ) . '">' . apply_filters( 'wpsight_bahia_more_text', $more_text ) . '</a></span>';

	return apply_filters( 'wpsight_bahia_excerpt_more', $excerpt_more );
	
}

/**
 * Echo custom excerpt
 *
 * @param integer $post_id Post ID
 * @param bool $excerpt Show WordPress excerpt or not
 * @param integer $length Length of the excerpt
 * @uses get_the_ID()
 * @uses get_post()
 * @uses get_the_content()
 * @uses preg_match()
 * @uses wpsight_bahia_excerpt_more()
 * @uses apply_filters()
 * @uses the_excerpt()
 * @uses the_content()
 *
 * @since 1.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_bahia_the_excerpt' ) ) {

	function wpsight_bahia_the_excerpt( $post_id = '', $excerpt = false, $length = '' ) {	
		global $post, $more;

		$more = false;
	
		if( empty( $post_id ) )
		    $post_id = get_the_ID();
		
		// Get post object	
		$post = get_post( $post_id );
		
		/**
		 * If length parameter provided,
		 * create custom excerpt.
		 */
			
		if( ! empty( $length ) ) {
		
			// Clean up excerpt
			$output = trim( strip_tags( strip_shortcodes( $post->post_content ) ) );
			
			// Respect post excerpt
			if( ! empty( $post->post_excerpt ) )
				$output = trim( strip_tags( strip_shortcodes( $post->post_excerpt ) ) );
			
			// Stop if no content
			if( empty( $output ) )
				return;
			
			// Get post word count	
			$count = count( explode( ' ', $output ) );
			
			// Respect more tag
			
			if( strpos( $post->post_content, '<!--more-->' ) ) {
				$output = get_the_content( '', true );
			} else {		
				// Get excerpt depening on $length		
				preg_match( '/^\s*+(?:\S++\s*+){1,' . $length . '}/', $output, $matches );	  
				$output = $matches[0];
			}
			
			// If content longer than excerpt, display more	
			if( $length <= $count )
				$output .= wpsight_bahia_excerpt_more();
			
			// Respect the_excerpt filter	
			$output = apply_filters( 'the_excerpt', $output );
			
			// Finally display custom excerpt
			echo $output;
			
			return;
		
		}
		
		/**
		 * Check if only the_excerpt or
		 * the_content with more.
		 */
			
		if( $excerpt == true || ! empty( $post->post_excerpt ) ) {
			the_excerpt();
		} else {	
			the_content( wpsight_bahia_excerpt_more() );	
		}
	
	}

}

/**
 * Filter images added through post editor
 */

add_filter( 'get_image_tag', 'wpsight_bahia_get_image_tag', 10, 6 );

function wpsight_bahia_get_image_tag( $html, $id, $alt, $title, $align, $size ) {	
	return '<span class="image ' . $align . '">' . $html . '</span>';	
}

/**
 * Filter get_search_form
 */

add_filter( 'get_search_form', 'wpsight_bahia_search_form' );

function wpsight_bahia_search_form( $form ) {
	
	ob_start(); ?>

	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="row 50%">
			<div class="8u 12u(medium)">
				<label>
					<span class="screen-reader-text"><?php _ex( 'Search for', 'search form', 'wpcasa-bahia' ); ?></span>
					<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'search form', 'wpcasa-bahia' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
				</label>
			</div>
			<div class="4u 6u(medium)">
				<input type="submit" class="search-submit fit" value="<?php echo esc_attr_x( 'Search', 'search form', 'wpcasa-bahia' ); ?>">
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
add_filter( 'embed_oembed_html', 'wpsight_bahia_oembed_handler', 10, 4 );

function wpsight_bahia_oembed_handler( $html, $url, $attr, $post_ID ) {
    return '<div class="oembed">' . $html . '</div>';
}
