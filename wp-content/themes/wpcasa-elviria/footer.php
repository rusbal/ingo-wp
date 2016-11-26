<?php
/**
 * The template for displaying the footer.
 *
 * @package WPCasa Elviria
 */
?>

		<div class="site-footer-bg">

			<?php if( is_active_sidebar( 'footer' ) ) : ?>
		
				<div class="site-footer-top site-section">
				
					<div class="container">
						
						<div class="row 150%">
		
							<?php dynamic_sidebar( 'footer' ); ?>
							
						</div>
							
					</div><!-- .container -->
				
				</div><!-- .footer-top -->
				
				<div class="site-hr">
			
					<div class="container">
						<hr />
					</div>
				
				</div><!-- .site-hr -->
			
			<?php endif; ?>
			
			<footer class="site-footer site-section" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
				
				<div class="container">
					
					<div class="row">
				
						<div class="12u$">
				
							<p>
								<?php printf( 'Copyright &copy; %s', '<span itemprop="copyrightYear">' . date( 'Y' ) . '</span>' ); ?> &sdot;
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="copyrightHolder"><?php bloginfo( 'name' ); ?></a> &sdot;
								<?php _e( 'Built on', 'wpcasa-elviria' ); ?> <a href="http://wordpress.org">WordPress</a> &amp; <a href="https://wpcasa.com">WPCasa</a>
							</p>
					
						</div>
					
					</div>
				
				</div><!-- .container -->
		
			</footer>
			
		</div><!-- .site-footer-bg -->
		
	</div><!-- .site-container -->

	<?php wp_footer(); ?>

</body>
</html>