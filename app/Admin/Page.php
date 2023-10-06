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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Include CSS files
	 * */
	public function enqueue_styles() {
		wp_enqueue_style( 'ashlin-react', ASHLIN_REACT_URL . 'assets/css/admin.css', array(), ASHLIN_REACT_VERSION, 'all' );
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
				'title_text'        => esc_html__( 'AshlinReact', 'ashlin-react' ),
				'tab_table_text'    => esc_html__( 'Table', 'ashlin-react' ),
				'tab_graph_text'    => esc_html__( 'Graph', 'ashlin-react' ),
				'tab_settings_text' => esc_html__( 'Settings', 'ashlin-react' ),
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
			'dashicons-chart-bar',
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
