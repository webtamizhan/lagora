<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class CloudRecordingQueryCallStatusException extends Exception
{
    public static function exception($message)
    {
        return new static("Unable to query call status, exception: ".$message);
    }
}
