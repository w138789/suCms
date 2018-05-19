<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\mobile\controller;

use pay\wxpay\Wxpay;

class Shopping extends Common
{

    public function index()
    {
        $goods = model('Mycart')->where(['user_id' => $this->userId])->select();
        if (empty($goods)) return false;
        $order              = array();
        $order['order_sn']  = date('YmdHis') . mt_rand(1, 10);
        $order['user_id']   = $this->userId;
        $addr               = model('Address')->where(['user_id' => $this->userId, 'is_default' => 1])->find();
        $order['consignee'] = $addr['consignee'];
        $order['province']  = $addr['province'];
        $order['city']      = $addr['city'];
        $order['district']  = $addr['district'];
        $order['address']   = $addr['address'];
        $order['mobile']    = $addr['mobile'];
        model('OrderInfo')->save($order);
        $order_id             = model('OrderInfo')->getLastInsID();
        $order['shop_prices'] = 0;
        foreach ($goods as $k => $v)
        {
            $good = model('Goods')->field('shop_price, goods_name')->where(['id' => $v['goods_id']])->find();
            //print_r($good); exit;
            $order['shop_prices']       += $good['shop_price'] * $v['goods_num'];
            $order_goods['goods_id']    = $v['goods_id'];
            $order_goods['order_id']    = $order_id;
            $order_goods['goods_name']  = $good['goods_name'];
            $order_goods['goods_num']   = $v['goods_num'];
            $order_goods['goods_price'] = $good['shop_price'];
            model('OrderGoods')->save($order_goods);
        }
        model('OrderInfo')->save($order, ['order_id' => $order_id]);
        model('Mycart')->where(['user_id' => $this->userId])->delete();
        //跳到支付
        $this->redirect(url('shopping/pay', ['order_id'=> $order_id]));
    }

    public function pay()
    {
        $order_id          = input('order_id');
        $order             = model('OrderInfo')->where(['order_id' => $order_id])->find();
        $datas['order_sn'] = $order['order_sn'];
        $datas['money']    = $order['shop_prices'];
        $pay = new Wxpay();
        $data = $pay->pay($datas);
        /*$data = [
            'jsApiParameters' => '',
            'editAddress'     => '',
        ];*/
        $this->assign('jsApiParameters', $data['jsApiParameters']);
        $this->assign('editAddress', $data['editAddress']);
        $this->assign('order', $order);
        return $this->fetch();
    }

    public function paySuccess()
    {
        $order['order_sn'] = input('order_sn');
        $this->assign('order', $order);
        $this->assign('web', '支付成功通知页');
        return $this->fetch();
    }
}
