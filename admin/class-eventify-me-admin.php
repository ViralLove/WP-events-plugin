<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Eventify_Me
 * @subpackage Eventify_Me/admin
 * @author     Your Name <email@example.com>
 */
class Eventify_Me_Admin {

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
	 * @param      string    $eventify_me       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $eventify_me, $version ) {
		$this->eventify_me = $eventify_me;
		$this->version = $version;

		// hooks & filters for duplicate event on admin page
        add_filter( 'bulk_actions-edit-eventify-me-events', [$this, 'register_bulk_action_for_duplicate_event'] );
        add_filter( 'handle_bulk_actions-edit-eventify-me-events', [$this, 'handle_bulk_action_for_duplicate_event'], 10, 3 );
        add_action( 'admin_notices', [$this, 'bulk_action_for_duplicate_event_admin_notice'] );

        //remove edit bulk action
        add_filter( 'bulk_actions-edit-eventify-me-events', [$this, 'remove_edit_bulk_action'] );

        // notice about event id on admin edit event page
        add_action( 'admin_notices', [$this, 'notice_about_event_id'] );

        if(!empty($_GET['post_type']) && $_GET['post_type'] === 'eventify-me-events') {
            //showing event id in admin list events page
            add_filter('manage_posts_columns', [$this, 'posts_columns_id'], 5);
            add_action('manage_posts_custom_column', [$this, 'posts_custom_id_columns'], 5, 2);
        }

        //add parents column to formats
        add_filter( 'manage_edit-event_formats_columns', [$this, 'add_parent_columns'] );
        add_filter( 'manage_event_formats_custom_column', [$this, 'add_parent_column_content'], 10 ,3 );

        //add parents column to thematics
        add_filter( 'manage_edit-event_thematics_columns', [$this, 'add_parent_columns'] );
        add_filter( 'manage_event_thematics_custom_column', [$this, 'add_parent_column_content'], 10 ,3 );

        // make paremt columns sortable
        add_filter( 'manage_edit-event_formats_sortable_columns', [$this, 'parent_column_set_sortable'] );
        add_filter( 'manage_edit-event_thematics_sortable_columns', [$this, 'parent_column_set_sortable'] );

        // added custom buttons to edit taxonomy of events
        add_action('event_formats_edit_form', [$this, 'add_custom_buttons_to_events_taxonomy'], 99, 2);
        add_action('event_thematics_edit_form', [$this, 'add_custom_buttons_to_events_taxonomy'], 99, 2);

        // change labels for admin menu
        add_action( 'admin_menu', [$this, 'change_events_submenu_label'] );

        // notice about event shortcodes on all events admin page
        add_action( 'admin_notices', [$this, 'notice_about_shortcodes'] );

        // redirect users with event manager role if they not connected to this event
        add_action( 'admin_init', [$this, 'redirect_not_connected_event_managers_to_event'] );

        //change row actions by user role
        add_filter('post_row_actions', [$this, 'change_row_actions_by_user_role'], 10, 2);

        //add link to events post type to admin bar
        add_action('admin_bar_menu', [$this, 'toolbar_link_to_eventify_me'], 50);

        // add event manager field by ajax
        add_action('wp_ajax_eventify_me_event_manager_field_item_ajax', [$this, 'eventify_me_event_manager_field_item_ajax']);

        //add classes for admin body
        add_filter('admin_body_class', [$this, 'add_class_role_to_body']);
        add_filter('body_class', [$this, 'add_class_role_to_body_front']);

        //redirect after event_manager login
        add_filter('login_redirect', [$this, 'redirect_after_event_manager_login'], 999, 3);
    }

    public function redirect_after_event_manager_login($redirect_to, $requested_redirect_to, $user) {
        if(!empty($user->roles) && in_array( 'eventify_me_manager', (array) $user->roles)){
            $redirect_to = home_url() . '/wp-admin/edit.php?post_type=eventify-me-events';
            return $redirect_to;
        }

        return home_url() . '/wp-admin';
    }

    public function add_class_role_to_body($classes) {
        $user = wp_get_current_user();

        if(in_array( 'eventify_me_manager', (array) $user->roles)) {
            $classes .= ' admin-body-role-eventify_me_manager ';
        }
        return $classes;
    }

    public function add_class_role_to_body_front($classes) {
        $user = wp_get_current_user();

        if(in_array( 'eventify_me_manager', (array) $user->roles)) {
            $classes[] = ' admin-body-role-eventify_me_manager ';
        }
        return $classes;
    }

    public function toolbar_link_to_eventify_me($wp_admin_bar) {
        $args = array(
            'id' => 'link_to_eventify_me_in_admin',
            'title' => 'Zeya4Events',
            'href' => home_url() . '/wp-admin/edit.php?post_type=eventify-me-events',
            'meta' => array(
                'title' => 'Zeya4Events'
            )
        );
        $wp_admin_bar->add_node($args);
    }

    public function change_row_actions_by_user_role($actions, $post) {
        //check for your post type
        if ($post->post_type == "eventify-me-events"){
            $user = wp_get_current_user();

            if(in_array( 'eventify_me_manager', (array) $user->roles)) {
                $event_managers_id = get_post_meta($post->ID, 'event_managers_id', true);

                if(!empty($event_managers_id) && !in_array($user->ID, $event_managers_id)) {
                    unset($actions['trash'], $actions['edit']);
                }
            }
        }
        return $actions;
    }

	public function redirect_not_connected_event_managers_to_event() {
        global $pagenow;
	    $user = wp_get_current_user();
        $screen = get_current_screen();

        if ( 'post.php' === $pagenow && isset($_GET['post']) && 'eventify-me-events' === get_post_type( $_GET['post'] ) && in_array( 'eventify_me_manager', (array) $user->roles)) {
            $event_managers_id = get_post_meta($_GET['post'], 'event_managers_id', true);
            if(!empty($event_managers_id) && !in_array($user->ID, $event_managers_id)) {
                wp_redirect('/wp-admin/edit.php?post_type=eventify-me-events');
                die;
            }
        }
    }

	public function notice_about_shortcodes() {
        global $my_admin_page, $post;
        $screen = get_current_screen();
        if ( is_admin() && ($screen->id == 'edit-eventify-me-events') ) {
//            global $post;
//            $id = $post->ID;
            echo '<div class="notice notice-info fade is-dismissible" style="border-left-color: #BB6ADF;">';
            echo '<p>';
            echo __('In order to add events content to your website, simply use shortcodes following our standards:', EVENTIFYME_TEXTDOMAIN);
            echo '</p>';
            echo '<p>';
                echo '<ol style="margin-top: 0; margin-bottom: 0;">';
                    echo '<li>' . '<strong>' . __('List all your events: ', EVENTIFYME_TEXTDOMAIN) . '</strong>' .'[events_list_view]<br>';
                    echo __('Attributes:', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('show_location="true/false"', EVENTIFYME_TEXTDOMAIN);
                    echo '</li>';

                    echo '<li>' . '<strong>' . __('Display specific event on a dedicated page: ', EVENTIFYME_TEXTDOMAIN) . '</strong>' . '[events_list_view]<br>';
                    echo __('Attributes:', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('show_location="true/false"', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('events_ids="1,2,3"', EVENTIFYME_TEXTDOMAIN);
                    echo '</li>';

                    echo '<li>' . '<strong>' . __('Display specific event’s booking section on a dedicated page: ', EVENTIFYME_TEXTDOMAIN) . '</strong>' . '[booking_event]<br>';
                    echo __('Attributes:', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('event_id="1"', EVENTIFYME_TEXTDOMAIN);
                    echo '</li>';
	
                    echo '<li>' . '<strong>' . __('Minimalistic event output: ', EVENTIFYME_TEXTDOMAIN) . '</strong>' . '[events_list_view_short]<br>';
                    echo __('Attributes:', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('numbers_of_events="3"', EVENTIFYME_TEXTDOMAIN) . '<br>';
                    echo __('link_to_all_events="/events"', EVENTIFYME_TEXTDOMAIN);
                    echo '</li>';
                echo '</ol>';
            echo '</p>';
            echo '</div>';
        }
    }

	public function change_events_submenu_label() {
        global $submenu;
        $user = wp_get_current_user();

        if(in_array( 'eventify_me_manager', (array) $user->roles))
            $submenu['edit.php?post_type=eventify-me-events'][5][0] = __('Events', EVENTIFYME_TEXTDOMAIN);
        else
            $submenu['edit.php?post_type=eventify-me-events'][0][0] = __('Events', EVENTIFYME_TEXTDOMAIN);

	}

	public function add_custom_buttons_to_events_taxonomy() {
        $screen = get_current_screen();
        if($screen->id === 'edit-event_formats' || $screen->id === 'edit-event_thematics') :
            $current_taxonomy = $screen->taxonomy;
	    ?>
        <div class="edit-tag-actions-custom">
            <span id="back-link">
                <a href="/wp-admin/edit-tags.php?taxonomy=<?php echo $current_taxonomy?>&post_type=eventify-me-events" class="back-link"><?php echo __('Back without saving', EVENTIFYME_TEXTDOMAIN)?></a>
            </span>

            <input type="submit" class="button button-primary" value="<?php echo __('Save and back', EVENTIFYME_TEXTDOMAIN)?>" id="save-and-return">
            <input type="hidden" name="all-taxonomy-page-url" value="<?php echo home_url()?>/wp-admin/edit-tags.php?taxonomy=<?php echo $current_taxonomy?>&post_type=eventify-me-events">

            <?php submit_button( __( 'Save' ), 'primary', null, false ); ?>
        </div>
        <?php endif;
    }

    public function parent_column_set_sortable( $columns ){
        $columns['parent'] = 'parent';
        return $columns;
    }

	public function add_parent_columns($columns) {
        $columns['parent'] = __('Parent', EVENTIFYME_TEXTDOMAIN);

        $result = [];
        foreach ($columns as $key => $value){
            if($key === 'name') {
                $result[$key] = $value;
                $result['parent'] = __('Parent', EVENTIFYME_TEXTDOMAIN);
            } else $result[$key] = $value;
        }

        $result['posts'] = __('Used in events', EVENTIFYME_TEXTDOMAIN);

        return $result;
    }

    public function add_parent_column_content( $content, $column_name, $term_id) {
        $term = get_term($term_id);
        if($term->parent === 0) return '—';
        $parent = get_term($term->parent);

        return !empty($parent) ? '<a href="' . get_edit_term_link($parent->term_id) . '">' . $parent->name . '</a>' : '—';
    }

	public function remove_edit_bulk_action($bulk_actions){
        unset($bulk_actions['edit']);
        return $bulk_actions;
    }

    //showing event id in admin list events page
    public function posts_columns_id($columns){
        $checkbox = array_slice( $columns , 0, 1 );
        $columns = array_slice( $columns , 1 );

        $id['event_id'] = __('ID', EVENTIFYME_TEXTDOMAIN);

        $columns = array_merge( $checkbox, $id, $columns );

        $event_manager['event_manager'] = __('Event manager', EVENTIFYME_TEXTDOMAIN);

        $date = array_slice($columns, -1);
        $columns = array_slice( $columns , 0, -1 );

        $columns = array_merge( $columns, $event_manager, $date );
        return $columns;
    }
    //showing event id in admin list events page
    public function posts_custom_id_columns($column_name, $id){
        if($column_name === 'event_id'){
            echo '<div class="event_id_wrap">';
            echo $id;
            echo '</div>';
        }

        if($column_name === 'event_manager') {
            $eventManagerIds = get_post_meta($id, 'event_managers_id', true);
            echo '<div class="event_manager_wrap">';
            if(!empty($eventManagerIds)) {
                foreach ($eventManagerIds as $eventManagerId){
                    $eventManager = get_user_by('id', $eventManagerId);
                    echo '<span>' . $eventManager->user_firstname . '</span><br>';
                    echo '<span>' . $eventManager->user_email . '</span><br>';
                }
            }
            echo '</div>';
        }
    }

    //notice about event id on admin edit event page
    public function notice_about_event_id() {
        global $my_admin_page, $post;
        $screen = get_current_screen();

        if ( is_admin() && ($screen->id == 'eventify-me-events') ) {
            global $post;
            $id = $post->ID;
            echo '<div class="notice notice-info fade is-dismissible" style="border-left-color: #BB6ADF;">';
                echo '<p>';
                echo sprintf(__('Event’s unique identifier: <strong>%s</strong> (used in the shortcodes for displaying specific event or limited list of events)', EVENTIFYME_TEXTDOMAIN), $id);
                echo '</p>';
                echo '<p class="info-about-shortcode">';
                echo sprintf(__('Short code to display this event separately: <span>[events_list_view events_ids="%s" show_location="false"]</span>', EVENTIFYME_TEXTDOMAIN), $id);
                echo '<a href="#" class="copy-shortcode-to-clickboard" title="' . __('Copy to clipboard', EVENTIFYME_TEXTDOMAIN) . '" data-type="event-page">' . Eventify_Me_Admin_Svg::getCopyIcon() . '</a>';
                echo '</p>';
                echo '<p class="info-about-shortcode">';
                echo sprintf(__('Short code to display the booking of this event: <span>[booking_event events_ids="%s"]</span>', EVENTIFYME_TEXTDOMAIN), $id);
                echo '<a href="#" class="copy-shortcode-to-clickboard" title="' . __('Copy to clipboard', EVENTIFYME_TEXTDOMAIN) . '" data-type="booking-page">' . Eventify_Me_Admin_Svg::getCopyIcon() . '</a>';
                echo '</p>';
                echo '<div style="display: none" class="message-about-copy"';
                echo ' data-event-page-text="' . __('Event page shortcode value successfully copied to clipboard', EVENTIFYME_TEXTDOMAIN) . '"';
                echo ' data-booking-page-text="' . __('Event booking page shortcode value successfully copied to clipboard', EVENTIFYME_TEXTDOMAIN) . '"';
                echo '>';
                echo '</div>';
            echo '</div>';
        }
    }

    //register duplicate event
	public function register_bulk_action_for_duplicate_event($bulk_actions) {
      $bulk_actions['duplicate_event'] = __( 'Duplicate event', EVENTIFYME_TEXTDOMAIN);
      return $bulk_actions;
    }

    //Handle duplicate event
    public function handle_bulk_action_for_duplicate_event( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'duplicate_event' ) {
            return $redirect_to;
        }
        foreach ( $post_ids as $post_id ) {
            $post = (array) get_post( $post_id ); // Post to duplicate.
            $metaFields = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($post_id);
            unset($post['ID']); // Remove id, wp will create new post if not set.

            $post['post_title'] = 'Copy ' . $post['post_title'];
            $post['post_status'] = 'draft';

            $new_post_id = wp_insert_post($post);


            if(!empty($metaFields['event_formats'])) {
                wp_set_post_terms( $new_post_id, array_column($metaFields['event_formats'], 'term_id'), 'event_formats' );
            } else wp_set_post_terms( $new_post_id, [], 'event_formats' );

            if(!empty($metaFields['event_thematics'])) {
                wp_set_post_terms( $new_post_id, array_column($metaFields['event_thematics'], 'term_id'), 'event_thematics' );
            } else wp_set_post_terms( $new_post_id, [], 'event_thematics' );

            // set sessions
            if(!empty($metaFields['event_sessions'])) {
                $sessionObject = new Event_Session();
                foreach ($metaFields['event_sessions'] as $session) {
                    $test = $sessionObject->set_session($new_post_id, $session['date'], $session['time_start'], $session['time_end'], $session['is_booking_enabled']);
                }
            }

            update_post_meta( $new_post_id, 'event_cover_single_page', !empty($metaFields['event_cover_single_page']) ? sanitize_text_field( $metaFields['event_cover_single_page'] ) : '' );
            update_post_meta( $new_post_id, 'event_cover_card', !empty($metaFields['event_cover_card']) ? sanitize_text_field( $metaFields['event_cover_card'] ) : '' );
            update_post_meta( $new_post_id, 'event_photos', !empty( $metaFields['event_photos'] ) ? sanitize_text_field( $metaFields['event_photos'] ) : '' );
            update_post_meta( $new_post_id, 'event_age_category', !empty($metaFields['event_age_category']) ? $metaFields['event_age_category'] : [] );
            update_post_meta( $new_post_id, 'event_children_allowed', !empty($metaFields['event_children_allowed']) ? sanitize_text_field( $metaFields['event_children_allowed'] ) : '' );
            update_post_meta( $new_post_id, 'event_is_free', !empty($metaFields['event_is_free']) ? sanitize_text_field( $metaFields['event_is_free'] ) : '' );
            update_post_meta( $new_post_id, 'event_language', !empty($metaFields['event_language']) ? $metaFields['event_language'] : [] );
            update_post_meta( $new_post_id, 'event_address', !empty($metaFields['event_address']) ? sanitize_text_field( $metaFields['event_address'] ) : '' );
            update_post_meta( $new_post_id, 'event_location_place_title', !empty($metaFields['event_location_place_title']) ? sanitize_text_field( $metaFields['event_location_place_title'] ) : '' );
            update_post_meta( $new_post_id, 'event_location_comments', !empty($metaFields['event_location_comments']) ? $metaFields['event_location_comments'] : '' );
            update_post_meta( $new_post_id, 'event_price', !empty($metaFields['event_price']) ? $metaFields['event_price'] : '' );
            update_post_meta( $new_post_id, 'event_managers_id', !empty($metaFields['event_managers_id']) ? $metaFields['event_managers_id'] : '' );
        }
        $redirect_to = add_query_arg( 'bulk_duplicated_posts', count( $post_ids ), $redirect_to );
        return $redirect_to;
    }

    //Notices duplicate event
    public function bulk_action_for_duplicate_event_admin_notice() {
        if ( ! empty( $_REQUEST['bulk_duplicated_posts'] ) ) {
            $emailed_count = intval( $_REQUEST['bulk_duplicated_posts'] );
            printf( '<div id="message" class="updated fade is-dismissible"><p>' .
                _n( 'Duplicated %s post.',
                    'Duplicated %s posts.',
                    $emailed_count,
                    EVENTIFYME_TEXTDOMAIN
                ) . '</p></div>', $emailed_count );
        }
    }

	/**
	 * Register the stylesheets for the admin area.
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

        wp_enqueue_style( $this->eventify_me . '-loader', plugin_dir_url( __FILE__ ) . 'css/loader.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->eventify_me . '-flatpickr', EVENTIFYME_DIR_URL . 'libraries/css/flatpickr.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->eventify_me, plugin_dir_url( __FILE__ ) . 'css/eventify-me-admin.css', array(), $this->version, 'all' );

        global $typenow;
        if ($typenow && $typenow === 'eventify-me-events') {
            wp_enqueue_style('thickbox');
        }

        // styles for plugin settings
        if(isset($_REQUEST['page']) && $_REQUEST['page'] === 'eventify-me-settings') {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', array(), $this->version, 'all');
            wp_enqueue_style( $this->eventify_me . '-settings', plugin_dir_url( __FILE__ ) . 'css/eventify-me-admin-settings.css', array(), $this->version, 'all' );
        }
	}

	/**
	 * Register the JavaScript for the admin area.
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

        global $typenow;
        if ($typenow && $typenow === 'eventify-me-events') {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');

            //for gallery
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-autocomplete');

            if ( ! did_action( 'wp_enqueue_media' ) )
                wp_enqueue_media();

            wp_enqueue_script( $this->eventify_me . '-flatpickr', EVENTIFYME_DIR_URL . 'libraries/js/flatpickr.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->eventify_me . '-input-masks', EVENTIFYME_DIR_URL . 'libraries/js/jquery.inputmask.min.js', array( 'jquery' ), $this->version, true );

            wp_enqueue_script( $this->eventify_me, plugin_dir_url( __FILE__ ) . 'js/eventify-me-admin.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->eventify_me, 'ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script( $this->eventify_me . '-gallery', plugin_dir_url( __FILE__ ) . 'js/eventify-me-admin-gallery.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, true );

            // scripts for plugin settings
            if(isset($_REQUEST['page']) && $_REQUEST['page'] === 'eventify-me-settings') {
                wp_enqueue_script( $this->eventify_me . '-settings', plugin_dir_url( __FILE__ ) . 'js/eventify-me-admin-settings.js', array('jquery', 'jquery-ui-tabs', 'wp-color-picker'), $this->version, true );
                wp_enqueue_script( $this->eventify_me . '-shortcode-preview', EVENTIFYME_DIR_URL . 'public/js/eventify-me-list-view-events.js', array( 'jquery' ), $this->version, false );
            }
        }
	}

	/*
	 * Register Events post type and taxonomies for it
	 */
	public function register_events_post_type(){
        $capabilities = array(
            'publish_posts' => 'publish_eventify_me_events',
            'edit_posts' => 'edit_eventify_me_events',
            'edit_others_posts' => 'edit_others_eventify_me_events',
            'delete_posts' => 'delete_eventify_me_events',
            'delete_others_posts' => 'delete_others_eventify_me_events',
            'read_private_posts' => 'read_private_eventify_me_events',
            'edit_post' => 'edit_eventify_me_events',
            'delete_post' => 'delete_eventify_me_events',
            'read_post' => 'read_eventify_me_events'
        );

        register_post_type( 'eventify-me-events', [
            'label'  => null,
            'labels' => [
                'name'               => __('Events', EVENTIFYME_TEXTDOMAIN),
                'singular_name'      => __('Event', EVENTIFYME_TEXTDOMAIN),
                'add_new'            => __('Add new Event', EVENTIFYME_TEXTDOMAIN),
                'add_new_item'       => __('Add new Event', EVENTIFYME_TEXTDOMAIN),
                'edit_item'          => __('Edit Event', EVENTIFYME_TEXTDOMAIN),
                'new_item'           => __('New Event', EVENTIFYME_TEXTDOMAIN),
                'view_item'          => __('View Event', EVENTIFYME_TEXTDOMAIN),
                'search_items'       => __('Search Events', EVENTIFYME_TEXTDOMAIN),
                'not_found'          => __('Events not found', EVENTIFYME_TEXTDOMAIN),
                'not_found_in_trash' => __('Events not found in the trash', EVENTIFYME_TEXTDOMAIN),
                'menu_name'          => __('Zeya4Events', EVENTIFYME_TEXTDOMAIN),
            ],
            'public'              => true,
            'capabilities'              => $capabilities,
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-calendar',
//            'menu_icon'           => EVENTIFYME_DIR_URL . 'public/img/acc-ticket.svg',
            'supports'            => [ 'title', 'editor' ],
            'rewrite' => array(
                'slug' => 'event'
            )
        ] );

        register_taxonomy( 'event_formats', [ 'eventify-me-events' ], [
            'labels'                => [
                'name'              => __('Event formats', EVENTIFYME_TEXTDOMAIN),
                'singular_name'     => __('Event format', EVENTIFYME_TEXTDOMAIN),
                'search_items'      => __('Search format', EVENTIFYME_TEXTDOMAIN),
                'all_items'         => __('All formats', EVENTIFYME_TEXTDOMAIN),
                'view_item '        => __('View format', EVENTIFYME_TEXTDOMAIN),
                'parent_item'       => __('Parent format', EVENTIFYME_TEXTDOMAIN),
                'edit_item'         => __('Edit format', EVENTIFYME_TEXTDOMAIN),
                'update_item'       => __('Update format', EVENTIFYME_TEXTDOMAIN),
                'add_new_item'      => __('Add new format', EVENTIFYME_TEXTDOMAIN),
                'new_item_name'     => __('New format name', EVENTIFYME_TEXTDOMAIN),
                'menu_name'         => __('Event formats', EVENTIFYME_TEXTDOMAIN),
                'back_to_items'     => __('Back to formats', EVENTIFYME_TEXTDOMAIN),
            ],
            'public'                => true,
            'hierarchical'          => true,
            'meta_box_cb'           => false,
        ] );

        register_taxonomy( 'event_thematics', [ 'eventify-me-events' ], [
            'labels'                => [
                'name'              => __('Event thematics', EVENTIFYME_TEXTDOMAIN),
                'singular_name'     => __('Event thematic', EVENTIFYME_TEXTDOMAIN),
                'search_items'      => __('Search thematics', EVENTIFYME_TEXTDOMAIN),
                'all_items'         => __('All thematics', EVENTIFYME_TEXTDOMAIN),
                'view_item '        => __('View thematic', EVENTIFYME_TEXTDOMAIN),
                'parent_item'       => __('Parent thematic', EVENTIFYME_TEXTDOMAIN),
                'edit_item'         => __('Edit thematic', EVENTIFYME_TEXTDOMAIN),
                'update_item'       => __('Update thematic', EVENTIFYME_TEXTDOMAIN),
                'add_new_item'      => __('Add new thematic', EVENTIFYME_TEXTDOMAIN),
                'new_item_name'     => __('New thematic name', EVENTIFYME_TEXTDOMAIN),
                'menu_name'         => __('Event thematics', EVENTIFYME_TEXTDOMAIN),
                'back_to_items'     => __('Back to thematics', EVENTIFYME_TEXTDOMAIN),
            ],
            'public'                => true,
            'hierarchical'          => true,
            'meta_box_cb'           => false,
        ] );
    }

    /*
     * Default terms for event_formats taxonomy. Will be on site always
     */
    public function set_defaults_terms_to_event_formats_taxonomy(){
        $taxonomyName = 'event_formats';

        $terms = [
            'regular-class' => __('Regular class', EVENTIFYME_TEXTDOMAIN),
            'workshop' => __('Workshop', EVENTIFYME_TEXTDOMAIN),
            'performance' => __('Performance', EVENTIFYME_TEXTDOMAIN),
            'dancing' => __('Dancing', EVENTIFYME_TEXTDOMAIN),
            'meditation' => __('Meditation', EVENTIFYME_TEXTDOMAIN),
            'music-jam' => __('Music jam', EVENTIFYME_TEXTDOMAIN),
            'lection' => __('Lection', EVENTIFYME_TEXTDOMAIN),
            'retreat' => __('Retreat', EVENTIFYME_TEXTDOMAIN),
        ];

        foreach ($terms as $slug => $name) {
            $term_exists = get_term_by('slug', $slug, $taxonomyName, ARRAY_A);
            if(!$term_exists) {
                wp_insert_term($name, $taxonomyName, [
                    'slug' => $slug,
                ]);
            }
        }
    }

    /*
      * Default terms for event_thematics taxonomy. Will be on site always
      */
    public function set_defaults_terms_to_event_thematics_taxonomy(){
        $taxonomyName = 'event_thematics';

        $terms = [
            'music' => __('Music', EVENTIFYME_TEXTDOMAIN),
            'visual-arts' => __('Visual arts', EVENTIFYME_TEXTDOMAIN),
            'body-practice' => __('Body practice', EVENTIFYME_TEXTDOMAIN),
            'dance' => __('Dance', EVENTIFYME_TEXTDOMAIN),
            'world-transition' => __('World transition', EVENTIFYME_TEXTDOMAIN),
            'spirituality' => __('Spirituality', EVENTIFYME_TEXTDOMAIN),
            'healing' => __('Healing', EVENTIFYME_TEXTDOMAIN)
        ];

        foreach ($terms as $slug => $name) {
            $term_exists = get_term_by('slug', $slug, $taxonomyName, ARRAY_A);
            if(!$term_exists) {
                wp_insert_term($name, $taxonomyName, [
                    'slug' => $slug,
                ]);
            }
        }
    }

    /*
     * Adding metaboxes with custom fields for eventify-me-events post type
    */
    public function add_metaboxes_for_events(){
        $screens = array( 'eventify-me-events' );
        add_meta_box( 'eventify_me_what_section', __('WHAT', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_what_meta_callback'], $screens, 'normal', 'high');
        add_meta_box( 'eventify_me_for_whom_section', __('FOR WHOM', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_for_whom_meta_callback'], $screens, 'normal', 'high');
        add_meta_box( 'eventify_me_when_section', __('WHEN', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_when_meta_callback'], $screens, 'normal', 'high');
        add_meta_box( 'eventify_me_where_section', __('WHERE', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_where_meta_callback'], $screens, 'normal', 'high');
        add_meta_box( 'eventify_me_price_section', __('PRICE', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_price_meta_callback'], $screens, 'normal', 'high');
        add_meta_box( 'eventify_me_side_section', __('Additional settings', EVENTIFYME_TEXTDOMAIN), [$this, 'eventify_me_side_meta_callback'], $screens, 'side', 'low'); // meta box for event manager
    }

    /*
    * Metabox section "side"
    */
    public function eventify_me_side_meta_callback ($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_side.php';
    }

    /*
    * Metabox section "WHAT"
    */
    public function eventify_me_what_meta_callback($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_what.php';
    }

    /*
   * Metabox section "FOR WHOM"
  */
    public function eventify_me_for_whom_meta_callback($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_for_whom.php';
    }

    /*
    * Metabox section "WHEN"
    */
    public function eventify_me_when_meta_callback($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_when.php';
    }

    /*
    * Metabox section "WHERE"
    */
    public function eventify_me_where_meta_callback($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_where.php';
    }

    /*
      * Metabox section "PRICE"
      */
    public function eventify_me_price_meta_callback($post, $meta) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/events_metabox_section_price.php';
    }

    /*
     * custom fields for gallery images
     */
    static function eventify_me_gallery_field( $name, $value = '' ) {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/eventify_me_gallery_field.php';
    }

    /*
     * custom fields for datetime from to repeater
     */
    static function eventify_me_timesession_field($field_name, $defaultValue){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/eventify_me_timesession_field.php';
    }

    /*
     * one item datetime input for datetime custom field
     */
    static function eventify_me_timesession_field_item($field_name, $val = '', $i = 0){
        require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/eventify_me_timesession_field_item.php';
    }

    /*
     * one item event manager input
     */
    static function eventify_me_event_manager_field_item($manager_id = ''){
        require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/eventify_me_event_manager_field_item.php';
    }

    /*
     * get one item datetime input by ajax
     */
    public function eventify_me_timesession_field_item_ajax(){
	    $i = (int) $_POST['currentCount'] + 1;
	    ob_start();
	    self::eventify_me_timesession_field_item($_POST['fieldName'], '', $i);
        $html = ob_get_contents();
	    ob_get_clean();

	    wp_send_json(array('status' => 'succes', 'html' => $html, 'newCount' => $i));
    }

    /*
   * get one item event manager input by ajax
   */
    public function eventify_me_event_manager_field_item_ajax (){
        ob_start();
        self::eventify_me_event_manager_field_item();
        $html = ob_get_contents();
        ob_get_clean();

        wp_send_json(array('status' => 'succes', 'html' => $html));
    }

    /*
     * save all custom fields for eventify-me-events post type
     */
    public function eventify_me_save_events_meta( $post_id ) {
        // if its autosave we don't do anything
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return;

        // check user permissions
        if( ! current_user_can( 'edit_post', $post_id ) )
            return;

        // check post type
        if(get_post_type($post_id) !== 'eventify-me-events')
            return;

        if(!empty($_POST['event_formats'])) {
            $_POST['tax_input']['event_formats'] = $_POST['event_formats'];
            wp_set_post_terms( $post_id, $_POST['event_formats'], 'event_formats' );
        } else wp_set_post_terms( $post_id, [], 'event_formats' );

        if(!empty($_POST['event_thematics'])) {
            $_POST['tax_input']['event_thematics'] = $_POST['event_thematics'];
            wp_set_post_terms( $post_id, $_POST['event_thematics'], 'event_thematics' );
        } else wp_set_post_terms( $post_id, [], 'event_thematics' );

        update_post_meta( $post_id, 'event_cover_single_page', !empty($_POST['event_cover_single_page']) ? sanitize_text_field( $_POST['event_cover_single_page'] ) : '' );
        update_post_meta( $post_id, 'event_cover_card', !empty($_POST['event_cover_card']) ? sanitize_text_field( $_POST['event_cover_card'] ) : '' );
        update_post_meta( $post_id, 'event_photos', !empty( $_POST['event_photos'] ) ? sanitize_text_field( $_POST['event_photos'] ) : '' );
        update_post_meta( $post_id, 'event_age_category', !empty($_POST['event_age_category']) ? $_POST['event_age_category'] : [] );
        update_post_meta( $post_id, 'event_children_allowed', !empty($_POST['event_children_allowed']) ? sanitize_text_field( $_POST['event_children_allowed'] ) : '' );
        update_post_meta( $post_id, 'event_is_free', !empty($_POST['event_is_free']) ? sanitize_text_field( $_POST['event_is_free'] ) : '' );
        update_post_meta( $post_id, 'event_language', !empty($_POST['event_language']) ? $_POST['event_language'] : [] );
        update_post_meta( $post_id, 'event_address', !empty($_POST['event_address']) ? sanitize_text_field( $_POST['event_address'] ) : '' );
        update_post_meta( $post_id, 'event_location_place_title', !empty($_POST['event_location_place_title']) ? sanitize_text_field( $_POST['event_location_place_title'] ) : '' );
        update_post_meta( $post_id, 'event_location_comments', !empty($_POST['event_location_comments']) ? $_POST['event_location_comments'] : '' );
        update_post_meta( $post_id, 'event_price', !empty($_POST['event_price']) ? $_POST['event_price'] : '' );
//        update_post_meta( $post_id, 'event_manager_id', !empty($_POST['event_manager_id']) ? $_POST['event_manager_id'] : '' );
        update_post_meta( $post_id, 'event_managers_id', !empty($_POST['event_managers_id']) ? array_unique(json_decode(stripslashes($_POST['event_managers_id']), true)) : '' );

        // update sessions
        if(!empty($_POST['event_sessions'])) {
            $sessionObject = new Event_Session();
            $sessionObject->update_or_set_sessions(json_decode(stripslashes($_POST['event_sessions']), true), $post_id);
        }

    }

}
