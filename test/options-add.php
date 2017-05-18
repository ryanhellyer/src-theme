<?php

/**
 * Example Options Page with rows
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyergmail.com>
 * @since 1.0
 */
class Example_Options_Page_With_Rows extends SRC_Core {

	/**
	 * Set some constants for setting options.
	 */
	const MENU_SLUG = 'example-page';
	const GROUP     = 'example-group';
	const OPTION    = 'example-option';

	public $keys;

	/**
	 * Fire the constructor up :)
	 */
	public function __construct() {
return;
		$this->keys = array(
			'position',
			'hours',
			'minutes',
			'seconds',
			'dnf',
		);

		// Add to hooks
		add_action( 'admin_init',     array( $this, 'register_settings' ) );
		add_action( 'admin_menu',     array( $this, 'create_admin_page' ) );
		add_action( 'admin_footer',   array( $this, 'scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
//		add_action( 'save_post',      array( $this, 'meta_boxes_save' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Init plugin options to white list our options.
	 */
	public function register_settings() {
		register_setting(
			self::GROUP,               // The settings group name
			self::OPTION,              // The option name
			array( $this, 'sanitize' ) // The sanitization callback
		);
	}

	/**
	 * Add admin metabox.
	 */
	public function add_metabox() {
		add_meta_box(
			'example', // ID
			__( 'Example meta box', 'plugin-slug' ), // Title
			array(
				$this,
				'meta_box', // Callback to method to display HTML
			),
			'results', // Post type
			'normal', // Context, choose between 'normal', 'advanced', or 'side'
			'default'  // Position, choose between 'high', 'core', 'default' or 'low'
		);
	}

	/**
	 * Create the page and add it to the menu.
	 */
	public function create_admin_page() {
		add_options_page(
			__ ( 'Example admin page', 'plugin-slug' ), // Page title
			__ ( 'Example page', 'plugin-slug' ),       // Menu title
			'manage_options',                           // Capability required
			self::MENU_SLUG,                            // The URL slug
			array( $this, 'meta_box' )                // Displays the admin page
		);
	}

	/**
	 * Output the admin page.
	 */
	public function meta_box() {

		?>
		<div class="wrap">
			<h2><?php _e( 'Example admin page', 'plugin-slug' ); ?></h2>
			<p><?php _e( 'Place a description of what the admin page does here to help users make better use of the admin page.', 'plugin-slug' ); ?></p>

			<form method="post" action="options.php">

				<table class="wp-list-table widefat plugins">
					<thead>
						<tr>
							<th class='column-author'>
								Driver
							</th>
							<th class='column-author'>
								Time
							</th>

							<th class='column-author'>
								DNF?
							</th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<th class='column-author'>
								Driver
							</th>
							<th class='column-author'>
								Time
							</th>

							<th class='column-author'>
								DNF?
							</th>
						</tr>
					</tfoot>

					<tbody id="add-rows"><?php

					// Grab options array and output a new row for each setting
					$options = get_option( self::OPTION );
					if ( is_array( $options ) ) {
						foreach( $options as $key => $value ) {
							echo $this->get_row( $value );
						}
					}

					// Add a new row by default
					echo $this->get_row();
					?>
					</tbody>
				</table>

				<input type="button" id="add-new-row" value="<?php _e( 'Add new row', 'plugin-slug' ); ?>" />

				<?php settings_fields( self::GROUP ); ?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'plugin-slug' ); ?>" />
				</p>
			</form>

		</div><?php
	}

	/**
	 * Get a single table row.
	 * 
	 * @param  string  $value  Option value
	 * @return string  The table row HTML
	 */
	public function get_row( $value = '' ) {

		if ( ! is_array( $value ) ) {
			$value = array();
		}

		foreach (
			$this->keys
			as $key
		) {
			if ( ! isset( $value[ $key ] ) ) {
				$value [ $key ] = '';
			}
		};

		$options = '<option selected disabled>' . esc_html__( 'Select a driver', 'src' ) . '</option>';
		foreach ( $this->get_src_users() as $user_id => $name ) {
			$options .= '<option value="' . esc_attr( $user_id ) . '">' . esc_html( $name ) . '</option>';
		}

		// Create the required HTML
		$row_html = '

					<tr class="sortable inactive">
						<td>
							<select name="' . esc_attr( self::OPTION ) . '[][driver]">' . $options . '</select>
						</td>
						<td>
							<label>Hours</label>
							<input class="small-text" type="number" name="' . esc_attr( self::OPTION ) . '[][hours]" value="' . esc_attr( $value['hours'] ) . '" />

							<label>Minutes</label>
							<input class="small-text" type="number" name="' . esc_attr( self::OPTION ) . '[][minutes]" value="' . esc_attr( $value['minutes'] ) . '" />

							<label>Seconds</label>
							<input class="small-text" type="number" name="' . esc_attr( self::OPTION ) . '[][seconds]" value="' . esc_attr( $value['seconds'] ) . '" />
						</td>
						<td>
							<input type="checkbox" name="' . esc_attr( self::OPTION ) . '[][time]" />
						</td>
					</tr>';

		// Strip out white space (need on line line to keep JS happy)
		$row_html = str_replace( '	', '', $row_html );
		$row_html = str_replace( "\n", '', $row_html );

		// Return the final HTML
		return $row_html;
	}

	/**
	 * Sanitize the page or product ID.
	 *
	 * @param   array   $input   The input string
	 * @return  array            The sanitized string
	 */
	public function sanitize( $input ) {
		$output = array();

		// Loop through each bit of data
		foreach( $input as $key => $value ) {

			foreach ( $this->keys as $key ) {
				$sanitized_value[ $key ] = wp_kses_post( $value[ $key ] );
			}

			// Put sanitized data in output variable
			$output[$sanitized_key] = $sanitized_value;

		}

		// Return the sanitized data
		return $output;
	}

	/**
	 * Output scripts into the footer.
	 * This is not best practice, but is implemented like this here to ensure that it can fit into a single file.
	 */
	public function scripts() {

		// Bail out if not on correct page
		if (
			isset( $_GET['page'] ) && self::MENU_SLUG != $_GET['page']
			||
			! isset( $_GET['page'] )
		) {
//			return;
		}

		?>
		<style>
		.read-more-text {
			display: none;
		}
		.sortable .toggle {
			display: inline !important;
		}
		</style>
		<script>

			jQuery(function($){ 

				/**
				 * Adding some buttons
				 */
				function add_buttons() {

					// Loop through each row
					$( ".sortable" ).each(function() {

						// If no input field found with class .remove-setting, then add buttons to the row
						if(!$(this).find('input').hasClass('remove-setting')) {

							// Add a remove button
							$(this).append('<td><input type="button" class="remove-setting" value="&times;" /></td>');

							// Remove button functionality
							$('.remove-setting').click(function () {
								$(this).parent().parent().remove();
							});

						}

					});

				}

				// Create the required HTML (this should be added inline via wp_localize_script() once JS is abstracted into external file)
				var html = '<?php echo $this->get_row( '' ); ?>';

				// Add the buttons
				add_buttons();

				// Add a fresh row on clicking the add row button
				$( "#add-new-row" ).click(function() {
					$( "#add-rows" ).append( html ); // Add the new row
					add_buttons(); // Add buttons tot he new row
				});

				// Allow for resorting rows
				$('#add-rows').sortable({
					axis: "y", // Limit to only moving on the Y-axis
				});

 			});

		</script><?php
	}

	/**
	 * Registers the JavaScript for handling the media uploader.
	 *
	 * @since 1.3
	 */
	public function enqueue_scripts( $hook ) {

		// Bail out if not on correct page
		if (
			isset( $_GET['page'] ) && self::MENU_SLUG != $_GET['page']
			||
			! isset( $_GET['page'] )
		) {
			return $hook;
		}

		wp_enqueue_media();

	}

}
new Example_Options_Page_With_Rows();
