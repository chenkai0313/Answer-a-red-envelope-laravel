<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class MerchantsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MerchantsService';
    }
}