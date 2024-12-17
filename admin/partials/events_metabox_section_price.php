<div class="eventify-me-metabox-container">
    <div class="input-group">
        <?php $eventIsFree = get_post_meta($post->ID, 'event_is_free', true);?>
        <label class="main-label main-label-tooltip">
            <?php echo __('Free event', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.PRICE.FreeEvent', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="event_is_free" value="yes" <?php checked('yes', $eventIsFree)?>>
                <?php echo __('Yes', EVENTIFYME_TEXTDOMAIN) ?>
            </label>
        </div>
    </div>

    <div class="input-group">
        <?php $eventPrice = get_post_meta($post->ID, 'event_price', true); ?>
        <label class="main-label main-label-tooltip" for="event_price">
            <?php echo __('Price', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.PRICE.Price', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <div class="input-description">
            <p>
                <?php echo __( 'You can visually emphasize a line with the actual price value, it will also contain an image with a ticket next to it. To do it, use "Select format" field in editor. It will be marked as "Heading 1".', EVENTIFYME_TEXTDOMAIN ); ?>
<!--                --><?php //echo __( 'You can visually emphasize a line with the actual price value, it will also contain an image with a ticket next to it. To do it, use combination', EVENTIFYME_TEXTDOMAIN) . ' <strong>' . __('Shift + Alt + 1', EVENTIFYME_TEXTDOMAIN) . '</strong> ' .__('on the required line. It will be marked as H1.', EVENTIFYME_TEXTDOMAIN ); ?>
                <br>
                <br>
                <?php echo __('For example:', EVENTIFYME_TEXTDOMAIN)?>
                <br>
                <strong><?php echo __('25€/class', EVENTIFYME_TEXTDOMAIN)?></strong>
                <br>
                <br>
                <?php echo __('The price includes all requirements drawing materials.', EVENTIFYME_TEXTDOMAIN)?>
                <br>
                <?php echo __('If you mark the line with the price as "Heading 1", it will be very nicely displayed differently from the other text.', EVENTIFYME_TEXTDOMAIN)?>
            </p>
        </div>

        <?php wp_editor( $eventPrice, 'event_price', [
            'textarea_name'=> 'event_price',
            'textarea_rows' => 10,
            'media_buttons' => false,
            'teeny' => false,
            'tabindex' => 4,
            'tinymce' => [
                'toolbar1' => 'formatselect, bold, italic, bullist, numlist',
                'block_formats' => 'Select format=p; Heading 1=h1',
            ],
            'quicktags' => false
        ]);?>
    </div>
</div>
