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
namespace Catcher\Support\Form\Fields;

use Catcher\Support\Form\Element\Components\Upload;

class ImageUpload extends Upload
{
    /**
     * make
     *
     * @time 2021年08月09日
     * @param string $name
     * @param string $title
     * @param string $action
     * @param bool $auth
     * @return Upload|ImageUpload
     */
    public static function make(string $name, string $title, string $action, bool $auth = true)
    {
        $imageUpload = new self($name, $title);

        $imageUpload = $imageUpload->uploadType(Upload::TYPE_IMAGE)
                                   ->uploadName($name)
                                   ->action($action)
                                   ->data(['none' => ''])
                                   ->accept('image/*');

        if ($auth) {
            return $imageUpload->authorization();
        }

        return $imageUpload;
    }
}
