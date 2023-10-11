<?php
/**
 * Ashlin_React_Test_Set_Up trait
 *
 * @package AshlinReact
 */

/**
 * Ashlin_React_Test_Set_Up trait.
 */
trait Ashlin_React_Test_Set_Up {

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
}
