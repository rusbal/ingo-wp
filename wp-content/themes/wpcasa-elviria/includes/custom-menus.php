<?php
/**
 * Built custom menus
 *
 * @package WPSight Elviria
 *
 */
 
/**
 * wpsight_elviria_menus()
 *
 * Create array of default theme menus.
 *
 * @return array $menus
 *
 * @since 1.0.0
 */

function wpsight_elviria_menus() {

	$menus = array(
		'primary' => __( 'Primary Menu', 'wpcasa-elviria' ),
		'secondary' => __( 'Secondary Menu', 'wpcasa-elviria' )
	);
	
	return apply_filters( 'wpsight_elviria_menus', $menus );

}
 
/**
 * wpsight_elviria_register_menus()
 *
 * Register custom menus by looping
 * through theme menus.
 *
 * @uses wpsight_elviria_menus()
 * @uses register_nav_menu()
 *
 * @since 1.0.0
 */
 
add_action( 'after_setup_theme', 'wpsight_elviria_register_menus' );

function wpsight_elviria_register_menus() {

	foreach( wpsight_elviria_menus() as $menu => $label )	
		register_nav_menu( $menu, $label );

}

/**
 * wpsight_elviria_menu()
 *
 * Create menu with optional fallback
 * and custom arguments.
 *
 * @uses wp_parse_args()
 * @uses has_nav_menu()
 * @uses wp_nav_menu()
 *
 * @return mixed If echo is false, return menu or fallback
 *
 * @since 1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_elviria_menu' ) ) {

	function wpsight_elviria_menu( $menu_location = 'primary', $menu_args = array(), $menu_default = false ) {
		
		// Set up menu defaults
		
		$menu_defaults = array(
			'theme_location'  => $menu_location,
			'menu'            => '',
			'container'       => false,
			'container_class' => '',
			'container_id'    => '',
			'align'			  => 'left',
			'menu_before'	  => '<nav class="nav-' . esc_attr( $menu_location ) . '" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">',
			'menu_after'	  => '</nav>',
			'menu_class'      => 'wpsight-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => false,
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		);
		
		// Merge args and defaults
		$menu_args = apply_filters( 'wpsight_elviria_menu_args', wp_parse_args( $menu_args, $menu_defaults ), $menu_location, $menu_args, $menu_default );
		
		// Set align center if desired
		
		if( 'center' == $menu_args['align'] )
			$menu_args['menu_class'] = $menu_args['menu_class'] . ' wpsight-menu-center';
		
		// Set align right if desired
		
		if( 'right' == $menu_args['align'] )
			$menu_args['menu_class'] = $menu_args['menu_class'] . ' wpsight-menu-right';
		
		// If we have a menu, echo or return
		
		if( has_nav_menu( $menu_location ) ) {
			
			if( $menu_args['echo'] === true ) {
				
				echo $menu_args['menu_before'];
				wp_nav_menu( $menu_args );
				echo $menu_args['menu_after'];

			} else {

				return $menu_args['menu_before'] . wp_nav_menu( $menu_args ) . $menu_args['menu_after'];

			}

		}
		
		// If no menu but default desired, echo or return fallback
		
		if( ! has_nav_menu( $menu_location ) && $menu_default === true ) {
		
			$menu_fallback = '<ul class="wpsight-menu">
				<li class="menu-item">
					<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . __( 'Create a custom menu &rarr;', 'wpcasa-elviria' ) . '</a>
				</li>
			</ul>';
			
			if( $menu_args['echo'] === true ) {
				
				echo $menu_args['menu_before'];
				echo $menu_fallback;
				echo $menu_args['menu_after'];

			} else {

				return $menu_args['menu_before'] . $menu_fallback . $menu_args['menu_after'];

			}
		
		}
			
	}

}

/**
 * wpsight_elviria_menu_first_last()
 *
 * Add .first-menu-item and .last-menu-item
 * classes for better styling.
 *
 * @uses preg_replace()
 * @uses strlen()
 * @uses strripos()
 * @uses substr_replace()
 *
 * @return string $menu HTML output of the given menu
 *
 * @since 1.0.0
 */

add_filter( 'wp_nav_menu', 'wpsight_elviria_menu_first_last' );

function wpsight_elviria_menu_first_last( $menu ) {

	$menu = preg_replace( '/class="menu-item/', 'class="first-menu-item menu-item', $menu, 1 );
	$menu = substr_replace( $menu, 'class="last-menu-item menu-item', strripos( $menu, 'class="menu-item' ), strlen( 'class="menu-item' ) );

	return $menu;

}
