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

        $this->app->bind(Contracts\SEOFriendly::class, 'haxibiao-config.friendlyurl');

        // Register Commands
        $this->commands([
            InstallCommand::class,
            EnvRefresh::class,
            SetEnv::class,
        ]);

        $this->bindPathsInContainer();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //命令行模式
        if ($this->app->runningInConsole()) {
            // 发布 Nova

            //注册 migrations paths
            $this->loadMigrationsFrom($this->app->make('path.haxibiao-config.migrations'));

        }

        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/seo.php', 'seo');
        }

        $this->app->singleton('app.config.beian', function ($app) {
            return \App\AppConfig::where([
                'group' => 'record',
                'name'  => 'web',
            ])->first();
        });

        $this->app->singleton('asos', function ($app) {
            return \App\Aso::all();
        });
        $this->app->singleton('seos', function ($app) {
            return \App\Seo::all();
        });
    }

    /**
     * Bind paths in container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        foreach ([
            'path.haxibiao-config'            => $root = dirname(__DIR__),
            'path.haxibiao-config.config'     => $root . '/config',
            'path.haxibiao-config.graphql'    => $root . '/graphql',
            'path.haxibiao-config.database'   => $database = $root . '/database',
            'path.haxibiao-config.migrations' => $database . '/migrations',
            'path.haxibiao-config.seeds'      => $database . '/seeds',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }
}
