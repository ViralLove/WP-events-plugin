/*
* this script file for gallery images custom field
*/

function in_array(el, arr) {
    for(var i in arr) {
        if(arr[i] == el) return true;
    }
    return false;
}

jQuery( function( $ ) {
    /*
     * Sortable images
     */
    $('ul.eventify-me-gallery-field').sortable({
        items:'li',
        cursor:'-webkit-grabbing', /* mouse cursor */
        scrollSensitivity:40,
        /*
        You can set your custom CSS styles while this element is dragging
        start:function(event,ui){
            ui.item.css({'background-color':'grey'});
        },
        */
        stop:function(event,ui){
            ui.item.removeAttr('style');

            var sort = new Array(), /* array of image IDs */
                gallery = $(this); /* ul.eventify-me-gallery-field */

            /* each time after dragging we resort our array */
            gallery.find('li').each(function(index){
                sort.push( $(this).attr('data-id') );
            });
            /* add the array value to the hidden input field */
            gallery.parent().next().val( sort.join() );
            /* console.log(sort); */
        }
    });
    /*
     * Multiple images uploader
     */
    $('.misha_upload_gallery_button').click( function(e){ /* on button click*/
        e.preventDefault();

        var button = $(this),
            hiddenfield = button.prev(),
            hiddenfieldvalue = hiddenfield.val().split(","), /* the array of added image IDs */
            custom_uploader = wp.media({
                title: 'Insert images', /* popup title */
                library : {type : 'image'},
                button: {text: 'Use these images'}, /* "Insert" button text */
                multiple: true
            }).on('select', function() {

                var attachments = custom_uploader.state().get('selection').map(function( a ) {
                        a.toJSON();
                        return a;
                    }),
                    thesamepicture = false,
                    i;

                /* loop through all the images */
                for (i = 0; i < attachments.length; ++i) {

                    /* if you don't want the same images to be added multiple time */
                    if( !in_array( attachments[i].id, hiddenfieldvalue ) ) {

                        /* add HTML element with an image */
                        $('ul.eventify-me-gallery-field').append('<li data-id="' + attachments[i].id + '"><span style="background-image:url(' + attachments[i].attributes.url + ')"></span><a href="#" class="misha_gallery_remove"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n' +
                            '<path d="M12 0.375C5.57812 0.375 0.375 5.57812 0.375 12C0.375 18.4219 5.57812 23.625 12 23.625C18.4219 23.625 23.625 18.4219 23.625 12C23.625 5.57812 18.4219 0.375 12 0.375ZM17.6719 15.0938C17.9062 15.2812 17.9062 15.6562 17.6719 15.8906L15.8438 17.7188C15.6094 17.9531 15.2344 17.9531 15.0469 17.7188L12 14.625L8.90625 17.7188C8.71875 17.9531 8.34375 17.9531 8.10938 17.7188L6.28125 15.8438C6.04688 15.6562 6.04688 15.2812 6.28125 15.0469L9.375 12L6.28125 8.95312C6.04688 8.76562 6.04688 8.39062 6.28125 8.15625L8.15625 6.32812C8.34375 6.09375 8.71875 6.09375 8.95312 6.32812L12 9.375L15.0469 6.32812C15.2344 6.09375 15.6094 6.09375 15.8438 6.32812L17.6719 8.15625C17.9062 8.39062 17.9062 8.76562 17.6719 8.95312L14.625 12L17.6719 15.0938Z" fill="#E8E8E8"/>\n' +
                            '</svg></a></li>');
                        /* add an image ID to the array of all images */
                        hiddenfieldvalue.push( attachments[i].id );
                    } else {
                        thesamepicture = true;
                    }
                }
                /* refresh sortable */
                $( "ul.eventify-me-gallery-field" ).sortable( "refresh" );
                /* add the IDs to the hidden field value */
                hiddenfield.val( hiddenfieldvalue.join() );
                /* you can print a message for users if you want to let you know about the same images */
                if( thesamepicture == true ) alert('The same images are not allowed.');
            }).open();
    });

    /*
     * Remove certain images
     */
    $('body').on('click', '.misha_gallery_remove', function(){
        var id = $(this).parent().attr('data-id'),
            gallery = $(this).parent().parent(),
            hiddenfield = gallery.parent().next(),
            hiddenfieldvalue = hiddenfield.val().split(","),
            i = hiddenfieldvalue.indexOf(id);

        $(this).parent().remove();

        /* remove certain array element */
        if(i != -1) {
            hiddenfieldvalue.splice(i, 1);
        }

        /* add the IDs to the hidden field value */
        hiddenfield.val( hiddenfieldvalue.join() );

        /* refresh sortable */
        gallery.sortable( "refresh" );

        return false;
    });
    /*
     * Selected item
     */
    $('body').on('mousedown', 'ul.eventify-me-gallery-field li', function(){
        var el = $(this);
        el.parent().find('li').removeClass('misha-active');
        el.addClass('misha-active');
    });
});