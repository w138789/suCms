<?php
namespace app\admin\controller;

use app\common\controller\Base;

class WeixinMenu extends Base
{
    public $wechatObj;

    public function __construct()
    {
        parent::__construct();
        $this->model = model('WeixinMenu');
        $this->wechatObj = new \weixin\Wxapi();
    }

    public function index()
    {
        $menu = $this->model->menu_stree();
        $count = $this->model->where('parent_id = 0')->count();
        $data = '{"button":[';
        foreach ($menu as $k => $v)
        {
            if (empty($v['arr']))
            {
                if (empty($v['url']))
                {
                    $data .= '{"type":"click","name":"' . $v['title'];
                    $data .= '","key":"' . $v['key'] . '"}';
                } else
                {
                    $data .= '{"type":"view","name":"' . $v['title'];
                    $data .= '","url":"' . $v['url'] . '"}';
                }
            } else
            {
                $data .= '{"name":"' . $v['title'] . '","sub_button":[';
            }
            foreach ($v['arr'] as $ks => $vs)
            {
                $counts = $this->model->where('parent_id = ' . $v['id'])->count();
                if (empty($vs['url']))
                {
                    $data .= '{"type":"click","name":"' . $vs['title'];
                    $data .= '","key":"' . $vs['key'] . '"}';
                } else
                {
                    $data .= '{"type":"view","name":"' . $vs['title'];
                    $data .= '","url":"' . $vs['url'] . '"}';
                }
                if ($counts == $ks + 1)
                {
                    $data .= ']}';
                } else
                {
                    $data .= ',';
                }
            }
            if ($count == $k + 1)
            {
                $data .= '';
            } else
            {
                $data .= ',';
            }
        }
        $data .= ']}';
        $access_token = $this->wechatObj->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
        $data = $this->wechatObj->post_curl($url, $data);
        $json = json_decode($data, TRUE);
        if (array_key_exists('errcode', $json) && $json['errcode'] != 0)
        {
            $this->error('添加失败: 错误信息: ' . $json['errmsg']);
        } else
        {
            $this->success('添加成功');
        }
    }
}