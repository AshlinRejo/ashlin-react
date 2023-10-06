<?php
/**
 * Plugin core file
 *
 * @package AshlinReact
 */

namespace AshlinReact;

use AshlinReact\Admin\Installation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Plugin
 */
class Plugin {

	/**
	 * Class instance.
	 *
	 * @var Plugin $instance
	 * */
	protected static $instance = null;

	/**
	 * Plugin loaded state
	 *
	 * @var boolean $loaded
	 * */
	private $loaded = false;

	/**
	 * Get class instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Initialise the plugin
	 * */
	public function load() {
		if ( true === $this->loaded ) {
			return;
		}
		$this->load_text_domain();
		$this->register_events();
		$this->loaded = true;
	}

	/**
	 * Register events
	 * */
	private function register_events() {
		$event_classes = array(
			'\AshlinReact\Admin\Page',
		);
		foreach ( $event_classes as $event_class ) {
			( new $event_class() )->hooks();
		}
	}

	/**
	 * Load plugin text-domain
	 * */
	private function load_text_domain() {
		load_plugin_textdomain( 'ashlin-react', false, 'ashlin-react/languages/' );
	}

	/**
	 * While activate plugin
	 * */
	public function plugin_activated() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		( new Installation() )->process_plugin_installation();
	}
}
