<?php
/**
 * Template Name: Single Movie
 * Template Post Type: movie
 */
get_header();
?>

<main id="primary" class="site-main movie-single">
    <section id="post-<?php the_ID(); ?>" class="section-single_movie">
        <div class="section-single_movie__container">
            <div class="section-single_movie__poster">
                <?php
                $poster = get_field( 'poster' );
                if ( $poster ) : ?>
                    <img src="<?= esc_url( $poster['url'] ); ?>" alt="<?= $poster['title']; ?>"
                         class="section-single_movie__poster-image"/>
                <?php else : ?>
                    <div class="movie-poster__poster-empty">No poster available</div>
                <?php endif; ?>
            </div>

            <div class="section-single_movie__movie-content">
                <header class="movie-header">
                    <h1 class="movie-header__title"><?php the_title(); ?></h1>

                    <div class="movie-rating">
                        <span class="movie-rating__value"><?php echo number_format( (float) get_field( 'rating' ), 1 ); ?></span>/10
                        <span class="movie-rating__stars"><?php echo str_repeat( '★', round( get_field( 'rating' ) ) ); ?></span>
                    </div>
                </header>

                <div class="movie-meta">
                    <?php if ( $release_date = get_field( 'release_date' ) ) : ?>
                        <div class="movie-meta__item">
                            <span class="movie-meta__label">Release Date:</span>
                            <span class="movie-meta__value"><?php echo date( 'F j, Y', strtotime( $release_date ) ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $release_year = get_field( 'release_year' ) ) : ?>
                        <div class="movie-meta__item">
                            <span class="movie-meta__label">Year:</span>
                            <span class="movie-meta__value"><?php echo esc_html( $release_year ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $genres = get_the_terms( get_the_ID(), 'genre' ) ) : ?>
                        <div class="movie-meta__item">
                            <span class="movie-meta__label">Genres:</span>
                            <span class="movie-meta__value">
                            <?php
                            $genre_links = [];
                            foreach ( $genres as $genre ) {
                                $genre_links[] = '<a href="#" class="movie-meta__link">' . $genre->name . '</a>';
                            }
                            echo implode( ', ', $genre_links );
                            ?>
                        </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="movie-description">
                    <?php the_content(); ?>
                </div>

                <?php
                $director = get_field( 'director' );
                if ( $director ) : ?>
                    <div class="movie-director"><strong>Director:</strong> <?= esc_html( $director ) ?></div>
                <?php endif; ?>
            </div>

            <nav class="movie-navigation">
                <div class="movie-navigation__prev"><?php previous_post_link( '%link', '← Previous Movie', true, '', 'genre' ); ?></div>
                <div class="movie-navigation__next"><?php next_post_link( '%link', 'Next Movie →', true, '', 'genre' ); ?></div>
            </nav>
        </div>
    </section>
</main>

<?php
get_footer();