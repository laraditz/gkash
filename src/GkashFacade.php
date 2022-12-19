<?php

namespace Laraditz\Gkash;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laraditz\Gkash\Skeleton\SkeletonClass
 */
class GkashFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gkash';
    }
}
