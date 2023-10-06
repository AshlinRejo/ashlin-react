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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Include javascript files
	 * */
	public function enqueue_scripts() {
		wp_enqueue_script( 'ashlin-react', ASHLIN_REACT_URL . 'dist/admin.js', array( 'wp-element' ), ASHLIN_REACT_VERSION, true );
		wp_localize_script(
			'ashlin-react',
			'ashlinReact',
			array(
				'title' => esc_html__( 'AshlinReact', 'ashlin-react' ),
			)
		);
	}

	/**
	 * For adding admin menu
	 * */
	public function add_menu() {
		if ( ! is_admin() ) {
			return;
		}

		add_menu_page(
			esc_html__( 'AshlinReact', 'ashlin-react' ),
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
