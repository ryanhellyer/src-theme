<?php

add_action( 'cmb2_admin_init', 'special_event_metabox' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function special_event_metabox() {
	$prefix = 'special_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Special Event?', 'src' ),
		'object_types' => array( 'season', ),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb->add_field( array(
		'name' => esc_html__( 'Special Event?', 'src' ),
		'description' => esc_html__( 'Check this box if this is a special event. It is important to specify if it is a special event to avoid the website thinking a brand new SRC season has started', 'src' ),
		'id'   => $prefix . 'checkbox',
		'type' => 'checkbox',
	) );

}


add_action( 'cmb2_admin_init', 'races_metaboxes' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function races_metaboxes() {
	$prefix = 'event_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Events', 'src' ),
		'object_types' => array( 'season', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb->add_field( array(
		'id'          => $prefix . 'demo',
		'type'        => 'group',
		'description' => esc_html__( 'Create all the events here.', 'src' ),
		'options'     => array(
			'group_title'   => esc_html__( 'Event {#}', 'src' ), // {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Event', 'src' ),
			'remove_button' => esc_html__( 'Remove Event', 'src' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Track Name', 'src' ),
		'id'   => 'name',
		'type' => 'text',
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Track Image', 'src' ),
		'id'   => 'image',
		'type' => 'file',
	) );

	$times = array(
		'FP1' => 'Free Practice 1',
		'FP2' => 'Free Practice 1',
		'Qualifyig' => 'Qualifying',
		'Race 1' => 'Race 1',
		'Race 2' => 'Race 2',
		'Race 3' => 'Race 3',
	);

	foreach ( $times as $name => $desc ) {

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html( $name ) . ' date/time',
			'desc' => esc_html( $desc ) . ' date/time',
			'id'   => $prefix . sanitize_title( $name ) . '_timestamp',
			'type' => 'text_datetime_timestamp',
			'time_format' => 'H:i', // Set to 24hr format
		) );

	}

}

add_action( 'cmb2_admin_init', 'entrants_metaboxes' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function entrants_metaboxes() {
	$prefix = 'entrant_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Entrants', 'src' ),
		'object_types' => array( 'season', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb->add_field( array(
		'id'          => $prefix . 'demo',
		'type'        => 'group',
		'description' => esc_html__( 'Add all the entrants here.', 'src' ),
		'options'     => array(
			'group_title'   => esc_html__( 'Entrant {#}', 'src' ), // {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Entrant', 'src' ),
			'remove_button' => esc_html__( 'Remove Entrant', 'src' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Team Name', 'src' ),
		'id'         => 'title',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Team Colour', 'cmb2' ),
		'desc'    => esc_html__( 'The colour assigned to this team', 'cmb2' ),
		'id'      => $prefix . 'colorpicker',
		'type'    => 'colorpicker',
		'default' => '#dd0000',
		// 'attributes' => array(
		// 	'data-colorpicker' => json_encode( array(
		// 		'palettes' => array( '#3dd0cc', '#ff834c', '#4fa2c0', '#0bc991', ),
		// 	) ),
		// ),
	) );

	$count = 0;
	while ( $count < 4 ) {
		$count++;

		$cmb->add_group_field( $group_field_id, array(
			'name'       => __( 'Select your_post_type Posts', 'cmb2' ),
			'desc'       => __( 'field description (optional)', 'cmb2' ),
			'id'         => $prefix . 'post_multicheckbox',
			'type'       => 'select',
			'options_cb' => 'get_src_users',
		) );


		$cmb->add_group_field( $group_field_id, array(
			'name'        => esc_html__( 'Driver #' . $count . ' Name', 'src' ),
			'id'          => 'name_' . $count,
			'type'        => 'textarea_small',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'        => esc_html__( 'Driver #' . $count . ' Car Manufacturer', 'src' ),
			'id'          => 'manufacturer_' . $count,
			'type'        => 'textarea_small',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'        => esc_html__( 'Driver #' . $count . ' Car Model', 'src' ),
			'id'          => 'model_' . $count,
			'type'        => 'textarea_small',
		) );

	}

}








/**
 * Gets a number of posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_post_options( $query_args ) {

	$args = wp_parse_args( $query_args, array(
		'post_type'   => 'post',
		'numberposts' => 10,
	) );

	$posts = get_posts( $args );

	$post_options = array();
	if ( $posts ) {
		foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}

/**
 * @return array An array of options that matches the CMB2 options array
 */
function get_src_users() {

	$users = get_users(
		array(
			'orderby' => 'nicename',
		)
	);

	$options = array();
	foreach ( $users as $user ) {
		$options[ $user->ID ] = $user->display_name;
	}

	return $options;
}

add_action( 'cmb2_admin_init', 'test' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function test() {
	$prefix = 'test_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Test', 'src' ),
		'object_types' => array( 'season', ),
	) );


}
