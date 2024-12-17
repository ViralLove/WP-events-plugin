<?php
/*
 * Table fields
 * event_session_id int
 * booking_order_id int
 * date text
 * time_start text
 * time_end text
 * date_added datetime
*/

class Event_Session_Booking {
    private $table_name = 'eventify_me_event_session_booking';

    public function __construct(){
        global $wpdb;

        $this->table_name = $wpdb->prefix . $this->table_name;

        // remove booking by ajax
        add_action('wp_ajax_remove_order_session_ajax', [$this, 'remove_order_session_ajax']);
    }

    public function get_order_sessions($fields = []){
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

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /* save order session and return it or false*/
    public function set_order_session($event_session_id, $booking_order_id){
        global $wpdb;

        if(empty($event_session_id) || empty($booking_order_id)) return false;

        $event_session_object = new Event_Session();
        $session = $event_session_object->get_sessions_by_ids([$event_session_id]);

        if(empty($session)) return false;

        $session = $session[0];

        $result = $wpdb->insert($this->table_name, [
            'event_session_id' => $event_session_id,
            'booking_order_id' => $booking_order_id,
            'date' => $session['date'],
            'time_start' => $session['time_start'],
            'time_end' => $session['time_end'],
            'date_added' => current_time('mysql', 1),
        ]);

        return $result === false ? false : $this->get_order_sessions(['id' => $wpdb->insert_id]);
    }

    /* update order session and return it or false*/
    public function update_order_session($order_session_id, $fields_to_update = []){
        global $wpdb;

        if(
            (isset($fields_to_update['event_session_id']) && empty($fields_to_update['event_session_id'])) ||
            (isset($fields_to_update['booking_order_id']) && empty($fields_to_update['booking_order_id']))
        ) return false;

        $event_session_object = new Event_Session();
        $session = $event_session_object->get_sessions_by_ids([$fields_to_update['event_session_id']]);

        if(empty($session)) return false;

        $session = $session[0];

        $fields_to_update['date'] = $session['date'];
        $fields_to_update['time_start'] = $session['time_start'];
        $fields_to_update['time_end'] = $session['time_end'];

        $result = $wpdb->update($this->table_name, $fields_to_update, ['id' => $order_session_id]);

        return $result === false ? false : $this->get_order_sessions(['id' => $order_session_id]);
    }

    /*remove order session*/
    public function remove_order_session($fields_to_remove){
        global $wpdb;
        $result = $wpdb->delete($this->table_name, $fields_to_remove);

        return $result === false ? false : true;
    }

    public function remove_order_session_ajax(){
        $order_session_id = !empty($_POST['order_session_id']) ? $_POST['order_session_id'] : NULL;

        if(empty($order_session_id)) wp_send_json(['status' => 'error', 'message' => __('Missing order session id.')]);

        $this->remove_order_session(['id' => $order_session_id]);

        wp_send_json(['status' => 'success', 'message' => '']);
    }
}

new Event_Session_Booking();