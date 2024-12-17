<?php

class Eventify_Me_Emails{

    private static $email_settings;

    public function __construct() {
        self::$email_settings = Eventify_Me_Settings::get_email_settings();
        add_filter('replaceEmailTemplateVarsWithData', [$this, 'replaceEmailTemplateVarsWithData'], 10, 3);
    }

    static function sendMail($toEmail, $subject, $html){
        $headers = 'From: ' . self::$email_settings['eventify_me_email_from_name'] . ' <' . self::$email_settings['eventify_me_email_from_address'] . '>' . "\r\n" .
            'Content-Type: text/html';
        $html = self::changeAlignClassesToStylesInImage($html);
        return wp_mail($toEmail, $subject, $html, $headers);
    }

    static function sendEmailToClientAfterConfirmedBooking($bookingData){
        $html = apply_filters('replaceEmailTemplateVarsWithData', self::$email_settings['eventify_me_email_to_user_text'], $bookingData, true);
        $subject = apply_filters('replaceEmailTemplateVarsWithData', self::$email_settings['eventify_me_email_to_user_subject'], $bookingData, false);
        return self::sendMail($bookingData['email'], $subject, $html);
    }

    static function sendEmailToEventManagersAfterConfirmedBooking($bookingData){
        if(empty($bookingData['eventId'])) return false;
        $html = apply_filters('replaceEmailTemplateVarsWithData', self::$email_settings['eventify_me_email_to_event_managers_text'], $bookingData, true);
        $subject = apply_filters('replaceEmailTemplateVarsWithData', self::$email_settings['eventify_me_email_to_event_managers_subject'], $bookingData, false);

        $event_managers_id = get_post_meta($bookingData['eventId'], 'event_managers_id', true);
        if(empty($event_managers_id)) {
            $usersForEmail = get_users([
                'role__in' => array('administrator', 'eventify_me_manager'),
            ]);
        } else {
            $usersForEmail = get_users([
                'include' => $event_managers_id,
            ]);
        }

        foreach ($usersForEmail as $user) {
            if(!empty($user->user_email)) self::sendMail($user->user_email, $subject, $html);
        }

        return true;
    }

    public function replaceEmailTemplateVarsWithData($content, $data, $the_content = false){

        foreach ($data as $key => $value) {
            switch ($key){
				case 'first_name':
					$content = str_replace('{client_first_name}', $value, $content);
					break;
				case 'last_name':
					$content = str_replace('{client_last_name}', $value, $content);
					break;
                case 'email':
                    $content = str_replace('{client_email}', $value, $content);
                    break;
                case 'phone':
                    $content = str_replace('{client_phone}', $value, $content);
                    break;
                case 'comments':
                    $content = str_replace('{client_comments}', $value, $content);
                    break;
                case 'tickets':
                    $ticketsText = self::getTextForTicketsArr($value, $data['bookingId']);
                    $content = str_replace('{booked_sessions}', $ticketsText, $content);
                    break;
                case 'eventId':
                    //event id
                    $content = str_replace('{event_id}', $value, $content);

                    // event data
                    $eventDetails = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($value);
                    $content = str_replace('{event_address}', $eventDetails['event_address'], $content);
                    $content = str_replace('{event_title}', get_the_title($value), $content);

                    // prices
                    $prices = implode(', ', Eventify_Me_Helpers::getPreviewForEventPrices($eventDetails['event_price']));
                    $content = str_replace('{event_price}', $prices, $content);

                    //age cats
                    $ageCategories = '';
                    if(!empty($eventDetails['event_age_category'])) $ageCategories = Eventify_Me_Helpers::getSuitableAgeTextForEvent($value);
                    $content = str_replace('{event_age_categories}', $ageCategories, $content);

                    //languages
                    $allLang = Eventify_Me_Helpers::getAllCountries();
                    $langNames = [];
                    foreach ($eventDetails['event_language'] as $langCode){
                        $langNames[] = $allLang[$langCode];
                    }
                    $content = str_replace('{event_languages}', implode(', ', $langNames), $content);

                    //description
                    $content = str_replace('{event_description}', get_the_content(null, false, $value), $content);
                    break;
                default:
                    break;
            }
        }

        return $the_content ? apply_filters('the_content', $content) : $content;
    }

    static function getTextForTicketsArr($tickets, $bookingId){
        $result = '<div>';
        $sessionBookingObject = new Event_Session_Booking();
        foreach ($tickets as $sessionId => $ticket){
            $session = $sessionBookingObject->get_order_sessions(['booking_order_id' => $bookingId, 'event_session_id' => $sessionId])[0];
            $result .= $session['date'] . ' ' . $session['time_start'];
            $result .=  !empty($session['time_end']) ? ' - ' . $session['time_end'] : '';
            $result .= ' ' . $ticket['amount'] . ' ' . __('tickets') . '<br>';
        }
        $result .= '</div>';
        return $result;
    }

    static function changeAlignClassesToStylesInImage($html = '') {
        require_once EVENTIFYME_DIR_PATH . 'libraries/php/simplehtmldom/simple_html_dom.php';
        $htmlDom = str_get_html($html);

        foreach ($htmlDom->find('img') as $img) {
            if(strpos($img->class, 'alignright') !== false) {
                $img->style = 'display: block;margin: 0 0 0 auto';
            }

            if(strpos($img->class, 'aligncenter') !== false) {
                $img->style = 'display: block;margin: 0 auto';
            }
        }

        return $htmlDom;
    }
}

new Eventify_Me_Emails();