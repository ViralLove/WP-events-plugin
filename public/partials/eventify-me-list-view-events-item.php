<?php $customFields = $event->custom_fields;?>
<div class="all-event-item">
    <a href="<?php echo get_the_permalink($event->ID)?>" class="all-event-item__link"></a>
    <div class="all-event-item__img">
        <img src="<?php echo $customFields['event_cover_card']?>" alt="">
    </div>
    <div class="all-event-item__info">
        <div class="all-event-item__info__title short-text">
            <?php echo $event->post_title?>
        </div>
        <div class="all-event-item__info__anothers">
            <div class="all-event-item__info__another">
                <?php if(!empty($customFields['event_formats'])):?>
                <div class="all-event-item__info__course">
                    <?php echo implode(' ● ', array_column($customFields['event_formats'], 'name'))?>
                </div>
                <?php endif;?>
                <?php if(!empty($customFields['event_sessions'])):?>
                    <div class="all-event-item__info__date">
                        <?php
                        $sessionDatetime = $customFields['event_nearest_session'];
                        ?>
                        <?php if(count($customFields['event_sessions']) === 1):?>
                            <?php echo $sessionDatetime['date'] . ' ' . $sessionDatetime['time_start'] ?>
                            <?php echo !empty($sessionDatetime['time_end']) ? ' — ' . $sessionDatetime['time_end'] : '' ?>
                        <?php else:?>
                            <?php echo $sessionDatetime['date'] . ' ' . $sessionDatetime['time_start']?>
                            <?php echo !empty($sessionDatetime['time_end']) ? ' — ' . $sessionDatetime['time_end'] : '' ?>
                            <span>+ <?php echo count($customFields['event_sessions']) - 1 . ' ' . __('sessions', EVENTIFYME_TEXTDOMAIN)?></span>
                        <?php endif;?>
                    </div>

                    <div class="all-event-item__info__date all-event-item__info__date__mobile">
                        <?php if(count($customFields['event_sessions']) === 1):?>
                            <?php echo $sessionDatetime['date_mobile'] . ' ' . $sessionDatetime['time_start'] . ' — ' . $sessionDatetime['time_end']?>
                        <?php else:?>
                            <?php echo $sessionDatetime['date_mobile'] . ' ' . $sessionDatetime['time_start'] . ' — ' . $sessionDatetime['time_end']?> <span>+ <?php echo count($customFields['event_sessions']) . ' ' . __('sessions', EVENTIFYME_TEXTDOMAIN)?></span>
                        <?php endif;?>
                    </div>
                <?php endif;?>
                <div class="all-event-item__info__place">
                    <?php if($atts['show_location'] && !empty($customFields['event_address'])): ?>
                        <?php echo $customFields['event_address']?>
                    <?php endif;?>
                </div>
            </div>
            <div class="all-event-item__info__tickets">
                <?php if(!empty($customFields['event_is_free']) && $customFields['event_is_free'] === 'yes'):?>
                    <div class="all-event-item__info__ticket all-event-item__info__ticket__free">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g id="ic_free">
                                <path id="Shape" d="M8 0C7.36637 0 6.71919 0.77648 6 1C5.4951 1.10447 4.54874 0.754611 4 1C3.45126 1.38824 3.28138 2.38528 3 3C2.38529 3.28138 1.38825 3.45126 1 4C0.754611 4.54874 1.10447 5.4951 1 6C0.77648 6.71919 0 7.36637 0 8C0 8.63362 0.77648 9.28082 1 10C1.10447 10.5049 0.754611 11.4513 1 12C1.38825 12.5487 2.38529 12.7186 3 13C3.28138 13.6147 3.45126 14.6118 4 15C4.54874 15.2454 5.4951 14.8955 6 15C6.71919 15.2235 7.36637 16 8 16C8.63363 16 9.28081 15.2235 10 15C10.5049 14.8955 11.4513 15.2454 12 15C12.5487 14.6118 12.7186 13.6147 13 13C13.6147 12.7186 14.6118 12.5487 15 12C15.2454 11.4513 14.8955 10.5049 15 10C15.2235 9.28082 16 8.63362 16 8C16 7.36637 15.2235 6.71919 15 6C14.8955 5.4951 15.2454 4.54874 15 4C14.6118 3.45126 13.6147 3.28138 13 3C12.7186 2.38528 12.5487 1.38824 12 1C11.4513 0.754611 10.5049 1.10447 10 1C9.28081 0.77648 8.63363 0 8 0Z" fill="<?php echo $settings['color_9']?>"/>
                                <path id="Path 7" d="M4.44434 8.44436L7.111 10.6666L10.6666 5.33325" stroke="white" stroke-width="2" fill="<?php echo $settings['color_9']?>"/>
                            </g>
                        </svg>
                        <span><?php echo __('Free', EVENTIFYME_TEXTDOMAIN)?></span>
                    </div>
                <?php endif?>
                <?php if(!empty($customFields['event_price'])):?>
                    <?php $preview = Eventify_Me_Helpers::getPreviewForEventPrices($customFields['event_price']);?>
                    <?php foreach ($preview as $title):?>
                        <div class="all-event-item__info__ticket">
                            <svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 6V5H11V1C11 0.262368 10.7314 0 10 0H8C7.40732 0 7.16287 0.215244 7 0C6.94255 1.21759 6.28157 1.76748 6 2C4.71843 1.76748 4.03333 1.21759 4 0C3.86287 0.191682 3.59268 0 3 0H1C0.268563 0 0 0.262368 0 1V5H1V6H0V15C0 15.7376 0.268563 16 1 16H3C3.37399 16 3.64255 15.7612 4 15C3.74065 14.6382 4.44824 14.0173 5 14C6.11111 14.0173 6.82033 14.6382 7 15C6.91713 15.7612 7.18569 16 8 16H10C10.7314 16 11 15.7376 11 15V6H10ZM6 5H7V6H6V5ZM4 5H5V6H4V5ZM2 5H3V6H2V5ZM9 6H8V5H9V6Z" fill="<?php echo $settings['color_1']?>"></path>
                            </svg>
                            <span><?php echo $title?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif;?>
            </div>
        </div>
        <div class="all-event-item__info__forwhom">
            <?php if(!empty($customFields['event_age_category'])):?>
                <div class="all-event-item__info__for">
                    <?php echo Eventify_Me_Helpers::getSuitableAgeTextForEvent($event->ID)?>
                </div>
            <?php endif;?>
            <div class="all-event-item__info__tags">
                <?php if(!empty($customFields['event_thematics'])):?>
                    <?php foreach ($customFields['event_thematics'] as $thematic):?>
                        <div class="all-event-item__info__tags-tag">
                            #<?php echo $thematic->name?>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>