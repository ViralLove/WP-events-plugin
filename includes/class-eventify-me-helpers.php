<?php

class Eventify_Me_Helpers {
    static function showTermsInCheckboxGroup($terms, $selectedTerms = [], $withChild = false){
        if(empty($terms)) return '';

        $html = '';

        $i = 0;
        $part = round(count($terms) / 3);
        $part = $part == 0 ? 1 : $part;
        foreach ($terms as $term):
            if($i % $part === 0) $html .= '<div class="checkbox-column">';
            $html .= '<label>';
                $checked = $selectedTerms && in_array($term->term_id, $selectedTerms) ? 'checked="checked"' : '';
                $html .= '<input type="checkbox" name="' . $term->taxonomy . '[]" value="' . $term->term_id . '" ' . $checked . '>';
                $html .= $term->name;
            $html .= '</label>';

            if($withChild) {
                $childTerms = get_terms([
                    'taxonomy' => $term->taxonomy,
                    'hide_empty' => false,
                    'parent' => $term->term_id
                ]);

                if(!empty($childTerms)){
                    $html .= '<div class="checkbox-group-child">';
                        $html .= self::showTermsInCheckboxGroup($childTerms, $selectedTerms,true);
                    $html .= '</div>';
                }
            }
            if(($i + 1) % $part === 0 || count($terms) === ($i +1)) $html .= '</div>';
            $i++;
        endforeach;

        return $html;
    }

    static function getAllCountries(){
        return [
            "EE" => __("Estonian", EVENTIFYME_TEXTDOMAIN),
            "RU" => __("Russian", EVENTIFYME_TEXTDOMAIN),
            "EN" => __("English", EVENTIFYME_TEXTDOMAIN),
            "FI" => __("Finish", EVENTIFYME_TEXTDOMAIN),
            "LV" => __("Latvian", EVENTIFYME_TEXTDOMAIN),
            "LT" => __("Lithuanian", EVENTIFYME_TEXTDOMAIN),
            "ES" => __("Spanish", EVENTIFYME_TEXTDOMAIN),
            "FR" => __("French", EVENTIFYME_TEXTDOMAIN),
            "DE" => __("German", EVENTIFYME_TEXTDOMAIN),
            "IT" => __("Italian", EVENTIFYME_TEXTDOMAIN),
            "NL" => __("Netherlands", EVENTIFYME_TEXTDOMAIN),
        ];
    }

    static function getAllSuitableAge(){
        return [
            'baby' => __('Baby', EVENTIFYME_TEXTDOMAIN),
            'toddler' => __('Toddler', EVENTIFYME_TEXTDOMAIN),
            'child' => __('Child', EVENTIFYME_TEXTDOMAIN),
            'teen' => __('Teen', EVENTIFYME_TEXTDOMAIN),
            'adult' => __('Adult', EVENTIFYME_TEXTDOMAIN),
            'senior' => __('Senior', EVENTIFYME_TEXTDOMAIN),
        ];
    }

    static function getSuitableAgeTextForEvent($event_id){
        $eventAgeCategory = self::getAllSuitableAge();

        $eventAgeCategorySelected = get_post_meta($event_id, 'event_age_category', true);
        $eventAgeCategorySelected = !empty($eventAgeCategorySelected) ? $eventAgeCategorySelected : [];

        $eventAgeCategoryKeys = array_keys($eventAgeCategory);

        $res = '';
        $bunch = '';
        foreach ($eventAgeCategorySelected as $key => $cat) {
            $currentCatIndex = array_search($cat, $eventAgeCategoryKeys);
            $nextCat = isset($eventAgeCategoryKeys[($currentCatIndex + 1)]) ? $eventAgeCategoryKeys[($currentCatIndex + 1)] : NULL;
            $nextSelectedCat = isset($eventAgeCategorySelected[($key + 1)]) ? $eventAgeCategorySelected[($key + 1)] : NULL;

            if(empty($nextCat) || empty($nextSelectedCat) || $nextCat !== $nextSelectedCat && empty($bunch)) {
                if(strlen($bunch) === 0) {
                    if(strlen($res) > 0)
                        $res .= ', ' . $eventAgeCategory[$cat];
                    else $res .= $eventAgeCategory[$cat];
                } else {
                    $bunch .= $eventAgeCategory[$cat];

                    if(strlen($res) > 0)
                        $res .= ', ' . $bunch;
                    else $res .= $bunch;
                    $bunch = '';
                }
            } else {
                if(strlen($bunch) === 0) {
                    if ($nextCat !== $nextSelectedCat) {
                        if(strlen($res) > 0) $res .= ', ' . $eventAgeCategory[$cat];
                        else $res .= $eventAgeCategory[$cat];
                    } else $bunch .= $eventAgeCategory[$cat] . ' - ';
                } else {
                    if (empty($nextCat) || empty($nextSelectedCat) || $nextCat !== $nextSelectedCat) {
                        $bunch .= $eventAgeCategory[$cat];

                        if(strlen($res) > 0)
                            $res .= ', ' . $bunch;
                        else $res .= $bunch;
                        $bunch = '';
                    }
                }
            }
        }

        return $res;
    }

    static function getEvents($atts = []){
        $args = shortcode_atts([
            'post_type' => 'eventify-me-events',
            'posts_per_page' => -1,
            'post__in' => [],
            'orderby' => 'date',
            'post_status' => 'publish'
        ], $atts);

        $events = new WP_Query($args);
        wp_reset_postdata();
        return $events->posts;
    }

    static function getEventsIdsByPeriod($period = 'current') {
        if($period === 'current')
         return Eventify_Me_Filter_Events_Admin::getEventsByTimeSessions(date('d/m/Y', strtotime(date('Y-m-d') . ' +10 year')) . ' to ' . date('d/m/Y') );

        return Eventify_Me_Filter_Events_Admin::getEventsByTimeSessions(date('d/m/Y') . ' to ' . date('d/m/Y', strtotime(date('Y-m-d') . ' -10 year')));
    }

    static function getAllCustomFieldsOfEvent($event_id){
        if(!get_post_status($event_id)) return [];
        $eventAgeCategorySelected = get_post_meta($event_id, 'event_age_category', true);
        $eventAgeCategorySelected = !empty($eventAgeCategorySelected) ? $eventAgeCategorySelected : [];

        $sessionObject = new Event_Session();
        $eventSessions = $sessionObject->get_sessions(['event_id' => $event_id]);

        return [
            'event_formats' => get_the_terms($event_id, 'event_formats'),
            'event_thematics' => get_the_terms($event_id, 'event_thematics'),
            'event_cover_single_page' => get_post_meta($event_id, 'event_cover_single_page', true),
            'event_cover_card' => get_post_meta($event_id, 'event_cover_card', true),
            'event_photos' => get_post_meta($event_id, 'event_photos', true),
            'event_age_category' => $eventAgeCategorySelected,
            'event_children_allowed' => get_post_meta($event_id, 'event_children_allowed', true),
            'event_language' => get_post_meta($event_id, 'event_language', true),
            'event_sessions' => $eventSessions,
            'event_address' => get_post_meta($event_id, 'event_address', true),
            'event_location_place_title' => get_post_meta($event_id, 'event_location_place_title', true),
            'event_location_comments' => get_post_meta($event_id, 'event_location_comments', true),
            'event_price' => get_post_meta($event_id, 'event_price', true),
            'event_is_free' => get_post_meta($event_id, 'event_is_free', true),
            'event_managers_id' => get_post_meta($event_id, 'event_managers_id', true),
        ];
    }

    static function getPreviewForEventPrices($string) {
        $result = self::getTextBetweenTags($string, 'h1');

        if(empty($result)) {
            $result = preg_split('#\r?\n#', $string, 0);
            if(count($result) > 1) $result = [];
            else $result = [$result[0]];
        }

        return $result;
    }

    static function getTextBetweenTags($string, $tagname) {
        $string = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>' . $string . '</body></html>'; // set correct encoding
        $d = new DOMDocument();
        $d->loadHTML($string);
        $result = array();
        foreach($d->getElementsByTagName($tagname) as $item){
            $result[] = $item->nodeValue;
        }
        return $result;
    }

    static function filterArrayByDate($a, $b){
        $aDate = date('Y-m-d H', strtotime( str_replace('/', '-', $a['date'] . ' ' . $a['time_start'])));
        $bDate = date('Y-m-d H', strtotime( str_replace('/', '-', $b['date'] . ' ' . $b['time_start'])));
        return $aDate > $bDate;
    }

    static function getNearestDateByPeriod($sessions, $period = 'current'){
        usort($sessions, ['Eventify_Me_Helpers', 'filterArrayByDate']);
		$fmt = new \IntlDateFormatter(get_locale(), NULL, NULL);

        $result = [];
        if($period === 'current') {
            foreach ($sessions as $session) {
                $date = date('Y-m-d', strtotime(str_replace('/', '-', $session['date'])));
                if($date < date('Y-m-d')) continue;
	
				$fmt->setPattern('EEEE d MMMM yyyy');
                $result['date'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
				
				$fmt->setPattern('d MMM yyyy, EEE, ');
                $result['date_mobile'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
	
				$fmt->setPattern('d MMMM yyyy, EEEE');
                $result['date_single_page'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
				
                $result['time_start'] = $session['time_start'];
                $result['time_end'] = $session['time_end'];
				$result['timestamp'] = strtotime(str_replace('/', '-', $session['date']));
                break;
            }
        } else {
            $count = count($sessions);

            foreach ($sessions as $key => $session) {
                $date = date('Y-m-d', strtotime(str_replace('/', '-', $session['date'])));

                if($date < date('Y-m-d') && ($key + 1) === $count) {
					$fmt->setPattern('EEEE d MMMM yyyy');
					$result['date'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
	
					$fmt->setPattern('d MMM yyyy, EEE, ');
					$result['date_mobile'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
					
					$fmt->setPattern('d MMMM yyyy, EEEE');
					$result['date_single_page'] = $fmt->format(new DateTime(str_replace('/', '-', $session['date'])));
					
                    $result['time_start'] = $session['time_start'];
                    $result['time_end'] = $session['time_end'];
					$result['timestamp'] = strtotime(str_replace('/', '-', $session['date']));
                }

                if($date < date('Y-m-d')) continue;
                $setKey = $key === 0 ? $key : $key -1;
				
				$fmt->setPattern('EEEE d MMMM yyyy');
				$result['date'] = $fmt->format(new DateTime(str_replace('/', '-', $sessions[$setKey]['date'])));
				
				$fmt->setPattern('d MMM yyyy, EEE, ');
				$result['date_mobile'] = $fmt->format(new DateTime(str_replace('/', '-', $sessions[$setKey]['date'])));
				
				$fmt->setPattern('d MMMM yyyy, EEEE');
				$result['date_single_page'] = $fmt->format(new DateTime(str_replace('/', '-', $sessions[$setKey]['date'])));
				
                $result['time_start'] = $sessions[$setKey]['time_start'];
                $result['time_end'] = $sessions[$setKey]['time_end'];
				$result['timestamp'] = strtotime(str_replace('/', '-', $sessions[$setKey]['date']));
                break;
            }
        }

        return $result;
    }

    static function sortEvents($events, $period = 'current'){
		$sessionObject = new Event_Session();
		$result = [];
		$i = 0;
        foreach ($events as $key => $event){
//            $events[$key]->custom_fields = self::getAllCustomFieldsOfEvent($event->ID);
//            $events[$key]->custom_fields['event_nearest_session'] =  self::getNearestDateByPeriod($events[$key]->custom_fields['event_sessions'], $period);
			$eventSessions = $sessionObject->get_sessions(['event_id' => $event->ID]);
			foreach ($eventSessions as $event_session) {
				$nearest_session = self::getNearestDateByPeriod([$event_session], $period);
				if(empty($nearest_session)) continue;
				
				$result[$i] = clone $event;
				$result[$i]->custom_fields = self::getAllCustomFieldsOfEvent($event->ID);
				$result[$i]->custom_fields['event_nearest_session'] = $nearest_session;
				$i++;
			}
        }

        if($period === 'current') {
            usort($result, ['Eventify_Me_Helpers', 'filterArrayByDateEventObjectCurrent']);
        } else {
            usort($result, ['Eventify_Me_Helpers', 'filterArrayByDateEventObjectPast']);
        }

        return $result;
    }

    static function filterArrayByDateEventObjectCurrent($a, $b){
        return date('Y-m-d', $a->custom_fields['event_nearest_session']['timestamp']) > date('Y-m-d', $b->custom_fields['event_nearest_session']['timestamp']);
    }

    static function filterArrayByDateEventObjectPast($a, $b){
        return date('Y-m-d', $a->custom_fields['event_nearest_session']['timestamp']) < date('Y-m-d', $b->custom_fields['event_nearest_session']['timestamp']);
    }

    static function groupedEventSessionsByDate($event_sessions, $future = false){
        if(empty($event_sessions)) return [];
        // sort sessions by date from first
        usort($event_sessions, function ($a, $b) {
            return date('Y-m-d', strtotime(str_replace('/', '-', $a['date']))) > date('Y-m-d', strtotime(str_replace('/', '-', $b['date'])));
        });

        $result = [];
        foreach ($event_sessions as $session){
            $sessionDate = date('Y-m-d', strtotime(str_replace('/', '-', $session['date'])));

            if($future && $sessionDate < date('Y-m-d')) continue;

            $result[$session['date']][] = [
                'id' => $session['id'],
                'date' => date('Y-m-d', strtotime(str_replace('/', '-', $sessionDate))),
                'time_start' => $session['time_start'],
                'time_end' => $session['time_end'],
                'is_booking_enabled' => $session['is_booking_enabled'],
            ];

            usort($result[$session['date']], function ($a, $b) {
                return date('H', strtotime($a['time_start'])) > date('H', strtotime($b['time_start']));
            });
        }

        return $result;
    }

    static function generate_random_digits_and_letters($length = 1) {
        return substr( sha1( mt_rand() ),17, $length);
    }
}