<?php
/**
 * Admin Page controller
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
class Page {

	/**
	 * Register the event
	 * */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * For adding admin menu
	 * */
	public function add_menu() {
		if ( ! is_admin() ) {
			return;
		}

		add_menu_page(
			__( 'WP Sitemap', 'ashlin-react' ),
			'AshlinReact',
			'manage_options',
			'ashlin-react',
			array( $this, 'load_menu_content' ),
			'',
			6
		);
	}

	/**
	 * Admin menu content
	 * */
	public function load_menu_content() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		include_once 'templates/dashboard.php';
	}
}
