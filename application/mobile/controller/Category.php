<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

class Category extends Common {

    /**
     * 分类首页
     * @return mixed
     */
	public function index() {
        $category = $this->model->select();
        $categorys = $this->model->stree($category, '0');
        $this->assign('categorys', $categorys);
        return $this->fetch();
	}
}
