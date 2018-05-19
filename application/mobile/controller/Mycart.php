<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

class Mycart extends Common
{

    public function index()
    {
        $map  = [
            'user_id'    => $this->userId,
            'is_default' => 1,
        ];
        $addr = model('Address')->where($map)->find();
        if (empty($addr)) $this->redirect('address/index');
        $province = model('Region')->where(['id' => $addr['province']])->value('name');
        $address  = $province . ' ';

        $city    = model('Region')->where(['id' => $addr['city']])->value('name');
        $address .= $city . ' ';

        $district = model('Region')->where(['id' => $addr['district']])->value('name');
        $address  .= $district . ' ' . $addr['address'];

        $mycart = $this->model->where(['user_id' => $this->userId])->select();
        //print_r($mycart); exit;
        foreach ($mycart as $k => $v)
        {
            $mycart[$k]              = db('Goods')->field('id, goods_name, shop_price, goods_thumb')->where(['id' => $v['goods_id']])->find();
            $mycart[$k]['goods_num'] = $v['goods_num'];
        }
        //print_r($mycart); exit;
        $goods_prices = 0;
        foreach ($mycart as $v)
        {
            $goods_prices = $goods_prices + $v['shop_price'] * $v['goods_num'];
        }
        $this->assign('addr', $addr);
        $this->assign('goods_prices', $goods_prices);
        $this->assign('address', $address);
        $this->assign('mycart', $mycart);
        return $this->fetch();
    }

    public function add()
    {
        $goods_id  = input('id');
        $goods_number = input('goods_number');
        $map       = [
            'user_id'  => $this->userId,
            'goods_id' => $goods_id,
        ];
        $mycart    = $this->model->where($map)->find();
        if ($mycart)
        {
            $goods_nums = $mycart['goods_num'] + $goods_number;
            $data       = [
                'goods_num' => $goods_nums
            ];
            $this->model->where($map)->update($data);
        } else
        {
            $data = [
                'user_id'   => $this->userId,
                'goods_id'  => $goods_id,
                'goods_num' => $goods_number,
            ];
            $this->model->save($data);
        }
        if (!IS_POST)
        {
            $this->redirect('mycart/index');
        } else
        {
            return [
                'status' => '1',
                'msg'    => '添加成功'
            ];
        }
    }

    public function del()
    {
        $goods_id = input('goods_id');
        if (empty($goods_id)) return false;
        //echo $goods_id; exit;
        $this->model->where(['goods_id' => $goods_id, 'user_id' => $this->userId])->delete();
        $this->redirect('mycart/index');
    }

    public function delAll()
    {
        $this->model->where(['user_id' => $this->userId])->delete();
        $this->redirect('mycart/index');
    }
}
