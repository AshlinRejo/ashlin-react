<?php
/**
 * Admin Installation test
 *
 * @package AshlinReact
 */

use AshlinReact\Admin\Settings;
use AshlinReact\Admin\Installation;

/**
 * Installation test case.
 */
class Installation_Test extends WP_UnitTestCase {

	use Ashlin_React_Test_Set_Up;

	/**
	 * Test for process_plugin_installation().
	 */
	public function test_process_plugin_installation() {

		delete_option( 'ashlin_react_settings' );
		$installation = new Installation();
		$installation->process_plugin_installation();

		$settings         = get_option( 'ashlin_react_settings' );
		$default_settings = Settings::get_default_settings();

		$this->assertTrue(
			$settings->number_of_rows_in_table === $default_settings->number_of_rows_in_table
			&& $settings->date_format === $default_settings->date_format && $settings->emails === $default_settings->emails
		);
	}
}
