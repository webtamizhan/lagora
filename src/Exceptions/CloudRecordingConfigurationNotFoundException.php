<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class CloudRecordingConfigurationNotFoundException extends Exception
{
    public static function notFound()
    {
        return new static("Missing Customer ID and/or Customer Secret!");
    }
}
