# haxibiao/config

> haxibiao/config 是配置管理库（含广告，APP 功能开关，ASO，SEO，CMS 等配置）, 主要在后台`配置管理`模块

## 导语

1. Config - 通用系统配置 laravel config 和 .env 之外配置到数据库的部分，支持运营后台修改
2. AppConfig - APP 功能配置和开关，比如钱包，广告等 (分组可以支持针对版本，系统，应用市场)
3. AdConfig - 广告配置，主要是穿山甲，优量汇等代码位
4. Aso - Aso 信息，最新版本网页下载图文介绍
5. Version - APP 版本信息说明和强更新配置
6. Seo - Seo 信息，关键词，开启 cms 站群模式后，支持多域名，不同 TKD，统计代码等..

## 环境要求

1. haxibiao/console
2. haxibiao/helpers

## 安装步骤

1. `composer.json`改动如下：
   在`repositories`中添加 vcs 类型远程仓库指向
   `http://code.haxibiao.cn/packages/haxibiao-config`
2. 执行`composer require haxibiao/config`
3. 安装和更新，需要执行`php artisan config:install`
4. 完成

### 如何完成更新？

> 远程仓库的 composer package 发生更新时如何进行更新操作呢？

1. 执行`composer update haxibiao/config`

## GQL 接口说明

## Api 接口说明
