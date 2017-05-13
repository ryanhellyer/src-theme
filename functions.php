<?php

//require( 'example-functions.php' );
require( 'test.php' );

require( 'inc/class-src-bbpress.php' );
require( 'inc/class-src-events.php' );
require( 'inc/class-src-results.php' );
require( 'inc/class-src-admin.php' );

/**
 * Primary class used to load the theme.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.5
 */
class SRC_Theme_Setup {

	/**
	 * Theme version number.
	 * 
	 * @var string
	 */
	const VERSION_NUMBER = '1.0';

	/**
	 * Theme name.
	 * 
	 * @var string
	 */
	const THEME_NAME = 'src';

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'after_setup_theme',  array( $this, 'theme_setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'stylesheets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'script' ) );

	}

	/**
	 * Load stylesheets.
	 */
	public function stylesheets() {
		if ( ! is_admin() ) {
			wp_enqueue_style( self::THEME_NAME, get_stylesheet_directory_uri() . '/css/style.css', array(), self::VERSION_NUMBER );
			wp_enqueue_style( 'google-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800', array(), self::VERSION_NUMBER );
		}
	}

	/**
	 * Load script.
	 */
	public function script() {
		if ( ! is_admin() ) {
			wp_enqueue_script( self::THEME_NAME, get_template_directory_uri() . '/js/script.js', null, SELF::VERSION_NUMBER );
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function theme_setup() {

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Add title tags
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
//		add_image_size( self::THEME_NAME . '-excerpt-thumb', 250, 350 );
	}

}
new SRC_Theme_Setup;
