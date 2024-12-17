<?php $eventManager = get_user_by('id', $manager_id); ?>
<div class="input-group">
    <div class="input-wrap">
        <?php if(!empty($eventManager)):?>
            <input type="text"
                   name="event_manager"
                   id="event_manager"
                   value="<?php echo $eventManager->first_name . ' ' . $eventManager->last_name?> (<?php echo $eventManager->user_login?>)"
                   placeholder="<?php echo __('Select a user as event manager…', EVENTIFYME_TEXTDOMAIN)?>"
            >
        <?php else:?>
            <input type="text"
                   name="event_manager"
                   id="event_manager"
                   placeholder="<?php echo __('Select a user as event manager…', EVENTIFYME_TEXTDOMAIN)?>"
            >
        <?php endif;?>

        <a href="#" class="event-manager-field-remove"><?php echo Eventify_Me_Admin_Svg::getBinIcon()?></a>
    </div>

    <input type="hidden" name="event_manager_id" value="<?php echo !empty($eventManager) ? $eventManager->ID : ''?>">

<!--    <strong class="user_nicename" style="margin-top: 5px; display:block">--><?php //echo $eventManager->user_firstname?><!--</strong>-->
<!--    <strong class="user_email" style="display:block;">--><?php //echo $eventManager->user_email?><!--</strong>-->
</div>
