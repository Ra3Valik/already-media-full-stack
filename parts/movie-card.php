<?php
$rating = get_field( 'rating' );
$year = get_field( 'year' );
$poster = get_field( 'poster' );
$genres = get_the_terms( get_the_ID(), 'genre' );
?>

<div class="movie-card">
	<?php if ( $rating ): ?>
        <div class="movie-card__rating"><?= esc_html( $rating ); ?> <span class="star">★</span></div>
	<?php endif; ?>

	<?php if ( $poster ): ?>
        <img src="<?= esc_url( $poster['url'] ); ?>" alt="<?= $poster['title']; ?>"
             class="movie-card__image"/>
	<?php endif; ?>

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

    <a href="<?php the_permalink(); ?>" class="movie-card__button">Read more</a>
</div>
