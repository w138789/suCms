<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

class Goods extends Common
{

    /**
     * 商品详情
     * @return mixed
     */
    public function index()
    {
        $goods_id = input('goods_id');
        $goods = $this->model->where(['id'=>$goods_id,'status' => 1])->find();
        $images = explode(',',$goods['images']);
        $this->assign('goods', $goods);
        $this->assign('images', $images);
        return $this->fetch();
    }

    /**
     * 商品列表
     * @return mixed
     */
    public function lists()
    {
        $category_id = input('category_id');
        $goods       = $this->model->where(['category_id' => $category_id, 'status' => 1])->select();
        //print_r($goods);
        $this->assign('goods', $goods);
        return $this->fetch();
    }
}
