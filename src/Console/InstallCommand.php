<?php

namespace haxibiao\config;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

class InstallCommand extends Command
{

    /**
     * The name and signature of the Console command.
     *
     * @var string
     */
    protected $signature = 'config:install';

    /**
     * The Console command description.
     *
     * @var string
     */
    protected $description = '安装 haxibiao/config';

    /**
     * Execute the Console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('config app.php 添加 Service Provider...');
        $this->registerHelperServiceProvider();
    }

    /**
     * Register the Helper service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerHelperServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class," . PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class," . PHP_EOL . "        haxibiao\helpers\ConfigServiceProvider::class," . PHP_EOL,
            file_get_contents(config_path('app.php'))
        ));
    }

    protected function getAppNamespace()
    {
        return Container::getInstance()->getNamespace();
    }
}
