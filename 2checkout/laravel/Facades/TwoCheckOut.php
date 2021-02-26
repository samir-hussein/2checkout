<?php

namespace TwoCheckOut\Facades;

use Illuminate\Support\Facades\Facade;

class TwoCheckOut extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TwoCheckout';
    }
}
