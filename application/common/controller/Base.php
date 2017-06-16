<?php

namespace app\common\controller;

use think\Controller;
use app\common\model;

class Base extends \think\Controller
{
    protected $url = '';

    public function _initialize()
    {
        if (!is_file(APP_PATH . 'database.php') || !is_file(APP_PATH . 'install.lock'))
        {
            return $this->redirect('install/index/index');
        }
        /* 读取数据库中的配置 */
        $config = cache('db_config_data');
        if (!$config) {
            $config = model('Config')->lists();
            cache('db_config_data', $config);
        }
        config($config);
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

