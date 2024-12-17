<style>
<?php
$font = Eventify_Me_Settings::defaultFonts()[$settings['font_family']];?>
@import url('<?php echo $font['url']?>');

.single-eventify-me-wrap, .single-eventify-me-wrap * {
    font-family: <?php echo $font['css_name']?> !important;
}

.single-eventify-me-wrap {
    margin-bottom: 25px;
}
.single-eventify-me-wrap .eventify-me-plugin-ad-text{
    margin-top: 25px;
}
.event-container {
    width: 1080px;
    margin: auto;
}
@media (max-width: 1200px) {
    .event-container {
        width: 910px!important;
    }
}

@media (max-width: 991px) {
    .event-container {
        width: 670px!important;
    }
}

@media (max-width: 768px) {
    .event-container {
        max-width: 490px!important;
    }
}

@media (max-width: 600px) {
    .event-container {
        width: 95%!important;
        padding: 0px;
    }
}
/* END Elementor container */
.event-cover {
    margin-top: 42px;
    display: flex;
    background: <?php echo $settings['color_11']?>;
}
.event-cover__img,
.event-cover__img img {
    width: 100%;
    max-width: 816px;
    height: 350px;
    object-fit: cover;
}
.event-cover__info {
    display: flex;
    flex-direction: column;
    padding: 24px 16px 0px 16px;
}
.event-cover__info__date {
    font-size: 16px;
    font-weight: 400;
    line-height: 22px;
    color: <?php echo $settings['color_10']?>;
    text-transform: capitalize;
}
.event-cover__info__time,
.event-cover__info__duration {
    line-height: 22px;
    font-size: 16px;
    font-weight: 400;
}
.event-cover__info__time {
    margin-top: 19px;
}
.event-cover__info__more {
    margin-top: 2px;
    line-height: 22px;
}
.event-cover__info__duration {
    margin-top: 2px;
}
.event-cover__info__time span,
.event-cover__info__duration span {
    font-weight: 600;
}
.event-cover__info__more,
.event-cover__info__more:hover,
.event-cover__info__more:visited {
    font-size: 16px;
    font-weight: 400;
    color: <?php echo $settings['color_3']?>!important;
    text-decoration: none;
}
.event-cover__info__tags {
    display: flex;
    flex-wrap: wrap;
    margin-top: 17px;
}
.event-cover__info__tags-tag {
    font-size: 16px;
    color: <?php echo $settings['color_3']?>;
    margin-right: 4px;
    margin-top: 1px;
    line-height: 22px;
}
.event-information {
    display: flex;
}
.event-information__price {
    margin-top: 8px;
    margin-left: 14px;
}
.event-information__price__info {
    line-height: 22px;
    font-size: 16px;
    margin-top: 8px;
}
.event-information__price__info p {
    margin: 0 0 15px;
}
.event-information__price__info p:last-child {
    margin: 0;
}
.event-information__price__info h1 {
    font-weight: 600;
    font-size: 16px;
    line-height: 24px;
    color: <?php echo $settings['color_1']?>;
    margin: 8px 0 0 0;
}
.event-information__price__info h1:before,
.event-information__price__info h1:after {
    display: none!important;
}
.event-information__info {
    min-width: 802px;
    max-width: 802px;
}
.event-information__infoshare {
    display: flex;
    padding-top: 36px;
    padding-bottom: 11px;
    justify-content: space-between;
    border-bottom: 1px solid <?php echo $settings['color_4']?>;
}
.event-information__price__lessons {
    line-height: 22px;
    font-size: 16px;
    margin-top: 7px;
    font-weight: 600;
}
.event-information__price__prices {
    margin-top: 7px;
}
.event-information__price__prices-price {
    line-height: 22px;
    font-weight: 600;
    font-size: 16px;
    line-height: 24px;
    color: <?php echo $settings['color_1']?>;
}
.event-information__infoshare__share,
.event-information__infoshare__share:hover,
.event-information__infoshare__share:visited,
.event-information__infoshare__share:hover {
    cursor: pointer;
    color: <?php echo $settings['color_3']?>;
    font-size: 14px;
}
.event-information__infoshare__share a,
.event-information__infoshare__share a:hover,
.event-information__infoshare__share a:visited,
.event-information__infoshare__share a:hover {
    cursor: pointer;
    color: <?php echo $settings['color_3']?>;
    font-size: 14px;
}
.event-information__infoshare__share span {
    font-size: 14px;
    color: <?php echo $settings['color_3']?>;
}
.event-information__infoshare__share svg {
    transform: translate(-2px, 2px);
}
.event-information__infoshare__info {
    display: flex;
    flex-wrap: wrap;
}
.event-information__infoshare__info-item:not(:last-child) {
    margin-right: 33px;
}
.event-information__infoshare__info-item {
    font-size: 20px;
    line-height: 24px;
    font-weight: 400;
    line-height: 24px;
}
.event-information__infoshare__info-item,
.event-information__infoshare__info-item:hover,
.event-information__infoshare__info-item:visited,
.event-information__infoshare__info-item:hover {
    color: #fff!important;
    text-decoration: none;
    font-size: 20px;
    line-height: 24px;
    font-weight: 400;
    color: <?php echo $settings['color_2']?>!important;
    line-height: 24px;
}
.event-information__price__title {
    font-size: 16px;
    font-weight: 600;
    line-height: 24px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__title {
    margin-top: 27px;
    line-height: 56px;
    font-size: 44px;
    font-weight: 400;
    color: <?php echo $settings['color_2']?>;
}
.event-information__info__whatsthat {
    margin-left: -1px;
    margin-top: 18px;
    line-height: 22px;
    font-size: 16px;
    color: <?php echo $settings['color_3']?>;
    font-weight: 600;
}
.event-information__info__forwhom__people__class {
    line-height: 22px;
}
.event-information__info__forwhom__lang__langs {
    line-height: 22px;
}
.event-information__info__forwhom {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
    padding: 15px 0px;
    border-top: 1px solid <?php echo $settings['color_4']?>;
    border-bottom: 1px solid <?php echo $settings['color_4']?>;
}
.event-information__info__forwhom__people,
.event-information__info__forwhom__lang {
    display: flex;
    align-items: center;
    font-weight: 600;
    font-size: 16px;
}
.event-information__info__forwhom__people span,
.event-information__info__forwhom__lang span {
    font-size: 16px;
    margin-left: 11px;
}
.event-information__infoshare__share svg {
    min-width: 19px;
    min-height: 16px;
}
.event-information__info__forwhom__lang {
    margin-right: 12px;
    display: flex;
    width: 48%;
    justify-content: end;
}
.event-information__info__forwhom__lang svg {
    margin-right: -2px;
}
.event-information__info__forwhom__description {
    margin-top: 23px;
    margin-left: -2px;
    color: <?php echo $settings['color_7']?>;
    font-size: 16px;
    font-size: 16px;
    line-height: 22px;
}
.event-information__info__forwhom__description ul {
    margin: 0;
    padding: 0 0 0 25px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h1 {
    font-size: 42px;
    font-weight: 700;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h2 {
    font-size: 38px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h3 {
    font-size: 34px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h4 {
    font-size: 30px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h5 {
    font-size: 26px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description h6 {
    font-size: 22px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__info__forwhom__description p, .event-information__info__forwhom__description div:not(.event-information__info__forwhom__pricemobile) {
    font-size: 16px;
    margin: 0;
    margin-top: 22px;
    line-height: 21.79px;
    margin-right: 5px;
    color: <?php echo $settings['color_7']?>;
}
.event-information__price__slider {
    display: flex;
}
.event-information__price__slider {
    margin-top: 16px;
    margin-left: -2px;
}
.event-information__price__slider-slide {
    position: relative;
    cursor: pointer;
    margin-right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.event-information__price__slider-slide__counter {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
}
.event-information__price__slider-slide__counter span {
    color: <?php echo $settings['color_5']?>;
    font-size: 32px;
    font-weight: bold;
    z-index: 2;
}
.event-information__price__slider-slide__counter:after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: <?php echo $settings['color_10']?>;
    opacity: 0.32;
    z-index: 1;
}
.event-information__price__slider__when {
    margin-top: 24px;
    margin-left: -2px;
}
.event-information__price__slider__when__title {
    font-size: 32px;
    line-height: 45px;
    color: <?php echo $settings['color_1']?>;
}
.event-information__price__slider__when-date {
    display: flex;
    margin-top: 8px;
}
.event-information__price__slider__when__dates {
    display: flex;
    margin-top: 9px;
}
.event-information__price__slider__when__dates__datelist-item,
.event-information__price__slider__when__weekdaylist-item,
.event-information__price__slider__when__timelist-item {
    margin-top: 8px;
    line-height: 22px;
}
.event-information__price__slider__when__dates__datelist-item {
    margin-right: 25px;
    font-size: 16px;
    line-height: 22px;
    color: <?php echo $settings['color_2']?>;
}
.event-information__price__slider__when__weekdaylist-item {
    font-size: 16px;
    line-height: 22px;
    color: <?php echo $settings['color_2']?>;
    margin-right: 16px;
    text-transform: capitalize;
}
.event-information__price__slider__when__weekdaylist-item-mobile {
    display: none;
}
.event-information__price__slider__when__timelist-item {
    font-size: 16px;
    color: <?php echo $settings['color_1']?>;
}
.event-information__where {
    margin-top: 32px;
}
.event-information__where__geo__address {
    color: <?php echo $settings['color_3']?>;
    font-size: 16px;
    margin-left: 12px;
    line-height: 22px;
}
.event-information__where__geo__address__name{
    color: <?php echo $settings['color_3']?>;
    font-size: 16px;
    line-height: 22px;
    margin: 0 0 10px;
    width: 100%;
}
.event-information__where__geo {
    display: flex;
    margin-top: 15px;
    margin-left: -1px;
    flex-wrap: wrap;
}
.event-information__where__geo svg {
    min-width: 14px;
    min-height: 20px;
}
.event-information__where__title {
    font-size: 32px;
    line-height: 44px;
    color: <?php echo $settings['color_1']?>;
}
.event-information__where__geo__extrainfo {
    margin-top: 11px;
    line-height: 22px;
    margin-left: -2px;
    font-size: 16px;
}
.event-information__where__geo__extrainfo p {
    margin: 0 0 15px;
}
.event-information__where__geo__extrainfo p:last-child {
    margin: 0;
}
.event-information__price__slider .event-information__price__slider-slide,
.event-information__price__slider .event-information__price__slider-slide img {
    width: 152px;
    height: 104px;
    object-fit: cover;
}
#ic_family path {
    color: transparent;
}
.event-information__info__forwhom__pricemobile {
    display: none;
}

/* START slider */
.swiper-container {
    width: 100%;
    height: 300px;
    margin: 20px auto;
    z-index: 3!important;
}

.fade-in {
    animation: fadeIn ease 10s;
    -webkit-animation: fadeIn ease 10s;
    -moz-animation: fadeIn ease 10s;
    -o-animation: fadeIn ease 10s;
    -ms-animation: fadeIn ease 10s;
}

@keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}

@-moz-keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}

@-webkit-keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}

@-o-keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}

@-ms-keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}
@keyframes fadeOut {
    0% {opacity:1;}
    100% {opacity:0;}
}

@keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}

@-moz-keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}

@-webkit-keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}

@-o-keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}

@-ms-keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}
@keyframes fadeIn {
    0% {opacity:0;}
    100% {opacity:1;}
}


.slider-event {
    position: fixed;
    width: 100%;
    display: none;
    top: 0;
    z-index: 2;
}
.fade-in {
    animation: fadeIn ease 0.300s;
}
.fade-out {
    animation: fadeOut ease 0.300s;
}
.slider-event-active {
    display: block;
}
.slider-event-close {
    position: absolute;
    right: 32px;
    top: 32px;
    z-index: 3;
    cursor: pointer;
}
.gallery-top .swiper-slide-container {
    text-align: center;
    font-size: 18px;
    background: <?php echo $settings['color_5']?>;
    height: 100%;
    width: 1039px;
    height: 520px;
    margin:auto;
    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}
.gallery-top .swiper-slide-container img {
    width: 1039px;
    height: 520px;
    object-fit: cover;
}
.event-information__info__forwhom__people {
    display: flex;
    width: 48%;
}
.event-information__info__forwhom__people svg {
    min-width: 28px;
    min-height: 25px;
}
.event-information__info__forwhom__lang svg {
    min-width: 28px;
    min-height: 25px;
}
.event-information__info__forwhom__slidermobile {
    display: none;
}
.gallery-thumbs .swiper-slide-container {
    text-align: center;
    font-size: 18px;
    background: <?php echo $settings['color_5']?>;
    height:100%;
    max-width: 600px;
    margin:auto;
    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}

.gallery-top {
    height: 80%;
    width: 100%;
}
.gallery-thumbs {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;
}
.gallery-thumbs .swiper-slide-active {
    opacity: 1;
}
.gallery-thumbs {
    margin-top: 12vh!important;
}
.gallery-thumbs .swiper-slide {
    width: 152px!important;
    height: 104px!important;
}
.gallery-thumbs .swiper-slide img {
    width: 152px!important;
    height: 104px!important;
}
.swiper-slider-container {
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 3;
    margin-top: 11vh;
}
.slider-event-arrows {
    width: 1330px;
    margin: auto;
    position: fixed;
    left: 0;
    right: 0;
    top: 400px;
    z-index: 4;
}
.gallery-thumbs-container {
    width: 800px;
    margin: auto;
}
.gallery-top .swiper-slide-container {

}
.swiper-slider {
    z-index: 4;
}
.slider-event-arrows .swiper-button-next {
    background-image: none!important;
}
.slider-event-arrows .swiper-button-prev {
    background-image: none!important;
}
.slider-event-overlay {
    position: fixed;
    top: 0;
    width: 100%;
    height: 100%;
    background: <?php echo $settings['color_10']?>;
    opacity: 0.85;
    z-index: 2;
    opacity: 0.85;
}
/* Effect 1: Fade in and scale up */


/* END slider */
.event-information__info__sharemobile {
    display: none;
}
.event-information__info__tagsmobile {
    display: none;
}
.event-information__info__buttonmobile {
    display: none
}
.gallery-thumbs-counter {
    font-size: 16px;
    font-weight: 400;
    color: <?php echo $settings['color_5']?>;
    transform: translateY(7px);
    text-align: center;
}
.event-cover__info__button {
    width: 100%;
    line-height: 19px;
    background: <?php echo $settings['color_3']?>;
    /*padding: 8px 0px;*/
    text-align: center;
    border-radius: 3px;
    margin-top: 19px;
    margin-bottom: 20px;
}
.event-cover__info__button a,
.event-cover__info__button a:hover,
.event-cover__info__button a:visited,
.event-cover__info__button a:hover {
    color: #fff!important;
    text-decoration: none;
    cursor: pointer;
    font-weight: 600;
    line-height: 19.07px;
    font-size: 14px;
    display: block;
    padding: 10px 0px
}

@media (max-width: 1200px) {
    .event-cover__img, .event-cover__img img {
        width: 650px;
        height: 350px;
    }
    .event-information__price__slider {
        flex-wrap: wrap;
    }
    .event-information__info {
        min-width: 640px;
        max-width: 640px;
    }
    .event-information__price__slider {
        margin-top: 0;
    }
    .event-information__price__slider-slide {
        margin-top: 16px;
    }
}
@media (max-width: 991px) {
    .event-cover__img, .event-cover__img img {
        width: 445px;
        height: 350px;
    }
    .event-information__info {
        min-width: 400px;
        max-width: 435px;
    }
}
@media (max-width: 767px) {
    .event-cover__info {
        display: none;
    }
    .event-cover__img, .event-cover__img img {
        width: 100%;
        height: 193px;
    }
    .event-information__price {
        display: none;
    }
    .event-information__info {
        min-width: 100%;
        max-width: 100%;
    }
    .event-information__infoshare__share {
        display: none;
    }
    .event-information__infoshare {
        width: 100%;
    }
    .event-information__price__slider {
        display: none;
    }
    .event-information__infoshare__info {
        justify-content: space-between;
        width: 100%;
    }
    .event-information__info__title {
        font-size: 24px;
        font-weight: 600;
    }
    .event-information__info__sharemobile {
        display: block;
        width: 35px;
        text-align: right;
    }
    .event-information__info__title {
        display: flex;
        line-height: 30px;
    }
    .event-information__info__buttonmobile {
        display: block;
    }
    .event-information__info__buttonmobile a,
    .event-information__info__buttonmobile a:hover,
    .event-information__info__buttonmobile a:visited,
    .event-information__info__buttonmobile a:hover {
        color: <?php echo $settings['color_5']?>!important;
        text-decoration: none;
        cursor: pointer;
    }
    .event-information__info__buttonmobile {
        margin-top: 16px;
    }
    .event-information__info__buttonmobile a {
        background: <?php echo $settings['color_3']?>;
        padding: 13px 0px;
        min-width: 100%;
        display: flex;
        text-align: center;
        justify-content: center;
        border-radius: 2px;
    }
    .event-information__info__forwhom__people {
        flex-direction: column;
        width: 48%;
    }
    .event-information__info__forwhom__people span, .event-information__info__forwhom__lang span {
        font-size: 16px;
        margin-left: 0px;
        margin-top: 10px;
        color: <?php echo $settings['color_7']?>;
    }
    .event-information__info__tagmobile {
        margin-right: 4px;
        color: <?php echo $settings['color_3']?>
    }
    .event-information__info__tagsmobile {
        margin-top: 15px;
        display: flex;
        line-height: 22px;
        flex-wrap: wrap;
        padding-top: 17px;
        border-top: 1px solid <?php echo $settings['color_4']?>;
        font-size: 16px;
    }
    .event-information__info__forwhom {
        padding-top: 24px;
        padding-bottom: 32px;
    }
    .event-information__price__slider__when__weekdaylist-item {
        display: none;
        line-height: 22px;
    }
    .event-information__price__slider__when__weekdaylist-item-mobile {
        margin-top: 8px;
        display: block;
    }
    .event-information__price__slider__when__dates__datelist-item {
        margin-right: 8px;
    }
    .event-information__price__slider__when__weekdaylist-item-mobile {
        margin-right: 12px;
        color: <?php echo $settings['color_2']?>;
        font-size: 16px;
        line-height: 22px;
        text-transform: capitalize;
    }
    .event-information__info__forwhom__pricemobile {
        display: block;
        margin-top: -14px;
        margin-bottom: -19px;
    }
    .event-information__info__forwhom__pricemobile h1 {
        font-size: 16px;
        color: <?php echo $settings['color_1']?>;
        font-weight: 600;
        line-height: 22px;
        margin: 0;
    }
    .event-information__info__forwhom__pricemobile h1:before,
    .event-information__info__forwhom__pricemobile h1:after {
        display: none!important;
    }
    .event-information__info__forwhom__lang {
        display: flex;
        width: 48%;
        justify-content: end;
        flex-direction: column;
    }
    .event-information__info__forwhom__slidermobile {
        margin-top: 20px;
        display: block;
        overflow: hidden;
    }
    .event-information__info__forwhom__slidermobile .swiper-slide,
    .event-information__info__forwhom__slidermobile .swiper-slide img {
        max-height: 235px;
        width: 100%;
        object-fit: cover;
    }
    .swiper-mobile {
        position: relative;
        max-width: 400px;
        overflow: hidden;
    }
    .swiper-mobile .swiper-button-prev {
        position: initial;
        background-image: url();
        transform: translateX(10px);
    }
    .swiper-mobile .swiper-button-next {
        position: initial;
        background-image: url();
        transform: translateX(-10px);
    }
    .swiper-mobile-arrows {
        /* position: relative; */
        position: absolute;
        top: 50%;
        width: 100%;
        z-index: 5;
        display: flex;
        justify-content: space-between;
    }
}

.eventify_me_notice {
    background: #fff;
    border: 1px solid <?php echo $settings['color_3']?>;
    border-left-width: 4px;
    box-shadow: 0 1px 1px rgb(0 0 0 / 4%);
    padding: 1px 12px;
    font-weight: 600;
}
.eventify_me_notice p {
    margin: 0.5em 0;
    padding: 2px;
    font-size: 13px;
    line-height: 1.5;
    color: <?php echo $settings['color_2']?>;
}
</style>