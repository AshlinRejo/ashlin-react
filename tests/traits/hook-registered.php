<?php
/**
 * Ashlin_React_Test_Hook_Registered trait
 *
 * @package AshlinReact
 */

/**
 * Ashlin_React_Test_Hook_Registered trait.
 */
trait Ashlin_React_Test_Hook_Registered {

	/**
	 * For checking hook registered
	 *
	 * @param string  $event_name Event name.
	 * @param integer $priority Event priority.
	 * @param string  $class_name Call back class name.
	 * @param string  $method_name Call back method name.
	 * @return boolean
	 */
	public function is_hook_registered( $event_name, $priority, $class_name, $method_name ) {
		global $wp_filter;
		if ( isset( $wp_filter[ $event_name ] ) && isset( $wp_filter[ $event_name ][ $priority ] ) ) {
			foreach ( $wp_filter[ $event_name ][ $priority ] as $callback_details ) {
				if ( isset( $callback_details['function'] ) && isset( $callback_details['function'][0] ) ) {
					if ( is_object( $callback_details['function'][0] ) ) {
						if ( get_class( $callback_details['function'][0] ) === $class_name && $callback_details['function'][1] === $method_name ) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}
}
