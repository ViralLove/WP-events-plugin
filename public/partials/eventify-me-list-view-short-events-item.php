<?php $customFields = $event->custom_fields;?>
<div class="eventify-me-list-view-short-events__item">
    <a href="<?php echo get_the_permalink($event->ID)?>"></a>

    <div class="eventify-me-list-view-short-events__item-calendar">
      <?php
      $sessionDatetime = $customFields['event_nearest_session'];
      $timestampDate = $sessionDatetime['timestamp'];

      $fmt = new \IntlDateFormatter(get_locale(), NULL, NULL);
      $date_for_date_time = date('Y-m-d', $timestampDate);
      ?>
      <div class="month">
          <?php
          $fmt->setPattern('MMM');
          echo $fmt->format(new DateTime($date_for_date_time));
          ?>
      </div>
      <div class="date"><?php
            $fmt->setPattern('d');
            echo $fmt->format(new DateTime($date_for_date_time));
          ?>
      </div>
      <div class="week_num">
          <?php
            $fmt->setPattern('EEE');
            echo $fmt->format(new DateTime($date_for_date_time));
          ?>
      </div>
    </div>

    <div class="eventify-me-list-view-short-events__item-info">
      <div class="top-info">
        <div class="time-start"><?php echo $sessionDatetime['time_start']?></div>
        <div class="tags">
          <?php if(!empty($customFields['event_thematics'])):?>
            <?php foreach ($customFields['event_thematics'] as $thematic):?>
<!--              <div class="tag">-->
                #<?php echo strtolower($thematic->name)?>
<!--              </div>-->
            <?php endforeach;?>
          <?php endif;?>
        </div>
      </div>
      <div class="title">
        <?php echo $event->post_title?>
      </div>
    </div>
</div>