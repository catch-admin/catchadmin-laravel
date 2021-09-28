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
use Catcher\Support\Utils;
use Catcher\Traits\DB\BaseOperate;
use Catcher\Traits\DB\Trans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use JaguarJack\Generate\Build\Value;
use JaguarJack\Generate\Exceptions\GenerateFailedExceptions;
use JaguarJack\Generate\Exceptions\TypeNotFoundException;
use JaguarJack\Generate\Generator;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\Property;
use Catcher\Base\CatchModel;
use JaguarJack\Generate\Types\Array_;
use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\ArrayItem;

class CreateModel extends Creator
{
    /**
     * @var mixed
     */
    protected $softDelete;

    /**
     * @var string
     */
    protected $table;

    /**
     * generate
     *
     * @param string $name
     * @return string
     * @throws TypeNotFoundException
     * @throws GenerateFailedExceptions
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    public function generate(string $name): string
    {
        $modelName = ucfirst($name);

        if ( ! $this->module) {
            throw new FailedException('should set module first');
        }

        Generator::namespace(trim(CatchAdmin::getModuleModelNamespace($this->module), '\\'))
            ->class($modelName, function (Class_ $class, Generator $generator) {
                $class->extend('Model');

                if (DB::table($this->table)->exists()) {
                    // 设置 class comment
                    $class->setDocComment($this->buildColumnComment());

                    $generator->property('table', function (Property $property) {
                        return $property->setDefault($this->table);
                    });

                    // 设置 name 属性
                    $generator->property('fillable', function (Property $property) {
                        return $property->setDefault($this->getFillable());
                    });
                }
            })
            ->uses([
                $this->softDelete ? CatchModel::class . ' as Model' : Model::class
            ])
            ->when(! $this->softDelete, function (Generator $generator){
                $generator->traits([
                    Trans::class,
                    BaseOperate::class
                ]);
            })
            ->file($modelName, CatchAdmin::getModuleModelPath($this->module));

        if (! File::exists(CatchAdmin::getModuleModelPath($this->module) . $modelName . '.php')) {
            throw new FailedException('Generate Model Failed');
        }

        return CatchAdmin::getModuleModelPath($this->module) . $modelName . '.php';
    }

    /**
     * 提供模型字段属性提示
     *
     * @time 2021年04月27日
     * @return string
     */
    protected function buildColumnComment(): string
    {
        $columns = Schema::getColumnListing($this->table);

        $comment = PHP_EOL . '/**' . PHP_EOL;

        foreach ($columns as $column) {
            $comment .= sprintf(' * @property $%s', $column) . PHP_EOL;
        }

        $comment .= ' */';

        return $comment;
    }

    /**
     * fillbale
     *
     * @time 2021年08月02日
     * @return Array_
     */
    protected function getFillable(): Array_
    {
        $columns = Schema::getColumnListing($this->table);

        $fetchItems = [];

        foreach ($columns as $column) {
            $item = new ArrayItem(Value::fetch($column), null);

            $comment = DB::connection()->getDoctrineColumn(Utils::withTablePrefix($this->table), $column)->getComment();

            $item->setDocComment(new Doc(sprintf('// %s', $comment ? : '' )));

            $fetchItems[] = $item;
        }

        return new Array_($fetchItems);
    }

    /**
     * set softDelete
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param $softDelete
     * @return $this
     */
    public function setSoftDelete($softDelete): CreateModel
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    /**
     * set table
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param string $table
     * @return $this
     */
    public function setTable(string $table): CreateModel
    {
        $this->table = $table;

        return $this;
    }
}
