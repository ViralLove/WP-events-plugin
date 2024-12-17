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

    #<?php echo $id?>.eventify-me-list-view-short-events-container *,
    #<?php echo $id?>.eventify-me-list-view-short-events-container h1,
    #<?php echo $id?>.eventify-me-list-view-short-events-container h2,
    #<?php echo $id?>.eventify-me-list-view-short-events-container h3,
    #<?php echo $id?>.eventify-me-list-view-short-events-container p,
    #<?php echo $id?>.eventify-me-list-view-short-events-container div,
    #<?php echo $id?>.eventify-me-list-view-short-events-container h4 {
         font-family: <?php echo $font['css_name']?> !important;
         font-weight: 400;
         line-height: normal;
    }

    #<?php echo $id?>.eventify-me-list-view-short-events-container {
      max-width: 330px;
      width: 100%;
   }

  #<?php echo $id?>.eventify-me-list-view-short-events-container .view-all-events {
      text-decoration: unset;
      display: block;
      text-align: center;
      width: 100%;
      padding: 5px;
      border-radius: 3px;
      font-weight: 600;
      font-size: 18px;
      line-height: 25px;
      color: <?php echo $settings['color_5']?>;
      background: <?php echo $settings['color_3']?>;
       margin-top: 10px;
    }

    /* END Elementor container */
   #<?php echo $id?> .eventify-me-list-view-short-events__title {
       font-size: 32px;
       line-height: 36px;
        font-weight: 400;
        margin-bottom: 24px;
        color: <?php echo $settings['color_1']?>;
    }
   #<?php echo $id?> .eventify-me-list-view-short-events__item .title{
        font-size: 16px;
        font-weight: 600!important;
        line-height: 16px!important;
        color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?>  .eventify-me-list-view-short-events__item {
        position: relative;
       display: flex;
       /*flex-wrap: wrap;*/
       align-items: flex-start;
       margin-bottom: 18px;
   }
    #<?php echo $id?> .eventify-me-list-view-short-events__item .eventify-me-list-view-short-events__item-calendar {
      /*width: 57px;*/
       border: 1px solid <?php echo $settings['color_3']?>;
       border-radius: 6px;
       margin-right: 8px;
       text-align: center;
      min-height: 75px;
      width: 17.5%;
        overflow: hidden;
    }
    #<?php echo $id?>  .eventify-me-list-view-short-events__item .eventify-me-list-view-short-events__item-calendar .month {
       background: <?php echo $settings['color_1']?>;
       /*border-radius: 6px 6px 0 0;*/
       font-weight: 600;
       font-size: 18px;
       line-height: 24px;
       color: <?php echo $settings['color_5']?>;
        text-transform: capitalize;
    }
    #<?php echo $id?>  .eventify-me-list-view-short-events__item .eventify-me-list-view-short-events__item-calendar .date {
       font-weight: 600;
       font-size: 18px;
       line-height: 24px;
       margin-top: 2px;
        color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?>  .eventify-me-list-view-short-events__item .eventify-me-list-view-short-events__item-calendar .week_num {
       font-weight: 600;
       font-size: 14px;
       line-height: 24px;
       margin-top: -4px;
       color: <?php echo $settings['color_2']?>;
       text-transform: capitalize;
    }
    #<?php echo $id?>  .eventify-me-list-view-short-events__item > a {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
    #<?php echo $id?> .eventify-me-list-view-short-events__item-info {
        flex: 1;
        width: 82.5%;
    }
    #<?php echo $id?> .top-info {
      display: flex;
      align-items: center;
      /*flex-wrap: wrap;*/
    }
    #<?php echo $id?> .top-info .time-start{
      display: inline-block;
      font-weight: 600;
      font-size: 16px;
      line-height: 24px;
      margin-right: 8px;
      color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?> .top-info .tags{
      white-space: nowrap;
      display: inline-block;
      overflow: hidden;
      text-overflow: ellipsis;
      font-weight: 400;
      font-size: 16px;
      line-height: 20px;
      color: <?php echo $settings['color_3']?>;
    }
</style>

