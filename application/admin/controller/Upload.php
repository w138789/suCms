<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Upload extends Admin {
    public function __construct()
    {
        $this->auto=false;
        parent::__construct();
    }

    public function _empty() {
        $controller = controller('common/Upload');
        $action     = ACTION_NAME;
        return $controller->$action();
    }
}