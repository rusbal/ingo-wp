<?php
/**
 * Home call to action #2 template
 *
 * @package WPCasa Sylt
 */
$cta_2_display = get_post_meta( get_the_id(), '_cta_2_display', true );

if( $cta_2_display ) : ?>

	<div id="home-cta-2" class="site-cta site-section home-section">
	
		<div class="container">
	
			<div class="content 12u$">
			
				<?php $cta_2_title = get_post_meta( get_the_id(), '_cta_2_title', true ); ?>
				
				<div class="cta-title">
					<h2><?php echo esc_attr( $cta_2_title ); ?></h2>
				</div>
				
				<?php $cta_2_description = get_post_meta( get_the_id(), '_cta_2_description', true ); ?>
				
				<?php if( $cta_2_description ) : ?>
				<div class="cta-description">					
					<p><?php echo wp_kses_post( nl2br( $cta_2_description ) ); ?></p>					
				</div>
				<?php endif; ?>
				
				<?php $cta_2_label = get_post_meta( get_the_id(), '_cta_2_button_label', true ); ?>
				<?php $cta_2_url = get_post_meta( get_the_id(), '_cta_2_button_url', true ); ?>
				
				<?php if( $cta_2_url ) : ?>
				<div class="cta-button">
					<a href="<?php echo esc_url( $cta_2_url ); ?>" class="button"><?php echo esc_attr( $cta_2_label ); ?></a>
				</div>
				<?php endif; ?>
			
			</div>
		
		</div><!-- .container -->
	
	</div><!-- .site-cta -->

<?php endif; ?>