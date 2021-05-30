<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    public static function invalidToken($channel = '')
    {
        return new static("Given token was invalid or empty!");
    }
}
