<?php
$genres = get_terms( ['taxonomy' => 'genre', 'hide_empty' => false] );

$min_year = get_min_release_year();
$args = get_movie_query_args();

$query = new WP_Query($args);
$page = !empty( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;
$total_pages = $query->max_num_pages;
?>
<section class="section-movie-browser">
    <div class="section-movie-browser__container">
        <h2 class="section-movie-browser__heading">
            Discover a&nbsp;<span class="highlight">Universe</span>&nbsp;of Cinematic Marvels
        </h2>

        <div class="section-movie-browser__layout">
            <div class="section-movie-browser__movies-filter">
                <form id="movies-filter-form" method="get" class="section-movie-browser__filter-form">
                    <div class="filter-search">
						<?= file_get_contents( get_theme_file_path( 'assets/img/loupe.svg' ) ) ?>

                        <input type="text" name="s" value="<?php echo get_search_query(); ?>"
                               placeholder="Search by name"
                               class="search-box__input">
                    </div>

                    <h3 class="filter-title">FILTER:</h3>

                    <div class="filter-fields">
                        <div class="filter-row">
                            <label>Genre:</label>
                            <select name="genre">
                                <option value="" <?php selected( $_GET['genre'] ?? '', '' ) ?>>All</option>
								<?php foreach ( $genres as $genre ) : ?>
                                    <option value="<?= $genre->slug ?>" <?php selected( $_GET['genre'] ?? '', $genre->slug ) ?>><?= $genre->name ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-row">
                            <label>Date from:</label>
                            <select name="year_from">
                                <option value="" <?php selected( $_GET['year_from'] ?? '', '' ) ?>>Any</option>

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

                        <input type="hidden" name="orderby" id="orderby-hidden" value="<?= $_GET['orderby'] ?? '' ?>">
                    </div>

                    <button class="btn btn--dark btn--capitalize filter-apply">Apply</button>

                </form>
            </div>

            <div class="section-movie-browser__movies-list">
                <div class="sort">
                    <span>Sort by:</span>
                    <select name="orderby">
                        <option value="" <?php selected( $_GET['orderby'] ?? '', '' ) ?>>Default</option>
                        <option value="rating" <?php selected( $_GET['orderby'] ?? '', 'rating' ); ?>>Rating</option>
                        <option value="release_year" <?php selected( $_GET['orderby'] ?? '', 'release_year' ); ?>>Year</option>
                    </select>
                </div>

                <div id="movies-grid" class="grid">
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

                <?php if ( $total_pages > $page ) : ?>
                    <div class="load-more-wrapper">
                        <button
                                id="load-more-movies"
                                class="btn btn--dark load-more"
                                data-page="<?= $page ?>"
                                data-max_pages="<?= $total_pages; ?>"
                        >
                            Load more
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

