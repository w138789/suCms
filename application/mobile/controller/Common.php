<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

use app\common\controller\Fornt;

class Common extends Fornt
{
    public $title = 'sucms';
    public function __construct()
    {
        parent::__construct();
        $noLogin = [
            'Index',
            'Category',
            'Login',
            'Goods',
        ];
        if (empty($this->userId) && empty(in_array(CONTROLLER_NAME, $noLogin)))
        {
            $this->redirect('login/index');
        }

        //设置标题
        $this->assign('title', $this->title);
    }
}
