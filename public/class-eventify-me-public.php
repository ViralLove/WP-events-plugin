<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/public
 * @author     Your Name <email@example.com>
 */
class Eventify_Me_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $eventify_me    The ID of this plugin.
	 */
	private $eventify_me;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $eventify_me       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $eventify_me, $version ) {

		$this->eventify_me = $eventify_me;
		$this->version = $version;

        // shortcodes for list events
        add_shortcode( 'events_list_view', [$this, 'shortcode_events_list_view'] );
        add_shortcode( 'events_list_view_short', [$this, 'shortcode_events_list_view_short'] );
        // connect template for single eventify-me-events post
        add_filter( 'single_template', [$this, 'eventify_me_post_type_single_template'] );

        //shortcode for booking event functionality
        add_shortcode( 'booking_event', [$this, 'shortcode_booking_event'] );

        // create url for bookings
        add_action( 'init', [$this, 'add_url_for_booking'] );
        add_filter('template_include', [$this, 'filter_template'], 99);
        add_filter('pre_get_document_title', [$this, 'title_page_for_booking_pages'], 10);
	}

    public function title_page_for_booking_pages($title) {

        if (get_query_var('event_booking') && get_query_var('event_booking') === 'booking') {
            $slug = get_query_var('post');
            $post = get_page_by_path( $slug, OBJECT, 'eventify-me-events' );
            if(!empty($post)) {
                $title = $post->post_title . ' - ' . __('Booking', EVENTIFYME_TEXTDOMAIN);
            }
        }

        return $title;
    }

    public function filter_template($template){
        if (get_query_var('event_booking') && get_query_var('event_booking') === 'booking') {
            return plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/booking-page.php';
        }
        return $template;
    }

    function add_url_for_booking(){
        add_rewrite_rule( '^(event)/([^/]*)/([^/]*)/?', 'index.php?post_type=$matches[1]&post=$matches[2]&event_booking=$matches[3]', 'top' );

        add_filter( 'query_vars', function( $vars ){
            $vars[] = 'post_type';
            $vars[] = 'post';
            $vars[] = 'event_booking';
            return $vars;
        } );
    }

    // connect template for single eventify-me-events post
    public function eventify_me_post_type_single_template($template) {
        global $post;
        if ( 'eventify-me-events' === $post->post_type && (locate_template( array( 'single-eventify-me.php' ) ) !== $template || empty($template)) ) {
            return plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/single-eventify-me.php';
        }

        return $template;
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Eventify_Me_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Eventify_Me_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_style( $this->eventify_me, plugin_dir_url( __FILE__ ) . 'css/eventify-me-list-view-events.css', array(), $this->version, 'all' );
//        wp_enqueue_style( 'eventify-me-google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap', false );

//        wp_enqueue_style( $this->eventify_me . '-loader', plugin_dir_url( __FILE__ ) . 'css/loader.css', array(), $this->version, 'all' );

        if(is_singular('eventify-me-events')){
            wp_enqueue_style( $this->eventify_me . '-swiper', EVENTIFYME_DIR_URL . 'libraries/css/swiper.min.css', array(), $this->version, 'all' );
        }

        wp_enqueue_style( $this->eventify_me . '-main-styles', EVENTIFYME_DIR_URL . 'public/css/main-styles.css', array(), $this->version, 'all' );

    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Eventify_Me_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Eventify_Me_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->eventify_me, plugin_dir_url( __FILE__ ) . 'js/eventify-me-list-view-events.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->eventify_me . '-input-masks', EVENTIFYME_DIR_URL . 'libraries/js/jquery.inputmask.min.js', array( 'jquery' ), $this->version, true );

        if(is_singular('eventify-me-events')){
            wp_enqueue_script( $this->eventify_me . '-swiper', EVENTIFYME_DIR_URL . 'libraries/js/swiper.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->eventify_me . '-smooth-scroll', EVENTIFYME_DIR_URL . 'libraries/js/smooth-scroll.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->eventify_me . '-single', plugin_dir_url( __FILE__ ) . 'js/single-eventify-me.js', array( 'jquery' ), $this->version, false );
        }

	}

	public function shortcode_booking_event( $atts ) {
        // default attrs
        $atts = shortcode_atts( array(
            'event_id' => false,
            'type' => 'main'
        ), $atts );

        $settingsObject = new Eventify_Me_Settings();
        if ($atts['type'] === 'preview') $settings = $settingsObject->get_visual_settings('preview');
        else $settings = $settingsObject->get_visual_settings();

        $id = 'booking-shortocde-' . substr(str_shuffle(MD5(microtime())), 0, 10);

        if($atts['type'] === 'main') {
            if($atts['event_id'] === false || empty($atts['event_id'])) return __('Attribute ', EVENTIFYME_TEXTDOMAIN) . ' <strong>event_id</strong> ' . __('not specified.');
            $event = get_post($atts['event_id']);
            if(empty($event) || $event->post_type !== 'eventify-me-events' || $event->post_status !== 'publish') return __('Event with ID ', EVENTIFYME_TEXTDOMAIN) . ' <strong>' . $atts['event_id'] . '</strong> ' . __('not found.');
        } else {
            $events = Eventify_Me_Helpers::getEvents(['posts_per_page' => 1]);
            $event = null;
            if(!empty($events)) $event = $events[0];
            if(empty($event) || $event->post_type !== 'eventify-me-events' || $event->post_status !== 'publish') return __('Event with available bookings not found.', EVENTIFYME_TEXTDOMAIN);
        }


        ob_start();
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/css/booking-shortcode-styles.php';
        if($atts['type'] === 'main') {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/booking-shortcode.php';
        } else {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/booking-shortcode_preview.php';
        }

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/loader.php';
        if($atts['type'] === 'main'): ?>
            <script>
                <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/js/booking-shortcode.js';?>
            </script>
        <?php endif;
        $html = ob_get_contents();
        ob_get_clean();

        return $html;
    }

    /*
     * shortcode shows t of events
     * Example default: [events_list_view]
     * Example with certain IDS: [events_list_view events_ids="13,25"]
     * Example with other parameters: [events_list_view show_location="true/false"]
     * */
    public function shortcode_events_list_view( $atts ){
        // default attrs
        $atts = shortcode_atts( array(
            'show_location' => true,
            'events_ids' => [],
            'type' => 'main'
        ), $atts );

        $settingsObject = new Eventify_Me_Settings();
        if ($atts['type'] === 'preview') $settings = $settingsObject->get_visual_settings('preview');
        else $settings = $settingsObject->get_visual_settings();

        if(!empty($atts['events_ids'])) {
            $event_ids = explode(',', $atts['events_ids']);
            $events = Eventify_Me_Helpers::sortEvents(Eventify_Me_Helpers::getEvents(['post__in' => $event_ids]), 'current');
        }

        if($atts['show_location'] === true || $atts['show_location'] === 'true') $atts['show_location'] = true;
        else if ($atts['show_location'] === false || $atts['show_location'] === 'false') $atts['show_location'] = false;
        else $atts['show_location'] = true;

        $id = 'events-list-' . substr(str_shuffle(MD5(microtime())), 0, 10);

        if(empty($event_ids) && empty($events)) {
            $current_events_ids = Eventify_Me_Helpers::getEventsIdsByPeriod('current');
            $current_events_ids = !empty($current_events_ids) ? $current_events_ids : [0];

            if(EVENTIFYME_IS_LICENSED) {
                $past_events_ids = Eventify_Me_Helpers::getEventsIdsByPeriod('past');
                $past_events_ids = !empty($past_events_ids) ? $past_events_ids : [0];
                $past_events = Eventify_Me_Helpers::sortEvents(Eventify_Me_Helpers::getEvents(['post__in' => $past_events_ids]), 'past');
            }

            $current_events = Eventify_Me_Helpers::sortEvents(Eventify_Me_Helpers::getEvents(['post__in' => $current_events_ids]), 'current');
        }

        ob_start();
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/css/eventify-me-list-view-events-styles.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/eventify-me-list-view-events.php';?>
        <script>
            (function( $ ) {
                'use strict';

                function setCookie(name, value, days) {
                    var expires = "";
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toUTCString();
                    }
                    document.cookie = name + "=" + (value || "") + expires + "; path=/";
                }

                $('body').on('click', '.all-event-item .all-event-item__link',function (){
                    setCookie('show_location', '<?php echo $atts['show_location']?>');
                })
            })( jQuery );
        </script>
        <?php $html = ob_get_contents();
        ob_get_clean();

        return $html;
    }

  /*
  * shortcode shows only future events with minimized design
  * Example default: [events_list_view_short]
  * Example with a certain number of events: [events_list_view_short numbers_of_events="10"]
  * */
  public function shortcode_events_list_view_short( $atts ){
    // default attrs
    $atts = shortcode_atts( array(
      'numbers_of_events' => '3',
      'link_to_all_events' => home_url('events'),
      'type' => 'main'
    ), $atts );

    $settingsObject = new Eventify_Me_Settings();
    if ($atts['type'] === 'preview') $settings = $settingsObject->get_visual_settings('preview');
    else $settings = $settingsObject->get_visual_settings();

    $id = 'events-list-' . substr(str_shuffle(MD5(microtime())), 0, 10);

    $current_events_ids = Eventify_Me_Helpers::getEventsIdsByPeriod('current');
    $current_events_ids = !empty($current_events_ids) ? $current_events_ids : [0];
    $current_events = Eventify_Me_Helpers::sortEvents(Eventify_Me_Helpers::getEvents(['post__in' => $current_events_ids]), 'current');

    ob_start();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/css/eventify-me-list-view-short-events-styles.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/eventify-me-list-view-short-events.php';?>
    <?php $html = ob_get_contents();
    ob_get_clean();

    return $html;
  }

}
