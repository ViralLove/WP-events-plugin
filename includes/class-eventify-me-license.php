<?php

class Eventify_Me_License {
    private $licenseKey;
    private $licenseTermTo;
    private $apiUrl;

    public function __construct(){
        $this->licenseKey = !empty(get_option('eventify_me_licenseKey')) ? get_option('eventify_me_licenseKey') : '';
        $this->licenseTermTo = !empty(get_option('eventify_me_licenseKey_term_to')) ? get_option('eventify_me_licenseKey_term_to') : '';
        $this->apiUrl = 'https://example.com/wp-json/zeya_events/v1/';
    }

    public function getLicenseKey() {
        return $this->licenseKey;
    }

    public function getLicenseKeyTermTo() {
        return $this->licenseTermTo;
    }

    public function setLicenseKey($newKey) {
        $isValid = $this->checkLicenseValid($newKey);
        if(!$isValid) return false;

        $this->licenseKey = $newKey;
        update_option('eventify_me_licenseKey_term_to', $this->licenseKey);

        $this->setLicenseTermTo($isValid['valid_to']);
        return $this->licenseKey;
    }

    public function setLicenseTermTo($newTermTo) {
        $this->licenseKey = $newTermTo;
        update_option('eventify_me_licenseKey_term_to', $this->licenseTermTo);
        return $this->licenseTermTo;
    }

    public function checkLicenseValid($license) {
        $args = ['license' => $license, 'site_url' => home_url()];
        return $this->postRequest('checkLicense', $args);
    }


    public function postRequest($endpoint, $postFields = []){
        if(!is_array($postFields)) return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $endpoint );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POST,true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec ($ch);
        $result = json_decode($result);

        return $result;
    }
}