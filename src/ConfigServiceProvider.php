<?php

namespace Haxibiao\Config;

use Illuminate\Support\ServiceProvider;
use Haxibiao\Config\Console\InstallCommand;
use Illuminate\Config\Repository as Config;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $src_path = __DIR__;
        foreach (glob($src_path . '/helpers/*.php') as $filename) {
            require_once $filename;
        }

        $this->app->singleton('haxibiao-config.friendlyurl', function ($app) {
            return new SEOFriendlyUrl(new Config($app['config']->get('seo.frienly_urls', [])));
        });
        $this->app->bind(Contracts\SEOFriendly::class, 'haxibiao-config.friendlyurl');

        // Register Commands
        $this->commands([
            InstallCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/seo.php', 'seo');
        }
    }
}
