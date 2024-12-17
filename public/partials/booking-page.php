<?php
global $wp_query;

if(!isset($wp_query->query['post'])) {
    wp_redirect('/');
    exit;
}

$slug = $wp_query->query['post'];
$post = get_page_by_path( $slug, OBJECT, 'eventify-me-events' );

if(empty($post)) {
    wp_redirect('/');
    exit;
}

$post_id = $post->ID;
$customFields = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($post_id);
$eventSessions = Eventify_Me_Helpers::groupedEventSessionsByDate($customFields['event_sessions'], true);

$eventCanBook = false;
foreach ($eventSessions as $session){
    if($eventCanBook === true) break;
    foreach ($session as $time) {
        if($time['is_booking_enabled'] === 'yes') {
            $eventCanBook = true;
            break;
        }
    }
}

if(!$eventCanBook) {
    wp_redirect(get_the_permalink($post_id));
    exit;
}

get_header();
echo do_shortcode('[booking_event event_id="' . $post_id . '"]');
get_footer();