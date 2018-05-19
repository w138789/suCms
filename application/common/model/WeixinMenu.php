<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;

class WeixinMenu extends Model
{
    public function menu_stree()
    {
        $menu = $this->order('id asc')->select();
        return $this->stree($menu, '0');
    }

    public function stree($arr, $id)
    {

        $subs = array(); // 子孙数组
        $ks = 0;
        foreach ($arr as $v)
        {
            if ($v['parent_id'] == $id)
            {
                $ks++;
                $subs[] = $v;
                $subs[$ks - 1]['arr'] = $this->stree($arr, $v['id']);
            }
        }
        return $subs;
    }
}