<?php
/**
 * Astra Child Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-child
 */

add_action( 'wp_enqueue_scripts', 'astra_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function astra_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'astra-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'astra-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'astra-style' ]
	);
}
