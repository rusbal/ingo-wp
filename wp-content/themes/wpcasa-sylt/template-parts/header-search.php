<?php
/**
 * Home search template
 *
 * @package WPCasa Sylt
 */

// Get search display option
$search_display = get_option( 'wpsight_sylt_header_search_display', 'all' );

// Set when to display search

$display = false;

// Display on all pages

if( 'all' == $search_display )
	$display = true;
	
// Display only on home page

if( 'home' == $search_display && is_front_page() )
	$display = true;

// Set antimate class
$animate = is_front_page() ? ' animated fadeIn' : ''; ?>

<?php if( $display ) : ?>

<div id="home-search" class="site-section home-section<?php echo $animate; ?>">
	
	<div class="container">

		<div class="content 12u$">
			
			<?php wpsight_search(); ?>
		
		</div>
	
	</div><!-- .container -->

</div><!-- #home-search -->

<?php endif; ?>