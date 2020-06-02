<?php

use haxibiao\config\Seo;

function seo_value($group, $name)
{
    return Seo::getValue($group, $name);
}

function get_seo_meta()
{
    return Seo::getValue('百度', 'meta');

}

function get_seo_push()
{
    return Seo::getValue('百度', 'push');
}

function get_seo_tj()
{
    return Seo::getValue('统计', 'matomo');
}

function get_seo_title()
{
    return Seo::getValue('TKD', 'title');
}

function get_seo_keywords()
{
    return Seo::getValue('TKD', 'keywords');
}

function get_seo_description()
{
    return Seo::getValue('TKD', 'description');
}
