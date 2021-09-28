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


use Catcher\Exceptions\FailedException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateSchema extends Creator
{
    /**
     * generate
     *
     * @time 2021年07月26日
     * @param string $schemaName
     * @return bool
     */
    public function generate(string $schemaName): bool
    {
        if (! $schemaName) {
            throw new FailedException('Should set schema name');
        }

        if (Schema::hasTable($schemaName)) {
            throw new FailedException(sprintf('Table [%s] has been existed', $schemaName));
        }

        Schema::create($schemaName, function (Blueprint $table) {
            $table->engine = $this->params['engine'];

            // 添加 columns
            $columns = $this->params['columns'];
            $table->id($this->params['primary_key']);

            foreach ($columns as $column) {

                if (in_array($column['type'], ['enum', 'set'])) {
                    $schemaColumn = $table->{$column['type']}($column['field'], explode(',', $column['default']));
                } elseif (in_array($column['type'], ['decimal', 'float'])) {
                    $schemaColumn = $table->{$column['type']}($column['field'], 8, 2);
                } elseif (in_array($column['type'], ['char', 'string'])) {
                    $schemaColumn = $table->{$column['type']}($column['field'], $column['length']);
                } else {
                    $schemaColumn = $table->{$column['type']}($column['field']);
                }

                if ($column['nullable']) {
                    $schemaColumn = $schemaColumn->nullable(true);
                }

                if ($column['unsigned']) {
                    $schemaColumn = $schemaColumn->unsigned();
                }

                if ($column['comment']) {
                    $schemaColumn = $schemaColumn->comment($column['comment']);
                }

                if (!$this->dontNeedDefaultValueType($column['type'])) {
                    $schemaColumn->default($column['default']);
                }
            }

            // 添加索引
            foreach ($this->params['indexes'] as $m => $columns) {
                foreach ($columns as $column) {
                    $table->{$m}($column);
                }
            }
        });

        if (! Schema::hasTable($schemaName)) {
            throw new FailedException(sprintf('Create Schema [%s] failed', $schemaName));
        }

        return true;
    }


    /**
     * 不需要默认值
     *
     * @param string $type
     * @time 2020年10月23日
     * @return  bool
     */
    protected function dontNeedDefaultValueType(string $type): bool
    {
        return in_array($type, [
            'blob', 'text', 'geometry', 'json',
            'tinytext', 'mediumtext', 'longtext',
            'tinyblob', 'mediumblob', 'longblob', 'enum', 'set',
            'date', 'datetime', 'time', 'timestamp', 'year'
        ]);
    }

    /**
     *
     * @time 2021年07月26日
     * @param string $type
     * @return bool
     */
    protected function setLengthType(string $type): bool
    {
        return in_array($type, [
            'char', 'string', 'decimal', 'tinytext', 'mediumtext', 'text',

            'longtext', 'tinyblob', 'mediumblob', 'longblob'
        ]);
    }
}
