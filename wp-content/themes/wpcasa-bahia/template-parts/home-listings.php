<?php
/**
 * Home listings template
 *
 * @package WPCasa Bahia
 */
$listings_display = get_post_meta( get_the_id(), '_listings_display', true );

if( $listings_display ) : ?>

	<?php
		
		$listing_queries = get_post_meta( get_the_id(), '_listings', true );
		
		$listings = array();
		
		$i = 1;
		
		foreach( $listing_queries as $home_query ) {
			
			// Set up query instance
			
			$instance = array(
				'nr'			=> isset( $home_query['_listings_nr'] ) ? $home_query['_listings_nr'] : false,
				'offer_filter'	=> isset( $home_query['_listings_offer'] ) ? $home_query['_listings_offer'] : false
			);
			
			foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {
				
				if( 'language' == $key || 'post_translations' == $key )
					continue;
				
				$instance[ 'taxonomy_filter_' . $key ] = isset( $home_query[ '_taxonomy_filter_' . $key ] ) ? $home_query[ '_taxonomy_filter_' . $key ] : false;

			}
			
			// Process listings instance
			
			$nr 				= absint( $instance['nr'] );
			$offer_filter		= isset( $instance['offer_filter'] ) ? strip_tags( $instance['offer_filter'] ) : false;
			$taxonomy_filters	= array();
			
			foreach( get_object_taxonomies( wpsight_post_type(), 'objects' ) as $key => $taxonomy ) {
				
				if( 'language' == $key || 'post_translations' == $key )
					continue;
				
				if( $instance[ 'taxonomy_filter_' . $key ] != false )
					$taxonomy_filters[ $key ] = strip_tags( $instance[ 'taxonomy_filter_' . $key ] );		
			}
			
			$defaults = array(
				'nr' => 10
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			// Listings args
			
			$listings_args = array(
				'nr'				=> $nr,
				'offer'				=> $offer_filter,
				'meta_query'		=> array(
					array(
						'key'		=> '_thumbnail_id',
						'compare'	=> 'EXISTS'
					)
				)
			);
			
			// Merge taxonomy filters into args and apply filter hook
			$listings_args = apply_filters( 'wpsight_bahia_home_listings_query_args', array_merge( $listings_args, $taxonomy_filters ), $instance, 'home' );
			
			// Finally get listings
			$listings[ $i ]['query'] = wpsight_get_listings( $listings_args );
			
			// Set up section data
			
			$listings[ $i ]['data'] = array(
				'title'	=> isset( $home_query['_listings_title'] ) ? $home_query['_listings_title'] : false,
				'label'	=> isset( $home_query['_listings_button_label'] ) ? $home_query['_listings_button_label'] : false,
				'url'	=> isset( $home_query['_listings_button_url'] ) ? $home_query['_listings_button_url'] : false
			);
			
			$i++;
		
		}
		
	?>
	
	<?php if( $listings ) : ?>

		<div id="home-listings" class="site-main site-section">
		
			<div class="container">
		
				<main class="content 12u$" role="main" itemprop="mainContentOfPage">
				
					<?php foreach( $listings as $key => $section ) : ?>
				
						<div class="home-section">
						
							<?php if( $section['data']['title'] ) : ?>
						
							<div class="site-section-title">
						
								<div class="row">
								
									<?php if( $section['data']['url'] ) : ?>
								
									<div class="6u 12u$(small)">							
										<h2><?php echo esc_attr( $section['data']['title'] ); ?></h2>						
									</div>
									
									<div class="6u$ 12u(small)">
								
										<div class="align-right">
											<a href="<?php echo esc_url( $section['data']['url'] ); ?>" class="button">
												<?php echo esc_attr( $section['data']['label'] ); ?>
											</a>
										</div>
								
									</div>
									
									<?php else : ?>
									
									<div class="12u">							
										<h2><?php echo esc_attr( $section['data']['title'] ); ?></h2>						
									</div>
									
									<?php endif; ?>
									
								</div><!-- .row -->
							
							</div><!-- .site-section-title -->
						
						<?php endif; ?>
		
						<?php wpsight_listings( $section['query'] ); ?>
					
					</div>
					
					<?php endforeach; ?>
				
				</main>
			
			</div><!-- .container -->
		
		</div><!-- .site-main -->
	
	<?php endif; ?>

<?php endif; ?>