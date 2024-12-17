<?php
$customFields = Eventify_Me_Helpers::getAllCustomFieldsOfEvent($event->ID);
$eventSessions = Eventify_Me_Helpers::groupedEventSessionsByDate($customFields['event_sessions'], true);
$bookingSettings = get_option('eventify_me_booking_settings');

$urlAfterConfirmation =
    $bookingSettings['after_booking_confirmed_redirect'] === 'redirect_to_specific_page'
    && !empty($bookingSettings['after_booking_confirmed_redirect_page_id'])
        ? get_the_permalink($bookingSettings['after_booking_confirmed_redirect_page_id'])
        : get_the_permalink($event->ID);

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
    echo __('Event with available bookings not found.', EVENTIFYME_TEXTDOMAIN);
} else {
?>
<div class="booking-shortcodes">
    <div class="booking-shortcode-container" id="<?php echo $id?>" style="background-color: transparent;">
        <div class="booking-shortcode">
            <div class="booking-shortcode__title">
                <a href="#"><span>←</span> <?php echo __('Back', EVENTIFYME_TEXTDOMAIN)?></a> <?php echo $event->post_title?>
            </div>
            <div class="booking-accrodion" style="display:block;">
                <div class="booking-accrodion-item">
                    <div class="booking-accrodion-item__parent">
                        <div class="booking-accrodion-item__parent__numbertitle">
                            <span class="booking-accrodion-item__parent__number active">1</span>
                            <span class="booking-accrodion-item__parent__title active"><?php echo __('Tickets', EVENTIFYME_TEXTDOMAIN)?></span>
                        </div>
                        <div class="booking-accrodion-item__parent__totaltickets">
                            3 <?php echo __('tickets', EVENTIFYME_TEXTDOMAIN)?>
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
                            <?php
                            $i = 1;
                            foreach ($eventSessions as $date => $sessions):?>
                                <?php foreach ($sessions as $session): ?>
                                    <?php if($session['is_booking_enabled'] === 'no') continue;?>

                                    <?php if($i === 1): $i++;?>
                                        <div class="booking-accrodion-item__children-info">
                                            <div class="booking-accrodion-item__children--info__date">
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
                                                                fill="" stroke="#EDEDED"></path>
                                                        <path d="M8 16.8889H28V19.1111H8V16.8889Z" fill="#C7C7C7"></path>
                                                    </svg>
                                                </div>
                                                <div class="booking-accrodion-item__children-info__counter__number">
                                                    <span>3</span>
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
                                    <?php else:?>
                                    <div class="booking-accrodion-item__children-info" data-session-id="<?php echo $session['id']?>">
                                        <div class="booking-accrodion-item__children--info__date">
											<?php
											$fmt = new \IntlDateFormatter(get_locale(), NULL, NULL);
											$fmt->setPattern('d MMMM, EEEE, ');
											echo $fmt->format(new DateTime(str_replace('/', '-', $session['date'])))?><span><?php echo $session['time_start']?></span>
                                        </div>
                                        <div class="booking-accrodion-item__children-info__counter">
                                            <div class="booking-accrodion-item__children-info__counter__minus">
                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                            d="M0.5 4C0.5 2.067 2.067 0.5 4 0.5H32C33.933 0.5 35.5 2.067 35.5 4V32C35.5 33.933 33.933 35.5 32 35.5H4C2.067 35.5 0.5 33.933 0.5 32V4Z"
                                                            fill="#fff" stroke="#EDEDED"></path>
                                                    <path d="M8 16.8889H28V19.1111H8V16.8889Z" fill="#C7C7C7"></path>
                                                </svg>
                                            </div>
                                            <div class="booking-accrodion-item__children-info__counter__number">
                                                <span>0</span>
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
                                    <?php endif;?>
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
                    <div class="booking-accrodion-item__children" style="display: none;">
                        <form class="booking-accrodion-item__children__form">
                            <div class="booking-accrodion-item__children__form-item booking-accrodion-item__children__form_acc-name">
                                <label for="acc-name"><?php echo __('Your e-mail', EVENTIFYME_TEXTDOMAIN)?> <span>*</span></label>
                                <input type="text" id="acc-name" type="email" required>
                            </div>
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
                            <?php
                            $i = 1;
                            $flag = false;
                            foreach ($eventSessions as $date => $sessions):?>
                                <?php foreach ($sessions as $session): ?>
                                    <?php if($session['is_booking_enabled'] === 'no') continue;?>

                                    <?php if($i === 1): $i++;?>
                                        <div class="booking-accrodion-item__children-ticket">
                                            <div class="booking-accrodion-item__children-ticket__date">		<?php
												$fmt = new \IntlDateFormatter(get_locale(), NULL, NULL);
												$fmt->setPattern('d MMMM, EEEE, ');
												echo $fmt->format(new DateTime(str_replace('/', '-', $session['date'])))?><span><?php echo $session['time_start']?></span></div>
                                            <div class="booking-accrodion-item__children-ticket__ticket">
                                                <span>3</span>
                                                <svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g opacity="0.8">
                                                        <path d="M28.8727 3.51033L27.1037 3.07967C25.8723 4.4764 23.9392 5.10627 22.1024 4.65918C20.266 4.21176 18.839 2.76313 18.388 0.956366L16.619 0.525709C16.6051 0.522486 16.5913 0.520874 16.5777 0.520874C16.4958 0.520874 16.4191 0.577607 16.3991 0.659483L15.1633 5.73388L16.5316 6.06719C16.9217 6.16229 17.1608 6.55555 17.0658 6.94559C16.9845 7.27793 16.6873 7.50067 16.3598 7.50067C16.3028 7.50067 16.2451 7.4939 16.187 7.48004L14.819 7.14674L11.4959 20.7901C11.4814 20.8491 11.5001 20.8968 11.5181 20.9268C11.5362 20.9564 11.57 20.9954 11.629 21.0096L23.8834 23.9946C23.9427 24.0094 23.9904 23.9904 24.0201 23.9723C24.0497 23.9543 24.0884 23.9204 24.1029 23.8614L27.426 10.2181L26.076 9.88927C25.686 9.79418 25.4468 9.40091 25.5416 9.01087C25.6367 8.62083 26.0306 8.38133 26.42 8.47674L27.7703 8.80554L29.0062 3.73081C29.0207 3.67182 29.002 3.62412 28.9839 3.59414C28.9659 3.56352 28.9317 3.52483 28.8727 3.51033ZM20.9504 7.89168C20.8695 8.22402 20.5719 8.44676 20.2444 8.44676C20.1874 8.44676 20.1297 8.44 20.072 8.42613L18.3062 7.99612C17.9161 7.90103 17.6769 7.50776 17.7717 7.11772C17.8668 6.72768 18.2601 6.48818 18.6501 6.58327L20.4159 7.01328C20.806 7.10838 21.0455 7.50164 20.9504 7.89168ZM24.8353 8.8381C24.7541 9.17044 24.4569 9.39318 24.1294 9.39318C24.0723 9.39318 24.0146 9.38641 23.9566 9.37255L22.1908 8.94221C21.801 8.84712 21.5619 8.45386 21.6566 8.06382V8.06349C21.7517 7.67345 22.1443 7.43427 22.5354 7.52936L24.3012 7.9597C24.6909 8.05479 24.9301 8.44805 24.8353 8.8381Z" fill="<?php echo $settings['color_1']?>"></path>
                                                        <path d="M9.73023 20.3599L13.0046 6.9154L12.8245 6.95924C12.7664 6.97342 12.7087 6.97987 12.6517 6.97987C12.3242 6.97987 12.027 6.75713 11.9457 6.42479C11.851 6.03475 12.0901 5.64148 12.4799 5.54639L13.3921 5.32397L13.6335 4.33243L12.612 0.138681C12.5978 0.0796912 12.5592 0.0455222 12.5292 0.0274707C12.4992 0.00909689 12.4509 -0.0089548 12.3925 0.00490616V0.00522863L10.6235 0.435886C10.1725 2.24265 8.74514 3.69128 6.90904 4.1387C5.07327 4.58611 3.13919 3.95592 1.90782 2.55919L0.138772 2.98985C0.0797819 3.00403 0.0456131 3.04271 0.0275616 3.07269C0.0095101 3.10267 -0.00918613 3.15038 0.00499718 3.20937L1.2412 8.28409L2.59152 7.95529C2.98091 7.86085 3.37482 8.09938 3.46991 8.48943V8.48975C3.56501 8.87979 3.3255 9.27305 2.93546 9.36814L1.58515 9.69694L4.90855 23.34C4.92274 23.399 4.96142 23.4331 4.9914 23.4512C5.02138 23.4693 5.07005 23.4876 5.1284 23.4734L10.2466 22.2266C10.1422 22.1193 10.0458 22.0026 9.96587 21.8711C9.68768 21.4143 9.6042 20.8776 9.73023 20.3599ZM6.82072 8.42141L5.0549 8.85174C4.99688 8.86593 4.93918 8.87237 4.88212 8.87237C4.55462 8.87237 4.25741 8.64963 4.17618 8.31729C4.17618 8.31729 4.17618 8.31729 4.17618 8.31697C4.08141 7.92693 4.32059 7.53366 4.71031 7.43857L6.47613 7.00824C6.86617 6.91282 7.25944 7.15233 7.35485 7.54237V7.54269C7.44962 7.93305 7.21044 8.32632 6.82072 8.42141ZM8.76706 7.92628C8.43955 7.92628 8.14235 7.70354 8.06112 7.3712C7.96602 6.98116 8.20553 6.58789 8.59557 6.4928L10.3614 6.06279C10.7501 5.96738 11.1447 6.20688 11.2398 6.59724C11.3349 6.98728 11.0954 7.38055 10.7053 7.47564L8.93951 7.90565C8.88181 7.91951 8.82411 7.92628 8.76706 7.92628Z" fill="<?php echo $settings['color_1']?>"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    <?php $flag = true;  break; endif;?>
                                <?php endforeach;?>
                                <?php if($flag) break;?>
                            <?php endforeach;?>
                        </div>
                        <!--  -->
                        <div class="booking-accrodion-item__children__successtext">
                            <?php echo __('By clicking the button I agree with the', EVENTIFYME_TEXTDOMAIN)?>
                            <a href="#">
                                <?php echo __('terms and conditions', EVENTIFYME_TEXTDOMAIN)?>
                            </a>
                        </div>
                        <div class="booking-accrodion-item__children__button booking-accrodion-item__children__button__success">
                            <a href="#"><?php echo __('Confirm Booking', EVENTIFYME_TEXTDOMAIN)?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }