<?php


namespace Webtamizhan\Lagora\Exceptions;

use Exception;

class RoleNotFoundException extends Exception
{
    public static function roleNotFound(int $role)
    {
        return new static("Given role {$role} was not found!");
    }
}
