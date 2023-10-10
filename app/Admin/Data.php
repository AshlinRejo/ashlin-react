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

		// Get cache data if exists.
		$data = get_transient( 'ashlin_react_api_data' );
		if ( ! $data ) {
			$api_response = wp_remote_get( $this->api_url );
			if ( is_array( $api_response ) && ! is_wp_error( $api_response ) ) {
				$data = $api_response['body'];
				// Set cache for 1 hour.
				set_transient( 'ashlin_react_api_data', $data, 3600 ); // 3600 (in seconds) = 1 hour.
			} else {
				$return_data = array( 'message' => esc_html__( 'Failed to retrieve from remote API.', 'ashlin-react' ) );
				wp_send_json_error( $return_data );
			}
		}
		$type = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : 'graph';
		if ( 'table' !== $type ) {
			$type = 'graph';
		}
		$formatted_data = $this->format_data_based_on_settings( $data, $type );
		wp_send_json_success( $formatted_data );
	}

	/**
	 * Format the API date based on settings
	 *
	 * @param string $data API json data as string.
	 * @param string $type Request for table/graph.
	 * @return object
	 * */
	protected function format_data_based_on_settings( $data, $type = 'graph' ) {
		$data            = json_decode( $data );
		$settings        = new Settings();
		$settings_values = $settings->get_settings();
		if ( 'graph' === $type ) {
			$data = $this->format_graph_data( $data->graph, $settings_values );
		} else {
			$data = $this->format_table_data( $data->table, $settings_values );
		}
		return $data;
	}

	/**
	 * Format graph data
	 *
	 * @param object $graph Graph data.
	 * @param object $settings Settings data.
	 * @return object
	 * */
	protected function format_graph_data( $graph, $settings ) {
		// To Change the date based on settings.
		if ( 'human_readable' === $settings->date_format ) {
			foreach ( $graph as $data ) {
				$data->date = $this->convert_to_human_readable_date( $data->date );
			}
		}
		return $graph;
	}

	/**
	 * Format table data
	 *
	 * @param object $table Table data.
	 * @param object $settings Settings data.
	 * @return object
	 * */
	protected function format_table_data( $table, $settings ) {
		// To limit rows based on settings.
		$new_row = array();
		foreach ( $table->data->rows as $key => $row ) {
			if ( $key >= $settings->number_of_rows_in_table ) {
				break;
			}
			$new_row[] = $row;
		}
		$table->data->rows = $new_row;

		// To Change the date based on settings.
		if ( 'human_readable' === $settings->date_format ) {
			foreach ( $table->data->rows as $row ) {
				$row->date = $this->convert_to_human_readable_date( $row->date );
			}
		}

		// To add emails.
		$table->emails = explode( ',', $settings->emails );

		return $table;
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
