<style>
    <?php $event_settings = new Eventify_Me_Settings();
    $settings = $event_settings->get_visual_settings();?>
    body.loader:after {
        background: rgba(255, 255, 255, 0.7);
        width: 100%;
        height: 100%;
        content: "";
        top: 0;
        position: fixed;
        z-index: 99999;
    }

    body.loader .lds-default {
        visibility: visible;
        top: calc(50% - 40px);
    }

    body .lds-default {
        display: inline-block;
        width: 80px;
        height: 80px;
        position: fixed;
        top: 150px;
        left: calc(50% - 40px);
        visibility: hidden;
        z-index: 100000;
    }

    body .lds-default div {
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: lds-default 1.2s linear infinite;
        --tw-bg-opacity: 1;
        background-color: <?php echo $settings['color_1']?>;
    }

    body .lds-default div:nth-child(1) {
        animation-delay: 0s;
        top: 37px;
        left: 66px;
    }

    body .lds-default div:nth-child(2) {
        animation-delay: -0.1s;
        top: 22px;
        left: 62px;
    }

    body .lds-default div:nth-child(3) {
        animation-delay: -0.2s;
        top: 11px;
        left: 52px;
    }

    body .lds-default div:nth-child(4) {
        animation-delay: -0.3s;
        top: 7px;
        left: 37px;
    }

    body .lds-default div:nth-child(5) {
        animation-delay: -0.4s;
        top: 11px;
        left: 22px;
    }

    body .lds-default div:nth-child(6) {
        animation-delay: -0.5s;
        top: 22px;
        left: 11px;
    }

    body .lds-default div:nth-child(7) {
        animation-delay: -0.6s;
        top: 37px;
        left: 7px;
    }

    body .lds-default div:nth-child(8) {
        animation-delay: -0.7s;
        top: 52px;
        left: 11px;
    }

    body .lds-default div:nth-child(9) {
        animation-delay: -0.8s;
        top: 62px;
        left: 22px;
    }

    body .lds-default div:nth-child(10) {
        animation-delay: -0.9s;
        top: 66px;
        left: 37px;
    }

    body .lds-default div:nth-child(11) {
        animation-delay: -1s;
        top: 62px;
        left: 52px;
    }

    body .lds-default div:nth-child(12) {
        animation-delay: -1.1s;
        top: 52px;
        left: 62px;
    }

    @keyframes lds-default {
        0%, 20%, 80%, 100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.5);
        }
    }
</style>

<div class="lds-default">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</div>
