<?php
/**
 * Admin Page controller
 *
 * @package AshlinReact
 */

namespace AshlinReact\Admin;

use AshlinReact\Helper\Common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Page
 */
class Page {

	/**
	 * Register the event
	 * */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Include CSS files
	 * */
	public function enqueue_styles() {
		$current_screen = get_current_screen();
		if ( 'toplevel_page_ashlin-react' === $current_screen->id ) {
			wp_enqueue_style( 'ashlin-react', ASHLIN_REACT_URL . 'assets/css/admin.css', array(), ASHLIN_REACT_VERSION, 'all' );
		}
	}

	/**
	 * Include javascript files
	 * */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( 'toplevel_page_ashlin-react' === $current_screen->id ) {
			wp_enqueue_script( 'ashlin-react', ASHLIN_REACT_URL . 'assets/js/admin.js', array( 'wp-element' ), ASHLIN_REACT_VERSION, true );
			wp_localize_script(
				'ashlin-react',
				'ashlinReact',
				array(
					'_ajax_nonce' => wp_create_nonce( 'ashlin-react-ajax-nonce' ),
					'title_text'  => esc_html__( 'AshlinReact', 'ashlin-react' ),
					'email'       => get_option( 'admin_email' ),
					'tab'         => array(
						'table_text'    => esc_html__( 'Table', 'ashlin-react' ),
						'graph_text'    => esc_html__( 'Graph', 'ashlin-react' ),
						'settings_text' => esc_html__( 'Settings', 'ashlin-react' ),
					),
					'settings'    => array(
						'number_of_rows_in_table_text'    => esc_html__( 'Number of rows in table', 'ashlin-react' ),
						'number_of_rows_in_table_desc_text' => esc_html__( 'To set the number of rows to display in a table', 'ashlin-react' ),
						'error_enter_number_between_one_and_five' => esc_html__( 'Enter number between 1 and 5.', 'ashlin-react' ),
						'date_format_text'                => esc_html__( 'Date format.', 'ashlin-react' ),
						'date_format_desc_text'           => esc_html__( 'Display the table’s date column in human readable format or as a Unix timestamp.', 'ashlin-react' ),
						'date_format_human_readable_text' => esc_html__( 'Human readable format', 'ashlin-react' ),
						'date_format_unix_timestamp_text' => esc_html__( 'Unix timestamp', 'ashlin-react' ),
						'error_invalid_input'             => esc_html__( 'Invalid input', 'ashlin-react' ),
						/* translators: %s: date in human readable format. */
						'human_readable_format_preview_text' => sprintf( esc_html__( 'Preview: %s', 'ashlin-react' ), current_time( get_option( 'date_format', 'F j, Y' ) ) ),
						/* translators: %s: date in unix timestamp. */
						'timestamp_preview_text'          => sprintf( esc_html__( 'Preview: %s', 'ashlin-react' ), time() ),
						'email_text'                      => esc_html__( 'Emails', 'ashlin-react' ),
						'email_desc_text'                 => esc_html__( 'You can add upto 5 emails', 'ashlin-react' ),
						'error_enter_an_email'            => esc_html__( 'Enter an email.', 'ashlin-react' ),
						'error_enter_valid_email'         => esc_html__( 'Enter a valid email.', 'ashlin-react' ),
						'add_email_button_text'           => esc_html__( 'Add', 'ashlin-react' ),
						'save_button_text'                => esc_html__( 'Save settings', 'ashlin-react' ),
						'failed_to_save'                  => esc_html__( 'Failed to save.', 'ashlin-react' ),
					),
					'graph'       => array(
						'value_text' => esc_html__( 'Value', 'ashlin-react' ),
					),
					'table'       => array(
						'emails_title_text' => esc_html__( 'Emails', 'ashlin-react' ),
					),
				)
			);
		}
	}

	/**
	 * For adding admin menu
	 * */
	public function add_menu() {
		if ( ! is_admin() ) {
			return;
		}

		add_menu_page(
			esc_html__( 'AshlinReact', 'ashlin-react' ),
			'AshlinReact',
			'manage_options',
			'ashlin-react',
			array( $this, 'load_menu_content' ),
			'dashicons-chart-bar',
			6
		);
	}

	/**
	 * Admin menu content
	 * */
	public function load_menu_content() {
		if ( ! Common::is_administrator() ) {
			return;
		}
		include_once 'templates/dashboard.php';
	}
}
