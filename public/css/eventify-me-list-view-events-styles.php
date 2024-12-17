<style>
    <?php $font = Eventify_Me_Settings::defaultFonts()[$settings['font_family']];?>
    @import url('<?php echo $font['url']?>');
    /* ANIMATION */
    .fadein {
        -webkit-animation: fadein 0.8s; /* Safari, Chrome and Opera > 12.1 */
        -moz-animation: fadein 0.8s; /* Firefox < 16 */
        -ms-animation: fadein 0.8s; /* Internet Explorer */
        -o-animation: fadein 0.8s; /* Opera < 12.1 */
        animation: fadein 0.8s;
    }

    @keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Firefox < 16 */
    @-moz-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Safari, Chrome and Opera > 12.1 */
    @-webkit-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Internet Explorer */
    @-ms-keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    /* ANIMATION */
    /* START Elementor container */
    #<?php echo $id?>.pr-container{
        max-width: 1040px!important;
        width: 100% !important;
        margin: auto !important;
    }

    #<?php echo $id?>.pr-container *,
    #<?php echo $id?>.pr-container h1,
    #<?php echo $id?>.pr-container h2,
    #<?php echo $id?>.pr-container h3,
    #<?php echo $id?>.pr-container p,
    #<?php echo $id?>.pr-container div,
    #<?php echo $id?>.pr-container h4 {
         font-family: <?php echo $font['css_name']?> !important;
         font-weight: 400;
         line-height: normal;
    }

    @media (max-width: 767px) {
        .complaint-section {
            padding-top: 60px;
            padding-bottom: 60px;
        }
    }
    @media (max-width: 1200px) {
       #<?php echo $id?>.pr-container {
            width: 910px!important;
        }
    }

    @media (max-width: 991px) {
       #<?php echo $id?>.pr-container {
            width: 670px!important;
        }
    }

    @media (max-width: 768px) {
       #<?php echo $id?>.pr-container {
            max-width: 490px!important;
        }
    }

    @media (max-width: 600px) {
       #<?php echo $id?>.pr-container {
            width: 95%!important;
            padding: 0px;
        }
    }
    /* END Elementor container */
   #<?php echo $id?> .short-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
   #<?php echo $id?> .all-event__title {
        font-size: 32px;
        line-height: 36px;
        font-weight: 400;
        color: <?php echo $settings['color_1']?>;
    }
    #<?php echo $id?> .all-event__title:before,
    #<?php echo $id?> .all-event__title:after {
        display: none;
    }
   #<?php echo $id?> .all-event__switcher {
        display: flex;
        border-bottom: 1px solid <?php echo $settings['color_4']?>;
    }
   #<?php echo $id?> .all-event__switcher-item {
        cursor: pointer;
        position: relative;
        font-size: 14px;
        padding: 13px 19px;
        color: <?php echo $settings['color_2']?>;
    }

   #<?php echo $id?> .all-event__switcher-item_active:after {
        position: absolute;
        content: '';
        top: 0;
        left: -1px;
        background: <?php echo $settings['color_3']?>;
        height: 2px;
        width: calc(100% + 2px);
        border-radius: 2px 2px 0px 0px;
    }
   #<?php echo $id?> .all-event__switcher-item_active:before {
        position: absolute;
        content: '';
        bottom: -2px;
        left: 0;
        background: #fff;
        height: 2px;
        width: 100%;
    }
   #<?php echo $id?> .all-event__switcher-item_active {
        border-radius: 2px 2px 0px 0px;
        border-left: 1px solid <?php echo $settings['color_4']?>;
        border-right: 1px solid <?php echo $settings['color_4']?>;
    }
   #<?php echo $id?> .all-event-item__info__title{
        width: 666px;
        font-size: 16px;
        font-weight: 600!important;
        line-height: 24px!important;
        color: <?php echo $settings['color_2']?>;
    }
   #<?php echo $id?> .all-event-item__info__course {
        margin-top: 7px;
        color: <?php echo $settings['color_6']?>;
        font-size: 14px;
        font-weight: 600;
    }
   #<?php echo $id?> .all-event-item__info__date,
        #<?php echo $id?> .all-event-item__info__place {
        font-size: 14px;
        margin-top: 9px;
        color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?> .all-event-item__info__date {
      text-transform: capitalize;
    }

    #<?php echo $id?>  .all-event-item__info__date__mobile {
      display: none;
    }

   #<?php echo $id?> .all-event-item__info__place {
        /* margin-top: 9px; */
    }
   #<?php echo $id?> .all-event-item__info__forwhom {
        display: flex;
        flex-wrap: wrap;
        margin-top: 11px;
        margin-left: 2px;
        align-items: center;
    }
   #<?php echo $id?> .all-event-item__info__for {
        font-size: 14px;
        background: <?php echo $settings['color_8']?>;
        border-radius: 4px;
        margin-right: 15px;
        padding: 5px 9px;
        color: <?php echo $settings['color_7']?>;
    }
   #<?php echo $id?> .all-event-item__info__tags {
        display: flex;
        flex-wrap: wrap;
    }
   #<?php echo $id?> .all-event-item__info__tags-tag {
        color: <?php echo $settings['color_3']?>;
        font-size: 14px;
        padding-right: 4px;
    }
   #<?php echo $id?> .all-event-items {
        margin-top: 17px;
    }
   #<?php echo $id?> .all-event-item {
        display: flex;
        margin-top: 13px;
        box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.08);
        border-radius: 2px;
        border: 1px solid #EEEEEE;
        position: relative;
    }
    #<?php echo $id?> .all-event-item__link {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
    #<?php echo $id?> .all-event-item__img {
        max-width: 312px;
        width: 100%;
    }
    #<?php echo $id?> .all-event-item__img img {
        width: 100%;
    }
    #<?php echo $id?> .all-event-item__img,
    #<?php echo $id?> .all-event-item__img img {
        height: 180px;
        object-fit: cover;
    }
   #<?php echo $id?> .all-event-item__info {
        flex: 1;
        padding: 11px 16px;
        background-color: <?php echo $settings['color_5']?>;
    }
   #<?php echo $id?> .all-event-item__info__ticket {
        margin-top: 8px;
        font-size: 16px;
    }
    #<?php echo $id?> .all-event-item__info__ticket__free {
      font-size: 14px;
      display: flex;
      align-items: center;
    }
    #<?php echo $id?> .all-event-item__info__ticket.all-event-item__info__ticket__free span {
        color: <?php echo $settings['color_9']?>;
        font-weight: bold;
        text-transform: uppercase;
    }
   #<?php echo $id?> .all-event-item__info__ticket:not(:first-child) {
        margin-top: 16px;
    }
   #<?php echo $id?> .all-event-item__info__ticket span {
        color: <?php echo $settings['color_1']?>;
        font-weight: 600;
        margin-left: 4px;
    }
   #<?php echo $id?> .all-event-item__info__anothers {
        display: flex;
    }
   #<?php echo $id?> .all-event-item__info__another {
        width: 55.2%;
    }
   #<?php echo $id?> .all-event-item-switcher-item {
        display: none;
    }
   #<?php echo $id?> .all-event-item-switcher-item_active {
        display: block;
        -webkit-animation: fadein 0.8s; /* Safari, Chrome and Opera > 12.1 */
        -moz-animation: fadein 0.8s; /* Firefox < 16 */
        -ms-animation: fadein 0.8s; /* Internet Explorer */
        -o-animation: fadein 0.8s; /* Opera < 12.1 */
        animation: fadein 0.8s;
    }
    @media (max-width: 1200px) {
       #<?php echo $id?> .all-event-item__info__title {
            width: 550px;
        }
        #<?php echo $id?>.pr-container {
            max-width: 100%!important;
        }
    }
    @media (max-width: 991px) {
       #<?php echo $id?> .all-event-item {
            flex-direction: column;
        }
       #<?php echo $id?> .all-event-item__img, #<?php echo $id?> .all-event-item__img img {
            width: 100%;
            height: 443px;
        max-width: 100%;
        }
    }
    @media (max-width: 767px) {
       #<?php echo $id?> .all-event-item__img, #<?php echo $id?> .all-event-item__img img {
            width: 100%;
            height: 323px;
        }
       #<?php echo $id?> .all-event-item__info__title {
            width: initial;
            white-space: initial!important;
            overflow: initial!important;
            text-overflow: initial!important;
        }
       #<?php echo $id?> .all-event-item__info {
            width: initial;
        }
       #<?php echo $id?> .all-event-item__info__forwhom {
            width: 180%;
        }
        #<?php echo $id?> .all-event-item {
            border: 0;
            box-shadow: initial;
        }
    }
    @media (max-width: 480px) {
       #<?php echo $id?> .all-event-item__img, #<?php echo $id?> .all-event-item__img img {
            width: 100%;
            height: 194px;
        }
       #<?php echo $id?> .all-event-item__info__anothers {
            flex-direction: column;
        }
       #<?php echo $id?> .all-event-item__info__another {
            width: 100%;
        }
       #<?php echo $id?> .all-event-item__info__forwhom {
            width: initial;
            display: none;
        }

    #<?php echo $id?>  .all-event-item__info__date{
                           display: none
                       }
        #<?php echo $id?>  .all-event-item__info__date__mobile {
                           display: block;
       }
    }

    #<?php echo $id?> .all-event__switcher-mobile-item {
        display: none;
    }
    @media (max-width: 767px) {
        #<?php echo $id?> .all-event-item {
            border: 0;
            box-shadow: initial;
        }
        #<?php echo $id?> .all-event__switcher-mobile-item_active {
            display: block;
            -webkit-animation: fadein 0.8s; /* Safari, Chrome and Opera > 12.1 */
            -moz-animation: fadein 0.8s; /* Firefox < 16 */
            -ms-animation: fadein 0.8s; /* Internet Explorer */
            -o-animation: fadein 0.8s; /* Opera < 12.1 */
            animation: fadein 0.8s;
            color: #6B6B6B;
            background: rgba(42, 149, 158, 0.08);
            border-radius: 5px;
            font-size: 14px;
            padding: 8px 12px;
            cursor: pointer;
        }
        #<?php echo $id?> .all-event__title-switcher {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #<?php echo $id?> .all-event__switcher {
            display: none;
        }
    }
</style>

