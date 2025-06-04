<?php

function r3_enqueue_assets()
{
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/dist/style.css' );
	wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/dist/main.js', [], null, true );
}

add_action( 'wp_enqueue_scripts', 'r3_enqueue_assets' );