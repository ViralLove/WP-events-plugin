<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 * @author     Your Name <email@example.com>
 */
class Eventify_Me_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::create_tables(); // create tables for plugin
        self::update_tables(); // add column to tables if needed
        Eventify_Me_Settings::set_default_booking_settings(); // set default plugin settings
        Eventify_Me_Settings::set_default_email_settings(); // set default plugin settings
        self::create_role_eventify_me_manager(); // create event manager role
        self::add_capabilities_for_eventify_me_to_admin(); // add cap to admin for eventify-me-events posts
	}

    static function create_tables(){
        self::table_creation_event_session();
        self::table_creation_booking_order();
        self::table_creation_event_session_booking();
    }

    static function update_tables() {
	    // examples
//        self::addColumnToTable('cars_vehicle_products', 'type_id', 'int NULL AFTER `about_exp`');
//        addColumnToTable('cars_events', 'type_id', 'int NULL AFTER `place_id`');
//        addColumnToTable('cars_booked_cars', 'is_active', 'ENUM(\'active\', \'inactive\') DEFAULT \'active\' AFTER `timeslot_item_id`');
//        addColumnToTable('cars_signatures', 'signature_theme_path', 'text CHARACTER SET utf8 COLLATE utf8_general_ci AFTER `dokusign_id`');
//        addColumnToTable('cars_signatures', 'signature_file_theme_path', 'text CHARACTER SET utf8 COLLATE utf8_general_ci AFTER `signature_theme_path`');
    }

    static function addColumnToTable($tableName, $columnName, $typeAfterString){
        global $wpdb;
        $dbname = $wpdb->dbname;
        $marks_table_name = $wpdb->prefix . $tableName;

        $is_col = $wpdb->get_results(  "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name` = '{$marks_table_name}' AND `TABLE_SCHEMA` = '{$dbname}' AND `COLUMN_NAME` = '{$columnName}'"  );

        if( empty($is_col) ) {
            $add_column = "ALTER TABLE `{$marks_table_name}` ADD `{$columnName}` {$typeAfterString}; ";
            $wpdb->query($add_column);
        }

        return true;
    }

    static function table_creation_event_session() {
        global $wpdb;
        $prefix = $wpdb->prefix . 'eventify_me_';
        $charset_collate = $wpdb->get_charset_collate();

        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$prefix}event_session` (
          `id` int NOT NULL AUTO_INCREMENT,
          `event_id` int  NOT NULL,
          `date` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `time_start` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `time_end` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `is_booking_enabled` ENUM('yes', 'no'),
          `date_added` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate");
    }

    static function table_creation_booking_order() {
        global $wpdb;
        $prefix = $wpdb->prefix . 'eventify_me_';
        $charset_collate = $wpdb->get_charset_collate();

        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$prefix}booking_order` (
          `id` int NOT NULL AUTO_INCREMENT,
          `order_identifier` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `event_id` int  NOT NULL,
          `status` ENUM('initiated', 'tickets', 'contact_details', 'confirmed') NOT NULL,
          `user_email` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `user_phone` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `user_comments` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `confirmed_on` datetime NULL,
          `date_added` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate");
		
		$existing_columns = $wpdb->get_col("DESC {$prefix}booking_order", 0);
		
		if(!in_array('user_first_name', $existing_columns)) {
			$wpdb->query("ALTER TABLE {$prefix}booking_order ADD user_first_name text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		}
		
		if(!in_array('user_last_name', $existing_columns)) {
			$wpdb->query("ALTER TABLE {$prefix}booking_order ADD user_last_name text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		}
    }

    static function table_creation_event_session_booking() {
        global $wpdb;
        $prefix = $wpdb->prefix . 'eventify_me_';
        $charset_collate = $wpdb->get_charset_collate();

        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$prefix}event_session_booking` (
          `id` int NOT NULL AUTO_INCREMENT,
          `event_session_id` int  NOT NULL,
          `booking_order_id` int  NOT NULL,
          `date` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `time_start` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `time_end` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `date_added` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate");
    }

    static function create_role_eventify_me_manager () {
        if(empty($GLOBALS['wp_roles']->is_role( 'eventify_me_manager' ))) {
            add_role( 'eventify_me_manager', __('Event manager', EVENTIFYME_TEXTDOMAIN),
                array(
                    'upload_files' => true,
                    'edit_posts' => true,
                    'read_eventify_me_events' => true,
                    'publish_eventify_me_events' => true,
                    'edit_eventify_me_events' => true,
                    'edit_others_eventify_me_events' => true,
                    'delete_eventify_me_events' => true,
                    'delete_others_eventify_me_events' => true,
                    'read_private_eventify_me_events' => true,
                    'edit_eventify_me_events' => true,
                    'delete_eventify_me_events' => true,
                )
            );
        }
    }

    static function add_capabilities_for_eventify_me_to_admin() {
        $role = get_role( 'administrator' );
        $role->add_cap('publish_eventify_me_events');
        $role->add_cap('edit_eventify_me_events');
        $role->add_cap('edit_others_eventify_me_events');
        $role->add_cap('delete_eventify_me_events');
        $role->add_cap('delete_others_eventify_me_events');
        $role->add_cap('read_private_eventify_me_events');
        $role->add_cap('edit_eventify_me_events');
        $role->add_cap('delete_eventify_me_events');
        $role->add_cap('read_eventify_me_events');

        $role = get_role( 'eventify_me_manager' );
        $role->add_cap('upload_files');
    }

}
