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
        defined('MODULE_NAME') or define('MODULE_NAME', $this->request->module());
        defined('CONTROLLER_NAME') or define('CONTROLLER_NAME', $this->request->controller());
        defined('ACTION_NAME') or define('ACTION_NAME', $this->request->action());
        defined('IS_GET') or define('IS_GET', $this->request->isGet());
        defined('IS_POST') or define('IS_POST', $this->request->isPost());
        $this->url = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
    }
}

