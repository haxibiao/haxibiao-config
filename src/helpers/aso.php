<?php

use Haxibiao\Config\AppConfig;
use Haxibiao\Config\Aso;
use Haxibiao\Config\Config;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

function adIsOpened()
{

    $os     = request()->header('os', 'android');
    $config = AppConfig::where([
        'group' => $os,
        'name'  => 'ad',
    ])->first();
    //如果使用的是版本开关
    if ($config && isset($config->app_version)) {
        $user          = getUser(false);
        $userVersion   = $user && $user->profile->app_version ? $user->profile->app_version : $config->app_version;
        $config->state = $config->isOpen($userVersion) == 'on' ? AppConfig::STATUS_ON : AppConfig::STATUS_OFF;

    }
    if ($config && $config->state === AppConfig::STATUS_OFF) {
        return false;
    } else {
        return true;
    }
}

function aso_value($group, $name)
{
    if ($asos = app('asos')) {
        foreach ($asos as $aso) {
            if ($aso->group == $group) {
                if ($aso->name == $name) {
                    return $aso->value;
                }
            }
        }
    }
    return Aso::getValue($group, $name);
}

function getDownloadUrl()
{
    $apkUrl = aso_value('下载页', '安卓地址');
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
        $config = app('app.config.beian');
        //兼容答赚web?
        if ($config === null) {
            return true;
        }
        if ($config->state === \App\AppConfig::STATUS_ON) {
            return true;
        }
    }
    return false;
}

function small_logo()
{
    return url('/logo/' . get_domain() . '.small.png');
}

/**
 * 去下载APP的qrcode图片地址(自动生成)
 */
function app_qrcode_url()
{
    $qrcode_path      = "/storage/qrcode." . get_domain() . ".jpg";
    $qrcode_full_path = public_path($qrcode_path);
    //缓存的二维码图片
    if (file_exists($qrcode_full_path)) {
        return url($qrcode_path);
    }

    //包含PC扫码场景，先打开app下载页
    $appDownloadPageUrl = url('/app');
    if (array_key_exists(get_domain(), neihan_sites_domains())) {
        $small_logo_path = seo_small_logo();
    } else {
        $small_logo_path = small_logo();
    }

    //中心带上small logo
    $qrcode = QrCode::format('png')->size(250)->encoding('UTF-8');
    if (file_exists($small_logo_path)) {
        $qrcode->merge($small_logo_path, .1, true);
    }

    try {
        @file_put_contents($qrcode_full_path, $qrcode->generate($appDownloadPageUrl));
    } catch (Exception $ex) {}
    return url($qrcode_path);

}

function qrcode_url()
{
    if (class_exists("Haxibiao\\Config\\Aso", true)) {
        $apkUrl = aso_value('下载页', '安卓地址');
        if (array_key_exists(get_domain(), neihan_sites_domains())) {
            $logo = seo_small_logo();
        } else {
            $logo = small_logo();
        }
        $qrcode = QrCode::format('png')->size(250)->encoding('UTF-8');
        if (!empty(env('COS_DOMAIN'))) {
            if (@file_get_contents($logo)) {
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
