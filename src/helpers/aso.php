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
    if(array_key_exists(get_domain(),neihan_sites_domains())){
        $small_logo_path    = seo_small_logo();
    }else{
        $small_logo_path    = small_logo();
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
        $apkUrl = Aso::getValue('下载页', '安卓地址');
        if(array_key_exists(get_domain(),neihan_sites_domains())){
            $logo    = seo_small_logo();
        }else{
            $logo   = small_logo();
        }    
        $qrcode = QrCode::format('png')->size(250)->encoding('UTF-8');
        if (!empty(env('COS_DOMAIN'))) {
            if (accessOK($logo)) {
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
