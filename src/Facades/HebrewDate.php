<?php

namespace StaffCollab\HebrewDate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StaffCollab\HebrewDate\HebrewDate
 */
class HebrewDate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \StaffCollab\HebrewDate\HebrewDate::class;
    }
}
