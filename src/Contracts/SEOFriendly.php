<?php


namespace Haxibiao\Config\Contracts;


use Illuminate\Config\Repository as Config;

interface SEOFriendly
{
    public function __construct(Config $config);

    public function generate();
}
