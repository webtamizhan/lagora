<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class CloudRecordingAcquireException extends Exception
{
    public static function acquireException($message): CloudRecordingAcquireException
    {
        return new static("Acquire Call Exception: ".$message);
    }
}
