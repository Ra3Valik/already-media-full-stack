<?php
/**
 * Set Test Data
 *
 * Usage:
 *
 * Run from command line:
 *   php wp-content/themes/already-media-full-stack/test-data/set-test-data.php
 */

require_once( 'wp-load.php' );

class TestDataImporter
{
	private $json_file;
	private $images_dir;

	public function __construct()
	{
		$this->json_file = get_theme_file_path( 'test-data/movies_data.json' );
		$this->images_dir = get_theme_file_path( 'test-data/images/' );
	}

	public function run()
	{
		$this->log( "Starting movie import..." );

		// Validate files
		if ( !file_exists( $this->json_file ) ) {
			$this->error( "JSON file not found: {$this->json_file}" );
			return;
		}

		$data = json_decode( file_get_contents( $this->json_file ), true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$this->error( 'JSON parse error: ' . json_last_error_msg() );
			return;
		}

		if ( empty( $data['movies'] ) ) {
			$this->error( 'No movie data found for import' );
			return;
		}

		if ( !file_exists( $this->images_dir ) ) {
			$this->warning( "Images directory not found: {$this->images_dir}" );
		}

		// Process movies
		foreach ( $data['movies'] as $movie ) {
			$this->process_movie( $movie );
		}

		// Process menu
		$this->create_main_menu_if_not_exists();

		$this->success( 'Import completed!' );
	}

	private function process_movie( $movie )
	{
		$this->log( "Processing: {$movie['title']}" );

		$movie_exists_query = new WP_Query( [
			'post_type' => 'movie',
			'title' => $movie['title'],
			'posts_per_page' => 1,
		] );
		if ( $movie_exists_query->have_posts() ) {
			$this->warning( "Movie with title = \"{$movie['title']}\" already exists" );
			return;
		}

		$post_id = wp_insert_post( [
			'post_title' => $movie['title'],
			'post_content' => $movie['description'],
			'post_type' => 'movie',
			'post_status' => 'publish'
		] );

		if ( is_wp_error( $post_id ) ) {
			$this->warning( "Failed to add movie {$movie['title']}: " . $post_id->get_error_message() );
			return;
		}

		$poster_id = null;
		if ( !empty( $movie['poster'] ) ) {
			$image_path = $this->images_dir . $movie['poster'];

			if ( file_exists( $image_path ) ) {
				$this->log( "- Uploading poster: {$movie['poster']}" );
				$poster_id = $this->upload_image_to_media_library( $image_path, $post_id );
			} else {
				$this->warning( "- Poster image not found: {$movie['poster']}" );
			}
		}

		update_field( 'rating', $movie['rating'], $post_id );
		update_field( 'poster', $poster_id, $post_id );
		update_field( 'release_date', $movie['release_date'], $post_id );
		update_field( 'release_year', $movie['release_year'], $post_id );
		update_field( 'director', $movie['director'], $post_id );

		// Set genres taxonomy
		if ( !empty( $movie['genres'] ) ) {
			wp_set_object_terms( $post_id, $movie['genres'], 'genre' );
		}

		$this->success( "Added movie: {$movie['title']} (ID: $post_id)" );
	}

	private function upload_image_to_media_library( $image_path, $post_id )
	{
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$file_name = basename( $image_path );
		$tmp_file = wp_tempnam( $file_name );

		if ( !copy( $image_path, $tmp_file ) ) {
			$this->warning( "Failed to copy image to temp directory: {$file_name}" );
			return null;
		}

		$file_array = [
			'name' => $file_name,
			'tmp_name' => $tmp_file,
		];

		$attachment_id = media_handle_sideload( $file_array, $post_id );

		if ( is_wp_error( $attachment_id ) ) {
			$this->warning( "Failed to upload image {$file_name}: " . $attachment_id->get_error_message() );
			return null;
		}

		return $attachment_id;
	}

	private function create_main_menu_if_not_exists()
	{
		$this->log( 'Starting menu creating...' );

		if ( has_nav_menu( 'main-menu' ) ) {
			$this->warning( 'Main menu already registered' );
			return;
		}
		$menu_name = 'Main Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( !$menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, [
				'menu-item-title' => 'Main page',
				'menu-item-url' => home_url( '/' ),
				'menu-item-status' => 'publish',
			] );

			wp_update_nav_menu_item( $menu_id, 0, [
				'menu-item-title' => 'About',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			] );

			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['main-menu'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$this->success( 'Menu created.' );
		} else {
			$this->warning( 'Menu with name Main Menu already exists' );
		}
	}


	/*------------------------------------------------------------------------------------*\
	\|/                                                                                  \|/
	 |                                 Logging methods                                    |
	/|\                                                                                  /|\
	\*------------------------------------------------------------------------------------*/
	private function log( $message )
	{
		if ( php_sapi_name() === 'cli' ) {
			echo "[INFO] $message\n";
		} else {
			echo "<p>[INFO] $message</p>";
		}
	}

	private function success( $message )
	{
		if ( php_sapi_name() === 'cli' ) {
			echo "\033[32m[SUCCESS] $message\033[0m\n";
		} else {
			echo "<p style='color:green;'>[SUCCESS] $message</p>";
		}
	}

	private function warning( $message )
	{
		if ( php_sapi_name() === 'cli' ) {
			echo "\033[33m[WARNING] $message\033[0m\n";
		} else {
			echo "<p style='color:orange;'>[WARNING] $message</p>";
		}
	}

	private function error( $message )
	{
		if ( php_sapi_name() === 'cli' ) {
			echo "\033[31m[ERROR] $message\033[0m\n";
		} else {
			echo "<p style='color:red;'>[ERROR] $message</p>";
		}
	}
}

$importer = new TestDataImporter();
$importer->run();
