<?php

namespace Webtamizhan\Lagora;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Webtamizhan\Lagora\Lagora
 */
class LagoraFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lagora';
    }
}
