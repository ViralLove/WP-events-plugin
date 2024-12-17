<?php global $post; ?>
<div class="eventify-me-metabox-container">
    <div class="input-group">
        <?php
        $sessionObject = new Event_Session();
        $eventSessions = $sessionObject->get_sessions(['event_id' => $post->ID]);?>
        <label class="main-label main-label-tooltip">
            <?php echo __('Sessions', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHEN.Sessions', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <?php Eventify_Me_Admin::eventify_me_timesession_field('event_sessions', $eventSessions);?>
    </div>
</div>
