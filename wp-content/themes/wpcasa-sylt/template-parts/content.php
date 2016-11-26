<?php
/**
 * @package WPCasa Sylt
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	
	<?php if( has_post_thumbnail() ) : ?>		
		<div class="entry-image image left">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></a>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php wpsight_sylt_the_excerpt( get_the_ID(), true, apply_filters( 'wpsight_sylt_excerpt_length', 25 ) ); ?>

		<?php if( is_singular() ) : ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpcasa-sylt' ),
				'after'  => '</div>',
			) );
		?>
		<?php endif; ?>

		<div class="entry-meta">
			<?php wpsight_sylt_posted_on(); ?>
		</div><!-- .entry-meta -->

	</div><!-- .entry-content -->

</article><!-- #post-## -->
