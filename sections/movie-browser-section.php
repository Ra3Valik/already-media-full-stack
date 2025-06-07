<?php
/*------------------------------------------------------------------------------------*\
\|/                                                                                  \|/
 |                                     Filters                                        |
/|\                                                                                  /|\
\*------------------------------------------------------------------------------------*/
$genres = get_terms( ['taxonomy' => 'genre', 'hide_empty' => false] );

$min_year = get_min_release_year();

$meta_query = [];
if ( !empty( $_GET['year_from'] ) || !empty( $_GET['year_to'] ) ) {
	$range = [
		'key' => 'year',
		'type' => 'NUMERIC',
	];
	if ( !empty( $_GET['year_from'] ) ) {
		$range['value'][] = (int) $_GET['year_from'];
		$range['compare'] = '>=';
	}
	if ( !empty( $_GET['year_to'] ) ) {
		$range['value'][] = (int) $_GET['year_to'];
		$range['compare'] = !empty( $range['compare'] ) ? 'BETWEEN' : '<=';
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

if ( !empty( $_GET['genre'] ) ) {
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
if ( !empty( $_GET['orderby'] ) ) {
	$args['meta_key'] = $_GET['orderby'];
	$args['orderby'] = 'meta_value_num';
	$args['order'] = 'DESC';
}

$query = new WP_Query( $args );
?>
<section class="section-movie-browser">
    <div class="section-movie-browser__container">
        <h2 class="section-movie-browser__heading">
            Discover a&nbsp;<span class="highlight">Universe</span>&nbsp;of Cinematic Marvels
        </h2>

        <div class="section-movie-browser__layout">
            <div class="section-movie-browser__movies-filter">
                <form method="get" class="section-movie-browser__filter-form">
                    <div class="filter-search">
						<?= file_get_contents( get_theme_file_path( 'assets/img/loupe.svg' ) ) ?>

                        <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Search by name"
                               class="search-box__input">
                    </div>

                    <h3 class="filter-title">FILTER:</h3>

                    <div class="filter-fields">
                        <div class="filter-row">
                            <label>Genre:</label>
                            <select name="genre">
                                <option value="">All</option>
                                <?php foreach ( $genres as $genre ) : ?>
                                    <option value="<?= $genre->slug ?>" <?php selected( $_GET['genre'] ?? '', $genre->slug ) ?>><?= $genre->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-row">
                            <label>Date from:</label>
                                <select name="year_from">
                                    <option value="" <?= !empty( $_GET['year_from'] ) ? '' : 'selected' ?>>Any</option>

                                    <?php for ( $year = $min_year; $year <= date( 'Y' ); $year++ ): ?>
                                        <option value="<?= $year; ?>" <?php selected( $_GET['year_from'] ?? '', $year ); ?>><?= $year; ?></option>
                                    <?php endfor; ?>
                                </select>

                            <label>to:</label>
                            <select name="year_to">
                                <option value="" <?php selected( $_GET['year_to'] ?? '', '' ) ?>>Any</option>


                                <?php for ( $year = $min_year; $year <= date( 'Y' ); $year++ ): ?>
                                    <option value="<?= $year; ?>" <?php selected( $_GET['year_to'] ?? '', $year ); ?>><?= $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn--dark btn--capitalize filter-apply">Apply</button>

                </form>
            </div>

            <div class="section-movie-browser__movies-list">
                <div class="sort">
                    <span>Sort by:</span>
                    <select name="orderby">
                        <option value="rating" <?php selected( $_GET['orderby'] ?? '', 'rating' ); ?>>Rating</option>
                        <option value="release_year" <?php selected( $_GET['orderby'] ?? '', 'release_year' ); ?>>Year
                        </option>
                    </select>
                </div>

                <div class="grid">
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

                <div class="load-more-wrapper">
                    <button class="btn btn--dark load-more">Load more</button>
                </div>
            </div>
        </div>
    </div>
</section>

