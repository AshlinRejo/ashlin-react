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

	/**
	 * Set up
	 * */
	public function set_up() {
		parent::set_up();

		// Create a user with nicename 'Ashlin'.
		$user_id = $this->factory->user->create(
			array(
				'user_nicename' => 'Ashlin',
				'role'          => 'administrator',
			)
		);

		// Set current user as 'Ashlin' so this user will have capability 'manage_options'.
		wp_set_current_user( $user_id );
		update_option( 'new_admin_email', 'ashlin@test.com' );
	}

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
