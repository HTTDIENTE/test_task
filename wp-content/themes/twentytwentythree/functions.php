<?php
add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_script(
			'jquery-core',
			get_stylesheet_directory_uri() . '/jquery.js',
			array(
				'jquery'
			),
		);

		wp_enqueue_script(
			'main',
			get_stylesheet_directory_uri() . '/index.js',
		);
	}
);

