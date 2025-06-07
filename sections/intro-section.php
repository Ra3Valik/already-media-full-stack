<section class="section-intro">
    <div class="container intro-section__container">
        <div class="section-intro__content">
            <h1 class="section-intro__title">
                Explore a <span class="highlight">World</span> of <br />Cinematic Wonders
            </h1>

            <p class="section-intro__description">
                Our database not only includes blockbusters but also independent films, documentary features, and works from talented directors worldwide.
            </p>

            <div class="section-intro__buttons">
                <a href="#" class="btn btn--dark btn--capitalize">Register Now</a>
                <a href="#" class="btn btn--link">About us</a>
            </div>
        </div>
        <div class="section-intro__image-block">
            <div class="popcorn-image">
                <div class="circle-bg"></div>

                <img src="<?= get_theme_file_uri( 'assets/img/popcorn.png' ) ?>" alt="Popcorn" class="section-intro__image" />

                <div class="badge-wrapper badge-wrapper--top">
                    <div class="badge">MovieHub</div>
                </div>

                <div class="badge-wrapper badge-wrapper--right">
                    <div class="badge">4.8 <?= file_get_contents( get_theme_file_path( 'assets/img/akar-icons_star.svg' ) ) ?></div>
                </div>

                <div class="badge-wrapper badge-wrapper--bottom">
                    <div class="badge">18K</div>
                </div>
            </div>
        </div>
    </div>
</section>