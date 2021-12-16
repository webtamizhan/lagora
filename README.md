# A Laravel wrapper for Agora Services

[![Latest Version on Packagist](https://img.shields.io/packagist/v/webtamizhan/lagora.svg?style=flat-square)](https://packagist.org/packages/webtamizhan/lagora)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/webtamizhan/lagora/run-tests?label=tests)](https://github.com/webtamizhan/lagora/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/webtamizhan/lagora/Check%20&%20fix%20styling?label=code%20style)](https://github.com/webtamizhan/lagora/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/webtamizhan/lagora.svg?style=flat-square)](https://packagist.org/packages/webtamizhan/lagora)

## Installation

You can install the package via composer:

```bash
composer require webtamizhan/lagora
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Webtamizhan\Lagora\LagoraServiceProvider" --tag="lagora-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Webtamizhan\Lagora\LagoraServiceProvider" --tag="lagora-config"
```

This is the contents of the published config file:

```php

return [

    /**
     * To generate Agora APP_ID and APP_CERTIFICATE, Please refer the url below
     * https://docs.agora.io/en/Agora%20Platform/manage_projects?platform=All%20Platforms
     */

    /**
     * Real Time Call & Video Call Service
     */
    "rtc" => [
        'app_id' => env('AGORA_RTC_APP_ID', ""),
        'app_certificate' => env('AGORA_RTC_APP_CERTIFICATE', ""),
    ],

    /**
     * Real Time Messaging
     */
    "rtm" => [
        'app_id' => env('AGORA_RTM_APP_ID', ""),
        'app_certificate' => env('AGORA_RTM_APP_CERTIFICATE', ""),
    ],

    /**
     * Cloud recording needs RTC app_id in order to record your audio and/or video calls on your third party storage providers.
     * So please make sure if you only use recording service, RTC APP ID was not empty!
     * Login into your agora console and enable Cloud Recording Service.
     * We use customer ID and customer secret are used for RESTful API. (https://console.agora.io/restfulApi)
     */
    'cloud_recording' => [
        "customer_id" => config('AGORA_CR_CUSTOMER_ID', ""),
        "customer_certificate" => config('AGORA_CR_CUSTOMER_CERTIFICATE', ""),

        /**
         * Individual Recording - individual //In individual recording mode, the audio and video of each UID in a channel are recorded in separate files.
         * Composite Recording - mix // In composite recording mode, the audio and video of multiple UIDs in a channel are recorded in a single file.
         */
        "recording_mode" => 'mix',

        "recordingConfig" => [
            "maxIdleTime" => 30, // in seconds
            "streamTypes" => 0, // 0 - Audio Only, 1 - Video Only, 2 - Audio & Video(default)
            /**
             *  0: (Default) Sample rate of 48 kHz, music encoding, mono, and a bitrate of up to 48 Kbps.
             * 1: Sample rate of 48 kHz, music encoding, mono, and a bitrate of up to 128 Kbps.
             * 2: Sample rate of 48 kHz, music encoding, stereo, and a bitrate of up to 192 Kbps.
             */
            "audioProfile" => 1,
            "channelType" => 0, // 0 - (Default) Communication profile, 1 - Live broadcast profile
            "videoStreamType" => 1,
        ],
        "recordingFileConfig" => [
            /**
             * hls - (Default) M3U8 and TS files
             * mp4, hls - This value is for composite recording (mix) and web page recording (web) only and must be set together with "hls"; otherwise, the recording service returns error code 2
             */
            "avFileType" => ["hls"]
        ],
        /**
         * Vendor
         *  0: Qiniu Cloud
            1: Amazon S3
            2: Alibaba Cloud
            3: Tencent Cloud
            4: Kingsoft Cloud
         *  Region
         *      When the third-party cloud storage is Qiniu Cloud (vendor = 0):
                0: East China
                1: North China
                2: South China
                3: North America
                4: Southeast Asia
         *
         *      When the third-party cloud storage is Amazon S3 (vendor = 1):
         *      0: US_EAST_1
                1: US_EAST_2
                2: US_WEST_1
                3: US_WEST_2
                4: EU_WEST_1
                5: EU_WEST_2
                6: EU_WEST_3
                7: EU_CENTRAL_1
                8: AP_SOUTHEAST_1
                9: AP_SOUTHEAST_2
                10: AP_NORTHEAST_1
                11: AP_NORTHEAST_2
                12: SA_EAST_1
                13: CA_CENTRAL_1
                14: AP_SOUTH_1
                15: CN_NORTH_1
                16: CN_NORTHWEST_1
                17: US_GOV_WEST_1
         *
         *      When the third-party cloud storage is Alibaba Cloud (vendor = 2):
         *      0: CN_Hangzhou
                1: CN_Shanghai
                2: CN_Qingdao
                3: CN_Beijing
                4: CN_Zhangjiakou
                5: CN_Huhehaote
                6: CN_Shenzhen
                7: CN_Hongkong
                8: US_West_1
                9: US_East_1
                10: AP_Southeast_1
                11: AP_Southeast_2
                12: AP_Southeast_3
                13: AP_Southeast_5
                14: AP_Northeast_1
                15: AP_South_1
                16: EU_Central_1
                17: EU_West_1
                18: EU_East_1
         *
         *      When the third-party cloud storage is Tencent Cloud (vendor = 3):
         *      0：AP_Beijing_1
                1：AP_Beijing
                2：AP_Shanghai
                3：AP_Guangzhou
                4：AP_Chengdu
                5：AP_Chongqing
                6：AP_Shenzhen_FSI
                7：AP_Shanghai_FSI
                8：AP_Beijing_FSI
                9：AP_Hongkong
                10：AP_Singapore
                11：AP_Mumbai
                12：AP_Seoul
                13：AP_Bangkok
                14：AP_Tokyo
                15：NA_Siliconvalley
                16：NA_Ashburn
                17：NA_Toronto
                18：EU_Frankfurt
                19：EU_Moscow
         *
         *      When the third-party cloud storage is Kingsoft Cloud (vendor = 4):
         *      0：CN_Hangzhou
                1：CN_Shanghai
                2：CN_Qingdao
                3：CN_Beijing
                4：CN_Guangzhou
                5：CN_Hongkong
                6：JR_Beijing
                7：JR_Shanghai
                8：NA_Russia_1
                9：NA_Singapore_1
         *
         * bucket: String. The bucket of the third-party cloud storage.
         * accessKey: String. The access key of the third-party cloud storage. Agora suggests that you use a write-only access key.
         * secretKey: String. The secret key of the third-party cloud storage.
         * fileNamePrefix : Folder name - (20210530) Ymd
         */
        "storageConfig" => [
            "vendor" => 1,
            "region" => 14,
            "bucket" => "XXXXXXXXX",
            "accessKey" => "XXXXXXXXX",
            "secretKey" => "XXXXXXXXX",
            "fileNamePrefix" => [(string)Str::slug(date("Y-m-d"),""),]
        ],
        /**
         * Resource token expires in
         */
        "resourceExpiredHour" => 24, // in hours
    ]
];
```

## Usage

```php
//RealTime Audio Call && RealTime Video Call Token generation
$channel = 'NewTestChannel';
        $service = new LagoraRTC();
        $service
            ->setUserID(0)
            ->setMinutes(10)
            ->setSeconds(10)
            ->setChannelName($channel)
            ->setRole(1)
            ->getToken();
        $token = $service->token;
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Prabakaran T](https://github.com/webtamizhan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
