<div class="eventify-me-metabox-container">
    <div class="input-group">
        <div class="checkbox-group">
            <?php
            $eventAgeCategory = Eventify_Me_Helpers::getAllSuitableAge();

            $eventAgeCategorySelected = get_post_meta($post->ID, 'event_age_category', true);
            $eventAgeCategorySelected = !empty($eventAgeCategorySelected) ? $eventAgeCategorySelected : [];
            ?>

            <label for="event_age_category" class="main-label main-label-tooltip">
                <?php echo __('Suitable age', EVENTIFYME_TEXTDOMAIN )?>
                <a href="#" class="custom-tooltip">
                    <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                    <div>
                        <?php echo __('addeventhelp.FORWHOM.SuitableAge', EVENTIFYME_TEXTDOMAIN)?>
                    </div>
                </a>
            </label>
            <?php
                $i = 0;
                $part = round(count($eventAgeCategory) / 3);
            ?>
            <?php foreach ($eventAgeCategory as $value => $label):?>
                <?php if($i % $part === 0) echo '<div class="checkbox-column">';?>
                <label>
                    <input type="checkbox" name="event_age_category[]" value="<?php echo $value?>" <?php checked(true, in_array($value, $eventAgeCategorySelected))?>>
                    <?php echo $label?>
                </label>
                <?php if(($i + 1) % $part === 0 || count($eventAgeCategory) === ($i +1)) echo '</div>';?>
                <?php $i++;?>
            <?php endforeach;?>
        </div>
    </div>

    <div class="input-group">
        <?php $eventChildrenAllowed = get_post_meta($post->ID, 'event_children_allowed', true);?>

        <label class="main-label main-label-tooltip" for="event_children_allowed">
            <?php echo __('Are children allowed?', EVENTIFYME_TEXTDOMAIN )?>

            <label class="switch-label" style="margin: 0 0 0 5px;">
                <input type="checkbox" name="event_children_allowed" id="event_children_allowed" value="yes" <?php checked('yes', $eventChildrenAllowed)?>>
                <span class="slider round"></span>
            </label>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.FORWHOM.AreChildrenAllowed', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>
    </div>

    <div class="input-group w-100 language-group">
        <?php $eventLanguageSelected = get_post_meta($post->ID, 'event_language', true);
        if(empty($eventLanguageSelected)) $eventLanguageSelected = [];
        ?>
        <label class="main-label main-label-tooltip">
            <?php echo __('Language that people need to speak', EVENTIFYME_TEXTDOMAIN )?>

            <a href="#" class="custom-tooltip">
                <?php echo Eventify_Me_Admin_Svg::getQuestionIcon()?>

                <div>
                    <?php echo __('addeventhelp.FORWHOM.LanguageThatPeopleNeedToSpeak', EVENTIFYME_TEXTDOMAIN)?>
                </div>
            </a>
        </label>

        <div class="checkbox-group">
            <?php
            $i = 0;
            $allCountries = Eventify_Me_Helpers::getAllCountries();
            $part = round(count($allCountries) / 3);
            foreach ($allCountries as $code => $country_name):?>
                <?php if($i % $part === 0) echo '<div class="checkbox-column">';?>
                <label>
                    <input type="checkbox" name="event_language[]" value="<?php echo $code?>" <?php checked(true, in_array($code, $eventLanguageSelected))?>>
                    <?php echo $country_name?>
                </label>
                <?php if(($i + 1) % $part === 0 || count($allCountries) === ($i +1)) echo '</div>';?>
                <?php $i++;?>
            <?php endforeach;?>
        </div>
    </div>
</div>
