<?php


namespace Haxibiao\Config\Facades;


use Illuminate\Support\Facades\Facade;

class SEOFriendlyUrl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'haxibiao-config.friendlyurl';
    }
}
