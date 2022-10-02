<?php

declare(strict_types=1);

namespace Karacweb\InfomaniakNewsletter;

class Facade extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return 'infomaniak-newsletter';
    }
}
