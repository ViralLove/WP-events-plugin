(function( $ ) {
    'use strict';

    const $body = $('body');

    // main settings tabs
    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );

    const $colorPicker =  $('.color-field').wpColorPicker();

    $('.iris-square, .iris-slider, .iris-palette').on('mousedown', function (){
        $body.on('mouseup', () => {
            setTimeout(() => {
                $('.update-preview-list-settings').click()
                $body.off('mouseup')
            }, 0)
        })
    })

    $('.wp-color-picker').off();

    $('.color-field').on('click', function (){
        $(this).closest('.wp-picker-container').find('.wp-color-result').click();
    })

    $('.color-field').on('change', function (){
        // console.log($(this).val());
        // $(this).attr('value', $(this).val())
        // var new_color = $(this).val();
        // $colorPicker.wpColorpicker('color', new_color);

        const $this = $(this)
        $this.closest('.wp-picker-container').find('.wp-color-result').css({'background-color': $this.val()})
        console.log($this.closest('.wp-picker-container').find('.wp-color-result'), $this.val());
        $('.update-preview-list-settings').click()
    })

    $('.form-list-events-settings #font_family').on('change', () => {
        $('.form-list-events-settings .update-preview-list-settings').click()
    })

    $body.on('click', '.update-preview-list-settings', updateListSettings);
    $body.on('submit', '.form-list-events-settings', updateListSettings);
    $body.on('click', '.reset-list-settings', updateListSettings);

    function updateListSettings(e){
        e.preventDefault();
        $body.addClass('loader');
        const $form = $('.form-list-events-settings'),
              data = $form.serializeArray();

        let type;
        if($(e.target).hasClass('form-list-events-settings')) type = 'main';
        else if ($(e.target).hasClass('reset-list-settings')) type = 'reset_to_default';
        else type = 'preview';

        $.ajax({
            url: ajax.ajaxurl,
            method: 'POST',
            data: {action: 'update_visual_settings', preview_settings: data, type: type},
            success: function(data){
                $body.find('#visual-settings #event-list-preview').html(data.list_preview);
                $body.find('#visual-settings #event-details-preview').html(data.detail_preview);
                $body.find('#visual-settings #event-booking-preview').html(data.booking_preview);
            },
            error: function(data){
                //
            },
            complete: function(data){
                if(type === 'reset_to_default') {
                    location.reload();
                    return false;
                } else $body.removeClass('loader');
            },
        });
        return false;
    }

    //visual settings preview tabs
    $( "#visual-settings-preview-tabs" ).tabs()

    //booking settings
    $body.on('submit', '.form-list-booking-settings', function (e){
        e.preventDefault()
        $body.addClass('loader')

        $.ajax({
            url: ajax.ajaxurl,
            method: 'POST',
            data: {action: 'update_booking_settings', settings: $(this).serializeArray()},
            success: function(data){
                //console.log(data);
            },
            error: function(data){
                //
            },
            complete: function(data){
                $body.removeClass('loader');
            },
        })
    })

    $body.on('change', '.eventify-me-settings-wrap .content-wrap > .ui-widget-content .input-group.input-group-radio input', function (e){
        const   $this = $(this),
                inputName = $this.attr('name');

        $('input[name=' + inputName + ']').closest('.input-group-radio').removeClass('active');
        $this.closest('.input-group-radio').addClass('active');
    })

    const pageForAutocomplete = JSON.parse($('.eventify-me-settings-wrap .wp-pages-for-autocomplete').text());

    $.each($('.wp-page-autocomplete'), function (){
        $(this).autocomplete({
            minLength: 0,
            source: function (request, response) {
                response($.map(pageForAutocomplete, function (val, i) {
                    if (val.post_title.toLowerCase().indexOf(request.term.toLocaleLowerCase()) > -1) {
                        return {
                            post_title: val.post_title,
                            ID: val.ID
                        };
                    }
                }));
            },
            focus: function( event, ui ) {
                $( event.target ).val( ui.item.post_title )
                $( event.target ).siblings('input[type=hidden]').val(ui.item.ID)
                return false;
            },
            select: function( event, ui ) {
                $( event.target ).val( ui.item.post_title );
                return false;
            }
        }).focus(function (){
            $(this).data("uiAutocomplete").search($(this).val());
        }).autocomplete( 'instance' )._renderItem = function( ul, item ) {
            //console.log(item);
            return $( '<li>' )
                .append( '<div>' + item.post_title + '</div>' )
                .appendTo( ul );
        }
    })

    //email settings
    $body.on('submit', '.form-list-email-settings', function (e){
        e.preventDefault()
        $body.addClass('loader')

        $.ajax({
            url: ajax.ajaxurl,
            method: 'POST',
            data: {action: 'update_email_settings', settings: $(this).serializeArray()},
            success: function(data){
               // console.log(data);
            },
            error: function(data){
                //
            },
            complete: function(data){
                $body.removeClass('loader');
            },
        })
    })

    //email template variables functions after wp_editor initialization
    setTimeout(() => {
        let focusRange
        let nodeWithVar
        let clientTemplateFocus
        let managerTemplateFocus
        const editorClient = tinyMCE.get('eventify_me_email_to_user_text')
        const editorManager = tinyMCE.get('eventify_me_email_to_event_managers_text')

        $('#eventify_me_email_to_user_subject').on('focus', function () {
            clientTemplateFocus = 'subject'
        })

        $('#eventify_me_email_to_event_managers_subject').on('focus', function () {
            managerTemplateFocus = 'subject'
        })

        editorClient.on('focus', function (e){
            clientTemplateFocus = 'wp_editor'
        })

        editorClient.on('blur', function (e){
            focusRange = editorClient.selection.getRng()
            nodeWithVar = editorClient.getDoc().createElement ('span')
        })

        editorManager.on('focus', function (e){
            managerTemplateFocus = 'wp_editor'
        })

        editorManager.on('blur', function (e){
            focusRange = editorManager.selection.getRng()
            nodeWithVar = editorManager.getDoc().createElement ('span')
        })

        let intervalAlertAboutFocus;
        $body.on('click', '.eventify-me-settings-wrap .content-wrap > .ui-widget-content .input-group-email-template .input-group-editor-vars-list .button', function (e){
            e.preventDefault()
            const $this = $(this)
            const templateName = $this.attr('data-template-name')
            const variableText = $this.attr('data-var')

            if(templateName === 'client') {
                if(clientTemplateFocus === undefined) {
                    intervalAlertAboutFocus = showEventifyMeAlert('alert-about-focus', intervalAlertAboutFocus)
                    return false
                }

                if(clientTemplateFocus === 'wp_editor') {
                    // editorClient.setContent(editorClient.getContent() + variableText)
                    nodeWithVar.innerText=variableText;
                    focusRange.insertNode(nodeWithVar)
                }
                else {
                    const inputSubj = $this.closest('.input-group-email-template').find('#eventify_me_email_to_user_subject')
                    inputSubj.val(inputSubj.val() + ' ' + variableText)
                }
            } else {
                if(managerTemplateFocus === undefined) {
                    intervalAlertAboutFocus = showEventifyMeAlert('alert-about-focus', intervalAlertAboutFocus)
                    return false
                }

                if(managerTemplateFocus === 'wp_editor') {
                    nodeWithVar.innerText=variableText;
                    focusRange.insertNode(nodeWithVar)
                }
                else {
                    const inputSubj = $this.closest('.input-group-email-template').find('#eventify_me_email_to_event_managers_subject')
                    inputSubj.val(inputSubj.val() + ' ' + variableText)
                }
            }

        })

        function sortParamsByName() {
            const sort_by_name = function(a, b) {
                return a.innerHTML.toLowerCase().localeCompare(b.innerHTML.toLowerCase());
            }

            const list = $(".params-wrap > a").get();
            list.sort(sort_by_name);
            for (let i = 0; i < list.length; i++) {
                list[i].parentNode.appendChild(list[i]);
            }
        }

        sortParamsByName();
    }, 1000)

    function showEventifyMeAlert(elClass, interval = '', timeout = 5000){
        $body.find('.' + elClass)
            .fadeIn()

        if(interval.length) clearInterval(interval)

        return setTimeout(function (){
            $body.find('.' + elClass).fadeOut()
        }, timeout)
    }
})( jQuery );