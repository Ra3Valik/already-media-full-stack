<?php
$rating = get_field( 'rating' );
$year = get_field( 'release_year' );
$poster = get_field( 'poster' );
$genres = get_the_terms( get_the_ID(), 'genre' );
if ( empty( $poster ) ) {
    $poster = [
        'url' => '',
        'title' => 'No Image'
    ];
}
?>

<article class="movie-card">
    <div class="movie-card__image-wrapper">
        <?php if ( $rating ): ?>
            <div class="movie-card__rating">
                <div class="badge">
                    <?= esc_html( number_format( $rating, 1 ) ); ?> <span class="star">★</span>
                </div>
            </div>
        <?php endif; ?>

        <img src="<?= esc_url( $poster['url'] ); ?>" alt="<?= $poster['title']; ?>"
             class="movie-card__image"/>
    </div>

    <div class="movie-card__content">
        <div class="movie-card__title"><?php the_title(); ?></div>

		<?php if ( $genres && !is_wp_error( $genres ) ): ?>
            <div class="movie-card__genres">
				<?php foreach ( $genres as $genre ): ?>
                    <span class="genre"><?= esc_html( $genre->name ); ?></span>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>

		<?php if ( $year ): ?>
            <div class="movie-card__year"><?= esc_html( $year ); ?></div>
		<?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="btn movie-card__button">Read more</a>
    </div>
</article>
