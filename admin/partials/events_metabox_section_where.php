<div class="eventify-me-metabox-container">
    <div class="input-group main-flex">
        <label class="main-label main-label-tooltip">
            <?php echo __('Address', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHERE.Address', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <div class="input-group">
            <?php $eventAddress = get_post_meta($post->ID, 'event_address', true); ?>
            <input type="text" name="event_address" id="event_address" value="<?php echo $eventAddress?>" placeholder="<?php echo __('Please indicate an exact address, but leave additional information to the “Comments” field', EVENTIFYME_TEXTDOMAIN)?>">
        </div>
    </div>



    <div class="input-group">
        <?php $eventLocationPlaceTitle = get_post_meta($post->ID, 'event_location_place_title', true); ?>
        <label for="event_location_place_title" class="main-label main-label-tooltip">
            <?php echo __('Place title', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHERE.PlaceTitle', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <input type="text" name="event_location_place_title" id="event_location_place_title" value="<?php echo $eventLocationPlaceTitle?>">
    </div>

    <div class="input-group">
        <?php $eventLocationComments = get_post_meta($post->ID, 'event_location_comments', true); ?>
        <label for="event_location_comments" class="main-label main-label-tooltip">
            <?php echo __('Location comments', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHERE.LocationComments', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <?php wp_editor( $eventLocationComments, 'event_location_comments', [
            'textarea_name'=> 'event_location_comments',
            'textarea_rows' => 10,
            'media_buttons' => false,
            'teeny' => true,
            'tabindex' => 4,
            'tinymce' => [
                'toolbar1' => 'bold, italic, bullist, numlist',
            ],
            'quicktags' => false
        ]);?>
    </div>
</div>
