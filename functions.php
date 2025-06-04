<?php

/**
 * Load styles and scripts
 *
 * @return void
 */
function r3_enqueue_assets()
{
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/dist/style.css' );
	wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/dist/main.js', [], null, true );
	wp_enqueue_script( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', [], null, true );
}

add_action( 'wp_enqueue_scripts', 'r3_enqueue_assets' );

/**
 * Theme dependencies
 *
 * @return void
 */
function load_theme_dependencies()
{
	add_theme_support( 'title-tag' );    # Add <title> tag for each page
	require_once( 'config/constants.php' );    	# Theme constants
	require_once( 'lib/menus.php' );            # Menu
	require_once( 'lib/helpers.php' ); 			# Helper functions
}

add_action( 'after_setup_theme', 'load_theme_dependencies' );