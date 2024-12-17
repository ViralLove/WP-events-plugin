jQuery(function($) {
    'use strict';

    $('body').on('click', '.all-event__switcher-item', function (e){
        if ($(this).hasClass('all-event__switcher-item_active')) return

        const dataTarget = $(this).attr('data-target');

        $(this).addClass('all-event__switcher-item_active');
        $(this).siblings('.all-event__switcher-item').removeClass('all-event__switcher-item_active')

        $('.all-event-item-switcher-item:not([data-target='+dataTarget+'])').removeClass('all-event-item-switcher-item_active')
        $('.all-event-item-switcher-item[data-target='+dataTarget+']').addClass('all-event-item-switcher-item_active')
    })

    $('body').on('click', '.all-event__switcher-mobile-item', function (e){
        const dataTarget = $(this).attr('data-target');

        $(this).removeClass('all-event__switcher-mobile-item_active');
        $(this).siblings('.all-event__switcher-mobile-item').addClass('all-event__switcher-mobile-item_active')

        $('.all-event-item-switcher-item:not([data-target='+dataTarget+'])').removeClass('all-event-item-switcher-item_active')
        $('.all-event-item-switcher-item[data-target='+dataTarget+']').addClass('all-event-item-switcher-item_active')
    })
});