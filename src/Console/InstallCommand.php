<?php

namespace Haxibiao\Config\Console;

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
        $this->copyStubs();
    }

    public function copyStubs()
    {
        //复制所有app stubs
        foreach (glob(__DIR__ . '/stubs/*.stub') as $filepath) {
            $filename = basename($filepath);
            copy($filepath, app_path(str_replace(".stub", ".php", $filename)));
        }

        //复制所有nova stubs
        if (!is_dir(app_path('Nova'))) {
            mkdir(app_path('Nova'));
        }
        foreach (glob(__DIR__ . '/stubs/Nova/*.stub') as $filepath) {
            $filename = basename($filepath);
            copy($filepath, app_path('Nova/' . str_replace(".stub", ".php", $filename)));
        }
    }
}
