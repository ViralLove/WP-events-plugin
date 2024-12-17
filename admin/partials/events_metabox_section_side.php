<div class="eventify-me-metabox-container eventify-me-metabox-container__side">
    <?php
    $users = get_users([
        'role__in' => array('administrator', 'eventify_me_manager'),
    ]);
    $wpUsers = [];
    foreach ($users as $key => $user) {
        $wpUsers[$key] = new stdClass();
        $wpUsers[$key]->ID = $user->ID;
        $wpUsers[$key]->user_login = $user->user_login;
        $wpUsers[$key]->user_email = $user->user_email;
        $wpUsers[$key]->user_nicename = $user->user_nicename;
        $wpUsers[$key]->first_name = $user->first_name;
        $wpUsers[$key]->last_name = $user->last_name;
    }
    ?>
    <div class="wp-users-for-autocomplete" style="display: none">
        <?php echo json_encode($wpUsers)?>
    </div>

    <div class="input-group main-flex input-group-event-managers">
        <?php $eventManagersId = get_post_meta($post->ID, 'event_managers_id', true); ?>
        <div class="action-title">
            <label for="event_manager"><?php echo __('Event managers', EVENTIFYME_TEXTDOMAIN )?>
                <a href="#" class="custom-tooltip">
                    <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                    <div><?php echo __('You can add to the list any user with the role Administrator or Event manager. These users will be the only ones to have access this event editing and its booking (including booking notification e-mail).', EVENTIFYME_TEXTDOMAIN)?></div>
                </a>
            </label>
            <a href="#" class="button add-event-manager"><?php echo __('Add', EVENTIFYME_TEXTDOMAIN)?></a>
            <input type="hidden" name="event_managers_id" value="">
        </div>

        <div class="event-managers-wrap">
            <?php if(!empty($eventManagersId)):?>
                <?php foreach ($eventManagersId as $eventManagerId):?>
                    <?php Eventify_Me_Admin::eventify_me_event_manager_field_item($eventManagerId)?>
                <?php endforeach;?>
            <?php endif;?>
        </div>

    </div>
</div>