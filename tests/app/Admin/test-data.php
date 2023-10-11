<?php
/**
 * Admin Data test
 *
 * @package AshlinReact
 */

/**
 * Admin Data test case.
 */
class Date_Test extends WP_Ajax_UnitTestCase {

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
		$ajax_action        = $this->is_hook_registered( 'wp_ajax_ashlin_react_get_data', 10, 'AshlinReact\Admin\Data', 'get_data' );
		$actions_registered = ( true === $ajax_action );
		$this->assertTrue( $actions_registered );
	}

	/**
	 * Test for get_data() ajax call.
	 */
	public function test_get_data() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		// Set GET values.
		$_GET = array(
			'action'      => 'ashlin_react_get_data',
			'_ajax_nonce' => wp_create_nonce( 'ashlin-react-ajax-nonce' ),
		);

		$this->make_ajax_call( 'ashlin_react_get_data' );

		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$this->assertTrue( $response['success'] && ! empty( $response['data'] ) );
	}

	/**
	 * Test get_data() for table ajax call.
	 */
	public function test_get_data_for_table() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		// Set GET values.
		$_GET = array(
			'action'      => 'ashlin_react_get_data',
			'_ajax_nonce' => wp_create_nonce( 'ashlin-react-ajax-nonce' ),
			'type'        => 'table',
		);

		$this->make_ajax_call( 'ashlin_react_get_data' );

		// Get the results.
		$response = json_decode( $this->_last_response, true );

		$this->assertTrue( $response['success'] && ! empty( $response['data']['title'] ) );
	}
}
