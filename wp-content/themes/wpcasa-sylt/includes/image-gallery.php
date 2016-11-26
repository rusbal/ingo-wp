<?php
/**
 * WPCasa Sylt gallery
 *
 * @package WPCasa Sylt
 */

add_filter( 'wpsight_meta_boxes', 'wpsight_sylt_meta_boxes_listing_images' );

function wpsight_sylt_meta_boxes_listing_images( $meta_boxes ) {
	
	$meta_boxes['listing_images'] = wpsight_meta_box_listing_images();
	
	return $meta_boxes;
	
}

/**
 * wpsight_sylt_image_gallery()
 *
 * Echo wpsight_sylt_get_gallery()
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses wpsight_sylt_get_image_gallery()
 *
 * @since 1.0.0
 */
 
function wpsight_sylt_image_gallery( $post_id = '', $args = array() ) {
	echo wpsight_sylt_get_image_gallery( $post_id, $args );
}

/**
 * wpsight_sylt_get_image_gallery()
 *
 * Create thumbnail gallery.
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 * @uses wpsight_sylt_image_gallery_root()
 * @uses wpsight_sylt_image_gallery_js()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */

function wpsight_sylt_get_image_gallery( $post_id = '', $args = array() ) {
	
	// If we have a post ID, get _gallery post meta
	
	if( ! is_array( $post_id ) ) {
	
		// Set default post ID
    	
    	if( ! $post_id )
			$post_id = get_the_ID();
		
		// Get gallery imgages		
		$images = array_keys( get_post_meta( absint( $post_id ), '_gallery', true ) );
		
		// Set gallery ID
		$gallery_id = 'wpsight-gallery-' . absint( $post_id );
			
	// Else set array of image attachment IDs
	
	} else {
		
		$images = array_map( 'absint', $post_id );
		
		// Set gallery ID
		$gallery_id = 'wpsight-gallery-' . implode( '-', $images );
		
	}
	
	// If there are images, create the gallery
	
	if( $images ) {
		
		// Set default classes
		
		$default_class_gallery	= isset( $args['class_gallery'] ) ? $args['class_gallery'] : $gallery_id . ' wpsight-gallery';
		$default_class_row		= isset( $args['class_row'] ) ? $args['class_row'] : 'row uniform';
		$default_class_unit		= isset( $args['class_unit'] ) ? $args['class_unit'] : 'wpsight-gallery-item-u 6u';
		
		// Set some defaults
		
		$defaults = array(
			'class_gallery'    		=> $default_class_gallery,
			'class_row' 	   		=> $default_class_row,
			'class_unit' 	   		=> $default_class_unit,
			'class_item'	   		=> 'wpsight-gallery-item image fit',
			'thumbs_gutter'	   		=> 100, // % of default gutter
			'thumbs_size' 	   		=> 'wpsight-half',
			'thumbs_caption'    	=> true,
			'thumbs_link'	   		=> true,
			'lightbox_size'	   		=> 'wpsight-full',
			'lightbox_caption' 		=> true,
			'lightbox_prev_next'   	=> true,
			'lightbox_zoom'		   	=> true,
			'lightbox_fullscreen'  	=> true,
			'lightbox_share'	   	=> false,
			'lightbox_close'	   	=> true,
			'lightbox_counter'	   	=> true
		);
		
		// Merge args with defaults and apply filter
		$args = apply_filters( 'wpsight_sylt_get_image_gallery_args', wp_parse_args( $args, $defaults ), $post_id, $args );
		
		// Sanitize gallery class
		
		$class_gallery = ! is_array( $args['class_gallery'] ) ? explode( ' ', $args['class_gallery'] ) : $args['class_gallery'];		
		$class_gallery = array_map( 'sanitize_html_class', $class_gallery );
		$class_gallery = implode( ' ', $class_gallery );
		
		// Sanitize unit class
		
		$class_unit = ! is_array( $args['class_unit'] ) ? explode( ' ', $args['class_unit'] ) : $args['class_unit'];		
		$class_unit = array_map( 'sanitize_html_class', $class_unit );
		$class_unit = implode( ' ', $class_unit );
		
		if( $args['thumbs_columns_small'] )
			$class_unit .= ' ' . absint( $args['thumbs_columns_small'] ) . 'u(medium)';
		
		// Sanitize row class
		
		$class_row = ! is_array( $args['class_row'] ) ? explode( ' ', $args['class_row'] ) : $args['class_row'];		
		$class_row = array_map( 'sanitize_html_class', $class_row );
		$class_row = implode( ' ', $class_row );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Check thumbnail gutter
		$args['thumbs_gutter'] = in_array( $args['thumbs_gutter'], array( 0, 25, 50, 100, 150, 200 ) ) ? $args['thumbs_gutter'] : '100';
		
		// Check thumbnail size
		$args['thumbs_size'] = in_array( $args['thumbs_size'], get_intermediate_image_sizes() ) || 'full' == $args['thumbs_size'] ? $args['thumbs_size'] : 'post-thumbnail';
		
		// Check lightbox size
		$args['lightbox_size'] = in_array( $args['lightbox_size'], get_intermediate_image_sizes() ) ? $args['lightbox_size'] : 'full';
		
		// Check thumbnail caption
		$args['thumbs_caption'] = $args['thumbs_caption'] == true ? true : false;
		
		// Check thumbnail links
		$args['thumbs_link'] = $args['thumbs_link'] == true ? true : false;
		
		// Check thumbnail caption
		$args['lightbox_caption'] = $args['lightbox_caption'] == true ? true : false;
		
		// Check show prev_next
		$args['lightbox_prev_next'] = $args['lightbox_prev_next'] == true ? true : false;
		
		// Check show zoom
		$args['lightbox_zoom'] = $args['lightbox_zoom'] == true ? true : false;
		
		// Check show fullscreen toggle
		$args['lightbox_fullscreen'] = $args['lightbox_fullscreen'] == true ? true : false;
		
		// Check show sharing options
		$args['lightbox_share'] = $args['lightbox_share'] == true ? true : false;
		
		// Check show close button
		$args['lightbox_close'] = $args['lightbox_close'] == true ? true : false;
		
		// Check show close button
		$args['lightbox_counter'] = $args['lightbox_counter'] == true ? true : false;
		
		// Set counter
		$i = 0;
		
		ob_start(); ?>
	
		<div class="<?php echo $class_gallery; ?>" itemscope itemtype="http://schema.org/ImageGallery">
					
			<div class="<?php echo $class_row; ?> <?php echo absint( $args['thumbs_gutter'] ) . '%'; ?>">
		
				<?php foreach( $images as $image ) : ?>
				
					<?php
						$attachment 	= get_post( absint( $image ) );
						$attachment_src = wp_get_attachment_image_src( $attachment->ID, $args['lightbox_size'] );
						$thumbnail_src  = wp_get_attachment_image_src( $attachment->ID, $args['thumbs_size'] );
					?>
					
					<?php if( $attachment !== NULL && $attachment->post_type == 'attachment' ) : ?>
				
						<div class="<?php echo $class_unit; ?>">
						
							<figure class="<?php echo $class_item; ?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
						
								<?php if( $args['thumbs_link'] === true ) : ?>
								<a href="<?php echo esc_url( $attachment_src[0] ); ?>" itemprop="contentUrl" data-size="<?php echo absint( $attachment_src[1] ); ?>x<?php echo absint( $attachment_src[2] ); ?>" data-counter="<?php echo absint( $i ); ?>">
								<?php endif; ?>
						
									<img src="<?php echo esc_url( $thumbnail_src[0] ); ?>" itemprop="thumbnail" alt="<?php echo esc_attr( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ); ?>" />
						
									<?php if( $attachment->post_content && $args['lightbox_caption'] === true ) : ?>
									<meta itemprop="title" content="<?php echo esc_attr( $attachment->post_content ); ?>">
									<?php endif; ?>
						
									<meta itemprop="width" content="<?php echo absint( $thumbnail_src[1] ); ?>">
									<meta itemprop="height" content="<?php echo absint( $thumbnail_src[2] ); ?>">
								
								<?php if( $args['thumbs_link'] === true ) : ?>
								</a>
								<?php endif; ?>
								
								<?php if( $attachment->post_excerpt && $args['thumbs_caption'] === true ) : ?>
								<figcaption class="wpsight-gallery-caption" itemprop="caption description"><?php echo $attachment->post_excerpt; ?></figcaption>
								<?php endif; ?>
						
							</figure>
						
						</div>
					
					<?php $i++; endif; ?>
				
				<?php endforeach; ?>
						
			</div><!-- .<?php echo $class_row; ?> -->
		
		</div><?php
		
		// Include Photoswipe lightbox markup, if active
		
		if( apply_filters( 'wpsight_sylt_photoswipe', true ) == true ) {
			wpsight_sylt_image_gallery_root( $args, $gallery_id );
			wpsight_sylt_image_gallery_js( $args, $gallery_id );
		}
		
		return apply_filters( 'wpsight_sylt_get_image_gallery', ob_get_clean(), $post_id, $args, $gallery_id );
	
	}
	
}

/**
 * wpsight_sylt_image_gallery_root()
 *
 * Echo wpsight_sylt_get_image_gallery_root()
 *
 * @param array $args Array of arguments
 * @param string $gallery_id Unique ID for Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_image_gallery_root( $args = array(), $gallery_id ) {
	echo wpsight_sylt_get_image_gallery_root( $args, $gallery_id );
}

/**
 * wpsight_sylt_get_image_gallery_root()
 *
 * Create Photoswipte root element that
 * serves as HTML markup for the lightbox.
 *
 * @param array $args Array of arguments
 * @param string $gallery_id Unique ID for Photoswipe root element
 *
 * @return string HTML markup of Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_get_image_gallery_root( $args = array(), $gallery_id ) {
	
	ob_start(); ?>

	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div id="<?php echo esc_attr( $gallery_id ); ?>" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="pswp__bg"></div>
	    <div class="pswp__scroll-wrap">
	        <div class="pswp__container">
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	        </div>
	        <div class="pswp__ui pswp__ui--hidden">		
	            <div class="pswp__top-bar">
	            
	                <div class="pswp__counter"<?php if( ! $args['lightbox_counter'] ) echo ' style="visibility:hidden"'; ?>></div>
	                
	                <?php if( $args['lightbox_close'] )	: ?>
	                <button class="pswp__button pswp__button--close" title="<?php echo esc_attr_x( 'Close (Esc)', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>"></button>
	                <?php endif; ?>
	                
	                <?php if( $args['lightbox_share'] )	: ?>
	                <button class="pswp__button pswp__button--share" title="<?php echo esc_attr_x( 'Share', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>"></button>
	                <?php endif; ?>
	                
	                <?php if( $args['lightbox_fullscreen'] ) : ?>
	                <button class="pswp__button pswp__button--fs" title="<?php echo esc_attr_x( 'Fullscreen', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>"></button>
	                <?php endif; ?>
	                
	                <?php if( $args['lightbox_zoom'] ) : ?>
	                <button class="pswp__button pswp__button--zoom" title="<?php echo esc_attr_x( 'Zoom', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>"></button>
	                <?php endif; ?>
	                
	                <div class="pswp__preloader">
	                    <div class="pswp__preloader__icn">
	                      <div class="pswp__preloader__cut">
	                        <div class="pswp__preloader__donut"></div>
	                      </div>
	                    </div>
	                </div>
	            </div>		
	            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
	                <div class="pswp__share-tooltip"></div> 
	            </div>
	            
	            <?php if( $args['lightbox_prev_next'] )	: ?>
	            <button class="pswp__button pswp__button--arrow--left" title="<?php echo esc_attr_x( 'Previous', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>">
	            </button>		
	            <button class="pswp__button pswp__button--arrow--right" title="<?php echo esc_attr_x( 'Next', 'photoswipe lightbox', 'wpcasa-sylt' ); ?>">
	            </button>
	            <?php endif; ?>
	            
	            <div class="pswp__caption">
	                <div class="pswp__caption__center"></div>
	            </div>		
	        </div>		
	    </div>		
	</div><?php
	
	return apply_filters( 'wpsight_sylt_get_image_gallery_root', ob_get_clean(), $args, $gallery_id );

}

/**
 * wpsight_sylt_image_gallery_js()
 *
 * Echo wpsight_sylt_get_image_gallery_js()
 *
 * @param array $args Array of arguments
 * @param string $slider_id Unique ID for Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_image_gallery_js( $args = array(), $gallery_id ) {
	echo wpsight_sylt_get_image_gallery_js( $args, $gallery_id );
}

/**
 * wpsight_sylt_get_image_gallery_js()
 *
 * Create Photoswipe Javascript.
 *
 * @param array $args Array of arguments
 * @param string $slider_id Unique ID for Photoswipe root element
 *
 * @return string Javascript output
 *
 * @since 1.0.0
 */

function wpsight_sylt_get_image_gallery_js( $args = array(), $gallery_id ) {
	
	ob_start(); ?>
	
	<script type="text/javascript">
		(function($) {
		    var $pswp = $('#<?php echo esc_attr( $gallery_id ); ?>')[0];
		    var image = [];
		
		    $('.wpsight-gallery').each( function() {
		        var $pic     = $(this),
		            getItems = function() {
		                var items = [];
		                $pic.find('a').each(function() {
		                    var $href   = $(this).attr('href'),
		                        $size   = $(this).data('size').split('x'),
		                        $width  = $size[0],
		                        $height = $size[1],
		                        $title  = $(this).find('meta[itemprop=title]').attr('content');
		
		                    var item = {
		                        src  : $href,
		                        w    : $width,
		                        h    : $height,
		                        title: $title
		                    }
		
		                    items.push(item);
		                });
		                return items;
		            }
		
		        var items = getItems();
		
		        $.each(items, function(index, value) {
		            image[index]     = new Image();
		            image[index].src = value['src'];
		        });
		
		        $pic.on('click', '.wpsight-gallery-item a', function(event) {
		            event.preventDefault();
		            
		            var $index = $(this).data('counter');
		            
		            var options = {
		                index: $index,
		                bgOpacity: 0.8,
		                showHideOpacity: true
		            }
		
		            var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
		            lightBox.init();
		        });
		    });
		})(jQuery);
	</script><?php
	
	return apply_filters( 'wpsight_sylt_get_image_gallery_js', ob_get_clean(), $args, $gallery_id );
	
}
