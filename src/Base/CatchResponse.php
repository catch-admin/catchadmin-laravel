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

use Catcher\Support\Code;
use Illuminate\Pagination\LengthAwarePaginator;

class CatchResponse
{
    /**
     *
     * @param mixed $data
     * @param string $msg
     * @param int $code
     * @return array
     *@author CatchAdmin
     * @time 2021年07月25日
     */
    public static function success($data, string $msg = 'success', int $code = Code::SUCCESS): array
    {
        return [
            'code' => $code,
            'data' => $data,
            'message' => $msg
        ];
    }


    /**
     * failed
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param mixed $data
     * @param string $msg
     * @param int $code
     * @return array
     */
    public static function failed($data, string $msg = 'success', int $code = Code::FAILED): array
    {
        return [
            'code' => $code,
            'data' => $data,
            'message' => $msg
        ];
    }

    /**
     * paginate
     *
     * @param LengthAwarePaginator $page
     * @return array
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    public static function paginate(LengthAwarePaginator $page): array
    {
        return [
            'code'    => Code::SUCCESS,
            'message' => 'success',
            'count'   => $page->count(),
            'current' => $page->currentPage(),
            'limit'   => $page->perPage(),
            'data'    => $page->getCollection(),
        ];
    }
}
