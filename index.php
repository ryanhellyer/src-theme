<?php
/**
 * The main template file.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */

get_header(); ?>



<?php

// Load main loop
if ( is_home() ) {
	get_template_part( 'templates/content-home' );
} else if ( have_posts() ) {

	// Start of the Loop
	while ( have_posts() ) {
		the_post();
		?>

			<header class="entry-header">
					<?php

					// Don't display links on singular post titles
					if ( is_singular() ) {
						the_title();
					} else {
						?><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'hellish-simplicity' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php
					}

					?>
			</header><!-- .entry-header -->

			<?php the_content(); ?>

		<?php

	}

	get_template_part( 'template-parts/numeric-pagination' );

}
else {
	get_template_part( 'template-parts/no-results' );
}
?>



<?php get_footer(); ?>