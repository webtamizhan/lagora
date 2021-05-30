<?php
// config for Webtamizhan/Lagora
return [

    /**
     * To generate Agora APP_ID and APP_CERTIFICATE, Please refer the url below
     * https://docs.agora.io/en/Agora%20Platform/manage_projects?platform=All%20Platforms
     */

    /**
     * Real Time Call & Video Call Service
     */
    "rtc" => [
        'app_id' => env('AGORA_RTC_APP_ID',"8e11645d9a854306a5518537079694be"),
        'app_certificate' => env('AGORA_RTC_APP_CERTIFICATE',"389bdf92e7ae470984095d6c71ed668b"),
    ],

    /**
     * Real Time Messaging
     */
    "rtm" => [
        'app_id' => env('AGORA_RTM_APP_ID',"4c22428e6e704c58892579a2ee8b252c"),
        'app_certificate' => env('AGORA_RTM_APP_CERTIFICATE',"a304d73de9cf42bc88d4c99a2a473ceb"),
    ]
];
