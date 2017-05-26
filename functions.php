<?php

function sample_admin_notice__success() {

	$message = '
<textarea style="width:100%;">NEW DESIGN IDEA:
Use top bit of https://thedroneracingleague.com/

Above fold
	Sticky header 
		SRC logo on left
		Menu 
		Red JOIN button on right 
	Header image (see below for functionality)
	Latest News - three side by side news items ala F1.com
Below fold
	Calendar ala F1.com
	Last race, points, Next race ala F1.com
Footer - use current dark design, but with red JOIN button on right, like in the header

HEADER IMAGE FUNCTIONALITY:
Logged in: Shows latest news item
Logged out: Always shows same "Drivers wanted" page
Text:
	* Drivers wanted *
	We are accepting new driver registrations!
	[link]Sign up here[/link]

</textarea>
<textarea style="width:100%;">NEW features

Results page should show FP1, qual, race 1, race 2 results etc.
OR IT COULD SHOW JUST ONE, BUT WHEN THE NEXT ONE IS DONE, IT COULD REMOVE THAT FROM THE FEATURED NEWS ITEMS AND REPLACE IT WITH THE NEXT ONE, WHICH WOULD LINK TO ALL THE PREVIOUS ONES FOR THAT ROUND.
Should also show description. This will show up in news items.

Put avatar where logo is, with driver number beside it. Car picture where drivers are.
https://www.formula1.com/en/championship/teams/Red-Bull.html

Tag posts with driver, so that those articles show up at bottom of their members page. Use same taxonomy for galleries post type.
OR JUST DO SEARCH QUERY ON DRIVERS NAME AND NICKNAME, if insufficient posts with thumbnails, then could display avatar or their members page car picture instead.

Maybe add galleries as forum post-type, but just style differently. Get bad URLs then though :/

Make "WP Racing League plugin"

Pick one person from each race to ask questions to automatically. These get turned into posts on the site.
	Perhaps have 20 possible questions, and ask two people four questions each for each round.

Use this to make cars with white backgrounds: https://www.rfactorcentral.com/detail.cfm?ID=Modern%20Showroom

Use this to import log files:
https://github.com/mauserrifle/simresults
</textarea>

<textarea style="width:49%;">
INFORMATION ABOUT THE WEBSITE FOR DRIVERS

We do not have enough people writing content to have a dedicated news section, and none of us wanting to be spending all our time writing content, so I have made the system autopost some content, and we can easily turn the few interesting forum posts we have into news items which appear on the home page. This gives the appearance of a busy site, even though it is not ;)

Making news items:
If you think something should be a proper news item, just let an admin know (see about page for list of admins)

Driver profiles:
Your forum profile is the page linked to from the results section etc. To update it, simply update your forum profile. We reserve the right to edit your forum profile at any time for the benefit of the league (ie: to make the website look more interesting). You can change it to something dumb, but that will just piss us off, so please do not do that ;)

Private data:
Your profile includes some "real" information. If you do not want to reveal some private information (your name, birthdate, whatever), just put fake data in there. Please keep it believable though and do not put anything stupid in there please :P

Avatatars:
We require everyone to add an avatar. If you do not pick one yourself, we will just give you one, and it may not be pretty ;) So for your own sake, please upload a picture of yourself (or something representing you at least).


Custom registration form:
Register/login in one step
If login does not work, then check if user wants to register instead
Include "forgot your password" link
On registration, redirect to league sign up page
On logging in again, redirect to forum
</textarea>

<textarea style="width:49%;">
REQUIRED PAGES

About page
	Goals of the league

	History 
		Who made the league
		Why is it called Seacrest

	Logic behind car choices

	People
		League Administrators
			Paul and Tangofoxx
		Website 
			Ryan
		Results
			Jeffrey
		Protests 
			Ryan (or Paul if Ryan is being protested against or protesteing someone)

Skins
	Rules for SRC (required bits, colour schemes etc)
	How to make them 
	How to test them
	Links to sources of good default skins


</textarea>
	';

	?>
	<div class="notice notice-success is-dismissible">
		<p><?php echo $message; ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'sample_admin_notice__success' );

require( 'inc/class-src-core.php' );
require( 'inc/class-src-bbpress.php' );
require( 'inc/class-src-events.php' );
require( 'inc/class-src-results.php' );
require( 'inc/class-src-admin.php' );

new SRC_Events;
new SRC_Results;
new SRC_Admin;

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
