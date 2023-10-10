<?php
/**
 * Admin Data controller
 *
 * @package AshlinReact
 */

namespace AshlinReact\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Page
 */
class Data {

	/**
	 * API URL
	 *
	 * @var string $api_url
	 * */
	private $api_url = 'https://miusage.com/v1/challenge/2/static/';

	/**
	 * Register the event
	 * */
	public function hooks() {
		add_action( 'wp_ajax_ashlin_react_get_data', array( $this, 'get_data' ) );
	}

	/**
	 * Get data
	 * */
	public function get_data() {
		// Check user access.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Invalid access.', 'ashlin-react' ), 403 );
		}

		// Verify nonce.
		check_ajax_referer( 'ashlin-react-ajax-nonce' );

		$data = get_transient( 'ashlin_react_api_data' );
		if ( ! $data ) {
			$api_response = wp_remote_get( $this->api_url );
			if ( is_array( $api_response ) && ! is_wp_error( $api_response ) ) {
				$data = $api_response['body'];
				set_transient( 'ashlin_react_api_data', $data, 3600 ); // 3600 (in seconds) = 1 hour.
			} else {
				$return_data = array( 'message' => esc_html__( 'Failed to retrieve from remote API.', 'ashlin-react' ) );
				wp_send_json_error( $return_data );
			}
		}
		$formatted_data = $this->format_data_based_on_settings( $data );
		wp_send_json_success( $formatted_data );
	}

	/**
	 * Format the API date based on settings
	 *
	 * @param string $data API json data as string.
	 * @return object
	 * */
	protected function format_data_based_on_settings( $data ) {
		$data            = json_decode( $data );
		$settings        = new Settings();
		$settings_values = $settings->get_settings();
		if ( 'human_readable' === $settings_values->date_format ) {
			foreach ( $data->graph as $graph ) {
				$graph->date = $this->convert_to_human_readable_date( $graph->date );
			}

			foreach ( $data->table->data->rows as $rows ) {
				$rows->date = $this->convert_to_human_readable_date( $rows->date );
			}
		}

		return $data;
	}

	/**
	 * Convert the timestamp to human read-able date.
	 *
	 * @param integer $timestamp Timestamp.
	 * @return string
	 * */
	protected function convert_to_human_readable_date( $timestamp ) {
		// phpcs ignore is used, as date() function is required to convert the date to local timezone.
		return get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), get_option( 'date_format', 'F j, Y' ) ); // phpcs:ignore
	}
}
