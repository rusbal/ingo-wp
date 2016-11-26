<div class="wpsight-dashboard table-wrapper">

	<table class="wpsight-listings-dashboard alt">
		<thead>
			<tr>
				<?php foreach ( $dashboard_columns as $key => $column ) : ?>
					<th class="wpsight-dashboard-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $column ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php if( ! $listings->have_posts() ) : ?>
				<tr>
					<td colspan="<?php echo count( $dashboard_columns ); ?>"><?php _e( 'You currently do not have any active listings.', 'wpsight-dashboard' ); ?></td>
				</tr>
			<?php else : $datef = wpsight_get_option( 'dashboard_date_format' ); ?>			
				<?php while ( $listings->have_posts() ) : $listings->the_post(); $post = get_post(); ?>
					<tr <?php wpsight_listing_class( 'dashboard-row' ); ?>>
						<?php foreach ( $dashboard_columns as $key => $column ) : ?>
							<td class="wpsight-dashboard-<?php echo esc_attr( $key ); ?>">

								<?php if ('image' === $key ) : $size = is_page_template( 'page-tpl-full.php' ) ? 'post-thumbnail' : array( 150, 150 ); ?>
									<?php wpsight_listing_thumbnail( $post->ID, $size ); ?>

								<?php elseif ('title' === $key ) : ?>
									<?php the_title( '<h4>', '</h4>' ); ?>									
									<div class="listings-dashboard-taxonomies">
									
										<?php
											$type = wpsight_get_listing_terms( 'listing-type' );
											$location = wpsight_get_listing_terms( 'location' );
										?>
									
										<?php if( $type ) echo wpsight_get_listing_terms( 'listing-type', $post->ID, ', ', '', '', false ) . ' '; ?>
										
										<?php if( $type && $location ) echo '&dash; '; ?>
										
										<?php if( $location ) echo wpsight_get_listing_terms( 'location', $post->ID, ' &rsaquo; ', '', '', false ) . ' '; ?>
										
										<small>(<?php wpsight_offer( wpsight_get_listing_offer( $post->ID, false ) ); ?>)</small>

									</div>
									
									<small><?php wpsight_listing_price( $post->ID ); ?></small>
									
									<div class="listings-dashboard-actions">
										<?php
											foreach ( wpsight_dashboard_actions() as $action => $v ) {
												$action_url = add_query_arg( array( 'action' => $action, 'item_id' => $post->ID ) );
												
												if ( $v['nonce'] )
													$action_url = wp_nonce_url( $action_url, 'wpsight_listings_dashboard_actions' );
												
												$allowed_targets = array( '_blank', '_parent', '_top' );
												$target = isset( $v['target'] ) && in_array( $v['target'], $allowed_targets ) ? $v['target'] : '';
												
												if ( ! empty( $target ) )
													$target = ' target="' . $target . '"';

												$confirm	= 'delete' == $action ? ' onclick="return confirm(\'' . __( 'Are you sure you want to continue?', 'wpsight-dashboard' ) . '\')"' : '';
												$danger		= 'delete' == $action ? ' danger' : '';

												echo '<a href="' . esc_url( $action_url ) . '" class="wpsight-dashboard-action-' . $action . $danger . ' button small alt"' . $confirm . $target . '>' . $v['label'] . '</a>';
											}
										?>
									</div>
									
									<?php if( wpsight_is_listing_not_available( $post->ID ) ) : ?>
									<small><?php _e( 'This listing is currently marked unavailable.', 'wpsight-dashboard' ); ?></small>
									<?php endif; ?>

								<?php elseif ( 'status' === $key ) : ?>
									<small><?php printf( __( 'Posted: %s', 'wpsight-dashboard' ), date_i18n( $datef, strtotime( $post->post_date ) ) ); ?></small>
									<br /><?php echo '<span class="listing-status status-' . sanitize_html_class( $post->post_status ) . '"><span></span> ' . wpsight_get_status( $post->post_status ) . '</span>'; ?>

								<?php elseif ( 'date' === $key ) : ?>
									<?php echo date_i18n( $datef, strtotime( $post->post_date ) ); ?>

								<?php elseif ( 'expire' === $key ) : ?>
									<small><?php echo $post->_listing_expires ? date_i18n( $datef, $post->_listing_expires ) : '&ndash;'; ?></small>

								<?php else : ?>
									<?php do_action( 'wpsight_dashboard_column_' . $key, $post ); ?>

								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endwhile; ?>
			<?php endif; ?>
		</tbody>
	</table>

	<?php wpsight_pagination( $listings->max_num_pages ); ?>

</div><!-- .wpsight-dashboard -->