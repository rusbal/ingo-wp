<?php
/**
 * Home call to action #1 template
 *
 * @package WPCasa Elviria
 */
$cta_1_display = get_post_meta( get_the_id(), '_cta_1_display', true );

if( $cta_1_display ) : ?>

	<div id="home-cta-1" class="site-cta site-section home-section">
	
		<div class="container">
	
			<div class="content 12u$">
			
				<?php $cta_1_title = get_post_meta( get_the_id(), '_cta_1_title', true ); ?>
				
				<div class="cta-title">
					<h2><?php echo esc_attr( $cta_1_title ); ?></h2>
				</div>
				
				<?php $cta_1_description = get_post_meta( get_the_id(), '_cta_1_description', true ); ?>
				
				<div class="cta-description">					
					<p><?php echo wp_kses_post( nl2br( $cta_1_description ) ); ?></p>					
				</div>
				
				<?php $cta_1_label = get_post_meta( get_the_id(), '_cta_1_button_label', true ); ?>
				<?php $cta_1_url = get_post_meta( get_the_id(), '_cta_1_button_url', true ); ?>
				
				<div class="cta-button">
					<a href="<?php echo esc_url( $cta_1_url ); ?>" class="button"><?php echo esc_attr( $cta_1_label ); ?></a>
				</div>
			
			</div>
		
		</div><!-- .container -->
	
	</div><!-- .site-cta -->

<?php endif; ?>