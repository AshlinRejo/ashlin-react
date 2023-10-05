<?php
/**
 * Plugin main file
 *
 * @package AshlinReact
 */

/**
 * Plugin name: AshlinReact
 * Description: A plugin with react UI at back-end.
 * Author: Ashlin
 * Author URI: https://github.com/AshlinRejo
 * Version: 1.0
 * Slug: ashlin-react
 * Text Domain: ashlin-react
 * Domain Path: languages
 * Requires at least: 5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ASHLIN_REACT_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
define( 'ASHLIN_REACT_PHP_VERSION', '7.2' );
define( 'ASHLIN_REACT_WP_VERSION', '5.0' );

require ASHLIN_REACT_PATH . 'inc/ashlin-react-requirement-checks.php';

// Checks plugin requirement.
if ( ( new Ashlin_React_Requirement_Checks() )->check() ) {
    // Composer autoload.
    if ( file_exists( ASHLIN_REACT_PATH . 'vendor/autoload.php' ) ) {
        require ASHLIN_REACT_PATH . 'vendor/autoload.php';
    }

    // while activate plugin.
    register_activation_hook( __FILE__, array( AshlinReact\Plugin::instance(), 'plugin_activated' ) );

    add_action( 'plugins_loaded', array( AshlinReact\Plugin::instance(), 'load' ) );
}