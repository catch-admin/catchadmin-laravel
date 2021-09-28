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

declare(strict_types=1);

namespace Catcher\Support\Generate\Create;

use Catcher\CatchAdmin;
use Catcher\Exceptions\FailedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use JaguarJack\MigrateGenerator\MigrateGenerator;

class CreateMigration extends Creator
{
    /**
     *
     * @time 2021年07月29日
     * @param string $name
     * @return string
     */
    public function generate(string $name): string
    {
        // TODO: Implement generate() method.

        $migrator = new MigrateGenerator('laravel');

        $filename = date('Y_m_d_H_is', time()) . '_create_'.$name.'_table';
        $migrationFile = CatchAdmin::getModuleMigrationPath($this->module) . $filename . '.php';

        if (!File::put($migrationFile, $migrator->getMigrationContent($name))) {
            throw new FailedException('create migration file failed');
        }

        $migrationModel = new class extends Model {
            protected $table = 'migration';
        };

        $migrationModel::query()->insert([
            'migration' => $filename,

            'batch' => time()
        ]);

        return $migrationFile;

    }
}
