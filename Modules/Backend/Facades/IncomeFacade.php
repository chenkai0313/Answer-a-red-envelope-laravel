<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class IncomeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return  'IncomeService';
    }
}