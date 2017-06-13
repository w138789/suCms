<?php

namespace app\common\controller;

use think\Controller;

class Base extends Controller
{
    protected $url = '';
    public function _initialize()
    {
        if (!is_file(APP_PATH . 'database.php') || !is_file(APP_PATH . 'install.lock'))
        {
            return $this->redirect('install/index/index');
        }
        //获取request信息
        $this->requestInfo();
    }

    public function requestInfo()
    {
        $this->url = strtolower($this->request->module() . '/'.$this->request->controller() . '/'.$this->request->action());
    }
}
