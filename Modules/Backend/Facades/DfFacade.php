<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class DfFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DfService';
    }
}