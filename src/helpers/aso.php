<?php

use Haxibiao\Config\AppConfig;
use Haxibiao\Config\Aso;
use Haxibiao\Config\Config;

function adIsOpened()
{

    $os     = request()->header('os', 'android');
    $config = AppConfig::where([
        'group' => $os,
        'name'  => 'ad',
    ])->first();
    if ($config && $config->state === AppConfig::STATUS_OFF) {
        return false;
    } else {
        return true;
    }
}

function aso_value($group, $name)
{

    return Aso::getValue($group, $name);
}

function getDownloadUrl()
{

    $apkUrl = Aso::getValue('下载页', '安卓地址');
    if (is_null($apkUrl) || empty($apkUrl)) {
        return null;
    }
    return $apkUrl;
}

function douyinOpen()
{

    $config = Config::where([
        'name' => 'douyin',
    ])->first();
    if ($config && $config->value === Config::CONFIG_OFF) {
        return false;
    } else {
        return true;
    }

}

/*****************************
 * *****答赚web网页兼容*********
 * ***************************
 */
//是否处于备案状态
function isRecording()
{
    if (class_exists("App\\AppConfig", true)) {

        $config = \App\AppConfig::where([
            'group' => 'record',
            'name'  => 'web',
        ])->first();
        if ($config === null) {
            return true;
        }
        if ($config->state === \App\AppConfig::STATUS_ON) {
            return true;
        }

        return false;
    }
}

function small_logo()
{
    if (class_exists("Haxibiao\\Config\\Aso", true)) {
        $logo = Aso::getValue('下载页', 'logo');

        if (empty($logo)) {
            return '/logo/' . env('APP_DOMAIN') . '.small.png';
        } else {
            return $logo;
        }
    }
}

function qrcode_url()
{
    if (class_exists("Haxibiao\\Config\\Aso", true)) {
        $apkUrl = Aso::getValue('下载页', '安卓地址');
        $logo   = small_logo();
        $qrcode = SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(250)->encoding('UTF-8');
        if(!empty(env('COS_DOMAIN'))) {
            if (str_contains($logo, env('COS_DOMAIN'))) {
                $qrcode->merge($logo, .1, true);
            } else {
                if (file_exists(public_path($logo))) {
                    $qrcode->merge(public_path($logo), .1, true);
                }
            }
        }
        

        $qrcode = $qrcode->generate($apkUrl);

        $path = base64_encode($qrcode);

        return $path;
    }
}
