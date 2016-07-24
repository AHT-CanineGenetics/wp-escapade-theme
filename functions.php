<?php

/**
 * Register custom Custom Navigation Menus.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_nav_menus
 */
function escapade_register_menus() {

	register_nav_menus(
		array(
			'site-info' => esc_html__( 'Site Info', 'escapade' ),
			'footer'	=> esc_html__( 'Footer', 'escapade' )
		)
	);

}
add_action( 'after_setup_theme', 'escapade_register_menus' );

/**
 * Add image size for hero image
 *
 * @link https://codex.wordpress.org/Function_Reference/add_image_size
 */
function escapade_add_image_size() {

	add_image_size( 'hero', 2400, 1320, array( 'center', 'center' ) );

}
add_action( 'after_setup_theme', 'escapade_add_image_size' );

/**
 * Remove primer navigation and add escapade navigation
 */
function escapade_navigation() {
	wp_dequeue_script( 'primer-navigation' );
	wp_enqueue_script( 'escapade-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array( 'jquery' ), '20120206', true );
}
add_action( 'wp_print_scripts', 'escapade_navigation', 100 );

/**
 * Add mobile menu to header
 *
 * @link https://codex.wordpress.org/Function_Reference/get_template_part
 */
function escapade_add_mobile_menu() {
	get_template_part( 'templates/parts/mobile-menu' );
}
add_action( 'primer_header', 'escapade_add_mobile_menu', 0 );

/**
 * Update custom header arguments
 *
 * @param $args
 * @return mixed
 */
function primer_update_custom_header_args( $args ) {
	$args['width'] = 2400;
	$args['height'] = 1320;

	return $args;
}
add_filter( 'primer_custom_header_args', 'primer_update_custom_header_args' );

/**
 * Display hero in the header
 *
 * @action primer_after_header
 */
function escapade_add_hero(){
	if( is_front_page() && is_active_sidebar( 'hero' ) ) {
		get_template_part( 'templates/parts/hero' );
	}
}
add_action( 'primer_header', 'escapade_add_hero', 25 );

/**
 * Register hero sidebar
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function escapade_register_hero_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Hero', 'escapade' ),
		'id'            => 'hero',
		'description'   => __( 'The hero appears in the hero widget area on the front page', 'escapade' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'escapade_register_hero_sidebar' );

/**
 * Get header image with image size
 *
 * @return false|string
 */
function escapade_get_header_image() {
	$image_size = (int) get_theme_mod( 'full_width' ) === 1 ? 'hero-2x' : 'hero';
	$custom_header = get_custom_header();

	if ( ! empty( $custom_header->attachment_id ) ) {
		$image = wp_get_attachment_image_url( $custom_header->attachment_id, $image_size );
		if ( getimagesize( $image ) ) {
			return $image;
		}
	}
	$header_image = get_header_image();
	return $header_image;
}

/**
 * Remove sidebar
 *
 */
function escapade_remove_widgets() {

	unregister_sidebar( 'sidebar-1' );
	unregister_sidebar( 'sidebar-2' );

}

add_action( 'widgets_init', 'escapade_remove_widgets', 11 );

function escapade_update_fonts() {
	return array(
		'Oswald',
		'Droid Serif',
		'Lato',
		'Merriweather'
	);
}
add_filter( 'primer_fonts', 'escapade_update_fonts' );

/**
 * Update font types specific to scribbles.
 *
 * @return array
 */
function scribbles_update_font_types() {
    return array(
        array(
            'name'    => 'primary_font',
            'label'   => __( 'Primary Font', 'primer' ),
            'default' => 'Oswald',
            'css'     => array(

            ),
            'weight'   => array(
                300
            )
        ),
        array(
            'name'    => 'secondary_font',
            'label'   => __( 'Secondary Font', 'primer' ),
            'default' => 'Droid Serif',
            'css'     => array(

            ),
            'weight'   => array(
                400
            )
        ),
    );
}
add_action( 'primer_font_types', 'scribbles_update_font_types' );

/**
 * Add Social links to primary navigation area.
 *
 * @action primer_after_header
 */
function escapade_add_social_to_header(){

	if ( has_nav_menu( 'social' ) ) :

		get_template_part( 'templates/parts/social-menu' );

	endif;

}
add_action( 'primer_after_header', 'escapade_add_social_to_header', 30 );

/**
 * Remove customizer features added by the parent theme that are not applicable to this theme
 * 
 * @action after_setup_theme
 */
function escapade_remove_customizer_features($wp_customize){

	$wp_customize->remove_section('layout');

}
add_action( 'customize_register', 'escapade_remove_customizer_features', 30 );