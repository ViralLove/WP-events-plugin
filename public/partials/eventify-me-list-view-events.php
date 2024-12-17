<div class="pr-container" id="<?php echo $id?>">
    <div class="all-event">
        <div class="all-event__title-switcher">
            <h1 class="all-event__title">
                <?php echo __('EVENTS', EVENTIFYME_TEXTDOMAIN)?>
            </h1>
            <?php if(empty($event_ids) && empty($events)):?>
<!--                <div class="all-event__switcher-mobile">-->
<!--                    <div class="all-event__switcher-mobile-item all-event__switcher-mobile-item_active" data-target="past">--><?php //echo __('View past events', EVENTIFYME_TEXTDOMAIN)?><!--</div>-->
<!--                    <div class="all-event__switcher-mobile-item" data-target="current">--><?php //echo __('View future events', EVENTIFYME_TEXTDOMAIN)?><!--</div>-->
<!--                </div>-->
            <?php endif;?>
        </div>
        <?php if(empty($event_ids) && empty($events) && EVENTIFYME_IS_LICENSED):?>
<!--            <div class="all-event__switcher">-->
<!--                <div class="all-event__switcher-item all-event__switcher-item_active" data-target="current">-->
<!--                    --><?php //echo __('View future events', EVENTIFYME_TEXTDOMAIN)?>
<!--                </div>-->
<!--                <div class="all-event__switcher-item" data-target="past">-->
<!--                    --><?php //echo __('View past events', EVENTIFYME_TEXTDOMAIN)?>
<!--                </div>-->
<!--            </div>-->
        <?php endif;?>
        <?php if(empty($event_ids) && empty($events)):?>
            <div class="all-event-items">
                <div class="all-event-item-switcher-item all-event-item-switcher-item_active" data-target="current">
                    <?php if(!empty($current_events)): ?>
                        <?php $eventPeriod = 'current';?>
                        <?php foreach ($current_events as $key => $event):?>
                            <?php require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/eventify-me-list-view-events-item.php';?>

                            <?php if(!EVENTIFYME_IS_LICENSED && $key >= 2) break;?>
                        <?php endforeach;?>
                    <?php else:?>
                        <h4><?php echo __('Events not found.', EVENTIFYME_TEXTDOMAIN)?></h4>
                    <?php endif;?>
                </div>
                <?php if(EVENTIFYME_IS_LICENSED):?>
                    <div class="all-event-item-switcher-item" data-target="past">
                        <?php if(!empty($past_events)):?>
                            <?php $eventPeriod = 'past';?>
                            <?php foreach ($past_events as $event):?>
                                <?php require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/eventify-me-list-view-events-item.php';?>
                            <?php endforeach;?>
                        <?php else:?>
                            <h4><?php echo __('Events not found.', EVENTIFYME_TEXTDOMAIN)?></h4>
                        <?php endif;?>
                    </div>
                <?php endif;?>
            </div>
        <?php else:?>
            <div class="all-event-item-switcher-item all-event-item-switcher-item_active">
                <?php foreach ($events as $event):?>
                    <?php $eventPeriod = 'current';?>
                    <?php require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/eventify-me-list-view-events-item.php';?>
                <?php endforeach;?>
            </div>
        <?php endif;?>

    </div>
    <?php require_once 'eventify-me-plugin-ad-text.php'?>
</div>