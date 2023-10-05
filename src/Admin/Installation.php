<?php
/**
 * Admin Installation controller
 *
 * @package AshlinReact
 */

namespace AshlinReact\Admin;

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
	}
}