<?php
/**
 * Admin Settings test
 *
 * @package AshlinReact
 */

use AshlinReact\Admin\Settings;

/**
 * Admin Settings test case.
 */
class Settings_Test extends WP_Ajax_UnitTestCase {

	use Ashlin_React_Test_Set_Up;
	use Ashlin_React_Test_Hook_Registered;

	/**
	 * To make an ajax call
	 *
	 * @param string $action Ajax action.
	 * */
	protected function make_ajax_call( $action ) {
		// Make the request.
		try {
			$this->_handleAjax( $action );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
	}

	/**
	 * Test for hooks().
	 */
	public function test_hooks() {
		// Verify actions.
		$ajax_post_action   = $this->is_hook_registered( 'wp_ajax_ashlin_react_save_settings', 10, 'AshlinReact\Admin\Settings', 'save_settings' );
		$ajax_get_action    = $this->is_hook_registered( 'wp_ajax_ashlin_react_get_settings', 10, 'AshlinReact\Admin\Settings', 'get_settings_ajax' );
		$actions_registered = ( true === $ajax_post_action && true === $ajax_get_action );
		$this->assertTrue( $actions_registered );
	}

	/**
	 * Test for get_settings_ajax() ajax call.
	 */
	public function test_get_settings_ajax() {
		$_SERVER['REQUEST_METHOD'] = 'GET';

		// Set GET values.
		$_GET = array(
			'action'      => 'ashlin_react_get_settings',
			'_ajax_nonce' => wp_create_nonce( 'ashlin-react-ajax-nonce' ),
		);

		$this->make_ajax_call( 'ashlin_react_get_settings' );

		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$this->assertTrue( $response['success'] && ! empty( $response['data']['settings'] ) );
	}

	/**
	 * Test for save_settings() ajax call.
	 */
	public function test_save_settings() {
		$_SERVER['REQUEST_METHOD'] = 'POST';

		$settings                          = new stdClass();
		$settings->number_of_rows_in_table = 4;
		$settings->date_format             = 'unix_timestamp';
		$settings->emails                  = 'testing@test.com,testing1@test.com';

		// Set POST values.
		$_POST = array(
			'action'      => 'ashlin_react_save_settings',
			'_ajax_nonce' => wp_create_nonce( 'ashlin-react-ajax-nonce' ),
			'settings'    => wp_json_encode( $settings ),
		);

		$this->make_ajax_call( 'ashlin_react_save_settings' );

		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$settings_after_save = ( new Settings() )->get_settings();
		$saved_checks        = ( $settings_after_save->number_of_rows_in_table == $settings->number_of_rows_in_table && $settings_after_save->date_format == $settings->date_format && $settings_after_save->emails == $settings->emails );

		$this->assertTrue( $response['success'] && ! empty( $response['data'] ) && $saved_checks );
	}

	/**
	 * Test for get_settings().
	 */
	public function test_get_settings() {
		$settings_object = new Settings();
		$settings        = $settings_object->get_settings();
		$result          = ( ! empty( $settings->number_of_rows_in_table ) && ! empty( $settings->date_format ) && ! empty( $settings->emails ) );

		$this->assertTrue( $result );
	}
}
