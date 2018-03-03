<?php

namespace Modules\Backend\Facades;

use Illuminate\Support\Facades\Facade;


class NotesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'NotesService';
    }
}