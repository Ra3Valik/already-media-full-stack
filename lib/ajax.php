<?php

add_action( 'wp_ajax_load_more_movies', 'ajax_load_more_movies' );
add_action( 'wp_ajax_nopriv_load_more_movies', 'ajax_load_more_movies' );

function ajax_load_more_movies()
{
	check_ajax_referer( 'load_more_nonce' );
	$args = get_movie_query_args();
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'parts/movie-card' );
		}
		wp_reset_postdata();

		wp_send_json_success( [
			'html' => ob_get_clean(),
			'current_page' => $args['paged'],
		] );
	}

	wp_send_json_error( 'No more posts' );
	wp_die();
}