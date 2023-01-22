<?php

namespace Joy\VoyagerCore\Facades;

use Illuminate\Support\Facades\Facade;

class Voyager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'joy-voyager';
    }
}
