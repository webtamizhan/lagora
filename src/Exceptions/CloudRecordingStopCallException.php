<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class CloudRecordingStopCallException extends Exception
{
    public static function exception($message)
    {
        return new static("Unable to stop call, exception: ".$message);
    }
}
