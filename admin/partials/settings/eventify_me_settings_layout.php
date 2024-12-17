<?php $event_settings = new Eventify_Me_Settings();
$list_settings = $event_settings->get_visual_settings('preview');?>
<div class="eventify-me-settings-wrap">
    <h1><?php echo __('Settings', EVENTIFYME_TEXTDOMAIN)?></h1>
    <div id="tabs">
        <ul>
            <li><a href="#visual-settings"><?php echo __('Visual settings', EVENTIFYME_TEXTDOMAIN)?></a></li>
            <li><a href="#booking-settings"><?php echo __('Booking settings', EVENTIFYME_TEXTDOMAIN)?></a></li>
            <li><a href="#email-settings"><?php echo __('Email settings', EVENTIFYME_TEXTDOMAIN)?></a></li>
<!--            <li><a href="#license-settings">--><?php //echo __('License settings', EVENTIFYME_TEXTDOMAIN)?><!--</a></li>-->
        </ul>
        <div class="content-wrap">
            <div id="visual-settings">
                <form method="POST" class="form-list-events-settings">
                    <h2>
                        <?php echo __('Colors', EVENTIFYME_TEXTDOMAIN)?>
                    </h2>

                    <div class="input-group-wrap">
                        <div class="input-group">
                            <label for="color_1">
                                <?php echo __('Color 1', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_1']?>" name="color_1" id="color_1">
                        </div>

                        <div class="input-group">
                            <label for="color_2">
                                <?php echo __('Color 2', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_2']?>" name="color_2" id="color_2">
                        </div>

                        <div class="input-group">
                            <label for="color_3">
                                <?php echo __('Color 3', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_3']?>" name="color_3" id="color_3">
                        </div>

                        <div class="input-group">
                            <label for="color_4">
                                <?php echo __('Color 4', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_4']?>" name="color_4" id="color_4">
                        </div>
                    </div>

                    <div class="input-group-wrap">
                        <div class="input-group">
                            <label for="color_5">
                                <?php echo __('Color 5', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_5']?>" name="color_5" id="color_5">
                        </div>

                        <div class="input-group">
                            <label for="color_6">
                                <?php echo __('Color 6', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_6']?>" name="color_6" id="color_6">
                        </div>

                        <div class="input-group">
                            <label for="color_7">
                                <?php echo __('Color 7', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_7']?>" name="color_7" id="color_7">
                        </div>

                        <div class="input-group">
                            <label for="color_8">
                                <?php echo __('Color 8', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_8']?>" name="color_8" id="color_8">
                        </div>
                    </div>

                    <div class="input-group-wrap">
                        <div class="input-group">
                            <label for="color_9">
                                <?php echo __('Color 9', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_9']?>" name="color_9" id="color_9">
                        </div>

                        <div class="input-group">
                            <label for="color_10">
                                <?php echo __('Color 10', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_10']?>" name="color_10" id="color_10">
                        </div>

                        <div class="input-group">
                            <label for="color_11">
                                <?php echo __('Color 11', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_11']?>" name="color_11" id="color_11">
                        </div>

                        <div class="input-group">
                            <label for="color_12">
                                <?php echo __('Color 12', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                            <input type="text" class="color-field" value="<?php echo $list_settings['color_12']?>" name="color_12" id="color_12">
                        </div>
                    </div>

                    <h2>
                        <?php echo __('Font', EVENTIFYME_TEXTDOMAIN)?>
                    </h2>

                    <div class="input-group-wrap">
                        <div class="input-group">
                            <select name="font_family" id="font_family">
                                <?php foreach (Eventify_Me_Settings::defaultFonts() as $name => $fonts):?>
                                    <option value="<?php echo $name?>" <?php selected($name, $list_settings['font_family'])?>><?php echo $fonts['label']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="actions-buttons">
                        <a href="#" class="button update-preview-list-settings"><?php echo __('Live update for review', EVENTIFYME_TEXTDOMAIN)?></a>
                        <button type="submit" class="button"><?php echo __('Publish', EVENTIFYME_TEXTDOMAIN)?></button>
                        <a href="#" class="button button-link-delete reset-list-settings"><?php echo __('Reset all to default values', EVENTIFYME_TEXTDOMAIN)?></a>
                    </div>
                </form>

                <div id="visual-settings-preview-tabs">
                    <ul>
                        <li><a href="#event-list-preview"><?php echo __('Preview events list', EVENTIFYME_TEXTDOMAIN)?></a></li>
                        <li><a href="#event-details-preview"><?php echo __('Preview event details', EVENTIFYME_TEXTDOMAIN)?></a></li>
                        <li><a href="#event-booking-preview"><?php echo __('Preview event booking', EVENTIFYME_TEXTDOMAIN)?></a></li>
                    </ul>
                    <div class="content-preview-wrap">
                        <div id="event-list-preview">
                            <?php echo do_shortcode('[events_list_view type="preview"]')?>
                        </div>

                        <div id="event-details-preview">
                            <?php
                            $preview_mode = true;
                            require_once EVENTIFYME_DIR_PATH . 'public/partials/single-eventify-me.php';?>
                        </div>

                        <div id="event-booking-preview">
                            <?php echo do_shortcode('[booking_event event_id="" type="preview"]')?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="booking-settings">
                <?php
                $bookingSettings = get_option('eventify_me_booking_settings');

                $allPages = new WP_Query([
                    'post_type' => 'page',
                    'post_status' => 'publish'
                ]);
                $wpPages = [];
                foreach ($allPages->posts as $key => $post) {
                    $wpPages[$key] = new stdClass();
                    $wpPages[$key]->ID = $post->ID;
                    $wpPages[$key]->post_title = $post->post_title;
                }
                ?>
                <form method="POST" class="form-list-booking-settings">
                    <div class="wp-pages-for-autocomplete" style="display: none">
                        <?php echo json_encode($wpPages)?>
                    </div>
                    <h2><?php echo __('Terms and conditions page', EVENTIFYME_TEXTDOMAIN)?></h2>
                    <div class="input-group-wrap">
<!--                        <div class="input-group">-->
<!--                            <select name="terms_page" id="terms_page">-->
<!--                                <option value="">--><?php //echo __('Choose Terms Page')?><!--</option>-->
<!--                                --><?php //foreach ($wpPages as $page):?>
<!--                                    <option-->
<!--                                        value="--><?php //echo $page->ID?><!--"-->
<!--                                        --><?php //echo !empty($bookingSettings['terms_page']) && $bookingSettings['terms_page'] == $page->ID ? 'selected' : ''?>
<!--                                    >-->
<!--                                        --><?php //echo $page->post_title?>
<!--                                    </option>-->
<!--                                --><?php //endforeach;?>
<!--                            </select>-->
<!--                        </div>-->

                        <div class="input-group input-group-autocomplete">
                            <input type="text" class="wp-page-autocomplete" placeholder="<?php echo __('Start typing and select a page')?>"
                                   value="<?php echo !empty($bookingSettings['terms_page']) ? get_the_title($bookingSettings['terms_page']) : ''?>">
                            <input type="hidden" name="terms_page"
                                   value="<?php echo !empty($bookingSettings['terms_page']) ? $bookingSettings['terms_page'] : ''?>">
                        </div>
                    </div>

                    <h2><?php echo __('Process after booking confirmation', EVENTIFYME_TEXTDOMAIN)?></h2>
                    <div class="input-group-wrap child-full-width">
                        <div class="input-group input-group-radio <?php echo $bookingSettings['after_booking_confirmed_redirect'] == 'redirect_to_event_page' ? 'active' :''?>">
                            <label for="redirect_to_event_page">
                                <input
                                    type="radio"
                                    name="after_booking_confirmed_redirect"
                                    value="redirect_to_event_page"
                                    id="redirect_to_event_page"
                                    <?php checked($bookingSettings['after_booking_confirmed_redirect'], 'redirect_to_event_page')?>
                                >
                                <?php echo __('Return to the event page', EVENTIFYME_TEXTDOMAIN)?>
                            </label>
                        </div>

                        <div class="input-group input-group-radio <?php echo $bookingSettings['after_booking_confirmed_redirect'] == 'redirect_to_specific_page' ? 'active' :''?>">
                            <label for="redirect_to_specific_page">
                                <input
                                    type="radio"
                                    name="after_booking_confirmed_redirect"
                                    value="redirect_to_specific_page"
                                    id="redirect_to_specific_page"
                                    <?php checked($bookingSettings['after_booking_confirmed_redirect'], 'redirect_to_specific_page')?>
                                >
                                <?php echo __('Display the page selected below', EVENTIFYME_TEXTDOMAIN)?>
                            </label>

                            <div class="input-group input-group-autocomplete">
                                <input type="text" class="wp-page-autocomplete" placeholder="<?php echo __('Start typing and select a page')?>"
                                       value="<?php echo !empty($bookingSettings['after_booking_confirmed_redirect_page_id']) ? get_the_title($bookingSettings['after_booking_confirmed_redirect_page_id']) : ''?>">
                                <input type="hidden" name="after_booking_confirmed_redirect_page_id"
                                       value="<?php echo !empty($bookingSettings['after_booking_confirmed_redirect_page_id']) ? $bookingSettings['after_booking_confirmed_redirect_page_id'] : ''?>">
                            </div>

<!--                            <div class="input-group">-->
<!--                                <select name="after_booking_confirmed_redirect_page_id" id="after_booking_confirmed_redirect_page_id">-->
<!--                                    <option value="">--><?php //echo __('Choose Page')?><!--</option>-->
<!--                                    --><?php //foreach ($wpPages as $page):?>
<!--                                        <option-->
<!--                                                value="--><?php //echo $page->ID?><!--"-->
<!--                                            --><?php //echo !empty($bookingSettings['after_booking_confirmed_redirect_page_id']) && $bookingSettings['after_booking_confirmed_redirect_page_id'] == $page->ID ? 'selected' : ''?>
<!--                                        >-->
<!--                                            --><?php //echo $page->post_title?>
<!--                                        </option>-->
<!--                                    --><?php //endforeach;?>
<!--                                </select>-->
<!--                            </div>-->
                        </div>
                    </div>

                    <div class="actions-buttons">
                        <button type="submit" class="button"><?php echo __('Publish', EVENTIFYME_TEXTDOMAIN)?></button>
                    </div>
                </form>
            </div>

            <div id="email-settings">
                <form method="POST" class="form-list-email-settings">
                    <?php $emailSettings = $event_settings::get_email_settings(); ?>
                    <h2>
                        <?php echo __('Email sender options', EVENTIFYME_TEXTDOMAIN)?>
                    </h2>

                    <div class="input-group-wrap input-group-wrap-email-sender-options">
                        <div class="input-group">
                            <label for="eventify_me_email_from_name"><?php echo __('Sender name', EVENTIFYME_TEXTDOMAIN)?></label>

                            <input type="text" name="eventify_me_email_from_name" id="eventify_me_email_from_name" value="<?php echo $emailSettings['eventify_me_email_from_name']?>">
                        </div>

                        <div class="input-group">
                            <label for="eventify_me_email_from_address"><?php echo __('Sender\'s address', EVENTIFYME_TEXTDOMAIN)?></label>

                            <input type="text" name="eventify_me_email_from_address" id="eventify_me_email_from_address" value="<?php echo $emailSettings['eventify_me_email_from_address']?>">
                        </div>
                    </div>

                    <h2>
                        <?php echo __('Email to client after booking confirmation', EVENTIFYME_TEXTDOMAIN)?>
                    </h2>

                    <div id="email-to-user-wrap" class="input-group-wrap input-group-email-template">
                        <div class="input-group input-group-editor">
                            <div class="input-group input-group-subject">
                                <label for="eventify_me_email_to_user_subject"><?php echo __('Email subject', EVENTIFYME_TEXTDOMAIN)?></label>
                                <input type="text" name="eventify_me_email_to_user_subject" id="eventify_me_email_to_user_subject" value="<?php echo $emailSettings['eventify_me_email_to_user_subject']?>">
                            </div>

                            <label for="eventify_me_email_to_user_text"><?php echo __('Email text', EVENTIFYME_TEXTDOMAIN)?></label>
                            <?php wp_editor( $emailSettings['eventify_me_email_to_user_text'], 'eventify_me_email_to_user_text', [
                                'textarea_name'=> 'eventify_me_email_to_user_text',
                                'textarea_rows' => 25,
                                'media_buttons' => true,
                                'teeny' => true,
                                'tabindex' => 4,
                                'tinymce' => [
                                    'toolbar1' => 'formatselect, bold, italic, bullist, numlist, alignleft, aligncenter, alignright',
                                ],
                                'quicktags' => false
                            ]);?>
                        </div>

                        <div class="input-group input-group-editor-vars-list" data-editor-id="eventify_me_email_to_user_text">
                            <label for=""><?php echo __('Parameters', EVENTIFYME_TEXTDOMAIN)?></label>
                            <div class="params-wrap">
                                <a href="#" class="button" data-template-name="client" data-var="{client_email}"><?php echo __('Client email', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{client_first_name}"><?php echo __('Client first name', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{client_last_name}"><?php echo __('Client last name', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{client_phone}"><?php echo __('Client phone', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{client_comments}"><?php echo __('Client comments', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{booked_sessions}"><?php echo __('Booked sessions', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_address}"><?php echo __('Event address', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_price}"><?php echo __('Event price', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_title}"><?php echo __('Event title', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_age_categories}"><?php echo __('Event age categories', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_languages}"><?php echo __('Event languages', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="client" data-var="{event_description}"><?php echo __('Event description', EVENTIFYME_TEXTDOMAIN)?></a>
                            </div>
                        </div>
                    </div>

                    <h2>
                        <?php echo __('Email to event managers after booking confirmation', EVENTIFYME_TEXTDOMAIN)?>
                    </h2>

                    <div id="email-to-event-managers-wrap" class="input-group-wrap input-group-email-template">
                        <div class="input-group input-group-editor">
                            <div class="input-group input-group-subject">
                                <label for="eventify_me_email_to_event_managers_subject"><?php echo __('Email subject', EVENTIFYME_TEXTDOMAIN)?></label>
                                <input type="text" name="eventify_me_email_to_event_managers_subject" id="eventify_me_email_to_event_managers_subject" value="<?php echo $emailSettings['eventify_me_email_to_event_managers_subject']?>">
                            </div>

                            <label for="eventify_me_email_to_event_managers_text"><?php echo __('Email text', EVENTIFYME_TEXTDOMAIN)?></label>
                            <?php wp_editor( $emailSettings['eventify_me_email_to_event_managers_text'], 'eventify_me_email_to_event_managers_text', [
                                'textarea_name'=> 'eventify_me_email_to_event_managers_text',
                                'textarea_rows' => 25,
                                'media_buttons' => true,
                                'teeny' => true,
                                'tabindex' => 4,
                                'tinymce' => [
                                    'toolbar1' => 'formatselect, bold, italic, bullist, numlist, alignleft, aligncenter, alignright',
                                ],
                                'quicktags' => false
                            ]);?>
                        </div>
                        <div class="input-group input-group-editor-vars-list" data-editor-id="eventify_me_email_to_event_managers_text">
                            <label for=""><?php echo __('Parameters', EVENTIFYME_TEXTDOMAIN)?></label>
                            <div class="params-wrap">
                                <a href="#" class="button" data-template-name="manager" data-var="{client_email}"><?php echo __('Client email', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{client_first_name}"><?php echo __('Client first name', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{client_last_name}"><?php echo __('Client last name', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{client_phone}"><?php echo __('Client phone', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{client_comments}"><?php echo __('Client comments', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{booked_sessions}"><?php echo __('Booked sessions', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{event_price}"><?php echo __('Event price', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{event_title}"><?php echo __('Event title', EVENTIFYME_TEXTDOMAIN)?></a>
                                <a href="#" class="button" data-template-name="manager" data-var="{event_id}"><?php echo __('Event Identifier', EVENTIFYME_TEXTDOMAIN)?></a>
                            </div>
                        </div>
                    </div>

                    <div class="actions-buttons">
                        <button type="submit" class="button"><?php echo __('Publish', EVENTIFYME_TEXTDOMAIN)?></button>
                    </div>

                    <div class="eventify-me-notification-alert alert-about-focus">
                        <?php echo __('Please put a cursor into the subject or text of the email next to the parameter you selected.', EVENTIFYME_TEXTDOMAIN)?>
                    </div>
                </form>
            </div>

<!--            <div id="license-settings">-->
<!--                --><?php //$license = new Eventify_Me_License();
//                //$license->getLicenseKeyTermTo()
//                ?>
<!--                <form method="POST" class="form-list-license-settings">-->
<!--                    <h2>--><?php //echo __('License key', EVENTIFYME_TEXTDOMAIN)?><!--</h2>-->
<!---->
<!--                    <div class="input-group-wrap input-group-wrap-email-sender-options">-->
<!--                        <div class="input-group">-->
<!--                            <input type="text"-->
<!--                                   name="eventify_me_license_key"-->
<!--                                   id="eventify_me_license_key"-->
<!--                                   value="--><?php //echo $license->getLicenseKey()?><!--"-->
<!--                                   placeholder="--><?php //echo __('License key', EVENTIFYME_TEXTDOMAIN)?><!--"-->
<!--                            >-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="actions-buttons">-->
<!--                        <button type="submit" class="button">--><?php //echo __('Publish', EVENTIFYME_TEXTDOMAIN)?><!--</button>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
        </div>
    </div>
</div>
<?php require_once EVENTIFYME_DIR_PATH . '/admin/partials/loader.php';?>