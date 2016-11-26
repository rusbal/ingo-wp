<?php
/**
 * Home search template
 *
 * @package WPCasa Sylt
 */

// Get search display option
$tagline_display = get_option( 'wpsight_sylt_header_tagline_display', 'home' );

// Set when to display search

$display = false;

// Display on all pages

if( 'all' == $tagline_display )
	$display = true;
	
// Display only on home page

if( 'home' == $tagline_display && is_front_page() )
	$display = true;

// Set antimate class
$animate = is_front_page() ? ' animated fadeIn' : ''; ?>

<?php if( $display ) : ?>

<?php $animate = is_front_page() ? ' animated fadeIn' : ''; ?>

<div id="home-tagline" class="site-section home-section<?php echo $animate; ?>">
	
	<div class="container">

		<div class="content 12u$">
			
			<div id="tagline">
				<?php echo get_option( 'wpsight_sylt_header_tagline', '<span>Let us help you find <em>your dream</em> home</span>' ); ?>
			</div>
		
		</div>
	
	</div><!-- .container -->

</div><!-- #home-tagline -->

<?php endif; ?>