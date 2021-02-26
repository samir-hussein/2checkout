<?php

namespace TwoCheckOut\Facades;

use Illuminate\Support\Facades\Facade;

class TwoCheckout extends Facade
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
