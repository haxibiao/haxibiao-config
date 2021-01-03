<?php

namespace Haxibiao\Config;

use Haxibiao\Config\Console\EnvRefresh;
use Haxibiao\Config\Console\InstallCommand;
use Haxibiao\Config\Console\SetEnv;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //帮助函数
        $src_path = __DIR__;
        foreach (glob($src_path . '/helpers/*.php') as $filename) {
            require_once $filename;
        }

        $this->app->singleton('haxibiao-config.friendlyurl', function ($app) {
            return new SEOFriendlyUrl(new Config($app['config']->get('seo.frienly_urls', [])));
        });

        $this->app->singleton('app.config.beian', function ($app) {
            return \App\AppConfig::where([
                'group' => 'record',
                'name'  => 'web',
            ])->first();
        });

        $this->app->bind(Contracts\SEOFriendly::class, 'haxibiao-config.friendlyurl');

        // Register Commands
        $this->commands([
            InstallCommand::class,
            EnvRefresh::class,
            SetEnv::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/seo.php', 'seo');
        }
    }
}
