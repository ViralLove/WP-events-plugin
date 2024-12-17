<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Eventify_Me
 * @subpackage Eventify_Me/includes
 * @author     Your Name <email@example.com>
 */
class Eventify_Me {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Eventify_Me_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $eventify_me    The string used to uniquely identify this plugin.
	 */
	protected $eventify_me;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EVENTIFYME_VERSION' ) ) {
			$this->version = EVENTIFYME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->eventify_me = 'eventify-me';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Eventify_Me_Loader. Orchestrates the hooks of the plugin.
	 * - Eventify_Me_i18n. Defines internationalization functionality.
	 * - Eventify_Me_Admin. Defines all hooks for the admin area.
	 * - Eventify_Me_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        if(!class_exists('WP_List_Table')){
            require_once( ABSPATH . 'wp-admin/includes/screen.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

        /**
         * The class with svg icons.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventify-me-admin-svg.php';

        /**
         * Class for license
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventify-me-license.php';

        /**
         * Class for user plugin setting
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventify-me-settings.php';

        /**
         * The class with helpers methods.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventify-me-helpers.php';

        /**
         * The class with emails methods.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventify-me-emails.php';

        /*
         * Load entities classes
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/entities/load_entities.php';

        /**
         * The class for filters events in admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventify-me-filter-events-admin.php';

        /**
         * Class for list bookings in admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventify-me-booking-list.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventify-me-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventify-me-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventify-me-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-eventify-me-public.php';

		$this->loader = new Eventify_Me_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Eventify_Me_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Eventify_Me_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Eventify_Me_Admin( $this->get_eventify_me(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_events_post_type' ); // register events post type
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_metaboxes_for_events' ); // adding metaboxes for events post type
		$this->loader->add_action( 'save_post', $plugin_admin, 'eventify_me_save_events_meta' );// save meta fields for events on save post
		$this->loader->add_action( 'admin_init', $plugin_admin, 'set_defaults_terms_to_event_formats_taxonomy');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'set_defaults_terms_to_event_thematics_taxonomy');
		$this->loader->add_action('wp_ajax_eventify_me_timesession_field_item_ajax', $plugin_admin, 'eventify_me_timesession_field_item_ajax');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Eventify_Me_Public( $this->get_eventify_me(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_eventify_me() {
		return $this->eventify_me;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Eventify_Me_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
