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

namespace Catcher\Base;

use Catcher\Exceptions\FailedException;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * catch excel
 *
 * @time 2021年08月10日
 */
abstract class CatchExport implements FromArray, ShouldAutoSize, WithColumnWidths, WithColumnFormatting, WithCustomCsvSettings
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    protected $toCsv = false;

    /**
     * @var string
     */
    protected $driver = 'public';


    /**
     * excel headers
     *
     * @time 2021年08月10日
     * @return array
     */
    abstract public function headers(): array;

    /**
     * export data
     *
     * @time 2021年08月10日
     * @return mixed
     */
    abstract public function columns();


    /**
     * set column width
     *
     * @time 2021年08月10日
     * @return array
     */
    public function columnWidths(): array
    {
        // TODO: Implement columnWidths() method.
        return [];
    }

    /**
     * column formats
     *
     * @time 2021年08月10日
     * @return array
     */
    public function columnFormats(): array
    {
        // TODO: Implement columnFormats() method.
        return [];
    }

    /**
     * csv 设置
     *
     * @time 2021年08月10日
     * @return array
     */
    public function getCsvSettings(): array
    {
        $csvSettings = [
            'use_bom' => true,
        ];

        return $this->toCsv ? $csvSettings : [];
    }

    /**
     * implement
     *
     * @time 2021年08月10日
     * @return array
     */
    public function array(): array
    {
        // TODO: Implement array() method.
        $columns = $this->columns();

        if ($columns instanceof Arrayable) {
            $columns = $columns->toArray();
        }

        $headers = $this->headers();

        $headerKeys = array_keys($headers);

        // 如果没有设置对应的字段的 key，直接写入
        if (is_int($headerKeys[0])) {
            array_unshift($columns, $headers);

            return $columns;
        }

        // 如果设置了字段的 key， 那么就写入设置的字段值
        $data = [];

        foreach ($columns as $column) {
            $data[] = Arr::only($column, $headerKeys);
        }

        array_unshift($data, $headers);

        return $data;
    }

    /**
     * export
     *
     * @time 2021年08月10日
     * @return string
     */
    public function export(): string
    {
        /**
         * 如果你想导出 CSV 文件，那么就需要设置你当前的导出文件继承 Maatwebsite\Excel\Concerns\WithCustomCsvSettings
         *
         * 如果出现了中文乱码，那么就需要设置 boom 头
         *
         * 而且必须实现 `toCsv` 方法，返回 True 即可
         */
        $storePath = $this->getStoragePath($this->isGetLocalDriver());

        $filename = $this->getFilename();

        if (! Excel::store($this, $filename, $this->isGetLocalDriver(), $this->getFileType())) {
            throw new FailedException('Store Excel Failed');
        }

        if ($this->driver <> 'public') {
            $disk = Storage::disk($this->driver);

            $disk->put($filename, file_get_contents($storePath . $filename));

            return $disk->getUrl($filename);
        }

        return config('filesystems.disks.public.url') . '/' . $filename;
    }

    /**
     * get storage path
     *
     * @time 2021年08月10日
     * @param string $driver
     * @return Repository|Application|mixed
     */
    protected function getStoragePath(string $driver)
    {
        $storagePath =  config('filesystems.disks.' . $driver . '.root');

        if ($storagePath) {
            if (! File::isDirectory($storagePath)
                && ! File::makeDirectory($storagePath)
                && ! File::isDirectory($storagePath)
            ) {
                throw new \RuntimeException(sprintf('Directory [%s] was not created', $storagePath));
            }
        }

        return $storagePath;
    }

    /**
     * is get local driver
     *
     * @time 2021年08月10日
     * @return string
     */
    protected function isGetLocalDriver(): string
    {
        return $this->driver == 'public' ? 'public' : 'local';
    }

    /**
     * download
     *
     * @time 2021年08月10日
     * @return BinaryFileResponse
     */
    public function download(): BinaryFileResponse
    {
        return Excel::download($this, $this->getFilename(), $this->getFileType());
    }


    /**
     * get filename
     *
     * @time 2021年08月10日
     * @return string
     */
    protected function getFilename()
    {
        return Str::random(10) . '.'. strtolower($this->getFileType());
    }

    /**
     * set driver
     *
     * @time 2021年08月10日
     * @param string $driver
     * @return $this
     */
    public function setFilesystemDriver(string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }


    /**
     * 是否需要存储成 csv 文件
     *
     * @time 2019年11月26日
     * @return string
     */
    protected function getFileType(): string
    {
        return $this->toCsv ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
    }

    /**
     * 设置参数
     *
     * @time 2021年08月10日
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }
}
