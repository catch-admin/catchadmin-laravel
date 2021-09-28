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

namespace Catcher\Middleware;

use Illuminate\Http\Request;

/**
 * Class JsonResponse
 * @package Catcher\Middleware
 * @author CatchAdmin
 * @time 2021年07月25日
 */
class JsonResponse
{
    /**
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // set json response
        $request->headers->set('Accept','application/json');

        // cors
        $response = $next($request);

        return $this->cors($response);
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param $response
     * @return mixed
     */
    protected function cors($response)
    {
        foreach ($this->corsHeaders() as $header => $value) {
            $response->headers->set($header, $value);
        }

        return $response;
    }

    /**
     * cors headers
     *
     * @time 2021年08月23日
     * @return array
     */
    protected function corsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Request-Method' => ['GET, POST, PUT, DELETE, OPTIONS'],
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Request-Headers' => '*',
            'Access-Control-Max-Age' => 86400,
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Allow-Headers' => '*'
        ];
    }
}
