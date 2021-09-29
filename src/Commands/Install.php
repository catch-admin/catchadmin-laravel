<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare (strict_types = 1);

namespace Catcher\Commands;

use Catcher\CatchAdmin;
use Catcher\Exceptions\FailedException;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class Install extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:install {--root=catch}';

    protected $moduleRoot;

    protected $moduleRootNamespace = 'CatchAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catchadmin install';

    protected $extensions = ['bcmath', 'ctype', 'fileinfo', 'json', 'mbstring', 'openssl', 'pdo', 'tokenizer', 'xml'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        // 获取 module root
        $this->moduleRoot = $this->option('root');

        try {
            // 检查环境
            $this->detectEnvironment();

            // 发布配置
            $this->publishConfig();

            // 下载默认模块
            $this->downloadDefaultModules();

            // 创建数据库 以及表
            $this->createDatabase();

            // 输出项目信息
            $this->project();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 检测环境
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @throws \Exception
     * @return void
     */
    protected function detectEnvironment()
    {
        $this->info('Begin check environment...');

        if (version_compare(PHP_VERSION, '7.3.0', '<')) {
            throw new \Exception('PHP version should be >= 7.3.0');
        }

        $this->info('PHP version: '. PHP_VERSION);

        foreach ($this->extensions as $extension) {
            if (! extension_loaded($extension)) {
                throw new \Exception(sprintf('Extension %s Has not installed', $extension));
            } else {
                $this->info(sprintf('Extension %s is installed', $extension));
            }
        }

        $this->copyEnvFile();

        $this->info('🎉 environment checking finished');
    }


    /**
     * copy .env
     *
     * @time 2021年09月29日
     * @return void
     */
    protected function copyEnvFile()
    {
        if (! File::exists(app()->environmentFilePath())) {
            copy(app()->environmentFilePath() . '.example', app()->environmentFilePath());
        }

        if (! File::exists(app()->environmentFilePath())) {
            $this->error('create 【.env】file failed, Please try again or you can create it by yourself');
            exit(0);
        }

        File::put(app()->environmentFile(), implode("\n", explode("\n", $this->getEnvFileContent())) . $this->defaultEnvConfig());

    }

    /**
     * create database
     *
     * @time 2021年09月16日
     * @return void
     */
    protected function createDatabase()
    {
        $appUrl = $this->ask('Please Input App Url');

        if ($appUrl && ! Str::contains($appUrl, 'http://') && ! Str::contains($appUrl, 'https://')) {
            $appUrl = 'http://' . $appUrl;
        }

        $databaseName = $this->ask('Please Input Database Name');

        $dbHost = $this->ask('Please Input DB Host', '127.0.0.1');

        $dbPort = $this->ask('Please Input DB Port', 3306);

        $dbUsername = $this->ask('Please Input DB UserName', 'root');

        $dbPassword = $this->ask('Please Input DB Password');

        if (! $dbPassword) {
            $dbPassword = $this->ask('Are You Sure The DB Password Is Null?');
        }

        // set env
        $env = explode("\n", $this->getEnvFileContent());

        foreach ($env as &$value) {
            if (Str::contains($value, 'APP_URL')) {
                $value = $this->resetEnvValue($value, $appUrl);
            }

            if (Str::contains($value, 'DB_HOST')) {
                $value = $this->resetEnvValue($value, $dbHost);
            }

            if (Str::contains($value, 'DB_PORT')) {
                $value = $this->resetEnvValue($value, $dbPort);
            }

            if (Str::contains($value, 'DB_DATABASE')) {
                $value = $this->resetEnvValue($value, $databaseName);
            }

            if (Str::contains($value, 'DB_USERNAME')) {
                $value = $this->resetEnvValue($value, $dbUsername);
            }

            if (Str::contains($value, 'DB_PASSWORD')) {
                $value = $this->resetEnvValue($value, $dbPassword);
            }
        }

        File::put(app()->environmentFile(), implode("\n", $env) . $this->defaultEnvConfig());

        app()->bootstrapWith([
            LoadEnvironmentVariables::class,
            LoadConfiguration::class
        ]);

        $this->info("Creating Database $databaseName");

        $databaseConfig = app('config')->get('database.connections.'.config('database.default'));

        $databaseConfig['database'] = null;

        $pdo = (new ConnectionFactory(app()))->createConnector($databaseConfig)->connect($databaseConfig);

        $pdo->query("CREATE DATABASE IF NOT EXISTS `$databaseName` DEFAULT CHARSET {$databaseConfig['charset']} COLLATE {$databaseConfig['collation']}");

        $this->info("Create Database $databaseName successful");

        $this->migrate();
    }

    /**
     * default config
     *
     * @time 2021年09月16日
     * @return string
     */
    protected function defaultEnvConfig(): string
    {
        $envContent = $this->getEnvFileContent();

        $env = '';

        foreach ([
             'CATCH_ROOT' => $this->moduleRoot,
             'CATCH_NAMESPACE' => $this->moduleRootNamespace,
             'CATCH_GUARD' => 'catch_admin',
             'CATCH_AUTH_MIDDLEWARE_ALIAS' => 'catch.auth'
        ] as $k => $value) {
            if (! Str::contains($envContent, $k)) {
                $env .= sprintf('%s%s=%s', "\n", $k, $value);
            }
        }

        return $env;
    }


    /**
     * migration
     *
     * @time 2021年09月16日
     * @return void
     */
    protected function migrate()
    {
        Artisan::call('catch:migrate', ['module' => 'Permissions']);

        Artisan::call('catch:db:seed', ['module' => 'Permissions']);

        // 模块缓存
        Artisan::call('catch:cache:modules');

        // key:generate
        Artisan::call('key:generate');
    }

    /**
     * reset env config
     *
     * @time 2021年09月16日
     * @param $value
     * @param $new
     * @return string
     */
    protected function resetEnvValue($value, $new): string
    {
        if (! $new) {
            return $value;
        }

        $value = explode('=', $value);

        $value[1] = $new;

        return implode('=', $value);
    }

    /**
     * publish config
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return void
     */
    protected function publishConfig()
    {
        Artisan::call('jwt:secret',['--force' => true]);

        Artisan::call('vendor:publish', [
            '--provider' => 'Tymon\JWTAuth\Providers\LaravelServiceProvider',
            '--force' => true
        ]);

        Artisan::call('vendor:publish', [
            '--provider' => 'Maatwebsite\Excel\ExcelServiceProvider',
            '--tag' => 'config',
            '--force' => true
        ]);

        Artisan::call('vendor:publish', [
            '--tag' => 'catch-config',
        ]);

        app()->bootstrapWith([
            LoadEnvironmentVariables::class,
            LoadConfiguration::class
        ]);
    }


    public function project()
    {
        $this->info('🎉 CatchAdmin is installed, welcome!');

        $this->info(sprintf('
 /-------------------- welcome to use -------------------------\
|               __       __       ___       __          _      |
|   _________ _/ /______/ /_     /   | ____/ /___ ___  (_)___  |
|  / ___/ __ `/ __/ ___/ __ \   / /| |/ __  / __ `__ \/ / __ \ |
| / /__/ /_/ / /_/ /__/ / / /  / ___ / /_/ / / / / / / / / / / |
| \___/\__,_/\__/\___/_/ /_/  /_/  |_\__,_/_/ /_/ /_/_/_/ /_/  |
|                                                              |
 \ __ __ __ __ _ __ _ __ enjoy it ! _ __ __ __ __ __ __ ___ _ @ 2017 ～ %s
 版本: %s
 初始账号: catch@admin.com
 初始密码: catchadmin
', date('Y'), CatchAdmin::VERSION));
    }

    /**
     * clone default modules
     *
     * @time 2021年09月17日
     * @return void
     */
    protected function downloadDefaultModules()
    {
        try {
            if (File::isDirectory(base_path($this->moduleRoot))) {
                $this->addModuleNamespace();
                return;
            }

            $this->exec(['git', 'clone', 'https://github.com/JaguarJack/catch-laravel-modules.git', $this->moduleRoot]);

            if (! File::isDirectory(base_path($this->moduleRoot))) {
                throw new FailedException('Download Catch-Module Failed, You sholud use [git clone https://github.com/JaguarJack/catch-laravel-modules.git catch] first, Then install!');
            }

            $this->addModuleNamespace();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            exit(0);
        }
    }


    /**
     * add module namespace
     *
     * @time 2021年09月17日
     * @return void
     */
    protected function addModuleNamespace()
    {
        $composerFile = base_path() . DIRECTORY_SEPARATOR . 'composer.json';

        $composerJson = \json_decode(File::get($composerFile), true);

        $composerJson['autoload']['psr-4'][$this->moduleRootNamespace . '\\'] = $this->moduleRoot . '/';

        // close platform check
        $composerJson['config']['platform-check'] = false;

        File::put($composerFile, \json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

        $this->exec(['composer', 'dump-autoload']);
    }

    /**
     * exec shell
     *
     * @time 2021年09月17日
     * @param array $command
     * @return void
     */
    protected function exec(array $command)
    {
        $process = new Process($command);

        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->info($buffer);
        });

        $process->stop();
    }

    /**
     * get env file content
     *
     * @time 2021年09月17日
     * @return string
     */
    protected function getEnvFileContent(): string
    {
        return File::get(app()->environmentFile());
    }
}
