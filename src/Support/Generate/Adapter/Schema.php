<?php

namespace Catcher\Support\Generate\Adapter;


use Illuminate\Support\Str;

class Schema extends Adapter
{
    protected $indexes = [];

    /**
     * @time 2021年07月27日
     * @return array
     */
    public function toLaravel(): array
    {
        $this->origin = \json_decode($this->origin, true);

        return [
            'engine' => $this->origin['extra']['engine'],

            'comment' => $this->origin['extra']['comment'],

            'primary_key' => $this->origin['extra']['primary_key'],

            'columns' => $this->columns($this->origin['table_fields']),

            'indexes' => $this->indexes
        ];
    }

    /**
     * columns
     *
     * @time 2021年07月27日
     * @param array $columns
     * @return array
     */
    protected function columns(array $columns): array
    {
        // 初始化 indexes 数组
        $indexes = [];

        foreach ($columns as &$column) {
            $column['type'] = $this->typeToMigrationMethod($column['type']);

            $column['nullable'] = $column['nullable'] ?? false;

            $column['unsigned'] = $column['unsigned'] ?? false;

            // 单独存储 index
            if ($column['index']) {
                $indexes[$column['index']][] = $column['field'];
            }

            // 整形的默认为 0
            if (! $column['default']) {
                if (Str::contains(strtolower($column['type']), 'integer')) {
                    $column['default'] = 0;
                }
            }
        }

        // 重新赋值给属性 indexes, 防止内存泄露
        $this->indexes = $indexes;

        if ($this->origin['extra']['created_at']) {
            $columns[] = $this->extraColumns('created_at');
            $columns[] = $this->extraColumns('updated_at');
        }

        if ($this->origin['extra']['creator_id']) {
            $columns[] = $this->extraColumns('creator_id');
        }

        if ($this->origin['extra']['deleted_at']) {
            $columns[] = $this->extraColumns('deleted_at');
        }

        return $columns;
    }

    /**
     *
     * @time 2021年07月27日
     * @param string $type
     * @return string
     */
    protected function typeToMigrationMethod(string $type): string
    {
        $methods = [
            'bigint' => 'bigInteger',
            'int' => 'integer',
            'mediumint'=> 'mediumInteger',
            'tinyint' => 'tinyInteger',
            'varchar' => 'string',
            'datetime' => 'dateTime',
            'tinyblob' => 'binary',
            'blob' => 'binary',
            'mediumblob' => 'binary',
            'longblob' => 'binary',
        ];

        if (isset($methods[$type])) {
            return $methods[$type];
        }

        return $type;
    }

    /**
     * extra columns
     *
     * @time 2021年07月27日
     * @param $field
     * @return array
     */
    protected function extraColumns($field): array
    {
        return [
            'type' => 'integer',
            'unsigned' => true,
            'default' => 0,
            'nullable' => false,
            'field' => $field
        ];
    }
}
