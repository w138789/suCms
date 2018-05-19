<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\index\controller;
use app\common\controller\Fornt;
use think\Request;

class Test extends Fornt {
    public function __construct(Request $request = null)
    {
        $this->auto=false;
        parent::__construct($request);
    }

    //网站首页
	public function index() {		//设置SEO
		echo config('weixin_appid');
	}
}
