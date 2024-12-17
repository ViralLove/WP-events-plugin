<div class="session-item" data-session-item="<?php echo $i;?>" data-field-name="<?php echo $field_name?>">
  <a href="#" class="timesession-field-remove" data-field-name="<?php echo $field_name?>"><?php echo Eventify_Me_Admin_Svg::getBinIcon()?></a>

  <div class="input-group">
    <label for="<?php echo $field_name?>_date_<?php echo $i?>"><?php echo __('Date', EVENTIFYME_TEXTDOMAIN)?></label>
    <input type="text" name="<?php echo $field_name?>_date_<?php echo $i?>" class="flatpickr-input" data-field-name="<?php echo $field_name?>" value="<?php echo !empty($val) ? $val['date'] : ''?>" data-flatpickr-type="date" data-input-type="date">
  </div>

  <div class="input-group">
    <label for="<?php echo $field_name?>_time_start_<?php echo $i?>"><?php echo __('Time start', EVENTIFYME_TEXTDOMAIN)?></label>
    <input type="text" name="<?php echo $field_name?>_time_start_<?php echo $i?>" class="flatpickr-input" data-field-name="<?php echo $field_name?>" value="<?php echo !empty($val) ? $val['time_start'] : ''?>" data-flatpickr-type="time" data-input-type="time_start">
    <!--        <a href="#" class="clear-input" title="--><?php //echo __('Clear input', EVENTIFYME_TEXTDOMAIN)?><!--" style="text-decoration: unset">×</a>-->
  </div>
  <span>&mdash;</span>
  <div class="input-group">
    <label for="<?php echo $field_name?>_time_end_<?php echo $i?>"><?php echo __('Time end', EVENTIFYME_TEXTDOMAIN)?></label>
    <input type="text" name="<?php echo $field_name?>_time_end_<?php echo $i?>" class="flatpickr-input" data-field-name="<?php echo $field_name?>" value="<?php echo !empty($val) ? $val['time_end'] : ''?>" data-flatpickr-type="time" data-input-type="time_end">
    <a href="#" class="clear-input" title="<?php echo __('Clear input', EVENTIFYME_TEXTDOMAIN)?>" style="text-decoration: unset">×</a>
  </div>

    <div class="input-group checkbox-group-session">
        <label for="<?php echo $field_name?>_is_booking_enabled_<?php echo $i?>"><?php echo __('Is booking enabled?', EVENTIFYME_TEXTDOMAIN)?></label>
        <label class="switch-label">
            <input type="checkbox" name="<?php echo $field_name?>_is_booking_enabled_<?php echo $i?>" data-field-name="<?php echo $field_name?>" value="yes" data-input-type="is_booking_enabled" <?php checked('yes', $val['is_booking_enabled'])?>>
            <?php // echo __('Yes', EVENTIFYME_TEXTDOMAIN)?>
            <span class="slider round"></span>
        </label>

    </div>

  <input type="hidden" name="<?php echo $field_name?>_session_id_<?php echo $i?>" value="<?php echo !empty($val) ? $val['id'] : ''?>" data-input-type="id">
</div>
