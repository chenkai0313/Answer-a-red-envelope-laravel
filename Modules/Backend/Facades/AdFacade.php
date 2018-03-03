<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class AdFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdService';
    }
}