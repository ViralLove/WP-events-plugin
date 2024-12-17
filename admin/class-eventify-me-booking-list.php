<?php

class Booking_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {

        parent::__construct( [
            'singular' => __( 'Booking', EVENTIFYME_TEXTDOMAIN ), //singular name of the listed records
            'plural'   => __( 'Bookings', EVENTIFYME_TEXTDOMAIN ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ] );

    }

    /**
     * Retrieve vehicles data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    static function get_bookings( $per_page = 10, $page_number = 1 ) {
        global $wpdb;
        $sessionsBookingObject = new Event_Session_Booking();
        $bookingTableName = "{$wpdb->prefix}eventify_me_booking_order";
        $sessionBookingTableName = "{$wpdb->prefix}eventify_me_event_session_booking";
        $postTable = "{$wpdb->prefix}posts";

        $sql = "SELECT {$bookingTableName}.* FROM {$bookingTableName}
                LEFT JOIN {$postTable}
                ON {$bookingTableName}.event_id = {$postTable}.ID
                LEFT JOIN {$sessionBookingTableName} 
                ON {$sessionBookingTableName}.booking_order_id = {$bookingTableName}.id";

        $sql .= " WHERE {$bookingTableName}.id IS NOT NULL";

        if ( ! empty( $_REQUEST['event_comments'] ) ) {
            $sql .= " AND (";
            $sql .= "{$bookingTableName}.user_comments LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= " OR {$postTable}.post_title LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= " OR {$postTable}.post_content LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= ")";
        }

        if( !empty( $_REQUEST['user_email'] )){
            $sql .= " AND {$bookingTableName}.user_email LIKE '%" . trim($_REQUEST['user_email']) . "%'";
        }
        
        if( !empty( $_REQUEST['user_first_name'] )){
            $sql .= " AND {$bookingTableName}.user_first_name LIKE '%" . trim($_REQUEST['user_first_name']) . "%'";
        }
        
        if( !empty( $_REQUEST['user_last_name'] )){
            $sql .= " AND {$bookingTableName}.user_last_name LIKE '%" . trim($_REQUEST['user_last_name']) . "%'";
        }

        if( !empty( $_REQUEST['user_phone'] )){
            $sql .= " AND {$bookingTableName}.user_phone LIKE '%" . trim($_REQUEST['user_phone']) . "%'";
        }

        if( !empty( $_REQUEST['order_identifier'] )){
            $sql .= " AND {$bookingTableName}.order_identifier LIKE '%" . trim($_REQUEST['order_identifier']) . "%'";
        }

        if (!empty($_REQUEST['order_status'])) {
            $sql .= " AND {$bookingTableName}.status = '" . esc_sql($_REQUEST['order_status']) . "'";
        }
        

//        if( !empty( $_REQUEST['date_from'] ) || !empty( $_REQUEST['date_to'])){
//            $sessions_filtered = Eventify_Me_Filter_Events_Admin::filter_sessions_by_date($sessionsBookingObject->get_order_sessions(), $_REQUEST['date_from'] . ' to ' . $_REQUEST['date_to']);
//            if(empty($sessions_filtered)) $sessions_filtered = [0];
//            $sql .= " AND {$sessionBookingTableName}.event_session_id IN (" . implode(',', $sessions_filtered) . ")";
//        }

        $sessions_filtered = [];
        if(
            !empty( $_REQUEST['start_date'] ) ||
            !empty( $_REQUEST['start_time_from'] ) ||
            !empty( $_REQUEST['start_time_to'] ) ||
            !empty( $_REQUEST['end_date'] ) ||
            !empty( $_REQUEST['end_time_from'] ) ||
            !empty( $_REQUEST['end_time_to'] )
        ) {
            $startTimeFrom = '';
            if(!empty($_REQUEST['start_time_from'])) {
                $startMinuteFrom = !empty($_REQUEST['start_minute_from']) ? $_REQUEST['start_minute_from'] : '00';
                $startTimeFrom = $_REQUEST['start_time_from'] . ':' . $startMinuteFrom;
            }

            $startTimeTo = '';
            if(!empty($_REQUEST['start_time_to'])) {
                $startMinuteTo = !empty($_REQUEST['start_minute_to']) ? $_REQUEST['start_minute_to'] : '00';
                $startTimeTo = $_REQUEST['start_time_to'] . ':' . $startMinuteTo;
            }

            $endTimeFrom = '';
            if(!empty($_REQUEST['end_time_from'])) {
                $endMinuteFrom = !empty($_REQUEST['end_minute_from']) ? $_REQUEST['end_minute_from'] : '00';
                $endTimeFrom = $_REQUEST['end_time_from'] . ':' . $endMinuteFrom;
            }

            $endTimeTo = '';
            if(!empty($_REQUEST['end_time_to'])) {
                $endMinuteTo = !empty($_REQUEST['end_minute_to']) ? $_REQUEST['end_minute_to'] : '00';
                $endTimeTo = $_REQUEST['end_time_to'] . ':' . $endMinuteTo;
            }

            $sessions_filtered = Eventify_Me_Filter_Events_Admin::filter_sessions_by_start_end(
                $sessionsBookingObject->get_order_sessions(),
                [
                    'start_date' => $_REQUEST['start_date'],
                    'start_time_from' => $startTimeFrom,
                    'start_time_to' => $startTimeTo,
                    'end_date' => $_REQUEST['end_date'],
                    'end_time_from' => $endTimeFrom,
                    'end_time_to' => $endTimeTo,
                ]
            );

            $sessions_filtered = array_unique($sessions_filtered);
            if(empty($sessions_filtered)) $sessions_filtered = [0];
        }

        if(!empty($sessions_filtered)) $sql .= " AND {$sessionBookingTableName}.event_session_id IN (" . implode(',', $sessions_filtered) . ")";

        if( !empty( $_REQUEST['created_from'] ) ){
            $sql .= " AND {$bookingTableName}.date_added >= '" . date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['created_from']))) . "'";
        }

        if( !empty( $_REQUEST['created_to'] ) ){
            $sql .= " AND {$bookingTableName}.date_added < '" . date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['created_to']) . '+ 1 day')) . "'";
        }

        $sql .= " GROUP BY {$bookingTableName}.id";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            // Проверка и обработка сортировки по количеству сессий
            if($_REQUEST['orderby'] === 'qty_of_sessions_in_order') {
                $bookingsIds = $wpdb->get_col("SELECT booking_order_id, COUNT(*) as count FROM {$wpdb->prefix}eventify_me_event_session_booking GROUP BY booking_order_id ORDER BY count {$_REQUEST['order']}", 0);
                $sql .= " ORDER BY FIELD({$bookingTableName}.id, " . implode(', ', $bookingsIds) . ")";
            } elseif ($_REQUEST['orderby'] === 'status') {
                // Сортировка по статусу заказа
                $sql .= " ORDER BY {$bookingTableName}.status " . esc_sql($_REQUEST['order']);
            } else {
                $sql .= " ORDER BY {$bookingTableName}." . esc_sql($_REQUEST['orderby']) . ' ' . esc_sql($_REQUEST['order']);
            }
        } else {
            $sql .= " ORDER BY {$bookingTableName}.id DESC";
        }

        $log_file = plugin_dir_path(__FILE__) . 'debug.log';
        file_put_contents($log_file, "SQL Query: " . $sql . "\n", FILE_APPEND);

        if($per_page != 0){
            $sql .= " LIMIT $per_page";
            $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        }

        return $wpdb->get_results( $sql, 'ARRAY_A' );
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    static function record_count() {
        global $wpdb;
        $sessionsBookingObject = new Event_Session_Booking();
        $bookingTableName = "{$wpdb->prefix}eventify_me_booking_order";
        $sessionBookingTableName = "{$wpdb->prefix}eventify_me_event_session_booking";
        $postTable = "{$wpdb->prefix}posts";

        $sql = "SELECT COUNT(*) FROM {$bookingTableName}
                LEFT JOIN {$postTable}
                ON {$bookingTableName}.event_id = {$postTable}.ID
                LEFT JOIN {$sessionBookingTableName} 
                ON {$sessionBookingTableName}.booking_order_id = {$bookingTableName}.id";

        $sql .= " WHERE {$bookingTableName}.id IS NOT NULL";

        if ( ! empty( $_REQUEST['event_comments'] ) ) {
            $sql .= " AND (";
            $sql .= "{$bookingTableName}.user_comments LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= " OR {$postTable}.post_title LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= " OR {$postTable}.post_content LIKE '%{$_REQUEST['event_comments']}%'";
            $sql .= ")";
        }

        if( !empty( $_REQUEST['user_email'] )){
            $sql .= " AND {$bookingTableName}.user_email LIKE '%" . trim($_REQUEST['user_email']) . "%'";
        }
        
        if( !empty( $_REQUEST['user_first_name'] )){
            $sql .= " AND {$bookingTableName}.user_first_name LIKE '%" . trim($_REQUEST['user_first_name']) . "%'";
        }
        
        if( !empty( $_REQUEST['user_last_name'] )){
            $sql .= " AND {$bookingTableName}.user_last_name LIKE '%" . trim($_REQUEST['user_last_name']) . "%'";
        }

        if( !empty( $_REQUEST['user_phone'] )){
            $sql .= " AND {$bookingTableName}.user_phone LIKE '%" . trim($_REQUEST['user_phone']) . "%'";
        }

        if( !empty( $_REQUEST['order_identifier'] )){
            $sql .= " AND {$bookingTableName}.order_identifier LIKE '%" . trim($_REQUEST['order_identifier']) . "%'";
        }

        $sessions_filtered = [];
        if(
            !empty( $_REQUEST['start_date'] ) ||
            !empty( $_REQUEST['start_time_from'] ) ||
            !empty( $_REQUEST['start_time_to'] ) ||
            !empty( $_REQUEST['end_date'] ) ||
            !empty( $_REQUEST['end_time_from'] ) ||
            !empty( $_REQUEST['end_time_to'] )
        ) {
            $startTimeFrom = '';
            if(!empty($_REQUEST['start_time_from'])) {
                $startMinuteFrom = !empty($_REQUEST['start_minute_from']) ? $_REQUEST['start_minute_from'] : '00';
                $startTimeFrom = $_REQUEST['start_time_from'] . ':' . $startMinuteFrom;
            }

            $startTimeTo = '';
            if(!empty($_REQUEST['start_time_to'])) {
                $startMinuteTo = !empty($_REQUEST['start_minute_to']) ? $_REQUEST['start_minute_to'] : '00';
                $startTimeTo = $_REQUEST['start_time_to'] . ':' . $startMinuteTo;
            }

            $endTimeFrom = '';
            if(!empty($_REQUEST['end_time_from'])) {
                $endMinuteFrom = !empty($_REQUEST['end_minute_from']) ? $_REQUEST['end_minute_from'] : '00';
                $endTimeFrom = $_REQUEST['end_time_from'] . ':' . $endMinuteFrom;
            }

            $endTimeTo = '';
            if(!empty($_REQUEST['end_time_to'])) {
                $endMinuteTo = !empty($_REQUEST['end_minute_to']) ? $_REQUEST['end_minute_to'] : '00';
                $endTimeTo = $_REQUEST['end_time_to'] . ':' . $endMinuteTo;
            }

            $sessions_filtered = Eventify_Me_Filter_Events_Admin::filter_sessions_by_start_end(
                $sessionsBookingObject->get_order_sessions(),
                [
                    'start_date' => $_REQUEST['start_date'],
                    'start_time_from' => $startTimeFrom,
                    'start_time_to' => $startTimeTo,
                    'end_date' => $_REQUEST['end_date'],
                    'end_time_from' => $endTimeFrom,
                    'end_time_to' => $endTimeTo,
                ]
            );

            $sessions_filtered = array_unique($sessions_filtered);
            if(empty($sessions_filtered)) $sessions_filtered = [0];
        }

        if(!empty($sessions_filtered)) $sql .= " AND {$sessionBookingTableName}.event_session_id IN (" . implode(',', $sessions_filtered) . ")";

        if( !empty( $_REQUEST['created_from'] ) ){
            $sql .= " AND {$bookingTableName}.date_added >= '" . date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['created_from']))) . "'";
        }

        if( !empty( $_REQUEST['created_to'] ) ){
            $sql .= " AND {$bookingTableName}.date_added < '" . date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['created_to']) . '+ 1 day')) . "'";
        }

        $sql .= " GROUP BY {$bookingTableName}.id";

        return count($wpdb->get_results( $sql, 'ARRAY_A' ));
    }

    /** Text displayed when no vehicles data is available */
    public function no_items() {
        _e( 'No bookings available.', EVENTIFYME_TEXTDOMAIN );
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id':
            case 'event_id':
            case 'time_sessions':
            case 'qty_of_sessions_in_order':
            case 'order_identifier':
            case 'status':
            case 'user_first_name':
            case 'user_last_name':
            case 'user_email':
            case 'user_phone':
            case 'user_comments':
            case 'confirmed_on':
            case 'date_added':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Method for id column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */

    function column_event_id( $item ) {
        $delete_nonce = wp_create_nonce( 'eventify_delete_booking' );

        $event = get_post($item['event_id']);
        $row_actions = '<div class="row-actions">';
        $row_actions .= '<span class="edit"><a href="' . get_edit_post_link($event->ID) . '"> ' . __('Edit', EVENTIFYME_TEXTDOMAIN) . '</a> | </span>';
        $row_actions .= '<span class="view"><a href="' . get_the_permalink($event->ID) . '" target="_blank">' . __('View', EVENTIFYME_TEXTDOMAIN) . '</a> | </span>';
        $row_actions .= '<span class="trash"><a href="' . sprintf( '?post_type=%s&page=%s&action=%s&booking=%s&_wpnonce=%s', esc_attr( $_REQUEST['post_type'] ), esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )

            . '" data-confirm="' . __('Remove this booking?', EVENTIFYME_TEXTDOMAIN) . '">' . __('Delete', EVENTIFYME_TEXTDOMAIN) . '</a></span>';
        $row_actions .= '</div>';
        return '<strong><a href="' . get_the_permalink($event->ID) . '" class="row-title" target="_blank">' . $event->post_title . '</a></strong>' . $row_actions;
    }

    function column_time_sessions($item){
        $orderSessionsObj = new Event_Session_Booking();
        $orderSessions = $orderSessionsObj->get_order_sessions(['booking_order_id' => $item['id']]);

        $alreadyWas = [];
        $res = '<div class="sessions-wrap">';
        foreach ($orderSessions as $orderSession){
            if(in_array($orderSession['event_session_id'], $alreadyWas)) continue;
            $alreadyWas[] = $orderSession['event_session_id'];

            $res .= '<div class="session">';
            $res .= $orderSession['date'] . ' ' . $orderSession['time_start'];
            $res .= !empty($orderSession['time_end']) ? ' - ' . $orderSession['time_end'] : '';
            $res .= '</div>';
        }
        $res .= '</div>';
        return $res;
    }

    function column_qty_of_sessions_in_order($item){
        $orderSessionsObj = new Event_Session_Booking();
        $orderSessions = $orderSessionsObj->get_order_sessions(['booking_order_id' => $item['id']]);
        $sessionsCounts = array_count_values(array_column($orderSessions, 'event_session_id'));

        $res = '<div class="sessions-wrap">';
        foreach ($sessionsCounts as $count){
            $res .= '<div class="session">';
            $res .= $count;
            $res .= '</div>';
        }
        $res .= '</div>';
        return $res;
    }

    function column_user_email($item) {
        return '<a href="mailto:' . $item['user_email'] . '">' . $item['user_email'] . '</a>';
    }
    
    function column_user_first_name($item) {
        return '<a href="mailto:' . $item['user_first_name'] . '">' . $item['user_first_name'] . '</a>';
    }
    
    function column_user_last_name($item) {
        return '<a href="mailto:' . $item['user_last_name'] . '">' . $item['user_last_name'] . '</a>';
    }

    function column_user_phone($item) {
        return '<a href="tel:' . $item['user_phone'] . '">' . $item['user_phone'] . '</a>';
    }

    function column_user_comments ($item) {
        return '<div title="' . $item['user_comments'] . '">' . $item['user_comments'] . '</div>';
    }

    function column_status( $item ) {
        return strtoupper($item['status']);
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'cb'    => '<input type="checkbox" />',
            'id'    => __( 'ID', EVENTIFYME_TEXTDOMAIN ),
            'event_id'    => __( 'Event', EVENTIFYME_TEXTDOMAIN ),
            'time_sessions'    => __( 'Time sessions', EVENTIFYME_TEXTDOMAIN ),
            'qty_of_sessions_in_order'    => __( 'Quantity in order', EVENTIFYME_TEXTDOMAIN ),
            'user_first_name'    => __( 'First name', EVENTIFYME_TEXTDOMAIN ),
            'user_last_name'    => __( 'Last name', EVENTIFYME_TEXTDOMAIN ),
            'user_email'    => __( 'User email', EVENTIFYME_TEXTDOMAIN ),
            'user_phone'    => __( 'User phone', EVENTIFYME_TEXTDOMAIN ),
            'user_comments'    => __( 'User comments', EVENTIFYME_TEXTDOMAIN ),
            'status'    => __( 'Status', EVENTIFYME_TEXTDOMAIN ),
//            'confirmed_on'    => __( 'Confirmed time', EVENTIFYME_TEXTDOMAIN ),
            'order_identifier'    => __( 'Order Identifier', EVENTIFYME_TEXTDOMAIN ),
            'date_added' => __( 'Order created on', 'supercars' ),
        ];

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'id' => array( 'id', true ),
            'event_id' => array( 'event_id', true ),
//            'time_sessions' => array( 'time_sessions', true ),
            'qty_of_sessions_in_order' => array( 'qty_of_sessions_in_order', true ),
            'user_first_name' => array( 'user_first_name', true ),
            'user_last_name' => array( 'user_last_name', true ),
            'user_email' => array( 'user_email', true ),
            'user_phone' => array( 'user_phone', true ),
            'user_comments' => array( 'user_comments', true ),
            'order_identifier' => array( 'order_identifier', true ),
            'status' => array( 'status', true ), // Добавлена сортировка по статусу
            'date_added' => array( 'date_added', false )
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => __('Delete', EVENTIFYME_TEXTDOMAIN)
        ];

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'bookings_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );
        $this->items = self::get_bookings( $per_page, $current_page );
    }

    public function process_bulk_action() {
        $bookingObject = new Booking_Order();

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, 'eventify_delete_booking' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                $test = $bookingObject->remove_booking( ['id' => absint( $_GET['booking'] )] );

                wp_redirect( '/wp-admin/edit.php?post_type=eventify-me-events&page=eventify-me-bookings' );
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                $bookingObject->remove_booking( ['id' => $id] );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            wp_redirect( esc_url_raw(add_query_arg()) );
//            exit;
        }
    }


    /**
     * Generates the table navigation above or below the table
     *
     * @since 3.1.0
     * @param string $which
     */
    protected function display_tablenav( $which ) {
        if ( 'top' === $which ) {
            wp_nonce_field( 'bulk-' . $this->_args['plural'] );
        }
        $this->extra_tablenav( $which );
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <?php if ( $this->has_items() ) : ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
            <?php
            endif;
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    protected function extra_tablenav( $which ) {
        if ( 'top' === $which ) { ?>
            <div class="custom-booking-filters">
                <div class="eventify-me-custom-admin-filters">
                    <h3><?php echo __('Search bookings by criteria', EVENTIFYME_TEXTDOMAIN)?></h3>

                    <div class="input-group full-width">
                        <label for="event_comments"> <?php echo __('Filter by event, comments', EVENTIFYME_TEXTDOMAIN)?></label>
                        <input type="text" name="event_comments" id="event_comments" placeholder="<?php echo __('Filter by event, comments', EVENTIFYME_TEXTDOMAIN)?>"
                            value="<?php echo !empty($_REQUEST['event_comments']) ? $_REQUEST['event_comments'] : ''?>">
                    </div>

                    <div class="input-group input-group-flex full-width input-group-sessions">
                        <label><?php echo __('Event sessions time', EVENTIFYME_TEXTDOMAIN)?></label>

                        <div class="child-wrap">
                            <div class="input-group">
                                <label for="start_date">
                                    <?php echo __('By start time', EVENTIFYME_TEXTDOMAIN)?>
                                    <input type="text" name="start_date" id="start_date" class="date_created" placeholder="<?php echo __('By start date', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''?>">
                                </label>
                            </div>

                            <div class="input-group input-group-child-big">
                                <label for="start_time_from">
                                    <?php echo __('Session starts between', EVENTIFYME_TEXTDOMAIN)?>
                                </label>
                                <input type="text" name="start_time_from" id="start_time_from" class="timehour" placeholder="<?php echo __('hh', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['start_time_from']) ? $_REQUEST['start_time_from'] : ''?>">
                                :
                                <input type="text" name="start_minute_from" id="start_minute_from" class="timeminute" placeholder="<?php echo __('mm', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['start_minute_from']) ? $_REQUEST['start_minute_from'] : ''?>">

                                <?php echo __('and', EVENTIFYME_TEXTDOMAIN)?>

                                <input type="text" name="start_time_to" id="start_time_to" class="timehour" placeholder="<?php echo __('hh', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['start_time_to']) ? $_REQUEST['start_time_to'] : ''?>">
                                :
                                <input type="text" name="start_minute_to" id="start_minute_to" class="timeminute" placeholder="<?php echo __('mm', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['start_minute_to']) ? $_REQUEST['start_minute_to'] : ''?>">
                            </div>
                        </div>

                        <div class="child-wrap">
                            <div class="input-group">
                                <label for="end_date">
                                    <?php echo __('By end time', EVENTIFYME_TEXTDOMAIN)?>
                                    <input type="text" name="end_date" id="end_date" class="date_created" placeholder="<?php echo __('By end date', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''?>">
                                </label>
                            </div>

                            <div class="input-group input-group-child-big">
                                <label for="end_time_from">
                                    <?php echo __('Session ends between', EVENTIFYME_TEXTDOMAIN)?>
                                </label>
                                <input type="text" name="end_time_from" id="end_time_from" class="timehour" placeholder="<?php echo __('hh', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['end_time_from']) ? $_REQUEST['end_time_from'] : ''?>">
                                :
                                <input type="text" name="end_minute_from" id="end_minute_from" class="timeminute" placeholder="<?php echo __('mm', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['end_minute_from']) ? $_REQUEST['end_minute_from'] : ''?>">

                                <?php echo __('and', EVENTIFYME_TEXTDOMAIN)?>

                                <input type="text" name="end_time_to" id="end_time_to" class="timehour" placeholder="<?php echo __('hh', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['end_time_to']) ? $_REQUEST['end_time_to'] : ''?>">
                                :
                                <input type="text" name="end_minute_to" id="end_minute_to" class="timeminute" placeholder="<?php echo __('mm', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['end_minute_to']) ? $_REQUEST['end_minute_to'] : ''?>">
                            </div>
                        </div>
                    </div>

                    <div class="input-group input-group-flex full-width">
                        <div class="input-group">
                            <label for="user_email" class="main-label"> <?php echo __('Filter by user email', EVENTIFYME_TEXTDOMAIN)?></label>
                            <input type="text" name="user_email" id="user_email" placeholder="<?php echo __('Filter by user email', EVENTIFYME_TEXTDOMAIN)?>"
                                value="<?php echo !empty($_REQUEST['user_email']) ? $_REQUEST['user_email'] : ''?>">
                        </div>
                        
                        <div class="input-group">
                            <label for="user_email" class="main-label"> <?php echo __('Filter by first name', EVENTIFYME_TEXTDOMAIN)?></label>
                            <input type="text" name="user_first_name" id="user_first_name" placeholder="<?php echo __('Filter by first name', EVENTIFYME_TEXTDOMAIN)?>"
                                value="<?php echo !empty($_REQUEST['user_first_name']) ? $_REQUEST['user_first_name'] : ''?>">
                        </div>
                        
                        <div class="input-group">
                            <label for="user_email" class="main-label"> <?php echo __('Filter by last name', EVENTIFYME_TEXTDOMAIN)?></label>
                            <input type="text" name="user_last_name" id="user_last_name" placeholder="<?php echo __('Filter by last name', EVENTIFYME_TEXTDOMAIN)?>"
                                value="<?php echo !empty($_REQUEST['user_last_name']) ? $_REQUEST['user_last_name'] : ''?>">
                        </div>

                        <div class="input-group">
                            <label for="user_phone" class="main-label"> <?php echo __('Filter by user phone', EVENTIFYME_TEXTDOMAIN)?></label>
                            <input type="text" name="user_phone" id="user_phone" placeholder="<?php echo __('Filter by user phone', EVENTIFYME_TEXTDOMAIN)?>"
                                value="<?php echo !empty($_REQUEST['user_phone']) ? $_REQUEST['user_phone'] : ''?>">
                        </div>
                    </div>
                    
                    <div class="input-group input-group-flex full-width">
                        <div class="input-group">
                            <label for="order_identifier" class="main-label"> <?php echo __('Filter by order identifier', EVENTIFYME_TEXTDOMAIN)?></label>
                            <input type="text" name="order_identifier" id="order_identifier" placeholder="<?php echo __('Filter by order identifier', EVENTIFYME_TEXTDOMAIN)?>"
                                value="<?php echo !empty($_REQUEST['order_identifier']) ? $_REQUEST['order_identifier'] : ''?>">
                        </div>
                    </div>

                    <!-- New Order Status Filter -->
                    <div class="input-group input-group-flex full-width">
                        <div class="input-group">
                            <label for="order_status" class="main-label"> <?php echo __('Order status', EVENTIFYME_TEXTDOMAIN)?></label>
                            <select name="order_status" id="order_status">
                                <option value=""><?php echo __('All statuses', EVENTIFYME_TEXTDOMAIN); ?></option>
                                <option value="initiated" <?php selected($_REQUEST['order_status'] ?? '', 'initiated'); ?>><?php echo __('Initiated', EVENTIFYME_TEXTDOMAIN); ?></option>
                                <option value="tickets" <?php selected($_REQUEST['order_status'] ?? '', 'tickets'); ?>><?php echo __('Tickets', EVENTIFYME_TEXTDOMAIN); ?></option>
                                <option value="contact_details" <?php selected($_REQUEST['order_status'] ?? '', 'contact_details'); ?>><?php echo __('Contact Details', EVENTIFYME_TEXTDOMAIN); ?></option>
                                <option value="confirmed" <?php selected($_REQUEST['order_status'] ?? '', 'confirmed'); ?>><?php echo __('Confirmed', EVENTIFYME_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group input-group-flex full-width">
                        <label><?php echo __('Filter by order created on', EVENTIFYME_TEXTDOMAIN)?></label>

                        <div class="input-group">
                            <label for="created_from">
                                <?php echo __('From', EVENTIFYME_TEXTDOMAIN)?>
                                <input type="text" name="created_from" id="created_from" class="date_created" placeholder="<?php echo __('From', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['created_from']) ? $_REQUEST['created_from'] : ''?>">
                            </label>
                        </div>

                        <div class="input-group">
                            <label for="created_to">
                                <?php echo __('To', EVENTIFYME_TEXTDOMAIN)?>
                                <input type="text" name="created_to" id="created_to" class="date_created" placeholder="<?php echo __('To', EVENTIFYME_TEXTDOMAIN)?>" value="<?php echo isset($_REQUEST['created_to']) ? $_REQUEST['created_to'] : ''?>">
                            </label>
                        </div>
                    </div>

                    <div class="main-btns">
                        <a href="#" class="button eventify-me-custom-admin-filters-hide"><?php echo __('Hide filters', EVENTIFYME_TEXTDOMAIN)?></a>

                        <button type="submit" class="button" id="search-submit">
                            <?php echo __('Apply filter', EVENTIFYME_TEXTDOMAIN)?>
                        </button>
                    </div>
                </div>
                <a href="#" class="button eventify-me-custom-admin-filters-show"><?php echo __('Show filters', EVENTIFYME_TEXTDOMAIN)?></a>
            </div>

        <?php }
    }
}
//new Booking_List();
