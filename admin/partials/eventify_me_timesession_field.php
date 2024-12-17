<div class="timesession-field">
    <div class="sessions-wrap" data-confirm-text-for-remove-session="<?php echo __('Do you really want to remove this session?', EVENTIFYME_TEXTDOMAIN)?>">
        <?php
        if(!empty($defaultValue)):?>
            <?php foreach ($defaultValue as $key => $val):?>
                <?php if(empty($val)) continue;?>
                <?php Eventify_Me_Admin::eventify_me_timesession_field_item($field_name, $val, ($key + 1))?>
            <?php endforeach; ?>
        <?php endif;?>
    </div>

    <input type="hidden" name="<?php echo $field_name;?>">
    <a href="#"
       class="button timesession-field-add"
       data-current-count="<?php echo is_array($defaultValue) ? count($defaultValue) : '0'?>"
       data-field-name="<?php echo $field_name?>"
       data-first-session-text="<?php echo __('Add your event’s first session', EVENTIFYME_TEXTDOMAIN)?>"
       data-another-session-text="<?php echo __('Add another event’s session', EVENTIFYME_TEXTDOMAIN)?>"
    >
        <?php if(count($defaultValue) > 0) {
            echo __('Add another event’s session', EVENTIFYME_TEXTDOMAIN);
        } else {
            echo __('Add your event’s first session', EVENTIFYME_TEXTDOMAIN);
        }?>
    </a>
</div>

<?php require_once 'loader.php';?>

