<?php
/*
Plugin Name: Test Task
Plugin URI: -
Description: -
Version: 1.0
Author: httdiente
Author URI: -
*/

$post_type = 'real_estate_objects';

add_shortcode(
	'get_houses_filter',
	static function () {
		ob_start();
		?>
        <div class="filters">
            <div class="form-field">
                <label for="name_house">Name House</label>
                <input type="text" name="name_house" id="name_house" value="<?php echo $_GET['name_house'] ?? ''; ?>">
            </div>
            <div class="form-field">
                <label for="building_type">Building Type</label>
                <?php
                $building_types = array(
                    'Панель',
                    'Кирпич',
                    'Пеноблок',
                );
                ?>
                <?php foreach ( $building_types as $row ) : ?>
                    <p><?php echo $row; ?> <input type="radio" name="building_type_select" data-type="<?php echo $row; ?>" <?php checked( $_GET['building_type'] ?? '', $row ); ?>></p>
                <?php endforeach; ?>
            </div>
            <div class="form-field">
                <label for="location_coordinates">Location coordinates</label>
                <input type="text" name="location_coordinates" id="location_coordinates" value="<?php echo $_GET['location_coordinates'] ?? ''; ?>">
            </div>
            <div class="form-field">
                <label for="number_of_floors">Number of floors</label>
                <select name="number_of_floors" id="number_of_floors">
                    <option value="" selected>Select option</option>
					<?php
					for ( $i = 1; $i < 21; $i ++ ) : ?>
                        <option value="<?php echo $i; ?>" <?php selected( $_GET['number_of_floors'] ?? '', $i ); ?>><?php echo $i; ?></option>
					<?php
					endfor; ?>
                </select>
            </div>
            <div class="form-field">
                <button class="houses-filter-submit">Submit</button>
                <a href="<?php echo home_url( 'houses' ); ?>">Clear</a>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
);

add_shortcode(
	'get_houses',
	static function () {
        $data_filters = array(
            'number_of_floors' => array(
                'value'   => $_GET['number_of_floors'] ?? '',
                'compare' => '=',
            ),
            'building_type' => array(
	            'value'   => $_GET['building_type'] ?? '',
	            'compare' => '=',
            ),
            'location_coordinates' => array(
	            'value'   => $_GET['location_coordinates'] ?? '',
	            'compare' => '=',
            ),
            'name_house' => array(
	            'value'   => $_GET['name_house'] ?? '',
	            'compare' => 'LIKE',
            ),
        );
		$queried_object_id    = get_queried_object_id();
		$paged                = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args                 = array(
			'posts_per_page' => 3,
			'paged'          => $paged,
			'post_type'      => 'real_estate_objects',
			'meta_query'     => array(
				'relation' => 'AND',
			)
		);

        foreach ( $data_filters as $key => $value ) {
            if ( ! $value['value'] ) {
                continue;
            }

	        $args['meta_query'][] = array(
		        'key'     => $key,
		        'value'   => $value['value'],
		        'compare' => $value['compare']
	        );
        }

		$posts = new WP_Query( $args );

		ob_start();
		if ( $posts->have_posts() ) :
			echo '<div class="posts" style="display: grid; grid-template-columns: repeat(3, 1fr);">';
			while ( $posts->have_posts() ) : $posts->the_post();
				?>
                <div class="post">
                    <p> Title: <?php
						echo get_the_title(); ?> </p>
                    <p> Description: <?php
						echo get_the_content(); ?></p>
                    <p> House name: <?php
						echo get_field( 'name_house' ); ?></p>
                    <p> Location coordinates: <?php
						echo get_field( 'location_coordinates' ); ?></p>
                    <p> Number of floors: <?php
						echo get_field( 'number_of_floors' ); ?></p>
                    <p> Building type: <?php
						echo get_field( 'building_type' ); ?></p>
                    <p><a href="<?php
						the_permalink(); ?>"> Link</a></p>
                </div>
			<?php
			endwhile;
			echo '</div>';
			echo '<div class="posts-pagination">';
			previous_posts_link( '&larr; Previous' );
			next_posts_link( 'Next &rarr;', $posts->max_num_pages );
			echo '</div>';
			wp_reset_postdata();
		endif;

		return ob_get_clean();
	},
);

add_action(
	'init',
	static function () use ( $post_type ) {
		# Add taxonomy
		register_taxonomy( 'area', [ $post_type ], [
			'label'             => __( 'Район', 'txtdomain' ),
			'hierarchical'      => true,
			'rewrite'           => [ 'slug' => 'area' ],
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'labels'            => [
				'singular_name' => __( 'Объект недвижимости', 'txtdomain' ),
				'all_items'     => __( 'Все объекты', 'txtdomain' ),
			]
		] );
		register_taxonomy_for_object_type( 'area', $post_type );

		# Add post-type
		$labels = array(
			'name'          => _x( 'Объекты недвижимости', 'Post Type General Name', 'twentytwentythree' ),
			'singular_name' => _x( 'Объект недвижимости', 'Post Type Singular Name', 'twentytwentythree' ),
			'menu_name'     => __( 'Объекты недвижимости', 'twentytwentythree' ),
			'all_items'     => __( 'Все объекты', 'twentytwentythree' ),
			'add_new'       => __( 'Добавить новый', 'twentytwentythree' ),
		);

		$args = array(
			'label'               => __( 'Объект недвижимости', 'twentytwentythree' ),
			'description'         => __( '', 'twentytwentythree' ),
			'labels'              => $labels,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);
// Registering your Custom Post Type
		register_post_type( $post_type, $args );
	}
);

add_action(
	'wp_ajax_get_houses_filter',
	static function () {
		$data   = $_POST;
		$params = home_url( 'houses' ) . '?';

		foreach ( $data as $key => $value ) {
			if ( 'action' === $key || ! $value ) {
				continue;
			}
			$params .= $key . '=' . $value . '&';
		}

		wp_send_json_success( rtrim( $params, '&' ) );
	}
);
