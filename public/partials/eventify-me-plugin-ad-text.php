<?php
$settingsObject = new Eventify_Me_Settings();
$settings = $settingsObject->get_visual_settings();
?>
<style>
    .eventify-me-plugin-ad-text {
        font-size: 15px;
        margin-top: 5px;
        color: <?php echo $settings['color_2']?>;
    }
    .eventify-me-plugin-ad-text a {
        color: <?php echo $settings['color_3']?>;
    }
    .eventify-me-plugin-ad-text img {
        width: 17px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
    }
</style>
<div class="eventify-me-plugin-ad-text">
    <img src="<?php echo EVENTIFYME_DIR_URL . '/public/img/logo-eventify-me.png'?>" alt=""> <?php echo sprintf(__('These events are displayed thanks to %s', EVENTIFYME_TEXTDOMAIN), '<a href="http://zeya888.com/zeya4events" target="_blank">Zeya4Events</a>')?>
</div>
