<?php

function src_get_events( $season_slug ) {
	$season_id = src_get_id_from_slug( $season_slug, 'season' );
	$events = get_post_meta( $season_id, 'event', true );
	return $events;
}

function src_get_id_from_slug( $slug, $post_type ) {

	// Get season ID from slug
	$query = new WP_Query(
		array(
			'post_type'              => 'season',
			'posts_per_page'         => 1,
			'post_title'             => $slug,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
		)
	);

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$season_id = get_the_ID();
		}
	}

	return $season_id;
}

function src_get_drivers( $season_slug ) {
	$season_id = src_get_id_from_slug( $season_slug, 'season' );
	$drivers = get_post_meta( get_the_ID(), '_seasons_drivers', true );	
	return $drivers;
}

function src_get_driver( $season_slug, $username ) {
	$season_id = src_get_id_from_slug( $season_slug, 'season' );
	$drivers = get_post_meta( get_the_ID(), '_seasons_drivers', true );	

	foreach ( $drivers as $key => $driver ) {
		if ( $username === $driver[0] ) {
			return $driver;
		}
	}

	return false;
}

function src_get_driver_info( $season_slug, $username, $info ) {
	$driver = src_get_driver( $season_slug, $username );

	if ( isset( $driver[1] ) && 'Number' === $info ) {
		return $driver[1];
	} else if ( isset( $driver[2] ) && 'Country' === $info ) {
		return $driver[2];
	} else if ( isset( $driver[3] ) && 'Car' === $info ) {
		return $driver[3];
	} else if ( isset( $driver[4] ) && 'Team' === $info ) {
		return $driver[4];
	} else if ( isset( $driver[5] ) && 'Class' === $info ) {
		return $driver[5];
	}

	return false;
}

function src_get_results( $season_slug ) {
	$season_id = src_get_id_from_slug( $season_slug, 'season' );
	$results = get_post_meta( $season_id, '_seasons_results', true );

	return $results;
}

function src_get_driver_results( $season_slug, $username ) {
	$results = src_get_results( $season_slug );
	return $results[$username];
}
