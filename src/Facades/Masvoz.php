<?php

namespace Holaluz\Masvoz\Facades;

use Illuminate\Support\Facades\Facade;

class Masvoz extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'masvoz';
    }
}
