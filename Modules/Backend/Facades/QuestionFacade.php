<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class QuestionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'QuestionService';
    }
}