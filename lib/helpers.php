<?php

/**
 * Get min release year in existing movies
 *
 * @return array|object|stdClass|null
 */
function get_min_release_year()
{
	global $wpdb;

	$query = $wpdb->get_var( "
	  SELECT MIN(CAST(meta_value AS UNSIGNED))
	  FROM {$wpdb->postmeta} pm
	  INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	  WHERE pm.meta_key = 'release_year'
		AND p.post_type = 'movie'
		AND p.post_status = 'publish'
		AND pm.meta_value != ''
	" );

	return $query ?? 2000;
}

/**
 * Get filters parameters
 */
function get_movie_filter_params()
{
	$request = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

	return [
		'genre' => sanitize_text_field( $request['genre'] ?? '' ),
		'year_from' => isset( $request['year_from'] ) ? (int) $request['year_from'] : null,
		'year_to' => isset( $request['year_to'] ) ? (int) $request['year_to'] : null,
		'orderby' => sanitize_text_field( $request['orderby'] ?? '' ),
		's' => sanitize_text_field( $request['s'] ?? '' ),
		'page' => isset( $request['page'] ) ? (int) $request['page'] : 1,
	];
}

/**
 * Movie arguments for WP Query
 */
function get_movie_query_args()
{
	$params = get_movie_filter_params();

	$meta_query = [];
	if ( !empty( $params['year_from'] ) || !empty( $params['year_to'] ) ) {
		$range = [
			'key' => 'release_year',
			'type' => 'NUMERIC',
		];

		if ( $params['year_from'] ) {
			$range['value'][] = $params['year_from'];
			$range['compare'] = '>=';
		}

		if ( $params['year_to'] ) {
			$range['value'][] = $params['year_to'];
			$range['compare'] = isset( $range['compare'] ) ? 'BETWEEN' : '<=';
		}

		$meta_query[] = $range;
	}

	$args = [
		'post_type' => 'movie',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'paged' => $params['page'],
		'post_status' => 'publish',
		's' => $params['s'],
		'tax_query' => [],
		'meta_query' => $meta_query,
	];

	if ( !empty( $params['genre'] ) ) {
		$args['tax_query'][] = [
			'taxonomy' => 'genre',
			'field' => 'slug',
			'terms' => $params['genre'],
		];
	}

	if ( !empty( $params['orderby'] ) ) {
		$args['meta_key'] = $params['orderby'];
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'DESC';
	}

	return $args;
}