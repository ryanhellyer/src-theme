<?php

if( isset( $_GET['test'])){
	$bla = get_post_meta( 53 );
	$bla = $bla['event_'][0];
	$bla = maybe_unserialize( $bla );
	print_r( $bla );
	die;
}

/**
 * Events.
 *
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package SRC Theme
 * @since SRC Theme 1.0
 */
class SRC_Events {

	/**
	 * Constructor.
	 * Add methods to appropriate hooks and filters.
	 */
	public function __construct() {

		// Add action hooks
		add_action( 'init',            array( $this, 'init' ) );
		add_action( 'cmb2_admin_init', array( $this, 'events_metaboxes' ) );
		add_action( 'cmb2_admin_init', array( $this, 'entrants_metaboxes' ) );
		add_action( 'cmb2_admin_init', array( $this, 'cars_metaboxes' ) );

	}

	/**
	 * Init.
	 */
	public function init() {

		$post_types = array(
			'season' => array(
				'public' => true,
				'label'  => 'Season',
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
	public function events_metaboxes() {
		$slug = 'event';

		$cmb = new_cmb2_box( array(
			'id'           => $slug,
			'title'        => esc_html__( 'Events', 'src' ),
			'object_types' => array( 'season', ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'          => $slug,
			'type'        => 'group',
			'description' => esc_html__( 'Create all the events here. When only one event is specified, it will be listed as a special event on the website.', 'src' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Event {#}', 'src' ), // {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Event', 'src' ),
				'remove_button' => esc_html__( 'Remove Event', 'src' ),
				'sortable'      => true, // beta
			),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Track Name', 'src' ),
			'id'   => 'track_name',
			'type' => 'text',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Track Country', 'src' ),
			'id'         => 'country',
			'type'       => 'select',
			'options_cb' => array( $this, 'get_countries' ),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Event Name', 'src' ),
			'description' => esc_html__( 'Usually in the format "Round 3: Laguna Seca"', 'src' ),
			'id'   => 'name',
			'type' => 'text',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Event Description', 'src' ),
			'description' => esc_html__( 'List the length of races, and any other relevant information specific to this event.', 'src' ),
			'id'   => 'description',
			'type' => 'textarea_small',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Event Image', 'src' ),
			'description' => esc_html__( 'This will most likely be a an image of the track.', 'src' ),
			'id'   => 'image',
			'type' => 'file',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'PRO Points Awarded', 'src' ),
			'description' => esc_html__( 'A comma delimited list of points awarded for the PRO division. If this is a single event season (AKA special event), the points will be ignored.', 'src' ),
			'default' => '10,8,6,5,4,3,2,1',
			'id'   => 'pro_points',
			'type' => 'text',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'AM Points Awarded', 'src' ),
			'description' => esc_html__( 'A comma delimited list of points awarded for the PRO division. If this is left blank, the system will assume there is no PRO or AM division.', 'src' ),
			'default' => '10,8,6,5,4,3,2,1',
			'id'   => 'am_points',
			'type' => 'text',
		) );

		$times = array(
			'FP1' => 'Free Practice 1',
			'FP2' => 'Free Practice 1',
			'Qualifying' => 'Qualifying',
			'Race 1' => 'Race 1',
			'Race 2' => 'Race 2',
			'Race 3' => 'Race 3',
		);

		foreach ( $times as $name => $desc ) {

			$cmb->add_group_field( $group_field_id, array(
				'name' => esc_html( $name ) . ' date/time',
				'desc' => esc_html( $desc ) . ' date/time',
				'id'   => $slug . '_' . sanitize_title( $name ) . '_timestamp',
				'type' => 'text_datetime_timestamp',
				'time_format' => 'H:i', // Set to 24hr format
			) );

		}

	}

	/**
	 * Hook in and add a metabox to demonstrate repeatable grouped fields
	 */
	public function entrants_metaboxes() {
		$slug = 'entrant';

		/**
		 * Repeatable Field Groups
		 */
		$cmb = new_cmb2_box( array(
			'id'           => $slug,
			'title'        => esc_html__( 'Entrants', 'src' ),
			'object_types' => array( 'season', ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'          => $slug,
			'type'        => 'group',
			'description' => esc_html__( 'Add all the entrants here.', 'src' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Entrant {#}', 'src' ), // {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Entrant', 'src' ),
				'remove_button' => esc_html__( 'Remove Entrant', 'src' ),
				'sortable'      => true, // beta
			),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'       => esc_html__( 'Team Name', 'src' ),
			'id'         => 'team_name',
			'type'       => 'text',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'    => esc_html__( 'Team Colour', 'cmb2' ),
			'desc'    => esc_html__( 'The colour assigned to this team', 'cmb2' ),
			'id'      => 'team_colour',
			'type'    => 'colorpicker',
			'default' => '#dd0000',
		) );

		$count = 0;
		while ( $count < 4 ) {
			$count++;

			$cmb->add_group_field( $group_field_id, array(
				'name'       => esc_html__( 'Driver #' . $count . ' Name', 'src' ),
				'id'         => 'name_' . $count,
				'type'       => 'select',
				'options_cb' => array( $this, 'get_src_users' ),
			) );

			$cmb->add_group_field( $group_field_id, array(
				'name'        => esc_html__( 'Driver #' . $count . ' Number', 'src' ),
				'id'          => 'number_' . $count,
				'type'        => 'text',
			) );

			$cmb->add_group_field( $group_field_id, array(
				'name'        => esc_html__( 'Driver #' . $count . ' Car Model', 'src' ),
				'id'          => 'model_' . $count,
				'type'        => 'text',
			) );

			$cmb->add_group_field( $group_field_id, array(
				'name'       => esc_html__( 'Driver #' . $count . ' Level', 'src' ),
				'id'         => 'level_' . $count,
				'type'       => 'select',
				'options_cb' => array( $this, 'get_race_classes' ),
			) );

		}

	}

	/**
	 * Hook in and add a metabox to demonstrate repeatable grouped fields
	 */
	public function cars_metaboxes() {
		$slug = 'car';

		$cmb = new_cmb2_box( array(
			'id'           => $slug,
			'title'        => esc_html__( 'Cars', 'src' ),
			'object_types' => array( 'season', ),
		) );

		$group_field_id = $cmb->add_field( array(
			'id'          => $slug,
			'type'        => 'group',
			'description' => esc_html__( 'Add all the cars here.', 'src' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Car {#}', 'src' ), // {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Car', 'src' ),
				'remove_button' => esc_html__( 'Remove Car', 'src' ),
				'sortable'      => true, // beta
			),
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name'        => esc_html__( 'Car Type', 'src' ),
			'description' => esc_html__( 'Put batches of cars together, which are suitable for use within a single team. This information will be used to (hopefully) intelligently work out what cars are left when new competitors try to sign up, and prevent them from submitting requests for cars which are unavailable.', 'src' ),
			'id'          => 'car_type',
			'type'        => 'text',
		) );

		$count = 0;
		while ( $count < 4 ) {
			$count++;

			$cmb->add_group_field( $group_field_id, array(
				'name'       => esc_html__( 'Car Model #' . $count, 'src' ),
				'id'         => 'car_' . $count,
				'type'       => 'text',
			) );

		}


	}

	/**
	 * XXXXXXXXX
	 */
	public  function get_src_users() {

		$users = get_users(
			array(
				'orderby' => 'nicename',
			)
		);

		$options = array( 0 => '' );
		foreach ( $users as $user ) {
			$options[ $user->ID ] = $user->display_name;
		}

		return $options;
	}

	/**
	 * Get the various race classes.
	 *
	 * @return array
	 */
	public function get_race_classes() {

		return array(
			'' => '',
			'am' => 'AM',
			'pro' => 'PRO',
		);

	}

	/**
	 * Get every country.
	 *
	 * @return array
	 */
	public function get_countries() {

		$countries = array(
			"AF" => "Afghanistan",
			"AX" => "Åland Islands",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AQ" => "Antarctica",
			"AG" => "Antigua and Barbuda",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belgium",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BA" => "Bosnia and Herzegovina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"IO" => "British Indian Ocean Territory",
			"BN" => "Brunei Darussalam",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos (Keeling) Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo",
			"CD" => "Congo, The Democratic Republic of The",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"CI" => "Cote D'ivoire",
			"HR" => "Croatia",
			"CU" => "Cuba",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands (Malvinas)",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GG" => "Guernsey",
			"GN" => "Guinea",
			"GW" => "Guinea-bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard Island and Mcdonald Islands",
			"VA" => "Holy See (Vatican City State)",
			"HN" => "Honduras",
			"HK" => "Hong Kong",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran, Islamic Republic of",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IM" => "Isle of Man",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JE" => "Jersey",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KP" => "Korea, Democratic People's Republic of",
			"KR" => "Korea, Republic of",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Lao People's Democratic Republic",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libyan Arab Jamahiriya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macao",
			"MK" => "Macedonia, The Former Yugoslav Republic of",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"MX" => "Mexico",
			"FM" => "Micronesia, Federated States of",
			"MD" => "Moldova, Republic of",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"ME" => "Montenegro",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"AN" => "Netherlands Antilles",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PS" => "Palestinian Territory, Occupied",
			"PA" => "Panama",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RE" => "Reunion",
			"RO" => "Romania",
			"RU" => "Russian Federation",
			"RW" => "Rwanda",
			"SH" => "Saint Helena",
			"KN" => "Saint Kitts and Nevis",
			"LC" => "Saint Lucia",
			"PM" => "Saint Pierre and Miquelon",
			"VC" => "Saint Vincent and The Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"ST" => "Sao Tome and Principe",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"RS" => "Serbia",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SK" => "Slovakia",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia and The South Sandwich Islands",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard and Jan Mayen",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syrian Arab Republic",
			"TW" => "Taiwan, Province of China",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania, United Republic of",
			"TH" => "Thailand",
			"TL" => "Timor-leste",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad and Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks and Caicos Islands",
			"TV" => "Tuvalu",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"AE" => "United Arab Emirates",
			"GB" => "United Kingdom",
			"US" => "United States",
			"UM" => "United States Minor Outlying Islands",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VE" => "Venezuela",
			"VN" => "Viet Nam",
			"VG" => "Virgin Islands, British",
			"VI" => "Virgin Islands, U.S.",
			"WF" => "Wallis and Futuna",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe"
		);

		return $countries;
	}

}
new SRC_Events;
