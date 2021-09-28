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

namespace Catcher\Commands\Tools;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Zip\Zipper;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Region extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:region {--rollback}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create region schema ';


    protected $regionTable = 'region';

    public function handle()
    {
        if ($this->option('rollback')) {
            if (Schema::hasTable($this->regionTable)) {
                Schema::drop($this->regionTable);
            }

            $this->info('drop ' . $this->regionTable . ' successful');
        } else {
            if (! Schema::hasTable($this->regionTable)) {
                try {
                    if ($this->createRegionSchema()) {
                        $this->importRegionData();
                    }
                } catch (\Exception $exception) {
                    Schema::drop($this->regionTable);
                    $this->error($exception->getMessage());
                    exit;
                }
            }

            $this->info(sprintf(PHP_EOL . 'create %s successful', $this->regionTable));
        }
    }

    /**
     *
     * @time 2021年07月31日
     * @return bool
     */
    protected function createRegionSchema(): bool
    {
        Schema::create($this->regionTable, function (Blueprint $table){
           $table->id();

           $table->integer('parent_id')->default(0)->comment('父级');

           $table->tinyInteger('level')->default(1)->comment('等级');

           $table->string('name', 50)->default('')->comment('地区名称');

           $table->string('initial', 100)->default('')->comment('首字母');

           $table->string('pinyin', 100)->default('')->comment('拼音');

           $table->string('city_code', 100)->default('')->comment('城市编码');

           $table->string('ad_code', 100)->default('')->comment('区域编码');

           $table->string('lng_lat', 100)->default('')->comment('中心经纬度');

           $table->index(['level']);
        });

        return Schema::hasTable($this->regionTable);
    }


    protected function importRegionData()
    {
        $regionZipUrl = 'http://json.think-region.yupoxiong.com/region.json.zip';

        $regionZip = storage_path() . DIRECTORY_SEPARATOR . 'region.zip';
        $regionJson = storage_path() . DIRECTORY_SEPARATOR . 'region.json';

        File::put($regionZip, file_get_contents($regionZipUrl));

        $zipper = new Zipper();
        $zipper->make($regionZip)->extractTo(storage_path());
        $zipper->close();

        if (File::exists($regionJson)) {
            $region = \json_decode(File::get($regionJson), true);

            $bar = $this->output->createProgressBar(count($region));

            $bar->start();

            Collection::make($region)->chunk(500)->each(function ($items) use ($bar){
                $data = [];
                $items->each(function (&$item) use (&$data){
                    $item['city_code'] = $item['citycode'];
                    $item['ad_code'] = $item['adcode'];
                    unset($item['citycode'], $item['adcode']);
                    $data[] = $item;
                });

                DB::table($this->regionTable)->insert($data);

                $bar->advance(500);
            });

            $bar->finish();

            File::delete($regionZip);
            File::delete($regionJson);
        } else {
            throw new FailedException('Download Json File failed');
        }
    }
}
