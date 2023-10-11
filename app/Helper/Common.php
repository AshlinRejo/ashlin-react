<?php
/**
 * Helper common
 *
 * @package AshlinReact
 */

namespace AshlinReact\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Common
 */
class Common {

	/**
	 * Check current user has administrator capability
	 *
	 * @return boolean
	 * */
	public static function is_administrator() {
		return current_user_can( 'manage_options' );
	}
}
