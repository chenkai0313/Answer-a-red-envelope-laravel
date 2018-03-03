<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class PackRecordFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return  'PackRecordService';
    }
}