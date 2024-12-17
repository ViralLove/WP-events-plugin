<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 * @author     Your Name <email@example.com>
 */
class Eventify_Me_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        self::remove_role_eventify_me_manager();
	}

	static function remove_role_eventify_me_manager () {
//        remove_role( 'eventify_me_manager' );
    }

}
