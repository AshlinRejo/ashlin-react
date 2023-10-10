<?php
/**
 * Admin Installation controller
 *
 * @package AshlinReact
 */

namespace AshlinReact\Admin;

use PhpParser\Node\Expr\Cast\Object_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Plugin installation
 */
class Installation {

	/**
	 * Process plugin installation
	 * */
	public function process_plugin_installation() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$this->add_default_options();
	}

	/**
	 * Add default option values if required
	 * */
	private function add_default_options() {
		$settings = get_option( 'ashlin_react_settings' );
		if ( false === $settings ) {
			$default_settings = Settings::get_default_settings();
			update_option( 'ashlin_react_settings', $default_settings );
		}
	}
}
