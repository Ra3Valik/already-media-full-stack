<?php
/*------------------------------------------------------------------------------------*\
\|/                                                                                  \|/
 |                                     Filters                                        |
/|\                                                                                  /|\
\*------------------------------------------------------------------------------------*/
$genres = get_terms( ['taxonomy' => 'genre', 'hide_empty' => false] );

$min_year = get_min_release_year();

$meta_query = [];
if ( isset( $_GET['year_from'] ) || isset( $_GET['year_to'] ) ) {
	$range = [
		'key' => 'year',
		'type' => 'NUMERIC',
	];
	if ( isset( $_GET['year_from'] ) ) {
		$range['value'][] = (int) $_GET['year_from'];
		$range['compare'] = '>=';
	}
	if ( isset( $_GET['year_to'] ) ) {
		$range['value'][] = (int) $_GET['year_to'];
		$range['compare'] = isset( $range['compare'] ) ? 'BETWEEN' : '<=';
	}
	$meta_query[] = $range;
}

$posts_per_page = get_option( 'posts_per_page' );
$args = [
	'post_type' => 'movie',
	'posts_per_page' => $posts_per_page,
	'post_status' => 'publish',
	's' => $_GET['s'] ?? '',
	'tax_query' => [],
	'meta_query' => $meta_query,
];

if ( isset( $_GET['genre'] ) ) {
	$args['tax_query'][] = [
		'taxonomy' => 'genre',
		'field' => 'slug',
		'terms' => sanitize_text_field( $_GET['genre'] ),
	];
}

/*------------------------------------------------------------------------------------*\
\|/                                                                                  \|/
 |                                       Sort                                         |
/|\                                                                                  /|\
\*------------------------------------------------------------------------------------*/
if ( isset( $_GET['orderby'] ) ) {
	$args['meta_key'] = $_GET['orderby'];
	$args['orderby'] = 'meta_value_num';
	$args['order'] = 'DESC';
}

$query = new WP_Query( $args );
?>
<section class="movie-browser">
    <h2 class="movie-browser__heading">
        Discover a <span class="highlight">Universe</span> of Cinematic Marvels
    </h2>

    <div class="movie-browser__controls">
        <form method="get" class="movie-browser__form">
            <div class="search-box">
                <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Search by name"
                       class="search-box__input">
            </div>

            <div class="filters">
                <p class="filters__label">FILTER:</p>

                <div class="filters__block">
                    <label>Genre:
                        <select name="genre">
                            <option value="">All</option>
							<?php foreach ( $genres as $genre ) : ?>
                                <option value="<?= $genre->slug ?>" <?php selected( $_GET['genre'] ?? '', $genre->slug ) ?>><?= $genre->name ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>

                    <label>Date from:
                        <select name="year_from">
                            <option value="" <?= isset( $_GET['year_from'] ) ? '' : 'selected' ?>>Any</option>

							<?php for ( $year = $min_year; $year <= date( 'Y' ); $year++ ): ?>
                                <option value="<?= $year; ?>" <?php selected( $_GET['year_from'] ?? '', $year ); ?>><?= $year; ?></option>
							<?php endfor; ?>
                        </select>
                    </label>

                    <label>to:
                        <select name="year_to">
                            <option value="" <?php selected( $_GET['year_to'] ?? '', '' ) ?>>Any</option>


							<?php for ( $year = $min_year; $year <= date( 'Y' ); $year++ ): ?>
                                <option value="<?= $year; ?>" <?php selected( $_GET['year_to'] ?? '', $year ); ?>><?= $year; ?></option>
							<?php endfor; ?>
                        </select>
                        </select>
                    </label>
                </div>

                <button type="submit" class="filters__apply">Apply</button>
            </div>

            <div class="sort">
                <span>Sort by:</span>
                <select name="orderby">
                    <option value="rating" <?php selected( $_GET['orderby'] ?? '', 'rating' ); ?>>Rating</option>
                    <option value="release_year" <?php selected( $_GET['orderby'] ?? '', 'release_year' ); ?>>Year
                    </option>
                </select>
            </div>
        </form>
    </div>

    <div class="movie-browser__grid">
		<?php
		if ( $query->have_posts() ):
			while ( $query->have_posts() ):
				$query->the_post();
				get_template_part( 'parts/movie-card' );
			endwhile;
			wp_reset_postdata();
		else:
			echo '<p>No movies found.</p>';
		endif;
		?>
    </div>

    <div class="movie-browser__load">
        <button class="load-more">Load more</button>
    </div>
</section>

