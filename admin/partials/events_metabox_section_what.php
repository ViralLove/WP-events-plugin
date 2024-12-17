<div class="eventify-me-metabox-container">
    <?php
    echo '<div class="notice_editor_copy_past">';
    echo '<p>';
    echo __('In order to have a correct formatting we recommend you to copy paste the text through a simple text editor. I.e. if you take event’s description from Facebook event, then first copy it to the text editor and then copy from there and paste here. This will remove all unnecessary artifacts that might corrupt visual text presentation.', EVENTIFYME_TEXTDOMAIN);
    echo '</p>';
    echo '</div>';
    ?>

    <div class="input-group">
        <div class="checkbox-group">
            <?php
            $selectedFormats = get_the_terms($post, 'event_formats');
            $selectedFormats = $selectedFormats ? array_column($selectedFormats, 'term_id') : [];

            $eventFormats = get_terms([
                'taxonomy' => 'event_formats',
                'hide_empty' => false,
                'parent' => 0
            ]);
            $formatsHtml = Eventify_Me_Helpers::showTermsInCheckboxGroup($eventFormats, $selectedFormats, true);
            ?>

            <label class="main-label main-label-tooltip">
                <?php echo __('Event formats', EVENTIFYME_TEXTDOMAIN)?>
                <a href="#" class="custom-tooltip">
                    <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                    <div>
                        <?php echo __('addeventhelp.WHAT.EventFormats', EVENTIFYME_TEXTDOMAIN)?>
                    </div>
                </a>
            </label>
            <?php if(!empty($formatsHtml)):?>
                <?php echo $formatsHtml;?>
            <?php else:?>
                <?php echo __('You have not yet added any formats.', EVENTIFYME_TEXTDOMAIN)?> <a href="/wp-admin/edit-tags.php?taxonomy=event_formats&post_type=eventify-me-events" target="_blank"><?php echo __('Add new', EVENTIFYME_TEXTDOMAIN)?></a>
            <?php endif;?>
        </div>
    </div>

    <div class="input-group">
        <div class="checkbox-group">
            <?php
            $selectedThematics = get_the_terms($post, 'event_thematics');
            $selectedThematics = $selectedThematics ? array_column($selectedThematics, 'term_id') : [];

            $eventThematics = get_terms([
                'taxonomy' => 'event_thematics',
                'hide_empty' => false,
                'parent' => 0
            ]);
            $thematicsHtml = Eventify_Me_Helpers::showTermsInCheckboxGroup($eventThematics, $selectedThematics, true);
            ?>

            <label class="main-label main-label-tooltip">
                <?php echo __('Event thematics', EVENTIFYME_TEXTDOMAIN)?>
                <a href="#" class="custom-tooltip">
                    <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                    <div>
                        <?php echo __('addeventhelp.WHAT.EventThematics', EVENTIFYME_TEXTDOMAIN)?>
                    </div>
                </a>
            </label>
            <?php if(!empty($thematicsHtml)):?>
                <?php echo $thematicsHtml;?>
            <?php else:?>
                <?php echo __('You have not yet added any thematics.', EVENTIFYME_TEXTDOMAIN)?> <a href="/wp-admin/edit-tags.php?taxonomy=event_thematics&post_type=eventify-me-events" target="_blank"><?php echo __('Add new', EVENTIFYME_TEXTDOMAIN)?></a>
            <?php endif;?>
        </div>
    </div>


    <div class="input-group" style="margin-bottom: 10px;">
        <label class="main-label main-label-tooltip" style="font-size: 16px;">
            <?php echo __('Cover', EVENTIFYME_TEXTDOMAIN )?>
            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHAT.Cover', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>
    </div>

    <div class="input-group w-50 input-group-image">
        <?php $coverSingle = get_post_meta($post->ID, 'event_cover_single_page', true);?>
        <label for="event_cover_single_page"> <?php echo __('Event page view (0.43)', EVENTIFYME_TEXTDOMAIN )?> </label>
        <div class="img-wrap <?php echo !empty($coverSingle) ? 'active' : ''?>">
            <input id="event_cover_single_page" type="text" size="36" name="event_cover_single_page" value="<?php echo $coverSingle?>" />
            <input class="upload_image_button" type="button" value="<?php echo __('Click here to add a cover', EVENTIFYME_TEXTDOMAIN)?>" <?php if(!empty($coverSingle)) echo 'style="background-image: url(' . $coverSingle . '); background-size: cover;"'?>/>

            <a href="#" class="remove-image">
                <?php echo Eventify_Me_Admin_Svg::getCloseIcon()?>
            </a>
        </div>
    </div>
    <div class="input-group w-50 input-group-image">
        <?php $coverCard = get_post_meta($post->ID, 'event_cover_card', true);?>
        <label for="event_cover_card"> <?php echo __('List view (0.58)', EVENTIFYME_TEXTDOMAIN )?> </label>
        <div class="img-wrap <?php echo !empty($coverCard) ? 'active' : ''?>">
            <input id="event_cover_card" type="text" size="36" name="event_cover_card" value="<?php echo $coverCard?>" />
            <input class="upload_image_button" type="button" value="<?php echo __('Click here to add a cover', EVENTIFYME_TEXTDOMAIN)?>" <?php if(!empty($coverCard)) echo 'style="background-image: url(' . $coverCard . '); background-size: cover;"'?>/>

            <a href="#" class="remove-image"><?php echo Eventify_Me_Admin_Svg::getCloseIcon()?></a>
        </div>
    </div>

    <div class="input-group">
        <label class="main-label main-label-tooltip" style="font-size: 16px;" for="event_cover_card">
            <?php echo __('Photo gallery', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.WHAT.PhotoGallery', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>
        <?php Eventify_Me_Admin::eventify_me_gallery_field( 'event_photos', get_post_meta($post->ID, 'event_photos', true) ) ?>
    </div>
</div>
