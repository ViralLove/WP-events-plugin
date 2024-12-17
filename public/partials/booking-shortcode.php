<?php
$customFields = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($event->ID);
$eventSessions = Eventify_Me_Helpers::groupedEventSessionsByDate($customFields['event_sessions'], true);
$bookingSettings = get_option('eventify_me_booking_settings');

$urlAfterConfirmation =
    $bookingSettings['after_booking_confirmed_redirect'] === 'redirect_to_specific_page'
    && !empty($bookingSettings['after_booking_confirmed_redirect_page_id'])
    ? get_the_permalink($bookingSettings['after_booking_confirmed_redirect_page_id'])
    : get_the_permalink($event->ID);

$countOfSessions = 0;
foreach ($eventSessions as $sessions) {
    foreach ($sessions as $session) {
        $countOfSessions++;
    }
}
?>
<div
     id="eventify-me-booking-shortcode"
     class="booking-shortcodes"
     data-event-id="<?php echo $event->ID?>"
     data-ajax-url="<?php echo admin_url('admin-ajax.php')?>"
     data-url-after-confirmation="<?php echo $urlAfterConfirmation?>"
     data-type-confirmation="<?php echo $bookingSettings['after_booking_confirmed_redirect']?>"
>
    <a href="#eventify-me-booking-shortcode" style="display: none" class="scroll-to-booking"></a>
    <div class="booking-shortcode-container" id="<?php echo $id?>" style="background-color: transparent;">
        <div class="booking-shortcode">
            <div class="booking-shortcode__title"><a href="<?php echo get_the_permalink($event->ID)?>"><span>←</span> <?php echo __('Back', EVENTIFYME_TEXTDOMAIN)?></a> <?php echo $event->post_title?></div>
            <div class="booking-accrodion">
                <div class="booking-accrodion-item">
                    <div class="booking-accrodion-item__parent">
                        <div class="booking-accrodion-item__parent__numbertitle">
                            <span class="booking-accrodion-item__parent__number">1</span>
                            <span class="booking-accrodion-item__parent__title"><?php echo __('Tickets', EVENTIFYME_TEXTDOMAIN)?></span>
                        </div>
                        <div class="booking-accrodion-item__parent__totaltickets">
                            <span class="count"><?php echo $countOfSessions?></span>
                            <?php echo __('tickets', EVENTIFYME_TEXTDOMAIN)?>
                        </div>
                    </div>
                    <div class="booking-accrodion-item__children">
                        <div class="booking-accrodion-item__children__address">
                            <svg width="14" height="20" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M0 7C0 3.13 3.13 0 7 0C10.87 0 14 3.13 14 7C14 12.25 7 20 7 20C7 20 0 12.25 0 7ZM4.5 7C4.5 8.38 5.62 9.5 7 9.5C8.38 9.5 9.5 8.38 9.5 7C9.5 5.62 8.38 4.5 7 4.5C5.62 4.5 4.5 5.62 4.5 7Z"
                                      fill="#169688" />
                            </svg>
                            <div class="booking-accrodion-item__children__address__place">
                                <span><?php echo $customFields['event_address']?></span>
                            </div>
                        </div>
                        <!--  -->
                        <div class="booking-accrodion-item__children-infolist">
                            <?php foreach ($eventSessions as $date => $sessions):?>
                                <?php foreach ($sessions as $session): ?>
                                    <?php if($session['is_booking_enabled'] === 'no') continue;?>
                                    <div class="booking-accrodion-item__children-info" data-session-id="<?php echo $session['id']?>">
                                        <div
                                            class="booking-accrodion-item__children--info__date"
                                            data-format-date="<?php echo date('Y-m-d', strtotime($session['date'])) . ' ' . $session['time_start'] . ':00'?>"
                                        >
                                            <?php
											                      $fmt = new \IntlDateFormatter(get_locale(), NULL, NULL);
											                      $fmt->setPattern('d MMMM, EEEE, ');
                                            echo $fmt->format(new DateTime(str_replace('/', '-', $session['date'])))?><span><?php echo $session['time_start']?></span>
                                        </div>
                                        <div class="booking-accrodion-item__children-info__counter">
                                            <div class="booking-accrodion-item__children-info__counter__minus active">
                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                            d="M0.5 4C0.5 2.067 2.067 0.5 4 0.5H32C33.933 0.5 35.5 2.067 35.5 4V32C35.5 33.933 33.933 35.5 32 35.5H4C2.067 35.5 0.5 33.933 0.5 32V4Z"
                                                            fill="#fff" stroke="#EDEDED"></path>
                                                    <path d="M8 16.8889H28V19.1111H8V16.8889Z" fill="#C7C7C7"></path>
                                                </svg>
                                            </div>
                                            <div class="booking-accrodion-item__children-info__counter__number">
                                                <span>1</span>
                                            </div>
                                            <div class="booking-accrodion-item__children-info__counter__plus">
                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                            d="M0 4C0 1.79086 1.79086 0 4 0H32C34.2091 0 36 1.79086 36 4V32C36 34.2091 34.2091 36 32 36H4C1.79086 36 0 34.2091 0 32V4Z"
                                                            fill="#45ABA0" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M19.1111 8H16.8889V16.8889H8V19.1111H16.8889V28H19.1111V19.1111H28V16.8889H19.1111V8Z" fill="white" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;?>

                            <?php endforeach; ?>
                        </div>
                        <!--  -->
                        <div class="booking-accrodion-item__children__button">
                            <a class="proceed-button"><?php echo __('Proceed', EVENTIFYME_TEXTDOMAIN)?></a>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="booking-accrodion-item">
                    <div class="booking-accrodion-item__parent">
                        <div class="booking-accrodion-item__parent__numbertitle">
                            <span class="booking-accrodion-item__parent__number">2</span>
                            <span class="booking-accrodion-item__parent__title"><?php echo __('Contact details', EVENTIFYME_TEXTDOMAIN)?></span>
                        </div>
                        <div class="booking-accrodion-item__parent__totaltickets">

                        </div>
                    </div>
                    <div class="booking-accrodion-item__children">
                        <form class="booking-accrodion-item__children__form">
                            
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_first_name">
                                <label for="first_name"><?php echo __('Your first name', EVENTIFYME_TEXTDOMAIN)?> <span>*</span></label>
                                <input id="first_name" type="text" required>
                            </div>
                            
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_last_name">
                                <label for="last_name"><?php echo __('Your last name', EVENTIFYME_TEXTDOMAIN)?> <span>*</span></label>
                                <input id="last_name" type="text" required>
                            </div>
                            
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_acc-name">
                                <label for="acc-name"><?php echo __('Your e-mail', EVENTIFYME_TEXTDOMAIN)?> <span>*</span></label>
                                <input id="acc-name" type="email" required>
                            </div>

                          <!--if mailchimp plugin is active added custom integration-->
                          <?php if(class_exists('MC4WP_Custom_Integration') && class_exists('MC4WP_MailChimp')):?>
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_acc-name">
                              <label for="acc-subscription">
                                <input type="checkbox" id="acc-subscription" name="mc4wp-subscribe" value="1">
                                <?php echo __('I want to get updates about upcoming events.', EVENTIFYME_TEXTDOMAIN)?>
                                <br>
                                <span><?php echo __('If you already do, we will just ignore this.', EVENTIFYME_TEXTDOMAIN)?></span>
                              </label>
                            </div>
                          <?php endif;?>
                            <!--  -->
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_acc-number">
                                <label for="acc-number"><?php echo __('Your phone number', EVENTIFYME_TEXTDOMAIN)?> <span>*</span></label>
                                <input type="text" id="acc-number" required>
                            </div>
                            <!--  -->
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_acc-textarea">
                                <label for="acc-textarea"><?php echo __('Additional comments (if needed)', EVENTIFYME_TEXTDOMAIN)?></label>
                                <textarea id="acc-textarea"></textarea>
                            </div>
                            <div class="booking-accrodion-item__children__button">
                                <button class="proceed-button"><?php echo __('Proceed', EVENTIFYME_TEXTDOMAIN)?></button>
                            </div>
                        </form>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <div class="booking-accrodion-item">
                    <div class="booking-accrodion-item__parent">
                        <div class="booking-accrodion-item__parent__numbertitle">
                            <span class="booking-accrodion-item__parent__number">3</span>
                            <span class="booking-accrodion-item__parent__title"><?php echo __('Confirmation', EVENTIFYME_TEXTDOMAIN)?></span>
                        </div>
                        <div class="booking-accrodion-item__parent__totaltickets">

                        </div>
                    </div>
                    <div class="booking-accrodion-item__children">
                        <div class="booking-accrodion-item__children__tickets">
                            <!--  -->
                        </div>
                        <!--  -->
                        <div class="booking-accrodion-item__children__successtext">
                            <?php echo __('By clicking the button I agree with the', EVENTIFYME_TEXTDOMAIN)?>
                            <a href="<?php echo !empty($bookingSettings['terms_page']) ? get_the_permalink($bookingSettings['terms_page']) : '#'?>" target="_blank">
                                <?php echo __('terms and conditions', EVENTIFYME_TEXTDOMAIN)?>
                            </a>
                        </div>
                        <div class="booking-accrodion-item__children__button booking-accrodion-item__children__button__success">
                            <a href="#"><?php echo __('Confirm Booking', EVENTIFYME_TEXTDOMAIN)?></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once 'eventify-me-plugin-ad-text.php'?>
        </div>
    </div>
</div>