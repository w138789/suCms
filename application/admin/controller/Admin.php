<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Base;

class Admin extends Base
{

    public function _initialize()
    {
        parent::_initialize();

        if (!is_login() && !in_array($this->url, ['admin/index/login', 'admin/index/logout', 'admin/index/verify']))
        {
            $this->redirect('admin/index/login');
        }
    }
}

