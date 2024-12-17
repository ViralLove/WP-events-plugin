<?php
/*
 * Class for user plugin setting
 */
class Eventify_Me_Settings {
    public $visual_settings;
    public $visual_settings_preview;

    public function __construct() {
        $this->visual_settings = get_option( 'eventify_me_visual_settings' );

        if(empty($this->visual_settings)) $this->visual_settings = $this->set_default_visual_settings();

        if(!wp_doing_ajax()){
            update_option('eventify_me_visual_settings_preview', $this->visual_settings);
            $this->visual_settings_preview = $this->visual_settings;
        } else $this->visual_settings_preview = get_option( 'eventify_me_visual_settings_preview' );

        //admin_menu
        add_action( 'admin_menu', [$this, 'settings_menu'] );

        //ajax methods
        add_action('wp_ajax_update_visual_settings', [$this, 'update_visual_settings']);
        add_action('wp_ajax_update_booking_settings', [$this, 'update_booking_settings']);
        add_action('wp_ajax_update_email_settings', [$this, 'update_email_settings']);
    }

    public function settings_menu() {
        add_submenu_page(
            'edit.php?post_type=eventify-me-events',
            __( 'Settings', EVENTIFYME_TEXTDOMAIN ),
            __( 'Settings', EVENTIFYME_TEXTDOMAIN ),
            'manage_options',
            'eventify-me-settings',
            [$this, 'settings_menu_contents'],
            5
        );
    }

    public function settings_menu_contents() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings/eventify_me_settings_layout.php';
    }

    private function set_default_visual_settings(){
        $default_settings = [
            'color_1' => '#BB6ADF',
            'color_2' => '#484848',
            'color_3' => '#169688',
            'color_4' => '#DBE0E0',
            'color_5' => '#fff',
            'color_6' => '#AAAAAA',
            'color_7' => '#4A525D',
            'color_8' => '#DAEEEC',
            'color_9' => '#FF794E',
            'color_10' => '#000000',
            'color_11' => '#E6F1F0',
            'color_12' => '#474747',
            'font_family' => 'open_sans',
        ];

        update_option('eventify_me_visual_settings', $default_settings);

        return $default_settings;
    }

    static function set_default_booking_settings() {
        $default_settings = [
            'after_booking_confirmed_redirect' => 'redirect_to_event_page',
        ];

        if(!empty(self::get_booking_settings())) return self::get_booking_settings();

        update_option('eventify_me_booking_settings', $default_settings);

        return $default_settings;
    }

    static function set_default_email_settings() {
        $default_settings = [
            'eventify_me_email_from_name' => get_bloginfo(),
            'eventify_me_email_from_address' => 'example@gmail.com',
            'eventify_me_email_to_user_subject' => __('Thank you for your booking!', EVENTIFYME_TEXTDOMAIN),
            'eventify_me_email_to_user_text' => __('Thank you for your booking!', EVENTIFYME_TEXTDOMAIN),
            'eventify_me_email_to_event_managers_subject' => __('You have new booking on your event!', EVENTIFYME_TEXTDOMAIN),
            'eventify_me_email_to_event_managers_text' => __('You have new booking on your event!', EVENTIFYME_TEXTDOMAIN),
        ];

        if(!empty(self::get_email_settings())) return self::get_email_settings();

        update_option('eventify_me_email_settings', $default_settings);

        return $default_settings;
    }

    static function get_booking_settings() {
        return get_option('eventify_me_booking_settings');
    }

    static function get_email_settings() {
        return get_option('eventify_me_email_settings');
    }

    public function get_visual_settings($type = 'main'){
        if($type === 'preview') return $this->visual_settings_preview;
        return $this->visual_settings;
    }

    public function update_visual_settings() {
        $data = array_column($_POST['preview_settings'], 'value', 'name');

        if($_POST['type'] === 'preview') update_option('eventify_me_visual_settings_preview', $data);
        else if($_POST['type'] === 'main') update_option('eventify_me_visual_settings', $data);
        else if($_POST['type'] === 'reset_to_default') $this->set_default_visual_settings();

        ob_start();
        echo do_shortcode('[events_list_view type="' . $_POST['type'] . '"]');
        $list_preview = ob_get_contents();
        ob_get_clean();

        ob_start();
        $preview_mode = true;
        require_once EVENTIFYME_DIR_PATH . 'public/partials/single-eventify-me.php';
        $detail_preview = ob_get_contents();
        ob_get_clean();

        ob_start();
        echo do_shortcode('[booking_event type="preview"]');
        $booking_preview = ob_get_contents();
        ob_get_clean();

        wp_send_json(['list_preview' => $list_preview, 'detail_preview' => $detail_preview, 'booking_preview' => $booking_preview]);
    }

    static function defaultFonts() {
        return [
            'open_sans' => [
                'id' => 'open_sans',
                'url' => 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap',
                'label' => 'Open Sans',
                'css_name' => '\'Open Sans\', sans-serif'
            ],
            'luxurious_roman' => [
                'id' => 'luxurious_roman',
                'url' => 'https://fonts.googleapis.com/css2?family=Luxurious+Roman&display=swap',
                'label' => 'Luxurious Roman',
                'css_name' => '\'Luxurious Roman\', cursive'
            ],
            'montserrat' => [
                'id' => 'montserrat',
                'url' => 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
                'label' => 'Montserrat',
                'css_name' => '\'Montserrat\', sans-serif'
            ],
        ];
    }

    public function update_booking_settings() {
        $data = array_column($_POST['settings'], 'value', 'name');
        update_option('eventify_me_booking_settings', $data);
        wp_die();
    }

    public function update_email_settings() {
        $data = array_column($_POST['settings'], 'value', 'name');
        $data['eventify_me_email_to_event_managers_text'] = stripcslashes($data['eventify_me_email_to_event_managers_text']);
        $data['eventify_me_email_to_user_text'] = stripcslashes($data['eventify_me_email_to_user_text']);
        $data['eventify_me_email_to_user_subject'] = stripcslashes($data['eventify_me_email_to_user_subject']);
        update_option('eventify_me_email_settings', $data);
        wp_die();
    }
}

new Eventify_Me_Settings();