<?php

/**
 * Seasons.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_Seasons extends SRC_Core {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'init',            array( $this, 'init' ) );
		add_action( 'cmb2_admin_init', array( $this, 'events_metaboxes' ) );
//		add_action( 'save_post',       array( $this, 'meta_boxes_save' ), 10, 2 );

	}

	/**
	 * Init.
	 */
	public function init() {

		register_post_type(
			'season',
			array(
				'public' => true,
				'label'  => __( 'Season', 'src' ),
				'supports' => array( 'thumbnail', 'title', 'editor' )
			)
		);

	}

	/**
	 * Hook in and add a metabox to demonstrate repeatable grouped fields
	 */
	public function events_metaboxes() {
		$slug = 'event';

		$cmb = new_cmb2_box( array(
			'id'           => $slug,
			'title'        => esc_html__( 'Events', 'src' ),
			'object_types' => array( 'season', ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'          => $slug,
			'type'        => 'group',
			'description' => esc_html__( 'Create all the events here. When only one event is specified, it will be listed as a special event on the website.', 'src' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Event {#}', 'src' ), // {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Event', 'src' ),
				'remove_button' => esc_html__( 'Remove Event', 'src' ),
				'sortable'      => true, // beta
			),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Track', 'src' ),
			'id'         => 'track',
			'type'       => 'select',
			'options_cb' => 'src_get_tracks',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Event Description', 'src' ),
			'description' => esc_html__( 'List the length of races, and any other relevant information specific to this event.', 'src' ),
			'id'   => 'description',
			'type' => 'textarea_small',
		) );

		foreach ( $this->event_types() as $name => $desc ) {

			$cmb->add_group_field( $group_field_id, array(
				'name' => esc_html( $name ) . ' date/time',
				'desc' => esc_html( $desc ) . ' date/time',
				'id'   => $slug . '_' . sanitize_title( $name ) . '_timestamp',
				'type' => 'text_datetime_timestamp',
				'time_format' => 'H:i', // Set to 24hr format
			) );

		}

	}

}
