<?php

/**
 * bbPress features.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_bbPress {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'wp_print_styles',    array( $this, 'deregister_bbpress_styles' ), 15 );

	}

	/**
	 * Deregister the bbPress stylesheet(s).
	 */
	public function deregister_bbpress_styles() {
		wp_deregister_style( 'bbp-default' );
	}

}
new SRC_bbPress;
