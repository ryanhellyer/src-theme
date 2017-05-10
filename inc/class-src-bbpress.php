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
		add_action( 'wp_print_styles', array( $this, 'deregister_bbpress_styles' ), 15 );
		add_action( 'init', array( $this, 'init' ) );

		// Add filters
		add_filter( 'bbp_get_topic_admin_links', array( $this, 'change_admin_links' ) );
		add_filter( 'bbp_get_reply_admin_links', array( $this, 'change_admin_links' ) );
		add_filter( 'bbp_reply_content_append_signature', array( $this, 'modify_signature_output' ) );
		add_filter('bbp_get_topic_author_avatar', array( $this, 'change_avatar_size' ), 20, 3);
		add_filter('bbp_get_reply_author_avatar', array( $this, 'change_avatar_size' ), 20, 3);
		add_filter('bbp_get_current_user_avatar', array( $this, 'change_avatar_size' ), 20, 3);



	}

	/**
	 * Init.
	 */
	public function init() {

		remove_action( 'wp_print_styles', 'bbp_signature_css' );

		add_filter( 'bbp_edit_user_signature_handler', 'wp_kses_post' );
		remove_filter( 'bbp_edit_user_signature_handler', 'trim' );
		remove_filter( 'bbp_edit_user_signature_handler', 'wp_filter_kses' );
		remove_filter( 'bbp_edit_user_signature_handler', 'force_balance_tags' );
		remove_filter( 'bbp_edit_user_signature_handler', '_wp_specialchars' );

	}


	/**
	 * Deregister the bbPress stylesheet(s).
	 */
	public function deregister_bbpress_styles() {
		wp_deregister_style( 'bbp-default' );
	}

	/**
	 * Change the admin links.
	 * Removes unneeded | symbol.
	 *
	 * @param  string  $string
	 * @return string
	 */
	public function change_admin_links( $content ) {
		$content = str_replace( ' | ', '', $content );

		return $content;
	}

	public function modify_signature_output( $content ) {

		$content = str_replace( '<hr />', '', $content );

		return $content;
	}

	public function change_avatar_size( $author_avatar, $topic_id, $size ) {
		$author_avatar = '';
		if ( $size == 80 ) {
			$size = 120;
		}
		$topic_id = bbp_get_topic_id( $topic_id );
		if ( ! empty( $topic_id ) ) {
			if ( !bbp_is_topic_anonymous( $topic_id ) ) {
				$author_avatar = get_avatar( bbp_get_topic_author_id( $topic_id ), $size );
			} else {
				$author_avatar = get_avatar( get_post_meta( $topic_id, '_bbp_anonymous_email', true ), $size );
			}
		}

		return $author_avatar;
	}

}
new SRC_bbPress;
