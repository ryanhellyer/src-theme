<?php
/**
 * Front page template file.
 *
 * @package SRC Theme
 * @since SRC Theme 1.0
 */

get_header();


?>

<section id="latest-news">
	<header>
		<h2>The latest in the Seacrest Racing Club</h2>
	</header>

	<div class="slider" data-slides="3" data-subtract="80">
		<div class="slider-inner"><?php

	if ( is_user_logged_in() ) {

		$args = array(
			'post_type'              => 'topic',
			'meta_key'               => '_thumbnail_id',
			'posts_per_page'         => 6,
			'offset'                 => 1,
			'no_found_rows'          => true,  // useful when pagination is not needed.
			'update_post_meta_cache' => false, // useful when post meta will not be utilized.
			'update_post_term_cache' => false, // useful when taxonomy terms will not be utilized.
			'fields'                 => 'ids'
		);

	} else {

		$args = array(
			'post_type'              => 'topic',
			'meta_key'               => '_thumbnail_id',
			'posts_per_page'         => 6,
			'no_found_rows'          => true,  // useful when pagination is not needed.
			'update_post_meta_cache' => false, // useful when post meta will not be utilized.
			'update_post_term_cache' => false, // useful when taxonomy terms will not be utilized.
			'fields'                 => 'ids'
		);

	}

	$featured_items = new WP_Query( $args );
	$count = 0;
	if ( $featured_items->have_posts() ) {
		while ( $featured_items->have_posts() ) {
			$featured_items->the_post();
			$count++;

			?>


			<a href="<?php the_permalink(); ?>" class="<?php echo esc_attr( 'slide post-' . $count ); ?>">
				<div class="image" style="background-image:url(<?php echo esc_url( get_the_post_thumbnail_url() ); ?>);"></div>

				<div class="box-text">
					<date><?php the_date(); ?></date>
					<p>
						<?php the_title(); ?>
					</p>
				</div>

			</a><?php

		}
	}

	?>

		</div><!-- .slider-inner -->
	</div><!-- .slider-outer -->
</section><!-- #latest-news -->

<section id="schedule">
	<div class="slider" data-slides="6">
		<ul class="slider-inner"><?php

$season_slug = '3';

			$count = 0;
			foreach ( src_get_events( $season_slug ) as $number => $event ) {
				$count++;

				$timestamp = $event['event_race-1_timestamp'];

				$text1 = $event['track_name'];

				if ( ! isset( $future ) && $timestamp > time() ) {
					$future = true;

					$extra_class = ' double-slide';
					$data_width = ' data-width="2"';

					$text2 = '
					<h2>' . sprintf( esc_html__( 'Round %s: %s' ), $count, $event['track_name'] )  . '</h2>';

					if ( isset( $event['event_qualifying_timestamp'] ) && '' !== $event['event_qualifying_timestamp'] ) {
						$date_qual  = get_date_from_gmt( date( get_option( 'date_format' ), $event['event_qualifying_timestamp'] ), get_option( 'date_format' ) );
						$time_qual  = get_date_from_gmt( date( get_option( 'time_format' ), $event['event_qualifying_timestamp'] ), get_option( 'time_format' ) ) . ' CET';
						$text2 .= '
					<span>
						' . esc_html( 'Qualifying', 'src' ) . ': <date>' . esc_html( $time_qual ) . ', ' . esc_html( $date_qual ) . '</date>
					</span>';
					}

					if ( isset( $event['event_race-1_timestamp'] ) && '' !== $event['event_race-1_timestamp'] ) {
						$date_race1 = get_date_from_gmt( date( get_option( 'date_format' ), $event['event_race-1_timestamp'] ), get_option( 'date_format' ) );
						$time_race1 = get_date_from_gmt( date( get_option( 'time_format' ), $event['event_race-1_timestamp'] ), get_option( 'time_format' ) ) . ' CET';
						$text2 .= '
					<span>
						' . esc_html( 'Race 1', 'src' ) . ': <date>' . esc_html( $time_race1 ) . ', ' . esc_html( $date_race1 ) . '</date>
					</span>';
					}

					if ( isset( $event['event_race-2_timestamp'] ) && '' !== $event['event_race-2_timestamp'] ) {
						$date_race2 = get_date_from_gmt( date( get_option( 'date_format' ), $event['event_race-2_timestamp'] ), get_option( 'date_format' ) );
						$time_race2 = get_date_from_gmt( date( get_option( 'time_format' ), $event['event_race-2_timestamp'] ), get_option( 'time_format' ) ) . ' CET';
						$text2 .= '
					<span>
						' . esc_html( 'Race 2', 'src' ) . ': <date>' . esc_html( $time_race2 ) . ', ' . esc_html( $date_race2 ) . '</date>
					</span>';
					}

				} else {

					$extra_class = '';
					$data_width = '';

					$text2 = '
					<date>
						<span>' . esc_html( date( 'd', $timestamp ) ) . '</span>
						' . esc_html( date( 'M', $timestamp ) ) . '
					</date>';

				}

				echo '
			<li class="' . esc_attr( 'post-' . $count . ' slide' . $extra_class ) . '"' . $data_width . '>
				<div>
					<img src="' . esc_url( get_template_directory_uri() . '/images/flag1.png' ) . '" />
					' . esc_html( $text1 ) .

					/* Already escaped */
					$text2

					. '
				</div>
			</li>';

			}


			?>

		</ul><!-- .slider-inner -->
	</div><!-- .slider -->
</section><!-- #schedule -->

<?php

if ( is_super_admin() ) {
	?>

<section id="results">

	<div class="other-race" style="background-image:url(http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/track.jpg">
		<h2>Last Race</h2>
		<p>Round 2<br />Monaco</p>
	</div>

	<div id="standings">
		<h3>Driver Standings</h3>
		<ul>
			<li>Pro Drivers</li>
			<li>Am Drivers</li>
			<li>Teams</li>
		</ul>
		<table>
			<tr>
				<td class="pink"><span>1</span></td>
				<td>Trek</td>
				<td>Ferrari 458</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/pink-car.jpg" /></td>
				<td>949</td>
			</tr>
			<tr>
				<td class="green"><span>2</span></td>
				<td>Paul Rosanski</td>
				<td>Audi R8</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/green-car.jpg" /></td>
				<td>86</td>
			</tr>
			<tr>
				<td class="red"><span>3</span></td>
				<td>Tango Foxx</td>
				<td>BMW M6</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/red-car.jpg" /></td>
				<td>46</td>
			</tr>
			<tr>
				<td class="pink"><span>4</span></td>
				<td>Ryan Hellyer</td>
				<td>Ferrari 458</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/pink-car.jpg" /></td>
				<td>24</td>
			</tr>
			<tr>
				<td class="yellow"><span>5</span></td>
				<td>Speedylu</td>
				<td>Porsche 911</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/yellow-car.jpg" /></td>
				<td>11</td>
			</tr>
			<tr>
				<td class="pink"><span>1</span></td>
				<td>Trek</td>
				<td>Ferrari 458</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/pink-car.jpg" /></td>
				<td>949</td>
			</tr>
			<tr>
				<td class="green"><span>2</span></td>
				<td>Paul Rosanski</td>
				<td>Audi R8</td>
				<td class="car"><img src="http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/green-car.jpg" /></td>
				<td>86</td>
			</tr>
		</table>

		<a href="#" class="highlighted-link">See all championship standings</a>

	</div>

	<div class="other-race" style="background-image:url(http://dev-hellyer.kiwi/bbpress/wp-content/themes/src/images/track.jpg">
		<h2>Next Race</h2>
		<p>Round 4<br />Bathurst</p>
	</div>

</section><!-- #results -->

<?php
}

get_footer();