<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class RandQuestionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return  'RandQuestionService';
    }
}