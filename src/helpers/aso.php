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
    if ($config && AppConfig::STATUS_OFF === $config->state) {
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
    if ($config && Config::CONFIG_OFF === $config->value) {
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
        // //兼容答赚web默认开启备案检查模式?
        // if ($config === null) {
        //     return true;
        // }
        if ($config && \App\AppConfig::STATUS_ON === $config->state) {
            return true;
        }
    }
    return false;
}

function touch_logo()
{
    $logo = str_replace('.small.', '.touch.', small_logo());
    if (file_exists(public_path($logo))) {
        return $logo;
    }
    return small_logo();
}

/**
 * 网站默认logo
 */
function web_logo()
{
    //兼容默认logo
    $logo_path = '/logo/' . get_domain() . '.png';
    if (file_exists(public_path($logo_path))) {
        return url($logo_path);
    }
    //有裁剪的情况
    $logo_path = '/logo/' . get_domain() . '.web.png';
    if (file_exists(public_path($logo_path))) {
        return url($logo_path);
    }
    //breeze默认logo
    return url("/vendor/breeze/images/logo/default.small.png");
}

/**
 * 注册登录场景用的文字logo
 */
function text_logo()
{
    $logo = str_replace('.small.', '.text.', small_logo());
    if (file_exists(public_path($logo))) {
        return $logo;
    }
    return small_logo();
}

/**
 * 小尺寸logo,大部分场景得到logo,尺寸60*60
 */
function small_logo()
{
    $logo = str_replace('.web.', '.text.', web_logo());
    if (file_exists(public_path($logo))) {
        return $logo;
    }
    return web_logo();
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
        return seo_url($qrcode_path);
    }

    //包含PC扫码场景，先打开app下载页
    $appDownloadPageUrl = seo_url('/app');
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
    return seo_url($qrcode_path);

}

/**
 * @deprecated 返回的是base64 data到页面的，建议用app_qrcode_url返回图片地址
 */
function qrcode_url()
{
    if (class_exists("Haxibiao\\Config\\Aso", true)) {
        $apkUrl = aso_value('下载页', '安卓地址');
        if (array_key_exists(get_domain(), neihan_sites_domains())) {
            $logo = seo_small_logo();
        } else {
            $logo = small_logo();
        }

        if (class_exists("SimpleSoftwareIO\QrCode\Facades\QrCode")) {
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

            if (!empty($apkUrl)) {
                $qrcode = $qrcode->generate($apkUrl);
                $data   = base64_encode($qrcode);
                return $data;
            }
        }

        return null;
    }
}
