<?php

namespace JeffersonGoncalves\ConvertKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\ConvertKit\ConvertKit
 */
class ConvertKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\ConvertKit\ConvertKit::class;
    }
}
