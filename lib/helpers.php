<?php

/**
 * Get min release year in existing movies
 *
 * @return array|object|stdClass|null
 */
function get_min_release_year()
{
	global $wpdb;

	$query = $wpdb->get_var("
	  SELECT MIN(CAST(meta_value AS UNSIGNED))
	  FROM {$wpdb->postmeta} pm
	  INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	  WHERE pm.meta_key = 'year'
		AND p.post_type = 'movie'
		AND p.post_status = 'publish'
		AND pm.meta_value != ''
	");
	return $query['min'] ?? 2000;
}