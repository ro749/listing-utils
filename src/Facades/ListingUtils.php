<?php

namespace Ro749\ListingUtils\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ro749\ListingUtils\ListingUtils
 */
class ListingUtils extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ro749\ListingUtils\ListingUtils::class;
    }
}
