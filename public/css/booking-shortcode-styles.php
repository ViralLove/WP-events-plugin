<style>
    <?php $font = Eventify_Me_Settings::defaultFonts()[$settings['font_family']];?>
    @import url('<?php echo $font['url']?>');
    
    #<?php echo $id?> *,
    #<?php echo $id?> h1,
    #<?php echo $id?> h2,
    #<?php echo $id?> h3,
    #<?php echo $id?> p,
    #<?php echo $id?> div,
    #<?php echo $id?> h4 {
         font-family: <?php echo $font['css_name']?> !important;
         font-weight: 400;
         line-height: normal;
     }

    /* START container */
    #<?php echo $id?>.booking-shortcode-container {
        width: 753px;
        margin: auto;
    }
    @media (max-width: 768px) {
        #<?php echo $id?>.booking-shortcode-container {
            max-width: 490px!important;
        }
    }

    @media (max-width: 600px) {
        #<?php echo $id?>.booking-shortcode-container {
            width: 95%!important;
            padding: 0px;
        }
    }
    /* END container */
    #<?php echo $id?> .booking-shortcode {
        padding: 20px 28px;
    }
    #<?php echo $id?> .booking-shortcode__title {
        color: <?php echo $settings['color_2']?>;
        font-weight: bold;
        font-size: 24px;
        line-height: 28px;
        display: flex;
        align-items: center;
    }
    #<?php echo $id?> .booking-shortcode__title a{
        font-size: 14px;
        line-height: 16px;
        color: <?php echo $settings['color_3']?>;
        font-weight: normal;
        margin-right: 16px;
        text-decoration: unset;
    }
    #<?php echo $id?> .booking-shortcode__title a span {
        position: relative;
        top: -2px;
    }
    #<?php echo $id?> .booking-accrodion {
        display: none;
        margin-top: 24px;
    }
    #<?php echo $id?> .booking-accrodion-item {
        /* background: #F8F8F8; */
        border: 1px solid #DCDBDB;
    }
    #<?php echo $id?> .booking-accrodion-item:not(:last-child) {
        margin-bottom: 8px;
    }
    #<?php echo $id?> .booking-accrodion-item__parent {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
        padding-bottom: 3px;
        padding-left: 11px;
        padding-bottom: 11px;
        cursor: pointer;
        background: #f8f8f8;

    }
    #<?php echo $id?> .booking-accrodion-item__parent__totaltickets {
        text-align: right;
        margin-right: 18px;
        font-weight: bold;
        width: 50%;
        font-size: 18px;
        line-height: 25px;
        color: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__totaltickets *{
        font-weight: bold;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__numbertitle {
        display: flex;
        align-items: center;
        width: 50%;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__number {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 40px;
        height: 40px;
        background: #FFFFFF;
        line-height: 28px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 22px;
        line-height: 28px;
        color: #7A7A7A;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__number.active {
        color: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__title {
        font-size: 22px;
        padding-left: 13px;
        transform: translateY(2px);
        color: #7A7A7A;
        line-height: 28px;
        font-weight: bold;
    }
    #<?php echo $id?> .booking-accrodion-item__parent__title.active {
        color: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children {
        background-color: #fff;
    }
    #<?php echo $id?> .booking-accrodion-item__children__address {
        padding-top: 24px;
        display: flex;
        margin-left: 16px;
        font-size: 16px;
        line-height: 22px;
        color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__address svg {
        min-width: 14px;
        min-height: 20px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__address svg path {
      fill: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__address__place span {
        font-weight: bold;
        margin-left: 7px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 17px;
        margin-left: 37px;
        padding-bottom: 10px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info:not(:last-child) {
        border-bottom: 1px solid #E6E6E6;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter svg {
        min-width: 36px;
        min-height: 36px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__date {
        width: 70%;
        color: <?php echo $settings['color_2']?>;
        font-size: 16px;
        line-height: 22px;
        margin-top: -5px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__date span {
        color: <?php echo $settings['color_2']?>;
        font-weight: bold;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__number {
        width: 39px;
        font-size: 16px;
        line-height: 22px;
        color: <?php echo $settings['color_2']?>;
        text-align: center;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__number span {
        transform: translateY(-3px);
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__minus,
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__plus {
        width: 36px;
        height: 36px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__minus svg,
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__plus svg {
        cursor: pointer;
        width: 36px;
        height: 36px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__minus.active svg path:first-child,
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__plus svg path:first-child {
        fill: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children-info__counter__minus.active svg path:last-child {
        fill: #fff;
    }
    #<?php echo $id?> .booking-accrodion-item__children--info__date {
        margin-top: -5px;
        margin-left: 27px;
        font-size: 16px;
        line-height: 22px;
        color: <?php echo $settings['color_2']?>;
        text-transform: capitalize;
    }
    #<?php echo $id?> .booking-accrodion-item__children--info__date span {
        font-weight: bold;
    }
    #<?php echo $id?> .booking-accrodion-item__children__button {
        display: flex;
        margin-left: 15px;
        margin-top: 1px;
        padding-bottom: 22px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__button .proceed-button,
    #<?php echo $id?> .booking-accrodion-item__children__button .proceed-button:visited,
    #<?php echo $id?> .booking-accrodion-item__children__button .proceed-button:hover,
    #<?php echo $id?> .booking-accrodion-item__children__button .proceed-button:active {
        color: #FFFFFF!important;
        background: <?php echo $settings['color_3']?>;
        cursor: pointer;
        outline: none;
        border: none;
        padding: 11px 0px;
        font-size: 16px;
        line-height: 19px;
        text-decoration: none;
        border-radius: 60px;
        padding-left: 34px;
        padding-right: 34px;
    }

    #<?php echo $id?> .booking-accrodion-item__children__form {
        padding-left: 12px;
        padding-right: 13px;
        padding-top: 1px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item {
        display: flex;
        flex-direction: column;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item label {
        font-weight: 600;
        font-size: 16px;
        line-height: 22px;
        color: <?php echo $settings['color_2']?>;
        margin-top: 16px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item label[for=acc-subscription] {
      cursor: pointer;
      padding-left: 30px;
      position: relative;
      font-size: 14px;
      color: <?php echo $settings['color_3']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item label[for=acc-subscription] span {
        color: <?php echo $settings['color_6']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item label span {
        color: #f44336
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item input,
    #<?php echo $id?> .booking-accrodion-item__children__form-item textarea {
        margin-top: 8px;
        background: #FFFFFF;
        border: 1px solid #DCDBDB;
        height: 36px;
        border-radius: 2px;
        font-size: 16px;
        background: #FFFFFF;
        padding-left: 10px;
        font-weight: 600;
        font-size: 16px;
        line-height: 22px;
        color: <?php echo $settings['color_2']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item textarea {
        font-weight: 400;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item input:focus {
        outline: none;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item textarea {
        margin-top: 8px;
        height: 71px;
        font-size: 16px;
        border: 1px solid #DCDBDB;
        box-sizing: border-box;
        border-radius: 2px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item textarea:focus {
        outline: none;
    }

    #<?php echo $id?> .booking-accrodion-item__children__form-item input[type=checkbox] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        position: absolute;
        left: 0;
        margin: 0;
        top: 3px;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #fff;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item input[type=checkbox]:after {
        content: "";
        opacity: 0;
        display: block;
        left: 6px;
        top: 3px;
        position: absolute;
        width: 6px;
        height: 10px;
        border: 2px solid <?php echo $settings['color_3']?>;
        border-top: 0;
        border-left: 0;
        transform: rotate(30deg);
    }
    #<?php echo $id?> .booking-accrodion-item__children__form-item input[type=checkbox]:checked:after {
      opacity: 1;
    }
    #<?php echo $id?> .booking-accrodion-item__children__form .booking-accrodion-item__children__button {
        margin-top: 16px;
        padding-bottom: 14px;
        margin-left: 0;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket span {
        color: <?php echo $settings['color_1']?>;
        font-weight: 600;
        font-size: 18px;
        line-height: 20px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket svg {
        margin-left: 6px;
        min-width: 30px;
        min-height: 24px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket svg path {
        fill: <?php echo $settings['color_1']?>;
    }
    #<?php echo $id?> .booking-accrodion-item__children__tickets {
        padding-left: 16px;
        padding-top: 7px;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket {
        margin-top: 8px;
        width: 353px;
        display: flex;
        justify-content: space-between;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket {
        display: flex;
        background: #fff;
        transform: translateY(1px);
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__date {
        color: <?php echo $settings['color_2']?>;
        font-size: 16px;
        background: #fff;
        line-height: 22px;
        text-transform: capitalize;
    }
    #<?php echo $id?> .booking-accrodion-item__children__successtext {
        margin-top: 16px;
        font-size: 16px;
        line-height: 26px;
        color: <?php echo $settings['color_2']?>;
        margin-left: 14px;
        margin-right: 20px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__successtext a,
    #<?php echo $id?> .booking-accrodion-item__children__successtext a:visited
    #<?php echo $id?> .booking-accrodion-item__children__successtext a:hover
    #<?php echo $id?> .booking-accrodion-item__children__successtext a:active {
        color: <?php echo $settings['color_3']?>;
        text-decoration: none;
        font-size: 16px;
        line-height: 26px;
    }
    #<?php echo $id?> .booking-accrodion-item__children__button__success a,
    #<?php echo $id?> .booking-accrodion-item__children__button__success a:visited,
    #<?php echo $id?> .booking-accrodion-item__children__button__success a:hover,
    #<?php echo $id?> .booking-accrodion-item__children__button__success a:active {
        margin-top: 15px;
        background: <?php echo $settings['color_1']?>!important;
        letter-spacing: -0.4px;
        padding-right: 26px;
        padding-left: 26px;
        color: #FFFFFF!important;
        background: <?php echo $settings['color_3']?>;
        cursor: pointer;
        outline: none;
        border: none;
        padding: 11px 0px;
        font-size: 16px;
        line-height: 19px;
        text-decoration: none;
        border-radius: 60px;
        padding-left: 34px;
        padding-right: 34px;
    }

    #<?php echo $id?> .booking-accrodion-item__children__button__success {
        padding-bottom: 20px;
    }
    #<?php echo $id?> .heightBeforeInit {
        height: 0px;
        -webkit-transition: height 1s ease-out;
        transition: height 1s ease-out;
        -webkit-transform: scaleY(0);
        transform: scaleY(0);
    }
    #<?php echo $id?> .heightInit {
        transform: scaleY(1)!important;
        transform-origin: top!important;
        transition: transform 0.4s ease!important;
        height: auto!important;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket {
        position: relative;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket {
        z-index: 2;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket::after {
        position: absolute;
        content: '';
        width: 100%;
        height: 1px;
        border-bottom: 1px dashed <?php echo $settings['color_6']?>;
        transform: translateY(14px);
        z-index: 1;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__date {
        z-index: 2;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__date,
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket {
        position: relative;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__date:after {
        position: absolute;
        content: '';
        width: 5px;
        height: 100%;
        background: #fff;
    }
    #<?php echo $id?> .booking-accrodion-item__children-ticket__ticket:before {
        position: absolute;
        content: '';
        width: 5px;
        height: 100%;
        left: -5px;
        background: #fff;
    }
</style>
