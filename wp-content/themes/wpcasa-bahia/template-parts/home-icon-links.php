<?php
/**
 * Home icon links template
 *
 * @package WPCasa Bahia
 */
$icons_display = get_post_meta( get_the_id(), '_icon_links_display', true );

if( $icons_display ) : ?>

	<?php $icon_links = get_post_meta( get_the_id(), '_icon_links', true ); ?>

	<div id="home-icons" class="site-section home-section">
	
		<div class="container">
	
			<div class="row 0%">
			
				<?php
					// Set column class depending on count
					
					$icon_count = count( $icon_links );
					
					$class = '2u 4u(medium) 6u(small)';
					
					if( 1 == $icon_count )
						$class = '12u$';
					
					if( 2 == $icon_count )
						$class = '6u';
					
					if( 3 == $icon_count )
						$class = '4u 12u$(small)';
					
					if( 4 == $icon_count )
						$class = '3u 6u(medium)';

				?>
			
				<?php foreach( $icon_links as $key => $link ) : ?>
						
				<div class="<?php echo $class; ?>">
				
					<a href="<?php echo esc_url( $link['_icon_link_url'] ); ?>" class="equal feature">				
						<span class="icon <?php echo esc_attr( $link['_icon_link_icon'] ); ?>"></span>	
						<span class="feature-title"><?php echo esc_attr( $link['_icon_link_label'] ); ?></span>
						<span class="feature-description"><?php echo esc_attr( $link['_icon_link_desc'] ); ?></span>	
					</a>
			
				</div>
				
				<?php endforeach; ?>
				
			</div><!-- .row -->
		
		</div><!-- .container -->
	
	</div><!-- #home-icons -->

<?php endif; ?>