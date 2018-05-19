<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

class Index extends Common
{

    //网站首页
    public function index()
    {
        $ads   = model('Ad')
            ->where(['place_id' => 3])
            ->select();
        $goods = model('Goods')->where(['is_recommend' => 1, 'status' => 1])->select();
        $navs  = model('Channel')->where(['status' => 1])->select();
        $this->assign('ads', $ads);
        $this->assign('goods', $goods);
        $this->assign('navs', $navs);
        return $this->fetch();
    }
}
