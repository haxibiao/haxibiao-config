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
    protected $signature = 'config:install {--force}';

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
        $this->info('安装 haxibiao-config ...');
        $force = $this->option('force');

        $this->comment('复制 stubs ...');
        copyStubs(__DIR__, $force);

        $this->comment('迁移数据库变化...');
        $this->call('migrate');

    }
}
