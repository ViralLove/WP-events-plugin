<?php
/*
 * Class for search filters on eventify-me-events posts page in admin area
 */
class Eventify_Me_Filter_Events_Admin {
    public function __construct() {
        global $typenow;
        if (!empty($_GET['post_type']) && $_GET['post_type'] === 'eventify-me-events') {
            add_action('restrict_manage_posts', [$this, 'open_wrap_for_custom_filter']);
            add_action('restrict_manage_posts', [$this, 'filter_events_by_event_formats']);
            add_action('restrict_manage_posts', [$this, 'filter_events_by_event_thematics']);
            add_action('restrict_manage_posts', [$this, 'filter_events_by_dates']);
            add_action('restrict_manage_posts', [$this, 'close_wrap_for_custom_filter']);
            add_filter('parse_query', [$this, 'convert_id_to_term_in_query']);
        }
    }

    public function open_wrap_for_custom_filter(){
        echo '<div class="eventify-me-custom-admin-filters">';
        echo '<h3>' . __('Search events by criteria', EVENTIFYME_TEXTDOMAIN) . '</h3>';
    }

    public function close_wrap_for_custom_filter(){
        echo '</div>';
    }

    /*
     * custom filters html datetimes
     * */
    public function filter_events_by_dates(){
        global $typenow;
        $post_type = 'eventify-me-events';
        if ($typenow == $post_type) {
            $selectedSessions = isset($_GET['sessions']) ? $_GET['sessions'] : '';
            $selectedCreation = isset($_GET['creation_filter']) ? $_GET['creation_filter'] : '';
            $selectedUpdated = isset($_GET['updated_filter']) ? $_GET['updated_filter'] : '';
            ?>
            <div class="dates-filters-wrap">
                <div class="admin-custom-filter-events-input-wrap">
                    <label for="sessions"> <?php echo __('Filter by events date', EVENTIFYME_TEXTDOMAIN)?></label>
                    <input type="text" name="sessions" id="sessions" class="filter-by-date" placeholder="<?php echo __('Filter by events date', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo $selectedSessions?>">
                </div>

                <div class="admin-custom-filter-events-input-wrap">
                    <label for="creation_filter"> <?php echo __('Filter By Creation', EVENTIFYME_TEXTDOMAIN)?></label>
                    <input type="text" name="creation_filter" id="creation_filter" class="filter-by-date" placeholder="<?php echo __('Filter By Creation', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo $selectedCreation?>">
                </div>

                <div class="admin-custom-filter-events-input-wrap">
                    <label for="updated_filter"> <?php echo __('Filter By Updated', EVENTIFYME_TEXTDOMAIN)?></label>
                    <input type="text" name="updated_filter" id="updated_filter" class="filter-by-date" placeholder="<?php echo __('Filter By Updated', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo $selectedUpdated?>">
                </div>
            </div>
        <?php
        }
    }

    /*
      * custom filters event_thematics
    */
    public function filter_events_by_event_thematics() {
        $this->get_html_filter_for_taxonomy('eventify-me-events', 'event_thematics', __('Show all', EVENTIFYME_TEXTDOMAIN), __('Filter by event thematics', EVENTIFYME_TEXTDOMAIN));
    }

    /*
      * custom filters event_formats
    */
    public function filter_events_by_event_formats() {
        $this->get_html_filter_for_taxonomy('eventify-me-events', 'event_formats', __('Show all', EVENTIFYME_TEXTDOMAIN), __('Filter by event formats', EVENTIFYME_TEXTDOMAIN));
    }

    /*
     * method generated html for taxonomies by params
    */
    public function get_html_filter_for_taxonomy($post_type, $taxonomy, $default_option, $label) {
        global $typenow;
        if ($typenow == $post_type) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : [];
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => true
            ]);
            ?>
            <div class="select-filters-wrap">
                <label for="<?php echo $taxonomy?>"><?php echo $label?></label>
                <select name="<?php echo $taxonomy?>[]" id="<?php echo $taxonomy?>" multiple class="postform">
                    <option value=""><?php echo $default_option?></option>
                    <?php foreach ($terms as $term):?>
                        <option value="<?php echo $term->term_id?>" <?php selected(true, in_array($term->term_id, $selected))?>><?php echo $term->name?> (<?php echo $term->count?>)</option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php
        }
    }

    /*
     * filter events by custom filters
    */
    public function convert_id_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'eventify-me-events'; // change to your post type
        $q_vars    = &$query->query_vars;

        if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type ) {
            if(!empty($q_vars['event_formats'])){
                $new_formats = [];
                foreach ($q_vars['event_formats'] as $term) {
                    if(empty($term)) continue;
                    $term = get_term_by('id', $term, 'event_formats');
                    $new_formats[] = $term->slug;
                }
                $q_vars['event_formats'] = $new_formats;
            }

            if(!empty($q_vars['event_thematics'])){
                $new_formats = [];
                foreach ($q_vars['event_thematics'] as $term) {
                    if(empty($term)) continue;
                    $term = get_term_by('id', $term, 'event_thematics');
                    $new_formats[] = $term->slug;
                }
                $q_vars['event_thematics'] = $new_formats;
            }

            if(!empty($_REQUEST['sessions'])){
                $sessions_filtered = self::getEventsByTimeSessions($_REQUEST['sessions']);
                $q_vars['post__in'] = !empty($sessions_filtered) ? $sessions_filtered : [0];
            }

            if(!empty($_REQUEST['creation_filter'])){
                add_filter( 'posts_where', array( $this, 'filter_by_creation_event' ) );
            }

            if(!empty($_REQUEST['updated_filter'])){
                add_filter( 'posts_where', array( $this, 'filter_by_updated_event' ) );
            }

        }

    }

    public function filter_by_creation_event($where = ''){
        global $wpdb;
        $dates = explode(' to ', $_REQUEST['creation_filter']);
        $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $dates[0])));
        $dateTo = !empty($dates[1]) ? date('Y-m-d', strtotime(str_replace('/', '-', $dates[1]))) : $dateFrom;
        $dateTo = date('Y-m-d', strtotime($dateTo . ' +1 day'));
        $where .=  " AND ({$wpdb->prefix}posts.post_date >= '{$dateFrom}' AND {$wpdb->prefix}posts.post_date <= '{$dateTo}')";
        return $where;
    }

    public function filter_by_updated_event($where = ''){
        global $wpdb;
        $dates = explode(' to ', $_REQUEST['updated_filter']);
        $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $dates[0])));
        $dateTo = !empty($dates[1]) ? date('Y-m-d', strtotime(str_replace('/', '-', $dates[1]))) : $dateFrom;
        $dateTo = date('Y-m-d', strtotime($dateTo . ' +1 day'));
        $where .=  " AND ({$wpdb->prefix}posts.post_modified >= '{$dateFrom}' AND {$wpdb->prefix}posts.post_modified <= '{$dateTo}')";
        return $where;
    }

    static function getEventsByTimeSessions($timeSessions){
        global $wpdb;
        $dates = explode(' to ', $timeSessions);
        $dateFrom = date('Y-m-d', strtotime( str_replace('/', '-', $dates[0])));
        $dateTo = !empty($dates[1]) ? date('Y-m-d', strtotime(str_replace('/', '-', $dates[1]))) : $dateFrom;

        $events = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'eventify-me-events' AND post_status = 'publish'", ARRAY_A);
        $eventsIds = array_column($events, 'ID');

        $resultsIds = [];
        foreach ($eventsIds as $eventsId){
//            $eventSessions = get_post_meta($eventsId, 'event_sessions', true);
//            $eventSessions = is_array($eventSessions) ? $eventSessions : [];
            $sessionObject = new Event_Session();
            $eventSessions = $sessionObject->get_sessions(['event_id' => $eventsId]);

            foreach ($eventSessions as $session){
                $sessionDate = date('Y-m-d', strtotime( str_replace('/', '-', $session['date'])));

                if(($sessionDate >= $dateFrom && $sessionDate <= $dateTo) || ($sessionDate <= $dateFrom && $sessionDate >= $dateTo)){
                    $resultsIds[] = $eventsId;
                    break;
                }
            }

            if(!EVENTIFYME_IS_LICENSED && count($resultsIds) > 2) break;
        }

        return $resultsIds;
    }

    static function filter_sessions_by_start_end($sessions, $dates = []){
        if(empty($sessions) || empty($dates)) return [];
        $dates['start_date'] = !empty($dates['start_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $dates['start_date']))) : '';
        $dates['end_date'] = !empty($dates['end_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $dates['end_date']))) : '';

        $resultIds = [];

        foreach ($sessions as $session) {
            $sessionDate = date('Y-m-d', strtotime( str_replace('/', '-', $session['date'])));
            $sessionHourStart = date('H:i', strtotime($session['time_start']));
            $sessionHourEnd = date('H:i', strtotime($session['time_end']));

            if(!empty($dates['start_date']) && $sessionDate < $dates['start_date']) continue;

            if(!empty($dates['end_date']) && $sessionDate > $dates['end_date']) continue;

            if(!empty($dates['start_time_from']) && $dates['start_time_from'] !== '00:00' && $sessionHourStart < $dates['start_time_from']) continue;

            if(!empty($dates['start_time_to']) && $dates['start_time_to'] !== '00:00' && $sessionHourStart > $dates['start_time_to']) continue;

            if(!empty($dates['end_time_from']) && $dates['end_time_from'] !== '00:00' && $sessionHourEnd < $dates['end_time_from']) continue;

            if(!empty($dates['end_time_to']) && $dates['end_time_to'] !== '00:00' && $sessionHourEnd > $dates['end_time_to']) continue;

            $resultIds[] = $session['event_session_id'];
        }

        return $resultIds;
    }
}

new Eventify_Me_Filter_Events_Admin();