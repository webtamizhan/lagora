<?php


namespace Webtamizhan\Lagora\Exceptions;


use Exception;

class CloudRecordingBucketConfigurationNotFoundException extends Exception
{
    public static function notFound()
    {
        return new static("Missing the Bucket configuration!");
    }
}
