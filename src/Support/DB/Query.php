<?php

namespace Catcher\Support\DB;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Query
{
    protected static $log = null;

    /**
     * log
     *
     * @return void
     * @author CatchAdmin
     * @time 2021年08月09日
     */
    public static function listen()
    {
        DB::listen(function ($query) {
            $sql = str_replace('?', '%s',
                sprintf('[%s] ' . $query->sql . ' | %s ms' . PHP_EOL, date('Y-m-d H:i'), $query->time));

            static::$log .= vsprintf($sql, $query->bindings);
        });
    }


    /**
     * log
     *
     * @author CatchAdmin
     * @time 2021年08月12日
     * @param bool $latest
     * @return void
     */
    public static function log(bool $latest = true)
    {
        if (static::$log) {
            $sqlLogPath = storage_path('logs' . DIRECTORY_SEPARATOR . 'query' . DIRECTORY_SEPARATOR);

            if (!File::isDirectory($sqlLogPath)) {
                File::makeDirectory($sqlLogPath, 0777, true);
            }

            $logFile = $sqlLogPath . date('Ymd') . '.log';

            if (! File::exists($logFile)) {
                File::put($logFile, '', true);
            }

            // 兼容 Octane，防止多进程争抢，需要 Lock
            $latest ? file_put_contents($logFile, static::$log . PHP_EOL . File::sharedGet($logFile), LOCK_EX) :

                file_put_contents($logFile,static::$log . PHP_EOL, LOCK_EX|FILE_APPEND);

            static::$log = null;
        }
    }
}
