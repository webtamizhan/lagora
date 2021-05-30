<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class InvalidChannelNameException extends Exception
{
    public static function invalidChannelName($channel = '')
    {
        return new static("Given channel {$channel} was invalid or empty!");
    }
}
