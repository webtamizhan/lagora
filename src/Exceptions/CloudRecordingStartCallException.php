<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class CloudRecordingStartCallException extends Exception
{
    public static function exception($message)
    {
        return new static("Unable to start call, exception: ".$message);
    }
}
