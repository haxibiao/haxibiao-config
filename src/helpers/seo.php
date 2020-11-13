<?php

use Haxibiao\Config\Seo;

function seo_small_logo()
{
    return url('/logo/' . get_domain() . '.small.png');
}

function seo_site_name()
{
    $sites_name_map = neihan_sites_domains();
    if ($name = $sites_name_map[get_domain()] ?? null) {
        return $name;
    }

    return '内涵电影';
}

/**
 * 所有内涵矩阵站点域名
 */
function neihan_sites_domains()
{
    return [
        "neihanxinwen.com"     => "内涵新闻",
        "neihanxiaoshipin.com" => "内涵小视频",
        "neihanduanshipin.com" => "内涵短视频",

        "jingdianmeiju.com"    => "经典美剧",
        "jingdianriju.com"     => "经典日剧",
        "jindianhanju.com"     => "经典韩剧",
        "jingdiangangju.com"   => "经典港剧",
        "jingdianyueyu.com"    => "经典粤语",

        "huaijiumeiju.com"     => "怀旧美剧",
        "huaijiuriju.com"      => "怀旧日剧",
        "huaijiuhanju.com"     => "怀旧韩剧",
        "huaijiugangju.com"    => "怀旧港剧",
        "huaijiuyueyu.com"     => "怀旧粤语",

        "fengkuangmeiju.com"   => "疯狂美剧",
        "fengkuangriju.com"    => "疯狂日剧",
        "fengkuanghanju.com"   => "疯狂韩剧",
        "fengkuanggangju.com"  => "疯狂港剧",

        "zaixianmeiju.com"     => "在线美剧",
        "zaixianriju.com"      => "在线日剧",
        "zaixianhanju.com"     => "在线韩剧",
        "zaixiangangju.com"    => "在线港剧",

        "neihanmeiju.com"      => "内涵美剧",
        "neihanriju.com"       => "内涵日剧",
        "neihanhanju.com"      => "内涵韩剧",
        "neihangangju.com"     => "内涵港剧",

        "aishanghanju.com"     => "爱上韩剧",
        "aishangriju.com"      => "爱上日剧",
        "aishanggangju.com"    => "爱上港剧",
        "aishangyueyu.com"     => "爱上粤语",

        "laoyueyu.com"         => "老粤语",
    ];

}

function seo_value($group, $name)
{
    return Seo::getValue($group, $name);
}

function get_seo_push()
{
    return Seo::getValue('百度', 'push');
}

function get_seo_tj()
{
    return Seo::getValue('统计', 'matomo');
}

/*****************************
 * *****答赚web网页兼容*********
 * ***************************
 */
function get_seo_title()
{
    return Haxibiao\Config\Seo::getValue('TKD', 'title');
}

function get_seo_keywords()
{
    return Haxibiao\Config\Seo::getValue('TKD', 'keywords');
}

function get_seo_description()
{
    return Haxibiao\Config\Seo::getValue('TKD', 'description');
}

function get_seo_meta()
{
    // $meta = '';
    // if (Storage::exists("seo_config")) {
    //     $json   = Storage::get('seo_config');
    //     $config = json_decode($json);
    //     $meta   = $config->seo_meta;
    // }
    // return $meta;
    return Haxibiao\Config\Seo::getValue('百度', 'meta');
}
