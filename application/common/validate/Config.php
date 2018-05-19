<?php
namespace app\common\validate;

class Config extends Base
{
    protected $rule = [
        'name'  => 'require|unique:config',
        'title' => 'require',
    ];
    protected $message = [
        'name.require' => '配置标识必须',
        'name.unique'  => '配置标识已经存在',
        'title'        => '配置名称必须',
    ];
    protected $scene = [
        'add'  => ['name', 'title'],
        'edit' => ['title']
    ];
}