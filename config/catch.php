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

return [
    /*
    |--------------------------------------------------------------------------
    | catch-admin default middleware
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'middleware_group' => [env('CATCH_AUTH_MIDDLEWARE_ALIAS')],

    /*
    |--------------------------------------------------------------------------
    | catch-admin catch_auth_middleware_alias
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'catch_auth_middleware_alias' => env('CATCH_AUTH_MIDDLEWARE_ALIAS'),

    /*
    |--------------------------------------------------------------------------
    | catch-admin super admin id
    |--------------------------------------------------------------------------
    |
    | where you can set super admin id
    |
    */
    'super_admin' => env('CATCH_SUPER_ADMIN', 1),

    /*
    |--------------------------------------------------------------------------
    | catch-admin module setting
    |--------------------------------------------------------------------------
    |
    | the root where module generate
    | the namespace is module root namespace
    | the default dirs is module generate default dirs
    */
    'module' => [
        'root' => base_path(env('CATCH_ROOT', 'catch')),

        'namespace' => env('CATCH_NAMESPACE', 'CatchAdmin'),

        'default_dirs' => [
            'Http' . DIRECTORY_SEPARATOR,

            'Http' . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR,

            'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR,

            'Models' . DIRECTORY_SEPARATOR,

            'Build' . DIRECTORY_SEPARATOR,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | catch-admin exception handle setting
    |--------------------------------------------------------------------------
    */
    'exception' => [
         /**
          * A list of the exception types that are not reported.
          */
        'dont_report' => [

        ],

        /**
         * A list of the inputs that are never flashed for validation exceptions.
         */
        'dont_flash' => [

        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | catch-admin exception handle setting
    |--------------------------------------------------------------------------
    | if you need publish module views to vue project views
    | you should set it
    */
    'front_views_path' => '',

    /*
    |--------------------------------------------------------------------------
    | catch-admin auth guard name
    |--------------------------------------------------------------------------
    */
    'guard' => env('CATCH_GUARD_NAME', 'catch_admin'),

    /*
    |--------------------------------------------------------------------------
    | catch-admin auth setting
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'guards' => [
            env('CATCH_GUARD', 'catch_admin') =>  [
                'driver' => 'jwt',
                'provider' => 'admin_users',
            ],
        ],

        'providers' => [
            'admin_users' => [
                'driver' => 'eloquent',
                'model' => \CatchAdmin\Permissions\Models\Users::class,
            ]
        ]
    ]
];
