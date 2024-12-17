<?php
/*
 * Table fields
 * id int
 * event_id int
 * date text
 * time_start text
 * time_end text
 * is_booking_enabled enum('yes', 'no')
 * date_added datetime
*/
class Event_Session {
    private $table_name = 'eventify_me_event_session';

    public function __construct(){
        global $wpdb;

        $this->table_name = $wpdb->prefix . $this->table_name;

        // remove session by ajax
        add_action('wp_ajax_remove_session_ajax', [$this, 'remove_session_ajax']);
    }

    public function get_sessions($fields = []){
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

    public function get_sessions_by_ids($ids) {
        global $wpdb;
        if(empty($ids)) return [];

        $ids = array_unique($ids);
        $sql = "SELECT * FROM {$this->table_name} WHERE id IN(" . implode(',', $ids) . ")";

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /* save session and return it false*/
    public function set_session($event_id, $date, $time_start, $time_end, $is_booking_enabled = 'no'){
        global $wpdb;

        if(empty($event_id) || empty($date) || empty($time_start)) return false;

        $result = $wpdb->insert($this->table_name, [
            'event_id' => $event_id,
            'date' => $date,
            'time_start' => $time_start,
            'time_end' => $time_end,
            'is_booking_enabled' => $is_booking_enabled,
            'date_added' => current_time('mysql', 1),
        ]);

        return $result === false ? false : $this->get_sessions(['id' => $wpdb->insert_id]);
    }

    /* update session and return it or false*/
    public function update_session($session_id, $fields_to_update = []){
        global $wpdb;

        if(
            (isset($fields_to_update['event_id']) && empty($fields_to_update['event_id'])) ||
            (isset($fields_to_update['date']) && empty($fields_to_update['date'])) ||
            (isset($fields_to_update['time_start']) && empty($fields_to_update['time_start'])) ||
            (isset($fields_to_update['is_booking_enabled']) && empty($fields_to_update['is_booking_enabled']))
        ) return false;

        $result = $wpdb->update($this->table_name, $fields_to_update, ['id' => $session_id]);

        return $result === false ? false : $this->get_sessions(['id' => $session_id]);
    }

    /*update or set array of sessions*/
    public function update_or_set_sessions($sessions, $event_id = NULL){

        foreach ($sessions as $session){
            if(empty($event_id) && empty($session['event_id'])) continue;
            $event_id = !empty($session['event_id']) ? $session['event_id'] : $event_id;

            if(empty($session['id'])) $this->set_session($event_id, $session['date'], $session['time_start'], $session['time_end'], $session['is_booking_enabled']);
            else $this->update_session($session['id'], ['date' => $session['date'], 'time_start' => $session['time_start'], 'time_end' => $session['time_end'], 'is_booking_enabled' => $session['is_booking_enabled']]);
        }
        return true;
    }

    /*remove session*/
    public function remove_session($fields_to_remove){
        global $wpdb;
        $result = $wpdb->delete($this->table_name, $fields_to_remove);

        return $result === false ? false : true;
    }

    public function remove_session_ajax(){
        $session_id = !empty($_POST['session_id']) ? $_POST['session_id'] : NULL;

        if(empty($session_id)) wp_send_json(['status' => 'error', 'message' => __('Missing session id.')]);

        $this->remove_session(['id' => $session_id]);

        wp_send_json(['status' => 'success', 'message' => '']);
    }
}

new Event_Session();