<?php
/**
 * Admin Settings tab controller
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
class Settings {

	/**
	 * Register the event
	 * */
	public function hooks() {
		add_action( 'wp_ajax_ashlin_react_save_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_ashlin_react_get_settings', array( $this, 'get_settings_ajax' ) );
	}

	/**
	 * Get settings from option table
	 * */
	public function get_settings() {
		// Load settings from option table.
		$settings = get_option( 'ashlin_react_settings' );

		// Update default value if option doesn't exists.
		if ( false === $settings ) {
			$settings = self::get_default_settings();
			update_option( 'ashlin_react_settings', $settings );
		}

		return $settings;
	}

	/**
	 * Get settings from option table for ajax request.
	 * */
	public function get_settings_ajax() {
		// Check user access.
		if ( ! Common::is_administrator() ) {
			wp_die( esc_html__( 'Invalid access.', 'ashlin-react' ), 403 );
		}

		// Verify nonce.
		check_ajax_referer( 'ashlin-react-ajax-nonce' );

		$settings = $this->get_settings();

		$return_data = array(
			'settings' => $settings,
		);
		wp_send_json_success( $return_data );
	}

	/**
	 * Save settings
	 * */
	public function save_settings() {
		// Check user access.
		if ( ! Common::is_administrator() ) {
			wp_die( esc_html__( 'Invalid access.', 'ashlin-react' ), 403 );
		}

		// Verify nonce.
		check_ajax_referer( 'ashlin-react-ajax-nonce' );

		// Check settings exists.
		if ( ! isset( $_POST['settings'] ) ) {
			wp_die( esc_html__( 'Invalid request.', 'ashlin-react' ), 400 );
		}

		$settings = wp_kses_post( wp_unslash( $_POST['settings'] ) );
		$data     = json_decode( $settings );

		// Check if the JSON string is valid.
		if ( empty( $data ) || null === $data && JSON_ERROR_NONE !== json_last_error() ) {
			wp_die( esc_html__( 'Invalid request.', 'ashlin-react' ), 400 );
		}
		$validation_result = $this->sanitize_and_validate_post_values( $data );
		if ( true === $validation_result['status'] ) {
			update_option( 'ashlin_react_settings', (object) $validation_result['data'] );
			// Didn't check the return value, because if update with same data it returns false.
			$return_data = array( 'message' => esc_html__( 'Updated successfully.', 'ashlin-react' ) );
			wp_send_json_success( $return_data );
		} else {
			$return_data = array(
				'errors'  => $validation_result['errors'],
				'message' => esc_html__( 'Failed to save.', 'ashlin-react' ),
			);
			wp_send_json_error( $return_data );
		}
	}

	/**
	 * Sanitize and validate post data.
	 *
	 * @param object $data Settings values.
	 * @return array
	 * */
	protected function sanitize_and_validate_post_values( $data ) {
		$sanitized_values = array();
		$errors           = array();

		// Validate number of rows in table field.
		if ( isset( $data->number_of_rows_in_table ) && ! empty( $data->number_of_rows_in_table ) ) {
			$sanitized_values['number_of_rows_in_table'] = intval( sanitize_text_field( $data->number_of_rows_in_table ) );
			if ( $sanitized_values['number_of_rows_in_table'] < 1 || $sanitized_values['number_of_rows_in_table'] > 5 ) {
				$errors['number_of_rows_in_table'] = esc_html__( 'Enter number between 1 and 5.', 'ashlin-react' );
			}
		} else {
			$errors['number_of_rows_in_table'] = esc_html__( 'Required.', 'ashlin-react' );
		}

		// Validate Date format.
		if ( isset( $data->date_format ) && ! empty( $data->date_format ) ) {
			$sanitized_values['date_format'] = sanitize_text_field( $data->date_format );
			if ( ! in_array( $sanitized_values['date_format'], array( 'human_readable', 'unix_timestamp' ), true ) ) {
				$errors['date_format'] = esc_html__( 'Invalid format.', 'ashlin-react' );
			}
		} else {
			$errors['date_format'] = esc_html__( 'Required.', 'ashlin-react' );
		}

		// Validate Emails.
		if ( isset( $data->emails ) && ! empty( $data->emails ) ) {
			$sanitized_values['emails'] = sanitize_text_field( $data->emails );
			$emails                     = explode( ',', $sanitized_values['emails'] );
			foreach ( $emails as $key => $email ) {
				$email = sanitize_email( $email );
				if ( empty( $email ) ) {
					$errors[ 'email_' . $key ] = esc_html__( 'Invalid email.', 'ashlin-react' );
				}
			}
		} else {
			$errors['email_0'] = esc_html__( 'Required.', 'ashlin-react' );
		}

		return array(
			'status' => empty( $errors ) ? true : false,
			'data'   => $sanitized_values,
			'errors' => $errors,
		);
	}

	/**
	 * Get default settings
	 *
	 * @return object
	 * */
	public static function get_default_settings() {
		$settings                          = new \stdClass();
		$settings->number_of_rows_in_table = 5;
		$settings->date_format             = 'human_readable';
		$settings->emails                  = get_option( 'new_admin_email' );

		return $settings;
	}
}
