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

namespace Catcher\Support\Table;

/**
 *
 * @time 2021年09月18日
 */
class Export
{
    protected $table;

    public function __construct(CatchTable $table)
    {
        $this->table = $table;
    }

    /**
     * export 处理
     *
     * @time 2021年09月18日
     * @return mixed
     */
    public function export()
    {
        $headers = $this->table->create()['headers'];

        // delete the field which not export
        foreach ($headers as $k => $header) {
            if (! isset($header['prop'])) {
               unset($headers[$k]);
            }

            if (isset($header['export']) && ! $header['export']) {
                unset($headers[$k]);
            }
        }

        $headersName = $_headers = [];
        foreach ($headers as $header) {
            $headersName[] = $header['label'];
            $_headers[$header['prop']] = $header;
        }

        return $this->table->exportData()
            ->transform(function ($item) use ($_headers){
                $data = [];

                foreach ($_headers as $field => $value) {
                    if (isset($value['export']) && $value['export'] instanceof \Closure) {
                        $data[] = call_user_func($value['export'], $item->$field, $item);
                    }  else {
                        $data[] = $item->$field;
                    }
                }

                return $data;
            })->export($headersName);
    }


    public function __invoke()
    {
        return $this->export();
    }
}
