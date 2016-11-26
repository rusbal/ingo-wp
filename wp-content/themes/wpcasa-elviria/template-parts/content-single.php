<?php
/**
 * @package WPCasa Elviria
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php wpsight_elviria_posted_on(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">
	
		<?php $page = null !== get_query_var( 'page' ) ? get_query_var( 'page' ) : false; ?>
	
		<?php if( has_post_thumbnail() && $page < 2 ) : ?>		
			<div class="entry-image image fit"><?php the_post_thumbnail( 'wpsight-large', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></div>		
		<?php endif; ?>

		<?php the_content(); ?>
		
		<?php
			wp_link_pages( array(
				'before'		=> '<div class="page-links">' . esc_html__( 'Pages:', 'wpcasa-elviria' ),
				'after'			=> '</div>',
				'link_before' 	=> '<span>',
				'link_after'	=> '</span>'
			) );
		?>
		
		<?php
			$tags_list = get_the_tag_list();
			if ( $tags_list ) {
				printf( '<div class="tags-links">%s</div>', $tags_list );
			}
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
