<?php
/**
 * Admin Page test
 *
 * @package AshlinReact
 */

/**
 * Admin Page test case.
 */
class Page_Test extends WP_UnitTestCase {

	use Ashlin_React_Test_Set_Up;
	use Ashlin_React_Test_Hook_Registered;

	/**
	 * Test for hooks().
	 */
	public function test_hooks() {
		// Verify actions.
		$admin_menu_action          = $this->is_hook_registered( 'admin_menu', 10, 'AshlinReact\Admin\Page', 'add_menu' );
		$enqueue_scripts_for_style  = $this->is_hook_registered( 'admin_enqueue_scripts', 10, 'AshlinReact\Admin\Page', 'enqueue_styles' );
		$enqueue_scripts_for_script = $this->is_hook_registered( 'admin_enqueue_scripts', 10, 'AshlinReact\Admin\Page', 'enqueue_scripts' );
		$actions_registered         = ( true === $admin_menu_action && true === $enqueue_scripts_for_style && true === $enqueue_scripts_for_script );
		$this->assertTrue( $actions_registered );
	}
}
