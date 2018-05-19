<?php

namespace app\admin\controller;

use weixin\Wxapi;

class Weixin
{
    public $wechatObj;

    public function __construct()
    {
        $this->wechatObj = new Wxapi();
    }

    public function index()
    {
        define('TOKEN', config('weixin_token'));
        $echoStr = input('echostr');
        if (!empty($echoStr))
        {
            $this->wechatObj->valid();
        } else
        {
            //回复文本消息
            /*$data['content'] = '123456';
            $this->wechatObj->responseMsg($data, 'text');*/
            //回复图文消息
            $data[0]['title']       = '什么东西';
            $data[0]['description'] = '没什么';
            $data[0]['picurl']      = 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=3636485369,93076635&fm=173&s=29413A6EC05AADD4DECC2C1F0000C081&w=218&h=146&img.JPEG';
            $data[0]['url']         = 'http://www.baidu.com';
            $data[1]['title']       = '无论';
            $data[1]['description'] = '吕大';
            $data[1]['picurl']      = 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=3636485369,93076635&fm=173&s=29413A6EC05AADD4DECC2C1F0000C081&w=218&h=146&img.JPEG';
            $data[1]['url']         = 'http://www.qq.com';
            $this->wechatObj->responseMsg($data, 'news');
        }
    }

    public function kf()
    {
        $openid = 'o32w2s7x5ySr_2wGd7BICmjfe2P4';
        //发送文本消息
        /*        $data['content'] = '456';
                $data['type'] = 'text';*/
        //发送图文消息
        $data['type']                   = 'news';
        $data['news'][0]['title']       = '789';
        $data['news'][0]['description'] = '555';
        $data['news'][0]['url']         = 'http://www.qq.com';
        $data['news'][0]['picurl']      = 'http://su1.sujianxun.com/upload/ad/2016-08-27/2016082711592810.jpg';
        $data['news'][1]['title']       = 'ggg';
        $data['news'][1]['description'] = '22';
        $data['news'][1]['url']         = 'http://www.baidu.com';
        $data['news'][1]['picurl']      = 'http://su1.sujianxun.com/upload/ad/2016-08-27/2016082711592810.jpg';
        $code                           = $this->wechatObj->kefu($openid, $data);
        p($code);
    }

}