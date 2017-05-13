<?php

/**
 * Results.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_Results {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'init',            array( $this, 'init' ) );
		add_action( 'cmb2_admin_init', array( $this, 'results_metaboxes' ) );

	}

	/**
	 * Init.
	 */
	public function init() {

		$post_types = array(
			'results' => array(
				'public' => true,
				'label'  => 'Results',
				'supports' => array( 'thumbnail' )
			),
		);

		foreach ( $post_types as $post_type => $args ) {
			register_post_type( $post_type, $args );
		}

	}

	/**
	 * Hook in and add a metabox to demonstrate repeatable grouped fields
	 */
	public function results_metaboxes() {
		$prefix = 'results_';

		/**
		 * Repeatable Field Groups
		 */
		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'metabox',
			'title'        => esc_html__( 'Results', 'src' ),
			'object_types' => array( 'results', ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'          => $prefix . 'demo',
			'type'        => 'group',
			'description' => esc_html__( 'I THINK THIS SHOULD BE CUSTOM. CMB2 IS NOT ABLE TO SHOW COLUMNS :/', 'src' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Result {#}', 'src' ), // {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Result', 'src' ),
				'remove_button' => esc_html__( 'Remove Result', 'src' ),
				'sortable'      => true, // beta
			),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'       => esc_html__( 'Penalities', 'src' ),
			'description' => 'Provide select box of penalities, include "Other (please specify) and show another text box if that option is shown for the exact reason to be specified.',
			'id'         => 'penalities',
			'type'       => 'text',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'       => esc_html__( 'Race time', 'src' ),
			'description' => 'If not time is specified, provide select box of reasons to not finish.',
			'id'         => 'race-time',
			'type'       => 'text',
		) );

	}

}
new SRC_Results;
