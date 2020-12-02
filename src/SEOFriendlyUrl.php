<?php

namespace Haxibiao\Config;
use Haxibiao\Config\Contracts\SEOFriendly as SEOFriendlyUrlContract;
use Illuminate\Support\Arr;
use Illuminate\Config\Repository as Config;

class SEOFriendlyUrl implements SEOFriendlyUrlContract
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function generate()
    {
        $domain = get_domain();
        $frienlyUrls = Arr::get($this->config,$domain,false);
        if(!$frienlyUrls){
            return null;
        }
        $html = [];
        $html [] = "<div id=\"link\">";
        $html [] = "<h7>友情链接</h7>";
        foreach ($frienlyUrls as $url){
            $href  = data_get($url,'url');
            $title = data_get($url,'title');
            $html [] = "<a target=\"_blank\" href=\"{$href}\" title=\"{$title}\">{$title}</a>";
        }
        $html [] = "</div>";
        return implode(PHP_EOL, $html);
    }
}
