<?php
/**
 * WPCasa Sylt listing image slider
 *
 * @package WPCasa Sylt
 */

/**
 * wpsight_sylt_image_slider()
 *
 * Echo wpsight_sylt_get_image_slider()
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses wpsight_sylt_get_image_slider()
 *
 * @since 1.0.0
 */
 
function wpsight_sylt_image_slider( $post_id = '', $args = array() ) {
	echo wpsight_sylt_get_image_slider( $post_id, $args );
}

/**
 * wpsight_sylt_get_image_slider()
 *
 * Create thumbnail gallery.
 *
 * @param integer|array $post_id Post ID or array of attachment IDs
 * @param array $args Optional gallery arguments
 *
 * @uses get_post_meta()
 * @uses get_post()
 * @uses wp_get_attachment_image_src()
 * @uses wpsight_sylt_image_slider_root()
 * @uses wpsight_sylt_image_slider_js()
 *
 * @return string HTML markup of thumbnail gallery with lightbox
 *
 * @since 1.0.0
 */

function wpsight_sylt_get_image_slider( $post_id = '', $args = array() ) {
	
	// If we have a post ID, get _gallery post meta
	
	if( ! is_array( $post_id ) ) {
	
		// Set default post ID
    	
    	if( ! $post_id )
			$post_id = get_the_ID();
		
		// Get gallery imgages		
		$images = array_keys( get_post_meta( absint( $post_id ), '_gallery', true ) );
		
		// Set gallery ID
		$slider_id = 'wpsight-image-slider-' . absint( $post_id );
			
	// Else set array of image attachment IDs
	
	} else {
		
		$images = array_map( 'absint', $post_id );
		
		// Set gallery ID
		$slider_id = 'wpsight-image-slider-' . implode( '-', $images );
		
	}
	
	// If there are images, create the gallery
	
	if( $images ) {
		
		// Set some defaults
		
		$defaults = array(
			'class_slider'    		=> $slider_id . ' wpsight-image-slider',
			'class_item'	   		=> 'wpsight-image-slider-item image fit',
			'thumbs_size' 	   		=> 'wpsight-half',
			'thumbs_caption'    	=> false,
			'thumbs_link'	   		=> true,			
			'slider_items'			=> 2,
			'slider_slide_by'		=> 2,
			'slider_margin'			=> 20,
			'slider_stage_padding'	=> 0,
			'slider_loop'			=> true,
			'slider_nav'			=> true,
			'slider_nav_text'		=> '["&lsaquo;","&rsaquo;"]',
			'slider_dots'			=> true,
			'slider_dots_each'		=> false,
			'slider_autoplay'		=> false,
			'slider_autoplay_time'	=> 5000,
			'slider_overlay'		=> '',			
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
		$args = apply_filters( 'wpsight_sylt_get_image_slider_args', wp_parse_args( $args, $defaults ), $post_id, $args );
		
		// Sanitize gallery class
		
		$class_slider = ! is_array( $args['class_slider'] ) ? explode( ' ', $args['class_slider'] ) : $args['class_slider'];		
		$class_slider = array_map( 'sanitize_html_class', $class_slider );
		$class_slider = implode( ' ', $class_slider );
		
		// Sanitize item class
		
		$class_item = ! is_array( $args['class_item'] ) ? explode( ' ', $args['class_item'] ) : $args['class_item'];		
		$class_item = array_map( 'sanitize_html_class', $class_item );
		$class_item = implode( ' ', $class_item );
		
		// Check thumbnail size
		$args['thumbs_size'] = in_array( $args['thumbs_size'], get_intermediate_image_sizes() ) || 'full' == $args['thumbs_size'] ? $args['thumbs_size'] : 'post-thumbnail';
		
		// Check thumbnail caption
		$args['thumbs_caption'] = $args['thumbs_caption'] == true ? true : false;
		
		// Check thumbnail links
		$args['thumbs_link'] = $args['thumbs_link'] == true ? true : false;
		
		// Sanitize overlay
		$args['slider_overlay'] = is_array( $args['slider_overlay'] ) ? array_map( 'wp_kses_post', $args['slider_overlay'] ) : '';
		
		// Check lightbox size
		$args['lightbox_size'] = in_array( $args['lightbox_size'], get_intermediate_image_sizes() ) ? $args['lightbox_size'] : 'full';
		
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
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
    
			    $('.wpsight-image-slider').owlCarousel({
					responsiveClass:	true,
				    responsive:{
    				    0:{
    				        items:		1,
    				        slideBy:	1,
    				        dots: 		false
    				    },
    				    981:{
    				        items: 		<?php echo absint( $args['slider_items'] ); ?>,
							slideBy:	<?php echo absint( $args['slider_slide_by'] ); ?>
    				    }
    				},
					margin: 			<?php echo absint( $args['slider_margin'] ); ?>,
				    stagePadding: 		<?php echo absint( $args['slider_stage_padding'] ); ?>,
				    loop: 				<?php echo $args['slider_loop'] == true ? 'true' : 'false'; ?>,
					nav: 				<?php echo $args['slider_nav'] == true ? 'true' : 'false'; ?>,
					navText:			<?php echo strip_tags( $args['slider_nav_text'] ); ?>,
					dots:				<?php echo $args['slider_dots'] == true ? 'true' : 'false'; ?>,
					dotsEach:			<?php echo $args['slider_dots_each'] == true ? 'true' : 'false'; ?>,
					autoplay:			<?php echo $args['slider_autoplay'] == true ? 'true' : 'false'; ?>,
					autoplayTimeout:	<?php echo absint( $args['slider_autoplay_time'] ); ?>,
					autoplayHoverPause: true,
					navContainer:		'.wpsight-image-slider-arrows.clearfix',
					dotsContainer: 		'.wpsight-image-slider-dots',
					autoHeight:			false
			    });
			    
			});
		</script>
		
		<div class="<?php echo $class_slider; ?>" itemscope itemtype="http://schema.org/ImageGallery">
		
			<?php foreach( $images as $image ) : ?>
			
				<?php
					$attachment 	= get_post( absint( $image ) );
					$attachment_src = wp_get_attachment_image_src( $attachment->ID, $args['lightbox_size'] );
					$thumbnail_src  = wp_get_attachment_image_src( $attachment->ID, $args['thumbs_size'] );
				?>
				
				<?php if( $attachment !== NULL && $attachment->post_type == 'attachment' ) : ?>
					
					<div class="<?php echo $class_item; ?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
					
						<?php if( $args['thumbs_link'] === true ) : ?>
						<a href="<?php echo esc_url( $attachment_src[0] ); ?>" itemprop="contentUrl" data-size="<?php echo absint( $attachment_src[1] ); ?>x<?php echo absint( $attachment_src[2] ); ?>" data-counter="<?php echo absint( $i ); ?>">
						<?php endif; ?>
					
							<span class="image"><img src="<?php echo esc_url( $thumbnail_src[0] ); ?>" itemprop="thumbnail" alt="<?php echo esc_attr( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ); ?>" /></span>
							
							<?php if( $attachment->post_content && $args['lightbox_caption'] === true ) : ?>
							<meta itemprop="title" content="<?php echo esc_attr( $attachment->post_content ); ?>">
							<?php endif; ?>
					
							<meta itemprop="width" content="<?php echo absint( $thumbnail_src[1] ); ?>">
							<meta itemprop="height" content="<?php echo absint( $thumbnail_src[2] ); ?>">
						
						<?php if( $args['thumbs_link'] === true ) : ?>
						</a>
						<?php endif; ?>
						
						<?php if( $attachment->post_excerpt && $args['thumbs_caption'] === true ) : ?>
						<figcaption class="wpsight-image-slider-caption" itemprop="caption description"><?php echo $attachment->post_excerpt; ?></figcaption>
						<?php endif; ?>
						
						<?php if( isset( $args['slider_overlay'][ $attachment->ID ] ) ) : ?>
						<div class="image-slider-overlay">
							<?php echo $args['slider_overlay'][ $attachment->ID ]; ?>
						</div>
						<?php endif; ?>
					
					</div>
				
				<?php $i++; endif; ?>
						
			<?php endforeach; ?>
		
		</div>
		
		<?php if( $args['slider_nav'] || $args['slider_dots'] ) : ?>
		<div class="wpsight-image-slider-nav clearfix">
			<?php if( $args['slider_nav'] ) : ?>
			<div class="wpsight-image-slider-arrows clearfix"></div>
			<?php endif; ?>
			<?php if( $args['slider_dots'] ) : ?>
			<div class="wpsight-image-slider-dots"></div>
			<?php endif; ?>
		</div>
		<?php endif;
		
		// Include Photoswipe lightbox markup, if active
		
		if( apply_filters( 'wpsight_sylt_photoswipe', true ) == true ) {
			wpsight_sylt_image_slider_root( $args, $slider_id );
			wpsight_sylt_image_slider_js( $args, $slider_id );
		}
	
	}
	
}

/**
 * wpsight_sylt_image_slider_root()
 *
 * Echo wpsight_sylt_get_image_slider_root()
 *
 * @param array $args Array of arguments
 * @param string $slider_id Unique ID for Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_image_slider_root( $args = array(), $slider_id ) {
	echo wpsight_sylt_get_image_slider_root( $args, $slider_id );
}

/**
 * wpsight_sylt_get_image_slider_root()
 *
 * Create Photoswipte root element that
 * serves as HTML markup for the lightbox.
 *
 * @param array $args Array of arguments
 * @param string $slider_id Unique ID for Photoswipe root element
 *
 * @return string HTML markup of Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_get_image_slider_root( $args = array(), $slider_id ) {
	
	ob_start(); ?>

	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div id="<?php echo esc_attr( $slider_id ); ?>" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
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
	
	return apply_filters( 'wpsight_sylt_get_image_slider_root', ob_get_clean(), $args, $slider_id );

}

/**
 * wpsight_sylt_image_slider_js()
 *
 * Echo wpsight_sylt_get_image_slider_js()
 *
 * @param array $args Array of arguments
 * @param string $slider_id Unique ID for Photoswipe root element
 *
 * @since 1.0.0
 */

function wpsight_sylt_image_slider_js( $args = array(), $slider_id ) {
	echo wpsight_sylt_get_image_slider_js( $args, $slider_id );
}

/**
 * wpsight_sylt_get_image_slider_root()
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

function wpsight_sylt_get_image_slider_js( $args = array(), $slider_id ) {
	
	ob_start(); ?>
	
	<script type="text/javascript">
		(function($) {
		    var $pswp = $('#<?php echo esc_attr( $slider_id ); ?>')[0];
		    var image = [];
		
		    $('.wpsight-image-slider').each( function() {
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
		
		        $pic.on('click', '.wpsight-image-slider-item a', function(event) {
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
	
	return apply_filters( 'wpsight_sylt_get_image_slider_js', ob_get_clean(), $args, $slider_id );
	
}
