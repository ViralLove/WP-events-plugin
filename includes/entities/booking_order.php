<?php
/*
 * Table fields
 * id int
 * order_identifier text
 * event_id int
 * status enum('initiated', 'tickets', 'contact_details', 'confirmed')
 * user_email text
 * user_phone text
 * user_comments text
 * confirmed_on datetime
 * date_added datetime
*/

class Booking_Order {
    private $table_name = 'eventify_me_booking_order';
    private $booking_list_obj;

    public function __construct(){
        global $wpdb;

        $this->table_name = $wpdb->prefix . $this->table_name;

        // remove booking by ajax
        add_action('wp_ajax_remove_booking_ajax', [$this, 'remove_booking_ajax']);

        // booking order menu
        add_action('admin_menu', [$this, 'booking_menu']);

        // work with booking from front end
        add_action('wp_ajax_nopriv_save_booking_from_front', [$this, 'save_booking_from_front']);
        add_action('wp_ajax_save_booking_from_front', [$this, 'save_booking_from_front']);
    }

    public function booking_menu() {
        $hook = add_submenu_page(
            'edit.php?post_type=eventify-me-events',
            __('Bookings', EVENTIFYME_TEXTDOMAIN),
            __('Bookings', EVENTIFYME_TEXTDOMAIN),
            'manage_options',
            'eventify-me-bookings',
            [$this, 'bookings_list_page'],
            4

        );

        add_action( "load-$hook", [ $this, 'screen_option' ] );
    }

    /**
     * Screen options
     */
    public function screen_option() {

        $option = 'per_page';
        $args   = [
            'label'   => 'Bookings',
            'default' => 10,
            'option'  => 'bookings_per_page'
        ];

        add_screen_option( $option, $args );

        $this->booking_list_obj = new Booking_List();
    }

    public function bookings_list_page() {
        ?>
        <div class="wrap bookings-list-page">
            <h1 class="wp-heading-inline"><?php echo __('Bookings', 'supercars')?></h1>
<!--            <a href="/wp-admin/admin.php?page=add-new-booking" class="page-title-action">--><?php //echo __('Add New Booking', 'supercars')?><!--</a>-->
<!--            <a href="#" class="page-title-action export-of-filtered-bookings-to-excel">--><?php //echo __('Export To Excel', 'supercars')?><!--</a>-->

            <div id="poststuff">
                <div id="post-body" class="metabox-holder">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post" class="eventify-me-booking-table-form">
                                <?php
                                $this->booking_list_obj->prepare_items();
                                $this->booking_list_obj->display(); ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <?php
//        get_template_part('template-parts/loader');
    }

    public function get_bookings($fields = [], $orderby = [], $paged = []){
        global $wpdb;
        $sql = "SELECT * FROM {$this->table_name}";
        if(!empty($fields)){
            $i = 1;
            foreach ($fields as $name_field => $value) {
                if($i === 1) $sql .= " WHERE {$name_field}='{$value}'";
                else $sql .= " AND {$name_field}='{$value}'";
                $i++;
            }
        }

        if(!empty($orderby)){
            $sql .= " ORDER BY {$orderby['orderby']}";
            $sql .= " " . $orderby['order'];
        } else {
            $sql .= " ORDER BY id";
            $sql .= ' DESC';
        }

        if(!empty($paged)){
            if(!empty($paged['limit'])) {
                $sql .= " LIMIT {$paged['limit']}";
            }

            if(!empty($paged['offset'])) {
                $sql .= " OFFSET {$paged['offset']}";
            }
        }
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /* save booking and return it false*/
    public function set_booking($event_id, $status, $first_name, $last_name, $user_email, $user_phone, $user_comments, $confirmed_on){
        global $wpdb;

        if(empty($event_id) || empty($status)) return false;

        $result = $wpdb->insert($this->table_name, [
            'order_identifier' => $this->generate_order_identifier(), // XXX-XXX-XXX
            'event_id' => $event_id,
            'status' => $status,
            'user_email' => $user_email,
            'user_phone' => $user_phone,
            'user_comments' => $user_comments,
            'confirmed_on' => $confirmed_on,
            'date_added' => current_time('mysql', 1),
            'user_first_name' => $first_name,
            'user_last_name' => $last_name,
        ]);

        return $result === false ? false : $this->get_bookings(['id' => $wpdb->insert_id])[0];
    }

    /* update booking and return it or false*/
    public function update_booking($booking_id, $fields_to_update = []){
        global $wpdb;

        $result = $wpdb->update($this->table_name, $fields_to_update, ['id' => $booking_id]);

        return $result === false ? false : $this->get_bookings(['id' => $booking_id])[0];
    }


    /*remove booking*/
    public function remove_booking($fields_to_remove){
        global $wpdb;
        $result = $wpdb->delete($this->table_name, $fields_to_remove);

        return $result === false ? false : true;
    }

    public function remove_booking_ajax(){
        $booking_id = !empty($_POST['booking_id']) ? $_POST['booking_id'] : NULL;

        if(empty($booking_id)) wp_send_json(['status' => 'error', 'message' => __('Missing booking id.')]);

        $this->remove_booking(['id' => $booking_id]);

        wp_send_json(['status' => 'success', 'message' => '']);
    }

    public function generate_order_identifier(){
        global $wpdb;
        $coupon_number = Eventify_Me_Helpers::generate_random_digits_and_letters(3) . '-' . Eventify_Me_Helpers::generate_random_digits_and_letters(3) . '-' . Eventify_Me_Helpers::generate_random_digits_and_letters(3);
        $coupon_number = strtoupper($coupon_number);

        $result = $wpdb->get_row("SELECT * FROM `{$this->table_name}` WHERE order_identifier = '{$coupon_number}'");
        if($result !== NULL) return $this->generate_order_identifier();

        return $coupon_number;
    }

    public function save_booking_from_front(){
        $bookingStatus = $_POST['status'];
        $bookingData = $_POST['data'];

        if($bookingStatus == 'initiated' && empty($bookingData['bookingId'])) {
            if(empty($bookingData['eventId'])) wp_die();
            $booking = $this->set_booking(
                    $bookingData['eventId'],
                    $bookingStatus,
                    !empty($bookingData['first_name']) ? $bookingData['first_name'] : '',
                    !empty($bookingData['last_name']) ? $bookingData['last_name'] : '',
                    !empty($bookingData['email']) ? $bookingData['email'] : '',
                    !empty($bookingData['phone']) ? $bookingData['phone'] : '',
                    !empty($bookingData['comments']) ? $bookingData['comments'] : '',
                    ''
            );

            if($booking) wp_send_json($booking);
            wp_die();
        }

        $booking = $this->update_booking($bookingData['bookingId'], [
            'status' => $bookingStatus,
            'user_first_name' => !empty($bookingData['first_name']) ? $bookingData['first_name'] : '',
            'user_last_name' => !empty($bookingData['last_name']) ? $bookingData['last_name'] : '',
            'user_email' => !empty($bookingData['email']) ? $bookingData['email'] : '',
            'user_phone' => !empty($bookingData['phone']) ? $bookingData['phone'] : '',
            'user_comments' => !empty($bookingData['comments']) ? $bookingData['comments'] : '',
            'confirmed_on' => $bookingStatus === 'confirmed' ? current_time('mysql', 1) : '',
        ]);

        if(isset($bookingData['tickets'])) {
            $sessionObject = new Event_Session();
            $sessionBookingObject = new Event_Session_Booking();
            foreach ($bookingData['tickets'] as $sessionId => $ticketData) {
                //remove before add
                $sessionBookingObject->remove_order_session(['booking_order_id' => $bookingData['bookingId'], 'event_session_id' => $sessionId]);
                $session = $sessionObject->get_sessions(['id' => $sessionId])[0];
                if($ticketData['amount'] > 0){
                    for ($i = 1; $i <= $ticketData['amount']; $i++){
                        $sessionBookingObject->set_order_session($session['id'], $bookingData['bookingId']);
                    }
                }
            }
        }

        if($bookingStatus === 'confirmed') {
            $this->send_user_to_mailchimp($bookingData['email']);
            Eventify_Me_Emails::sendEmailToClientAfterConfirmedBooking($bookingData);
            Eventify_Me_Emails::sendEmailToEventManagersAfterConfirmedBooking($bookingData);
        }

        if($booking) wp_send_json($booking);
        wp_die();
    }

    public function send_user_to_mailchimp ($email) {
      if(class_exists('MC4WP_Custom_Integration') && class_exists('MC4WP_MailChimp')){
        $options = get_option( 'mc4wp', array() );
        $optionsIntegrations = get_option( 'mc4wp_integrations', array() );

        if ( empty( $options['api_key'] ) ) {
          return false;
        }

        if(empty($optionsIntegrations['custom']) || empty($optionsIntegrations['custom']['lists'])) {
          return false;
        }

        $lists_ids = $optionsIntegrations['custom']['lists'];

        $mailchimpApi   = new MC4WP_API_V3($options['api_key']);

        foreach ($lists_ids as $list_id) {
          $mailchimpApi->add_list_member($list_id, ['email_address' => $email, 'status_if_new' => 'subscribed', 'language' => explode('_', get_locale())[0]]);
        }
        return true;
      }

      return false;
    }
}

new Booking_Order();