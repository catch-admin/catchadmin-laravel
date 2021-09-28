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

namespace Catcher\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CatchException extends HttpException
{
    protected $code = 0;

    public function __construct(string $message = '', $code = 0)
    {
        if ($code) {
            $this->code = $code;
        }

        parent::__construct(self::statusCode(), $message ? : $this->message, null, [], $code ? : $this->code);
    }

    /**
     * success
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return int
     */
    protected function statusCode(): int
    {
        return 200;
    }

    /**
     * render
     *
     * @return array
     *@author CatchAdmin
     * @time 2021年07月25日
     */
    public function render(): array
    {
        return [
            'code' => $this->code,

            'message' => $this->message
        ];
    }
}
