<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package SRC
 * @since SRC 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'hellish-simplicity' ); ?></a>

<header id="site-header" role="banner">
	<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( get_bloginfo( 'name', 'display' ) ); ?>"><?php esc_html_e( get_bloginfo( 'name', 'display' ) ); ?></a></h1>
	<nav>
		<ul>
			<li><a href="#">Forum</a></li>
			<li><a href="#">Schedule</a></li>
			<li><a href="#">Results</a></li>
			<li><a href="#">Live Timing</a></li>
			<li><a href="#">Gallery</a></li>
			<li><?php

			if ( is_user_logged_in() ) {
				echo '
				<span>Welcome</span>
				<a href="#">View Profile</a>';
			} else {
				echo '
				<span>Join us</span>
				<a href="#">Log in</a> or <a href="#">Register</a>';
			}
			?>

			</li>
		</ul>
	</nav>
</header><!-- #site-header -->

<?php
if ( is_front_page() ) {
	?>
<section id="featured-news" style="background-image: url(http://dev-hellyer.kiwi/bbpress/wp-content/themes/src-theme/images/featured-image.jpg);">
	<div class="text">
		<h1>Hockenheim Results: Stunning wins for Rosanski and Speedy</h1>
		<a href="#" class="button">Check out the results</a>
	</div>
</section><!-- #featured-news -->

<?php } else { ?>

<div id="header-section">
</div><!-- #header-section --><?php
}
?>

<main id="main">
