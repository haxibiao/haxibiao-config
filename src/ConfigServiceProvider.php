<?php

namespace Haxibiao\Config;

use Illuminate\Support\ServiceProvider;
use Haxibiao\Config\Console\InstallCommand;

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

    }
}
