<div class="eventify-me-list-view-short-events-container" id="<?php echo $id?>">
  <h1 class="eventify-me-list-view-short-events__title">
    <?php echo __('Upcoming Events', EVENTIFYME_TEXTDOMAIN)?>
  </h1>

  <div class="all-event">

    <div class="all-event-items">
      <?php if(!empty($current_events)): ?>
        <?php $eventPeriod = 'current';?>
        <?php foreach ($current_events as $key => $event):?>
          <?php require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/eventify-me-list-view-short-events-item.php';?>
          <?php if(($key + 1) === (int) $atts['numbers_of_events']) break;?>
        <?php endforeach;?>
      <?php else:?>
        <h4><?php echo __('Events not found.', EVENTIFYME_TEXTDOMAIN)?></h4>
      <?php endif;?>
    </div>

  </div>

  <?php if(!empty($current_events)): ?>
    <a href="<?php echo $atts['link_to_all_events']?>" class="view-all-events"><?php echo __('View all events', EVENTIFYME_TEXTDOMAIN)?></a>
  <?php endif;?>
</div>