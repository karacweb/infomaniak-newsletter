<?php

namespace Karacweb\InfomaniakNewsletter;

class Facade extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return 'infomaniak-newsletter';
    }
}