<?php

namespace Haxibiao\Config;

use Illuminate\Console\Command;
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

        $this->comment('复制 stubs ...');
        copy($this->resolveStubPath('/stubs/AdConfig.stub'), app_path('AdConfig.php'));
        copy($this->resolveStubPath('/stubs/AppConfig.stub'), app_path('AppConfig.php'));
        copy($this->resolveStubPath('/stubs/Aso.stub'), app_path('Aso.php'));
        copy($this->resolveStubPath('/stubs/Config.stub'), app_path('Config.php'));
        copy($this->resolveStubPath('/stubs/Seo.stub'), app_path('Seo.php'));
        copy($this->resolveStubPath('/stubs/Version.stub'), app_path('Version.php'));

    }

    protected function resolveStubPath($stub)
    {
        return __DIR__ . $stub;
    }

}
