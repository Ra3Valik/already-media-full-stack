<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php wp_head() ?>
</head>

<body <?php body_class() ?>>
<?php wp_body_open() ?>

<div class="header-bar">
    <div class="navbar">
        <div class="logo">
            <a href="<?= home_url() ?>">
				<?= file_get_contents( get_theme_file_path( 'assets/img/logo.svg' ) ) ?> MovieHub
            </a>
        </div>

        <nav class="desktop-menu-wrap">
			<?php
			if ( has_nav_menu( 'main-menu' ) ) {
				wp_nav_menu( [
					'theme_location' => 'main-menu',
					'container' => 'nav',
					'container_class' => 'desktop-menu',
					'depth' => 3,
				] );
			} else {
				echo 'No menu provided';
			}
			?>
        </nav>
    </div>
</div>
