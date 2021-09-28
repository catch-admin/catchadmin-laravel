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

use Catcher\Support\Form\Element\Driver\FormComponent;
use Illuminate\Support\Facades\Request;

class Editor extends FormComponent
{
    protected $defaultProps = [
        'type' => 'editor'
    ];

    protected $uploadImageUrl;

    protected $defaultPlugins = ['advlist anchor autolink autosave lists code codesample colorpicker colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists media nonbreaking noneditable pagebreak paste preview print save searchreplace spellchecker tabfocus table template textcolor textpattern visualblocks visualchars wordcount'];

    protected $defaultToolbars = ['searchreplace bold italic underline strikethrough alignleft aligncenter alignright outdent indent  blockquote undo redo removeformat subscript superscript code codesample', 'hr bullist numlist link image charmap preview anchor pagebreak insertdatetime media table emoticons forecolor backcolor fullscreen'];

    public static function make(string $name, string $title)
    {
        return new self($name, $title);
    }

    /**
     * 初始化
     *
     * @time 2021年04月11日
     * @return Editor|void
     */
    protected function init(): Editor
    {
        return $this->plugins()
            ->toolbars()
            ->language()
            ->initContent()
            ->uploadConf();
    }

    public function createValidate()
    {
        // TODO: Implement createValidate() method.
        return self::validateStr();
    }


    /**
     * set plugins
     *
     * @time 2021年04月11日
     * @param array $plugins
     * @return Editor
     */
    public function plugins(array $plugins = []): Editor
    {
        $this->props([
            'plugins' => count($plugins) ? $plugins : $this->defaultPlugins,
        ]);

        return $this;
    }


    /**
     * set toolbars
     *
     * @time 2021年04月11日
     * @param array $toolbars
     * @return Editor
     */
    public function toolbars(array $toolbars = []): Editor
    {
        $this->props([
            'toolbar' => count($toolbars) ? $toolbars : $this->defaultToolbars,
        ]);

        return $this;
    }

    /**
     * 设置语言
     * 支持 'es_MX', 'en', 'zh_CN', 'ja'
     * @time 2021年04月11日
     * @param string $language
     * @return $this
     */
    public function language(string $language = 'zh'): Editor
    {
        $this->props([
            'language' => $language
        ]);

        return $this;
    }


    /**
     * 编辑器高度
     *
     * @time 2021年04月11日
     * @param int $height
     * @return $this
     */
    public function height(int $height = 500): Editor
    {
        $this->props([
            'height' => $height
        ]);

        return $this;
    }

    /**
     * 上传图片接口
     *
     * @time 2021年08月09日
     * @param string $url
     * @return $this
     */
    public function uploadImageUrl(string $url): Editor
    {
        $this->uploadImageUrl = $url;

        return $this;
    }

    /**
     * 编辑器默认内容
     *
     * @time 2021年04月11日
     * @param string $content
     * @return $this
     */
    public function initContent(string $content = ''): Editor
    {
        $this->props([
            'initContent' => $content
        ]);

        return $this;
    }

    /**
     * 上传配置
     *
     * @time 2021年04月11日
     * @param int $size
     * @return $this
     */
    public function uploadConf(int $size = 10): Editor
    {
        $this->props([
            'uploadConf' => array_merge([
                'url' => $this->uploadImageUrl,
                'size' => $size,
            ], [
                'authorization' => Request::header('authorization')
            ])
        ]);

        return $this;
    }
}
