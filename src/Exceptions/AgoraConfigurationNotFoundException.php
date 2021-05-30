<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class AgoraConfigurationNotFoundException extends Exception
{
    public static function rtcNotConfigured()
    {
        return new static("Missing Agora APP_ID and/or APP_CERTIFICATE, please check on config/lagora.php!");
    }
}
