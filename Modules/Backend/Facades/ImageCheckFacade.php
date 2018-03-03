<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class ImageCheckFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ImageCheckService';
    }
}