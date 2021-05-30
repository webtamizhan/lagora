<?php
// config for Webtamizhan/ClassName
return [

    /**
     * To generate Agora APP_ID and APP_CERTIFICATE, Please refer the url below
     * https://docs.agora.io/en/Agora%20Platform/manage_projects?platform=All%20Platforms
     */

    "rtc" => [
        'app_id' => env('AGORA_RTC_APP_ID',"8e11645d9a854306a5518537079694be"),
        'app_certificate' => env('AGORA_RTC_APP_CERTIFICATE',"389bdf92e7ae470984095d6c71ed668b"),
    ]
];
