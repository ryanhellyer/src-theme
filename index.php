<?php
/**
 * The main template file.
 *
 * @package SRC Theme
 * @since SRC Theme 1.0
 */

get_header();

echo '<div id="bbpress-wrapper">';


// Load main loop
if ( have_posts() ) {

	// Start of the Loop
	while ( have_posts() ) {
		the_post();


		if ( ! bbp_is_single_user() ) {
			?>

			<header class="entry-header">
				<h1>
					<?php

					// Don't display links on singular post titles
					if ( is_singular() ) {
						the_title();
					} else {
						?><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'hellish-simplicity' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php
					}

					?>
				</h1>
			</header><!-- .entry-header --><?php
		}

		the_content();
	}

	get_template_part( 'template-parts/numeric-pagination' );

}
else {
	get_template_part( 'template-parts/no-results' );
}


?>


	<div id="sidebar">

		<h3>Latest posts</h3>
		<ul>
			<li><a href="#">Some thread by Luigi</a></li>
			<li><a href="#">Latest results</a></li>
			<li><a href="#">My penis is itchy. Will driving faster help me scratch it?</a></li>
			<li><a href="#">My car is fucked</a></li>
			<li><a href="#">Pinks cars are faster than other cars</a></li>
		</ul>

		<h3>Newest users</h3>
		<ul>
			<li><a href="#">Elise bla</a></li>
			<li><a href="#">Paul Rosanski</a></li>
			<li><a href="#">Tango Foxx</a></li>
			<li><a href="#">Jacob Reid</a></li>
		</ul>

	</div><!-- #sidebar -->


</div>

<?php get_footer();