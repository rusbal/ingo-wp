<?php
/**
 * The template for displaying the footer.
 *
 * @package WPCasa STAGE
 */
?>

		<?php if( is_active_sidebar( 'footer' ) ) : ?>
		
			<div class="container">
				<hr />
			</div>

			<div class="site-footer-top site-section">
			
				<div class="container">
					
					<div class="row">

						<div class="12u$">
				
							<?php dynamic_sidebar( 'footer' ); ?>
							
						</div>
						
					</div>
						
				</div><!-- .container -->
			
			</div><!-- .footer-top -->
		
		<?php endif; ?>
		
		<div class="container">
			<hr />
		</div>
		
		<footer class="site-footer site-section" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
			
			<div class="container">
				
				<div class="row">

					<div class="12u$">

						<p>
							<?php printf( 'Copyright &copy; %s', '<span itemprop="copyrightYear">' . date( 'Y' ) . '</span>' ); ?> &sdot;
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="copyrightHolder"><?php bloginfo( 'name' ); ?></a> &sdot;
							<?php _e( 'Built on', 'wpsight-stage' ); ?> <a href="http://wordpress.org">WordPress</a> &amp; <a href="http://wpcasa.com">WPCasa</a>
						</p>
				
					</div>
				
				</div>

			</div><!-- .container -->

		</footer>

	</div><!-- .site-container -->

	<?php wp_footer(); ?>

</body>
</html>