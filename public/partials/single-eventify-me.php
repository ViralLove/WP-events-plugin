<?php
if(!isset($preview_mode) || $preview_mode === false) $preview_mode = false;
else $preview_mode = true;

if(!$preview_mode) get_header();

$settingsObject = new Eventify_Me_Settings();

if ($preview_mode) $settings = $settingsObject->get_visual_settings('preview');
else $settings = $settingsObject->get_visual_settings();

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'css/single-eventify-me-styles.php';

if(!$preview_mode) {
    global $post;
} else $post = Eventify_Me_Helpers::getEvents(['posts_per_page' => 1, 'orderby' => 'ID'])[0];

$customFields = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($post->ID);
$nearestDate = Eventify_Me_Helpers::getNearestDateByPeriod($customFields['event_sessions'], 'current');
if(empty($nearestDate)) {
    $nearestDate = Eventify_Me_Helpers::getNearestDateByPeriod($customFields['event_sessions'], 'past');
}

if(!empty($_GET['preview']) && $_GET['preview'] === 'true') {
    $show_location_on_cards = true;
} else {
    $show_location_on_cards = !isset($_COOKIE['show_location']) ? 'true' : $_COOKIE['show_location'];
    if($show_location_on_cards == '1' || $show_location_on_cards == 'true') $show_location_on_cards = true;
    else $show_location_on_cards = false;
}


$eventSessions = Eventify_Me_Helpers::groupedEventSessionsByDate($customFields['event_sessions']);
$eventSessionsFuture = Eventify_Me_Helpers::groupedEventSessionsByDate($customFields['event_sessions'], true);

$eventCanBook = false;
foreach ($eventSessionsFuture as $session){
    if($eventCanBook === true) break;
    foreach ($session as $time) {
        if($time['is_booking_enabled'] === 'yes') {
            $eventCanBook = true;
            break;
        }
    }
}
?>
    <div id="eventify-me-event-wrap" class="single-eventify-me-wrap">
        <a href="#eventify-me-event-wrap" style="display: none" class="scroll-to-event"></a>
        <div class="event-container">
            <div class="eventify_me_notice eventify_me_notice-after-booking-confirmation" style="display: none">
                <p><?php echo __('Thank you for the successful booking!', EVENTIFYME_TEXTDOMAIN)?></p>
            </div>
        </div>
        <div class="event-covers">
            <div class="event-container">
                <div class="event-cover">
                    <div class="event-cover__img">
                        <img src="<?php echo $customFields['event_cover_single_page']?>" alt="<?php echo $post->post_title?>" title="<?php echo $post->post_title?>">
                    </div>
                    <div class="event-cover__info">
                        <div class="event-cover__info__date"><?php echo $nearestDate['date_single_page']?></div>
                        <div class="event-cover__info__time">
                            <?php echo __('Time', EVENTIFYME_TEXTDOMAIN)?>:
                            <span>
                                <?php echo $nearestDate['time_start']?>
                                <?php echo !empty($nearestDate['time_end']) ? '– ' . $nearestDate['time_end'] : ''?>
                            </span>
                        </div>
                        <?php if(!empty(!empty($nearestDate['time_end']))):?>
                            <div class="event-cover__info__duration">
                                <?php echo __('Duration', EVENTIFYME_TEXTDOMAIN)?>:
                                <span>
                                    <?php echo date('H', strtotime($nearestDate['time_end'])) - date('H', strtotime($nearestDate['time_start']))?> <?php echo __('hours', EVENTIFYME_TEXTDOMAIN)?>
                                </span>
                            </div>
                        <?php endif;?>
                        <?php if(count($eventSessions) > 1):?>
                          <a href="#when" class="event-cover__info__more"><?php echo __('More sessions', EVENTIFYME_TEXTDOMAIN)?></a>
                        <?php endif;?>
                        <div class="event-cover__info__tags">
                            <?php if(!empty($customFields['event_thematics'])):?>
                                <?php foreach ($customFields['event_thematics'] as $thematic):?>
                                    <div class="event-cover__info__tags-tag">
                                        #<?php echo $thematic->name?>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                        <?php if($eventCanBook):?>
                            <div class="event-cover__info__button">
                                <a href="<?php echo get_the_permalink($post->ID)?>/booking"><?php echo __('Sign up', EVENTIFYME_TEXTDOMAIN)?></a>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="event-informations">
            <div class="event-container">
                <div class="event-information">
                    <div class="event-information__info">
                        <div class="event-information__infoshare">
                            <div class="event-information__infoshare__info">
                                <a href="#what" class="event-information__infoshare__info-item"><?php echo __('WHAT', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#when" class="event-information__infoshare__info-item"><?php echo __('WHEN', EVENTIFYME_TEXTDOMAIN)?></a>
                                <?php if($show_location_on_cards):?>
                                    <a href="#where" class="event-information__infoshare__info-item"><?php echo __('WHERE', EVENTIFYME_TEXTDOMAIN) ?></a>
                                <?php endif;?>
                            </div>
<!--                            <div class="event-information__infoshare__share">-->
<!--                                <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                    <path fill-rule="evenodd" clip-rule="evenodd"-->
<!--                                          d="M13 0V2.21867C9.711 3.05467 5.02333 5.29167 3.5 10C7.306 6.66 12.6863 7.02467 13 7.039V9.25767L19 4.63267L13 0ZM0 2.5V15.5C0 15.7617 0.238333 16 0.5 16H13.5C13.7617 16 14 15.7617 14 15.5V9.74233L13.6093 10.047L13 10.5157V15H1V3H8.35933C9.073 2.604 9.79433 2.27467 10.5 2H0.5C0.200667 2.00533 0.00666667 2.27467 0 2.5Z"-->
<!--                                          fill="<?php //echo $settings['color_3']?>" />-->
<!--                                </svg>-->
<!--                                <span>--><?php //echo __('Share', EVENTIFYME_TEXTDOMAIN)?><!--</span>-->
<!--                            </div>-->
                        </div>
                        <!--  -->
                        <div class="event-information__info__title" id="what">
                            <span><?php echo $post->post_title?></span>
<!--                            <div class="event-information__info__sharemobile">-->
<!--                                <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                    <path fill-rule="evenodd" clip-rule="evenodd"-->
<!--                                          d="M13 0V2.21867C9.711 3.05467 5.02333 5.29167 3.5 10C7.306 6.66 12.6863 7.02467 13 7.039V9.25767L19 4.63267L13 0ZM0 2.5V15.5C0 15.7617 0.238333 16 0.5 16H13.5C13.7617 16 14 15.7617 14 15.5V9.74233L13.6093 10.047L13 10.5157V15H1V3H8.35933C9.073 2.604 9.79433 2.27467 10.5 2H0.5C0.200667 2.00533 0.00666667 2.27467 0 2.5Z"-->
<!--                                          fill="<?php //echo $settings['color_3']?>" />-->
<!--                                </svg>-->
<!--                            </div>-->
                        </div>
                        <div class="event-information__info__buttonmobile">
                            <a href="<?php echo get_the_permalink($post->ID)?>/booking">
                                <?php echo __('Sign up', EVENTIFYME_TEXTDOMAIN)?>
                            </a>
                        </div>
                        <div class="event-information__info__whatsthat">
                            <?php if(!empty($customFields['event_formats'])):?>
                                <?php echo implode(', ', array_column($customFields['event_formats'], 'name'))?>
                            <?php endif; ?>
                        </div>
                        <?php if(!empty($customFields['event_thematics'])):?>
                            <div class="event-information__info__tagsmobile">
                                <?php foreach ($customFields['event_thematics'] as $thematic):?>
                                    <div class="event-information__info__tagmobile">
                                        #<?php echo $thematic->name?>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        <?php endif;?>
                        <?php $suitableAgeText = Eventify_Me_Helpers::getSuitableAgeTextForEvent($post->ID);?>
                        <?php if(!empty($suitableAgeText) || !empty($customFields['event_language'])):?>
                            <div class="event-information__info__forwhom">
                                <?php if(!empty($suitableAgeText)):?>
                                    <div class="event-information__info__forwhom__people">
                                        <svg width="28" height="25" viewBox="0 0 28 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="ic_family">
                                                <path id="Stroke 1" fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M15.8246 14.1284C17.0861 13.2448 17.9417 11.5283 17.9417 9.55596C17.9417 6.68096 15.5864 5.72168 13.8836 5.72168C12.1804 5.72168 9.82511 6.68096 9.82511 9.55596C9.82511 11.5283 10.6808 13.2448 11.9423 14.1284C9.36822 14.9574 6.95795 17.2921 6.95795 20.044C6.95795 23.4901 10.4206 23.8492 13.8836 23.8492C17.3462 23.8492 20.8078 23.4901 20.8078 20.044C20.8078 17.2921 18.3986 14.9574 15.8246 14.1284Z"
                                                      stroke="<?php echo $settings['color_3']?>" stroke-width="1.40214" />
                                                <path id="Stroke 2"
                                                      d="M9.93892 8.97382C9.50259 8.71127 9.03896 8.49967 8.56932 8.34787C9.62027 7.61257 10.3334 6.18197 10.3334 4.53836C10.3334 2.14176 8.37011 1.34277 6.95089 1.34277C5.53166 1.34277 3.56977 2.14176 3.56977 4.53836C3.56977 6.18197 4.28293 7.61257 5.33388 8.34787C3.18909 9.03893 1.18005 10.9851 1.18005 13.278C1.18005 16.1488 4.06529 16.4502 6.95089 16.4502C7.43826 16.4502 7.92421 16.4407 8.39776 16.4131"
                                                      stroke="<?php echo $settings['color_3']?>" stroke-width="1.40214" />
                                                <path id="Stroke 3"
                                                      d="M17.8545 8.95577C18.2838 8.70171 18.7364 8.49577 19.1961 8.34787C18.1452 7.61257 17.432 6.18197 17.432 4.53836C17.432 2.14176 19.3943 1.34277 20.8131 1.34277C22.2338 1.34277 24.1957 2.14176 24.1957 4.53836C24.1957 6.18197 23.4829 7.61257 22.4319 8.34787C24.5767 9.03893 26.5843 10.9851 26.5843 13.278C26.5843 16.1488 23.6987 16.4502 20.8131 16.4502C20.3204 16.4502 19.826 16.4407 19.3457 16.4117"
                                                      stroke="<?php echo $settings['color_3']?>" stroke-width="1.40214" />
                                            </g>
                                        </svg>
                                        <span class="event-information__info__forwhom__people__class">
                                            <?php echo $suitableAgeText?>
                                        </span>
                                    </div>
                                <?php endif;?>
                                <!--  -->
                                <?php if(!empty($customFields['event_language'])):?>
                                <div class="event-information__info__forwhom__lang">
                                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path id="ic_languages" fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.3565 0C5.0942 0 0 5.15831 0 11.4994C0 17.8405 5.0942 23 11.3565 23H22.4338C22.6627 23 22.8685 22.8599 22.957 22.6467C23.0443 22.4322 22.9961 22.1863 22.8358 22.0224L19.9134 19.0628C21.7235 16.9666 22.7141 14.3023 22.7141 11.501C22.7125 5.15831 17.6187 0 11.3565 0ZM11.0002 21.9971C5.48665 21.9971 1 17.2865 1 11.4977C1 5.70898 5.48665 1 11.0002 1C16.5133 1 21 5.71062 21 11.4977C21 14.2249 20.0098 16.8066 18.2107 18.7725C18.2107 18.7725 18.2091 18.7725 18.2091 18.7741L18.2075 18.7754C18.2044 18.7786 18.2044 18.7787 18.2013 18.7819C18.1845 18.7995 18.1787 18.8237 18.1635 18.8429C18.1346 18.8843 18.1026 18.9261 18.0843 18.974C18.0749 18.9977 18.0749 19.0252 18.0691 19.0505C18.0582 19.0984 18.0445 19.1435 18.0461 19.1914C18.0461 19.2233 18.0582 19.2524 18.0628 19.2844C18.0707 19.3257 18.0718 19.3691 18.0886 19.4088C18.1038 19.4457 18.1299 19.476 18.1526 19.5096C18.1709 19.5386 18.1834 19.5706 18.2075 19.596L18.2153 19.6042C18.2153 19.6042 18.2153 19.6042 18.2153 19.6058L20.4942 22H11.0002V21.9971Z"
                                              fill="<?php echo $settings['color_3']?>" />
                                    </svg>

                                    <span class="event-information__info__forwhom__lang__langs">
                                        <?php
                                        $allLang = Eventify_Me_Helpers::getAllCountries();
                                        $langNames = [];
                                        foreach ($customFields['event_language'] as $langCode){
                                            $langNames[] = $allLang[$langCode];
                                        }
                                        echo implode(', ', $langNames)?>
                                    </span>

                                </div>
                                <?php endif;?>
                                <!--  -->
                            </div>
                        <?php endif;?>
                        <div class="event-information__info__forwhom__description">
                            <div class="event-information__info__forwhom__pricemobile">
                                <?php echo $customFields['event_price'];?>
                            </div>
                            <?php echo apply_filters('the_content', $post->post_content);?>
                        </div>
                        <!--  -->
                        <?php
                        if( $images = get_posts( array(
                            'post_type' => 'attachment',
                            'orderby' => 'post__in', /* we have to save the order */
                            'order' => 'ASC',
                            'post__in' => explode(',', $customFields['event_photos']), /* $value is the image IDs comma separated */
                            'numberposts' => -1,
                            'post_mime_type' => 'image'
                        ) ) ):
                        ?>
                            <div class="event-information__info__forwhom__slidermobile">
                                <div class="swiper-mobile">
                                    <div class="swiper-mobile-block">
                                        <div class="swiper-mobile-arrows">
                                            <div class="swiper-button-prev">
                                                <svg width="25" height="36" viewBox="0 0 34 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M0 31.1127L31.1127 0L33.234 2.12132L3.73833 31.617L33.234 61.1127L31.1127 63.234L0 32.1213L0.504313 31.617L0 31.1127Z"
                                                          fill="white" />
                                                </svg>
                                            </div>
                                            <div class="swiper-button-next">
                                                <svg width="25" height="36" viewBox="0 0 34 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M33.234 31.1127L2.12131 0L-7.62939e-06 2.12132L29.4957 31.617L-7.62939e-06 61.1127L2.12131 63.234L33.234 32.1213L32.7297 31.617L33.234 31.1127Z"
                                                          fill="white" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="swiper-wrapper">
                                            <?php foreach( $images as $key => $image ): ?>
                                                <?php
                                                $image_src = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                                                <div class="swiper-slide">
                                                    <div class="swiper-slide-container">
                                                        <img src="<?php echo $image_src[0]?>" alt="">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event-information__price__slider">
                                <?php foreach( $images as $key => $image ): ?>
                                    <?php
                                    if(count($images) > 5 && ($key + 1) === 6) break;
                                    $image_src = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                                    <div class="event-information__price__slider-slide" data-all-images="<?php echo count($images)?>">
                                        <img src="<?php echo $image_src[0]?>" alt="">

                                        <?php if(count($images) > 5 && ($key + 1) === 5):?>
                                            <div class="event-information__price__slider-slide__counter">
                                                <span>+<?php echo count($images) - ($key + 1)?></span>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif;?>

                        <?php if(!empty($eventSessions)):?>
                        <div id="when" class="event-information__price__slider__when">
                            <div class="event-information__price__slider__when__title"><?php echo __('WHEN', EVENTIFYME_TEXTDOMAIN)?></div>
                            <div class="event-information__price__slider__when__dates">
                                <div class="event-information__price__slider__when__dates__datelist">
                                    <?php foreach ($eventSessions as $date => $session):?>
                                        <div class="event-information__price__slider__when__dates__datelist-item"><?php echo $date?></div>
                                    <?php endforeach;?>
                                </div>
                                <!--  -->
                                <div class="event-information__price__slider__when__weekdaylist">
                                    <?php foreach ($eventSessions as $date => $session) :?>
                                        <?php
										                    $dateTime = new DateTime(str_replace('/', '-', $date));
                                        $dateFormatter = \IntlDateFormatter::create(
											                    get_locale(),
                                          \IntlDateFormatter::NONE,
                                          \IntlDateFormatter::NONE,
                                          \date_default_timezone_get(),
                                          \IntlDateFormatter::GREGORIAN,
                                          'EEEE'
                                        ); ?>
                                        <div class="event-information__price__slider__when__weekdaylist-item">
                                            <?php // echo date('l', strtotime(str_replace('/', '-', $date)))?>
                                            <?php echo $dateFormatter->format($dateTime)?>
                                        </div>
                                        <div class="event-information__price__slider__when__weekdaylist-item-mobile">
                                            <?php //echo date('D', strtotime(str_replace('/', '-', $date)))?>
                                            <?php
											                        $dateFormatter->setPattern('EEE');
                                              echo $dateFormatter->format($dateTime);
                                            ?>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                                <!--  -->
                                <div class="event-information__price__slider__when__timelist">
                                    <?php
                                    foreach ($eventSessions as $date => $session) {
                                        echo '<div class="event-information__price__slider__when__timelist-item">';
                                            foreach ($session as $key => $time) {
                                                if(count($session) !== ($key + 1)) {
                                                    echo $time['time_start'];
                                                    echo !empty($time['time_end']) ? ' - ' . $time['time_end'] : '';
                                                    echo ', ';
                                                }
                                                else {
                                                    echo $time['time_start'];
                                                    echo !empty($time['time_end']) ? ' - ' . $time['time_end'] : '';
                                                }
                                            }
                                        echo '</div>';
                                    }?>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                        <!--  -->
                        <?php if(!empty($customFields['event_address']) && $show_location_on_cards):?>
                            <div class="event-information__where" id="where">
                                <div class="event-information__where__title"><?php echo __('WHERE', EVENTIFYME_TEXTDOMAIN)?></div>
                                <div class="event-information__where__geo">

                                    <?php if(!empty($customFields['event_location_place_title'])):?>
                                        <div class="event-information__where__geo__address__name">
                                            <?php echo $customFields['event_location_place_title']?>
                                        </div>
                                    <?php endif;?>

                                    <svg width="14" height="20" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M0 7C0 3.13 3.13 0 7 0C10.87 0 14 3.13 14 7C14 12.25 7 20 7 20C7 20 0 12.25 0 7ZM4.5 7C4.5 8.38 5.62 9.5 7 9.5C8.38 9.5 9.5 8.38 9.5 7C9.5 5.62 8.38 4.5 7 4.5C5.62 4.5 4.5 5.62 4.5 7Z"
                                              fill="<?php echo $settings['color_3']?>" />
                                    </svg>
                                    <div class="event-information__where__geo__address">
                                        <?php echo $customFields['event_address']?>
                                    </div>
                                </div>
                                <?php if(!empty($customFields['event_location_comments'])):?>
                                    <div class="event-information__where__geo__extrainfo">
                                        <?php echo apply_filters('the_content', $customFields['event_location_comments'])?>
                                    </div>
                                <?php endif;?>
                            </div>
                        <?php endif;?>
                    </div>

                    <!--  -->
                    <?php if(!empty($customFields['event_price'])):?>
                        <div class="event-information__price">
                            <div class="event-information__price__title">
                                <?php echo __('PRICE', EVENTIFYME_TEXTDOMAIN)?>
                            </div>
                            <div class="event-information__price__info">
                                <?php echo apply_filters('the_content', $customFields['event_price']);?>
                            </div>
                        </div>
                    <?php endif;?>
                    <!--  -->
                </div>
                <?php require_once 'eventify-me-plugin-ad-text.php'?>
            </div>
        </div>

        <!-- slider -->
        <!-- Swiper -->
        <?php if(!empty($images)):?>
            <div class="slider-event">
                <div class="slider-event-close">
                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M13 11.4706L1.52941 0L0 1.52941L11.4706 13L2.07973e-06 24.4706L1.52941 26L13 14.5294L24.4706 26L26 24.4706L14.5294 13L26 1.52941L24.4706 0L13 11.4706Z"
                              fill="white" />
                    </svg>

                </div>
                <div class="slider-event-overlay"></div>
                <!-- Add Arrows -->
                <div class="slider-event-arrows">
                    <div class="swiper-button-next">
                        <svg width="34" height="64" viewBox="0 0 34 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M33.234 31.1127L2.12131 0L-7.62939e-06 2.12132L29.4957 31.617L-7.62939e-06 61.1127L2.12131 63.234L33.234 32.1213L32.7297 31.617L33.234 31.1127Z"
                                  fill="white" />
                        </svg>
                    </div>
                    <div class="swiper-button-prev">
                        <svg width="34" height="64" viewBox="0 0 34 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M0 31.1127L31.1127 0L33.234 2.12132L3.73833 31.617L33.234 61.1127L31.1127 63.234L0 32.1213L0.504313 31.617L0 31.1127Z"
                                  fill="white" />
                        </svg>
                    </div>
                </div>
                <div class="swiper-slider-container">
                    <div class="event-container swiper-slider-content">
                        <div class="swiper-container gallery-top">
                            <div class="swiper-wrapper">
                                <?php foreach( $images as $key => $image ): ?>
                                    <?php $image_src = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                                    <div class="swiper-slide">
                                        <div class="swiper-slide-container">
                                            <img src="<?php echo $image_src[0]?>" alt="">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="gallery-thumbs-container">
                            <div class="gallery-thumbs-counter">
                                <span>1</span>/<span>12</span>
                            </div>
                            <div class="swiper-container gallery-thumbs">
                                <div class="swiper-wrapper">
                                    <?php foreach( $images as $key => $image ): ?>
                                        <?php $image_src = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                                        <div class="swiper-slide">
                                            <div class="swiper-slide-container" id="slider-<?php echo ($key + 1)?>">
                                                <img src="<?php echo $image_src[0]?>" alt="">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>
        <!-- slider end -->
    </div>

<?php if(!$preview_mode) get_footer();