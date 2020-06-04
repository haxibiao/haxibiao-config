<?php

use haxibiao\config\Seo;

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
    return \App\Seo::getValue('TKD', 'title');
}

function get_seo_keywords()
{
    return \App\Seo::getValue('TKD', 'keywords');
}

function get_seo_description()
{
    return \App\Seo::getValue('TKD', 'description');
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
    return \App\Seo::getValue('百度', 'meta');
}
