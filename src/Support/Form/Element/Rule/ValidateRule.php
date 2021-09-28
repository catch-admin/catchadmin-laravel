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

namespace Catcher\Support\Form\Element\Rule;

use Illuminate\Support\Str;

trait ValidateRule
{
    /**
     * 组件验证规则
     *
     * @var array
     */
    protected $validate = [];

    /**
     * validate
     *
     * @time 2021年08月11日
     * @param string $validates
     * @return $this
     */
    public function validate(string $validates): self
    {
        $validateRules = $this->validateRules();

        $validates = explode('|', $validates);

        foreach ($validates as $validate) {
            if (Str::contains($validate, ':')) {
                $this->appendValidate(ValidateFactory::validatePattern(explode(':', $validate)[1]));
            } else {
                if (isset($validateRules[$validate])) {
                    $this->appendValidate($validateRules[$validate]);
                }
            }
        }

        return $this;
    }

    /**
     * @param $validate
     * @return $this
     */
    public function appendValidate($validate): self
    {
        $this->validate[] = $validate;

        return $this;
    }

    public function appendValidates(array $validates):self
    {
        $this->validate = array_merge($this->validate, $validates);

        return $this;
    }

    public function getValidate(): array
    {
        return $this->validate;
    }

    protected function parseValidate(): array
    {
        $validate = [];

        foreach ($this->validate as $value) {
            $validate[] = method_exists($value, 'getValidate') ? $value->getValidate() : $value;
        }

        return $validate;
    }

    public function parseValidateRule(): array
    {
        if (!count($this->validate)) {
            return [];
        }

        return [
            'validate' => $this->parseValidate()
        ];
    }


    /**
     * validate rule
     *
     * @time 2021年08月11日
     * @return array
     */
    protected function validateRules(): array
    {
        return [
            'string' => ValidateFactory::validateStr(),
            'array' => ValidateFactory::validateArr(),
            'number' => ValidateFactory::validateNum(),
            'date' => ValidateFactory::validateDate(),
            'int' => ValidateFactory::validateInt(),
            'float' => ValidateFactory::validateFloat(),
            'object' => ValidateFactory::validateObject(),
            'email' => ValidateFactory::validateEmail(),
            'enum' => ValidateFactory::validateEnum(),
            'url' => ValidateFactory::validateUrl(),
            'hex' => ValidateFactory::validateHex(),
            'pattern' => '',
            'alpha' => ValidateFactory::validateAlpha(),
            'alphaNum' => ValidateFactory::validateAlphaNum(),
            'alphaDash' => ValidateFactory::validateAlphaDash(),
            'mobile' => ValidateFactory::validateMobile(),
            'idCard' => ValidateFactory::validateIdCard(),
            'zip' => ValidateFactory::validateZip(),
            'ip' => ValidateFactory::validateIp(),
            'landLine' => ValidateFactory::validateLandLine(),
            'password' => ValidateFactory::validatePassword(),
            'strongPassword' => ValidateFactory::validateStrongPassword(),
            'chinese' => ValidateFactory::validateChineseCharacter(),
        ];
    }
}
